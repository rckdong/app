var windowScrollTop;
$(function () {

    $("#M_more").click(function () {
        var scrollTop = $(window).scrollTop();							// 滚动条高度
        var menu = $(this).find("i");
        if (menu.hasClass("M_icon_close-w")) {
            menu.removeClass("M_icon_close-w").addClass("M_icon_nav");
            $("#M_header_nav").hide();
            topTzClose();//导航关闭
        } else {
            menu.removeClass("M_icon_nav").addClass("M_icon_close-w");
            $("#M_header_nav").show();
            topTZBlock();//导航条高度调整
        }
    });

    // $("#M_header_nav").bind("touchstart", function () {
    //     $("#M_more i").removeClass("M_icon_close-w").addClass("M_icon_nav");
    //     $("#M_header_nav").removeClass("M_posf").animate({opacity: "0", height: "0"}, 300);
		// $(".M_scrollHidden").removeAttr("style");
		// //document.removeEventListener("touchmove",touchMove);
    // });

    // $("#tip_close").click(function () {
    //     // $(this).animate({
    //     //     height: "0",
    //     //     padding: "0 12px 0 12px"
    //     // }, "slow", function () {
    //     //     $(this).hide();
    //     // });
    //     $(this).hide();
    // });

    $(".M_price-bg").bind("click", function () {
        $(".M_layer").css("display", "none");
        $(this).css("display", "none");
        $("#M_order").removeClass("current");
        $("#cityBox").hide();
		$(".M_scrollHidden").removeAttr("style");
		$(".M_main").removeAttr("style");
		$(".sxBox").hide();
		
		$(".sx").removeClass("current");
        $(".sx em").removeClass("M_icon_arrow-ub");
		
		$(".M_levelWrap").hide();
		$(this).removeAttr("style");
		
    });

    $("#pop_brand").click(function () {
		popBrandTop();
        $(".U_pop_brand").css("display", "block");
		$(".M_scrollHidden").css("overflow","hidden");
		$(".M_header").eq(1).hide();
		$(".M_select_btn").eq(1).hide();
		//$(".M_main").eq(0).css("display","none");
		//$(".M_download-tip").css("display","none");
		
		//document.addEventListener("touchmove",touchMove,isPassive() ? {
//			capture: false,
//			passive: false
//		} : false);
//		scroll5.refresh();
		
    });

    $("#select_close").click(function () {
        $(".U_pop_brand").css("display", "none");
		$(".M_scrollHidden").removeAttr("style");
		$(".M_header").eq(1).show();
		$(".M_select_btn").eq(1).show();
		//$(".M_main,.M_download-tip").removeAttr("style");
		//document.removeEventListener("touchmove",touchMove);
	});
	
	$("#go_close").click(function () {
		$(".M_brand-tip").css("display","none");
	});
	
    //增加一个class='sx'
    $(".sx").click(function () {
        var showid = $(this).attr("show-id");// 获取自定义属性show-id='sxBox2'
        if ($(this).hasClass("current")) {//close
            $(this).siblings().removeClass("current");
            $(this).find("em").removeClass("M_icon_arrow-ub");
            $(this).siblings().find("em").removeClass("M_icon_arrow-ub");
            $(this).removeClass("current");
            $(".sxBox").hide(); //之前要设定class='sxBox'
            $(".M_price-bg").hide();
			
			$(".M_main").removeAttr("style");
            topTzClose();
        } else {//open
            $(this).addClass("current");
            $(this).siblings().removeClass("current");
            $(this).find("em").addClass("M_icon_arrow-ub");
            $(this).siblings().find("em").removeClass("M_icon_arrow-ub");
            $(".sxBox").hide();//先隐藏所有
            $("#" + showid).show();//再显示当前指定的
            $(".M_price-bg").css("z-index", "10").show();
			// $(".M_main").css({"height":"100%","overflow":"hidden"});
            topTZBlock();
        }
    });

    //列表页
    $(".see_select").bind('click',function(){
        $(this).parent().parent().find(".M_icon_select").removeClass('M_icon_select');
        $(this).find(".M_icon").addClass('M_icon_select');
    });

    //隐藏车商电话列表
    $(".M_pop-bg").bind('click',function () {
        hidePhone();
    });
	
    // 关闭
    $(".M_download-tip .close").click(function(){
        $(".M_download-tip").remove();
        topTZBlockNone();
		
		// 新增加
		$(".M_scrollHidden").removeAttr("style");
    });

	// 滚动跟随
	if ($(".M-h").length) {
		
		$(window).bind("touchend",function(){
		
			if ($(".M_header").length && $(".M_scrollHidden").css('overflow') != 'hidden'){
				var headerHeight = $(".M_header").height();						// 导航高度
				var mhOffsetTop = $(".M_header").eq(-1).parent().offset().top;	// 导航距离顶部距离
				var scrollTop = $(window).scrollTop();							// 滚动条高度
				if ( mhOffsetTop > scrollTop  ) {
					$(".M_header").removeClass("M_posf");
				}
				
				else {
					$(".M_header").addClass("M_posf");
				}

                if($(".M_scrollHidden").css('overflow') != 'hidden') {
                    if ($(".M_main_menu").length) {
                        if (mhOffsetTop > scrollTop) {
                            $(".M_main_menu").removeClass("M_posf");
                        } else {
                            $(".M_main_menu").addClass("M_posf");
                        }
                    }
                }
			}
		});
        $(window).bind("scroll",function(){
            if($(".M_scrollHidden").css('overflow') != 'hidden') {
                if ($(".M_header").length) {
                    var headerHeight = $(".M_header").height();						// 导航高度
                    var mhOffsetTop = $(".M_header").eq(-1).parent().offset().top;	// 导航距离顶部距离
                    var scrollTop = $(window).scrollTop();							// 滚动条高度
                    if (mhOffsetTop > scrollTop) {
                        $(".M_header").removeClass("M_posf");
                    }
                    if ($(".M_main_menu").length) {
                        if (mhOffsetTop > scrollTop) {
                            $(".M_main_menu").removeClass("M_posf");
                        }
                    }
                }
            }
        });
	}
    $(".M_header").bind("touchmove",function(e){
        e.preventDefault();
    });

    $(".M_main_menu").bind("touchmove",function(e){
        e.preventDefault();
    });

    $(".M_price-bg").bind("touchmove",function(e){
        e.preventDefault();
    });


    // 条件选车-推荐弹层 finish
	function popBrandTop(){
		if($(".U_pop_brand").length){
			var headerHeight = $(".M_header").height();
			$(".U_pop_brand-list").css({"top":headerHeight + 1,"padding-bottom":89});
		}
	}
	
	// 报价详情-更多配置显示
	$("#peizhiMore").click(function(){
		$(this).prev().css("height","auto");
		$(this).hide();
		$(this).next().show().attr("href","#cspz");
	});
	
	$("#retract").click(function(){
		$(this).prev().show();
		$("#peizhiList").removeAttr("style");
		$(this).hide();
		$(window).scrollTop($("#cspz").offset().top);
	});
	
	
	// 报价详情-图片居中
	$(".img-position").each(function(){
		var src = $(this).attr("_src");
		$(this).attr("src",src);
		this.onload = function(){
			var imgHeight = Math.round($(this).height());
			var imgMarginTop = Math.round(imgHeight / 2);
			$(this).css({"margin-top":-imgMarginTop,"height":imgHeight});
			
			// 活动资讯-获取标题背景高度
			var height = Math.round($(".img-height").height());
			$(".M_promotion_headerTitle").css("height",height);
			
			// 微店首页-获取标题背景高度
			$(".M_dealer_salerTop").css("height",height);
		};
	});
	
	$(".img-box").each(function(){
		var imgWidth = $(this).width();
		var imgHeight = Math.round(imgWidth * 9 / 16);
		$(this).css({"width":imgWidth,"height":imgHeight});
	});
	
	$(".M_price-imgBox").each(function(){
		var imgWidth = $(this).width();
		var imgHeight = Math.round(imgWidth * 4 / 7);
		$(this).css({"width":imgWidth,"height":imgHeight});
	});
	
	// 条件选车-类型弹层
	if($(".M_level").length){
	
		$(".M_level li").click(function(){
			var levelOffsetTop = $(".M_level").offset().top;
			var levelPosB = levelOffsetTop + 96;
			var levelPosT = levelOffsetTop - 142;
			var scrollTop = $(window).scrollTop();
			var levelWidth = $(this).width();		

			if($(this).index() == 0){
				$(".M_price-bg").show().css("z-index",22);
				if(levelOffsetTop - 142 >= scrollTop){
					$("#levelCar").show().css("top",levelPosT);
					$("#levelCar em").removeClass("up").addClass("down").css("left",levelWidth / 2 - 12);
				} else {
					$("#levelCar").show().css("top",levelPosB);
					$("#levelCar em").removeClass("down").addClass("up").css("left",levelWidth / 2 - 12);
				}

			} else if($(this).index() == 1){
				$(".M_price-bg").show().css("z-index",22);
				if(levelOffsetTop - 142 >= scrollTop){
					$("#levelSuv").show().css("top",levelPosT);
					$("#levelSuv em").removeClass("up").addClass("down").css("left",levelWidth * 3 / 2 - 12);
				} else {
					$("#levelSuv").show().css("top",levelPosB);
					$("#levelSuv em").removeClass("down").addClass("up").css("left",levelWidth * 3 / 2 - 12);
				}

			} else {
				// 无弹层提示
			}
		});
		
	}
	
	// 右侧字母跟随浮动
	// 获取当前高度
	(function(){
		var height = $(".M_rightFix").height();
		var marginTop =  $(".M_rightFix").height() / 2;
		$(".M_rightFix").css({"height":height,"margin-Top":-marginTop});
	})();
	

    var t = setTimeout(function(){},100)
    $(".M_rightFix ul").on("touchstart touchmove","li",function(e){
        var x = e.originalEvent.targetTouches[0].clientX
        var y = e.originalEvent.targetTouches[0].clientY
        var obj = document.elementFromPoint(x,y)
        var letter = $(obj).text();
        var index = $(obj).text() + "0";

        var offset = 0;
        if ( index == "＃0" ) {
            index = "#0";
        }
        if ( index == "#0" ){
            index = "AA";
        }
        if($(obj).hasClass("jump-li")){
            var offset = $("."+index).offset().top;
            if( index == 'AA' && ($(obj).index()==0)) {
                offset = 0;
            }
            $(window).scrollTop(offset);
            $("#letter_middle").text(letter).show();
            clearTimeout(t)
            t = setTimeout(function(){
                $("#letter_middle").hide();
            },300)
        }

        e.preventDefault();
        e.stopPropagation();
    });

});


