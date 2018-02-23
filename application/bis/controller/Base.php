<?php
namespace app\bis\controller;
use think\Controller;

class Base extends Controller
{
    public $account;

    public function _initialize()
    {
        //判定用户是否在登录状态
        $isLogin = $this->isLogin();
        if (!$isLogin){
            return $this->redirect(url('login/index'));
        }
    }

    /**判断session
     * @return bool
     */
    public function isLogin(){
        $user = $this->getLoginUser();
        if ($user && $user->id){
            return true;
        }else{
            return false;
        }
    }

    /**获取session
     * @return mixed
     */
    public function getLoginUser(){
        if (!$this->account){
            $this->account = session('bisAccount','','bis');
        }
        return $this->account;
    }


}