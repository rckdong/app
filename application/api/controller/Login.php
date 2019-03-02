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
class Login extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';


    public function login()
    {
        // 输入数据效验
        $username = $this->request->request('username', '', 'trim');
        $password = $this->request->request('password', '', 'trim');
        strlen($username) < 4 && $this->error_json(400, '登录账号长度不能少于4位有效字符', []);
        strlen($password) < 4 && $this->error_json(400, '登录密码长度不能少于4位有效字符', []);
        // 用户信息验证
        $user = Db::name($this->table)->where('username', $username)->find();
        empty($user) && $this->error_json(400, '登录账号不存在，请重新输入!', []);
        ($user['password'] !== md5($password)) && $this->error_json(400, '登录密码与账号不匹配，请重新输入!', []);
        empty($user['status']) && $this->error_json(400, '账号已经被禁用，请联系管理!', []);
        // 更新登录信息
        $new_app_token = md5('chexinyuan' . time() . $user['id']);
        $data = ['app_token' => $new_app_token,'id'=>$user['id']];
        Db::name($this->table)->where(['id' => $user['id']])->update($data);
//        $this->success_json('登录成功', ['app_token' => $new_app_token]);
        header("Content-type:application/json;charset=utf-8");
        exit(json_encode(['status' => 200, 'message' => '登录成功', 'data' => $data]));
    }

    public function login_out()
    {
        $get = $this->request->request();
        if (!isset($get['app_token'])) {
            $this->error_json(1003, '参数不足!', []);
        }
        $app_token = $get['app_token'];

        $user = Db::name($this->table)->where('app_token', $app_token)->find();
        empty($user) && $this->error_json(400, '查询不到用户!', []);

        $data['app_token'] = '';
        Db::name($this->table)->where(['app_token' => $app_token])->update($data);
        $this->success_json('登出成功', []);
    }

    public function get_auth()
    {
        $get = $this->request->request();
        if (!isset($get['app_token'])) {
            $this->error_json(1003, '参数不足!', []);
        }
        $app_token = $get['app_token'];
        $user = Db::name($this->table)->where('app_token', $app_token)->find();
        empty($user) && $this->error_json(400, 'token失效，查询不到用户!', []);
        $data = [
            [
                'icon' => 'el-icon-setting',
                'index' => 'dashboard',
                'title' => '系统首页',
            ]
            ,
            [
                'icon' => 'el-icon-date',
                'index' => 'market',
                'title' => '销售经理人',
                'subs' => [
                    [
                        'index' => 'enteringContract',
                        'title' => '录合同'
                    ],
                    [
                        'index' => 'marketContractList',
                        'title' => '已完成合同'
                    ],
                    [
                        'index' => 'marketContractUnfinished',
                        'title' => '未完成合同'
                    ],
                    [
                        'index' => 'MyRewardList',
                        'title' => '账户'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-date',
                'index' => 'handler',
                'title' => '销售经理人经理',
                'subs' => [
                    [
                        'index' => 'teamData',
                        'title' => '团队数据'
                    ],
                    [
                        'index' => 'monthData',
                        'title' => '本月团队已完成'
                    ],
                    [
                        'index' => 'teamAccount',
                        'title' => '团队账户'
                    ],
                    [
                        'index' => 'teamApproveList',
                        'title' => '审批列表'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'clerk',
                'title' => '销售文员',
                'subs' => [
                    [
                        'index' => 'clerkMain',
                        'title' => '文员主页'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'mortgage',
                'title' => '按揭专员',
                'subs' => [
                    [
                        'index' => 'mortgageList',
                        'title' => '按揭主页'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'finance',
                'title' => '财务管理',
                'subs' => [
                    [
                        'index' => 'financeMain',
                        'title' => '财务主页'
                    ],
                    [
                        'index' => 'businessIncome',
                        'title' => '营业收入'
                    ],
                    [
                        'index' => 'businessExpenditure',
                        'title' => '营业支出'
                    ]
                    ,
                    [
                        'index' => 'withdrawMain',
                        'title' => '提现列表'
                    ]
                    ,
                    [
                        'index' => 'marketAccounts',
                        'title' => '销售结算'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'administrative',
                'title' => '行政管理',
                'subs' => [
                    [
                        'index' => 'administrativeIncome',
                        'title' => '行政收入'
                    ],
                    [
                        'index' => 'administrativeExpend',
                        'title' => '行政支出'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'boutique',
                'title' => '精品管理',
                'subs' => [
                    [
                        'index' => 'boutiqueMain',
                        'title' => '精品主页'
                    ],
                    [
                        'index' => 'boutiqueType',
                        'title' => '精品类型'
                    ],
                    [
                        'index' => 'boutiqueList',
                        'title' => '精品列表'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'repertory',
                'title' => '库管管理',
                'subs' => [
                    [
                        'index' => 'repertoryIndex',
                        'title' => '库管主页'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'manager',
                'title' => '总经理',
                'subs' => [
                    [
                        'index' => 'managerIndex',
                        'title' => '主页'
                    ],
                    [
                        'index' => 'managerApproveList',
                        'title' => '审核'
                    ],
                    [
                        'index' => 'managerAccount',
                        'title' => '团队账户'
                    ]
                ]
            ]
            ,
            [
                'icon' => 'el-icon-document',
                'index' => 'investor',
                'title' => '投资人',
                'subs' => [
                    [
                        'index' => 'investorIndex',
                        'title' => '主页'
                    ],
                    [
                        'index' => 'investorAccount',
                        'title' => '团队账户'
                    ]
                ]
            ]
//            ,
//            [
//                'icon' => 'el-icon-warning',
//                'index' => 'permission',
//                'title' => '权限测试'
//            ]
//            ,
//            [
//                'icon' => 'el-icon-error',
//                'index' => '404',
//                'title' => '404页面'
//            ]
        ];

        $this->success_json('登录成功', ['auth' => $data]);
    }

}
