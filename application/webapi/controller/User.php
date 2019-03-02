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

use app\api\model\FinanceModel;
use app\webapi\controller\Baseapp;
use service\DataService;
use think\Db;
use app\webapi\service\AppuserService;

/**
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class User extends Baseapp
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'AppUsers';


    /**
     * 获取用户基本信息
     */
    public function get_userinfo()
    {
        $user_info = AppuserService::get_user_data($this->user_id, "*");
        return $this->fetch('', ['user_info' => $user_info]);
    }

    public function user_form()
    {
        $user_info = AppuserService::get_user_data($this->user_id, "*");
        return $this->fetch('', ['user_info' => $user_info]);
    }

    public function do_user_form()
    {
        header('Content-Type:application/json; charset=utf-8');
        $ret = [
            'status' => 200,
            'msg' => '成功',
            'data' => []
        ];
        $get = $this->request->post();
        //TODO 验证
        $savedata['nickname'] = $get['user_name'];
//        $savedata['sex'] = $get['sex'];
        $savedata['phone'] = $get['phone'];
        $savedata['id'] = $this->user_id;
        $r = DataService::save("AppUsers",$savedata);
        if(!$r){
            $ret['status'] = 400;
            $ret['msg'] = '提交失败，请稍后处理';
            exit(json_encode($ret));
        }
        exit(json_encode($ret));
    }

    /**
     * 用户信息
     * @return mixed
     */
    public function user_center()
    {
        $user_info = AppuserService::get_user_data($this->user_id, "*");
        $contract_info = Db::name("Contacts")->order("id desc")->where("user_id", $this->user_id)->where("is_deleted", 0)->field("id,car_model,transaction_price")->find();
        return $this->fetch('', ['user_info' => $user_info, 'contract_info' => $contract_info]);
    }


//    我的购车
    public function gouche()
    {
        $contract_info = Db::name("Contacts")
            ->where("user_id", $this->user_id)
            ->where("is_deleted", 0)
            ->order("id desc")
            ->field("id,contract_type,contract_number")->find();
        return $this->fetch('', ['contract_info' => $contract_info]);
    }

    //    我的代办
    public function daiban()
    {
        $contract_info = Db::name("Contacts")
            ->where("user_id", $this->user_id)
            ->where("is_deleted", 0)
            ->order("id desc")
            ->field("id,contract_type,contract_number")->find();
        if ($contract_info['contract_type'] == 2) {
            return $this->fetch('public/error', ['title' => '订车合同无此信息，请联系销售转定车合同']);
        }
        return $this->fetch('', ['contract_info' => $contract_info]);
    }

//    我的付款
    public function my_pay()
    {
        //合同信息
        $contract_info = Db::name("Contacts")
            ->where("user_id", $this->user_id)
            ->where("is_deleted", 0)
            ->order("id desc")
            ->field("id,contract_type,contract_number,transaction_price")->find();
        if (!$contract_info) {
            return $this->fetch('public/error', ['title' => '对不起，您的合同信息不存在']);
        }
        $finances = Db::name("Finance")
            ->where("contacts_id", $contract_info['id'])
            ->where("is_deleted", 0)
            ->where("type", 0)
            ->select();
        if (!$finances) {
            return $this->fetch('public/error', ['title' => '暂无收款信息']);
        }
        $tmp = $contract_info['transaction_price'];
        $FinanceModel = new FinanceModel();
        $income_type = $FinanceModel->get_incom_type();

        foreach ($finances as $key => $val) {
            $finances[$key]['surplus'] = $tmp = $tmp - $val['money'];
            $finances[$key]['create_at'] = date("Y-m-d", strtotime($val['create_at']));
            $finances[$key]['option'] = $income_type[$val['option']];
        }

        return $this->fetch('', ['list' => $finances]);
    }


    /**
     * 测试短信
     */
    public function sms_test()
    {
        $mobile = '15920165887';
        $code = '1231';
        $r = AlidayuService::_test($mobile, $code);
        print_r($r);
    }
}
