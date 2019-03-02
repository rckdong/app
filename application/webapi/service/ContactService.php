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

namespace app\webapi\service;

use app\api\model\CityModel;
use app\api\model\InsuranceModel;
use app\api\model\ProductsModel;
use think\Db;

/**
 * 合同处理类
 * Class ProductService
 * @package app\goods\service
 */
class ContactService
{
    /**
     * 获取合同信息
     * @param $user_id
     */
    public static function get_contract_by_user_id($user_id)
    {
        $contact_info = Db::name("Contacts")
            ->where("user_id", $user_id)
            ->where("is_deleted", 0)
            ->order("id desc")
            ->find();
        if (!$contact_info) {
            return false;
        }
        $contact_info['insurance_list'] = [];
        $contact_info['full_address'] = $contact_info['address'];
        $contact_info['product_list'] = [];
        $contact_info['city'] = '';
        $contact_info['province'] = '';

        if ($contact_info['insurance_ids']) {
            $insuranceModel = new InsuranceModel();
            $insurance_list = $insuranceModel->getByIds($contact_info['insurance_ids']);
            $insurance_list = array_column($insurance_list, 'name');
            $contact_info['insurance_list'] = $insurance_list;
            $insurance_ids = explode(',', $contact_info['insurance_ids']);
            $new_insurance = [];
            foreach ($insurance_ids as $key => $val) {
                $new_insurance[] = intval($val);
            }
            $contact_info['insurance_ids'] = $new_insurance;
        } else {
            $contact_info['insurance_ids'] = [];
        }
        if ($contact_info['city_id']) {
            $cityModel = new CityModel();
            $city = $cityModel->get_city_info($contact_info['city_id']);
            $contact_info['full_address'] = $city . ' ' . $contact_info['address'];
            $city_info = $cityModel->getOne($contact_info['city_id']);
            $contact_info['city'] = $city_info['name'];
            $province_info = $cityModel->getOne($contact_info['province_id']);
            $contact_info['province'] = $province_info['name'];
        }

        if ($contact_info['products_ids']) {
            $productsModel = new ProductsModel();
            $product_list = $productsModel->getByIds($contact_info['products_ids']);
            $product_list = array_column($product_list, 'name');
            $contact_info['product_list'] = $product_list;

            $products_ids = explode(',', $contact_info['products_ids']);
            $new_products = [];
            foreach ($products_ids as $key => $val) {
                $new_products[] = intval($val);
            }
            $contact_info['products_ids'] = $new_products;
        } else {
            $contact_info['products_ids'] = [];
        }

        return $contact_info;
    }

    public static function get_contract_by_id($id){
        $contact_info = Db::name("Contacts")
            ->where("id", $id)
            ->where("is_deleted", 0)
            ->order("id desc")
            ->find();
        if (!$contact_info) {
            return false;
        }
        $contact_info['insurance_list'] = [];
        $contact_info['full_address'] = $contact_info['address'];
        $contact_info['product_list'] = [];
        $contact_info['city'] = '';
        $contact_info['province'] = '';

        if ($contact_info['insurance_ids']) {
            $insuranceModel = new InsuranceModel();
            $insurance_list = $insuranceModel->getByIds($contact_info['insurance_ids']);
            $insurance_list = array_column($insurance_list, 'name');
            $contact_info['insurance_list'] = $insurance_list;
            $insurance_ids = explode(',', $contact_info['insurance_ids']);
            $new_insurance = [];
            foreach ($insurance_ids as $key => $val) {
                $new_insurance[] = intval($val);
            }
            $contact_info['insurance_ids'] = $new_insurance;
        } else {
            $contact_info['insurance_ids'] = [];
        }
        if ($contact_info['city_id']) {
            $cityModel = new CityModel();
            $city = $cityModel->get_city_info($contact_info['city_id']);
            $contact_info['full_address'] = $city . ' ' . $contact_info['address'];
            $city_info = $cityModel->getOne($contact_info['city_id']);
            $contact_info['city'] = $city_info['name'];
            $province_info = $cityModel->getOne($contact_info['province_id']);
            $contact_info['province'] = $province_info['name'];
        }

        if ($contact_info['products_ids']) {
            $productsModel = new ProductsModel();
            $product_list = $productsModel->getByIds($contact_info['products_ids']);
            $product_list = array_column($product_list, 'name');
            $contact_info['product_list'] = $product_list;

            $products_ids = explode(',', $contact_info['products_ids']);
            $new_products = [];
            foreach ($products_ids as $key => $val) {
                $new_products[] = intval($val);
            }
            $contact_info['products_ids'] = $new_products;
        } else {
            $contact_info['products_ids'] = [];
        }

        return $contact_info;
    }


    /**
     * 查看合同是否定车合同
     * @param $user_id
     * @param $contract_id
     */
    public static function contract_check($user_id, $contract_id)
    {
        $count = Db::name("Contacts")
            ->where("user_id", $user_id)
            ->where("id", $contract_id)
            ->where("contract_type", 1)
            ->count();
        if ($count <= 0) {
            return false;
        }
        return true;
    }


    public static function get_simple_contract($user_id, $contract_id, $field)
    {
        $info = Db::name("Contacts")
            ->where("user_id", $user_id)
            ->where("id", $contract_id)
            ->where("contract_type", 1)
            ->field($field)
            ->find();
        if (!$info) {
            return false;
        }
        return $info;
    }
}