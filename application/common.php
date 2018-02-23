<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**审核状态的文案
 * @param $status
 * @return string
 */
function status($status){
    if ($status == 1){
        $str = "<span class='label label-success radius'>正常</span>";
    }elseif ($status == 0){
        $str = "<span class='label label-warning radius'>待审</span>";
    }else{
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

/** curl处理
 * @param $url
 * @param $type 0 get | 1 post
 * @param array $data
 */
function doCurl($url,$type=0,$data=[])
{
    $ch = curl_init();  //初始化
    //设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /*成功返回结果*/
    curl_setopt($ch, CURLOPT_HEADER, 0); //不输出header头消息

    if ($type==1){
        //post
        curl_setopt($ch,CURLOPT_PORT,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//post过去的数据
    }

    //执行并获取内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    return $output;
}

/**商户入驻申请的文案
 * @param $status
 * @return string
 */
function bisRegister($status){
    if ($status==1){
        $str = '入驻申请成功';
    }elseif ($status==0){
        $str = '待审核';
    }elseif ($status==2){
        $str = '入驻申请失败，请重新提交材料';
    }else{
        $str = '入驻申请已被删除';
    }
    return $str;
}

/**通用的分页
 * @param $obj
 */
function pagination($obj){
    //优化的方案
    $params = request()->param(); //获取url地址参数
    return "<div class='cl pd-5 bg-1 bk-gray mt-20 tp5-o2o'>".$obj->appends($params)->render()."</div>";
}

/**模板输出二级城市（通过city_path）
 * @param $path
 * @return bool|string
 */
function getSeCityName($path){
    if (empty($path)){
        return '';
    }
    //判断$path中是否有逗号(1.有二级城市;2.无二级城市)
    if (preg_match('/,/',$path)){
        $cityPath = explode(',',$path); //将字符串分割成数组
        $cityId = $cityPath[1];  //得到二级城市的id
    }else{
        $cityId = $path;
    }

    $city = model('City')->get($cityId);
    return $city->name;
}

/**模板输出分类子类（通过city_path）
 * @param $path
 * @return bool|string
 */
function getSeCategoryName($path){
    if (empty($path)){
        return '';
    }
    //判断$path中是否有逗号(1.有二级分类;2.无二级分类)
    if (preg_match('/,/',$path)){
        $cityPath = explode(',',$path); //将字符串分割成数组
        $cityId = $cityPath[1];  //得到二级城市的id
    }else{
        $cityId = $path;
    }

    $city = model('Category')->get($cityId);
    return $city->name;
}

/**几点通用
 * @param $ids
 * @return int
 */
function countLocation($ids){
    if (!$ids){
        return 1;
    }
    if (preg_match('/,/',$ids)){
        $arr = explode(',',$ids);
        return count($arr);
    }
    return 1;
}

/**
 * 设置订单号
 */
function setOrderSn(){
    list($t1,$t2) = explode(' ',microtime());
//    dump(microtime());exit;
//    echo $t1.'<br/>'.$t2;exit;
    $t3 = explode('.',$t1*10000);
    return $t2.$t3[0].rand(10000,99999);
}