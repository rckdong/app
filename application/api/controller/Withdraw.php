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

use app\api\model\FinanceModel;
use app\api\model\SystemUserModel;
use app\api\service\AdministrativeService;
use app\api\service\FinanceService;
use controller\BasicApi;
use service\DataService;
use think\App;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Withdraw extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemWithdraw';

    /**
     * 申请提现列表
     */
    public function index()
    {
        $get = $this->request->request();
        $db = Db::name($this->table)->where("is_deleted", 0)->order("id desc");
        if (isset($get['status'])) {
            $db->where('status', isset($get['status']) ? $get['status'] : 0);
        }
        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $result = parent::_list($db);
        if ($result['list']) {
            $saler_ids = array_column($result['list'], 'create_by');
            $saler_ids = array_unique($saler_ids);
            $saler_ids = implode(',', $saler_ids);
            $systemUserModel = new SystemUserModel();
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
            foreach ($result['list'] as $key => &$val) {
                $val['saler_name'] = $saler_names[$val['create_by']];
            }
        }
        $this->success_json('获取数据成功', $result);
    }


    /**
     * 审核
     */
    public function do_examine()
    {
        $get = $this->request->request();
        $vi_arr = ['id', 'status'];
        foreach ($vi_arr as $key => $val) {
            if (!isset($get[$val]) || $get[$val] === '') {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        $info = Db::name($this->table)->where("id", $get['id'])->find();

        if (!$info) {
            $this->error_json(400, '信息不存在', []);
        }
        if ($info['status'] != 0) {
            $this->error_json(400, '请重复操作', []);
        }
        $user_info = Db::name("SystemUser")->where("id", $info['create_by'])->find();
        Db::startTrans();
        $savedata['id'] = $info['id'];
        $savedata['desc'] = isset($get['reason']) ? $get['reason'] : '';
        $savedata['status'] = intval($get['status']);
        $savedata['certificate'] = $this->get_full_url($get['certificate']);
        $r = DataService::save($this->table, $savedata);
        if (!$r) {
            Db::rollback();
            $this->error_json(400, '操作失败，请稍后重试', []);
        }
        if ($savedata['status'] == 2) {
            //审核失败，不打款
            $sy_user['money'] = $user_info['money'] + $info['omoney'];
            $sy_user['id'] = $user_info['id'];
            $r = DataService::save("SystemUser", $sy_user);
            if (!$r) {
                Db::rollback();
                $this->error_json(400, '操作失败，请稍后重试', []);
            }
        }elseif($savedata['status'] == 1){
            //添加行政收支记录
            $r = AdministrativeService::add_withdraw_log($user_info,$info,$this->system_user, $savedata['certificate']);
            if (!$r){
                Db::rollback();
                $this->error_json(400, '提现失败，请稍后重试', []);
            }
        }
        //TODO 推送消息
        Db::commit();
        if($user_info['openid']){
            $push_data['openid'] = $user_info['openid'];
            $push_data['url'] = '';
            $push_data['first'] = '';
            $push_data['keyword1'] = '金额：'.$info['money'];
            $push_data['keyword2'] = '';
            if($savedata['status'] == 1){
                $push_data['first'] = '您申请的佣金提现，审核通过，请等待财务打款';
            }elseif ($savedata['status'] == 2){
                $push_data['first'] = '您申请的佣金提现，审核不通过，金额原路返回钱包';
            }

        }
        $this->success_json('获取数据成功', []);
    }


    /**
     * 审核，提现到钱包
     */
    public function examine_to_wallet(){
        $get = $this->request->request();
        $vi_arr = ['id','status'];
        foreach ($vi_arr as $key => $val) {
            if (!isset($get[$val]) || $get[$val] === '') {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        $financeService = new FinanceService();
        $r = $financeService->examine_to_wallet($get);
        if ($r['code'] != 200) {
            $this->error_json($r['code'], $r['msg'], []);
        }
        $this->success_json('获取数据成功', []);
    }

}
