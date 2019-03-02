<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014-2018 东莞市云拓互联网络科技有限公司
// +----------------------------------------------------------------------
// | 官方网站:http://www.ytclouds.net
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------

namespace service;

use think\Db;
use think\db\Query;

/**
 * web推送服务
 * Class DataService
 * @package service
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/22 15:32
 */
class PushService
{

    /**
     * Web服务推送
     * @param string $to_uid
     * @param array $data
     * @return bool
     */
    public static function notify($to_uid = '',$data = ['status' => 200, 'msg' => '成功推送', 'data' => '新消息'])
    {
        self::push_weixin($to_uid,$data);
        $push_api_url = "http://chexinyuan.com:2121/";
        $post_data = array(
            "type" => "publish",
            "content" =>$data ,
            "to" => $to_uid,
        );
        $res = HttpService::post($push_api_url, $post_data);
        if($res == 'ok'){
            //添加微信推送
            return true;
        }
        return false;
    }

    public static function push_weixin($to_uid = '',$data = []){
        if(!$to_uid){
            return false;
        }
        $system_user_info = Db::name("SystemUser")->where("id",$to_uid)->find();
        if($system_user_info['openid'] == ''){
            return false;
        }
        if($data['data'] == '新消息'){
            return false;
        }
        $push_data = $data['data'];
        $push_data['openid'] = $system_user_info['openid'];
        \app\api\service\PushService::admin_push($push_data);
        return true;
    }

}
