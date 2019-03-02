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

use app\api\model\ContactsApprovalModel;
use app\api\model\ContactsModel;
use app\api\model\InsuranceModel;
use app\api\model\SystemUserModel;
use service\DataService;
use service\PushService as extend_push;
use think\Db;

/**
 * 销售经理操作
 * Class FansService
 * @package app\wechat
 */
class ManagerService
{

    /**
     * 合同审批
     * @param $data
     * @return bool
     */
    public function do_approval($data)
    {
        $id = $data['id'];
        $contractApprovalModel = new ContactsApprovalModel();
        $contractApprovalInfo = $contractApprovalModel->getOne($id);
        if (!$contractApprovalInfo) {
            return false;
        }
        $contract_id = $contractApprovalInfo['contacts_id'];
        $contract['id'] = $contract_id;

        $approval['id'] = $id;
        $approval['status'] = $data['status'];
        if ($approval['status'] == 2) {
            $approval['false_reason'] = $data['reason'];
            $contract['false_reason'] = $data['reason'];
            $contract['is_cancel'] = 7;     //审核失败
            $contract['manage_confirm'] = 0;
        } else {
            $contract['is_cancel'] = 0;     //审核通过
            $contract['manage_confirm'] = 1;     //审核通过
        }

        //预算
        $budget_info = Db::name("Budget")->where("contacts_id", $contract_id)->find();

        //合同信息
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($contract_id);

        $contractService = new ContractService();
        Db::startTrans();
        $r = DataService::save("ContactsApproval", $approval);
        if (!$r) {
            Db::rollback();
            return false;
        }
        $cr = DataService::save("Contacts", $contract);
        if (!$cr) {
            Db::rollback();
            return false;
        }
        Db::commit();

        if ($data['status'] == 1) {
            if ($budget_info['company_gross_profit'] > 5000) {
                //总经理审批
                $leader = $this->get_leader($contractApprovalInfo['admin_id']);
                if ($leader) {
                    $push_data['id'] = $contract_info['id'];
                    $push_data['contract_number'] = $contract_info['contract_number'];
                    $push_data['pid'] = $leader['pid'];
                    $contractService->contact_zong_manage($push_data, 0);
                }
            } else {
                //用户审核
                if ($contract_info) {
                    $contractService->contract_push_client($contract_info);
                }
            }
        } else {
            //通知销售，合同审核不通过
            if ($contract_info['saler_id']) {
                $send_data['url'] = '';
                $send_data['first'] =  '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过,需修改';
                $send_data['keyword1'] =  '';
                $send_data['keyword2'] =  '';
                extend_push::notify($contract_info['saler_id'], ['status' => 200, 'msg' => '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过', 'data' => $send_data]);
            }
        }

        return true;
    }

    /**
     * 找上级总经理
     */
    public function get_leader($user_id)
    {
        if (!$user_id) {
            return false;
        }
        $systemUserModel = new SystemUserModel();
        $system_user_info = $systemUserModel
            ->alias("su")
            ->join("ganwei", "ganwei.id = su.ganwei_id", 'LEFT')
            ->where("su.id", $user_id)
            ->field("ganwei.id,ganwei.pid")
            ->find();
        if (!$system_user_info || $system_user_info['pid'] == 0) {
            return false;
        }
        return $system_user_info;
    }
}