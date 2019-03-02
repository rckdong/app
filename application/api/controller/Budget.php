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

namespace app\api\controller;

use app\api\model\AdministrativeModel;
use app\api\model\DepartmentModel;
use app\api\model\FinanceModel;
use app\api\model\SystemUserModel;
use app\api\service\BudgetService;
use controller\BasicApi;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\App;
use app\api\model\UserModel;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Budget extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Budget';

    public function get_type()
    {
        $res = [
            [
                'id' => 1,
                'name' => '一次性付款方案一'
            ],
            [
                'id' => 2,
                'name' => '按揭付款方案二'
            ],
            [
                'id' => 3,
                'name' => '一次性店保付款方案三'
            ],
            [
                'id' => 4,
                'name' => '按揭店保付款方案四'
            ],
            [
                'id' => 5,
                'name' => '4s全包方案五'
            ],
            [
                'id' => 6,
                'name' => '无保险方案六'
            ],
        ];
        $this->success_json('预算信息', $res);
    }

    /**
     * 预算
     */
    public function calculation()
    {
        $get = $this->request->request();
        if (!isset($get['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
//        `ticket_price` double(10,2) DEFAULT '0.00' COMMENT '开票价',
//        `purchasing_cost` double(10,2) DEFAULT '0.00' COMMENT '车辆采购成本',
//        `transportation_cost` double(10,2) DEFAULT '0.00' COMMENT '运输成本',
//        `purchase_tax_cost` double(10,2) DEFAULT '0.00' COMMENT '购置税成本',
//        `commercial_insurance` double(10,2) DEFAULT '0.00' COMMENT '商业险',
//        `compulsory_insurance` double(10,2) DEFAULT NULL COMMENT '交强/车船',
//        `quality_assurance` double(10,2) DEFAULT NULL COMMENT '质保',
//        `shop_insurance` double(10,2) DEFAULT NULL COMMENT '店保',
//        `premium` double(10,2) DEFAULT NULL COMMENT '上牌费',
//        `quality_cost` double(10,2) DEFAULT '0.00' COMMENT '精品成本',
//        `card_cost` double(10,2) DEFAULT '0.00' COMMENT '刷卡成本',
//        `capital_cost` double(10,2) DEFAULT '0.00' COMMENT '垫资成本',
//        `other_cost` double(10,2) DEFAULT '0.00' COMMENT '其他成本',
//        `total_income` double(10,2) DEFAULT '0.00' COMMENT '财务总收入',
//        `satisfaction` int(11) DEFAULT '1' COMMENT '客户满意度100分',
//        `type` tinyint(1) DEFAULT '0' COMMENT '结算类型',
//        `single_cost` double(10,2) DEFAULT '0.00' COMMENT '单台成本',
//        `company_gross_profit` double(10,2) DEFAULT '0.00' COMMENT '公司毛利润',
//        `sales_gross_profit` double(10,2) DEFAULT '0.00' COMMENT '销售毛利润',
//        `company_net_profit` double(10,2) DEFAULT NULL COMMENT '公司净利润',
//        `sales_net_profit` double(10,2) DEFAULT '0.00' COMMENT '销售净利润',
//        `company_cost` double(10,2) DEFAULT '0.00' COMMENT '公司应收总成本',
//        `deduction` varchar(255) DEFAULT '0' COMMENT '客户满意度扣分',

        $savedata['contacts_id'] = $get['contacts_id'];
        $savedata['ticket_price'] = isset($get['ticket_price']) ? $get['ticket_price'] : 0;
        $savedata['purchasing_cost'] = isset($get['purchasing_cost']) ? $get['purchasing_cost'] : 0;
        $savedata['transportation_cost'] = isset($get['transportation_cost']) ? $get['transportation_cost'] : 0;
        $savedata['purchase_tax_cost'] = isset($get['purchase_tax_cost']) ? $get['purchase_tax_cost'] : 0;
        $savedata['commercial_insurance'] = isset($get['commercial_insurance']) ? $get['commercial_insurance'] : 0;
        $savedata['compulsory_insurance'] = isset($get['compulsory_insurance']) ? $get['compulsory_insurance'] : 0;
        $savedata['quality_assurance'] = isset($get['quality_assurance']) ? $get['quality_assurance'] : 0;
        $savedata['shop_insurance'] = isset($get['shop_insurance']) ? $get['shop_insurance'] : 0;
        $savedata['premium'] = isset($get['premium']) ? $get['premium'] : 0;
        $savedata['quality_cost'] = isset($get['quality_cost']) ? $get['quality_cost'] : 0;
        $savedata['card_cost'] = isset($get['card_cost']) ? $get['card_cost'] : 0;
        $savedata['capital_cost'] = isset($get['capital_cost']) ? $get['capital_cost'] : 0;
        $savedata['other_cost'] = isset($get['other_cost']) ? $get['other_cost'] : 0;
        $savedata['total_income'] = isset($get['total_income']) ? $get['total_income'] : 0;
        $savedata['satisfaction'] = isset($get['satisfaction']) ? $get['satisfaction'] : 0;
        $type = $savedata['type'] = isset($get['type']) ? $get['type'] : 1;

        $percent = 0.008;           //开票基数
        $insurance_base = 1.5;      //保险乘积
        $register_cost = 1000;      //上牌成本
        $car_management_cost = 800;  //商品车管理
        $mortgage_cost = 0;             //按揭手续费
        switch ($type) {
            case 1:
                //一次性付款方案一

                break;
            case 2:
                //按揭付款方案二
                $percent = 0.01;
                $mortgage_cost = 300;
                break;
            case 3:
                $insurance_base = 1.2;
                //一次性店保付款方案三
                break;
            case 4:
                $percent = 0.01;
                $insurance_base = 1.2;
                $mortgage_cost = 300;
                //按揭店保付款方案四
                break;
            case 5:
                //4s全包方案5
                $percent = 0.008;
                $insurance_base = 1.2;
                $register_cost = 500;
                $car_management_cost = 0;
                break;
            case 6:
                //按揭付款方案二
                $percent = 0.02;
                $savedata['commercial_insurance'] = 0;
                $savedata['compulsory_insurance'] = 0;
                $savedata['quality_assurance'] = 0;
                $savedata['shop_insurance'] = 0;
                break;
        }

        //        单台成本
        $savedata['single_cost'] = $savedata['purchasing_cost'] + $savedata['transportation_cost'] + $savedata['purchase_tax_cost'] + $savedata['commercial_insurance'] + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'] + 500 + $savedata['quality_cost'] + $savedata['card_cost'] + $savedata['capital_cost'] + 100 + $mortgage_cost + $savedata['other_cost'];

        //        公司毛利润
        $savedata['company_gross_profit'] = ($savedata['ticket_price'] * $percent) + $savedata['purchasing_cost'] + $savedata['transportation_cost'] + $savedata['purchase_tax_cost'] + ($savedata['commercial_insurance'] * $insurance_base) + $savedata['compulsory_insurance'] + $savedata['quality_assurance'] + $savedata['shop_insurance'] + $savedata['premium'] + $savedata['quality_cost'] + $savedata['card_cost'] + $savedata['capital_cost'] + $car_management_cost + $mortgage_cost + $savedata['other_cost'];

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

        $res = DataService::save($this->table, $savedata, 'contacts_id');

        $return_data['company_cost'] = $savedata['company_cost'];
        $return_data['deduction'] = $savedata['deduction'];
        $return_data['sales_net_profit'] = $savedata['sales_net_profit'];
        $return_data['sales_gross_profit'] = $savedata['sales_gross_profit'];
        $return_data['single_cost'] = $savedata['single_cost'];
        $return_data['company_gross_profit'] = $savedata['company_gross_profit'];
        $return_data['commission'] = $savedata['sales_net_profit']*0.8;
        $return_data['commission_second'] = $savedata['sales_net_profit']*0.1;
        $return_data['commission_first'] = $savedata['sales_net_profit']*0.1;

        if (!$res) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('保存成功', $return_data);
    }



    /**
     * 获取预算信息
     */
    public function get_info()
    {
        $get = $this->request->request();
        if (!isset($get['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
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
            $this->success_json('查无预算信息', $info);
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
//        if($contact_info['insurance_ids']){
//            $insurances = $budgetService->get_insurance_ids($contact_info['insurance_ids']);
//            $info['commercial_insurance'] = $insurances['commercial_insurance'];
//            $info['compulsory_insurance'] = $insurances['compulsory_insurance'];
//            $info['quality_assurance'] = $insurances['quality_assurance'];
//            $info['shop_insurance'] = $insurances['shop_insurance'];
//        }

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

        $this->success_json('预算信息', $info);
    }


}
