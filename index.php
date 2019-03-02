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

namespace think;

$a = '{"background_image":"data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX\/\/\/+nxBvIAAAACklEQVQI12NgAAAAAgAB4iG8MwAAAABJRU5ErkJggg==","border_color":"rgba(0,0,0,.1)","navs":[{"url":"\/pages\/index\/index","icon":"http:\/\/sanmeng.ytclouds.net\/addons\/zjhj_mall\/core\/web\/statics\/images\/appnavbar\/nav-icon-index.png","active_icon":"http:\/\/0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/c1\/c13eef1a8dd8c40c0f0d42effed9b49b.png","text":"商城","color":"#888","active_color":"#2c4194"},{"url":"\/pages\/cat\/cat","icon":"http:\/\/sanmeng.ytclouds.net\/addons\/zjhj_mall\/core\/web\/statics\/images\/appnavbar\/nav-icon-cat.png","active_icon":"http:\/\/0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/ec\/ec75662ccec9388c270fc63b6e86c84f.png","text":"分类","color":"#888","active_color":"#2c4194"},{"url":"\/pages\/list\/list?cat_id=28","icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/29\/294233e754adb7770b19e99f377674c7.png","active_icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/aa\/aa1b7ae7bf5bfa5576e74921e8312c8d.png","text":"促销","color":"#888","active_color":"#2c4194"},{"url":"\/pages\/cart\/cart","icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/5a\/5a9d79a60a54f714b7a7573ca4ce9e70.png","active_icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/1e\/1e01b1a9304fdf2125bc873d87a9c68f.png","text":"购物车","color":"#888","active_color":"#2c4194"},{"url":"\/pages\/user\/user","icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/54\/5473fec2a2b28d3c48967abf72d929c0.png","active_icon":"https:\/\/www.0760net.net\/addons\/zjhj_mall\/core\/web\/uploads\/image\/37\/37713c7f2b380f02566434557c743998.png","text":"我的","color":"#888","active_color":"#2c4194"}]}';
$a = serialize($a);
echo $a;exit;


// 加载基础文件
require __DIR__ . '/thinkphp/base.php';

// 执行应用并响应
Container::get('app', [__DIR__ . '/application/'])->run()->send();
