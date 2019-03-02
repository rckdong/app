<?php /*a:3:{s:56:"E:\car_subsystem/application/index\view\index\index.html";i:1538895313;s:60:"E:\car_subsystem/application/index\view\public\baidu_hm.html";i:1538895021;s:64:"E:\car_subsystem/application/index\view\public\weixin_share.html";i:1532906734;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo sysconf('wap_title'); ?></title>
    <meta name="keywords" content="<?php echo sysconf('wap_keywords'); ?>">
    <meta name="description" content="<?php echo sysconf('wap_description'); ?>">

    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="no-cache" http-equiv="cache-control">
    <link href="/static/index_files/cheshangle.css" rel="stylesheet" type="text/css">
    <link href="/static/index_files/cheoo.css" rel="stylesheet" type="text/css">

    <script src="/static/index_files/jquery-1.8.3.min.js"></script>
    <script src="/static/index_files/cheshangle.js"></script>
    <script src="/static/index_files/shencong.js"></script>
    <script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?5cbe57b76be97bce82685474ee6e1bb7";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

</head>

<body>
<div class="M_scrollHidden">

    <div class="M_main pt0">
        <!-- 下载提示 begin -->
        <!-- 经销商 begin -->
        <div class="M_main_con M_clearfix">

            <div class="M_distributor">

                <div class="title">

                    <!--<a href="javascript:back();" class="left"><i class="M_icon M_icon_arrow-lw"></i></a>-->

                    <div class="img">
                        <img src="<?php echo sysconf('company_image'); ?>">
                    </div>

                    <h1><?php echo sysconf('company_name'); ?><i class="M_icon M_icon_zhan M_ml5"></i></h1>

                    <p><a href="https://apis.map.qq.com/uri/v1/search?keyword=龙岗区宝南路9号101室&region=深圳&referer=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"><i class="M_icon M_icon_location-b"></i><?php echo sysconf('company_address'); ?></a> </p>

                    <a href="javascript:showPhone(111564);" id="dealer_index_button" class="M_btn M_btn_blue M_br3"><i
                            class="M_icon M_icon_tel-w M_mr5"></i>销售热线</a>

                </div>

                <div class="menu">

                    <ul>

                        <li class="current"><a href="<?php echo url('wap/index/index'); ?>">首页</a></li>

                        <li class=""><a href="/index.php/wap/index/get_list">全部车型</a></li>

                        <li class=""><a href="/index.php/wap/index/has_sale">已售 <?php echo htmlentities($has_sale); ?></a></li>

                        <li class=""><a href="<?php echo url('wap/index/about'); ?>">关于我们</a></li>

                    </ul>

                </div>

            </div>

        </div>
        <!-- 电话弹层 begin -->
        <div class="M_pop_tel" id="">

            <ul class="list M_br12">

            </ul>

            <a href="javascript:hidePhone();" class="close M_br12">取消</a>

        </div>

        <div class="M_pop-bg"></div>


        <!-- 车型报价 begin -->
        <div class="M_main_con">

            <h2>车型介绍</h2>

            <ul class="M_choice M_series M_clearfix">
                <?php foreach($list as $key=>$vo): ?>
                <li>
                    <a href="/index.php/wap/index/info?id=<?php echo htmlentities($vo['id']); ?>">
                        <div class="img"><img src="<?php echo htmlentities($vo['goods_logo']); ?>"></div>
                        <div class="title" style="overflow:hidden;">
                            <span style="display: block;width: 100%;float: left;font-size: 18px;font-weight: normal;line-height: 1.8rem"><?php echo htmlentities($vo['goods_title']); ?></span>
                            <span class="price" style="text-align: left">
                                <?php foreach($vo['goods_tag'] as $tag): if($tag != ''): ?><em class="M_br3"><?php echo htmlentities($tag); ?></em><?php endif; endforeach; ?>
                            </span>
                            <span class="price" style="text-align: left;padding: 5px 0px;"><?php echo htmlentities($vo['goods_prices']); ?> 万起<em style="margin-left: 10px;color: #c3c3c3;text-decoration:line-through;font-size: 14px"><?php echo htmlentities($vo['guid_price']); ?> 万</em></span>
                        </div>
                        <div class="title" style="overflow:hidden;"><span class="bg_blue">现车</span><span class="bg_blue">分期</span><span class="bg_blue">一次性</span></div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="M_plrb12"><a href="/index.php/wap/index/get_list"
                                     class="M_btn_yellow M_btn-all M_br3 M_mt12">更多车型介绍</a></div>

        </div>

    </div>

</div>


<script src="/static/index_files/jquery-1.8.3.min.js"></script>
<script src="/static/index_files/shencong.js"></script>
<script src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>wx.config(JSON.parse('<?php echo json_encode($wx_options); ?>'));</script>
<script>
    wx.error(function (err) {
        $.toptip(err.errMsg, 100000, 'error');
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: '<?php echo htmlentities((isset($share['title']) && ($share['title'] !== '')?$share['title']:"")); ?>', // 分享标题
            link: '<?php echo request()->url(true); ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php echo htmlentities((isset($share['img_url']) && ($share['img_url'] !== '')?$share['img_url']:"")); ?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数

            },cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo htmlentities((isset($share['title']) && ($share['title'] !== '')?$share['title']:"")); ?>', // 分享标题
            desc: '<?php echo htmlentities((isset($share['desc']) && ($share['desc'] !== '')?$share['desc']:"")); ?>', // 分享描述
            link: '<?php echo request()->url(true); ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php echo htmlentities((isset($share['img_url']) && ($share['img_url'] !== '')?$share['img_url']:"")); ?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({
            title: '<?php echo htmlentities((isset($share['title']) && ($share['title'] !== '')?$share['title']:"")); ?>', // 分享标题
            desc: '<?php echo htmlentities((isset($share['desc']) && ($share['desc'] !== '')?$share['desc']:"")); ?>', // 分享描述
            link: '<?php echo request()->url(true); ?>', // 分享链接
            imgUrl: '<?php echo htmlentities((isset($share['img_url']) && ($share['img_url'] !== '')?$share['img_url']:"")); ?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareWeibo({
            title: '<?php echo htmlentities((isset($share['title']) && ($share['title'] !== '')?$share['title']:"")); ?>', // 分享标题
            desc: '<?php echo htmlentities((isset($share['desc']) && ($share['desc'] !== '')?$share['desc']:"")); ?>', // 分享描述
            link: '<?php echo request()->url(true); ?>', // 分享链接
            imgUrl: '<?php echo htmlentities((isset($share['img_url']) && ($share['img_url'] !== '')?$share['img_url']:"")); ?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQZone({
            title: '<?php echo htmlentities((isset($share['title']) && ($share['title'] !== '')?$share['title']:"")); ?>', // 分享标题
            desc: '<?php echo htmlentities((isset($share['desc']) && ($share['desc'] !== '')?$share['desc']:"")); ?>', // 分享描述
            link: '<?php echo request()->url(true); ?>', // 分享链接
            imgUrl: '<?php echo htmlentities((isset($share['img_url']) && ($share['img_url'] !== '')?$share['img_url']:"")); ?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
</body>
</html>