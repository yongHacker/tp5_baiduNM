<?php
namespace app\index\controller;
use think\Controller;

class User extends Controller
{
    /**登录页
     * @return mixed
     */
    public function login()
    {
        //获取session
        $user = session('o2o_user','','o2o');
        if ($user && $user->id){
            $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }

    /**注册页
     * @return mixed
     */
    public function register()
    {
        if (request()->isPost()){
            $data = input('post.');
            //校验数据 validate
            if ($data['password'] != $data['repassword']){
                $this->error('两次输入密码不一致');
            }
            //加密密码
            $data['code'] = mt_rand(100,10000);
            $data['password'] = md5($data['password'].$data['code']);

            try{
                $res = model('User')->add($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            if ($res){
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');
            }

        }
        return $this->fetch();
    }

    public function logincheck(){
        if (!request()->isPost()){
            $this->error('提交不合法');
        }
        $data = input('post.');
        //校验数据
        try{
            $user = model('User')->getUserByUsername($data['username']);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        if (!$user || $user->status!=1){
            $this->error('用户不存在');
        }
        if (md5($data['password'].$user->code) != $user->password){
            $this->error('密码不正确');
        }
        //登录chengg
        model('User')->updateById(['last_login_time'=>time()],$user->id);

        //把用户信息记录到session
        session('o2o_user',$user,'o2o');

        $this->success('登录成功',url('index/index'));

    }

    /**
     * 退出登录
     */
    public function logout(){
        session(null,'o2o');
        $this->redirect(url('user/login'));
    }

}
