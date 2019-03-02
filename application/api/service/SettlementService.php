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
use app\api\model\InsuranceModel;
use app\api\model\SystemUserModel;
use service\DataService;
use think\Db;

/**
 * 结算处理
 * Class FansService
 * @package app\wechat
 */
class SettlementService
{
    public function add_log($contract_id, $star)
    {
        if (!$contract_id) {
            return false;
        }
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($contract_id);
        $contacts_process_info = Db::name("ContactsProcess")->where("contacts_id", $contract_id)->find();
        if (!$contract_info) {
            return false;
        }
        $savedata['total_income'] = $contract_info['pay_money'];
        if ($contract_info['products_ids']) {
            $savedata['quality_cost'] = Db::name("Products")->whereIn("id", $contract_info['products_ids'])->sum('cost');
        } else {
            $savedata['quality_cost'] = 0;
        }

        $contacts_insurance_info = Db::name('ContactsInsurance')->where("contacts_id", $contract_info['id'])->find();

        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费

        $type = $savedata['type'] = $this->get_type($contract_info, $contacts_process_info, $contacts_insurance_info);

        $FinaceModel = new FinanceModel();
        $output_type = $FinaceModel->get_out_type();

        $savedata['purchasing_cost'] = $this->get_money($output_type[0], $contract_info);
        $savedata['purchase_tax_cost'] = $this->get_money($output_type[1], $contract_info);
        $savedata['commercial_insurance'] = $this->get_money($output_type[2], $contract_info);
        $savedata['compulsory_insurance'] = $this->get_money($output_type[3], $contract_info);
        $savedata['shop_insurance'] = $this->get_money($output_type[4], $contract_info);
        $savedata['quality_assurance'] = $this->get_money($output_type[5], $contract_info);
        $savedata['premium'] = $this->get_money($output_type[7], $contract_info);
        $savedata['premium']  = 1000;
        $savedata['transportation_cost'] = $this->get_money($output_type[8], $contract_info);
        $savedata['card_cost'] = $this->get_card_cost($contract_info);
        $savedata['capital_cost'] = $this->get_money($output_type[10], $contract_info);
        $refund_money = $this->get_money($output_type[9],$contract_info);
        $other_money = $this->get_money($output_type[11],$contract_info);
        $savedata['other_cost'] = $refund_money + $other_money;        //退款+其他
        $savedata['satisfaction'] = $star * 10;
        $savedata['ticket_price'] = isset($contacts_process_info['invoice_money']) ? $contacts_process_info['invoice_money'] : 0;

        $insurance_money = 0;
        $register_cost = 500;
        switch ($type) {
            case 1:
                //一次性付款方案一
                $insurance_money = ($savedata['commercial_insurance'] * $insurance_base) + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'];
                break;
            case 2:
                //按揭付款方案二
                $percent = 0.01;
                $mortgage_cost = 300;
                $insurance_money = ($savedata['commercial_insurance'] * $insurance_base) + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'];
                break;
            case 3:
                $insurance_base = 1.2;
                $insurance_money = $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + ($savedata['shop_insurance'] * $insurance_base);
                //一次性店保付款方案三
                break;
            case 4:
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                $insurance_money = $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + ($savedata['shop_insurance'] * $insurance_base);
                //按揭店保付款方案四
                break;
            case 5:
                //4s全包方案5
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 0;
                $car_management_cost = 0;
                $insurance_money = ($savedata['commercial_insurance'] * $insurance_base) + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'];
                break;
            case 6:
                //按揭付款方案二——无保险
                $percent = 0.02;
                $savedata['commercial_insurance'] = 0;
                $savedata['compulsory_insurance'] = 0;
                $savedata['quality_assurance'] = 0;
                $savedata['shop_insurance'] = 0;
                $insurance_money = 0;
                break;
        }

//        单台成本
        $savedata['single_cost'] = $savedata['purchasing_cost'] + $savedata['transportation_cost'] + $savedata['purchase_tax_cost'] + $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'] + $register_cost + $savedata['quality_cost'] + $savedata['card_cost'] + $savedata['capital_cost'] + 100 + $mortgage_cost + $savedata['other_cost'];

        //        公司毛利润
        $savedata['company_gross_profit'] = ($savedata['ticket_price'] * $percent) + $savedata['purchasing_cost'] + $savedata['transportation_cost'] + $savedata['purchase_tax_cost'] + $insurance_money + $savedata['premium'] + $savedata['quality_cost'] + $savedata['card_cost'] + $savedata['capital_cost'] + $car_management_cost + $mortgage_cost + $savedata['other_cost'];

        //销售毛利润
        $savedata['sales_gross_profit'] = $savedata['total_income'] - $savedata['company_gross_profit'];

        //销售毛利润判断(当销售毛利润 小于 (总收入-公司毛利润)/2 )
        $r = ($savedata['total_income'] - $savedata['single_cost']) / 2;
        if ($savedata['sales_gross_profit'] < $r) {
            $savedata['sales_gross_profit'] = $r;
        }

        //公司应收总成本
        $savedata['company_cost'] = $savedata['company_gross_profit'] - ($savedata['sales_gross_profit'] * 0.3) * (100 - $savedata['satisfaction']) * 0.01;

        //客户满意度扣分
        $savedata['deduction'] = $savedata['company_gross_profit'] - $savedata['company_gross_profit'] - ($savedata['sales_gross_profit'] * 0.3) * (100 - $savedata['satisfaction']) * 0.01;
        //销售净利润
        $savedata['sales_net_profit'] = $savedata['sales_gross_profit'] - abs($savedata['deduction']);

        //公司净利润
        $savedata['company_net_profit'] = $savedata['total_income'] - $savedata['single_cost'] - $savedata['sales_net_profit'];

        $savedata['contacts_id'] = $contract_id;
        $res = DataService::save("OrderSettlement", $savedata, "contacts_id");
        // 结算完成分红
        if ($res) {
            $this->fenhong($contract_info, $savedata);
        }
        return true;
    }

