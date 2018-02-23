<?php
namespace app\admin\controller;
use think\Controller;
class Base extends  Controller
{
    /**
     * 修改状态
     */
    public function status(){
        //获取状态status
        $data = input('get.');
        //校验数据
        if(empty($data['id'])){
            $this->error('id不合法');
        }
        if (!is_numeric($data['status'])){
            $this->error('status不合法');
        }
        //更新数据库
        //获取控制器名
        $model = request()->controller();
        $res = model($model)->save(['status'=>$data['status']],['id'=>$data['id']]);
        if ($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }
}
