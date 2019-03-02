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
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Car extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'City';


    /**
     * 获取品牌表
     */
    public function get_brands()
    {
        $list = Db::name("Brand")->where(['is_deleted' => 0])->select();
        if(!$list){
            $this->error_json(400,'获取失败',[]);
        }
        $this->success_json('获取品牌',$list);
    }

    /**
 * 获取精品表`products`
 */
    public function get_products()
    {
        $list = Db::name("Products")->where(['is_deleted' => 0])->select();
        if(!$list){
            $this->error_json(400,'获取失败',[]);
        }
        $this->success_json('获取精品',$list);
    }

    /**
     * 获取保险列表`get_insurances`
     */
    public function get_insurances()
    {
        $list = Db::name("Insurance")->where(['is_deleted' => 0])->select();
        if(!$list){
            $this->error_json(400,'获取失败',[]);
        }
        $this->success_json('获取保险列表',$list);
    }

}
