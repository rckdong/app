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
class City extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'City';


    public function get_city_list()
    {
        $get = $this->request->request();
        $parent_id = isset($get['parent_id']) ? $get['parent_id'] : 1;
        $list = Db::name($this->table)->where(['parent_id' => $parent_id])->select();
        if(!$list){
            $this->error_json(400,'获取失败',[]);
        }
        $this->success_json('地理位置',$list);
    }

}
