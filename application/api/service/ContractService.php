<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014-2018 东莞市云拓互联网络科技有限公司
// +----------------------------------------------------------------------
// | 官方网站:http://www.ytclouds.net
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------

namespace app\api\service;

use app\api\model\ContactsModel;
use app\api\model\FinanceModel;
use service\DataService;
use service\PushService as extend_push;
use service\ToolsService;
use service\WechatService;
use think\Db;

/**
 * 合同业务处理
 * Class FansService
 * @package app\wechat
 */
class ContractService
{
    /**
     * 生成合同。合同通知
     */
    public function contract_push($contract_id, $type)
    {
        if (!$contract_id) {
            return false;
        }
        $contract = new ContactsModel();
        $data = $contract->getOne($contract_id);
        //统一审核，经理，总经理，用户
        $this->contact_manage($data, $type);
//        if ($data['contract_type'] == 1) {
//            $this->contact_manage($data);
//        } else {
//            $this->contract_push_client($data);
//            PushService::confirm($data);
//        }
    }

    //通知经理
    public function contact_manage($data, $type = 0)
    {
        //合同审批表，合同表
        //通知经理
        $sale_info = Db::name("SystemUser")->where("id", $data['saler_id'])->field("id,ganwei_id")->find();
        if (!$sale_info) {
            return false;
        }
        //把过期的隐藏
        Db::name("ContactsApproval")->where("contacts_id", $data['id'])->where("status", 0)->update(['is_deleted' => 1]);

        $ganwei = Db::name("Ganwei")->where("id", $sale_info['ganwei_id'])->find();

        $save_data['is_cancel'] = 6;
        $save_data['manage_confirm'] = 0;
        $save_data['client_confirm'] = 0;
        $save_data['boss_confirm'] = 0;
        $save_data['id'] = $data['id'];
        $r = DataService::save("Contacts", $save_data);
        //TODO 做事务
        $approval['contacts_id'] = $data['id'];
        $approval['admin_id'] = $ganwei['pid'];
        $approval['manage_type'] = 0;
        $approval['type'] = $type ? $type : 0;
        $r = DataService::save("ContactsApproval", $approval);

        if ($ganwei['pid']) {
            $send_data['url'] = 'http://chexinyuan.com/index.php/manage/user/get_approval_list.html';
            $send_data['first'] = '合同编号：' . $data['contract_number'] . ' ,等待审核';
            $send_data['keyword1'] = '';
            $send_data['keyword2'] = '';
            extend_push::notify($ganwei['pid'], ['status' => 200, 'msg' => '合同编号：' . $data['contract_number'] . ' ,等待审核', 'data' => $send_data]);
        }
    }

    /**
     * 联系总经理
     * @param $data
     * @param int $type
     */
    public function contact_zong_manage($data, $type = 0)
    {
        //把过期的隐藏
        Db::name("ContactsApproval")->where("contacts_id", $data['id'])->where("status", 0)->update(['is_deleted' => 1]);
        $save_data['is_cancel'] = 8;
        $save_data['boss_confirm'] = 0;
        $save_data['id'] = $data['id'];
        $r = DataService::save("Contacts", $save_data);

        $approval['contacts_id'] = $data['id'];
        $approval['admin_id'] = $data['pid'];
        $approval['manage_type'] = 1;
        $approval['type'] = $type ? $type : 0;
        $r = DataService::save("ContactsApproval", $approval);

        if ($data['pid']) {
            $send_data['url'] = 'http://chexinyuan.com/index.php/manage/user/get_approval_list.html';
            $send_data['first'] = '合同编号：' . $data['contract_number'] . ' ,等待审核';
            $send_data['keyword1'] = '';
            $send_data['keyword2'] = '';
            extend_push::notify($data['pid'], ['status' => 200, 'msg' => '合同编号：' . $data['contract_number'] . ' ,等待审核', 'data' => $send_data]);
        }
    }

    //通知用户
    public function contract_push_client($data)
    {
        $save_data['is_cancel'] = 4;
        $save_data['boss_confirm'] = 1;
        $save_data['client_confirm'] = 0;
        $save_data['id'] = $data['id'];
        $r = DataService::save("Contacts", $save_data);

        if (!$r) {
            return false;
        }
        PushService::confirm($data);
        return true;
    }


    /**
     * 支付记录详细
     * @param $id
     * @param $contract_info
     * @return array
     */
    public function pay_log($id, $contract_info)
    {
        $financeModel = new  FinanceModel();
        $income_type = $financeModel->get_incom_type();
        $db = Db::name("Finance");
        $db->where("is_deleted", 0);
        $db->where("status", 1);
        $db->where("contacts_id", $id);
        $db->where("type", 0);
        $list = $db->select();
        $pay_log = [];

        foreach ($list as $key => $val) {
            $pay_log[$key]['pic_url'] = get_site_url($val['certificate']);
            $pay_log[$key]['title'] = "车厘子汽车收款凭证";
            $income = $income_type[$val['option']];
            $pay_log[$key]['content'] = '兹收到客户' . $contract_info["nickname"] . '的购买：' . $contract_info["brand_name"] . ' .' . $contract_info["car_model"] . ' ' . $contract_info["car_color"] . '的（' . $income . '）。金额：' . $val["money"] . '元 大写：' . get_amount($val["money"]);
            $pay_log[$key]['date'] = format_datetime($val['create_at'], "Y 年 m 月 d 日");
        }
        return $pay_log;
    }

