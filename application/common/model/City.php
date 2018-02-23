<?php
namespace app\common\model;
use think\Model;

class City extends Model
{
    /**数据库查询城市信息(传入parent_id条件)
     * @param int $parentId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalCitysByParentId($parentId=0){ //默认取省级城市
        $data = [
            'status'=>1,
            'parent_id'=>$parentId,
        ];
        $order = [
            'id'=>'desc',
        ];

        return $this->where($data)->order($order)->select();
    }

    /**数据库查询所有城市信息(市级)
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalCitys(){
        $data = [
            'status'=>1,
            'parent_id'=>['gt',0],  //parent_id>0
        ];
        $order = [
            'id'=>'desc',
        ];

        return $this->where($data)->order($order)->select();
    }
}