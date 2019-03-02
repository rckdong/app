<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\ToolsService;
use think\Db;

/**
 * 岗位管理
 * Class Cate
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Ganwei extends BasicAdmin
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'Ganwei';

    /**
     * 岗位列表
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->title = '岗位管理';
        $db = Db::name($this->table)->where(['is_deleted' => '0']);
        return parent::_list($db->order('sort asc,id asc'), false);
    }

    public function _data_filter(&$data)
    {
        foreach ($data as $key => $val) {
            $bumen = Db::name("Bumen")->where("id", $val['bumen_id'])->field("id,name")->find();
            $data[$key]['bumen'] = isset($bumen['name']) ? $bumen['name'] : '无';
            if ($val['pid'] != 0) {
                $users = Db::name("SystemUser")->where("id", $val['pid'])->field("id,name")->find();
                $data[$key]['pid_name'] = isset($users['name']) ? $users['name'] : '';
            } else {
                $data[$key]['pid_name'] = '无';
            }
        }
    }


    /**
     * 添加菜单
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $users = Db::name("SystemUser")->where("status", 1)->where("is_deleted", 0)->select();
        $this->assign("users", $users);
        $bumen = Db::name("Bumen")->where("status", 1)->where("is_deleted", 0)->select();
        $this->assign("bumens", $bumen);
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑菜单
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $users = Db::name("SystemUser")->where("status", 1)->select();
        $this->assign("users", $users);
        $bumen = Db::name("Bumen")->where("status", 1)->where("is_deleted", 0)->select();
        $this->assign("bumens", $bumen);
        return $this->_form($this->table, 'form');
    }


    /**
     * 删除岗位
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            $this->success("岗位删除成功！", '');
        }
        $this->error("岗位删除失败，请稍候再试！");
    }

    /**
     * 岗位禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbid()
    {
        if (DataService::update($this->table)) {
            $this->success("岗位禁用成功！", '');
        }
        $this->error("岗位禁用失败，请稍候再试！");
    }

    /**
     * 岗位禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function resume()
    {
        if (DataService::update($this->table)) {
            $this->success("岗位启用成功！", '');
        }
        $this->error("岗位启用失败，请稍候再试！");
    }

}
