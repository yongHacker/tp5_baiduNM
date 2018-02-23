<?php
namespace app\index\controller;
use think\Controller;

class Order extends Base
{
    public function index(){
        $user = $this->getLoginUser()->toArray();
        if (!$this->getLoginUser()){
            $this->error('请先登录','user/login');
        }
        $id = input('id',0,'intval');
        if (!$id){
            $this->error('id不存在');
        }
        $dealCount = input('get.deal_count',1,'intval');
        $totalPrice = input('get.total_price');

        $deal = model('Deal')->find($id);
        if(!$deal || $deal->status!=1){
            $this->error('商品不存在');
        }

        if (empty($_SERVER['HTTP_REFERER'])){ //获取前一页面的url地址
            $this->error('请求不合法');
        }

        //组装入库数据
        $orderSn = setOrderSn();
        $data = [
            'out_trade_no' => $orderSn,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'deal_id' => $id,
            'deal_count' => $dealCount,
            'total_price' => $totalPrice,
            'referer' => $_SERVER['HTTP_REFERER'],
        ];

        try{
            $orderId = model('Order')->add($data);
        }catch (\Exception $e){
            $this->error('订单处理失败');
        }

        $this->redirect(url('pay/index',['id'=>$orderId]));

    }

    public function confirm(){
        if (!$this->getLoginUser()){
            $this->error('请先登录','user/login');
        }
        //
        $id = input('id',0,'intval');
        if (!$id){
            $this->error('id不存在');
        }
        $count = input('get.count',1,'intval');

        $deal = model('Deal')->find($id);
        if(!$deal || $deal->status!=1){
            $this->error('商品不存在');
        }
        $deal = $deal->toArray();

        $this->assign([
            'controller'=>'pay' ,
            'count'=>$count ,
            'deal'=>$deal ,
        ]);
        return $this->fetch();
    }


}
