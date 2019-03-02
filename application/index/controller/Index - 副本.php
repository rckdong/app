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

namespace app\wap\controller;


use controller\BasicApi;
use think\Db;

/**
 * 入口
 * Class Index
 * @package app\admin\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/02/15 10:41
 */
class Index extends BasicApi
{
    public $company_phone;
    public $table = "Goods";

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * 首页
     */
    public function index()
    {
        $db = Db::name($this->table)->where(['is_deleted' => '0']);
        $res = $db->field("id,goods_title,goods_prices,goods_logo,guid_price")->order("sort")->limit(10)->select();
        return $this->fetch('', ['list' => $res]);
    }

    /**
     * 关于我们
     */
    public function about()
    {
        $vo = Db::name("CompanyInfo")->where(['id' => '1'])->find();
        return $this->fetch("", ["vo" => $vo]);
    }

    public function get_list()
    {
        $get = $this->request->request();


        $cate_id = isset($get['cate_id']) ? $get['cate_id'] : 0;

        if (isset($get['page']) && $get['page'] != 0) {
            $res['nextPage'] = $get['page'] + 1;
            $db = Db::name($this->table);
            $db->field("id,goods_title,goods_prices,goods_logo,guid_price")->order("sort");
            if ($cate_id != 0) {
                $cates = Db::name("GoodsCate")->where(['is_deleted' => '0', 'pid' => $cate_id, 'status' => 1])->column("id");
                array_unshift($cates, $cate_id);
                $db->whereIn("cate_id", $cates);
            }
            $list = $db->paginate(10);
            if ($list->lastPage() < $res['nextPage']) {
                $res['nextPage'] = 0;
            }
            $str = "";
            foreach ($list as $key => $val) {
//                <li>
//                    <a href="/index.php/wap/index/info?id={$vo.id}">
//                        <div class="title2" style="overflow: hidden">
//                            <span class="title_name" >{$vo.goods_title}</span>
//                            <span class="price" style="text-align: right;width: 20%;float: right">{$vo.goods_prices} 万起</span>
//                        </div>
//                        <div class="title2 guide"><em style="float: right;color: #a5a5a5;text-decoration:line-through">厂家指导价: {$vo.guid_price} 万</em></div>
//                    </a>
//                </li>
                $str .= '<li>';
                $str .= '<a href="' . url("wap/index/info") . '?id=' . $val["id"] . '">';
                $str .= '<div class="title2" style="overflow: hidden"><span class="title_name" >' . $val["goods_title"] . '</span><span class="price" style="text-align: right;width: 20%;float: right">' . $val["goods_prices"] . ' 万起</span></div>';
                $str .= '<div class="title2 guide" ><em style="float: right;color: #a5a5a5;text-decoration:line-through">厂家指导价:'.$val["guid_price"].' 万</em></div>';
                $str .= '</a></li>';
            }
            $res['str'] = $str;
            exit(json_encode($res));
        }

        $cate_list2 = [];
        $active_cate1 = 0;
        $active_cate2 = 0;
        $db = Db::name($this->table)->where(['is_deleted' => '0']);
        if ($cate_id != 0) {
            $cate_info = Db::name("GoodsCate")->where([ 'id' => $cate_id])->find();
            $cates = [];
            if($cate_info['pid'] == 0){
                $active_cate1 = $cate_id;
                $cates_child = Db::name("GoodsCate")->where(['is_deleted' => '0', 'pid' => $cate_id, 'status' => 1])->select();
                $cates = array_column($cates_child,'id');

                $cate_list2 = Db::name("GoodsCate")
                    ->where(['is_deleted' => '0', 'pid' => $cate_id, 'status' => 1])
                    ->order("sort")
                    ->field("id,cate_title,sort")
                    ->select();
            }else{
                $active_cate1 = $cate_info['pid'];
                $active_cate2 = $cate_id;
                $cate_list2 = Db::name("GoodsCate")
                    ->where(['is_deleted' => '0', 'pid' => $active_cate1, 'status' => 1])
                    ->order("sort")
                    ->field("id,cate_title,sort")
                    ->select();
            }
            array_unshift($cates, $cate_id);
            $db->whereIn("cate_id", $cates);
        }
        $res = $db->field("id,goods_title,goods_prices,goods_logo,guid_price")->order("sort")->limit(10)->select();

        $cate_list = Db::name("GoodsCate")
            ->where(['is_deleted' => '0', 'pid' => 0, 'status' => 1])
            ->order("sort")
            ->field("id,cate_title,sort")
            ->select();
        return $this->fetch('', ['cate_list' => $cate_list, 'list' => $res, 'cate_list2' => $cate_list2,'active_cate1'=>$active_cate1,'active_cate2'=>$active_cate2]);
    }

//    获得联系方式
    public function get_phones()
    {
        $company_phone = sysconf("company_phone");
        $company_phone = explode('|', $company_phone);
        foreach ($company_phone as $val) {
            $a = ['title' => '联系电话', 'phone' => $val];
            $this->company_phone[] = $a;
        }
        exit(json_encode($this->company_phone));
    }

//    图片展示
    public function show_pic()
    {
        $get = $this->request->get();
        $id = $get['id'];
        $info = DB::name($this->table)->where("id", $id)->find();
        $info['goods_image'] = explode('|', $info['goods_image']);
        array_unshift($info['goods_image'], $info['goods_logo']);
        return $this->fetch('', ['vo' => $info]);
    }

//    详情页
    public function info()
    {
        $get = $this->request->get();
        $id = $get['id'];
        $info = DB::name($this->table)->where("id", $id)->find();
        $info['goods_content'] = htmlspecialchars_decode($info['goods_content']);
        $info['goods_tag'] = explode('|', $info['goods_tag']);
        //echo $info['goods_content'];exit;
        return $this->fetch('', ['vo' => $info]);
    }

    /**
     * 已售车列表
     */
    public function has_sale(){
        $get = $this->request->get();
        $db = Db::name("ProductSaled")->order("sort");
        if(isset($get['page']) && $get['page'] != 0){
            //分页json
            $res['nextPage'] = $get['page'] + 1;
            $db->where("is_deleted",0);
            $db->field("id,brand_name,car_model,car_color,ahead_image");
            $list = $db->paginate(10);
            if ($list->lastPage() < $res['nextPage']) {
                $res['nextPage'] = 0;
            }
            $str = "";
            foreach ($list as $key => $val) {
                $str .= '<li>';
                $str .= '<a href="' . url("wap/index/show_sale") . '?id=' . $val["id"] . '">';
                $str .= '<div class="img"><img src="' . $this->get_full_url($val["ahead_image"]) . '"></div>';
                $str .= '<div class="title">' . $val["car_model"].'-'.$val["car_color"] . '<span class="price">' . $val["brand_name"] . ' </span></div>';
                $str .= '</a></li>';
            }
            $res['str'] = $str;
            exit(json_encode($res));
        }
        return $this->fetch('');
    }

    //    已售出，图片展示
    public function show_sale()
    {
        $get = $this->request->get();
        $id = $get['id'];
        $info = DB::name("ProductSaled")->where("id", $id)->find();
        $info['ahead_image'] = $this->get_full_url($info['ahead_image']);
        $info['side_image'] = $this->get_full_url($info['side_image']);
        $info['back_image'] = $this->get_full_url($info['back_image']);
        $info['inside_image_one'] = $this->get_full_url($info['inside_image_one']);
        $info['inside_image_two'] = $this->get_full_url($info['inside_image_two']);
        return $this->fetch('', ['vo' => $info]);
    }
}
