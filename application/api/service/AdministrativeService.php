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

use app\api\model\AdministrativeModel;
use app\api\model\ContactsModel;
use app\api\model\FinanceModel;
use service\DataService;
use service\ToolsService;
use service\WechatService;
use think\Db;

/**
 * 财务服务
 * Class FansService
 * @package app\wechat
 */
class AdministrativeService
{
//    导出excel
    public function export_excel($data)
    {
        $strTable = '<table width="900" border="1">';
        $strTable .= '<tr><td colspan="9" style="text-align:center;font-size:18px;height: 32px;line-height: 32px;">行政收支记录</td></tr>';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >部门</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >资产类型</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >收支选项</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="100">金额</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发票信息</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">备注</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">创建时间</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">操作人</td>';
        $strTable .= '</tr>';
        $contract_number = '';
        $contract_types = '';
        foreach ($data as $key => $val) {
            $type = ($val['type'] == 1) ? '支出' : '收入';
            $invoice = ($val['is_invoice'] == 1) ? '有发票' : '无发票';
            $contract_types = $type;
            if ($val['type'] == 1) {
                $asset_type = $val['asset_type'];
            } else {
                $asset_type = '';
            }
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $val["department"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">' . $type . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $asset_type . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*" >' . $val["option"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["money"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $invoice . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["ps"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["create_at"] . '</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">' . $val["clerk_name"] . '</td>';
            $strTable .= '</tr>';
        }
        $strTable .= '</table>';
        downloadExcel($strTable, "行政" . $contract_types . '记录');
        exit();
    }

    /**
     * 添加提现记录
     * @param $user_info
     * @param $withdraw_info
     * @return bool
     */
    public static function add_withdraw_log($user_info, $withdraw_info, $system_user,$img_url)
    {
        $administrativeModel = new AdministrativeModel();
        $out_put = $administrativeModel->get_out_type();
        $bumen = Db::name("Ganwei")
            ->alias("gw")
            ->where("gw.id", $user_info['ganwei_id'])
            ->join("bumen", "bumen.id = gw.bumen_id", 'LEFT')
            ->field("bumen.id,bumen.name")
            ->find();
        //TODO 删掉
        $savedata['department_id'] = $bumen['id'];
        $savedata['admin_id'] = isset($system_user['id']) ? $system_user['id'] : 10000;
        $savedata['type'] = 1;
        $savedata['option'] = $administrativeModel->get_out_type_id($out_put[17]);
        $savedata['asset_type'] = '';
        $savedata['is_invoice'] = 1;
        $savedata['certificate'] = $img_url;
        $savedata['money'] = $withdraw_info['money'];
        $savedata['ps'] = $bumen['name'] . '的' . $user_info['name'] . '提现【佣金】，打款' . $withdraw_info['money'] . '元';
        $r = DataService::save("Administrative", $savedata);

        if($user_info['pid'] != 0 && $user_info['pid'] != $user_info['id'] && $withdraw_info['pmoney']){
            //给上级加余额
            //TODO 加余额记录
            $puser_info = Db::name("SystemUser")->where("id", $user_info['pid'])->find();
            if($puser_info){
                $sy_user['money'] = $puser_info['money'] + $withdraw_info['pmoney'];
                $sy_user['id'] = $puser_info['id'];
                $res = DataService::save("SystemUser", $sy_user);
                if(!$res){
                    return false;
                }
            }
        }
        return $r;
    }

}