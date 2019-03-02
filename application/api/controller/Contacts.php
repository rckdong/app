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

use app\api\model\ContactsCheckModel;
use app\api\model\ContactsInsuranceModel;
use app\api\model\ContactsLicenseModel;
use app\api\model\ContactsLogisticsModel;
use app\api\model\ContactsProcessModel;
use app\api\model\SystemUserModel;
use app\api\service\BudgetService;
use app\api\service\ContractService;
use app\api\service\InsuranceService;
use app\api\service\RepertoryService;
use app\api\service\SettlementService;
use controller\BasicApi;
use service\DataService;
use service\HttpService;
use service\NodeService;
use service\ToolsService;
use think\App;
use app\api\model\ContactsModel;
use app\api\model\TrafficRecordModel;
use app\api\model\InsuranceModel;
use app\api\model\ProductsModel;
use app\api\model\CityModel;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Contacts extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Contacts';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 合同列表
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 合同数据库对象
        //TODO 通过token获取对应的列表
        $get = $this->request->request();
        $db = Db::name($this->table)->order('id desc');
        foreach (['contract_number'] as $key) {
            (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
        }
        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        if (isset($get['is_withdraw_ok']) && $get['is_withdraw_ok'] == 1) {
            $db->where("is_withdraw_ok", 'neq', 0);
        }
        $db->where('is_temp', 0);
        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }
        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $result = parent::_list($db);
