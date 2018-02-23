<?php
namespace app\common\model;
use think\Model;

class User extends BaseModel
{
    /**User表插入操作
     * @param array $data
     */
    public function add($data=[])
    {
        if (!is_array($data)){
            //抛出异常
            exception('提交的数据不是数组');
        }
        $data['status'] = 1;
        return $this->data($data)->allowField(true)->save();
    }

    /**根据用户名获取用户信息
     * @param $username
     */
    public function getUserByUsername($username){
        if (!$username){
            exception('用户名不合法');
        }
        $data = ['username'=>$username];
        return $this->where($data)->find();
    }

}