//列表页
//点击省
function provinceClick(obj){
    var id = $(obj).attr('data_value');
    $.ajax({
        url:"/area/cityajax",
        type:'get',
        data:{id:id},
        dataType:'text',
        success:function (r) {
            $(".M_price-bg").css('z-index',12).show();
            $("#cityBox ul").html(r)
            $("#cityBox").show().css('z-index',13);
			$("#sxBox3").hide();
			//scroll4.refresh();
        }
    })
}
//点击市
function cityClick(obj) {
    var id = $(obj).attr("data_value");
    var clickName = $(obj).attr("data_name");
    if($(obj).hasClass('current')){
        $(obj).siblings().removeClass("current");
        $(obj).siblings().find("i").removeClass("M_icon_arrow-uw");
        $(obj).find("i").removeClass("M_icon_arrow-uw");
        $(obj).removeClass("current");
        $(obj).children("ul").hide();
		//scroll4.refresh();
    }else{
        $.ajax({
            url:"/area/countyajax",
            type:'get',
            data:{id:id},
            dataType:"json",
            success:function(r){
                var str = '<li data_name="'+clickName+'" data_key="cityid" data_value="'+id+'" onclick="selectArea(this)"> <a href="javascript:void(0);">全市</a> </li>';
                for(var i=0; i<r.county.length;i++){
                    str += '<li data_name="'+r.county[i].name+'" data_key="countyid" data_value="'+r.county[i].id+'" onclick="selectArea(this)"> <a href="javascript:void(0);">'+r.county[i].name+'</a> </li>';
                }
                $(obj).children("ul").html(str)
                $(obj).addClass("current");
                $(obj).siblings().removeClass("current");
                $(obj).siblings().find("i").removeClass("M_icon_arrow-uw");
                $(obj).find("i").addClass("M_icon_arrow-uw");
                $(obj).children("ul").show();
				//scroll4.refresh();
            }
        })
    }
}
//隐藏
function hideLayer() {
    $(".sxBox").hide();
    $(".M_price-bg").hide();
    $(".sx").find("em").removeClass("M_icon_arrow-ub");
    $(".sx").removeClass("current");
	$(".ajax_con").removeAttr("style");
	$(".M_scrollHidden").removeAttr("style");
}
function back(){
    var url = document.referrer;
    if(url.indexOf('m.maiche168.com') !=-1){
        history.go(-1)
    }else{
        window.location.href="http://chexinyuan.com/index.php/wap/index/index";
    }
}
//显示电话
function showPhone(uid) {
    $.ajax({
        url:"/wap/index/get_phones",
        type:'get',
        dataType:"json",
        success:function(r){
            if(r.length>0){
                if(r.length == 1){
                    window.location.href="tel:"+r[0].phone;
                }else{
                    var str = '';
                    for(var i=0; i<r.length; i++){
                        str += "<li><a href='tel:"+r[i].phone+"'>"+r[i].title+"："+r[i].phone+"</a></li>";
                    }
                    $(".M_pop_tel>ul").html(str);
                    $(".M_pop-bg").show();
                    $(".M_pop_tel").show();
                    $(window).bind('touchmove',function(e){e.preventDefault();});
                }
            }
        }
    })
}

