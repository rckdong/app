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

namespace app\webapi\controller;

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
        }else{
            if (!session('wx_user.id')){
                $user_info = Db::name("AppUsers")
                    ->where(['openid'=>session('wx_user.openid')])
                    ->find();
                $this->user_id = $user_info['id'];
            }else{
                $this->user_id = session('wx_user.id');
            }
        }
        $action = $this->request->action();
        $user_info = Db::name("AppUsers")->where("id",session('wx_user.id'))->field("phone")->find();
        if($user_info['phone'] == '' && !in_array($action,['user_form','do_user_form'])){
            $this->redirect('@webapi/user/user_form');
            exit;
        }
        $wx_options = WechatService::webJsSDK();
        $this->assign('wx_options', $wx_options);
        //分享的参数
        $this->share['title'] = sysconf("wap_title");
        $this->share['desc'] = sysconf("wap_keywords");
        $this->share['img_url'] = 'http://chexinyuan.com/static/upload/3f75168918ae1342/50caea27245f7637.jpg';
        $this->assign('share', $this->share);
    }


//    protected $share;
//
//    /**
//     * 构造方法
//     * @access public
//     */
//    public function __construct()
//    {
//        parent::__construct();
//        //获取微信登录
//        $this->user_id = '1';
//        if (!session('wx_user.openid')) {
//            $fans = WechatService::webOauth(1);
//            $this->user_login_save($fans);
//        }else{
//            if (!session('wx_user.id')){
//                $user_info = Db::name("AppUsers")
//                    ->where(['openid'=>session('wx_user.openid')])
//                    ->find();
//                $this->user_id = $user_info['id'];
//            }else{
//                $this->user_id = session('wx_user.id');
//            }
//        }
//        //获取微信公众号SDK
//        $wx_options = WechatService::webJsSDK();
//        $this->assign('wx_options', $wx_options);
//        //分享的参数
//        $this->share['title'] = '燎焰传媒';
//        $this->share['desc'] = '2018亚洲新人赛上海赛区报名';
//        $this->share['img_url'] = 'http://lottery.liaoyan.com.cn/logo.jpg';
//        $this->assign('share', $this->share);
//    }

    /**
     * 用户登录操作
     * @param $fans
     */
    private function user_login_save($fans)
    {
        if (!isset($fans['openid'])) {
            $this->error("请授权登录");
        }
        $user_info = Db::name("AppUsers")
            ->where(['openid' => $fans['openid']])
            ->find();

        //TODO 查看用户是否关注了
        if (!$user_info) {
            //录入资料
            $insert_data['openid'] = $fans['openid'];
            $insert_data['nickname'] = isset($fans['fansinfo']['nickname']) ? $fans['fansinfo']['nickname'] : '';
            $insert_data['sex'] = isset($fans['fansinfo']['sex']) ? $fans['fansinfo']['sex'] : 1;
            $insert_data['country'] = isset($fans['fansinfo']['country']) ? $fans['fansinfo']['country'] : '';
            $insert_data['province'] = isset($fans['fansinfo']['province']) ? $fans['fansinfo']['province'] : '';
            $insert_data['city'] = isset($fans['fansinfo']['city']) ? $fans['fansinfo']['city'] : '';
            $insert_data['city'] = isset($fans['fansinfo']['city']) ? $fans['fansinfo']['city'] : '';
            $insert_data['language'] = isset($fans['fansinfo']['language']) ? $fans['fansinfo']['language'] : '';
            $insert_data['headimgurl'] = isset($fans['fansinfo']['headimgurl']) ? $fans['fansinfo']['headimgurl'] : '';
            $this->user_id = Db::name("AppUsers")->getLastInsID($insert_data);
            $insert_data['id'] = $this->user_id;
            session('wx_user', $insert_data);
        } else {
            $this->user_id = $user_info['id'];
            session('wx_user', $user_info);
        }
    }

    //json返回
    protected function json_return($arr)
    {
        exit(json_encode($arr));
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
