<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;
use wxpay\WxPayApi;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;

class Weixinpay extends Controller
{
    /**
     * 微信支付成功回调
     */
    public function notify(){
        //测试
        $weixinData = file_get_contents("php://input");
        file_put_contents('/tmp/weixin.txt',$weixinData,FILE_APPEND);

        try{
            $resultObj = new WxPayResults();
            $weixinData = $resultObj->Init($weixinData);
        }catch (\Exception $e){
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('return_msg',$e->getMessage());
            return $resultObj->toXml();
        }

        if ($weixinData['return_code']==='FAIL' || $weixinData['result_code']!=='SUCCESS' ){
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('return_msg','error');
            return $resultObj->toXml();
        }

        //根据out_trade_no来查询订单数据
        $outTradeNo = $weixinData['out_trade_no'];
        $order = model('Order')->get(['out_trade_no'=>$outTradeNo]);
        if (!$order || $order->pay_status==1){
            $resultObj->setData('return_code','SUCCESS');
            $resultObj->setData('return_msg','ok');
            return $resultObj->toXml();
        }

        //更新表 订单表 商品表
        try{
            $orderRes = model('Order')->updateOrderByOutTradeNo($outTradeNo,$weixinData);

            model('Deal')->updateBuyCountById($order->deal_id,$order->deal_count);

            //消费券生成
            $coupons = [
                'sn' => $outTradeNo,
                'password' => rand(10000,99999),
                'user_id' => $order->user_id,
                'deal_id' => $order->deal_id,
                'order_id' => $order->id,
            ];
            model('Coupons')->add($coupons);

        }catch (\Exception $e){
            //更新失败 -》告诉微信服务器我们还需要回调
            return false;
        }

        $resultObj->setData('return_code','SUCCESS');
        $resultObj->setData('return_msg','ok');
        return $resultObj->toXml();

    }

    /**生成微信二维码
     * @param $id
     * @return string
     */
    public function wxpayQCode($id){
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody("支付 0.01 元");
        $input->setAttach("支付 0.01 元");
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->setTotalFee("1");
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis",time()+600));
        $input->setGoodsTag("QRCode");
        $input->setNotifyUrl("/index.php/index/weixinpay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($id);
        $result = $notify->getPayUrl($input);
        if (empty($result['code_url'])){
            $url = '';
        }else{
            $url = $result['code_url'];
        }

        return "<img alt='扫码支付' src='/weixin/example/qrcode.php?data=".urlencode($url)."' style='width: 300px;height: 300px;' />";

    }


}
