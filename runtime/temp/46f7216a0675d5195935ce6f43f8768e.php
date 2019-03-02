<?php /*a:2:{s:62:"E:\car_subsystem/application/webapi\view\user\user_center.html";i:1541056988;s:60:"E:\car_subsystem/application/webapi\view\public\header2.html";i:1541059213;}*/ ?>
<html lang="en" data-dpr="1" style="font-size: 32px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script>
        !function (a, b) {
            function c() {
                var b = f.getBoundingClientRect().width;
                b / i > 540 && (b = 540 * i);
                var c = b / 10;
                f.style.fontSize = c + "px", k.rem = a.rem = c
            }

            var d, e = a.document, f = e.documentElement, g = e.querySelector('meta[name="viewport"]'), h = e.querySelector('meta[name="flexible"]'), i = 0, j = 0, k = b.flexible || (b.flexible = {});
            if (g) {
                console.warn("将根据已有的meta标签来设置缩放比例");
                var l = g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);
                l && (j = parseFloat(l[1]), i = parseInt(1 / j))
            } else if (h) {
                var m = h.getAttribute("content");
                if (m) {
                    var n = m.match(/initial\-dpr=([\d\.]+)/), o = m.match(/maximum\-dpr=([\d\.]+)/);
                    n && (i = parseFloat(n[1]), j = parseFloat((1 / i).toFixed(2))), o && (i = parseFloat(o[1]), j = parseFloat((1 / i).toFixed(2)))
                }
            }
            if (!i && !j) {
                var p = (a.navigator.appVersion.match(/android/gi), a.navigator.appVersion.match(/iphone/gi)), q = a.devicePixelRatio;
                i = p ? q >= 3 && (!i || i >= 3) ? 3 : q >= 2 && (!i || i >= 2) ? 2 : 1 : 1, j = 1 / i
            }
            if (f.setAttribute("data-dpr", i), !g) if (g = e.createElement("meta"), g.setAttribute("name", "viewport"), g.setAttribute("content", "initial-scale=" + j + ", maximum-scale=" + j + ", minimum-scale=" + j + ", user-scalable=no"), f.firstElementChild) f.firstElementChild.appendChild(g); else {
                var r = e.createElement("div");
                r.appendChild(g), e.write(r.innerHTML)
            }
            a.addEventListener("resize", function () {
                clearTimeout(d), d = setTimeout(c, 300)
            }, !1), a.addEventListener("pageshow", function (a) {
                a.persisted && (clearTimeout(d), d = setTimeout(c, 300))
            }, !1), "complete" === e.readyState ? e.body.style.fontSize = 12 * i + "px" : e.addEventListener("DOMContentLoaded", function () {
                    e.body.style.fontSize = 12 * i + "px"
                }, !1), c(), k.dpr = a.dpr = i, k.refreshRem = c, k.rem2px = function (a) {
                var b = parseFloat(a) * this.rem;
                return "string" == typeof a && a.match(/rem$/) && (b += "px"), b
            }, k.px2rem = function (a) {
                var b = parseFloat(a) / this.rem;
                return "string" == typeof a && a.match(/px$/) && (b += "rem"), b
            }
        }(window, window.lib || (window.lib = {}));
    </script>
    <title>深圳车厘子-首页</title>
    <link rel="stylesheet" href="/static/bank/index.css">
    <script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>
<link rel="icon" href="http://chexinyuan.com/clz.ico"/>
<link href="http://chexinyuan.com/clz.ico"
      rel="apple-touch-icon-precomposed">
<link href="http://chexinyuan.com/clz.ico" rel="Shortcut Icon"
      type="image/x-icon"/>

    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

    </style>
</head>

