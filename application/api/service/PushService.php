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

namespace app\api\service;

use app\api\model\ContactsModel;
use service\DataService;
use service\ToolsService;
use service\WechatService;
use think\Db;

/**
 * 微信粉丝数据服务
 * Class FansService
 * @package app\wechat
 */
class PushService
{
    public static $site_url = "http://chexinyuan.com/";

    /**
     * 用户确认
     */
    public static function confirm($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }
        if ($data['contract_type'] == 1) {
            $first = "定车合同，请确认信息无误";
        } else {
            $first = "订车合同，请确认信息无误";
        }
        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => 'guQna97I1i6a0_TnPJ_gxZ3t4-KArpat9KNHQoyVDc8',
            'url' => self::$site_url . 'index.php/webapi/contract/push_confirm.html?contract_id=' . $data['id'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $first,
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['contract_number'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['brand_name'] . ' ' . $data['car_color'] . '色_' . $data['car_model'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }

    /**
     * 支付通知
     * @param $data
     * @return bool
     */
    public static function pay_success($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }

        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => 'vxwR1r0gC_Q0KAulArfAgRYHqnG2Encu6IE4G2lyHb8',
            'url' => self::$site_url . 'index.php/webapi/user/user_center.html',
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => '亲，您的购车款支付成功',
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['money'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['brand_name'] . ' ' . $data['car_color'] . '色_' . $data['car_model'],
                    'color' => '#173177'
                ],
                'keyword3' => [
                    'value' => '深圳市龙岗区中心城宝南路9号 - 车厘子汽车交付中心',
                    'color' => '#173177'
                ],
                'keyword4' => [
                    'value' => '0755-28363331',
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }

    //库管消息推送
    public static function repertory_send($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }

        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => '3VR-Azwh9DeGD7pr4aQFJYaGL8XeBkpoO0wUmi3yPk4',
            'url' => self::$site_url . 'index.php/webapi/contract/car_info.html?contract_id=' . $data['contacts_id'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $data['first'],
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['contract_number'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['keyword2'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }

    public static function insurance_push($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }

        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => '3VR-Azwh9DeGD7pr4aQFJYaGL8XeBkpoO0wUmi3yPk4',
            'url' => self::$site_url . 'index.php/webapi/contract/baoxian.html?contract_id=' . $data['contacts_id'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $data['first'],
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['contract_number'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['keyword2'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }


    public static function product_check_push($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }

        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => '3VR-Azwh9DeGD7pr4aQFJYaGL8XeBkpoO0wUmi3yPk4',
            'url' => self::$site_url . 'index.php/webapi/contract/jingpin.html?contract_id=' . $data['contacts_id'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $data['first'],
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['contract_number'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['keyword2'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }

    //贷款通知
    public static function mortgage_send($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }
        $first_desc = ($data['status'] == 1) ? "亲，您的按揭审核通过" : "亲，您的按揭审核不通过";
        $remark = ($data['status'] == 1) ? "按揭金额为：" . $data['money'] . "元" : $data['false_reason'];
        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => 'AwWT3hDYagEAGjos3eLbzS-VhzwSUk6yStX_snZ_yr4',
            'url' => self::$site_url . 'index.php/webapi/user/user_center.html',
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $first_desc,
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['nickname'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['phone'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => $remark . ',更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }

//    交车推送确认
    public static function jiaoche_push($data)
    {
        $user_info = Db::name("AppUsers")
            ->where("id", $data['user_id'])
            ->field("openid,nickname,phone")
            ->find();
        if (!$user_info) {
            return false;
        }
        $first = "申请交车确认";
        $push_data = [
            'touser' => $user_info['openid'],
            'template_id' => 'guQna97I1i6a0_TnPJ_gxZ3t4-KArpat9KNHQoyVDc8',
            'url' => self::$site_url . 'index.php/webapi/contract/jiaoche.html?contract_id=' . $data['id'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $first,
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['contract_number'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['brand_name'] . ' ' . $data['car_color'] . '色_' . $data['car_model'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }


    //库管消息推送
    public static function admin_push($data)
    {
        $push_data = [
            'touser' => $data['openid'],
            'template_id' => '3VR-Azwh9DeGD7pr4aQFJYaGL8XeBkpoO0wUmi3yPk4',
            'url' => $data['url'],
            'topcolor' => '#FF0000',
            'data' => [
                'first' => [
                    'value' => $data['first'],
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $data['keyword1'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $data['keyword2'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => '更多信息请查询公众号的购车合同或拨打：0755-28363331',
                    'color' => '#173177'
                ],
            ]
        ];
        return self::wxPush($push_data);
    }


    //微信推送
    public static function wxPush(array $data)
    {
        $template = WechatService::template();
        $r = $template->send($data);
        if ($r['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }
}