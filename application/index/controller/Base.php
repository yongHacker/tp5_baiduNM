<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    public $city='';
    public $user='';

    public function _initialize()
    {
        //所有城市数据
        $citys = model('City')->getNormalCitys();
        //用户数据
        //获取首页分类数据
        $cats = $this->getRecommendCats();

        $this->getCity($citys);
        $this->assign([
            'citys' => $citys,
            'city' => $this->city,
            'cats' => $cats,
            'user' => $this->getLoginUser(),
            'controller' => strtolower(request()->controller()),
            'title' => 'o2o团购网'
        ]);
    }

    /**获取定位城市
     * @param $citys
     */
    public function getCity($citys){
        foreach ($citys as $city){
            $city = $city->toArray();  //对象转换成数组
            if ($city['is_default'] == 1){
                $defaultuname = $city['uname'];
                break; //终止循环
            }
        }
        $defaultuname = $defaultuname ? $defaultuname : 'nanchang';
        if (session('cityuname','','o2o') && !input('get.city')){
            $cityuname = session('cityuname','','o2o');
        }else{
            $cityuname = input('get.city',$defaultuname,'trim');
            session('cityuname',$cityuname,'o2o');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();

    }

    /**从session获取用户数据
     * @return mixed|string
     */
    public function getLoginUser(){
        if (!$this->user){
            $this->user = session('o2o_user','','o2o');
        }
        return $this->user;
    }

    /**
     * 获取首页推荐中的商品分类数据
     */
    public function getRecommendCats(){
        $parentIds = [];
        $sedcatArr = [];
        $recomCats = [];

        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);

        foreach ($cats as $cat){
            $parentIds[] = $cat->id;
        }

        //获取二级分类数据
        $sedCats = model('Category')->getNormalCategoryIdParentId($parentIds);

        foreach ($sedCats as $sedCat){
            $sedcatArr[$sedCat->parent_id][] = [
                'id' => $sedCat->id,
                'name' => $sedCat->name,
            ];
        }

        foreach ($cats as $cat){
            //$recomCats代表一级和二级数据 第一个参数是一级分类的name,第二个参数是此一级分类下面的所有二级分类数据
            $recomCats[$cat->id] = [$cat->name,empty($sedcatArr[$cat->id]) ? [] : $sedcatArr[$cat->id]];
        }

        return $recomCats;
    }

}