    /**
     * 分红
     * @param $contract_info
     * @param $savedata
     */
    public function fenhong($contract_info, $savedata)
    {
        $system_info = Db::name("SystemUser")->where("id", $contract_info['saler_id'])->field("id,status,ganwei_id")->find();
        $leader_id = 0;
        if ($system_info) {
            $leader = Db::name("Ganwei")->where("id", $system_info['ganwei_id'])->find();
            if ($leader) {
                $leader_id = $leader['pid'];
            }
        }
        $savedata['contacts_id'] = $contract_info['id'];
        $savedata['admin_id'] = $leader_id;
        $savedata['saler_id'] = $contract_info['saler_id'];
        $savedata['phone'] = $contract_info['phone'];
        $savedata['status'] = 0;
        $savedata['reward'] = $savedata['sales_net_profit'];

        $res = DataService::save("ContactsReward", $savedata,'contacts_id');
        return true;
    }

    /**
     * 获取类型
     * @param $contract_info
     * @param $contacts_process_info
     * @return int
     */
    public function get_type($contract_info, $contacts_process_info, $contacts_insurance_info)
    {
        //TODO 数据判断从财务那里读取
        //TODO 验证是否全包
        if (isset($contacts_process_info['is_quanbao']) && $contacts_process_info['is_quanbao'] > 0) {
            return 5;
        }
        if ($contract_info['buy_type'] == 1) {
            if ($contacts_insurance_info['commercial_insurance_three'] > 0) {
                return 4;
            } else {
                return 2;
            }
        }

        if ($contacts_insurance_info['commercial_insurance_three'] > 0) {
            return 3;
        } else {
            return 1;
        }
    }

    /**
     * 获取金额
     * @param $output_type
     * @param $contract_info
     * @return float|int
     */
    public function get_money($output_type, $contract_info)
    {
        $FinaceModel = new FinanceModel();
        return Db::name("Finance")->where("contacts_id", $contract_info['id'])->where("type", 1)->where("status", 1)->where("option", $FinaceModel->get_out_type_id($output_type))->sum('money');
    }

    public function get_card_cost($contract_info){
        return Db::name("Finance")->where("contacts_id", $contract_info['id'])->where("status",1)->where("is_deleted",0)->sum("poundage");
    }

    public function get_info($contacts_id){
        $ret = [
            'company_net_profit'=>0,
            'zmanage_profit'=>0,
            'sales_profit'=>0,
            'manage_profit'=>0,
        ];
        if(!$contacts_id){
            return $ret;
        }
        $info = Db::name("OrderSettlement")->where("contacts_id",$contacts_id)->find();
        if(!$info){
            return $ret;
        }
        $ret['company_net_profit'] = $info['company_net_profit'];
        $ret['zmanage_profit'] = $info['sales_net_profit'] * 0.1;
        $ret['sales_profit'] = ($info['sales_net_profit'] - $ret['zmanage_profit']) * 0.9;
        $ret['manage_profit'] = ($info['sales_net_profit'] - $ret['zmanage_profit']) * 0.1;
        return $ret;
    }
}