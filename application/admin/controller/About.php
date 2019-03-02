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
namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;

/**
 * 后台参数配置控制器
 * Class Config
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 18:05
 */
class About extends BasicAdmin
{

    /**
     * 当前默认数据模型
     * @var string
     */
    public $table = 'CompanyInfo';

    /**
     * 当前页面标题
     * @var string
     */
    public $title = '公司设置';

    /**
     * 显示系统常规配置
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $content = Db::name("CompanyInfo")->find();
        if ($this->request->isGet()) {
            return $this->fetch('', ['title' => $this->title, 'content' => $content]);
        }
        if ($this->request->isPost()) {
            foreach ($this->request->post() as $key => $vo) {
                sysconf($key, $vo);
            }
            $savedata['id'] = 1;
            $savedata['content'] = $this->request->post("content");
            DataService::save("CompanyInfo", $savedata, 'id');
            $this->success('公司设置成功！', '');
        }
    }

}
