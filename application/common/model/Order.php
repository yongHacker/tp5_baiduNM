<?php
namespace app\common\model;
use think\Model;

class Order extends BaseModel
{
    public function add($data){
        $data['status'] = 1;
//        $data['create_time'] = time();
        $this->save($data); //想自动填充时间，插入只能用save()操作
        return $this->id;
    }

    public function updateOrderByOutTradeNo($outTradeNo,$weixinData){
        if (!empty($weixinData['transaction_id'])){
            $data['transaction_id'] = $weixinData['transaction_id'];
        }
        if (!empty($weixinData['total_fee'])){
            $data['pay_amount'] = $weixinData['total_fee']/100;
            $data['pay_status'] = 1;
        }
        if (!empty($weixinData['time_end'])){
            $data['pay_time'] = $weixinData['time_end'];
        }

        return $this->allowField(true)->save($data,['out_trade_no'=>$outTradeNo]);

    }

}