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
use service\HttpService;
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
class User extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'AppUsers';


    /**
     * 通过手机号获取用户信息
     */
    public function get_user_by_phone()
    {
        $get = $this->request->request();
        $phone = isset($get['phone']) ? $get['phone'] : '';
        if (!$phone) {
            $this->error_json(1003, '参数缺少', []);
        }
        $userModel = new UserModel();
        $list = $userModel->getOne(['phone' => $phone]);
        if (!$list) {
            $this->error_json(400, '所输入的手机号码不存在', []);
        }
        $this->success_json('个人信息', $list);
    }

}