//显示电话
function showPhone2() {
    if($(".M_pop_tel ul li").length==1){
        var tel = $(".M_pop_tel li a").attr('href');
        window.location.href = tel;
        return false;
    }
    $(".M_pop-bg").show();
    $(".M_pop_tel").show();
    $(window).bind('touchmove',function(e){e.preventDefault();});
}

//隐藏电话
function hidePhone() {
    $(".M_pop-bg").hide();
    $(".M_pop_tel").hide();
    $(window).off("touchmove");
}
//网站统计
function visit(uid, infoid, type) {
    var url = "/site/visit?uid="+uid+"&infoid="+infoid+'&type='+type;
    $.get(url,function(){});
}

// 禁止body滚动
function touchMove(e){
	e.preventDefault();
}

function isPassive() {
    var supportsPassiveOption = false;
    try {
        addEventListener("test", null, Object.defineProperty({}, 'passive', {
            get: function () {
                supportsPassiveOption = true;
            }
        }));
    } catch(e) {}
    return supportsPassiveOption;
}

//关闭弹层时,导航恢复高度
function topTzClose() {
    var show1 = $(".M_header_nav").css('display') == 'none';
    var show2 = true;
    if($(".M_price-bg").length){
        show2 = $(".M_price-bg").css('display') == 'none';
    }
    if(show1 && show2) {

        $(".M_header").css("transform", "translateY(0px)");
        if ($(".M_download-tip").length) {
            $(".M_download-tip").css("transform", "translateY(0px)");
        }
        if ($(".M_main_menu").length) {
            $(".M_main_menu").css("transform", "translateY(0px)");
        }
        $(".M_scrollHidden").removeAttr("style");
        if (windowScrollTop > 0) {
            $(window).scrollTop(windowScrollTop);


            if ($(".M_header").length) {
                var mhOffsetTop = $(".M_header").eq(-1).parent().offset().top;	// 导航距离顶部距离
                var scrollTop = windowScrollTop;							// 滚动条高度
                if (mhOffsetTop > scrollTop) {
                    $(".M_header").removeClass("M_posf");
                } else {
                    $(".M_header").addClass("M_posf");
                }

                if ($(".M_main_menu").length) {
                    if (mhOffsetTop > scrollTop) {
                        $(".M_main_menu").removeClass("M_posf");
                    } else {
                        $(".M_main_menu").addClass("M_posf");
                    }
                }
            }
        }
    }
}