//        print_r($result);exit;
        $SettlementService = new SettlementService();
        foreach ($result['list'] as $key =>$val){
            $result['list'][$key]['is_settlement'] = Db::name("OrderSettlement")->where("contacts_id",$val['id'])->where("is_deleted",0)->count();
            if(isset($get['staff']) && $get['staff'] == 'caiwu'){
                //财务
                $order_settlement_info = $SettlementService->get_info($val['id']);
                $result['list'][$key]['company_net_profit'] = $order_settlement_info['company_net_profit'];
                $result['list'][$key]['zmanage_profit'] = $order_settlement_info['zmanage_profit'];
                $result['list'][$key]['sales_profit'] = $order_settlement_info['sales_profit'];
                $result['list'][$key]['manage_profit'] = $order_settlement_info['manage_profit'];
            }
        }
        $this->success_json('获取数据成功', $result);
    }


    /**
     * 获取销售合同和付款凭证pdf
     */
    public function get_contacts_pdf()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $id = $get['id'];
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($id);
        if (!$contract_info) {
            $this->error_json(400, '查无信息', []);
        }
        $contractService = new ContractService();
        $pay_log = $contractService->pay_log($id, $contract_info);
        //TODO 补回参数
        $result = [
            'contact_url' => $this->get_full_url('/static/pdf/contract_' . $contract_info["contract_number"] . '.png'),
            'seal_url' => sysconf("system_seal"),
            'pay_log' => $pay_log
        ];

        $this->success_json('获取数据成功', $result);
    }

    /**
     * 销售文员合同列表
     */
    public function get_contacts_list_by_clerk()
    {
        // 合同数据库对象
        //TODO 通过token获取对应的列表
        $get = $this->request->request();
        $db = Db::name($this->table)->order('id desc');
        foreach (['contract_number'] as $key) {
            (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
        }
//        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        isset($get['is_insuramce']) ? $db->where('is_insuramce', $get['is_insuramce']) : '';
        isset($get['is_process']) ? $db->where('is_process', $get['is_process']) : '';
        isset($get['is_logistics']) ? $db->where('is_logistics', $get['is_logistics']) : '';
        isset($get['is_license']) ? $db->where('is_license', $get['is_license']) : '';

        $db->where('clerk_show', 1);
        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }
        if (isset($get['nickname']) && $get['nickname'] !== '') {
            $db->where('nickname', $get['nickname']);
        }
        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $result = parent::_list($db);
        if ($result['list']) {
            $saler_ids = array_column($result['list'], 'saler_id');
            $saler_ids = array_unique($saler_ids);
            $saler_ids = implode(',', $saler_ids);
            $systemUserModel = new SystemUserModel();
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
            foreach ($result['list'] as $key => &$val) {
                $val['saler_name'] = $saler_names[$val['saler_id']];
            }
        }
        //合同未完成
        $result['contact_count'] = 0;
        //保险未完成
        $result['insurance_count'] = 0;
        //手续未完成
        $result['process_count'] = 0;
        //物流未完成
        $result['logistics_count'] = 0;
        //行驶证未完成
        $result['license_count'] = 0;
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 查看上传的保险信息
     */
    public function show_insurance()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contactsInsuranceModel = new ContactsInsuranceModel();
        $info = $contactsInsuranceModel->getOneByContacts_id($post['contacts_id']);
        if (!$info) {
            $info['id'] = 0;
            $info['contacts_id'] = $post['contacts_id'];
            $info['compulsory_insurance'] = 0;
            $info['commercial_insurance_one'] = 0;
            $info['commercial_insurance_two'] = 0;
            $info['commercial_insurance_three'] = 0;
            $info['quality_assurance'] = 0;
            $info['compulsory_image'] = '';
            $info['commercial_image'] = '';
            $info['quality_image'] = '';
            $info['compulsory_invoice_image'] = '';
            $info['commercial_invoice_image'] = '';
            $info['quality_invoice_image'] = '';
            $info['is_deleted'] = 0;
            $info['compulsory_image_full'] = '';
            $info['commercial_image_full'] = '';
            $info['quality_image_full'] = '';
            $info['compulsory_invoice_image_full'] = '';
            $info['commercial_invoice_image_full'] = '';
            $info['quality_invoice_image_full'] = '';
            $this->success_json("上传的保险信息", $info);
        }
        $info['compulsory_image_full'] = $this->get_full_url($info['compulsory_image']);
        $info['commercial_image_full'] = $this->get_full_url($info['commercial_image']);
        $info['quality_image_full'] = $this->get_full_url($info['quality_image']);
        $info['compulsory_invoice_image_full'] = $this->get_full_url($info['compulsory_invoice_image']);
        $info['commercial_invoice_image_full'] = $this->get_full_url($info['commercial_invoice_image']);
        $info['quality_invoice_image_full'] = $this->get_full_url($info['quality_invoice_image']);
        $this->success_json("上传的保险信息", $info);
    }

    /**
     * 查看上传的手续信息
     */
    public function show_process()
    {
        $post = $this->request->request();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contactsProcessModel = new ContactsProcessModel();
        $info = $contactsProcessModel->getOneByContacts_id($post['contacts_id']);
        if (!$info) {
            $info['id'] = 0;
            $info['contacts_id'] = $post['contacts_id'];
            $info['invoice_money'] = 0;
            $info['certificate_image'] = '';
            $info['examination_image'] = '';
            $info['agreement_image'] = '';
            $info['invoice_image'] = '';
            $info['environment_image'] = '';
            $info['is_deleted'] = 0;
            $info['is_quanbao'] = 0;
            $info['certificate_image_full'] = '';
            $info['examination_image_full'] = '';
            $info['agreement_image_full'] = '';
            $info['invoice_image_full'] = '';
            $info['environment_image_full'] = '';
            $this->success_json("上传的手续信息", $info);
        }
        $info['certificate_image_full'] = $this->get_full_url($info['certificate_image']);
        $info['examination_image_full'] = $this->get_full_url($info['examination_image']);
        $info['agreement_image_full'] = $this->get_full_url($info['agreement_image']);
        $info['invoice_image_full'] = $this->get_full_url($info['invoice_image']);
        $info['environment_image_full'] = $this->get_full_url($info['environment_image']);
        $this->success_json("上传的手续信息", $info);
    }

    /**
     * 查看上传的物流信息
     */
    public function show_logistics()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contactsLogisticsModel = new ContactsLogisticsModel();
        $info = $contactsLogisticsModel->getOneByContacts_id($post['contacts_id']);
        if (!$info) {
            $info['id'] = 0;
            $info['contacts_id'] = $post['contacts_id'];
            $info['garage_number'] = '';
            $info['ahead_image'] = '';
            $info['side_image'] = '';
            $info['back_image'] = '';
            $info['inside_image_one'] = '';
            $info['inside_image_two'] = '';
            $info['is_deleted'] = 0;
            $info['nameplate_image'] = '';
            $info['ahead_image_full'] = '';
            $info['side_image_full'] = '';
            $info['back_image_full'] = '';
            $info['inside_image_one_full'] = '';
            $info['inside_image_two_full'] = '';
            $info['nameplate_image_full'] = '';
            $this->success_json("上传的物流信息", $info);
        }
        $info['ahead_image_full'] = $this->get_full_url($info['ahead_image']);
        $info['side_image_full'] = $this->get_full_url($info['side_image']);
        $info['back_image_full'] = $this->get_full_url($info['back_image']);
        $info['inside_image_one_full'] = $this->get_full_url($info['inside_image_one']);
        $info['inside_image_two_full'] = $this->get_full_url($info['inside_image_two']);
        $info['nameplate_image_full'] = $this->get_full_url($info['nameplate_image']);
        $this->success_json("上传的物流信息", $info);
    }

    /**
     * 查看行驶证上传
     */
    public function show_license()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contactsLicenseModel = new ContactsLicenseModel();
        $info = $contactsLicenseModel->getOneByContacts_id($post['contacts_id']);
        if (!$info) {
            $info['id'] = 0;
            $info['contacts_id'] = $post['contacts_id'];
            $info['license_image'] = '';
            $info['registration_image'] = '';
            $info['tax_image'] = '';
            $info['receipt_image'] = '';
            $info['is_deleted'] = 0;
            $info['license_image_full'] = '';
            $info['registration_image_full'] = '';
            $info['tax_image_full'] = '';
            $info['receipt_image_full'] = '';
            $this->success_json("行驶证上传的信息", $info);
        }
        $info['license_image_full'] = $this->get_full_url($info['license_image']);
        $info['registration_image_full'] = $this->get_full_url($info['registration_image']);
        $info['tax_image_full'] = $this->get_full_url($info['tax_image']);
        $info['receipt_image_full'] = $this->get_full_url($info['receipt_image']);

        $this->success_json("行驶证上传的信息", $info);
    }

    /**
     * 上传保险信息
     */
    public function upload_insurance()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $insuranceService = new InsuranceService();
        $savedata = [];
        $savekey = $insuranceService->check_key;
        foreach ($savekey as $key) {
            if (isset($post[$key]) && $post[$key] !== '') {
                $savedata[$key] = $post[$key];
            } else {
                $savedata[$key] = '';
            }
        }
        //旧的数据
        $old_data = $insuranceService->get_old_data($post['contacts_id']);
        $r = $insuranceService->save_insurance($savedata);
        if ($r === false) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        //判断是否推送
        $insuranceService->add_doing_log($old_data, $savedata);
        $this->success_json("保存成功", ['insertId' => $r]);
    }

    /**
     * 上传手续信息
     */
    public function upload_process()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $savedata = [];
        $savekey = [
            'contacts_id',
            'invoice_money',
            'certificate_image',
            'examination_image',
            'agreement_image',
            'invoice_image',
            'environment_image',
            'is_quanbao',
        ];
        foreach ($savekey as $key) {
            if (isset($post[$key]) && $post[$key] !== '') {
                $savedata[$key] = $post[$key];
            }
        }
        $contactsProcessModel = new ContactsProcessModel();
        $r = $contactsProcessModel->save_info($savedata);
        if (!$r) {
            $this->error_json(400, '保存失败', []);
        }
        //修改合同表的数据
        if($savedata['invoice_money'] != ''){
            $update_contacts['is_process'] = 1;
            $update_contacts['id'] = $post['contacts_id'];
            $r = DataService::save($this->table, $update_contacts, 'id');
        }
        $this->success_json("保存成功", ['insertId' => $r]);
    }

    /**
     * 上传物流信息
     */
    public function upload_logistics()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }

        $savedata = [];
        $savekey = [
            'contacts_id',
            'garage_number',
            'ahead_image',
            'side_image',
            'back_image',
            'inside_image_one',
            'inside_image_two',
            'nameplate_image',
        ];
        foreach ($savekey as $key) {
            if (isset($post[$key]) && $post[$key] !== '') {
                $savedata[$key] = $post[$key];
                if ($key != 'contacts_id' && $key != 'garage_number') {
                    if (!isset($post['garage_number']) || $post['garage_number'] == '') {
                        $this->error_json(1003, '请提交车架号', []);
                        break;
                    }
                }
            }
        }
        $contactsLogisticsModel = new ContactsLogisticsModel();
        $old_log = $contactsLogisticsModel->getOneByContacts_id($post['contacts_id']);
        $r = $contactsLogisticsModel->save_info($savedata);
        if (!$r) {
            $this->error_json(400, '保存失败', []);
        }
        //修改合同表的数据
        if($savedata['garage_number'] != ''){
            $update_contacts['is_logistics'] = 1;
            $update_contacts['frame_number'] = $savedata['garage_number'];
            $update_contacts['id'] = $post['contacts_id'];
            $r = DataService::save($this->table, $update_contacts, 'id');
        }


        $repertoryService = new RepertoryService();
        $repertoryService->add_doing_log($old_log, $savedata);

        $this->success_json("保存成功", ['insertId' => $r]);
    }

    /**
     * 上传行驶证
     */
    public function upload_license()
    {
        $post = $this->request->post();
        if (!isset($post['contacts_id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $savedata = [];
        $savekey = [
            'contacts_id',
            'license_image',
            'registration_image',
            'tax_image',
            'receipt_image',
        ];
        foreach ($savekey as $key) {
            if (isset($post[$key]) && $post[$key] !== '') {
                $savedata[$key] = $post[$key];
            }
        }
        $contactsLicenseModel = new ContactsLicenseModel();
        $r = $contactsLicenseModel->save_info($savedata);
        if (!$r) {
            $this->error_json(400, '保存失败', []);
        }
        //修改合同表的数据
        if($savedata['license_image']!= '' || $savedata['registration_image']!= '' || $savedata['tax_image']!= '' || $savedata['receipt_image']!= ''){
            $update_contacts['is_license'] = 1;
            $update_contacts['id'] = $post['contacts_id'];
            $r = DataService::save($this->table, $update_contacts, 'id');
        }
        $this->success_json("保存成功", ['insertId' => $r]);
    }


    /**
     * 合同详情
     */
    public function contact_info()
    {
        $get = $this->request->request();

        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试1', []);
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
        if ($contact_info['get_time'] == null || $contact_info['get_time'] == '1970-01-01 08:00:00') {
            $contact_info['get_time'] = '待定';
        }
        $this->success_json('获取数据成功', $contact_info);
    }

    /**
     * 添加合同数据
     */
    public function add()
    {
        $post = $this->request->post();
        foreach (['user_id', 'nickname', 'identify', 'address', 'car_model', 'brand_id', 'deposit_price', 'transaction_price', 'contract_type'] as $key) {
            if (!isset($post[$key])) {
                $this->error_json(1003, '合同数据不完善' . $key, []);
            }
        }
        $user_info = Db::name("AppUsers")->where(['id' => $post['user_id']])->find();
        if (!$user_info) {
            $this->error_json(400, '用户信息不存在，请稍后重试', []);
        }
        $brand_info = Db::name("Brand")->where(['id' => $post['brand_id']])->find();
        if (!$user_info) {
            $this->error_json(400, '品牌不存在，请稍后重试', []);
        }
        $insert_data = [];
        $insert_data['contract_number'] = date('YmdHis', time()) . $post['user_id'];
        $insert_data['contract_type'] = $post['contract_type'];
        $insert_data['user_id'] = $post['user_id'];
        $insert_data['nickname'] = $post['nickname'];
        $insert_data['phone'] = $user_info['phone'];
        $insert_data['identify'] = $post['identify'];
        $insert_data['car_type'] = $post['car_type'];
        $insert_data['brand_id'] = $post['brand_id'];
        $insert_data['brand_name'] = $brand_info['name'];
        $insert_data['province_id'] = isset($post['province_id']) ? $post['province_id'] : 0;
        $insert_data['city_id'] = isset($post['city_id']) ? $post['city_id'] : 0;
        $insert_data['address'] = isset($post['address']) ? $post['address'] : '';
        $insert_data['emergency_contact'] = isset($post['emergency_contact']) ? $post['emergency_contact'] : '';
        $insert_data['emergency_phone'] = isset($post['emergency_phone']) ? $post['emergency_phone'] : '';
        $insert_data['car_type_desc'] = isset($post['car_type_desc']) ? $post['car_type_desc'] : '';
        $insert_data['car_type_desc'] = isset($post['car_type_desc']) ? $post['car_type_desc'] : '';
        $insert_data['car_model'] = isset($post['car_model']) ? $post['car_model'] : '';
        $insert_data['car_color'] = isset($post['car_color']) ? $post['car_color'] : '';
        $insert_data['car_selection'] = isset($post['car_selection']) ? $post['car_selection'] : '';
        $insert_data['remarks'] = isset($post['remarks']) ? $post['remarks'] : '';
        $insert_data['license'] = isset($post['license']) ? $post['license'] : '';
        $insert_data['guidance_price'] = isset($post['guidance_price']) ? $post['guidance_price'] : 0;
        $insert_data['freight'] = isset($post['freight']) ? $post['freight'] : 0;
        $insert_data['transaction_price'] = isset($post['transaction_price']) ? $post['transaction_price'] : 0;
        $insert_data['deposit_price'] = isset($post['deposit_price']) ? $post['deposit_price'] : 0;
        $insert_data['purchase_tax'] = isset($post['purchase_tax']) ? $post['purchase_tax'] : 0;
        $insert_data['buy_type'] = isset($post['buy_type']) ? $post['buy_type'] : 0;
        $insert_data['buy_type_desc'] = isset($post['buy_type_desc']) ? $post['buy_type_desc'] : '';
        $insert_data['insurance_ids'] = isset($post['insurance_ids']) ? implode(',', $post['insurance_ids']) : '';
        $insert_data['products_ids'] = isset($post['products_ids']) ? implode(',', $post['products_ids']) : '';
        $insert_data['book_time'] = date('Y-m-d H:i:s', time());
        $insert_data['get_time'] = isset($post['get_time']) ? date('Y-m-d H:i:s', strtotime($post['get_time'])) : '';
        $insert_data['saler_id'] = $this->system_user['id'];
        //TODO 精品负责人
        $insert_data['product_user_id'] = 10023;
        $insert_data['store_id'] = 10000;
        Db::name($this->table)->insert($insert_data);
        $new_id = Db::name($this->table)->getLastInsID();

        if (!$new_id) {
            $this->error_json(400, '保存失败', []);
        }
//        $result['insertId'] = $new_id;
        $result['insert_id'] = $new_id;

//        if ($insert_data['contract_type'] == 1) {
//            $app_insert['contacts_id'] = $new_id;
//            $app_insert['admin_id'] = 10000;
//            $app_insert['type'] = 2;
//            $app_insert['type'] = 2;
//            DataService::save("ContactsApproval", $app_insert, 'contacts_id');
//        }
        $this->success_json('提交成功', $result);
    }


    /**
     * 合同修改
     */
    public function edit()
    {
        $post = $this->request->post();
        foreach (['id', 'user_id', 'nickname', 'identify', 'address', 'car_model', 'brand_id', 'deposit_price', 'transaction_price', 'contract_type'] as $key) {
            if (!isset($post[$key])) {
                $this->error_json(1003, '合同数据不完善' . $key, []);
            }
        }
        $contact_id = $post['id'];
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试1', []);
        }


        $user_info = Db::name("AppUsers")->where(['id' => $post['user_id']])->find();
        if (!$user_info) {
            $this->error_json(400, '用户信息不存在，请稍后重试', []);
        }
        $brand_info = Db::name("Brand")->where(['id' => $post['brand_id']])->find();
        if (!$user_info) {
            $this->error_json(400, '品牌不存在，请稍后重试', []);
        }
        $insert_data = [];
        $insert_data['contract_number'] = date('YmdHis', time()) . $post['user_id'];
        $insert_data['contract_type'] = $post['contract_type'];
        $insert_data['user_id'] = $post['user_id'];
        $insert_data['nickname'] = $post['nickname'];
        $insert_data['phone'] = $user_info['phone'];
        $insert_data['identify'] = $post['identify'];
        $insert_data['car_type'] = $post['car_type'];
        $insert_data['brand_id'] = $post['brand_id'];
        $insert_data['brand_name'] = $brand_info['name'];
        $insert_data['province_id'] = isset($post['province_id']) ? $post['province_id'] : 0;
        $insert_data['city_id'] = isset($post['city_id']) ? $post['city_id'] : 0;
        $insert_data['address'] = isset($post['address']) ? $post['address'] : '';
        $insert_data['emergency_contact'] = isset($post['emergency_contact']) ? $post['emergency_contact'] : '';
        $insert_data['emergency_phone'] = isset($post['emergency_phone']) ? $post['emergency_phone'] : '';
        $insert_data['car_type_desc'] = isset($post['car_type_desc']) ? $post['car_type_desc'] : '';
        $insert_data['car_type_desc'] = isset($post['car_type_desc']) ? $post['car_type_desc'] : '';
        $insert_data['car_model'] = isset($post['car_model']) ? $post['car_model'] : '';
        $insert_data['car_color'] = isset($post['car_color']) ? $post['car_color'] : '';
        $insert_data['car_selection'] = isset($post['car_selection']) ? $post['car_selection'] : '';
        $insert_data['remarks'] = isset($post['remarks']) ? $post['remarks'] : '';
        $insert_data['license'] = isset($post['license']) ? $post['license'] : '';
        $insert_data['guidance_price'] = isset($post['guidance_price']) ? $post['guidance_price'] : 0;
        $insert_data['freight'] = isset($post['freight']) ? $post['freight'] : 0;
        $insert_data['transaction_price'] = isset($post['transaction_price']) ? $post['transaction_price'] : 0;
        $insert_data['deposit_price'] = isset($post['deposit_price']) ? $post['deposit_price'] : 0;
        $insert_data['purchase_tax'] = isset($post['purchase_tax']) ? $post['purchase_tax'] : 0;
        $insert_data['buy_type'] = isset($post['buy_type']) ? $post['buy_type'] : 0;
        $insert_data['buy_type_desc'] = isset($post['buy_type_desc']) ? $post['buy_type_desc'] : '';
        $insert_data['insurance_ids'] = isset($post['insurance_ids']) ? implode(',', $post['insurance_ids']) : '';
        $insert_data['products_ids'] = isset($post['products_ids']) ? implode(',', $post['products_ids']) : '';
        $insert_data['get_time'] = isset($post['get_time']) ? date('Y-m-d H:i:s', strtotime($post['get_time'])) : '';
        //TODO 精品负责人
        $insert_data['product_user_id'] = 10023;
        $insert_data['store_id'] = 10000;
        $r = Db::name($this->table)->where(['id' => $contact_id])->update($insert_data);

        if (!$r) {
            $this->error_json(400, '保存失败', []);
        }
//        if ($insert_data['contract_type'] == 1) {
//            $app_insert['contacts_id'] = $contact_id;
//            $app_insert['admin_id'] = 10000;
//            $app_insert['type'] = 2;
//            $app_insert['type'] = 2;
//            DataService::save("ContactsApproval", $app_insert, 'contacts_id');
//        }

        //重新计算预算
        $buggetService = new BudgetService();
        $buggetService->calculation($contact_id, $insert_data);


        if ($contact_info['is_temp'] == 0) {
            $contractService = new ContractService();
            $contractService->contract_push($contact_id, 2);
        }

        //生成pdf文件
        $url = "http://chexinyuan.com/index.php/index/word/create_word?contract_id=" . $contact_id;
        HttpService::get($url, [], ['timeout' => 1]);
        $this->success_json('提交成功', []);
    }


    /**
     * 临时合同转正
     */
    public function contact_change_formal()
    {
        $get = $this->request->request();
        if (!isset($get['id']) && $get['id'] != 0) {
            $this->error_json(1003, '参数不足', []);
        }
        $savedata['is_temp'] = 0;
        $savedata['id'] = $get['id'];
        $r = DataService::save($this->table, $savedata, 'id');

        if (!$r) {
            $this->error_json(400, '修改失败，请稍后重试', []);
        }
        //推送
        $contractService = new ContractService();
        $contractService->contract_push($get['id'], 0);
        //生成pdf文件
        $url = "http://chexinyuan.com/index.php/index/word/create_word?contract_id=" . $get['id'];
        HttpService::get($url, [], ['timeout' => 1]);
        $this->success_json('修改成功', []);
    }

    /**
     * 修改订车合同转定车合同
     */
    public function change_contract_type()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);

        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试', []);
        }

        if ($contact_info['contract_type'] == 1) {
            $this->error_json(400, '该合同已是定车合同', []);
        }
        $savedata['contract_type'] = 1;
        $savedata['id'] = $contact_id;
        $r = DataService::save($this->table, $savedata, 'id');

        if (!$r) {
            $this->error_json(400, '修改失败，请稍后重试', []);
        }

        $contractService = new ContractService();
        $contractService->contract_push($get['id'], 2);

        //生成pdf文件
        $url = "http://chexinyuan.com/index.php/index/word/create_word?contract_id=" . $contact_id;
        HttpService::get($url, [], ['timeout' => 1]);
        $this->success_json('修改成功', []);
    }


    /**
     * 获取退订描述
     */
    public function get_cancel_desc()
    {
        $contactsModel = new ContactsModel();

        $cancel = $contactsModel->get_cancel_desc();
        $this->success_json('获取退订描述', $cancel);
    }

    /**
     * 退订合同
     */
    public function cancel_contract()
    {
        $get = $this->request->request();
        if (!isset($get['id']) || !isset($get['cancel_desc'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $cancel_desc = $get['cancel_desc'];
        $other_reason = isset($get['other_reason']) ? $get['other_reason'] : '';
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            $this->error_json(400, '合同不存在，请稍后重试', []);
        }
        if ($contact_info['is_cancel'] != 0) {
            $this->error_json(400, '已经申请了退订，请勿重复申请', []);
        }
        $save_data['is_cancel'] = 1;
        $save_data['cancel_desc'] = $other_reason ? $cancel_desc . ':' . $other_reason : $cancel_desc;
        $save_data['id'] = $contact_id;
        $r = DataService::save($this->table, $save_data, 'id');
        if (!$r) {
            $this->error_json(400, '修改失败，请稍后重试', []);
        }
        $this->success_json('修改成功', []);
    }

    /**
     * 交车记录
     */
    public function traffic_record()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $trafficRecordModel = new TrafficRecordModel();
        $r = $trafficRecordModel->get_list($contact_id);
        if (count($r) == 0) {
            $this->error_json(400, '暂无交车记录', []);
        }
        $this->success_json('获取交车记录成功', $r);
    }

    /**
     * 申请交车
     */
    public function apply_traffic()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $contractService = new ContractService();
        $contract_res = $contractService->apply_traffic_check($contact_id);

        if ($contract_res['code'] != 200) {
            $this->error_json($contract_res['code'], $contract_res['msg'], []);
        }
        $contact_info = $contract_res['data'];
        $contactsModel = new ContactsModel();
//        $contact_info = $contactsModel->getOne($contact_id);


        $contactCheckModel = new ContactsCheckModel();
        $contact_check_info = $contactCheckModel->getOneByContacts_id($contact_id);
        //TODO 如果手续收款不完全，不能交车，1004码
//        $this->error_json(1004, '收款不完全，不能交车', []);

        $return['contact_id'] = $contact_id;
        $return['brand_name'] = $contact_info['brand_name'];
        $return['car_model'] = $contact_info['car_model'];
        $return['car_color'] = $contact_info['car_color'];
        $return['car_selection'] = $contact_info['car_selection'];
        $return['guidance_price'] = $contact_info['guidance_price'];
        $return['frame_number'] = $contact_info['frame_number'];
        $return['transaction_price'] = $contact_info['transaction_price'];
        $return['pay_money'] = $contact_info['pay_money'];
        $return['un_pay'] = $contact_info['transaction_price'] - $contact_info['pay_money'];
        $return['buy_type'] = $contact_info['buy_type'];
        $return['buy_type_desc'] = $contact_info['buy_type_desc'];

        $return['is_car_check'] = $contact_check_info['is_car_check'];
        $return['is_car_check_push'] = $contact_check_info['is_car_check_push'];
        $return['car_check_ps'] = $contact_check_info['car_check_ps'];
        $car_check = Db::name("CarCheck")->where("is_deleted", 0)->field("id,name,is_check")->order("sort asc,id asc")->select();

        foreach ($car_check as &$v) {
            $v['key'] = $v['id'];
            $v['value'] = $v['name'];
        }
        $return['car_check_select'] = $contactsModel->get_car_check($contact_check_info['car_check_select'], $car_check);

        $return['is_process_check'] = $contact_check_info['is_process_check'];
        $return['is_process_check_push'] = $contact_check_info['is_process_check_push'];
        $return['process_check_ps'] = $contact_check_info['process_check_ps'];
        $process_check = Db::name("ProcessCheck")->where("is_deleted", 0)->field("id,name,is_check")->order("sort asc,id asc")->select();
        foreach ($process_check as &$v) {
            $v['key'] = $v['id'];
            $v['value'] = $v['name'];
        }
        $return['process_check_select'] = $contactsModel->get_process_check($contact_check_info['process_check_select'], $process_check);

        $return['is_product_check'] = $contact_check_info['is_product_check'];
        $return['is_product_check_push'] = $contact_check_info['is_product_check_push'];
        $return['product_check_ps'] = $contact_check_info['product_check_ps'];
        $productsModel = new ProductsModel();
        $need_products = $productsModel->getByIds($contact_info['products_ids']);
        if (!$need_products) {
            $return['product_check_select'] = [];
        } else {
            $return['product_check_select'] = $contactsModel->get_product_check($contact_check_info['product_check_select'], $need_products);
        }

        $return['is_other_check'] = $contact_check_info['is_other_check'];
        $return['is_other_check_push'] = $contact_check_info['is_other_check_push'];
        $return['is_checkin'] = ($contact_check_info['is_checkin'] == 1) ? true : false;
        $return['is_mortgage'] = ($contact_check_info['is_mortgage'] == 1) ? true : false;
        $return['other_check_ps'] = $contact_check_info['other_check_ps'];
        $return['ps'] = $contact_check_info['ps'];
                $return['is_pass'] = $contact_check_info['is_pass'];

        $this->success_json('交车申请详情', $return);
    }


    /**
     * 申请放行条，并且推送给用户确认
     */
    public function do_apply()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            $this->error_json(400, '合同不存在', []);
        }
        $car_check_select = isset($get['car_check_select']) ? $get['car_check_select'] : [];
        $car_check_select = implode(',', $car_check_select);
        $savedata['car_check_select'] = $car_check_select;
        $savedata['is_car_check'] = 0;
        $savedata['is_car_check_push'] = 1;

        $process_check_select = isset($get['process_check_select']) ? $get['process_check_select'] : [];
        $process_check_select = implode(',', $process_check_select);
        $savedata['process_check_select'] = $process_check_select;
        $savedata['is_process_check'] = 0;
        $savedata['is_process_check_push'] = 1;

        $product_check_select = isset($get['product_check_select']) ? $get['product_check_select'] : [];
        $product_check_select = implode(',', $product_check_select);
        $savedata['product_check_select'] = $product_check_select;
        $savedata['is_product_check'] = 0;
        $savedata['is_product_check_push'] = 1;

        $savedata['is_checkin'] = isset($get['is_checkin']) ? $get['is_checkin'] : 0;
        $savedata['is_mortgage'] = isset($get['is_mortgage']) ? $get['is_mortgage'] : 0;
        $savedata['ps'] = isset($get['ps']) ? $get['ps'] : '';
        $savedata['is_other_check'] = 0;
        $savedata['is_other_check_push'] = 1;
        $savedata['is_pass'] = 0;


        $savedata['contacts_id'] = $contact_id;
        $r = DataService::save('ContactsCheck', $savedata, 'contacts_id');
        if (!$r) {
            $this->error_json(400, '推送失败，请稍后重试', []);
        }
        $contractService = new ContractService();
        //添加推送
        $contractService->jiaoche_push($contact_info);
        $repertoryService = new RepertoryService();
        $data['contacts_id'] = $contact_id;
        $data['status'] = 2;
        $data['ps'] = '';
        $repertoryService->add_log($data);
        $this->success_json('推送成功，等待确认', []);
    }


    /**
     * 推送通知用户2次确认
     */
    public function apply_push()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $contact_id = $get['id'];
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id);
        if (!$contact_info) {
            $this->error_json(400, '合同不存在', []);
        }
        $type = $get['type'];
        $need_arg = [1, 2, 3, 4];
        if (!in_array($type, $need_arg)) {
            $this->error_json(1004, '参数错误', []);
        }
        $savedata = [];
        $select_option = isset($get['select_option']) ? $get['select_option'] : [];
        $select_option = implode(',', $select_option);
        switch ($type) {
            case 1:     //车辆检查推送
                $savedata['car_check_select'] = $select_option;
                $savedata['is_car_check'] = 0;
                $savedata['is_car_check_push'] = 1;
                break;
            case 2:     //车辆手续推送
                $savedata['process_check_select'] = $select_option;
                $savedata['is_process_check'] = 0;
                $savedata['is_process_check_push'] = 1;
                break;
            case 3:     //车辆精品推送
                $savedata['product_check_select'] = $select_option;
                $savedata['is_product_check'] = 0;
                $savedata['is_product_check_push'] = 1;
                break;
            case 4:     //杂项推送
                $savedata['is_checkin'] = isset($get['is_checkin']) ? $get['is_checkin'] : 0;
                $savedata['is_mortgage'] = isset($get['is_mortgage']) ? $get['is_mortgage'] : 0;
                $savedata['ps'] = isset($get['ps']) ? $get['ps'] : '';
                $savedata['is_other_check'] = 0;
                $savedata['is_other_check_push'] = 1;
                break;
            default:
                $this->error_json(1004, '参数错误', []);
        }

        //TODO 添加推送
        $savedata['contacts_id'] = $contact_id;
        $r = DataService::save('ContactsCheck', $savedata, 'contacts_id');
        if (!$r) {
            $this->error_json(400, '推送失败，请稍后重试', []);
        }
        $this->success_json('推送成功，等待确认', []);

    }


    /**
     * 结算表
     */
    public function settlement()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            echo "跨域请求错误";
            exit;
        }
        $id = $get['id'];
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($id);
        if (!$contract_info) {
            echo "参数错误";
            exit;
        }
        $info = Db::name("OrderSettlement")->where("contacts_id", $contract_info['id'])->find();
        if (!$info) {
            echo "结算不存在";
            exit;
        }
        $info['type'] = $info['type'] . '';
        $info['commission'] = $info['sales_net_profit'] * 0.8;
        $info['commission_second'] = $info['sales_net_profit'] * 0.1;
        $info['commission_first'] = $info['sales_net_profit'] * 0.1;
        $budgetService = new BudgetService();
        $do_type = true;
        $budgetService->export_excel($info, $contract_info, $do_type);
        exit();
    }

    /**
     * 申请提现到钱包
     */
    public function apply_withdraw()
    {
        $get = $this->request->request();
        if (!isset($get['id']) || $get['id'] == '') {
            $this->error_json(400, '申请失败，请稍后重试', []);
        }
        $contractService = new ContractService();
        $check = $contractService->apply_withdraw($get['id']);
        if ($check['code'] != 200) {
            $this->error_json($check['code'], $check['msg'], []);
        }
        //可以操作提现申请
        $r = $contractService->do_apply_withdraw($check['data']);
        if (!$r) {
            $this->error_json(400, '申请失败，请稍后重试', []);
        }
        $this->success_json('申请成功，等待确认', []);
    }


    /**
     * 结算生成
     */
    public function settlement_create()
    {
        $get = $this->request->request();
        if (!isset($get['id']) || $get['id'] == '') {
            $this->error_json(400, '生成失败，请稍后重试', []);
        }
        $contractService = new ContractService();
        $check = $contractService->apply_withdraw($get['id'], 0);
        if ($check['code'] != 200) {
            $this->error_json($check['code'], $check['msg'], []);
        }
        //生成结算
        $contract_info = $check['data'];
        $settlementService = new SettlementService();
        $r = $settlementService->add_log($contract_info['id'], $contract_info['star']);
        if (!$r) {
            $this->error_json(400, '生成失败，请稍后重试', []);
        }
        $this->success_json('生成成功', []);
    }
}
