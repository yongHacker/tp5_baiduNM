<?php
/**返回的二级信息封装成指定数组格式
 * @param $status
 * @param string $message
 * @param array $data
 * @return array
 */
function show($status,$message='',$data=[]){
    return [
        'status'=>intval($status),
        'message'=>$message,
        'data'=>$data,
    ];
}