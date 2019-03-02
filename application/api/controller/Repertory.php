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

use app\api\model\SystemUserModel;
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
class Repertory extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Repertory';


    /**
     * 财务主页
     */
    public function index()
    {
        $get = $this->request->request();
        $db = Db::name("Contacts")->order('id desc');
        $db->where('frame_number','neq', '');
        foreach (['contract_number'] as $key) {
            (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
        }
        if(isset($get['status'])){
            $db->where('status', isset($get['status']) ? $get['status'] : 0);
        }
        if (isset($get['frame_number']) && $get['frame_number'] !== '') {
            $db->where('frame_number', $get['frame_number']);
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
        $db->field("id,contract_number,contract_type,user_id,nickname,phone,brand_id,brand_name,car_model,car_color,guidance_price,frame_number,transaction_price,deposit_price,pay_money,saler_id,book_time");
        $result = parent::_list($db);
        if ($result['list']) {
            $saler_ids = array_column($result['list'], 'saler_id');
            $saler_ids = array_unique($saler_ids);
            $saler_ids = implode(',', $saler_ids);
            $systemUserModel = new SystemUserModel();
            $saler_names = $systemUserModel->getNameByIds($saler_ids);
            foreach ($result['list'] as $key => &$val) {
                $val['contacts_id'] = $val['id'];
                $val['saler_name'] = $saler_names[$val['saler_id']];
                $val['status'] = 0;
                $val['ps'] = '';
                $info = Db::name($this->table)->order("create_at desc")->where("contacts_id",$val['id'])->find();
                if($info){
                    $val['status'] = $info['status'];
                    $val['ps'] = $info['ps'];
                }

            }
        }
        $result['arrive_count'] = 4;
        $result['un_arrive_count'] = 4;
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 设置到店
     */
    public function set_arrive(){
        $get = $this->request->request();
        if(!isset($get['id'])||!isset($get['ps'])){
            $this->error_json(1003, '缺少参数');
        }
        $last = Db::name($this->table)->order("create_at desc")->where("contacts_id",$get['id'])->find();
        if($last){
            if($last['status']>0){
                $this->error_json(400, '已经提交过来，请勿重复提交');
            }
        }
        $savedata['contacts_id'] = $get['id'];
        $savedata['status'] = 1;
        $savedata['ps'] = $get['ps'];
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);

    }


}
