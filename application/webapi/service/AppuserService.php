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

namespace app\webapi\service;

use think\Db;

/**
 * 用户数据类
 * Class ProductService
 * @package app\goods\service
 */
class AppuserService
{
    /**
     * 分享操作添加普通抽奖次数
     * @param $user_id
     */
    public static function do_share($user_id){
        $user_info = self::get_user_data($user_id);
        if(!$user_info || $user_info['lottery_share'] == 1){
            return false;
        }
        //修改用户表信息
        $r = Db::name('AppUsers')
            ->where('id',$user_id)
            ->inc("lottery_count")
            ->exp('lottery_share',1)
            ->update();
        return $r;
    }

    /**
     * 用户报名增加普通抽奖次数
     * @param $user_id
     */
    public static function do_apply($user_id){
        $user_info = self::get_user_data($user_id);
        if(!$user_info || $user_info['lottery_apply'] == 1){
            return false;
        }
        //修改用户表信息
        $r = Db::name('AppUsers')
            ->where('id',$user_id)
            ->inc("lottery_count")
            ->exp('lottery_apply',1)
            ->update();
        return $r;
    }

    /**
     * 用户支付增加高级抽奖次数
     * @param $user_id
     */
    public static function do_pay_add_lottery_count($user_id){
        $user_info = self::get_user_data($user_id);
        if(!$user_info || $user_info['lottery_pay'] == 1){
            return false;
        }
        //修改用户表信息
        $r = Db::name('AppUsers')
            ->where('id',$user_id)
            ->inc("lottery_high_count")
            ->exp('lottery_pay',1)
            ->update();
        return $r;
    }

    /**
     * 用户关注获取抽奖次数
     * @param $user_id
     */
    public static function do_subscribe($user_id){
        $user_info = self::get_user_data($user_id);
        if(!$user_info || $user_info['lottery_subscribe'] == 1){
            return false;
        }
        //修改用户表信息
        $r = Db::name('AppUsers')
            ->where('id',$user_id)
            ->inc("lottery_count")
            ->exp('lottery_subscribe',1)
            ->update();
        return $r;
    }


    /**
     * 通过用户ID获取用户信息
     * @param $user_id
     * @param $field
     * @return array
     */
    public static function get_user_data($user_id, $field = '*')
    {
        if (!isset($user_id)) {
            return [];
        }
        $field = isset($field) ? $field : "*";
        $res = Db::name('AppUsers')->where(['id' => $user_id])->field($field)->find();
        return $res;
    }

    /**
     * 减少用户的抽奖次数
     */
    public static function user_lottery_count_dec($user_id,$type = 0){
        $user_info = self::get_user_data($user_id);
        if($type == 0 ){
            if ($user_info['lottery_count'] <= 0) {
                return false;
            }
            $res = Db::name('AppUsers')->where(['id' => $user_id])->setDec('lottery_count');
            return $res;
        }else{
            if ($user_info['lottery_high_count'] <= 0) {
                return false;
            }
            $res = Db::name('AppUsers')->where(['id' => $user_id])->setDec('lottery_high_count');
            return $res;
        }
    }
}