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

use app\api\service\ContractService;
use controller\BasicApi;
use service\DataService;
use service\PushService as extend_push;
use think\App;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Touzi extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';

    /**
     * 用户列表
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function index()
    {
        $info['all_count'] = Db::name("Contacts")->where("is_temp", 0)->count();
        $info['finish_count'] = Db::name("Contacts")->where("status", 1)->count();
        $info['out'] = Db::name("Administrative")->where("type", 1)->sum("money");
        $info['income'] = Db::name("Administrative")->where("type", 0)->sum("money");
        $info['f_income'] = Db::name("Finance")->where("type", 0)->sum("money");
        $info['f_out'] = Db::name("Finance")->where("type", 1)->sum("money");
        $info['company_money'] = $info['income'] + $info['f_income'] - $info['out'] - $info['f_out'];
        $this->success_json('获取数据成功', $info);
    }


    /**
     * 审批列表
     */
    public function approval_list()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsApproval")->order('id desc')->where("manage_type",1)->where("is_deleted",0);

        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        $result =  parent::_list($db);
        $this->success_json('获取数据成功', $result);
    }

    public function _approval_list_data_filter(&$list)
    {
        foreach ($list as $key => $val) {
            $info = Db::name("Contacts")
                ->where('id', $val['contacts_id'])
                ->field("saler_id,contract_number,book_time,nickname,brand_name,car_model,guidance_price,transaction_price,contract_type")
                ->find();
            foreach (["saler_id", "contract_number", "book_time", "nickname", "brand_name", "car_model", "guidance_price", "transaction_price", "contract_type"] as $k => $v) {
                if ($v == 'saler_id') {
                    if (!isset($info[$v])) {
                        $list[$key]["saler_name"] = '';
                    } else {
                        $user_info = Db::name("system_user")->where(['id' => $info['saler_id']])->find();
                        $list[$key]["saler_name"] = $user_info['name'];
                    }
                }
                $list[$key][$v] = isset($info[$v]) ? $info[$v] : '';
            }
        }
    }

    /**
     * 审批操作
     */
    public function do_approval()
    {
        $post = $this->request->request();
        foreach (['id', 'status'] as $key) {
            if (!isset($post[$key])) {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        $savedata['id'] = $post['id'];
        $savedata['false_reason'] = $post['reason'];
        $savedata['status'] = $post['status'];
        $res = DataService::save("ContactsApproval",$savedata);
        if(!$res){
            $this->error_json(400, "审核失败,请稍后操作", []);
        }
        $r = $this->after_do($savedata);
        if (!$r) {
            $this->error_json(400, "审核失败,请稍后操作", []);
        }
        //TODO 修改合同
        $this->success_json('修改成功', []);
    }

    public function after_do($data){
//        操作审核
        $id = $data['id'];
        $status = $data['status'];
        $false_reason = $data['false_reason'];
        $info = Db::name("ContactsApproval")->where("id",$id)->find();
        if(!$info){
            return false;
        }
        $contract_info = Db::name("Contacts")->where("id",$info['contacts_id'])->find();
        if(!$contract_info){
            return false;
        }
        $savedata['id'] = $info['contacts_id'];
        if($status == 1){
            $savedata['is_cancel'] = 0;
            $savedata['boss_confirm'] = 1;
        }else{
            $savedata['false_reason'] = $false_reason;
            $savedata['is_cancel'] = 9;
            $savedata['boss_confirm'] = 0;
        }
        $r = DataService::save("Contacts",$savedata);
        if(!$r){
            return false;
        }
        $contractService = new ContractService();
        if($status == 1){
            //通过，通知用户
            //用户审核
            if ($contract_info) {
                $contractService->contract_push_client($contract_info);
            }
        }else{
            //不通过通知销售
            if ($contract_info['saler_id']) {
                $send_data['url'] = '';
                $send_data['first'] =  '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过,需修改';
                $send_data['keyword1'] =  '';
                $send_data['keyword2'] =  '';
                extend_push::notify($contract_info['saler_id'], ['status' => 200, 'msg' => '合同编号：' . $contract_info['contract_number'] . ' ,审核不通过', 'data' => $send_data]);
            }
        }
        return true;
    }

}
