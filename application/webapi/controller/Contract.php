<?php

// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014-2018 东莞市云拓互联网络科技有限公司
// +----------------------------------------------------------------------
// | 官方网站:http://www.ytclouds.net
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace app\webapi\controller;

use app\api\model\ContactsCheckModel;
use app\api\model\ContactsModel;
use app\api\model\ProductsModel;
use app\api\service\RepertoryService;
use app\api\service\SettlementService;
use app\webapi\service\ContactService;
use service\DataService;
use service\PushService;
use think\Db;

/**
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Contract extends Baseapp
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'Contacts';

    /**
     * 合同详情
     */
    public function index()
    {
        $info = ContactService::get_contract_by_user_id($this->user_id);
        if (!$info) {
            return $this->fetch('public/error', ['title' => '暂无合同信息']);
        }
        return $this->fetch('', ['info' => $info]);
    }

    public function push_confirm()
    {
        $contract_id = $this->request->get("contract_id");
        if (!$contract_id) {
            return $this->fetch('public/error', ['title' => '暂无合同信息']);
        }
        $info = ContactService::get_contract_by_id($contract_id);
        if (!$info) {
            return $this->fetch('public/error', ['title' => '暂无合同信息']);
        }
        if ($info['client_confirm'] == 1) {
            return $this->fetch('public/error', ['title' => '您已经确认了']);
        }

        return $this->fetch('', ['info' => $info]);
    }

    public function upload_sign()
    {
        $get = $this->request->post();

        $info = ContactService::get_contract_by_id($get['contact_id']);
        if (!$info) {
            $ret['status'] = 400;
            $ret['data'] = [];
            $ret['msg'] = '保存失败，请稍后重试';
            exit(json_encode($ret));
        }

//        保存签名

        $r = $this->base64_image_content($get['client_sign'], './static/client_sign');
        if ($r) {
            $image = \think\Image::open('.' . $r);
            $image->thumb(156, 90)->save('.' . $r);
            $save_data['id'] = $get['contact_id'];
            $save_data['client_sign'] = $r;
            $res = DataService::save($this->table, $save_data, 'id');
            if (!$res) {
                $ret['status'] = 400;
                $ret['msg'] = '失败，请稍后重试';
                exit(json_encode($ret));
            }
            $ret['status'] = 200;
            $ret['data'] = ['sign_url' => $r];
            $ret['msg'] = '保存成功';
            exit(json_encode($ret));
        } else {
            $ret['status'] = 400;
            $ret['data'] = [];
            $ret['msg'] = '保存失败，请稍后重试';
            exit(json_encode($ret));
        }
    }

    public function base64_image_content($base64_image_content, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path . "/" . date('Ymd', time()) . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return str_replace('./', '/', $new_file);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 车辆状态
     */
    public function car_info()
    {
        //准备中，贷款下来的日期
        //运输中，文员上传车架号信息
        //车到店，库管修改的日期（销售申请交车）
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

//        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
//        if (!$is_dingche) {
//            return $this->fetch('public/error', ['title' => '暂无信息']);
//        }

        $list = Db::name("repertory")
            ->where("contacts_id", $contract_id)
            ->where("is_deleted", 0)
            ->select();
        if (count($list) <= 0) {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }
        foreach ($list as $key => &$val) {
            if ($val['status'] == 1) {
                //添加图片
                $images = Db::name("ContactsLogistics")
                    ->where("contacts_id", $val['contacts_id'])
                    ->find();
                $val['images'] = $images;
            }
            $val['desc'] = '';
            switch ($val['status']) {
                case 0:
                    $val['desc'] = '车辆准备中';
                    break;
                case 1:
                    $val['desc'] = '车辆运输中';
                    break;
                case 2:
                    $val['desc'] = '确认到店';
                    break;
                case 3:
                    $val['desc'] = '已经交车';
                    break;
            }
            $val['create_at'] = date("m-d", strtotime($val['create_at']));
        }
        return $this->fetch('', ['list' => $list]);
    }


//    按揭
    public function anjie()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

//        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
//        if (!$is_dingche) {
//            return $this->fetch('public/error', ['title' => '暂无信息']);
//        }

        $list = Db::name("MortgageLog")
            ->where("contacts_id", $contract_id)
            ->where("is_deleted", 0)
            ->select();
        if (count($list) <= 0) {
            return $this->fetch('public/error', ['title' => '暂无按揭信息']);
        }
        foreach ($list as $key => &$val) {
            $val['create_at'] = date("m-d", strtotime($val['create_at']));
        }
        return $this->fetch('', ['list' => $list]);
    }

    public function baoxian()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

