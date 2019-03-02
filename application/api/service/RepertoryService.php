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
 * 预算处理
 * Class FansService
 * @package app\wechat
 */
class RepertoryService
{
    /**
     * 添加文员的上传记录
     */
    public function add_doing_log($old_log, $new_log)
    {
        //判断是否和以前一致
        $is_diff = false;
        if ($old_log) {
            $check_key = ['garage_number', 'ahead_image', 'side_image', 'back_image', 'inside_image_one', 'inside_image_two', 'nameplate_image'];
            foreach ($check_key as $v) {
                if (isset($new_log[$v])) {
                    if ($old_log[$v] != $new_log[$v]) {
                        $is_diff = true;
                        break;
                    }
                }
            }
        } else {
            $is_diff = true;
        }
        if (!$is_diff) {
            return false;
        }
        //添加记录，并且推送
        $data['contacts_id'] = $new_log['contacts_id'];
        $data['status'] = 1;
        $data['ps'] = '';
        $r = $this->add_log($data);
        if ($r) {
            //精品录入
            $productService = new ProductService();
            $productService->add_log($new_log['contacts_id']);
        }
        return $r;
    }

    public function add_log($data)
    {
        $savedata['contacts_id'] = $data['contacts_id'];
        $savedata['status'] = $data['status'];
        $savedata['ps'] = $data['ps'];
        $r = DataService::save("Repertory", $savedata);
        if (!$r) {
            return false;
        }
        //到店不用推送
        if ($data['status'] == 2) {
            return true;
        }
        //推送记录
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($data['contacts_id']);
        if (!$contract_info) {
            return false;
        }
        $send_data['user_id'] = $contract_info['user_id'];
        $send_data['contacts_id'] = $data['contacts_id'];
        $send_data['contract_number'] = $contract_info['contract_number'];
        $send_data['keyword2'] = '';
        switch ($data['status']) {
            case 0:
                $send_data['keyword2'] = '车辆准备中';
                break;
            case 1:
                $send_data['keyword2'] = '车辆运输中，请进行确认';
                break;
            case 2:
                $send_data['keyword2'] = '车辆已到店';
                break;
            case 3:
                $send_data['keyword2'] = '已经交车';
                break;
        }

        $send_data['first'] = "尊贵的客户，您购买的【" . $contract_info['brand_name'] . "】 " . $contract_info['car_color'] . " " . $contract_info['car_model'] . "，车辆状态如下：";
        PushService::repertory_send($send_data);
        return true;
    }
}