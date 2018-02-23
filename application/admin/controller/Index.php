<?php
namespace app\admin\controller;
use think\Controller;
class Index extends  Controller
{
    public function index()
    {
        return $this->fetch();
    }
	public function test() {
        return \Map::getLngLat('北京昌平沙河地铁');
	}
    public function map() {
        return \Map::staticimage('广东省东莞市');
    }
    public function welcome() {
//        \phpmailer\Email::send('754354600@qq.com','tp5-emaiil','sucess-hello');
//        return '发送邮件成功';
        return "欢迎来到o2o主后台首页!";
    }
}
