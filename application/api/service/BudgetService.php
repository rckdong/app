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

use app\api\model\FinanceModel;
use app\api\model\InsuranceModel;
use app\api\model\SystemUserModel;
use service\DataService;
use think\Db;

/**
 * 预算处理
 * Class FansService
 * @package app\wechat
 */
class BudgetService
{

    public function export_excel($info, $contact_info, $do_type = false)
    {
        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费
        $all_in = 0;
        switch ($info['type']) {
            case 1:
                //一次性付款方案一
                $name = "一次性付款方案一";
                $all_in = ($info["commercial_insurance"] * $insurance_base) + $info['compulsory_insurance'] + $info['quality_assurance'] + $info['shop_insurance'];
                break;
            case 2:
                //按揭付款方案二
                $name = "按揭付款方案二";
                $percent = 0.01;
                $mortgage_cost = 300;
                $all_in = ($info["commercial_insurance"] * $insurance_base) + $info['compulsory_insurance'] + $info['quality_assurance'] + $info['shop_insurance'];
                break;
            case 3:
                //一次性店保付款方案三
                $name = "一次性店保付款方案三";
                $insurance_base = 1.2;
                $all_in = $info["commercial_insurance"] + $info['compulsory_insurance'] + $info['quality_assurance'] + ($info['shop_insurance'] * $insurance_base);
                break;
            case 4:
                //按揭店保付款方案四
                $name = "按揭店保付款方案四";
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                $all_in = $info["commercial_insurance"] + $info['compulsory_insurance'] + $info['quality_assurance'] + ($info['shop_insurance'] * $insurance_base);

                break;
            case 5:
                //4s全包方案5
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 500;
                $name = "4s全包方案5";
                $all_in = ($info["commercial_insurance"] * $insurance_base) + $info['compulsory_insurance'] + $info['quality_assurance'] + $info['shop_insurance'];
                break;
            case 6:
                //无保险二
                $percent = 0.02;
                $name = "无保险";
                break;
        }

        $systemUserModel = new SystemUserModel();
        $saler_info = $systemUserModel->getOne(['id' => $contact_info['saler_id']]);

        $strTable = '<table width="1100" border="1">';

        if ($do_type) {
            $strTable .= '<tr><td colspan="12" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">结算</td></tr>';
        } else {
            $strTable .= '<tr><td colspan="12" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">预算</td></tr>';
        }
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >销售顾问</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $saler_info["name"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">客户名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $contact_info["nickname"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">车架号</td>';
        if ($do_type) {
            $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*">' . $contact_info["frame_number"] . '</td>';
        } else {
            $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*"></td>';
        }

        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">合同号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $contact_info["contract_number"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">店面</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">龙岗店</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $name . '</td>';
        $strTable .= '<td colspan="3" style="text-align:center;font-size:12px;" width="*" >口 0.6%   口0.8%    口1%</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">车型</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*">' . $contact_info["brand_name"] . ' ' . $contact_info["car_model"] . ' ' . $contact_info["car_color"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">供应商</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">交车日期</td>';

        if ($do_type) {
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $contact_info["get_time"] . '</td>';
        } else {
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">待定</td>';
        }

        $strTable .= '</tr>';

        $strTable .= '<tr></tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >项目</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >金额</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >备注</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">支出明细</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >开票价（0.6-1）%</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . ($info["ticket_price"] * $percent) . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["ticket_price"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >车辆采购成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["purchasing_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际付出车款	</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >运输成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["transportation_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >大板车+小板车</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >购置税成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["purchase_tax_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实缴</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td rowspan="2" style="text-align:center;font-size:12px;" width="*" >保险成本</td>';
        $strTable .= '<td rowspan="2" style="text-align:center;font-size:12px;" width="*" >' . $all_in . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >商业险</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >交强/车船</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >质保 85折</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >店保+12%</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["commercial_insurance"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["compulsory_insurance"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["quality_assurance"] . '</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $info["shop_insurance"] . '</td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >上牌成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["premium"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >中规固定</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >精品成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["quality_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >表格为准</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >刷卡成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["card_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际为准</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >商品车管理</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $car_management_cost . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >固定</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >金融按揭</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $mortgage_cost . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >放款1笔300+</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >垫资成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["capital_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >实际发生</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >其他成本</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["other_cost"] . '</td>';
        $strTable .= '<td colspan="2" style="text-align:center;font-size:12px;" width="*" ></td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*"></td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >单台成本</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["single_cost"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司毛利润</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["company_gross_profit"] . '</td>';
        $strTable .= '</tr>';


        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >客户满意度</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["satisfaction"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司应收总成本</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["company_cost"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >公司净利润</td>';
        $strTable .= '<td colspan="5" style="text-align:center;font-size:12px;" width="*" >' . $info["company_net_profit"] . '</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" ></td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >销售毛利润</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >销售净利润</td>';
        $strTable .= '<td  colspan="2"  style="text-align:center;font-size:12px;" width="*" >销售经纪人佣金80%</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >上级奖励</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >总经理奖励</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" >销售总价</td>';
        $strTable .= '</tr>';

        $strTable .= '<tr>';
        $strTable .= '<td  colspan="2" style="text-align:center;font-size:12px;" width="*" >' . $info["total_income"] . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["sales_gross_profit"] . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["sales_net_profit"] . '</td>';
        $strTable .= '<td  colspan="2"  style="text-align:center;font-size:12px;" width="*" >' . $info["sales_net_profit"] * 0.8 . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["sales_net_profit"] * 0.1 . '</td>';
        $strTable .= '<td  style="text-align:center;font-size:12px;" width="*" >' . $info["sales_net_profit"] * 0.1 . '</td>';
        $strTable .= '</tr>';


        if ($do_type) {
            //查询出收支记录
            $strTable .= '<tr></tr>';
            $strTable .= '<tr><td colspan="12" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">收支记录</td></tr>';
            $finance_list = Db::name("Finance")->where("contacts_id", $contact_info['id'])->where("is_ex", 0)->where("status", 1)->where("is_deleted", 0)->select();
            $FinaceModel = new FinanceModel();
            $income_type = $FinaceModel->get_incom_type();
            $pay_type = $FinaceModel->get_pay_type();
            $out_type = $FinaceModel->get_out_type();
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >合同编号</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >类型</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">合同类型</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收支类型</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收支方式</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">金额</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">手续费</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">备注</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">创建时间</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">操作人</td>';
            $strTable .= '</tr>';
            foreach ($finance_list as $k => $v) {
                $contract_type = ($contact_info['contract_type'] == 1) ? '定车合同' : '订车合同';
                $type = ($v['type'] == 1) ? '支出' : '收入';
                $contract_number = $contact_info['contract_number'];
                $contract_types = $type;

                $option = '';
                $pay_type_item = $pay_type[$v['pay_type']];

                if ($v['type'] == 1) {
                    //支出
                    $option = $out_type[$v['option']];

                } else {
                    //收入
                    $option = $income_type[$v['option']];
                }

                $clerk_info = Db::name("SystemUser")->where("id", $v['admin_id'])->find();
                $clerk_name = isset($clerk_info['name']) ? $clerk_info['name'] : '';
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $contract_number . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $type . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_type . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $option . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $pay_type_item . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $v["money"] . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $v["poundage"] . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $v["ps"] . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $v["create_at"] . '</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $clerk_name . '</td>';
                $strTable .= '</tr>';
            }

        }

        $strTable .= '</table>';

        if ($do_type) {
            downloadExcel($strTable, "结算导出_合同编号" . $contact_info["contract_number"]);
        } else {
            downloadExcel($strTable, "预算导出_合同编号" . $contact_info["contract_number"]);
        }

    }

//    获取保险的价格
    public function get_insurance_ids($ids)
    {
        $insuranceModel = new InsuranceModel();
        $insurances = $insuranceModel->getByIds($ids);
        $data['commercial_insurance'] = 0;
        $data['compulsory_insurance'] = 0;
        $data['quality_assurance'] = 0;
        $data['shop_insurance'] = 0;
        foreach ($insurances as $k => $v) {
            switch ($v['type']) {
                case '0':
                    $data['commercial_insurance'] = $data['commercial_insurance'] + $v['price'];
                    break;
                case '1':
                    $data['compulsory_insurance'] = $data['compulsory_insurance'] + $v['price'];
                    break;
                case '2':
                    $data['quality_assurance'] = $data['quality_assurance'] + $v['price'];
                    break;
                case '3':
                    $data['shop_insurance'] = $data['shop_insurance'] + $v['price'];
                    break;
            }
        }
        return $data;
    }

    public function calculation($contact_id, $insert_data)
    {
        $savedata = Db::name("Budget")->where("contacts_id", $contact_id)->find();
        if (!$savedata) {
            return false;
        }
        $savedata['total_income'] = $insert_data['transaction_price'];
        if ($insert_data['products_ids']) {
            $savedata['quality_cost'] = Db::name("Products")->whereIn("id", $insert_data['products_ids'])->sum('cost');
        } else {
            $savedata['quality_cost'] = 0;
        }
        $type = $savedata['type'];
        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费
        $insurance_money = 0;
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
                //一次性店保付款方案三
                $insurance_money = $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + ($insurance_base * $savedata['shop_insurance']);

                break;
            case 4:
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                $insurance_money = $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + ($insurance_base * $savedata['shop_insurance']);

                //按揭店保付款方案四
                break;
            case 5:
                //4s全包方案5
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 500;
                $car_management_cost = 0;
                $insurance_money = ($savedata['commercial_insurance'] * $insurance_base) + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'];

                break;
            case 6:
                //按揭付款方案二
                $percent = 0.02;
                $savedata['commercial_insurance'] = 0;
                $savedata['compulsory_insurance'] = 0;
                $savedata['quality_assurance'] = 0;
                $savedata['shop_insurance'] = 0;
                $insurance_money = 0;
                break;
        }
        //        单台成本
        $savedata['single_cost'] = $savedata['purchasing_cost'] + $savedata['transportation_cost'] + $savedata['purchase_tax_cost'] + $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'] + 500 + $savedata['quality_cost'] + $savedata['card_cost'] + $savedata['capital_cost'] + 100 + $mortgage_cost + $savedata['other_cost'];

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

        $res = DataService::save("Budget", $savedata);
        return true;
    }
}