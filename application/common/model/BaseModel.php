<?php
namespace app\common\model;
use think\Model;

/**
 * Class BaseModel 公共的model层
 * @package app\common\model
 */
class BaseModel extends Model
{
    protected $autoWriteTimestamp=true;//自动填充时间字段

    /**插入操作
     * @param $data
     * @return mixed
     */
    public function add($data){
        $data['status'] = 0;
        $this->save($data);
        return $this->id; //返回插入的主键id
    }

    /**通过id更新传过来的数据
     * @param $data
     * @param $id
     * @return false|int
     */
    public function updateById($data,$id){
        return $this->allowField(true)->save($data,['id'=>$id]);
    }


}