<?php
namespace app\admin\controller;
use think\Controller;
class Featured extends  Base
{
   private $obj;

   public function _initialize()
   {
       $this->obj = model('Featured');
   }

    /**推荐位列表页
     * @return mixed
     */
    public function index(){
        //获取推荐位类别
        $types = config('featured.featured_type');
        //获取列表数据
        $type = input('get.type',0,'intval');
        $results = $this->obj->getFeaturedsByType($type);

        $this->assign(array(
            'types'=>$types,
            'results'=>$results,
        ));
        return $this->fetch();
    }

    /**推荐位添加页
     * @return mixed
     */
    public function add(){
        if (request()->isPost()){
            //入库的逻辑
            $data = input('post.');
            dump($data);exit;

            //校验数据

            $id = model('Featured')->add($data);
            if ($id){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }


        }else{
            //获取推荐位类型
            $types = config('featured.featured_type');
            $this->assign('types',$types);
            return $this->fetch();
        }

   }

    /**
     * 修改状态
     */
   public function status(){
        //获取状态status
       $data = input('get.');
       //校验数据
       //更新数据库
       $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
       if ($res){
           $this->success('更新成功');
       }else{
           $this->error('更新失败');
       }
   }

}
