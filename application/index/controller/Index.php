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

namespace app\index\controller;


use controller\BasicApi;
use service\DataService;
use service\HttpService;
use service\WechatService;
use think\Db;
use think\Exception;

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
    public $share;

    public function __construct()
    {
        parent::__construct();
        $sale_count = Db::name("ProductSaled")->where("is_deleted", 0)->count();
        $this->assign("has_sale", $sale_count);

        //获取微信公众号SDK
//        $wx_options = WechatService::webJsSDK();
//        $this->assign('wx_options', $wx_options);
        //分享的参数
        $this->share['title'] = sysconf("wap_title");
        $this->share['desc'] = sysconf("wap_keywords");
        $this->share['img_url'] = 'http://chexinyuan.com/static/upload/3f75168918ae1342/50caea27245f7637.jpg';
//        $this->assign('share', $this->share);
    }


    public function test(){
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
//        $content = HttpService::get("https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types");

//        preg_match_all('#^([^\s]{2,}?)\s+(.+?)$#ism', $content, $matches, PREG_SET_ORDER);
        try {
            $content = file_get_contents("http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types");
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }


    /**
     * 首页
     */
    public function index()
    {
        $db = Db::name($this->table)->where(['is_deleted' => '0']);
        $res = $db->field("id,goods_title,goods_prices,goods_logo,guid_price,goods_tag")->order("sort")->limit(10)->select();
        foreach ($res as &$val) {
            $val['goods_tag'] = explode('|', $val['goods_tag']);
        }
        $jiaoche = Db::name("ProductSaled")->order("id desc")->limit(10)->select();
        $images = [];
        foreach ($jiaoche as $k => $v) {
            $jiaoche[$k]['create_at'] = date("Y-m-d", strtotime($jiaoche[$k]['create_at']));
            ($v['ahead_image'] != '') ? $images[$k][] = $v['ahead_image'] : false;
            ($v['side_image'] != '') ? $images[$k][] = $v['side_image'] : false;
            ($v['back_image'] != '') ? $images[$k][] = $v['back_image'] : false;
            ($v['inside_image_one'] != '') ? $images[$k][] = $v['inside_image_one'] : false;
            ($v['inside_image_two'] != '') ? $images[$k][] = $v['inside_image_two'] : false;
        }
        $images = json_encode($images);
        return $this->fetch('', ['list' => $res, 'images' => $images, 'jiaoche' => $jiaoche]);
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
            $db->field("id,goods_title,goods_prices,goods_logo,guid_price,goods_tag")->order("sort");
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

                $goods_tags = explode('|', $val['goods_tag']);

                $str .= '<li>';
                $str .= '<a href="/index.php/wap/index/info?id=' . $val["id"] . '">';
                $str .= '<div class="img"><img src="' . $val["goods_logo"] . '"></div>';
                $str .= '<div class="title" style="overflow:hidden;"><span style="display: block;width: 100%;float: left;font-size: 18px;font-weight: normal;line-height: 1.8rem">' . $val["goods_title"] . '</span>';
                $str .= '<span class="price" style="text-align: left">';
                foreach ($goods_tags as $value) {
                    if ($value != '') {
                        $str .= '<em class="M_br3">' . $value . '</em>';
                    }
                }
                $str .= '</span>';
                $str .= '<span class="price" style="text-align: left;padding: 5px 0px;">' . $val["goods_prices"] . ' 万起<em style="margin-left: 10px;color: #c3c3c3;text-decoration:line-through;font-size: 14px">' . $val["guid_price"] . ' 万</em></span></div>';
                $str .= '<div class="title" style="overflow:hidden;"><span class="bg_blue">现车</span><span class="bg_blue">分期</span><span class="bg_blue">一次性</span></div>';
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
            $cate_info = Db::name("GoodsCate")->where(['id' => $cate_id])->find();
            $cates = [];
            if ($cate_info['pid'] == 0) {
                $active_cate1 = $cate_id;
                $cates_child = Db::name("GoodsCate")->where(['is_deleted' => '0', 'pid' => $cate_id, 'status' => 1])->select();
                $cates = array_column($cates_child, 'id');

                $cate_list2 = Db::name("GoodsCate")
                    ->where(['is_deleted' => '0', 'pid' => $cate_id, 'status' => 1])
                    ->order("sort")
                    ->field("id,cate_title,sort")
                    ->select();
            } else {
                $active_cate1 = $cate_info['pid'];
                $active_cate2 = $cate_id;
                $cate_list2 = Db::name("GoodsCate")
                    ->where(['is_deleted' => '0', 'pid' => $active_cate1, 'status' => 1])
                    ->order("sort asc,id asc")
                    ->field("id,cate_title,sort")
                    ->select();
            }
            array_unshift($cates, $cate_id);
            $db->whereIn("cate_id", $cates);
        }
        $res = $db->field("id,goods_title,goods_prices,goods_logo,guid_price,goods_tag")->order("sort")->limit(10)->select();
        foreach ($res as &$val) {
            $val['goods_tag'] = explode('|', $val['goods_tag']);
        }
        $cate_list = Db::name("GoodsCate")
            ->where(['is_deleted' => '0', 'pid' => 0, 'status' => 1])
            ->order("sort asc,id asc")
            ->field("id,cate_title,sort")
            ->select();
        return $this->fetch('', ['cate_list' => $cate_list, 'list' => $res, 'cate_list2' => $cate_list2, 'active_cate1' => $active_cate1, 'active_cate2' => $active_cate2]);
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
        $this->share['title'] = "【" . $info['goods_title'] . "】- " . sysconf('wap_title');
        $this->share['desc'] = $info['goods_title'] . ",报价,价格";
        $this->assign('share', $this->share);
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
        //【{$vo.goods_title}】-{$vo.goods_prices}万起- {:sysconf('wap_title')}
        $this->share['title'] = "【" . $info['goods_title'] . "】-" . $info['goods_prices'] . "万起- " . sysconf('wap_title');
        $this->share['desc'] = $info['goods_title'] . ",报价,价格";
        $this->assign('share', $this->share);
        return $this->fetch('', ['vo' => $info]);
    }

    /**
     * 已售车列表
     */
    public function has_sale()
    {
        $get = $this->request->get();
        $db = Db::name("ProductSaled")->order("id desc");
        if (isset($get['page']) && $get['page'] != 0) {
            //分页json
            $res['nextPage'] = $get['page'] + 1;
            $db->where("is_deleted", 0);
            $db->field("id,brand_name,car_model,car_color,ahead_image,create_at");
            $list = $db->paginate(10);
            if ($list->lastPage() < $res['nextPage']) {
                $res['nextPage'] = 0;
            }
            $str = "";
            foreach ($list as $key => $val) {
                $str .= '<li>';
                $str .= '<a href="' . url("wap/index/show_sale") . '?id=' . $val["id"] . '">';
                $str .= '<div class="img"><img src="' . $this->get_full_url($val["ahead_image"]) . '"></div>';
                $str .= '<div class="title"><span class="price">' . $val["brand_name"] . ' </span>' . $val["car_model"] . '-' . $val["car_color"] . '<span class="price2">' . date("Y-m-d", strtotime($val["create_at"])) . ' </span></div>';
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


    //询问底价
    public function contact()
    {
        $get = $this->request->get();
        $id = $get['id'];
        $info = Db::name($this->table)->where("id", $id)->find();

        return $this->fetch('', ['vo' => $info]);
    }

    //保存信息
//goods_id: goods_id,
//name: name,
//phone_num: phone_num
    public function update_contact()
    {
        $get = $this->request->request();
        $goods_id = $get['goods_id'];
        $name = $get['name'];
        $phone_num = $get['phone_num'];
        $is_has = Db::name("GoodsContacts")->where("goods_id", $goods_id)->where("phone_num", $phone_num)->find();
        if ($is_has && $is_has['status'] == 0) {
            $this->success_json("保存成功");
        }
        $savedata['goods_id'] = $goods_id;
        $savedata['phone_num'] = $phone_num;
        $savedata['name'] = $name;
        $r = DataService::save("GoodsContacts", $savedata, 'id');
        if (!$r) {
            $this->error_json(400, '保存失败，请稍后重试', []);
        }
        $this->success_json("保存成功");
    }
}
