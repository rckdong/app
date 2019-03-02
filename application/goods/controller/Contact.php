<?php

// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace app\goods\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;

/**
 * 电话咨询
 * Class Brand
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Contact extends BasicAdmin
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'GoodsContacts';

    /**
     * 电话咨询列表
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->title = '咨询列表';
        $get = $this->request->get();
        $db = Db::name($this->table);
        if (isset($get['phone_num']) && $get['phone_num'] !== '') {
            $db->where("phone_num",$get['phone_num']);
        }
        if(isset($get['is_deleted']) && $get['is_deleted'] ==2){
            $db->where(['is_deleted' => '0']);
        }
        if(isset($get['is_deleted']) && $get['is_deleted'] ==1){
            $db->where(['is_deleted' => '1']);
        }
        if (isset($get['name']) && $get['name'] !== '') {
            $db->whereLike('name', "%{$get['name']}%");
        }
        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $db->whereBetween('create_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        return parent::_list($db->order('id desc'));
    }

    /**
     * 商城数据处理
     * @param array $data
     */
    protected function _data_filter(&$data)
    {
        foreach ($data as $key =>$val){
            $data[$key]['goods_title'] = "";
            $data[$key]['goods_prices'] = 0;
            if($val['goods_id']){
                $info = Db::name("Goods")
                    ->where("id",$val['goods_id'])
                    ->field("goods_title,goods_prices,is_deleted")
                    ->find();
                if($info){
                    if($info['is_deleted'] == 1){
                        $data[$key]['goods_title'] = $info['goods_title']."[已删除]";
                    }else{
                        $data[$key]['goods_title'] = $info['goods_title'];
                    }
                    $data[$key]['goods_prices'] = $info['goods_prices'];
                }
            }
        }
    }

    /**
     * 编辑品牌
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function edit()
    {
        $this->title = '编辑品牌';
        return $this->_form($this->table, 'form');
    }

    /**
     * 商城数据处理
     * @param array $data
     */
    protected function _form_filter(&$data)
    {
        if (!$this->request->isPost()) {
            $data['goods_title'] = "";
            $data['goods_prices'] = "";
            if($data['goods_id']){
                $info = Db::name("Goods")
                    ->where("id",$data['goods_id'])
                    ->field("goods_title,goods_prices,is_deleted")
                    ->find();
                if($info){
                    if($info['is_deleted'] == 1){
                        $data['goods_title'] = $info['goods_title']."[已删除]";
                    }else{
                        $data['goods_title'] = $info['goods_title'];
                    }
                    $data['goods_prices'] = $info['goods_prices'];
                }
            }
        }
    }


    /**
     * 删除品牌
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            $this->success("删除成功！", '');
        }
        $this->error("删除失败，请稍候再试！");
    }



}
