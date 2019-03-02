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
use app\api\service\MortgageService;
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
class Mortgage extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Contacts';


    /**
     * 审批列表
     */
    public function index()
    {
        $get = $this->request->request();
        $db = Db::name("ContactsMortgage")->order('id desc');

        if (isset($get['status']) && $get['status'] !== '') {
            $db->where('status', isset($get['status']) ? $get['status'] : 0);
        }
        if (isset($get['nickname']) && $get['nickname'] !== '') {
            $db->where('nickname', $get['nickname']);
        }
        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }

        $result = parent::_list($db);
        $result['un_examine_count'] = Db::name("ContactsMortgage")->where("status", 0)->count();
        $this->success_json('获取数据成功', $result);
    }

    public function _index_data_filter(&$list)
    {
        foreach ($list as $key => $val) {
            $info = Db::name($this->table)
                ->where('id', $val['contacts_id'])
                ->field("saler_id,contract_number,book_time,nickname,brand_name,car_model,guidance_price,transaction_price,contract_type")
                ->find();
            foreach (["saler_id", "contract_number", "book_time", "nickname", "brand_name", "car_model", "guidance_price", "transaction_price", "contract_type", "car_color"] as $k => $v) {
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
    public function do_examine()
    {
        $post = $this->request->request();
        foreach (['id', 'status'] as $key) {
            if (!isset($post[$key])) {
                $this->error_json(1003, '参数不足' . $key, []);
            }
        }
//        $info = Db::name("ContactsMortgage")->where("id", $post['id'])->find();
//        if ($info['status'] != 0) {
//            $this->error_json(400, '该审批已经审核过了', []);
//        }
        //TODO 审批操作
        $save_data['id'] = $post['id'];
        $save_data['status'] = $post['status'];
        $save_data['false_reason'] = isset($post['reason']) ? $post['reason'] : '';
        $save_data['money'] = isset($post['money']) ? $post['money'] : '';
        $r = DataService::save("ContactsMortgage", $save_data, 'id');
        if (!$r) {
            $this->error_json(400, '申请失败，请稍后重试', []);
        }
        $mortgageModel = new MortgageService();
        $mortgageModel->do_examine($save_data);
        $this->success_json('操作成功', []);
    }
}
 