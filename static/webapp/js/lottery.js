/**

 * Created by loy on 2017/4/2 0002.

 */

var turnplate = {

    restaraunts: [], //大转盘奖品名称

    colors: [], //大转盘奖品区块对应背景颜色

    outsideRadius: 216, //大转盘外圆的半径

    textRadius: 160, //大转盘奖品位置距离圆心的距离

    insideRadius: 5, //大转盘内圆的半径

    startAngle: 0, //开始角度

    bRotate: false, //false:停止;ture:旋转

    img_obj: []

};


$(document).ready(function () {


    var token;

    if (location.search.indexOf("android") > -1 || location.search.indexOf("ios") > -1) {
        token = location.search.substring(1).split("=")[1].split("&")[0]
    } else {
        token = localStorage.getItem("token")
    }

    var audioEle = $("#app_music")[0];


    var lottery_list = null;
    var myLotteryListData = null;
    var count = 0;
    var isLotteryEnd = 0; //记录抽了几次
    var number_container = $("#number_container"); //剩余抽奖次数
    var lotteryIng = false; //记录是否正在抽奖中
    var timeOut;
    var lottery_id = -1; //当前中奖的ID
    var timeCheckPass = null;  //检查是否到抽奖时间
    var countdown = 7; //倒计时秒数
    var flag_countdown = 7;



    function htmldecode(string) {
        var div = document.createElement('div');
        div.innerHTML = string;
        return div.innerText || div.textContent;
    }


    //页面所有元素加载完毕后执行drawRouletteWheel()方法对转盘进行渲染

    window.onload = function () {

        //获取奖品列表
        $.post(getLottery, {type: lotteryType},function (data) {

            $("#lodding").hide();

            $("#lottery_model").show();

            var str = '';
            $.each(data.result.winners, function (index, item) {
                str += ' <li class="lottery-time"> <div>'+ item.addtime +'  '+ item.nickname +'  '+ item.good_name +'</div> </li>';
            })
            // var html = template("pop_tpl", data.result)

            $("#popTpl").html(str);

            if (data && data.status == 200) {

                $("#rule_gc").html(htmldecode(data.result.lottery_help));

                //中奖规则
                if (data.result.rule && (data.result.rule).indexOf('\r\n')) {

                    data.result.rule = JSON.parse(JSON.stringify((data.result.rule).split('\r\n')));

                    var str = '';
                    $.each(data.result.rule, function (index, item) {
                        str += '<li><em>' + (index + 1) + '</em><span>' + item + '</span></li>';
                    });

                    // $("#rule_ul").append(template('rule_tpl', data.result));

                    $("#rule_ul").html(str);
                }

                //中奖列表

                if (data.result.poolList) {


                    $.each(data.result.poolList, function (index, item) {


                        //颜色
                        if (turnplate.colors.length == 0) {

                            turnplate.colors.push("#FAECD2");

                        } else {

                            if (turnplate.colors[turnplate.colors.length - 1] == '#FAECD2') {

                                turnplate.colors.push("#FFFFFF");

                            } else {

                                turnplate.colors.push("#FAECD2");  //FAECD2
                            }
                        }

                        turnplate.restaraunts.push(item);

                        var img = new Image();

                        img.src = item.original_img;


                        img.onload = function () {

                            turnplate.img_obj.push(img);

                            console.log(turnplate.img_obj.length);

                            if (turnplate.img_obj.length == data.result.poolList.length) {

                                console.log('ok...');

                                lottery_list = JSON.stringify(data.result.poolList);  //记录奖池奖品

                                setTimeout(function () {

                                    drawRouletteWheel();

                                    lottery();

                                }, 300);

                            }

                        }

                    });

                }
            }

        }, 'json');

    };

    drawRouletteWheel();


    //详细规则按钮事件
    $("#lottery_role_btn").click(function () {
        $("#lottery_role_dialog").fadeIn();
        $("#filter_bg2").fadeIn();
    });

    //关闭抽奖规则层
    $("#filter_bg2").click(function () {
        $(this).hide();
        $("#lottery_role_dialog").hide();
    });

    //关闭中奖弹窗
    $("#closeBtn").click(function () {

        $("#filter_bg").fadeOut();
        $("#lottery_dialog").fadeOut();

    });

    /**
     *旋转转盘
     * @param item 奖品ID
     * @param data 奖品数据
     */
    var rotateFn = function rotateFn(result,s) {

        var angles = result.winningUser.prize_id * (360 / turnplate.restaraunts.length) - 360 / turnplate.restaraunts.length;

        if (angles < 250) {

            angles = 250 - angles;
        } else {
            angles = 360 - angles + 250;
        }

        $('#wheelcanvas').stopRotate();

        $('#wheelcanvas').rotate({
            angle: 0,
            animateTo: angles + 1800,
            duration: 4700,

            callback: function callback() {

                var data = result.winningUser;

                //还原状态
                $('.pointer').removeClass('lotteryIng').children('img').hide();

                lottery_id = data.prize_id;

                //重新设置剩余抽奖次数
                var p = parseInt(number_container.text()) - 1;

                number_container.text(s.new_count);

                lotteryIng = false;

                // console.log(data.original_img);

              //  var a = 'http://192.168.0.124/lottery/award/60/3.png';  //data.original_img

                var html = ' <img src="' + data.original_img + '"><div class="lot_msg"><h5>' + data.copywriter1 + '</h5><p>' + data.copywriter2 + '</p></div>';
                $("#nodeInfo").html(html);

                $("#filter_bg").fadeIn();
                $("#lottery_dialog").show();

                turnplate.bRotate = false;
            }
        });
    };


    String.prototype.format = function () {

        if (arguments.length == 0) return this;

        for (var s = this, i = 0; i < arguments.length; i++)

            s = s.replace(new RegExp("\\{" + i + "\\}", "g"), arguments[i]);

        return s;

    };


    var showLoading = function showLoading(infoText) {
        infoText = infoText || 'loading…';
        $.dialog({
            type: 'info',
            align: 'center',
            infoText: infoText,
            infoIcon:loading,
            autoClose: 0
        });
    };

    var lottery = function lottery() {

        $('.pointer').click(function () {

            /* if (!app_token) {
             $.alerts.alertTo('亲，您还未登录哦', function () {
             window.location.href = registerUrl;
             });
             return;
             }*/

            if (number_container.text() == 0) {
                $.alerts.alertTo('亲，您的抽奖机会已经用完啦', function () {
                });
                return;
            }

            if (lotteryIng) {
                mui.toast('正在抽取奖品中，请等待...', {duration: 'long', type: 'div'});
                return;
            }

            lotteryIng = true;

            var _this = this;

            $(_this).addClass('lotteryReady');

            if (turnplate.bRotate) return;
            turnplate.bRotate = !turnplate.bRotate;

            isLotteryEnd += 1;

            /**
             * 抽奖结果回调
             */
            var lotteryResult = function (m) {

                if (m.status == 200) {
                    rotateFn(m.result,m);
                    return;
                }

                var msg = '';

                switch (m.status) {
                    case  202:
                        msg = '抽奖活动即将开始，请等待!';
                        break;

                    case  203:
                        msg = '活动还未开始呢，请耐心等待哦!';
                        break;


                    case  205:
                        msg = '抽奖活动已结束，请等待下一期!';
                        break;

                    default:

                        msg = '服务器繁忙，请稍后再试';
                        break;
                }

                if (msg) {

                    $.alerts.alertTo(msg, function () {

                    });
                    lotteryIng = false;
                    turnplate.bRotate = false;
                    clearTimeout(timeOut);
                    $('.pointer').removeClass('lotteryIng lotteryReady').children('img').hide();
                    return;
                }
            }

            $.ajax({
                type: "post",
                url: startLottery,
                //    headers: {"mifengToken": '', "Content-Type": "text/plain;charset=UTF-8"},
                data: {
                    type: lotteryType
                },
                dataType: "json",
                timeout: 3000,
                success: function (data) {

                    $.dialog.close();
                    timeOut = setTimeout(function () {
                        $('#pointer').removeClass('lotteryReady').addClass('lotteryIng').find('img').show();
                        lotteryResult(data);
                    }, 400);

                },
                beforeSend: function () {

                    showLoading('正在准备抽奖...');
                    //  mui.toast('准备抽奖，请稍后...', {duration: 1000, type: 'div'});
                },
                error: function () {

                    $.dialog.close();
                    $.alerts.alertTo('服务器繁忙，请稍后再试', function () {
                    });

                    lotteryIng = false;
                    turnplate.bRotate = false;
                    clearTimeout(timeOut);
                    $('.pointer').removeClass('lotteryIng lotteryReady').children('img').hide();
                }
            });
        });
    };

});


