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

use app\api\model\ContactsInsuranceModel;
use app\api\model\ContactsLicenseModel;
use app\api\model\ContactsLogisticsModel;
use app\api\model\ContactsProcessModel;
use app\api\model\SystemUserModel;
use app\api\model\UserModel;
use app\api\service\ManagerService;
use app\api\service\SettlementService;
use controller\BasicApi;
use service\DataService;
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
 * 销售经理的操作
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Manager extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Contacts';

    /**
     * 销售经理人的统计
     */
    public function get_statistics()
    {
        $result = [
            'all_order_count' => 0,       //本月公司订单
            'all_order_complete' => 0,    //本月公司交车
            'team_order_count' => 0,      //本月团队订单
            'team_order_complete' => 0,   //本月团队交车
        ];
        $this->success_json('获取数据成功', $result);
    }


    /**
     * 团队数据
     */
    public function team_statistics()
    {
        $result = [
            'team_complete' => 1000,       //团队已完成（单位万）
            'team_target' => 1000,    //公司本季度目标（单位万）
        ];
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 本月团队已完成统计
     */
    public function team_complete_statistics()
    {
        $result = [
            [
                'saler_id' => 10,     //销售ID
                'saler_name' => 'rckdong',        //销售昵称
                'month_difference' => '2500000',      //本月离目标还差多少钱
                'target' => '季度开票任务200万',     //季度目标开票任务
                'quarter_difference' => '-500000',        //季度离目标还差多少钱
                'month_complete_percent' => '20%',      //本月完成目标百分比
                'quarter_complete_percent' => '120%',   //本季度完成模板百分比
            ],
            [
                'saler_id' => 1,     //销售ID
                'saler_name' => '程晓丽',        //销售昵称
                'month_difference' => '200000',      //本月离目标还差多少钱
                'target' => '季度开票任务200万',     //季度目标开票任务
                'quarter_difference' => '500000',        //季度离目标还差多少钱
                'month_complete_percent' => '20%',      //本月完成目标百分比
                'quarter_complete_percent' => '50%',   //本季度完成模板百分比
            ]
        ];
        $res['total'] = 2;
        $res['list'] = $result;
        $res['keyWord'] = [];
        $this->success_json('获取数据成功', $res);
    }

    /**
     * 审批列表
     */
    public function approval_list()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsApproval")->order('id desc')->where("manage_type", 0);

        $db->where('status', isset($get['status']) ? $get['status'] : 0);
        $db->where("is_deleted", 0);

        $result = parent::_list($db);
        $this->success_json('获取数据成功', $result);
    }

    public function _approval_list_data_filter(&$list)
    {
        foreach ($list as $key => $val) {
            $info = Db::name($this->table)
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
        $managerService = new ManagerService();
        $r = $managerService->do_approval($post);
        if (!$r) {
            $this->error_json(400, "审核失败,请稍后操作", []);
        }
        //TODO 修改合同
        $this->success_json('修改成功', []);
    }

    /**
     * 我的奖励列表
     */
    public function my_reward_list()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsReward")->order('id desc');
        //TODO 搜索我的奖励列表
//        $db->where('saler_id', isset($get['saler_id']) ? $get['saler_id'] : 0);
//        $db->where('status', isset($get['status']) ? $get['status'] : 0);

//        if (isset($get['phone'])) {
//            $db->where('phone', isset($get['phone']) ? $get['phone'] : '');
//        }

        $result = parent::_list($db);
//        print_r($result);exit;

        $system_id = isset($this->system_user['id']) ? $this->system_user['id'] : 10000;
        $system_info = Db::name("SystemUser")->where("id", $system_id)->find();
        $result['money'] = $system_info['money'];
        $this->success_json('获取数据成功', $result);
    }

    public function _my_reward_list_data_filter(&$list)
    {
        foreach ($list as $key => $val) {
            $list[$key]['reward'] = round($val['reward'] * 0.8, 2);
            $info = Db::name($this->table)
                ->where('id', $val['contacts_id'])
                ->field("saler_id,contract_number,book_time,nickname,brand_name,car_model,guidance_price,transaction_price,contract_type,is_withdraw_ok,withdraw_desc")
                ->find();
            $is_settlement = Db::name("OrderSettlement")->where("contacts_id", $val['contacts_id'])->count();
            if ($is_settlement > 0) {
                $list[$key]['is_settlement'] = 1;
            } else {
                $list[$key]['is_settlement'] = 0;
            }
            foreach (["saler_id", "contract_number", "book_time", "nickname", "brand_name", "car_model", "guidance_price", "transaction_price", "contract_type","is_withdraw_ok","withdraw_desc"] as $k => $v) {
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
     * 奖励列表
     */
    public function reward_list()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsReward")->order('id desc');

//        $db->where('status', isset($get['status']) ? $get['status'] : 0);

        $result = parent::_list($db);
        $this->success_json('获取数据成功', $result);
    }

    public function _reward_list_data_filter(&$list)
    {
        $SettlementService = new SettlementService();
        foreach ($list as $key => $val) {
            $list[$key]['reward'] = round($val['reward'] * 0.1, 2);
            $info = Db::name($this->table)
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
            $order_settlement_info = $SettlementService->get_info($val['contacts_id']);
            $list[$key]['company_net_profit'] = $order_settlement_info['company_net_profit'];
            $list[$key]['zmanage_profit'] = $order_settlement_info['zmanage_profit'];
            $list[$key]['sales_profit'] = $order_settlement_info['sales_profit'];
            $list[$key]['manage_profit'] = $order_settlement_info['manage_profit'];
        }
    }


    /**
     * 经理提现到余额申请
     */
    public function withdraw()
    {
        //TODO 经理申请提现申请
        $post = $this->request->request();
        foreach (['id'] as $key) {
            if (!isset($post[$key])) {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
        $info = Db::name("ContactsReward")->where("id", $post['id'])->find();
        $admin_info = Db::name("SystemUser")->where("id", $info['admin_id'])->find();
//        $user_info = Db::name("SystemUser")->where("id", $info['saler_id'])->find();

        if ($info['status'] == 1) {
            $this->error_json(400, '已经提现了', []);
        }

        //TODO 提现到余额
        $save_data['id'] = $post['id'];
        $save_data['status'] = 1;
        Db::startTrans();
        $r = DataService::save("ContactsReward", $save_data, 'id');
        if (!$r) {
            Db::rollback();
            $this->error_json(400, '申请失败，请稍后重试', []);
        }
        if ($admin_info) {
            $user_data['id'] = $info['admin_id'];
            $user_data['money'] = $admin_info['money'] + round($info['reward'] * 0.1, 2);
            $a_r = DataService::save("SystemUser", $user_data);
            if (!$a_r) {
                Db::rollback();
                $this->error_json(400, '申请失败，请稍后重试', []);
            }
        }
//        if ($user_info) {
//            $user_data['id'] = $info['saler_id'];
//            $user_data['money'] = $user_info['money'] + round($info['reward'] * 0.8, 2);
//            $u_r = DataService::save("SystemUser", $user_data);
//            if (!$u_r) {
//                Db::rollback();
//                $this->error_json(400, '申请失败，请稍后重试', []);
//            }
//        }

        Db::commit();

        $this->success_json('提现成功', []);
    }

}
 