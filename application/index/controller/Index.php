<?php
namespace app\index\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        //获取首页大图数据
        //获取广告位数据
        //商品分类数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);

        //获取4个子分类
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1,4);

        $this->assign([
            'datas' => $datas,
            'meishicates' => $meishicates,
        ]);
        return $this->fetch();
    }
}
