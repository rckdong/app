<?php /*a:2:{s:58:"E:\car_subsystem/application/webapi\view\public\error.html";i:1536334994;s:59:"E:\car_subsystem/application/webapi\view\public\header.html";i:1541059213;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <title>车厘子 - <?php echo htmlentities($title); ?></title>
    <meta name="keywords" content=""/>
<meta name="description" content=""/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>


<link rel="icon" href="http://chexinyuan.com/clz.ico"/>
<link href="http://chexinyuan.com/clz.ico"
      rel="apple-touch-icon-precomposed">
<link href="http://chexinyuan.com/clz.ico" rel="Shortcut Icon"
      type="image/x-icon"/>
<link rel="stylesheet" href="/static/webapp/css/mob.common.min.css?0809"/>

<link href="/static/webapp/css/index.css" rel="stylesheet"/>
<link href="/static/webapp/css/mui.min.css" rel="stylesheet"/>
<script type="text/javascript" src="/static/webapp/js/mui.js"></script>

</head>


<body style="background-color: #fff !important;">

<script type="text/javascript">!function (a) {
    function b() {
        var b = g.getBoundingClientRect().width;
        b / c > 640 && (b = 640 * c), a.rem = b / 16, g.style.fontSize = a.rem + "px"
    }

    var c, d, e, f = a.document, g = f.documentElement, h = f.querySelector('meta[name="viewport"]'),
        i = f.querySelector('meta[name="flexible"]');
    if (h) {
        console.warn("将根据已有的meta标签来设置缩放比例");
        var j = h.getAttribute("content").match(/initial-scale=(["']?)([d.]+)1?/);
        j && (d = parseFloat(j[2]), c = parseInt(1 / d))
    } else if (i) {
        var j = i.getAttribute("content").match(/initial-dpr=(["']?)([d.]+)1?/);
        j && (c = parseFloat(j[2]), d = parseFloat((1 / c).toFixed(2)))
    }
    if (!c && !d) {
        var k = (a.navigator.appVersion.match(/android/gi), a.navigator.appVersion.match(/iphone/gi)),
            c = a.devicePixelRatio;
        c = k ? c >= 3 ? 3 : c >= 2 ? 2 : 1 : 1, d = 1 / c
    }
    if (g.setAttribute("data-dpr", c), !h) if (h = f.createElement("meta"), h.setAttribute("name", "viewport"), h.setAttribute("content", "initial-scale=" + d + ", maximum-scale=" + d + ", minimum-scale=" + d + ", user-scalable=no"), g.firstElementChild) g.firstElementChild.appendChild(h); else {
        var l = f.createElement("div");
        l.appendChild(h), f.write(l.innerHTML)
    }
    a.dpr = c, a.addEventListener("resize", function () {
        clearTimeout(e), e = setTimeout(b, 300)
    }, !1), a.addEventListener("pageshow", function (a) {
        a.persisted && (clearTimeout(e), e = setTimeout(b, 300))
    }, !1), "complete" === f.readyState ? f.body.style.fontSize = 12 * c + "px" : f.addEventListener("DOMContentLoaded", function () {
            f.body.setAttribute('fontSize', 12 * c + "px")
        }, !1), b()
}(window);
/*app body*/
(function (b) {
    var a = b.body;
    var e = navigator.userAgent;
    /in_app_not_header/i.test(e) && (a.className += " app-body-no-header");
    /in_app_not_footer/i.test(e) && (a.className += " app-body-no-footer");

    function d() {
        var g = location.search;
        var f = new Object();
        if (g.indexOf("?") != -1) {
            var j = g.substr(1);
            strs = j.split("&");
            for (var h = 0; h < strs.length; h++) {
                f[strs[h].split("=")[0]] = (strs[h].split("=")[1])
            }
        }
        return f
    }

    var c = d();
    if (c.APP_NAME && c.APP_NAME == "MLL_ADMIN") {
        a.className += " app-body-no-toolbar"
    }
})(document);</script>

<script type="text/javascript">
    window._gaq = window._gaq || [];
    window._ana = window._ana || [];
    _ana.baseTime = new Date().getTime();
    window.$$ = window.$$ || {};
    $$.__IMG = 'http://img002.mllres.com' || 'http://image.meilele.com';
    window._onReadyList = window._onReadyList || [];
    window._$_ = function (id) {
        return document.getElementById(id)
    };
</script>

<div style="margin: 130px auto;text-align: center">
    <img src="/static/webapp/images/error.png" style="width: 200px;" alt="<?php echo htmlentities($title); ?>">
    <p style="font-size: 16px;margin-top: 20px;color: grey;"><?php echo htmlentities((isset($title) && ($title !== '')?$title:'暂无数据！')); ?></p>
</div>


<footer class="mll-footer" style="position:absolute;width: 100%;bottom: 0px;left: 0px;margin-top: 50px;">
    <div class="mll-footer-icp">如有问题，联系客服<a href="tel:4006561513">400 - 6561 -513</a></div>
</footer>

</body>
</html>
