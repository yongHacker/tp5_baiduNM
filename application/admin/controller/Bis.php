<?php
namespace app\admin\controller;
use think\Controller;
class Bis extends  Controller
{
   private $obj;

   public function _initialize()
   {
       $this->obj = model('Bis');
   }

    /**商户列表
     * @return mixed
     */
    public function index(){
        $bis = $this->obj->getBisByStatus(1);
        $this->assign('bis',$bis);
        return $this->fetch();
    }

    /**入驻申请列表
     * @return mixed
     */
   public function apply(){
//       $bis = $this->obj->getBisByStatus();
//       $this->assign('bis',$bis);

       $bisLocation = model('BisLocation')->getBisByStatus(0);

       $this->assign('bis',$bisLocation);
       return $this->fetch();
   }

    /**入驻申请详情页
     * @return mixed
     */
   public function detail(){
       $id = input('get.id');
       if (empty($id)){
           $this->error('id错误');
       }
       //获取一级城市数据
       $citys = model('City')->getNormalCitysByParentId();
       //获取一级分类数据
       $categorys = model('Category')->getNormalCategorysByParentId();
       //获取商户数据
       $bisData = model('Bis')->get($id);
       $locationData = model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);
       $accountData = model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);
       $this->assign(array(
           'citys'=>$citys,
           'categorys'=>$categorys,
           'bisData'=>$bisData,
           'locationData'=>$locationData,
           'accountData'=>$accountData,
       ));
       return $this->fetch();
   }

    /**
     * 入驻申请修改状态
     */
   public function status(){
       $data = input('get.');
       $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]); //数据，条件
       $location = model('BisLocation')->save(['status'=>$data['status']],['bis_id'=>$data['id']]);
       $account = model('BisAccount')->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
       if ($res && $location && $account){
           //发送邮件给客户端
           $this->success('状态更新成功');
       }else{
           $this->error('状态更新成功');
       }
   }

}
