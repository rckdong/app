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
class Products extends BasicApi
{

    //TODO 派单功能实现
    //表设计，products_check
    //products_check 生成时间？
    //财务确认付款后生成 products_check
    //id,product_id,contact_id,ps,status
    //流程：系统派单，派单人员操作，选择完成，流程结束

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'Products';

    /**
     * 精品列表
     */
    public function index()
    {
        $db = Db::name($this->table)->order('id desc');
        $result = parent::_list($db);
        $this->success_json('获取数据成功', $result);
    }

    public function _index_data_filter(&$list)
    {
        $ids = array_column($list, 'cate_id');
        $ids = array_unique($ids);
        if ($ids) {
            //获取分类名称
            $ids = implode(',', $ids);
            $productCateModel = new ProductsCateModel();
            $cates = $productCateModel->getByIds($ids);
            foreach ($list as $key => $val) {
                $list[$key]['cate_name'] = isset($cates[$val['cate_id']]) ? $cates[$val['cate_id']] : '';
            }
        }
    }


    /**
     * 添加精品
     */
    public function add()
    {
        $get = $this->request->request();
        $verification = ['cate_id', 'name', 'content', 'ps', 'market_price', 'price','cost'];
        $savedata = [];
        foreach ($verification as $key => $val) {
            if (!isset($get[$val])) {
                $this->error_json(1003, '缺少参数_' . $val);
            }
            $savedata[$val] = $get[$val];
        }
        if (!isset($savedata['name'])) {
            $this->error_json(1003, '缺少参数');
        }
        $is_has = Db::name($this->table)->where("name", $savedata['name'])->where("is_deleted", 0)->count();
        if ($is_has > 0) {
            $this->error_json(1004, '该名称已经存在');
        }
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }


    /**
     * 修改精品
     */
    public function edit()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '缺少参数');
        }
        $info = Db::name($this->table)->where("id", $get['id'])->find();
        if (!$info) {
            $this->error_json(400, "精品不存在");
        }
        $savedata = [];
//        if ($info['is_used'] == 0) {
            //没使用可以修改的选项
            //TODO 合同中增加个精品使用中功能
            $edit_key = ['name', 'content', 'ps', 'market_price', 'price','cost'];
            foreach ($edit_key as $key => $val) {
                if (isset($get[$val]) && $get[$val] != '') {
                    $savedata[$val] = $get[$val];
                }
            }
//        }
        //是否冻结
        if (isset($get['is_deleted']) && ($get['is_deleted'] == 1 || $get['is_deleted'] == 0)) {
            $savedata['is_deleted'] = $get['is_deleted'];
        }
        //修改类别
        if (isset($get['cate_id'])) {
            $savedata['cate_id'] = $get['cate_id'];
        }
        if (!$savedata) {
            $this->error_json(400, "精品已经使用中，只能修改冻结状态和类别");
        }
        $savedata['id'] = $get['id'];

        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }


    /**
     * 冻结精品
     */
    public function forzen()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '缺少参数');
        }
        $savedata['id'] = $get['id'];
        $savedata['is_deleted'] = 1;
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }

    /**
     * 恢复精品
     */
    public function restart()
    {
        $get = $this->request->request();
        if (!isset($get['id'])) {
            $this->error_json(1003, '缺少参数');
        }
        $savedata['id'] = $get['id'];
        $savedata['is_deleted'] = 0;
        $r = DataService::save($this->table, $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json('提交成功', []);
    }

}
