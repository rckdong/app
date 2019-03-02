<?php /*a:3:{s:60:"E:\car_subsystem/application/webapi\view\user\user_form.html";i:1535184344;s:63:"E:\car_subsystem/application/webapi\view\public\weixin_sdk.html";i:1531453703;s:65:"E:\car_subsystem/application/webapi\view\public\weixin_share.html";i:1531453701;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="0">
    <title>车主绑定</title>
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <link href="/static/webapp/css/mui.min.css" rel="stylesheet"/>
    <link href="/static/webapp/css/from.css" rel="stylesheet"/>
    <script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>
    <script src="/static/webapp/js/mui.js"></script>
    <link href="/static/webapp/js/dialog/dialog.css" type="text/css" rel="stylesheet">
    <script src="/static/webapp/js/dialog/dialog.js"></script>
    <script src="/static/webapp/js/confirm.js"></script>

    <link href="/static/webapp/js/picker_mui/picker.css" rel="stylesheet"/>
    <link href="/static/webapp/js/picker_mui/mui.picker.min.css" rel="stylesheet"/>

    <script src="/static/webapp/js/picker_mui/mui.picker.js"></script>
    <script src="/static/webapp/js/picker_mui/mui.poppicker.js"></script>
    <script src="/static/webapp/js/picker_mui/mui.picker.min.js"></script>
    
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
                var user_share_url = "<?php echo url('webapi/user/user_share'); ?>";
                $.post(user_share_url, function (res) {
                    console.log(res);
                });
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
                var user_share_url = "<?php echo url('webapi/user/user_share'); ?>";
                $.post(user_share_url, function (res) {
                    console.log(res);
                });
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
                var user_share_url = "<?php echo url('webapi/user/user_share'); ?>";
                $.post(user_share_url, function (res) {
                    console.log(res);
                });
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
                var user_share_url = "<?php echo url('webapi/user/user_share'); ?>";
                $.post(user_share_url, function (res) {
                    console.log(res);
                });
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
                var user_share_url = "<?php echo url('webapi/user/user_share'); ?>";
                $.post(user_share_url, function (res) {
                    console.log(res);
                });
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
</head>
<body>

<style type="text/css">
    body {
        background: #FFFFFF;
        overflow-x: hidden;
    }

    header.mui-bar {
        background-color: #242736;
        color: #FFF;
    }

    header.mui-bar .mui-title {
        color: #FFF;
        font-size: 15px;
    }

    div.mui-card {
        width: 90%;
        margin: 0 auto
    }

    input#btn {
        position: absolute;
        z-index: 2;
        top: 4px;
        right: 25px;
        font-size: 12px;
        background: #3377ff;
        width: 85px;
        padding: 0 5px;
        height: 28px;
        color: #FFF;
        border-radius: 5px;;
    }

    .mui-input-group:before {
        height: 0;
    }

    .okBtn {
        border: none;
        width: 80%;
        height: 37px;
        margin: 0 auto;
        display: block;
        margin-top: 30px;
        margin-bottom: 30px
    }

    div.filter_bg {
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        top: 0;
        z-index: 100;
        display: none;
    }

    div.conDialog {
        position: fixed;
        width: 86%;
        height: 420px;
        top: 14%;
        z-index: 9999;
        left: 7%;
        background: #FFF;
        background-size: 100%;
        border-radius: 10px;
        border-radius: 20px;
        overflow: hidden;
        display: none;
    }

    p.tit {
        text-align: center;
        background-color: #242736;
        height: 40px;
        margin-top: 0px;
        color: #FFF;
        line-height: 40px;
        font-size: 14px;
    }

    div.mzCon {
        line-height: 1.5;
        font-size: 12px;
        padding: 8px; height: 70%;
    }

    button.poss {
        position: absolute;
        bottom: 10px;
        margin: 0 auto;
        width: 40%;
        left: 9%;
    }
    button.upload_cancle {
        background: #404040;
        border: 1px solid #404040;
        position: absolute;
        bottom: 10px;
        margin: 0 auto;
        width: 40%;
        right: 9%;
    }
    .test{
        width: 98%;
        height: 370px;
        overflow: auto;
        margin: 5px;
    }
    .scrollbar{
        width: 90%;
        height: 360px;
        margin: 0 auto;
    }


    .test-1::-webkit-scrollbar /*滚动条整体样式*/
        width: 5px;     /*高宽分别对应横竖滚动条的尺寸*/
        height: 1px;
    
    .test-1::-webkit-scrollbar-thumb /*滚动条里面小方块*/
        border-radius: 5px;
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        background: #535353;
    
    .test-1::-webkit-scrollbar-track /*滚动条里面轨道*/
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        border-radius: 5px;
        background: #EDEDED;
    

    .scrollbar b.f{
        color: #cf5012;
    }
    .scrollbar span.vis{
        visibility: hidden;
    }
    .scrollbar span.fl{
        margin-left:1px;
    }

    .mui-input-group:after{
        height: 0;
    }
    .mui-input-group .mui-input-row{
        height: 60px;
        margin-bottom: 35px;
    }
</style>

<a href="<?php echo url('webapi/user/user_center'); ?>">
    <header id="mui-color" style="background: #36a9d3" class="mui-bar mui-bar-nav">
        <div class="mui-icon  mui-icon-left-nav mui-pull-left lotteryBack"></div>
        <h1 class="mui-title">手机号绑定</h1>
    </header>