<body style="font-size: 12px;">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
     style="position: absolute; width: 0; height: 0" id="__SVG_SPRITE_NODE__">
    <symbol viewBox="0 0 22 22" id="icon_edit">
        <title>sign copy</title>
        <desc>Created with Sketch.</desc>
        <g id="icon_edit_Page-1">
            <g id="icon_edit__x31_.0-Login" transform="translate(-70.000000, -329.000000)">
                <g id="icon_edit_Group" transform="translate(38.000000, 315.000000)">
                    <g id="icon_edit_sign-copy" transform="translate(33.000000, 15.000000)">
                        <path d="M20,20.8H0c-0.4,0-0.8-0.3-0.8-0.8s0.3-0.8,0.8-0.8h20c0.4,0,0.8,0.3,0.8,0.8S20.4,20.8,20,20.8z"></path>
                        <path d="M4.3,17.4c-0.8,0-1.6-0.3-2.2-0.9l0,0c-0.6-0.6-0.9-1.4-0.9-2.2s0.3-1.6,0.9-2.2l12-12c1.2-1.2,3.2-1.2,4.4,0      c0.6,0.6,0.9,1.4,0.9,2.2S19.1,4,18.5,4.6l-12,12C5.9,17.1,5.2,17.4,4.3,17.4z M3.2,15.5c0.6,0.6,1.6,0.6,2.3,0l12-12      c0.3-0.3,0.5-0.7,0.5-1.1s-0.2-0.8-0.5-1.1c-0.6-0.6-1.7-0.6-2.3,0l-12,12c-0.3,0.3-0.5,0.7-0.5,1.1C2.7,14.8,2.9,15.2,3.2,15.5      L3.2,15.5z"></path>
                        <path d="M1.3,18.1c-0.2,0-0.4-0.1-0.5-0.2c-0.3-0.3-0.3-0.8,0-1.1l1.3-1.3c0.3-0.3,0.8-0.3,1.1,0s0.3,0.8,0,1.1l-1.3,1.3      C1.7,18,1.5,18.1,1.3,18.1z"></path>
                        <path d="M15.3,13.4c-0.2,0-0.4-0.1-0.5-0.2c-0.3-0.3-0.3-0.8,0-1.1l3.5-3.5l-0.8-0.8c-0.3-0.3-0.3-0.8,0-1.1s0.8-0.3,1.1,0      l1.3,1.3c0.3,0.3,0.3,0.8,0,1.1l-4,4C15.7,13.3,15.5,13.4,15.3,13.4z"></path>
                    </g>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 24 22" id="icon_check_progress">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>check-circle-07</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_check_progress_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
           stroke-linecap="round" stroke-linejoin="round">
            <g id="icon_check_progress_1.0-Login" transform="translate(-70.000000, -459.000000)" stroke="#FFFFFF"
               stroke-width="1.5">
                <g id="icon_check_progress_check-circle-07" transform="translate(71.000000, 460.000000)">
                    <path d="M19.1757143,8.57 C19.2485714,9.03571429 19.2857143,9.51428571 19.2857143,10 C19.2857143,15.1285714 15.1285714,19.2857143 10,19.2857143 C4.87142857,19.2857143 0.714285714,15.1285714 0.714285714,10 C0.714285714,4.87142857 4.87142857,0.714285714 10,0.714285714 C11.5457143,0.714285714 13.0028571,1.09142857 14.2857143,1.76"
                          id="icon_check_progress_Shape"></path>
                    <polyline id="icon_check_progress_Shape"
                              points="5.71428571 7.85714286 10 12.1428571 21.4285714 0.714285714"></polyline>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 22 22" id="icon_card_add">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>card-add</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_card_add_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
           stroke-linecap="round" stroke-linejoin="round">
            <g id="icon_card_add_1.0-Login" transform="translate(-71.000000, -394.000000)" stroke="#FFFFFF"
               stroke-width="1.5">
                <g id="icon_card_add_card-add" transform="translate(72.000000, 395.000000)">
                    <path d="M0.625,5.625 L19.375,5.625" id="icon_card_add_Shape"></path>
                    <path d="M5.625,15.625 L1.875,15.625 C1.185,15.625 0.625,15.065 0.625,14.375 L0.625,1.875 C0.625,1.185 1.185,0.625 1.875,0.625 L18.125,0.625 C18.815,0.625 19.375,1.185 19.375,1.875 L19.375,8.125"
                          id="icon_card_add_Shape"></path>
                    <circle id="icon_card_add_Oval" cx="14.375" cy="14.375" r="5"></circle>
                    <path d="M14.375,11.875 L14.375,16.875" id="icon_card_add_Shape"></path>
                    <path d="M11.875,14.375 L16.875,14.375" id="icon_card_add_Shape"></path>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 28 28" id="icon_terms">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>paper-2 copy</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_terms_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="icon_terms_2.0-Application---Step-1" transform="translate(-49.000000, -99.000000)" stroke="#FFFFFF"
               stroke-width="1.5">
                <g id="icon_terms_Group-5" transform="translate(33.000000, 83.000000)">
                    <g id="icon_terms_paper-2-copy" transform="translate(16.000000, 16.000000)">
                        <path d="M4,25.2 L4,15 L0.666666667,15 C0.298666667,15 0,15.2688 0,15.6 L0,25.2 C0,26.1942 0.895333333,27 2,27 C3.10466667,27 4,26.1942 4,25.2 Z" id="icon_terms_Shape"></path>
                        <path d="M27.3951564,0 L5.62078726,0 C5.28630876,0 5.01594368,0.272695652 5.01594368,0.608695652 L5.01594368,25.5652174 C5.01594368,26.7710435 4.14315438,27.7650435 3,27.9592174 C3.06350858,27.9817391 3.13004137,28 3.20141291,28 L24.9757821,28 C26.6433358,28 28,26.6346957 28,24.9565217 L28,0.608695652 C28,0.272695652 27.7296349,0 27.3951564,0 Z M9.85469238,6.69565217 C9.85469238,6.35965217 10.1250575,6.08695652 10.459536,6.08695652 L15.2982847,6.08695652 C15.6327632,6.08695652 15.9031283,6.35965217 15.9031283,6.69565217 L15.9031283,11.5652174 C15.9031283,11.9012174 15.6327632,12.173913 15.2982847,12.173913 L10.459536,12.173913 C10.1250575,12.173913 9.85469238,11.9012174 9.85469238,11.5652174 L9.85469238,6.69565217 Z M22.5564077,21.9130435 L10.459536,21.9130435 C10.1250575,21.9130435 9.85469238,21.6403478 9.85469238,21.3043478 C9.85469238,20.9683478 10.1250575,20.6956522 10.459536,20.6956522 L22.5564077,20.6956522 C22.8908862,20.6956522 23.1612513,20.9683478 23.1612513,21.3043478 C23.1612513,21.6403478 22.8908862,21.9130435 22.5564077,21.9130435 Z M22.5564077,17.0434783 L10.459536,17.0434783 C10.1250575,17.0434783 9.85469238,16.7707826 9.85469238,16.4347826 C9.85469238,16.0987826 10.1250575,15.826087 10.459536,15.826087 L22.5564077,15.826087 C22.8908862,15.826087 23.1612513,16.0987826 23.1612513,16.4347826 C23.1612513,16.7707826 22.8908862,17.0434783 22.5564077,17.0434783 Z M22.5564077,12.173913 L19.5321898,12.173913 C19.1977113,12.173913 18.9273462,11.9012174 18.9273462,11.5652174 C18.9273462,11.2292174 19.1977113,10.9565217 19.5321898,10.9565217 L22.5564077,10.9565217 C22.8908862,10.9565217 23.1612513,11.2292174 23.1612513,11.5652174 C23.1612513,11.9012174 22.8908862,12.173913 22.5564077,12.173913 Z M22.5564077,7.30434783 L19.5321898,7.30434783 C19.1977113,7.30434783 18.9273462,7.03165217 18.9273462,6.69565217 C18.9273462,6.35965217 19.1977113,6.08695652 19.5321898,6.08695652 L22.5564077,6.08695652 C22.8908862,6.08695652 23.1612513,6.35965217 23.1612513,6.69565217 C23.1612513,7.03165217 22.8908862,7.30434783 22.5564077,7.30434783 Z" id="icon_terms_Shape"></path>
                    </g>
                </g>
            </g>
        </g>
    </symbol>
