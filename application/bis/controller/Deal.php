<?php
namespace app\bis\controller;
use think\Controller;

class Deal extends Base
{
    /**商户中心 团购列表页
     * @return mixed
     */
    public function index(){
        return '团购列表';
        return $this->fetch();
    }

    public function add(){
        $bisId = $this->getLoginUser()->bis_id; //商户管理员

        if (request()->isPost()){
            //走插入逻辑
            $data = input('post.');
            //检验提交的数据

            $location = model('BisLocation')->get($data['location_idx'][0]);
            $deals = [
                'bis_id'=>$bisId,
                'name'=>$data['name'],
                'image'=>$data['image'],
                'category_id'=>$data['category_id'],
                'se_category_id'=> empty($data['se_category_id']) ? '' : implode(',',$data['se_category_id']),
                'city_id'=>$data['city_id'],
                'location_idx'=> empty($data['location_idx']) ? '' : implode(',',$data['location_idx']),
                'start_time'=>strtotime($data['start_time']),
                'end_time'=>strtotime($data['end_time']),
                'total_count'=>$data['total_count'],
                'origin_price'=>$data['origin_price'],
                'current_price'=>$data['current_price'],
                'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
                'coupons_end_time'=>strtotime($data['coupons_end_time']),
                'notes'=>$data['notes'],
                'description'=>$data['description'],
                'bis_account_id'=> $this->getLoginUser()->id,
                'xpoint'=>$location->xpoint,
                'ypoint'=>$location->ypoint,
            ];
            $id = model('Deal')->add($deals);
            if ($id){
                $this->success('添加成功',url('deal/index'));
            }else{
                $this->success('添加失败');
            }

        }else{
            //获取一级城市的数据(parent_id默认为0)
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级分类的数据(parent_id默认为0)
            $categorys = model('Category')->getNormalCategorysByParentId();
            $this->assign(array(
                'citys'=>$citys,
                'categorys'=>$categorys,
                'bislocations' => model('BisLocation')->getNormalLocationByBisId($bisId),
            ));
            return $this->fetch();
        }

    }

}