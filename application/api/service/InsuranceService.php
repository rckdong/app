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

use app\api\model\ContactsInsuranceModel;
use app\api\model\ContactsModel;
use app\api\model\InsuranceModel;
use service\DataService;
use think\Db;

/**
 * 保险处理
 * Class FansService
 * @package app\wechat
 */
class InsuranceService
{

    /**
     * 表单检查的字段
     * @var array
     */
    public $check_key = [
        'contacts_id',
        'compulsory_insurance',
        'commercial_insurance_one',
        'commercial_insurance_two',
        'commercial_insurance_three',
        'quality_assurance',
        'compulsory_image',
        'commercial_image',
        'quality_image',
        'compulsory_invoice_image',
        'commercial_invoice_image',
        'quality_invoice_image',
    ];

    /**
     * 保存数据
     * @param $savedata
     * @return bool
     */
    public function save_insurance($savedata)
    {
        $contacts_id = $savedata['contacts_id'];
        if (!$contacts_id) {
            return false;
        }
        Db::startTrans();
        $contactsInsuranceModel = new ContactsInsuranceModel();
        $return_id = $contactsInsuranceModel->save_info($savedata);
        if (!$return_id) {
            Db::rollback();
            return false;
        }
        //修改合同表的数据
        $update_contacts['is_insuramce'] = 1;
        $update_contacts['id'] = $contacts_id;
        $r = DataService::save('Contacts', $update_contacts, 'id');
        if (!$r) {
            Db::rollback();
            return false;
        }
        Db::commit();
        return $return_id;
    }

    public function get_old_data($contract_id)
    {
        if (!$contract_id) {
            return false;
        }
        $contactsInsuranceModel = new ContactsInsuranceModel();
        $r = $contactsInsuranceModel->getOneByContacts_id($contract_id);
        if (!$r) {
            return false;
        }
        return $r;
    }

    /**
     * 判断是否一致，并且推送
     */
    public function add_doing_log($old_log, $new_log)
    {
        //判断是否和以前一致
        $is_diff = false;
        if ($old_log) {
            foreach ($this->check_key as $v) {
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
        //推送
        $this->add_log($new_log);
        return true;
    }

    public function add_log($data)
    {
        //推送记录
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($data['contacts_id']);
        if (!$contract_info) {
            return false;
        }
        $send_data['user_id'] = $contract_info['user_id'];
        $send_data['contacts_id'] = $data['contacts_id'];
        $send_data['contract_number'] = $contract_info['contract_number'];
        $send_data['keyword2'] = '保单信息已更新';
        $send_data['first'] = "尊贵的客户，您购买的【" . $contract_info['brand_name'] . "】 " . $contract_info['car_color'] . " " . $contract_info['car_model'] . "，保单信息如下：";
        PushService::insurance_push($send_data);
        return true;
    }
}