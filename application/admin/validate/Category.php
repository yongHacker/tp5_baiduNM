<?php
namespace app\admin\validate;
use think\Validate;

class Category extends Validate
{
    protected $rule=[
        ['name','require|max:10','分类名必须传递|分类名不能超过10个字符'],
        ['parent_id','number','分类id必须为数字'],
        ['id','number','id必须为数字'],
        ['status','number|in:-1,0,1','状态必须为数字|status只能是-1,0,1'],
        ['listorder','number'],
    ];

    /*场景设置*/
    protected $scene = [
        'add' => ['name','parent_id','id'], //添加
        'listorder' => ['id','listorder'], //排序
        'status' => ['id','status'], //状态
    ];


}