</svg>

<div id="root">
    <div class="container___3Wps8">
        <section class="logo___2vrnR"><img src="/static/bank/clz.png" alt="深圳车厘子汽车销售中心"></section>
        <p class="hello___AU_Z0">你好!</p>
        <p>欢迎你来到深圳车厘子汽车销售中心</p>
        <p><span><?php echo htmlentities($user_info['nickname']); ?></span>,请您选择服务。</p>
        <div class="am-whitespace am-whitespace-lg"></div>
        <div class="am-whitespace am-whitespace-lg"></div>
        <a href="<?php echo url('webapi/user/gouche'); ?>" style="width: 100%;">
            <div class="container___1F_I1">
                <div class="button___1dfGc hasIcon___3gONm">
                    <svg class="am-icon am-icon-md " color="#fff">
                        <use xlink:href="#icon_edit" width="18" height="18"></use>
                    </svg>
                    <span class="buttonText___3_BSg">深圳车厘子-我的购车</span></div>
            </div>
        </a>
        <div class="am-whitespace am-whitespace-lg"></div>
        <a href="<?php echo url('webapi/user/my_pay'); ?>" style="width: 100%;">
            <div class="container___1F_I1">
                <div class="button___1dfGc hasIcon___3gONm">
                    <svg class="am-icon am-icon-md " color="#fff">
                        <use xlink:href="#icon_check_progress" width="18" height="18"></use>
                    </svg>
                    <span class="buttonText___3_BSg">汽车销售-我的付款</span></div>
            </div>
        </a>
        <div class="am-whitespace am-whitespace-lg"></div>
        <a href="<?php echo url('webapi/user/daiban'); ?>" style="width: 100%;">
            <div class="container___1F_I1">
                <div class="button___1dfGc hasIcon___3gONm">
                    <svg class="am-icon am-icon-md " color="#fff">
                        <use xlink:href="#icon_card_add" width="18" height="18"></use>
                    </svg>
                    <span class="buttonText___3_BSg">代办业务</span></div>
            </div>
        </a>

        <div class="am-whitespace am-whitespace-lg"></div>
        <a href="<?php echo url('webapi/contract/jiaoche'); ?>?contract_id=<?php echo htmlentities($contract_info['id']); ?>" style="width: 100%;">
            <div class="container___1F_I1">
                <div class="button___1dfGc hasIcon___3gONm">
                    <svg class="am-icon am-icon-md  " color="#fff">
                        <use xlink:href="#icon_terms" width="18" height="18"></use>
                    </svg>
                    <span class="buttonText___3_BSg">交车</span></div>
            </div>
        </a>
    </div>
</div>

<script src="//as.alipayobjects.com/g/component/fastclick/1.0.6/fastclick.js" async=""></script>

<script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>
<script>
    var window_height = $(window).height();
    $("body").height(window_height)
</script>
</body>
</html>