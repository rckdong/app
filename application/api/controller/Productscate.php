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

use app\api\model\ProductsCateModel;
use app\api\model\ProductsModel;
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
class Productscate extends BasicApi
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'ProductsCate';

    /**
     * 精品列表
     */
    public function index()
    {
        $db = Db::name($this->table)->order('id desc');
        $db->where("is_deleted",0);
        $result = parent::_list($db);
        $this->success_json('获取数据成功', $result);
    }

    /**
     * 获取所有精品类别
     */
    public function get_all()
    {
        $r = Db::name($this->table)->where("is_deleted", 0)->field("id,name")->select();
        $this->success_json('获取数据成功', $r);
    }

    /**
     * 添加精品类别
     */
    public function add()
    {
        $get = $this->request->request();
        if (!isset($get['name'])) {
            $this->error_json(1003, '缺少参数');
        }
        $productCateModel = new ProductsCateModel();
        $is_has = $productCateModel->getCountByName($get['name']);
        if ($is_has > 0) {
            $this->error_json(1004, '该类目名称已经存在');
        }
        $savedata['name'] = $get['name'];
        $r = DataService::save('ProductsCate', $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }

    public function edit()
    {
        $get = $this->request->request();
        if (!isset($get['id']) || !isset($get['name'])) {
            $this->error_json(1003, '缺少参数');
        }
        $savedata['name'] = $get['name'];
        $savedata['id'] = $get['id'];
        $r = DataService::save('ProductsCate', $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }


    /**
     * 删除精品分类
     */
    public function del()
    {
        $get = $this->request->request();
        if (!isset($get['ids'])) {
            $this->error_json(1003, '缺少参数');
        }
        if (is_string($get['ids'])) {
            $get['ids'] = explode(',', $get['ids']);
        }

        //TODO 添加判断
        $count = Db::name("Products")->whereIn("cate_id", $get['ids'])->where("is_deleted", 0)->count();
        if ($count > 0) {
            $this->error_json(400, '该类别下面还有精品，不允许删除', []);
        }
        $savedata['is_deleted'] = 1;
        $r = Db::name($this->table)->whereIn("id", $get['ids'])->data($savedata)->update();
        if (!$r) {
            $this->error_json(400, '删除失败，请稍后重试', []);
        }
        $this->success_json('删除成功', []);
    }

}