//        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
//        if (!$is_dingche) {
//            return $this->fetch('public/error', ['title' => '暂无信息']);
//        }

        $info = Db::name("ContactsInsurance")
            ->where("contacts_id", $contract_id)
            ->where("is_deleted", 0)
            ->find();
        if (!$info) {
            return $this->fetch('public/error', ['title' => '暂无保单信息']);
        }
        return $this->fetch('', ['info' => $info]);
    }

    /**
     * 精品状态
     * @return mixed
     */
    public function jingpin()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

//        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
//        if (!$is_dingche) {
//            return $this->fetch('public/error', ['title' => '暂无信息']);
//        }

        $list = Db::name("ProductCheck")
            ->alias("p")
            ->join("products ps", "ps.id = p.product_id", 'LEFT')
            ->where("p.contacts_id", $contract_id)
            ->where("p.is_deleted", '0')
            ->field("p.*,ps.name")
            ->select();
        foreach ($list as $key => &$val) {
            $val['dotime'] = date("m-d", strtotime($val['dotime']));
        }
        return $this->fetch('', ['list' => $list]);
    }

    /**
     * 商品记录
     */
    public function shangpai()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
        if (!$is_dingche) {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }
        $list = Db::name("ShangpaiLog")
            ->where("contacts_id", $contract_id)
            ->where("is_deleted", 0)
            ->select();
        if (count($list) <= 0) {
            return $this->fetch('public/error', ['title' => '暂无上牌记录信息']);
        }
        foreach ($list as $key => &$val) {
            $val['create_at'] = date("m-d", strtotime($val['create_at']));
        }
        return $this->fetch('', ['list' => $list]);
    }

    /**
     * 交车记录
     */
    public function jiaoche()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }

