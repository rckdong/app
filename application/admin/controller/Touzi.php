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

namespace app\admin\controller;

use app\api\service\BudgetService;
use app\api\service\ContractService;
use controller\BasicAdmin;
use service\DataService;
use service\PushService as extend_push;
use think\Db;

/**
 * 员工管理控制器
 * Class User
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 18:12
 */
class Touzi extends BasicAdmin
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';

    /**
     * 用户列表
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function index()
    {
        $info['all_count'] = Db::name("Contacts")->where("is_temp", 0)->count();
        $info['finish_count'] = Db::name("Contacts")->where("status", 1)->count();
        $info['out'] = Db::name("Administrative")->where("type", 1)->sum("money");
        $info['income'] = Db::name("Administrative")->where("type", 0)->sum("money");
        $info['f_income'] = Db::name("Finance")->where("type", 0)->sum("money");
        $info['f_out'] = Db::name("Finance")->where("type", 1)->sum("money");
        $info['all'] =  $info['income'] + $info['f_income'] - $info['out'] -$info['f_out'];
        $this->assign("info",$info);
        return $this->fetch('', ['title' => '主页']);
    }

    /**
     * 审批列表
     */
    public function approval_list()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsApproval")->order('id desc')->where("manage_type",1);

        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        $this->title = "合同审批";
        return parent::_list($db);
    }

    public function _approval_list_data_filter(&$list)
    {
        foreach ($list as $key => $val) {
            $info = Db::name("Contacts")
                ->where('id', $val['contacts_id'])
                ->field("saler_id,contract_number,book_time,nickname,brand_name,car_model,guidance_price,transaction_price,contract_type")
                ->find();
            foreach (["saler_id", "contract_number", "book_time", "nickname", "brand_name", "car_model", "guidance_price", "transaction_price", "contract_type"] as $k => $v) {
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
    }


    public function get_info(){
        $get = $this->request->request();
        $this->table = 'Budget';
        if(!isset($get['contacts_id'])){
            $this->error("参数错误");
        }
        $info = Db::name($this->table)->where("contacts_id", $get['contacts_id'])->find();
        $contact_info = Db::name("Contacts")->where("id",$get['contacts_id'])->find();
        $budgetService = new BudgetService();
        if (!$info) {
            $info['id'] = 0;
            $info['contacts_id'] = $get['contacts_id'];
            $info['ticket_price'] = 0;
            $info['purchasing_cost'] = 0;
            $info['transportation_cost'] = 0;
            $info['purchase_tax_cost'] = 0;
            $info['commercial_insurance'] = 0;
            $info['compulsory_insurance'] = 0;
            $info['quality_assurance'] = 0;
            $info['shop_insurance'] = 0;
            $info['premium'] = 0;
            $info['quality_cost'] = 0;
            $info['card_cost'] = 0;
            $info['capital_cost'] = 0;
            $info['other_cost'] = 0;
            $info['total_income'] = $contact_info['transaction_price'];
            $info['satisfaction'] = 100;
            $info['type'] = '1';
            $info['sales_gross_profit'] = 0;
            $info['sales_net_profit'] = 0;
            $info['company_cost'] = 0;
            $info['deduction'] = 0;
            $info['sales_net_profit'] = 0;
            $info['sales_gross_profit'] = 0;
            $info['single_cost'] = 0;
            $info['company_gross_profit'] = 0;
            $info['commission'] = 0;
            $info['commission_second'] = 0;
            $info['commission_first'] = 0;
            if($contact_info['insurance_ids']){
                $insurances = $budgetService->get_insurance_ids($contact_info['insurance_ids']);
                $info['commercial_insurance'] = $insurances['commercial_insurance'];
                $info['compulsory_insurance'] = $insurances['compulsory_insurance'];
                $info['quality_assurance'] = $insurances['quality_assurance'];
                $info['shop_insurance'] = $insurances['shop_insurance'];
            }

            if($contact_info['products_ids']){
                $info['quality_cost'] = Db::name("Products")->whereIn("id",$contact_info['products_ids'])->sum('cost');
            }
//            $this->success_json('查无预算信息', $info);
            return $this->fetch("",['info'=>$info,'contract_info'=>$contact_info]);
        }
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
        if($contact_info['products_ids']){
            $info['quality_cost'] = Db::name("Products")->whereIn("id",$contact_info['products_ids'])->sum('cost');
        }
        $info['type'] = $info['type'].'';
        $info['commission'] = $info['sales_net_profit']*0.8;
        $info['commission_second'] = $info['sales_net_profit']*0.1;
        $info['commission_first'] = $info['sales_net_profit']*0.1;

        if(isset($get['export']) && $get['export'] ==1){
            $budgetService = new BudgetService();

            $budgetService->export_excel($info,$contact_info);
            exit();
        }

//        $this->success_json('预算信息', $info);
        return $this->fetch("",['info'=>$info,'contract_info'=>$contact_info]);
    }

//    操作审核
    public function edit(){
        $get = $this->request->request();
        if(!isset($get['id'])){
            $this->error("参数错误");
        }
        return $this->_form("ContactsApproval", 'form');
    }

    public function _form_result($result,$data){
        if($result != 1){
            return false;
        }
//        操作审核
        $id = $data['id'];
        $status = $data['status'];
        $false_reason = $data['false_reason'];
        $info = Db::name("ContactsApproval")->where("id",$id)->find();
        if(!$info){
            return false;
        }
        $contract_info = Db::name("Contacts")->where("id",$info['contacts_id'])->find();
        if(!$contract_info){
            return false;
        }
        $savedata['id'] = $info['contacts_id'];
        if($status == 1){
            $savedata['is_cancel'] = 0;
            $savedata['boss_confirm'] = 1;
        }else{
            $savedata['false_reason'] = $false_reason;
            $savedata['is_cancel'] = 9;
            $savedata['boss_confirm'] = 0;
        }
        $r = DataService::save("Contacts",$savedata);
        if(!$r){
            return false;
        }
        $contractService = new ContractService();
        if($status == 1){
            //通过，通知用户
            //用户审核
            if ($contract_info) {
                $contractService->contract_push_client($contract_info);
            }
        }else{
            //不通过通知销售
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

}