var get_canvas = function (img) {

    var canvas = document.createElement("canvas");

    canvas.width = img.width;

    canvas.height = img.height;

    var ctx = canvas.getContext("2d");

    ctx.drawImage(img, 0, 0, img.width, img.height);

    return canvas;

}


function drawRouletteWheel() {


    var canvas = document.getElementById("wheelcanvas");

    if (canvas.getContext) {

        //根据奖品个数计算圆周角度

        var arc = Math.PI / (turnplate.restaraunts.length / 2);

        var ctx = canvas.getContext("2d");

        //在给定矩形内清空一个矩形

        ctx.clearRect(0, 0, 480, 480);

        //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式

        ctx.strokeStyle = "#FFBE04";

        //font 属性设置或返回画布上文本内容的当前字体属性

        ctx.font = '18px Microsoft YaHei';


        for (var i = 0; i < turnplate.restaraunts.length; i++) {

            var angle = turnplate.startAngle + i * arc;

            ctx.fillStyle = turnplate.colors[i];

            ctx.beginPath();

            //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）

            ctx.arc(241, 241, turnplate.outsideRadius, angle, angle + arc, false);

            ctx.arc(241, 241, turnplate.insideRadius, angle + arc, angle, true);

            ctx.stroke();

            ctx.fill();

            //锁画布(为了保存之前的画布状态)
            ctx.save();

            //----绘制奖品开始----
            ctx.fillStyle = "#63544C";

            var goods = turnplate.restaraunts[i];

            var text = (goods.goods_name);

            if (text) {

                text = (text).substring(0, 8);

                ctx.translate(241 + Math.cos(angle + arc / 2) * turnplate.textRadius, 241 + Math.sin(angle + arc / 2) * turnplate.textRadius);

                ctx.rotate(angle + arc / 2 + Math.PI / 2);

                ctx.fillText(text, -ctx.measureText(text).width / 2, 0);

                img = goods.original_img;


                var img2 = new Image();

                img2.src = img;

                img2 = get_canvas(img2);

                ctx.drawImage(img2, -20, 15);

                ctx.restore();
            }

            ctx.restore();

        }


    }

}



