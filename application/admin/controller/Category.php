<?php
namespace app\admin\controller;
use think\Controller;


class Category extends Controller
{
    private  $obj;
    public function _initialize()
    {
        $this->obj = model("Category");
    }

    /**分类页
     * @return mixed
     */
    public function index(){
        $parentId = input('get.parent_id',0,'intval');
        $categorys = $this->obj->getFirstCategorys($parentId);
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    /**分类添加页
     * @return mixed
     */
    public function add(){
        $categorys =  $this->obj->getNormalFirstCategory();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    /**
     * 分类添加处理
     */
    public function save(){
//        print_r(input('post.'));
//        print_r(request()->post());

        if (!request()->isPost()){
            $this->error('不是post请求');
        }
        //验证post过来的数据
        $data = input('post.');
        $validate = validate('Category');
        if(!$validate->scene('add')->check($data)){
            $this->error($validate->getError());
        }
        //更新数据库
        if(!empty($data['id'])){
            return $this->update($data);
        }
        //把$data提交给model层(插入数据库)
        $res =  $this->obj->add($data);
        if ($res){
            $this->success('新增成功');
        }else{
            $this->error('新增失败');
        }

    }

    /**
     * 编辑页面
     */
    public function edit(){
        $id = intval(input('id'));
        if ($id<1){
            $this->error('参数不合法');
        }
        $category = $this->obj->get($id);
        $this->assign('category',$category);

        $categorys = $this->obj->getNormalFirstCategory();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    /**分类编辑操作
     * @param $data
     */
    public function update($data){
        $res =  $this->obj->save($data,['id'=>intval($data['id'])]);
        if ($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }

    /**分类排序操作(ajax)
     * @param $id
     * @param $listorder
     */
    public function listorder($id,$listorder){
        $res = $this->obj->save(['listorder'=>$listorder],['id'=>$id]); //(修改的内容，修改的条件)
        if ($res){
            //result()返回封装后的API数据到客户端 ---ajax返回
            $this->result($_SERVER['HTTP_REFERER'],1,'排序更新成功');//php $_SERVER['HTTP_REFERER']获取上一个页面的URL地址
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'排序更新失败');
        }
    }

    /**
     * 修改状态
     */
    public function status(){
        $data = input('get.');
        //验证get过来的数据
        $validate = validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }
        $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
        if ($res){
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }



}