</a>

<div class="filter_bg"></div>

<div id="conDialog" class="conDialog">
    <p class="tit">免责协议书</p>
    <div class="mzCon">
        <div class="test test-1">
            <div class="scrollbar">
                免责协议书内容....<br />
            </div>
        </div>
    </div>
    <button id="confirm_update_button" type="button" class="mui-btn mui-disabled mui-btn-primary poss" onclick="checkForm(this);">确认报名&nbsp;(<b id="after_timer">10</b>)&nbsp;</button>
    <button id="update_cancle" type="button" class="mui-btn mui-btn-primary upload_cancle" >取消</button>
</div>


<div style="padding-top:25%;">
    <div class="mui-content">

        <div class="mui-content-padded" style="margin: 0 5px;width: 105%;margin-left: -5px;">
            <form class="mui-input-group test2">
                <div class="mui-input-row">
                    <label>姓名</label>
                    <input type="text" class="mui-input-clear" id="userName" placeholder="请输入您的姓名" value="">
                </div>

                <div class="mui-input-row">
                    <label>性别</label>

                    <div style="display: inline-block;" class="mui-input-row mui-radio">
                        <input name="sex" value="1" type="radio">
                        <label style="padding-right: 50px;">男</label>
                    </div>

                    <div style="display: inline-block;" class="mui-input-row mui-radio">
                        <input name="sex" value="0" type="radio">
                        <label style="padding-right: 50px;">女</label>
                    </div>
                </div>

                <div class="mui-input-row">
                    <label>手机号码</label>
                    <input type="number" class="mui-input-clear" id="phone" value=""
                           placeholder="请输入您的手机号码">
                </div>

                <div class="mui-input-row" style="position: relative">
                    <label class="iconfont_log_reg icon-youjian">短信验证码</label>
                    <input type="text" placeholder="请输入短信验证码" id="u_code">
                    <input type="button" id="btn" value="获取验证码" onclick="send_sms(this)"/>
                </div>

                <!--<div class="mui-card">-->
                    <!--<ul class="mui-table-view">-->
                        <!--<li class="mui-table-view-cell mui-collapse mui-active">-->
                            <!--<a class="mui-navigate-right"><b style="color:#3377FF;">免责声明</b></a>-->
                            <!--<div class="mui-collapse-content">-->
                                <!--<p>-->
                                     <!--免责声明...-->
                                <!--</p>-->
                            <!--</div>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</div>-->

                <button id="xyM" type="button" style="background: #3377ff" class="mui-btn mui-btn-primary okBtn" onclick="javascript:void (0)">确认</button>
            </form>

        </div>
    </div>
</div>


<script type="text/javascript">

    (function ($, doc) {
        $.init();
        $.ready(function () {
            /**
             * 获取对象属性的值
             * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
             * @param {Object} obj 对象
             * @param {String} param 属性名
             */
            var _getParam = function (obj, param) {
                return obj[param] || '';
            };

        });
    })(mui, document);

    //发送短信
    function send_sms(obj) {
        var phone = $("#phone").val();
        var sms_send = "<?php echo url('webapi/user/send_sms'); ?>";
        var send_data = {
            mobile: phone,
        };
        $.get(sms_send, send_data, function (res) {
        });
        settime(obj);
    }

    var countdown = 60;
    function settime(obj) {

        var phone = $("#phone").val();
        var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
        if (!myreg.test(phone)) {
            mui.toast('请输入正确的手机号码');
            return;
        }
        if (countdown == 0) {
            obj.removeAttribute("disabled");
            obj.value = "免费获取验证码";
            countdown = 60;
            return;
        } else {
            obj.setAttribute("disabled", true);
            obj.value = "重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function () {
            settime(obj)
        }, 1000);
    }

    var time_one;
    $("#xyM").click(function () {
        //数据验证
        var userName = $("#userName").val();  //姓名
        var phone = $("#phone").val();  //手机号码
        var code = $("#u_code").val();  //验证码
        var sex = $("input[name='sex']:checked").val();
        var _this = $(this);

        if ($.trim(userName) == '') {
            mui.toast('请输入您的姓名');
            return;
        }

        if (!/^[1][3,4,5,7,8][0-9]{9}$/.test(phone)) {
            mui.toast('请输入正确的手机号码');
            return;
        }


        if ($.trim(code) == '') {
            mui.toast('请输入短信验证码');
            return;
        }

        //end
        var data = {
            form: 'user_form',
            user_name: userName,
            sex: sex,
            phone: phone,
            code: code,
        };

        /* console.log(data);
         return;*/


        $(this).text('正在提交....').css({"background": '#404040'});
        $(this).addClass("mui-disabled");
        //提交表单
        var updateForm = "<?php echo url('webapi/user/do_user_form'); ?>";
        $.post(updateForm, data, function (res) {
            console.log(res);
            mui.toast(res.msg);
            $(_this).text('确认').css({"background": '#FF6C3C'});
            $(_this).removeClass("mui-disabled");
            if (res.status == 200) {
                //TODO 保存成功操作
                var pay_url = "<?php echo url('webapi/user/user_center'); ?>";
                window.location.href = pay_url;
            }
        });
        return;
    });



</script>
</body>
</html>