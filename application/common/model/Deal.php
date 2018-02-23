<?php
namespace app\common\model;
use think\Model;

class Deal extends BaseModel
{
    public function getNormalDeals($data=[]){
        $data['status'] = 1;
        $order = ['id'=>'desc'];

        $result = $this->where($data)->order($order)->paginate();
//        echo $this->getLastSql();
        return $result;
    }

    /**根据分类以及城市来获取商品数据
     * @param $id 分类id
     * @param $cityId 城市id
     * @param int $limit 条数
     */
    public function getNormalDealByCategoryCityId($id,$cityId,$limit=10){
        $data = [
            'end_time' => ['gt',time()],
            'category_id' => $id,
            'city_id' => $cityId,
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

    /**根据条件筛选商品
     * @param array $data
     * @param $orders
     * @return \think\Paginator
     */
    public function getDealByConditions($data=[],$orders){
        if (!empty($orders['order_sales'])){
            $order['buy_count'] = 'desc';
        }
        if (!empty($orders['order_price'])){
            $order['current_price'] = 'desc';
        }
        if (!empty($orders['order_time'])){
            $order['create_time'] = 'desc';
        }
//        $data['city_id'] = $this->city->id;
        //SQL函数 find_in_set(11,se_category_id)
        $order['id'] = 'desc'; //默认第二筛选条件
        $datas = [];
        if (!empty($data['se_category_id'])){
            $datas[] = " find_in_set(".$data['se_category_id'].",se_category_id) ";
        }
        if (!empty($data['category_id'])){
            $datas[] = " category_id=".$data['category_id'];
        }
        if (!empty($data['city_id'])){
            $datas[] = " city_id=".$data['city_id'];
        }
        $datas[] = " status=1";
//        $datas[] = " end_time >".time();

        $result = $this->where(implode(' AND ',$datas))->order($order)->paginate();
//        echo $this->getLastSql();
        return $result;
    }

    public function updateBuyCountById($id,$buyCount)
    {
        return $this->where(['id'=>$id])->setInc('buy_count',$buyCount);
    }

}