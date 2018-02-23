<?php
namespace app\bis\controller;
use think\Controller;
use think\Model;

class Register extends Controller
{
    /**申请入驻页面
     * @return mixed
     */
    public function index(){
        //获取一级城市的数据(parent_id默认为0)
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级分类的数据(parent_id默认为0)
        $categorys = model('Category')->getNormalCategorysByParentId();
        $this->assign(array(
            'citys'=>$citys,
            'categorys'=>$categorys,

        ));
        return $this->fetch();
    }

    /**
     * 申请入驻处理
     */
    public function add(){
        if (!request()->isPost()){
            $this->error('不是post请求');
        }
        //获取表单的值
        $data = input('post.');

        //校验基本信息数据
        $validate = validate('Bis');
        if (!$validate->scene('add')->check($data)){
//            $this->error($validate->getError());
        }
        //校验总店信息数据
        //校验账号信息数据

        //获取经纬度
        $lnglat = \Map::getLngLat($data['address']);
        if (empty($lnglat) || $lnglat['status']!=0){
            $this->error('请输入正确且详细的商户地址');
        }
        //判断用户是否已存在
        $accountResult = model('BisAccount')->get(['username'=>$data['username']]);
        if($accountResult){
            $this->error('该用户已存在');
        }
        //基本信息入库
        $bisData = [
            'name'=>htmlentities($data['name']), //数据转换成html实体
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'logo'=>$data['logo'],
            'licence_logo'=>$data['licence_logo'],
            'description'=>empty($data['description']) ? '' : $data['description'],
            'bank_info'=>$data['bank_info'],
            'bank_user'=>$data['bank_user'],
            'bank_name'=>$data['bank_name'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'email'=>$data['email'],
        ];
        $bisId = model("Bis")->add($bisData);

        //总店信息入库
            //将checkbox提交的数组转换成字符串
        $data['cat']='';
        if (!empty($data['se_category_id'])){
            $data['cat'] = implode('|',$data['se_category_id']);//变成字符串
        }
        $locationData = [
            'bis_id'=>$bisId,
            'name'=>$data['name'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
            'category_id'=>$data['category_id'],
            'category_path'=>empty($data['se_category_id']) ? $data['category_id'] : $data['category_id'].','.$data['cat'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'api_address'=>$data['address'],
            'open_time'=>$data['open_time'],
            'content'=>empty($data['content']) ? '' : $data['content'],
            'is_main'=>1, //代表的是总店信息
            'xpoint'=>empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
            'ypoint'=>empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
        ];
        $locationId = model('BisLocation')->add($locationData);

        //账号信息入库
        $data['code'] = mt_rand(100,10000); //自动生成密码加盐字符串
        $accountData = [
            'bis_id'=>$bisId,
            'code'=>$data['code'],
            'username'=>$data['username'],
            'password'=>md5($data['password'].$data['code']),
            'is_main'=>1,  //代表总管理员
        ];
        $accountId = model('BisAccount')->add($accountData);
        if (!$accountId){
            $this->error('申请失败');
        }

        //发送邮件
        $url = request()->domain().url('bis/register/waiting',['id'=>$bisId]); //request()->domain()获取当前域名； url()生成url地址
        $title = 'o2o入驻申请通知';
        $content = "您提交的入驻申请需等待平台方进行审核,可通过点击链接<a href='".$url."' target='_blank'>查看链接</a>查看审核状态.";
        \phpmailer\Email::send($data['email'],$title,$content);

        $this->success('申请成功',url('register/waiting',['id'=>$bisId]));

    }

    public function waiting($id){
        if (empty($id)){
            $this->error('error');
        }
        $detail = model("Bis")->get($id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }


}