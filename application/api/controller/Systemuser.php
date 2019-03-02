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
class Systemuser extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';

    public $system_id;
    public $system_info;

    public function __construct()
    {
        parent::__construct();
        //TODO 判断修改 10000
        $this->system_id = isset($this->system_user['id']) ? $this->system_user['id'] : 10000;
        if (!$this->system_id) {
            $this->error_json(1001, '登录超时，请重新登录', []);
        }
        $this->system_info = Db::name($this->table)->where("id", $this->system_id)->find();
        if (!$this->system_info) {
            $this->error_json(1001, '帐号不存在，请稍后重试', []);
        }
    }

    /**
     * 获取账户信息
     */
    public function get_info()
    {
        if ($this->system_info['status'] == 0) {
            $this->error_json(1001, '该帐号被禁用', []);
        }
        $return['id'] = $this->system_info['id'];
        $return['name'] = $this->system_info['name'];
        $return['username'] = $this->system_info['username'];
        $return['money'] = $this->system_info['money'];
        $return['qq'] = $this->system_info['qq'];
        $return['mail'] = $this->system_info['mail'];
        $return['phone'] = $this->system_info['phone'];
        $return['desc'] = $this->system_info['desc'];
        //TODO 补字段
        $return['job'] = '销售经理人';
        $return['leader'] = 'admin';

        $this->success_json('获取成功', $return);
    }

    /**
     * 申请提现
     */
    public function do_withdraw()
    {
        if ($this->system_info['status'] == 0) {
            $this->error_json(1001, '该帐号被禁用', []);
        }

        $get = $this->request->request();
        if (!isset($get['apply_money'])) {
            $this->error_json(1003, '缺失参数', []);
        }
        $apply_money = $get['apply_money'];
        if ($apply_money < 100) {
            $this->error_json(1004, '最少提现100元', []);
        }
        if ($apply_money > $this->system_info['money']) {
            $this->error_json(1004, '余额不足，申请失败', []);
        }
        $data['money'] = bcsub($this->system_info['money'], $apply_money, 2);
        $data['id'] = $this->system_info['id'];
        Db::startTrans();
        //修改余额
        $r = DataService::save($this->table, $data, 'id');
        //增加记录
        if($this->system_info['pid'] != 0){
            $insert_data['omoney'] = $apply_money;
            $insert_data['money'] = $apply_money * (1 - 0.0326) * 0.9;
            $insert_data['pmoney'] = $apply_money * 0.1;
            $insert_data['create_by'] = $this->system_info['id'];
        }else{
            $insert_data['omoney'] = $apply_money;
            $insert_data['money'] = $apply_money * (1 - 0.0326);
            $insert_data['pmoney'] = 0;
            $insert_data['create_by'] = $this->system_info['id'];
        }

        $insert_data['desc'] = "本次提现将收取3.26%手续费，10%上级获取金额";
        $r1 = Db::name("SystemWithdraw")->insert($insert_data);
        if (!$r || !$r1) {
            Db::rollback();
            $this->error_json(400, '申请失败，稍后重试', []);
        }
        Db::commit();
        $this->success_json("申请成功,等待审核", []);
    }

    /**
     * 修改密码
     */
    public function change_password()
    {
        if ($this->system_info['status'] == 0) {
            $this->error_json(1001, '该帐号被禁用', []);
        }
        $get = $this->request->request();
        if (!isset($get['password']) || !isset($get['new_password']) || !isset($get['cnew_password'])) {
            $this->error_json(1003, '参数不足', []);
        }
        $user = Db::name($this->table)->where('id', $this->system_info['id'])->find();
        empty($user) && $this->error_json(1001, '帐号不存在，请稍后重试', []);
        ($user['password'] !== md5($get['password'])) && $this->error_json(1001, '旧密码验证错误', []);
        ($get['new_password'] !== $get['cnew_password']) && $this->error_json(1004, '重复密码与新密码不一致', []);
        $savedata['id'] = $this->system_info['id'];
        $savedata['password'] = md5($get['new_password']);
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '修改失败，稍后重试', []);
        }
        $this->success_json("修改成功,等待审核", []);
    }

    /**
     * 上传印章
     */
    public function set_seal()
    {
        $get = $this->request->request();
        if (!isset($get['img_url'])) {
            $this->error_json(1003, '参数不足', []);
        }
        sysconf('system_seal', $get['img_url']);
        $this->success_json("保存成功", []);
    }

    /**
     * 获取印章
     */
    public function get_seal()
    {
        $url = sysconf('system_seal');
        $full_url['url'] = $url;
        $full_url['full_url'] = $this->get_full_url($url);
        $this->success_json("获取印章路径", $full_url);
    }

    /**
     * 提现记录
     */
    public function withdraw_log()
    {
        if ($this->system_info['status'] == 0) {
            $this->error_json(1001, '该帐号被禁用', []);
        }
        $list = Db::name("SystemWithdraw")->where("create_by", $this->system_info['id'])->order("id desc")->select();
        $this->success_json("提现记录", $list);
    }


}
