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
use service\DataService;
use service\ToolsService;
use service\WechatService;
use think\Db;

/**
 * 贷款服务
 * Class FansService
 * @package app\wechat
 */
class MortgageService
{
    public function do_examine($data)
    {
        $mortgage_info = Db::name("ContactsMortgage")->where("id", $data['id'])->find();
        if (!$mortgage_info) {
            return false;
        }
        $log['contacts_id'] = $mortgage_info['contacts_id'];
        if ($data['status'] == 1) {
            //审核通过，发通知
            $log['desc'] = '按揭审核通过，贷款金额' . $data['money'] . '元';
            $log['status'] = '1';
        } else {
            $log['desc'] = '按揭审核不通过，原因：' . $data['false_reason'];
            $log['status'] = '2';
        }
        $r = DataService::save("MortgageLog", $log);
        if (!$r) {
            return false;
        }
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($mortgage_info['contacts_id']);
        if ($data['status'] == 1) {
//            付款大于0.3
//            $precent_money = $contract_info['transaction_price'] * 0.3;
//            if($contract_info['pay_money'] > $precent_money){
//                $contract['clerk_show'] = 1;
//            }
            $contract['clerk_show'] = 1;
            $contract['loan_money'] = $data['money'];
            $contract['id'] = $mortgage_info['contacts_id'];
            $contractService = new ContractService();
            $r = $contractService->set_clerk_show($contract);
            if (!$r) {
                return false;
            }
        }
        $data['user_id'] = $contract_info['user_id'];
        $data['nickname'] = $contract_info['nickname'];
        $data['phone'] = $contract_info['phone'];
        $data['brand_name'] = $contract_info['brand_name'];
        $data['car_color'] = $contract_info['car_color'];
        $data['car_model'] = $contract_info['car_model'];
        PushService::mortgage_send($data);
    }
}