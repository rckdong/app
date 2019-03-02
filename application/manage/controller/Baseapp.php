<?php

// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014-2018 东莞市云拓互联网络科技有限公司
// +----------------------------------------------------------------------
// | 官方网站:http://www.ytclouds.net
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace app\manage\controller;

use service\DataService;
use think\Controller;
use think\Db;
use think\db\Query;
use service\WechatService;

/**
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Baseapp extends Controller
{
    protected $success_code = 200;
    protected $error_code = 400;
    protected $user_id;
    public $share;

    public function __construct()
    {
        parent::__construct();
        //获取微信公众号SDK
        if (!session('wx_user.openid')) {
            $fans = WechatService::webOauth(1);
            $this->user_login_save($fans);
        }
        $user_info = Db::name("SystemUser")
        ->where(['openid'=>session('wx_user.openid')])
        ->find();
        if(!$user_info){
            $this->redirect('@manage/error/index');
            exit;
        }
        $this->user_id = $user_info['id'];

        $wx_options = WechatService::webJsSDK();
        $this->assign('wx_options', $wx_options);
        //分享的参数
        $this->share['title'] = sysconf("wap_title");
        $this->share['desc'] = sysconf("wap_keywords");
        $this->share['img_url'] = 'http://chexinyuan.com/static/upload/3f75168918ae1342/50caea27245f7637.jpg';
        $this->assign('share', $this->share);
    }


    /**
     * 用户登录操作
     * @param $fans
     */
    private function user_login_save($fans)
    {
        if (!isset($fans['openid'])) {
            $this->error("请授权登录");
        }
        $user_info = Db::name("SystemUser")
            ->where(['openid' => $fans['openid']])
            ->find();

        //TODO 查看用户是否关注了
        if (!$user_info) {
            //录入资料
            $this->redirect('@manage/error/index');
            exit;
        } else {
            $this->user_id = $user_info['id'];
            session('wx_user', $user_info);
        }
    }

    protected function success_return($res, $msg)
    {
        $arr = [
            'status' => $this->success_code,
            'result' => $res,
            'msg' => $msg
        ];
        $this->json_return($arr);
    }


    /**
     * 成功返回
     * @param string $msg
     * @param string $data
     */
    protected function success_json($msg = '', $data = '')
    {
        $this->json_return(200, $msg, $data);
    }

    /**
     * 失败返回
     * @param int $code
     * @param string $msg
     * @param string $data
     */
    protected function error_json($code = 400, $msg = '', $data = '')
    {
        $this->json_return($code, $msg, $data);
    }

    protected function json_return($code = 200, $msg = '', $data = '', $token = '')
    {
        header("Content-type:application/json;charset=utf-8");
        $app_token = $this->request->request("app_token") ? $this->request->request("app_token") : '';
        exit(json_encode(['status' => $code, 'message' => $msg, 'data' => $data, 'app_token' => $app_token]));
    }

    /**
     * 当前对象回调成员方法
     * @param string $method
     * @param array|bool $data1
     * @param array|bool $data2
     * @return bool
     */
    protected function _callback($method, &$data1, $data2)
    {
        foreach ([$method, "_" . $this->request->action() . "{$method}"] as $_method) {
            if (method_exists($this, $_method) && false === $this->$_method($data1, $data2)) {
                return false;
            }
        }
        return true;
    }
}
