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

use app\api\model\ContactsModel;
use app\api\model\FinanceModel;
use app\api\model\SystemUserModel;
use app\api\service\FinanceService;
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
class Finance extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Finance';


    /**
     * 财务主页
     */
    public function index()
    {
        $info['out'] = Db::name("Administrative")->where("type", 1)->sum("money");
        $info['income'] = Db::name("Administrative")->where("type", 0)->sum("money");
        $info['f_income'] = Db::name("Finance")->where("type", 0)->sum("money");
        $info['f_out'] = Db::name("Finance")->where("type", 1)->sum("money");
        $info['all'] = $info['income'] + $info['f_income'] - $info['out'] - $info['f_out'];

        //TODO 财务主页数据
        $res = [
            'business_income' => $info['f_income'] . '',
            'business_expenses' => $info['f_out'] . '',
            'administrator_expenses' => $info['out'] . '',
            'administrator_income' => $info['income'] . '',
            'settlement' => '0',
            'company_money' => $info['all'],
            'company_business_expenses' => '0',
            'company_management_expenses' => '0',
            'company_finace_expenses' => '0',
        ];
        $this->success_json("获取成功", $res);

    }

    /**
     * 营业收入列表
     */
    public function business_index()
    {
        $get = $this->request->request();
        $db = Db::name("Contacts")->order('id desc');
        foreach (['contract_number'] as $key) {
            (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
        }
        if (isset($get['status']) && $get['status'] !== '') {
            $db->where('status', isset($get['status']) ? $get['status'] : 0);
        }
        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }

        if (isset($get['brand_name']) && $get['brand_name'] !== '') {
            $db->where('brand_name', $get['brand_name']);
        }

        if (isset($get['nickname']) && $get['nickname'] !== '') {
            $db->where('nickname', $get['nickname']);
        }

        if (isset($get['book_time']) && $get['book_time'] !== '') {
            list($start, $end) = explode(' - ', $get['book_time']);
            $db->whereBetween('book_time', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }

        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $db->field("id,contract_number,contract_type,user_id,nickname,phone,brand_id,brand_name,car_model,car_color,guidance_price,transaction_price,deposit_price,loan_money,pay_money,saler_id,book_time");
        $result = parent::_list($db);

        if ($result['list']) {
            $saler_ids = array_column($result['list'], 'saler_id');
            $saler_ids = array_unique($saler_ids);
            $saler_ids = implode(',', $saler_ids);
            $systemUserModel = new SystemUserModel();
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
            foreach ($result['list'] as $key => &$val) {
                $val['saler_name'] = $saler_names[$val['saler_id']];
                //TODO 总金额
                $val['pay_percent'] = bcmul(bcdiv($val['pay_money'], $val['transaction_price'], 2), 100, 2) . ' %';
            }
        }
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 获取收款的选项
     */
    public function get_business_options()
    {
        $FinanceModel = new FinanceModel();
        $res['income_type'] = $FinanceModel->get_incom_type();
        $res['pay_type'] = $FinanceModel->get_pay_type();
        $res['out_type'] = $FinanceModel->get_out_type();
        $res['ex_income_type'] = $FinanceModel->get_ex_income_type();
        $this->success_json('获取数据成功', $res);
    }


    /**
     * 添加营业收入记录
     */
    public function add_income_log()
    {
        $get = $this->request->request();
        $vi_arr = ['id', 'option', 'pay_type', 'poundage', 'ps', 'certificate', 'type', 'money'];
        foreach ($vi_arr as $key => $val) {
            if (!isset($get[$val]) || $get[$val] === '') {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        //TODO 添加处理人ID,增加收入或者支出的业务
        $system_id = isset($this->system_user['id']) ? $this->system_user['id'] : 10000;
        $savedata['admin_id'] = $system_id;
        $FinanceModel = new FinanceModel();
        $financeService = new FinanceService();

        $contract_info = $financeService->check_contract($get['id']);

        if (!$contract_info) {
            $this->error_json(1003, '合同不存在或者合同等待审核', []);
        }

        $savedata['type'] = ($get['type'] == 1) ? $get['type'] : 0;
        if ($get['type'] == 1) {
            $savedata['option'] = $FinanceModel->get_out_type_id($get['option']);
        } else {
            $savedata['option'] = $FinanceModel->get_income_id($get['option']);
        }

        $savedata['pay_type'] = $FinanceModel->get_pay_type_id($get['pay_type']);
        if ($savedata['option'] === false || $savedata['pay_type'] === false) {
            $this->error_json(1001, '参数验证错误', []);
        }
        $savedata['poundage'] = $get['poundage'];
        $savedata['money'] = $get['money'];
        $savedata['ps'] = $get['ps'];
        $savedata['contacts_id'] = $get['id'];
        $savedata['certificate'] = $get['certificate'];

        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }

        $financeService->do_add_income_log($savedata, $system_id, $contract_info);

        $this->success_json('保存成功', []);
    }


    /**
     * 添加额外的营业收入记录
     */
    public function add_exincome_log()
    {
        $get = $this->request->request();
        $vi_arr = ['option', 'ps', 'money'];
        foreach ($vi_arr as $key => $val) {
            if (!isset($get[$val]) || $get[$val] === '') {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        //TODO 添加处理人ID,增加收入或者支出的业务
        $savedata['admin_id'] = $this->system_user['id'];
        $FinanceModel = new FinanceModel();
        $savedata['type'] = 0;
        $savedata['is_ex'] = 1;
        $savedata['money'] = $get['money'];
        $savedata['option'] = $FinanceModel->get_ex_income_id($get['option']);

        $savedata['pay_type'] = -1;
        if ($savedata['option'] === false) {
            $this->error_json(1001, '参数验证错误', []);
        }
        $savedata['ps'] = $get['ps'];

        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('保存成功', []);
    }

    /**
     * 获取营业记录
     */
    public function get_business_log()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $db = Db::name($this->table)->order('id desc');
        if (isset($get['type']) && $get['type'] !== '') {
            $db->where('type', $get['type']);
        }
        $db->where('is_deleted', 0);
        $db->where('contacts_id', $get['id']);
        $result = parent::_list($db);
        if (isset($get['export']) && $get['export'] == 1) {
            $financeService = new FinanceService();
            $financeService->export_excel($result['list']);
            exit();
        }
        $result['incomes'] = Db::name($this->table)->where('is_deleted', 0)->where('contacts_id', $get['id'])->where('type', 0)->sum("money");
        $result['expenses'] = Db::name($this->table)->where('is_deleted', 0)->where('contacts_id', $get['id'])->where('type', 1)->sum("money");
        $this->success_json('获取数据成功', $result);
    }


    public function _get_business_log_data_filter(&$list)
    {
        $FinanceModel = new FinanceModel();
        $income_type = $FinanceModel->get_incom_type();
        $out_type = $FinanceModel->get_out_type();
        $pay_type = $FinanceModel->get_pay_type();
        $ex_income_type = $FinanceModel->get_ex_income_type();
        //获取列表中的人员
        $saler_ids = array_column($list, 'admin_id');
        $saler_ids = array_unique($saler_ids);
        $saler_ids = implode(',', $saler_ids);
        $systemUserModel = new SystemUserModel();
        if ($saler_ids) {
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
        }
        $contact_info = [];
        if ($list) {
            $contact_info = Db::name("Contacts")->where("id", $list[0]['contacts_id'])->field("contract_number,contract_type")->find();
        }

        foreach ($list as $key => $val) {
            $list[$key]['contract_number'] = isset($contact_info['contract_number']) ? $contact_info['contract_number'] : '';
            $list[$key]['contract_type'] = isset($contact_info['contract_type']) ? $contact_info['contract_type'] : 0;
            $list[$key]['certificate'] = $this->get_full_url($val['certificate']);
            $list[$key]['clerk_name'] = isset($saler_names[$val['admin_id']]) ? $saler_names[$val['admin_id']] : '';
            if ($val['pay_type'] == -1) {
                $list[$key]['pay_type'] = ' - ';
            } else {
                $list[$key]['pay_type'] = $pay_type[$val['pay_type']];
            }
            if ($val['type'] == '1') {
                $list[$key]['option'] = $out_type[$val['option']];
            } else {
                if ($val['is_ex'] == '1') {
                    //额外的收入
                    $list[$key]['option'] = $ex_income_type[$val['option']];
                } else {
                    $list[$key]['option'] = $income_type[$val['option']];
                }
            }
        }
    }


    /**
     * 销售结算表
     */
    public function settlement()
    {
        $get = $this->request->request();
        $db = Db::name("Contacts")->order('id desc');
        foreach (['contract_number'] as $key) {
            (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
        }
        if (isset($get['status'])) {
            $db->where('status', isset($get['status']) ? $get['status'] : 0);
        }
        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }

        if (isset($get['brand_name']) && $get['brand_name'] !== '') {
            $db->where('brand_name', $get['brand_name']);
        }

        if (isset($get['nickname']) && $get['nickname'] !== '') {
            $db->where('nickname', $get['nickname']);
        }

        if (isset($get['book_time']) && $get['book_time'] !== '') {
            list($start, $end) = explode(' - ', $get['book_time']);
            $db->whereBetween('book_time', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }

        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $db->field("id,contract_number,user_id,nickname,brand_name,car_model,car_color,saler_id,get_time,profit");
        $result = parent::_list($db);
        if ($result['list']) {
            $saler_ids = array_column($result['list'], 'saler_id');
            $saler_ids = array_unique($saler_ids);
            $saler_ids = implode(',', $saler_ids);
            $systemUserModel = new SystemUserModel();
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
            foreach ($result['list'] as $key => &$val) {
                $val['saler_name'] = $saler_names[$val['saler_id']];
                $settlement_info = Db::name("Settlement")->where("contacts_id", $val['id'])->order("id desc")->find();
                $val['status'] = -1;
                $val['is_substitute'] = 0;
                $val['substitute_money'] = 0;
                $val['is_refund'] = 0;
                $val['refund_money'] = 0;
                $val['is_other'] = 0;
                $val['other_money'] = 0;
                $val['ps'] = '';
                if ($settlement_info) {
                    $val['status'] = $settlement_info['status'];
                    $val['is_substitute'] = $settlement_info['is_substitute'];
                    $val['substitute_money'] = $settlement_info['substitute_money'];
                    $val['is_refund'] = $settlement_info['is_refund'];
                    $val['refund_money'] = $settlement_info['refund_money'];
                    $val['is_other'] = $settlement_info['is_other'];
                    $val['other_money'] = $settlement_info['other_money'];
                    $val['ps'] = $settlement_info['ps'];
                }
            }
        }
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 提交审核
     */
    public function do_settlement()
    {
        $get = $this->request->request();
        $verification = ['id', 'is_substitute', 'is_refund', 'is_other'];
        foreach ($verification as $key => $val) {
            if (!isset($get[$val])) {
                $this->error_json(1003, '缺少参数_' . $val);
            }
        }
        //TODO 文员的ID
        $savedata['admin_id'] = $this->system_user['id'];
        $savedata['contacts_id'] = $get['id'];
        $savedata['substitute_money'] = $get['substitute_money'] ? $get['substitute_money'] : 0;
        $savedata['refund_money'] = $get['refund_money'] ? $get['refund_money'] : 0;
        $savedata['other_money'] = $get['other_money'] ? $get['other_money'] : 0;
        $savedata['ps'] = $get['ps'];
        $savedata['status'] = 0;
        $savedata['is_substitute'] = ($get['is_substitute'] == 1) ? $get['is_substitute'] : 0;
        $savedata['is_refund'] = ($get['is_refund'] == 1) ? $get['is_refund'] : 0;
        $savedata['is_other'] = ($get['is_other'] == 1) ? $get['is_other'] : 0;

        $r = DataService::save("Settlement", $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功,等待审核', []);
    }


    /**
     * 数据打印
     */
    public function log_report(){
        $get = $this->request->request();
        if(!isset($get['export_type']) || $get['export_type'] == ''){
            echo "查询失败";
        }
        if (isset($get['export']) && $get['export'] == 1) {
            $financeService = new FinanceService();
            $financeService->log_report_excel($get);
            exit();
        }
        echo "查询失败";
    }


}