//打开弹层时导航固定高度
function topTZBlock(){
    if($(".M_scrollHidden").css('overflow') != 'hidden') {
        var mhOffsetTop = $(".M_header").eq(-1).parent().offset().top;	// 导航距离顶部距离
        var scrollTop = $(window).scrollTop();							// 滚动条高度
        windowScrollTop = scrollTop;
        var M_headerTop;//导航高度
        var headerNavTop;//导航弹层高度
        if (scrollTop < mhOffsetTop) {
            M_headerTop = -scrollTop;
            if ($(".M_download-tip").length) {
                $(".M_download-tip").css("transform", "translateY(-" + scrollTop + "px)");
            }
            $(".M_header").css("transform", "translateY(" + M_headerTop + "px)");
            headerNavTop = $(".M_header").eq(-1).height() + $(".M_header").eq(-1).offset().top;
        } else {
            M_headerTop = -mhOffsetTop;
            $(".M_header").css("transform", "translateY(" + M_headerTop + "px)");
            headerNavTop = $(".M_header").eq(-1).height();
        }
        $(".M_header_nav").css("transform", "translateY(" + (headerNavTop + 1) + "px)");
        //条件条的高度
        if ($(".M_main_menu").length) {
            $(".M_main_menu").css("transform", "translateY(" + M_headerTop + "px)");
            M_modelsPos();
        }
        $(".M_scrollHidden").css("overflow","hidden");
        $(".M_posf").removeClass("M_posf");
    }
}
function topTZBlockNone(){
        var mhOffsetTop = $(".M_header").eq(-1).parent().offset().top;	// 导航距离顶部距离
        var scrollTop = $(window).scrollTop();							// 滚动条高度
        windowScrollTop = scrollTop;
        var M_headerTop;//导航高度
        var headerNavTop;//导航弹层高度
        if (scrollTop < mhOffsetTop) {
            M_headerTop = -scrollTop;
            if ($(".M_download-tip").length) {
                $(".M_download-tip").css("transform", "translateY(-" + scrollTop + "px)");
            }
            $(".M_header").css("transform", "translateY(" + M_headerTop + "px)");
            headerNavTop = $(".M_header").eq(-1).height() + $(".M_header").eq(-1).offset().top;
        } else {
            M_headerTop = -mhOffsetTop;
            $(".M_header").css("transform", "translateY(" + M_headerTop + "px)");
            headerNavTop = $(".M_header").eq(-1).height();
        }
        $(".M_header_nav").css("transform", "translateY(" + (headerNavTop + 1) + "px)");
        //条件条的高度
        if ($(".M_main_menu").length) {
            $(".M_main_menu").css("transform", "translateY(" + M_headerTop + "px)");
            M_modelsPos();
        }
    $(".M_scrollHidden").css("overflow","hidden");
}
//dd
function M_modelsPos(){
    setTimeout(function(){
        var Model =  $(".M_models_con");
        var M_modelsTop =  $(".M_main_menu").offset().top+ $(".M_main_menu").height();
        Model.css({"position":"absolute","top":M_modelsTop,"bottom":0});
        $("#cityBox").css({"position":"absolute","top":M_modelsTop,"bottom":0});
    },50)
}
