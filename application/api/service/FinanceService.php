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
use app\api\model\SystemUserModel;
use service\DataService;
use service\ToolsService;
use service\WechatService;
use think\Db;

/**
 * 财务服务
 * Class FansService
 * @package app\wechat
 */
class FinanceService
{

    public function log_report_excel($get)
    {
        $db = Db::name("Finance")->where("status", 1)->where("is_deleted", 0)->order("contacts_id desc");
        switch ($get['export_type']) {
            case "exweek":
                $beginLastweek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
                $endLastweek = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
                $beginLastweek = date("Y-m-d", $beginLastweek);
                $endLastweek = date("Y-m-d", $endLastweek);
                $db->whereBetween('create_at', ["{$beginLastweek} 00:00:00", "{$endLastweek} 23:59:59"]);
                break;
            case "week":
                $beginThisweek = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y")));
                $endThisweek = date("Y-m-d", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y")));
                $db->whereBetween('create_at', ["{$beginThisweek} 00:00:00", "{$endThisweek} 23:59:59"]);
                break;
            case "exmonth":
                $beginLastmonth = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
                $endThLastmonth = date("Y-m-d", mktime(23, 59, 59, date("m"), 0, date("Y")));
                $db->whereBetween('create_at', ["{$beginLastmonth} 00:00:00", "{$endThLastmonth} 23:59:59"]);
                break;
            case "month":
                $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
                $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                $beginThismonth = date("Y-m-d", $beginThismonth);
                $endThismonth = date("Y-m-d", $endThismonth);
                $db->whereBetween('create_at', ["{$beginThismonth} 00:00:00", "{$endThismonth} 23:59:59"]);
                break;
            case "exquarter":
                $season = ceil((date('n')) / 3) - 1;//上季度是第几季度
                $beginLastseason = date('Y-m-d', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
                $endLastseason = date('Y-m-d', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));
                $db->whereBetween('create_at', ["{$beginLastseason} 00:00:00", "{$endLastseason} 23:59:59"]);
                break;
            case "quarter":
                $season = ceil((date('n')) / 3);//当月是第几季度
                $beginThisseason = date('Y-m-d', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
                $endThisseason = date('Y-m-d', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));
                $db->whereBetween('create_at', ["{$beginThisseason} 00:00:00", "{$endThisseason} 23:59:59"]);
                break;
        }
        $res = $db->select();
        if(!$res){
            echo "暂无数据";
            exit();
        }
        //操作文员
        $saler_ids = array_column($res, 'admin_id');
        $saler_ids = array_unique($saler_ids);
        $saler_ids = implode(',', $saler_ids);
        $systemUserModel = new SystemUserModel();
        $clerk_name = [];
        if ($saler_ids) {
            $clerk_name = $systemUserModel->getNameByIds($saler_ids);
        }


        $contacts_ids = array_column($res,'contacts_id');
        $contacts_ids = array_unique($contacts_ids);
        $contract_infos = Db::name("Contacts")
            ->alias("c")
            ->join('system_user',"system_user.id = c.saler_id",'LEFT')
            ->whereIn('c.id',$contacts_ids)
            ->column("c.id,c.contract_number,c.contract_type,c.nickname,c.brand_name,c.car_model,c.car_color,c.guidance_price,c.transaction_price,c.phone,c.saler_id,system_user.name");
        $FinanceModel = new FinanceModel();
        $income_type = $FinanceModel->get_incom_type();
        $out_type = $FinanceModel->get_out_type();
        $pay_type = $FinanceModel->get_pay_type();
        $ex_income_type = $FinanceModel->get_ex_income_type();

        $strTable = '<table width="1700" border="1">';
        $strTable .= '<tr><td colspan="17" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">收支记录报表</td></tr>';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >合同编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">合同类型</td>';

        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">客户名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">品牌名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">车型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">颜色</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">指导价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">成交价</td>';

        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">销售</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收支类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">收支方式</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">手续费</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">备注</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">创建时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">操作人</td>';
        $strTable .= '</tr>';
        $contract_number = '';
        $contract_types = '';
        foreach ($res as $key => $val) {
            $contract_type = ($contract_infos[$val['contacts_id']]['contract_type'] == 1) ? '定车合同' : '订车合同';
            $type = ($val['type'] == 1) ? '支出' : '收入';
            $contract_number = $contract_infos[$val['contacts_id']]['contract_number'];
            $saler = $contract_infos[$val['contacts_id']]['name'];

            if ($val['pay_type'] == -1) {
                $pay_type_value = ' - ';
            } else {
                $pay_type_value = $pay_type[$val['pay_type']];
            }
            if ($val['type'] == '1') {
                $option_value = $out_type[$val['option']];
            } else {
                if ($val['is_ex'] == '1') {
                    //额外的收入
                    $option_value = $ex_income_type[$val['option']];
                } else {
                    $option_value = $income_type[$val['option']];
                }
            }


            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $contract_number . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $type . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_type . '</td>';

            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['nickname'] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['brand_name'] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['car_model'] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['car_color'] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['guidance_price'] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_infos[$val['contacts_id']]['transaction_price'] . '</td>';


            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $saler . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $option_value . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $pay_type_value . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["money"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["poundage"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["ps"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["create_at"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $clerk_name[$val['admin_id']] . '</td>';
            $strTable .= '</tr>';
        }
        $strTable .= '</table>';
        downloadExcel($strTable, "财务导出");
        exit();
    }

//    导出excel
    public function export_excel($data)
    {
        $financeModel = new FinanceModel();
        $strTable = '<table width="900" border="1">';
        $strTable .= '<tr><td colspan="10" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">收支记录详情</td></tr>';
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
        $contract_number = '';
        $contract_types = '';
        foreach ($data as $key => $val) {
            $contract_type = ($val['contract_type'] == 1) ? '定车合同' : '订车合同';
            $type = ($val['type'] == 1) ? '支出' : '收入';
            $contract_number = $val['contract_number'];
            $contract_types = $type;
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $val["contract_number"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $type . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $contract_type . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["option"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["pay_type"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["money"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["poundage"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["ps"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["create_at"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["clerk_name"] . '</td>';
            $strTable .= '</tr>';
        }
        $strTable .= '</table>';
        downloadExcel($strTable, "合同编号" . $contract_number . '_' . $contract_types . '记录');
        exit();
    }

    public function check_contract($id)
    {
        if (!$id) {
            return false;
        }
        $contractModel = new ContactsModel();
        $contract_info = $contractModel->getOne($id);
        if (!$contract_info) {
            return false;
        }
        if ($contract_info['client_confirm'] != 1 || $contract_info['manage_confirm'] != 1 || $contract_info['boss_confirm'] != 1) {
            return false;
        }
        return $contract_info;
    }

    public function do_add_income_log($data, $system_id, $contract_info)
    {
//        $contractModel = new ContactsModel();
//        $contract_info = $contractModel->getOne($data['contacts_id']);
        if (!$contract_info) {
            return false;
        }
        if ($data['type'] == 0) {
            //收入
            $savedata['id'] = $data['contacts_id'];
            $savedata['pay_money'] = $contract_info['pay_money'] + $data['money'];
            $savedata['is_deposit_ok'] = 0;
            if ($savedata['pay_money'] >= $contract_info['deposit_price']) {
                $savedata['is_deposit_ok'] = 1;     //已付定金
            }
            $r = DataService::save("Contacts", $savedata);
            if (!$r) {
                return false;
            }
            //添加按揭
            //是否给了定金，用户是否确认，是否分期购买，是否申请按揭了
            if ($savedata['is_deposit_ok'] == 1 && $contract_info['client_confirm'] == 1 && $contract_info['buy_type'] == 1) {
                $contactsMortgageCount = Db::name("ContactsMortgage")->where("contacts_id", $data['contacts_id'])->count();
                if ($contactsMortgageCount == 0) {
                    //付了定金，按揭购买
                    //TODO 分配问题
                    $insert['contacts_id'] = $data['contacts_id'];
                    $insert['admin_id'] = 10000;
                    $insert['phone'] = $contract_info['phone'];
                    $insert['nickname'] = $contract_info['nickname'];
                    $i_r = DataService::save("ContactsMortgage", $insert, 'contacts_id');
                    if (!$i_r) {
                        return false;
                    }
                    $mortgage_log['contacts_id'] = $data['contacts_id'];
                    $mortgage_log['desc'] = '按揭申请中';
                    $mortgage_log['status'] = 0;
                    $m_r = DataService::save("MortgageLog", $mortgage_log, 'contacts_id');
                    if (!$m_r) {
                        return false;
                    }
                }
            }

            if ($contract_info['buy_type'] == 0) {
                //一次性付款
                $contract['id'] = $contract_info['id'];
                $contract['clerk_show'] = 1;
                $contractService = new ContractService();
                $c_r = $contractService->set_clerk_show($contract);
                if (!$c_r) {
                    return false;
                }
            }

//            $precent_money = $contract_info['transaction_price'] * 0.3;
            //销售文员显示
            //是否已经付了30%，是否贷款下来了
//            if($savedata['pay_money'] >= $precent_money ){
//                if($contract_info['buy_type'] == 1){
//                    if($contract_info['loan_money']!=0){
//                        //贷款下来了
//                        //TODO 单独分出了
//                        $contract['id'] = $contract_info['id'];
//                        $contract['clerk_show'] = 1;
//                        $c_r = DataService::save("Contacts",$contract);
//                        if(!$c_r){
//                            return false;
//                        }
//                    }
//                }else{
//                    //一次性购买的，走一次性流程
//                    //TODO 单独分出了
//                    $contract['id'] = $contract_info['id'];
//                    $contract['clerk_show'] = 1;
//                    $c_r = DataService::save("Contacts",$contract);
//                    if(!$c_r){
//                        return false;
//                    }
//                }
//            }
            //通知用户，支付成功
            $data['user_id'] = $contract_info['user_id'];
            $data['nickname'] = $contract_info['nickname'];
            $data['phone'] = $contract_info['phone'];
            $data['brand_name'] = $contract_info['brand_name'];
            $data['car_color'] = $contract_info['car_color'];
            $data['car_model'] = $contract_info['car_model'];
            PushService::pay_success($data);
        }
    }


    /**
     * 申请提现到余额
     * @param $contact_id
     * @return array
     */
    public function check_witdraw($contact_id)
    {
        $contactsModel = new ContactsModel();
        $contact_info = $contactsModel->getOne($contact_id)->toArray();
        if (!$contact_info) {
            return ['code' => 400, 'msg' => '信息不存在', 'data' => ''];
        }
        if ($contact_info['contract_type'] == 2) {
            return ['code' => 5001, 'msg' => '该合同为订车合同，不能提现', 'data' => ''];
        }
        if ($contact_info['transaction_price'] > $contact_info['pay_money']) {
            return ['code' => 5002, 'msg' => '车款还未收完，不能提现', 'data' => ''];
        }
        if (!$contact_info['frame_number']) {
            return ['code' => 5003, 'msg' => '暂未上传车架号，不能提现', 'data' => ''];
        }
        if (!$contact_info['products_check']) {
            return ['code' => 5005, 'msg' => '精品检查暂未完成，不能提现', 'data' => ''];
        }
        if (!$contact_info['is_insuramce'] || !$contact_info['is_process'] || !$contact_info['is_logistics'] || !$contact_info['is_license']) {
            return ['code' => 5006, 'msg' => '手续未操作完成，不能提现', 'data' => ''];
        }
        if (!$contact_info['pass_code']) {
            return ['code' => 5006, 'msg' => '放行条未生成，不能提现', 'data' => ''];
        }
        if ($contact_info['status'] != 1) {
            return ['code' => 5006, 'msg' => '合同状态未完成，不能提现', 'data' => ''];
        }
        if ($contact_info['is_withdraw_ok'] != 3) {
            return ['code' => 5006, 'msg' => '已经操作过了', 'data' => ''];
        }
        return ['code' => 200, 'msg' => '', 'data' => $contact_info];
    }

    public function examine_to_wallet($data)
    {
        $contract_id = $data['id'];
        $check = $this->check_witdraw($contract_id);
        if ($check['code'] != 200) {
            return $check;
        }

        $contract_info = $check['data'];
        $systemUserModel = new SystemUserModel();
        $system_user_info = $systemUserModel->getOne(['id' => $contract_info['saler_id']]);
        $contacts_reward = Db::name("ContactsReward")->where("contacts_id", $contract_info['id'])->find();
        if (!$system_user_info || !$contacts_reward) {
            return ['code' => 5600, 'msg' => '暂无数据，请稍后重试', 'data' => []];
        }
        Db::startTrans();
        $savedata['id'] = $data['id'];
        $savedata['is_withdraw_ok'] = intval($data['status']);
        $savedata['withdraw_desc'] = $data['reason'];
        //修改合同提现状态
        $r = DataService::save("Contacts", $savedata);
        if (!$r) {
            Db::rollback();
            return ['code' => 400, 'msg' => '修改失败', 'data' => []];
        }
        //销售提现
        if ($data['status'] == 1) {
            $sale_data['id'] = $system_user_info['id'];
//            $sale_data['money'] = $system_user_info['money'] + bcmul($contacts_reward['reward'],0.8,2);
            $sale_data['money'] = $system_user_info['money'] + $contacts_reward['reward'];
            $r_sysuser = DataService::save("SystemUser", $sale_data);
            if (!$r_sysuser) {
                Db::rollback();
                return ['code' => 400, 'msg' => '修改失败', 'data' => []];
            }
        }
        Db::commit();
        return ['code' => 200, 'msg' => '审核成功', 'data' => []];
    }

}