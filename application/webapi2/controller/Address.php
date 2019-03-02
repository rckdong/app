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

use app\webapi\controller\Baseapp;
use service\DataService;
use think\Db;

/**
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Address extends Baseapp
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'CityArea';

    /**
     * 获取子城市
     * @param pid
     */
    public function get_city_by_parent_id()
    {
        $get = $this->request->get();
        $pid = isset($get['pid']) ? $get['pid'] : 0;
        $res = Db::name($this->table)
            ->where(['parent_id'=>$pid])
            ->field("id,name,level,parent_id")
            ->order("id asc")
            ->cache(true)
            ->select();
        if($res){
            $this->success_return($res,'城市列表');
        }else{
            $arr = [
                'status' => -1,
                'result' => $res,
                'msg' => '城市列表'
            ];
            $this->json_return($arr);
        }
    }

}
