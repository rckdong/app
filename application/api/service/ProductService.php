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
use app\api\model\InsuranceModel;
use service\DataService;
use think\Db;

/**
 * 精品处理
 * Class FansService
 * @package app\wechat
 */
class ProductService
{

    //添加精品处理记录
    public function add_log($contacts_id)
    {
        if (!$contacts_id) {
            return false;
        }
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($contacts_id, "id,products_check,products_ids");
        if (!$contract_info) {
            return false;
        }
        if ($contract_info['products_check'] != 0) {
            return false;
        }
        $savedata['id'] = $contract_info['id'];
        $savedata['products_check'] = 1;

        $product_ids = explode(',', $contract_info['products_ids']);

        //没购买精品
        if (count($product_ids) < 1 || !$product_ids) {
            return true;
        }

        Db::startTrans();
        $r = DataService::save("Contacts", $savedata);
        if (!$r) {
            Db::rollback();
            return false;
        }
        //添加精品产品到精品项目表
        $do = true;
        foreach ($product_ids as $val) {
            $pro_data['contacts_id'] = $contacts_id;
            $pro_data['product_id'] = $val;
            $pro_data['ps'] = '';
            $r = DataService::save("ProductCheck", $pro_data);
            if (!$r) {
                Db::rollback();
                $do = false;
                break;
            }
        }
        if (!$do) {
            return false;
        }
        Db::commit();
        return true;
    }

    //获取精品成本
    public function get_products_cost($products_ids)
    {
        if ($products_ids) {
            return Db::name("Products")->whereIn("id", $products_ids)->sum('cost');
        } else {
            return 0;
        }
    }

    /**
     * 精品项目推送
     */
    public function product_push($contract_info)
    {
        if (!$contract_info) {
            return false;
        }
        $send_data['user_id'] = $contract_info['user_id'];
        $send_data['contacts_id'] = $contract_info['id'];
        $send_data['contract_number'] = $contract_info['contract_number'];
        $send_data['keyword2'] = '代办的精品项目更新';
        $send_data['first'] = "尊贵的客户，您购买的【" . $contract_info['brand_name'] . "】 " . $contract_info['car_color'] . " " . $contract_info['car_model'] . "，代办信息更新：";
        PushService::product_check_push($send_data);
        return true;
    }

    /**
     * 提现到余额
     * @param $id
     */
    public function withdraw($id)
    {
        $contractModel = new ContactsModel();
        $info = $contractModel->getOne($id, "id,status,product_w,products_check,products_ids,product_user_id");
        if(!$info){
            return ['error_code' => -1, 'msg' => '合同不存在'];
        }
        if ($info['products_ids'] == '') {
            return ['error_code' => -1, 'msg' => '该订单没有办理精品项目'];
        }
        if ($info['product_user_id'] == 0) {
            return ['error_code' => -6, 'msg' => '找不到对应的负责人'];
        }
        if ($info['status'] != 1) {
            return ['error_code' => -2, 'msg' => '合同未完成，不能提现'];
        }
        if ($info['product_w'] == 1) {
            return ['error_code' => -3, 'msg' => '已经提现了，请勿重复提现'];
        }
        if ($info['products_check'] != 2) {
            return ['error_code' => -4, 'msg' => '精品项目未完成，不能提现'];
        }
        //获取成本
        $cost = $this->get_products_cost($info['products_ids']);
        if ($cost == 0) {
            return ['error_code' => -5, 'msg' => '精品成本是0元'];
        }
        //提现
        $user_info = Db::name("SystemUser")->where("id", $info['product_user_id'])->where("is_deleted", 0)->find();
        if (!$user_info) {
            return ['error_code' => -6, 'msg' => '找不到对应的负责人'];
        }
        Db::startTrans();
        $system_user['id'] = $user_info['id'];
        $system_user['money'] = $user_info['money'] + $cost;
        $r = DataService::save("SystemUser", $system_user);
        if (!$r) {
            Db::rollback();
            return ['error_code' => -7, 'msg' => '提现到余额失败，请稍后重试'];
        }
        $contract['id'] = $id;
        $contract['product_w'] = 1;
        $r = DataService::save("Contacts", $contract);
        if (!$r) {
            Db::rollback();
            return ['error_code' => -7, 'msg' => '提现到余额失败，请稍后重试'];
        }
        Db::commit();

        return ['error_code' => 0, 'msg' => ''];
    }
}