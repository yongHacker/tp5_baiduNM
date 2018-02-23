<?php
namespace app\index\controller;
use think\Controller;

class Detail extends Base
{
    public function index($id)
    {
        if (!intval($id)){
            $this->error('id不合法');
        }
        //根据id查询商品的数据
        $deal = model('Deal')->get($id);
        if (!$deal || $deal->status!=1){
            $this->error('该商品不存在');
        }

        //获取分类信息
        $category = model('Category')->get($deal->category_id);

        //获取分店信息
        $locations = model('BisLocation')->getNormalLocationsInId($deal->location_idx);

        //倒计时(商品有限期)
        $flag = 0;
        $timedata = '';
        if ($deal->start_time > time()){
            $flag = 1;
            $dtime = $deal->start_time - time();
            $d = floor($dtime/(3600*24));
            if ($d){
                $timedata .= $d.'天';
            }
            $h = floor($dtime%(3600*24)/3600);
            if ($h){
                $timedata .= $h.'小时';
            }
            $m = floor($dtime%(3600*24)%3600/60);
            if ($h){
                $timedata .= $m.'分';
            }
        }

        $this->assign([
            'title' => $deal->name,
            'deal' => $deal,
            'category' => $category,
            'locations' => $locations,
            'overplus' => $deal->total_count - $deal->buy_count,
            'flag' => $flag,
            'timedata' => $timedata,
            'mapstr' => $locations[0]['xpoint'] . ',' .$locations[0]['ypoint'] ,
        ]);
        return $this->fetch();
    }
}