    /**
     * 交车前检查
     */
    public function apply_traffic_check($contact_id)
    {
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            return ['code' => 400, 'msg' => '信息不存在', 'data' => ''];
        }
        if ($contact_info['contract_type'] == 2) {
            return ['code' => 5001, 'msg' => '该合同为订车合同，不能提车', 'data' => ''];
        }
        //TODO 总收入
        $all_in = Db::name("Finance")->where("contacts_id",$contact_id)->where("type",0)->sum("money");
        $all_out = Db::name("Finance")->where("contacts_id",$contact_id)->where("type",1)->sum("money");
        //退款的金额
        $refund_money = Db::name("Finance")->where("contacts_id",$contact_id)->where("type",1)->where("option",9)->sum("money");
        $all_poundage = Db::name("Finance")->where("contacts_id",$contact_id)->sum("poundage");
        $pay_money  = $all_in - $all_out - $all_poundage;
        $check_money = $all_in - $refund_money - $contact_info['transaction_price'];

        if($check_money != 0 ){
            return ['code' => 5002, 'msg' => '收入不等于合同价不能交车', 'data' => ''];
        }

        if($pay_money < 0){
            return ['code' => 5002, 'msg' => '收入是负的，不能提车', 'data' => ''];
        }
        if ($contact_info['transaction_price'] > $contact_info['pay_money']) {
            return ['code' => 5002, 'msg' => '车款还未收完，不能提车', 'data' => ''];
        }
        if (!$contact_info['frame_number']) {
            return ['code' => 5003, 'msg' => '暂未上传车架号，不能提车', 'data' => ''];
        }
        if (!$contact_info['products_check']) {
            return ['code' => 5005, 'msg' => '精品检查暂未完成，不能提车', 'data' => ''];
        }
        if (!$contact_info['is_insuramce'] || !$contact_info['is_logistics']) {
            return ['code' => 5006, 'msg' => '手续未操作完成，不能提车', 'data' => ''];
        }
        return ['code' => 200, 'msg' => '', 'data' => $contact_info];
    }


    //销售文员显示消息
    public function set_clerk_show($savedata)
    {
        //TODO 分配会员使用
        $c_r = DataService::save("Contacts", $savedata);
        if (!$c_r) {
            return false;
        }
        $is_exist = Db::name("Repertory")->where("contacts_id", $savedata['id'])->count();
        if ($is_exist) {
            return true;
        }
        $repertoryService = new RepertoryService();
        $data['contacts_id'] = $savedata['id'];
        $data['status'] = 0;
        $data['ps'] = '';
        $repertoryService->add_log($data);
        return true;
    }

    /**
     * 交车推送确认
     * @param $data
     * @return bool
     */
    public function jiaoche_push($data)
    {
        PushService::jiaoche_push($data);
        return true;
    }


    /**
     * 申请提现到余额
     * @param $contact_id
     * @param int $is_withdraw
     * @return array
     */
    public function apply_withdraw($contact_id, $is_withdraw = 1)
    {
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            return ['code' => 400, 'msg' => '信息不存在', 'data' => ''];
        }
        if ($contact_info['contract_type'] == 2) {
            return ['code' => 5001, 'msg' => '该合同为订车合同，不能提现', 'data' => ''];
        }
        if ($contact_info['transaction_price'] > $contact_info['pay_money']) {
            return ['code' => 5002, 'msg' => '车款还未收完，不能提现', 'data' => ''];
        }
        if (!$contact_info['frame_number']) {
            return ['code' => 5003, 'msg' => '暂未上传车架号，不能提现', 'data' => ''];
        }
        if (!$contact_info['products_check']) {
            return ['code' => 5005, 'msg' => '精品检查暂未完成，不能提现', 'data' => ''];
        }
        if (!$contact_info['is_insuramce'] || !$contact_info['is_process'] || !$contact_info['is_logistics'] || !$contact_info['is_license']) {
            return ['code' => 5006, 'msg' => '手续未操作完成，不能提现', 'data' => ''];
        }
        if (!$contact_info['pass_code']) {
            return ['code' => 5006, 'msg' => '放行条未生成，不能提现', 'data' => ''];
        }
        if ($contact_info['status'] != 1) {
            return ['code' => 5006, 'msg' => '合同状态未完成，不能提现', 'data' => ''];
        }
        if ($is_withdraw == 1) {
            if ($contact_info['is_withdraw_ok'] != 0 && $contact_info['is_withdraw_ok'] != 2) {
                return ['code' => 5006, 'msg' => '已经申请了提现 或者 已经提现了', 'data' => ''];
            }
        }else{
            //验证是否生成了结算记录
//            $count = Db::name("OrderSettlement")->where("contacts_id",$contact_id)->where("is_deleted",0)->count();
//            if($count<=0){
//                return ['code' => 5006, 'msg' => '未生成结算数据，请去“完成合同”处生成', 'data' => ''];
//            }
        }
        return ['code' => 200, 'msg' => '', 'data' => $contact_info];
    }

    /**
     * 操作提现申请
     * @param $contract_info
     */
    public function do_apply_withdraw($contract_info)
    {
        $savedata['id'] = $contract_info['id'];
        $savedata['is_withdraw_ok'] = 3;
        $savedata['withdraw_desc'] = '';
        $r = DataService::save("Contacts", $savedata);
        //TODO 通知财务审核
        if ($r) {
            return true;
        }
        return false;
    }
}