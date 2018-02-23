<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\File;

class Order extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Order');
    }

    public function payStatus(){
        $id = input('post.id',0,'intval');
        if (!$id){
            return show(0,'error');
        }

        $order = $this->obj->get($id);

        if ($order->pay_status == 1){   //支付成功
            return show(1,'success');
        }

        return show(0,'error');
    }

}