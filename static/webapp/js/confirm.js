(function ($) {

    $.alerts = {
        confirm: function (message, callback) {


            /*  console.log(callback);
              return;*/

            $.alerts._show(message, callback, 1);
        },

        alertTo: function (message, callback) {

            $.alerts._show(message, callback, 0);

        },
        _show: function (msg, callback, type) {

            var _html = "";

            var cancelStr = type ? '<input id="mb_btn_no" type="button" style="width: 49.5%; height: 100%; border: none; " value="取消" />' : '';

            _html += '<div id="mb_box"></div><div id="mb_con"><span id="mb_tit">提示</span>';
            _html += '<div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';
            _html += cancelStr;
            _html += '<input id="mb_btn_ok" style="color:red;" type="button" value="确定" />';
            _html += '</div></div>';
            //必须先将_html添加到body，再设置Css样式
            $("body").append(_html);
            GenerateCss(type);


            $("#mb_btn_ok").click(function () {

                //  $("body").hide();

                $.alerts._hide();
                callback ? callback(true) : '';
            });
            $("#mb_btn_no").click(function () {
                $.alerts._hide();
                //callback ? callback(false) : '';
            });
            $("#mb_btn_no").focus();
            $("#mb_btn_ok, #mb_btn_no").keypress(function (e) {
                if (e.keyCode == 13) $("#mb_btn_ok").trigger('click');
                if (e.keyCode == 27) $("#mb_btn_no").trigger('click');
            });
        },
        _hide: function () {
            $("#mb_box,#mb_con").remove();
        }
    }


    window.confirm = function (message, callback) {
        $.alerts.confirm(message, callback);
    };

    window.alert = function (message, callback) {

        $.alerts.alertTo(message, callback);
    };

    //生成Css
    var GenerateCss = function (type) {


        $("#mb_box").css({
            width: '100%', height: '100%', zIndex: '99999', position: 'fixed',
            filter: 'Alpha(opacity=60)', backgroundColor: '#000', top: '0', left: '0', opacity: '0.5'
        });

        $("#mb_con").css({
            zIndex: '999999', width: '80%', position: 'fixed',
            backgroundColor: 'White',
            borderRadius: '5px'
        });

        $("#mb_tit").css({
            display: 'block', fontSize: '16px', color: '#333', padding: '10px 15px',
            borderRadius: '5px',
            textAlign: 'center',
            fontWeight: 'bold'
        });

        $("#mb_msg").css({
            padding: '20px', lineHeight: '20px',
            borderBottom: '1px solid #ddd',
            fontSize: '14px',
            textAlign: 'center'
        })
        ;

        /* $("#mb_ico").css({
             display: 'block', position: 'absolute', right: '10px', top: '9px',
             border: '1px solid Gray', width: '18px', height: '18px', textAlign: 'center',
             lineHeight: '16px', cursor: 'pointer', borderRadius: '12px', fontFamily: '微软雅黑'
         });*/

        $("#mb_btnbox").css({
            margin: '0 auto',
            height: '40px'
        });

        var w = !type ? '100%' : '49.5%';

        $("#mb_btn_ok,#mb_btn_no").css({
            'width': w,
            'height': ' 100%',
            'border': 'none',
            backgroundColor: 'White',
            'border-radius': '0 0 0 5px',
            'font-size': '14px',
            // 'background': '#fff;'
        });

        $("#mb_btn_no").css({
            'border-right': ' 1px solid rgb(221, 221, 221)',
            'color': '#333;',
        });

        /*//右上角关闭按钮hover样式
        $("#mb_ico").hover(function () {
            $(this).css({backgroundColor: 'Red', color: 'White'});
        }, function () {
            $(this).css({backgroundColor: '#DDD', color: 'black'});
        });*/

        var _widht = document.documentElement.clientWidth; //屏幕宽
        var _height = document.documentElement.clientHeight; //屏幕高

        var boxWidth = $("#mb_con").width();
        var boxHeight = $("#mb_con").height();

//让提示框居中  
        $("#mb_con").css({top: (_height - boxHeight) / 2 + "px", left: (_widht - boxWidth) / 2 + "px"});
    }
})(jQuery);  