//        $is_dingche = ContactService::contract_check($this->user_id, $contract_id);
        $is_dingche = ContactService::get_simple_contract($this->user_id, $contract_id, 'star,advice,pass_code');
        if (!$is_dingche) {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }
        if ($is_dingche['pass_code'] != '') {
            $url = url("webapi/contract/get_pass_number") . '?contract_id=' . $contract_id;
            $this->redirect($url);
        }
        $contactCheckModel = new ContactsCheckModel();
        $contact_check_info = $contactCheckModel->getOneByContacts_id($contract_id);
        if ($contact_check_info['is_pass'] == -1) {
            return $this->fetch('public/error', ['title' => '暂未能申请交车']);
        }
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contract_id);
        if (!$contact_info) {
            return $this->fetch('public/error', ['title' => '查询无合同信息']);
        }
        if ($contact_info['pay_money'] < $contact_info['transaction_price']) {
            //TODO 判断收入大于合同金
            return $this->fetch('public/error', ['title' => '暂无交车信息']);
        }
        $return['contact_id'] = $contract_id;
        $return['brand_name'] = $contact_info['brand_name'];
        $return['car_model'] = $contact_info['car_model'];
        $return['car_color'] = $contact_info['car_color'];
        $return['car_selection'] = $contact_info['car_selection'];
        $return['guidance_price'] = $contact_info['guidance_price'];
        $return['frame_number'] = $contact_info['frame_number'];
        $return['transaction_price'] = $contact_info['transaction_price'];
        $return['pay_money'] = $contact_info['pay_money'];
        $return['un_pay'] = $contact_info['transaction_price'] - $contact_info['pay_money'];
        $return['buy_type'] = $contact_info['buy_type'];
        $return['buy_type_desc'] = $contact_info['buy_type_desc'];

        $return['is_car_check'] = $contact_check_info['is_car_check'];
        $return['is_car_check_push'] = $contact_check_info['is_car_check_push'];
        $return['car_check_ps'] = $contact_check_info['car_check_ps'];
        $car_check = Db::name("CarCheck")->where("is_deleted", 0)->field("id,name,is_check")->order("sort asc,id asc")->select();
        foreach ($car_check as &$v) {
            $v['key'] = $v['id'];
            $v['value'] = $v['name'];
        }
        $return['car_check_select'] = $contactsModel->get_car_check($contact_check_info['car_check_select'], $car_check);

        $return['is_process_check'] = $contact_check_info['is_process_check'];
        $return['is_process_check_push'] = $contact_check_info['is_process_check_push'];
        $return['process_check_ps'] = $contact_check_info['process_check_ps'];
        $process_check = Db::name("ProcessCheck")->where("is_deleted", 0)->field("id,name,is_check")->order("sort asc,id asc")->select();
        foreach ($process_check as &$v) {
            $v['key'] = $v['id'];
            $v['value'] = $v['name'];
        }
        $return['process_check_select'] = $contactsModel->get_process_check($contact_check_info['process_check_select'], $process_check);

        $return['is_product_check'] = $contact_check_info['is_product_check'];
        $return['is_product_check_push'] = $contact_check_info['is_product_check_push'];
        $return['product_check_ps'] = $contact_check_info['product_check_ps'];
        $productsModel = new ProductsModel();
        $need_products = $productsModel->getByIds($contact_info['products_ids']);
        if (!$need_products) {
            $return['product_check_select'] = [];
        } else {
            $return['product_check_select'] = $contactsModel->get_product_check($contact_check_info['product_check_select'], $need_products);
        }

        $return['is_other_check'] = $contact_check_info['is_other_check'];
        $return['is_other_check_push'] = $contact_check_info['is_other_check_push'];
        $return['is_checkin'] = ($contact_check_info['is_checkin'] == 1) ? true : false;
        $return['is_mortgage'] = ($contact_check_info['is_mortgage'] == 1) ? true : false;
        $return['other_check_ps'] = $contact_check_info['other_check_ps'];
        $return['ps'] = $contact_check_info['ps'];
        $return['is_pass'] = $contact_check_info['is_pass'];
        return $this->fetch('', ['info' => $return]);
    }


    /**
     * 用户确认
     */
    public function do_confirm()
    {
        header('Content-Type:application/json; charset=utf-8');
        $get = $this->request->request();
        $ret = [
            'status' => 200,
            'msg' => '成功',
            'data' => []
        ];
        $validate = ['contact_id', 'type'];
        foreach ($validate as $key => $val) {
            if (!isset($get[$val]) || $get[$val] == '') {
                $ret['status'] = 1003;
                $ret['msg'] = '提交信息不全';
                exit(json_encode($ret));
            }
        }

        $info = ContactService::get_contract_by_id($get['contact_id']);
        if (!$info) {
            $ret['status'] = 400;
            $ret['data'] = [];
            $ret['msg'] = '保存失败，请稍后重试';
            exit(json_encode($ret));
        }
        if ($info['client_sign'] == '') {
            $ret['status'] = 400;
            $ret['data'] = [];
            $ret['msg'] = '请签名确认，请稍后重试';
            exit(json_encode($ret));
        }

        if ($get['type'] == '1') {
            //用户同意，付定金
            $savedata['id'] = $get['contact_id'];
            $savedata['is_cancel'] = 0;
            $savedata['client_confirm'] = 1;

        } elseif ($get['type'] == '2') {
            //用户不同意，需要修改
            $savedata['id'] = $get['contact_id'];
            $savedata['is_cancel'] = 5;
            $savedata['client_confirm'] = 0;
            $savedata['false_reason'] = $get['ps_content'] ?: '';

        } else {
            $ret['status'] = 1003;
            $ret['msg'] = '参数错误';
            exit(json_encode($ret));
        }
        $r = DataService::save("Contacts", $savedata);
        if (!$r) {
            $ret['status'] = 400;
            $ret['msg'] = '保存失败，请稍后重试';
            exit(json_encode($ret));
        }
        if ($get['type'] == 2) {
            //通知销售修改合同
            $contanct_info = Db::name("Contacts")->where("id", $get['contact_id'])->field("id,saler_id,contract_number")->find();
            if ($contanct_info) {
                $send_data['url'] = '';
                $send_data['first'] = '合同编号：' . $contanct_info['contract_number'] . ' ,用户不同意该合同,需修改';
                $send_data['keyword1'] = '';
                $send_data['keyword2'] = '';
                PushService::notify($contanct_info['saler_id'], ['status' => 200, 'msg' => '合同编号：' . $contanct_info['contract_number'] . ' ,用户不同意该合同，需修改', 'data' => $send_data]);
            }
        }

        $hetong_water = './static/dianzi.png';
        $qianming_water = '.' . $info['client_sign'];
        $hetong = './static/pdf/contract_'.$info['contract_number'].'.png';
        $image = \think\Image::open($hetong);
        $today = date("Y-m-d",time());
        $ttf = 'wryh.ttf';
        //合同加水印
        if ($info['contract_type'] == '1') {
            $image->water($hetong_water, [422, 1502], 50);
            $image->water($qianming_water, [96, 1467]);
            $image->text($today, $ttf, 10, '#000000', [96, 1587]);
        } else {
            $image->water($hetong_water, [436, 1030], 50);
            $image->water($qianming_water, [96, 998]);
            $image->text($today, $ttf, 10, '#000000', [96, 1111]);
        }
        $image->save($hetong);

        exit(json_encode($ret));
    }


    /**
     * 评价
     */
    public function do_advice()
    {
        header('Content-Type:application/json; charset=utf-8');
        $get = $this->request->post();
        $ret = [
            'status' => 200,
            'msg' => '成功',
            'data' => []
        ];
        $validate = ['contact_id', 'star', 'advice'];
        foreach ($validate as $key => $val) {
            if (!isset($get[$val]) || $get[$val] == '') {
                $ret['status'] = 1003;
                $ret['msg'] = '提交信息不全';
                exit(json_encode($ret));
            }
        }
        $is_dingche = ContactService::get_simple_contract($this->user_id, $get['contact_id'], 'star,advice');
        if (!$is_dingche) {
            $ret['status'] = 400;
            $ret['msg'] = '查询不到合同信息';
            exit(json_encode($ret));
        }
        if ($is_dingche['star'] || $is_dingche['advice']) {
            $ret['status'] = 200;
            $ret['msg'] = '已经评论了';
            exit(json_encode($ret));
        }
        $savedata['id'] = $get['contact_id'];
        $savedata['star'] = $get['star'];
        $savedata['status'] = 1;
        $savedata['advice'] = $get['advice'];
        $now = time();
        $savedata['get_time'] = date("Y-m-d H:i:s", $now);
        $savedata['pass_code'] = 'CLZ' . substr($now, 3) . $get['contact_id'];

        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $ret['status'] = 400;
            $ret['msg'] = '评论失败，请稍后重试';
            exit(json_encode($ret));
        }
        //修改库管状态
        $repertoryService = new RepertoryService();
        $data['contacts_id'] = $get['contact_id'];
        $data['status'] = 3;
        $data['ps'] = '';
        $repertoryService->add_log($data);
//        $settlementService = new SettlementService();
//        $settlementService->add_log($get['contact_id'],$get['star']);
        $ret['status'] = 200;
        $ret['msg'] = '评论成功';
        exit(json_encode($ret));
    }

    /**
     * 放行条
     */
    public function get_pass_number()
    {
        $contract_id = $this->request->get("contract_id");
        if (!isset($contract_id) || $contract_id == '') {
            return $this->fetch('public/error', ['title' => '错误请求']);
        }
        $info = ContactService::get_simple_contract($this->user_id, $contract_id, 'star,advice,pass_code,frame_number,brand_name,car_model,car_color');
        if (!$info['pass_code']) {
            return $this->fetch('public/error', ['title' => '暂无信息']);
        }
        return $this->fetch('', ['contract_info' => $info]);
    }


}
