<?php
namespace app\common\model;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true; //自动填充数据库时间戳字段

    /**添加分类操作
     * @param $data
     */
    public function add($data){
        $data['status'] = 1;
//        $data['create_time'] = time();
        return $this->save($data); //想自动填充时间，插入只能用save()操作
    }

    /**获取一级分类列表(下拉框add.html)
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalFirstCategory(){
        $data = [
            'status' => 1,
            'parent_id' => 0,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->select();
    }

    /**获取分类列表(index.html)
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getFirstCategorys($parentId=0){
        $data = [
            'status' => ['neq',-1],
            'parent_id' => $parentId,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',

        ];
        $result = $this->where($data)->order($order)->paginate(5);
//        echo $this->getLastSql();

        return $result;
    }

    /**数据库查询分类信息
     * @param int $parentId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalCategorysByParentId($parentId=0){
        $data = [
            'status'=>1,
            'parent_id'=>$parentId,
        ];
        $order = [
            'id'=>'desc',
        ];

        return $this->where($data)->order($order)->select();
    }

    /**前台获取一级分类数据
     * @param int $id
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalRecommendCategoryByParentId($id=0,$limit=5){
        $data = [
            'parent_id' => $id,
            'status' => 1,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order);
        if ($limit){
            $result = $result->limit($limit);
        }

        return $result->select();
    }

    /**前台获取二级分类数据
     * @param $ids
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalCategoryIdParentId($ids){
        $data = [
            'parent_id' => ['in',implode(',',$ids)],
            'status' => 1,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order)->select();

        return $result;
    }
}