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

namespace app\manage\controller;

use app\api\service\BudgetService;
use app\api\service\ContractService;
use app\api\service\ManagerService;
use service\DataService;
use think\Db;
use service\PushService as extend_push;

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
    public $table = 'SystemUser';

    public function user_info()
    {
        return $this->fetch('public/error', ['title' => '用户信息']);
    }

    /**
     * 获取审批列表
     */
    public function get_approval_list()
    {
        $db = Db::name("ContactsApproval")->where("admin_id", $this->user_id)->order('id desc');

//        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        $db->where("status", 0);
        $db->where("is_deleted", 0);
        $list = $db->select();

        foreach ($list as $key => $val) {
            $info = Db::name("Contacts")
                ->where('id', $val['contacts_id'])
                ->field("saler_id,contract_number,book_time,nickname,brand_name,car_color,car_model,guidance_price,transaction_price,contract_type")
                ->find();
            foreach (["saler_id", "contract_number", "book_time", "nickname", "brand_name", "car_model", "guidance_price", "transaction_price", "contract_type", "car_color"] as $k => $v) {
                if ($v == 'saler_id') {
                    if (!isset($info[$v])) {
                        $list[$key]["saler_name"] = '';
                    } else {
                        $user_info = Db::name("system_user")->where(['id' => $info['saler_id']])->find();
                        $list[$key]["saler_name"] = $user_info['name'];
                    }
                }
                $list[$key][$v] = isset($info[$v]) ? $info[$v] : '';
            }
        }

        return $this->fetch('', ['list' => $list]);
    }

    public function budget_info()
    {
        $get = $this->request->request();
        $contract_id = intval($get['contacts_id']);
        $id = intval($get['id']);
        $budget_info = Db::name("ContactsApproval")->where("id", $id)->where("is_deleted",0)->find();
        if (!$budget_info) {
            return $this->fetch('public/error', ['title' => '审核信息不存在']);
        }
        if ($budget_info['status'] != 0) {
            return $this->fetch('public/error', ['title' => '已经审核']);
        }
        $info = Db::name("Budget")->where("contacts_id", $contract_id)->find();

        if (!$info) {
            return $this->fetch('public/error', ['title' => '查无预算']);
        }
        $contact_info = Db::name("Contacts")->where("id", $info['contacts_id'])->find();
        switch ($info['type']) {
            case 1:
                $info['type_name'] = '一次性付款方案一';

                break;
            case 2:
                $info['type_name'] = '按揭付款方案二';
                break;
            case 3:
                $info['type_name'] = '一次性店保付款方案三';
                break;
            case 4:
                $info['type_name'] = '按揭店保付款方案四';
                break;
            case 5:
                $info['type_name'] = '4s全包方案五';
                break;
            case 6:
                $info['type_name'] = '无保险方案六';
                break;
        }
//        if($contact_info['insurance_ids']){
//            $insurances = $budgetService->get_insurance_ids($contact_info['insurance_ids']);
//            $info['commercial_insurance'] = $insurances['commercial_insurance'];
//            $info['compulsory_insurance'] = $insurances['compulsory_insurance'];
//            $info['quality_assurance'] = $insurances['quality_assurance'];
//            $info['shop_insurance'] = $insurances['shop_insurance'];
//        }

        if ($contact_info['products_ids']) {
            $info['quality_cost'] = Db::name("Products")->whereIn("id", $contact_info['products_ids'])->sum('cost');
        }
        $info['type'] = $info['type'] . '';
        $info['commission'] = $info['sales_net_profit'] * 0.8;
        $info['commission_second'] = $info['sales_net_profit'] * 0.1;
        $info['commission_first'] = $info['sales_net_profit'] * 0.1;

        return $this->fetch('', ['title' => '预算查看', 'info' => $info, 'vo' => $contact_info, 'id' => $id]);
    }

    /**
     * 普通修改审核
     */
    public function update_confirm()
    {
        $get = $this->request->request();
        $contacts_id = $get['contacts_id'];
        $status = $get['status'];
        $false_reason = $get['false_reason'];
        $id = $get['id'];
        $info = Db::name("ContactsApproval")->where("id", $id)->find();
        if (!$info) {
            $this->error_json(400, "审核失败,请稍后操作", []);
        }

        if ($info['manage_type'] == 1) {
            $approval['id'] = $id;
            $approval['false_reason'] = $false_reason;
            $approval['status'] = $status;
            $r = DataService::save("ContactsApproval", $approval);
            if(!$r){
                $this->error_json(400, "修改失败，请稍后重试", []);
            }

            //总经理审核
            $contract_info = Db::name("Contacts")->where("id", $info['contacts_id'])->find();
            if (!$contract_info) {
                $this->error_json(400, "合同不存在", []);
            }
            $savedata['id'] = $info['contacts_id'];
            if ($status == 1) {
                $savedata['is_cancel'] = 0;
                $savedata['boss_confirm'] = 1;
            } else {
                $savedata['false_reason'] = $false_reason;
                $savedata['is_cancel'] = 9;
                $savedata['boss_confirm'] = 0;
            }
            $r = DataService::save("Contacts", $savedata);
            if (!$r) {
                $this->error_json(400, "保存失败，请稍后重试", []);
            }
            $contractService = new ContractService();
            if ($status == 1) {
                //通过，通知用户
                //用户审核
                if ($contract_info) {
                    $contractService->contract_push_client($contract_info);
                }
            } else {
                //不通过通知销售
                if ($contract_info['saler_id']) {
                    $send_data['url'] = '';
                    $send_data['first'] = '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过,需修改';
                    $send_data['keyword1'] = '';
                    $send_data['keyword2'] = '';
                    extend_push::notify($contract_info['saler_id'], ['status' => 200, 'msg' => '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过', 'data' => $send_data]);
                }
            }
            $this->success_json('修改成功', []);
        } else {
            $managerService = new ManagerService();
            $post['id'] = $id;
            $post['status'] = $status;
            $post['reason'] = $false_reason;
            $r = $managerService->do_approval($post);
            if (!$r) {
                $this->error_json(400, "审核失败,请稍后操作", []);
            }
            //TODO 修改合同
            $this->success_json('修改成功', []);
        }
    }
}
