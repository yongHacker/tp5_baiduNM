<?php
namespace app\api\controller;
use think\Controller;

class Category extends Controller
{
    private $obj;

    public function _initialize(){
        $this->obj = model("Category");
    }

    /**二级分类请求接口
     * @return array
     */
    public function getCategoryByParentId(){
        $id = input('post.id',0,'intval');
        if (!$id){
            $this->error('Id不合法');
        }
        //通过parent_id获取二级城市
        $categorys = $this->obj->getNormalCategorysByParentId($id);
        //返回客户端信息（数组格式）
        if (!$categorys){
            return show(0,'error');
        }
        return show(1,'success',$categorys);

    }


}