<?php
namespace app\common\model;
use think\Model;

class BisLocation extends BaseModel
{
    /**通过$bisId查询所有门店
     * @param $bisId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalLocationByBisId($bisId){
        $data = [
            'bis_id'=>$bisId,
            'status'=>1, //审核通过的状态
        ];
        $result = $this->where($data)->order('id','desc')->select();
        return $result;
    }

    /**获取分店信息
     * @param $ids
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalLocationsInId($ids){
        $data = [
            'id' => ['in',$ids],
            'status' => 1,
        ];

        return $this->where($data)->select();
    }

    /**通过状态获取商家信息
     * @param $statys
     */
    public function getBisByStatus($status=0){
        $order = [
            'id'=>'desc'
        ];
        $data = [
            'status'=>$status
        ];
        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }

}