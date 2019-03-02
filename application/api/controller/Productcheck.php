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
use app\api\service\ProductService;
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
 * 精品项目检查
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Productcheck extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'ProductCheck';


    /**
     * 审批列表
     */
    public function index()
    {
        $get = $this->request->request();
        $db = Db::name("Contacts")->order('id desc');

        if (isset($get['products_check']) && $get['products_check'] !== '') {
            $db->where('products_check', $get['products_check']);
        } else {
            $db->where('products_check', '>', 0);
        }

        if (isset($get['nickname']) && $get['nickname'] !== '') {
            $db->where('nickname', $get['nickname']);
        }

        if (isset($get['phone']) && $get['phone'] !== '') {
            $db->where('phone', $get['phone']);
        }

        $result = parent::_list($db);
        $result['un_done_count'] = Db::name("Contacts")->where("products_check", 1)->count();
        $result['done_count'] = Db::name("Contacts")->where("products_check", 2)->count();
        $this->success_json('获取数据成功', $result);
    }

    public function _index_data_filter(&$list)
    {
        $productService = new ProductService();
        foreach ($list as $key => $val) {
            if (!isset($val['saler_id'])) {
                $list[$key]["saler_name"] = '';
            } else {
                $user_info = Db::name("system_user")->where(['id' => $val['saler_id']])->find();
                $list[$key]["saler_name"] = $user_info['name'];
            }
            //TODO products_check
            if($val['status'] == 1 && $val['product_w'] == 1){
                $list[$key]['products_withdraw'] = 1;
            }else{
                $list[$key]['products_withdraw'] = 0;
            }
            $list[$key]['cost'] = $productService->get_products_cost($val['products_ids']);
        }
    }

    /**
     * 精品项目列表
     */
    public function product_detail()
    {
        $get = $this->request->request();
        $verification = ['id'];
        foreach ($verification as $key => $val) {
            if (!isset($get[$val])) {
                $this->error_json(1003, '缺少参数_' . $val);
            }
        }
        $contact_info = Db::name("Contacts")->where("id", $get['id'])->where("is_deleted", 0)->find();
        if (!$contact_info) {
            $this->error_json(400, '合同不存在');
        }
        $list = Db::name($this->table)->where("contacts_id", $get['id'])->select();
        $prductModel = new ProductsModel();
        foreach ($list as $key => &$val) {
            $product_info = $prductModel->getInfo($val['product_id']);
            $val['product_name'] = $product_info['name'];
            $val['product_content'] = $product_info['content'];
            $val['product_ps'] = $product_info['ps'];
            $val['market_price'] = $product_info['market_price'];
            $val['price'] = $product_info['price'];
            $val['cate_name'] = $product_info['cate_name'];
            $val['cost'] = $product_info['cost'];
        }
        $result['list'] = $list;
        $result['total'] = count($list);
        $result['keyWord'] = isset($get) ? $get : (object)[];
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 精品完成确认
     */
    public function do_finish()
    {
        $get = $this->request->request();
        $verification = ['id'];
        foreach ($verification as $key => $val) {
            if (!isset($get[$val])) {
                $this->error_json(1003, '缺少参数_' . $val);
            }
        }
        $check_info = Db::name($this->table)->where("id", $get['id'])->find();
        if (!$check_info) {
            $this->error_json(400, '信息不存在');
        }
        if($check_info['status'] == 1){
            $this->success_json('已经提交了', []);
        }
        $contact_info = Db::name("Contacts")->where("id", $check_info['contacts_id'])->find();
        $contact_check_count = Db::name($this->table)
            ->where("contacts_id", $check_info['contacts_id'])
            ->where("status", 0)
            ->where("id", "<>", $get['id'])
            ->count();
        Db::startTrans();
        $savedata['id'] = $get['id'];
        $savedata['dotime'] = date("Y-m-d H:i:s", time());
        $savedata['status'] = 1;

        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            Db::rollback();
            $this->error_json(400, '保存失败，请稍后重试', []);
        }

        if ($contact_check_count == 0) {
            //全部完成了修改合同状态
            $save_contact['id'] = $contact_info['id'];
            $save_contact['products_check'] = 2;
            $re = DataService::save("Contacts", $save_contact, 'id');
            if (!$re) {
                Db::rollback();
                $this->error_json(400, '保存失败，请稍后重试', []);
            }
        }

        Db::commit();
        //成功推送
        $productService = new ProductService();
        $productService->product_push($contact_info);
        $this->success_json('提交成功', []);
    }


    //精品提现，
    public function products_withdraw(){
        $get = $this->request->request();
        $verification = ['id'];
        foreach ($verification as $key => $val) {
            if (!isset($get[$val])) {
                $this->error_json(1003, '缺少参数_' . $val);
            }
        }
        $productService = new ProductService();
        $r = $productService->withdraw($get['id']);
        if($r['error_code'] != 0){
            $this->error_json(400, $r['msg']);
        }
        $this->success_json('提现成功', []);
    }



}
 