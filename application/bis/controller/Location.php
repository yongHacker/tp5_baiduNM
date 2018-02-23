<?php
namespace app\bis\controller;
use think\Controller;

class Location extends Base
{
    /**门店列表页
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            //校验数据
            $data = input('post.');
            $bisId = $this->getLoginUser()->bis_id;
            /*门店入库操作*/

            /*总店信息入库*/
            //获取经纬度
            $lnglat = \Map::getLngLat($data['address']);
            if (empty($lnglat) || $lnglat['status']!=0){
                $this->error('请输入正确且详细的商户地址');
            }

            //将checkbox提交的数组转换成字符串
            $data['cat']='';
            if (!empty($data['se_category_id'])){
                $data['cat'] = implode('|',$data['se_category_id']);//变成字符串
            }

            $locationData = [
                'bis_id'=>$bisId,
                'name'=>$data['name'],
                'tel'=>$data['tel'],
                'contact'=>$data['contact'],
                'category_id'=>$data['category_id'],
                'category_path'=>empty($data['se_category_id']) ? $data['category_id'] : $data['category_id'].','.$data['cat'],
                'city_id'=>$data['city_id'],
                'city_path'=>empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
                'api_address'=>$data['address'],
                'open_time'=>$data['open_time'],
                'content'=>empty($data['content']) ? '' : $data['content'],
                'is_main'=>0, //代表的是分店信息
                'xpoint'=>empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
                'ypoint'=>empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
            ];
            $locationId = model('BisLocation')->add($locationData);
            if ($locationId){
                return $this->success('门店申请成功');
            }else{
                return $this->error('门店申请失败');
            }

        }else{
            //获取一级城市的数据(parent_id默认为0)
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级分类的数据(parent_id默认为0)
            $categorys = model('Category')->getNormalCategorysByParentId();
            $this->assign(array(
                'citys'=>$citys,
                'categorys'=>$categorys,

            ));
            return $this->fetch();
        }
    }

}