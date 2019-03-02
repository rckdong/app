$(function(){

	// tab 切换
	$(".tabBox").each(function(){
		$(this).children().eq(0).show();
	});

	$(".tabHd ul").on("touchstart","li",function(){
		$(this).siblings().removeClass("current");
		$(this).addClass("current");
		var index = $(this).index();
		var tabBox = $(this).parents(".statisticsWrap").find(".tabBox");
		tabBox.children().hide();
		tabBox.children().eq(index).show();
	});

	// 报价详情-图片居中
	$(".img-position").each(function(){
		var src = $(this).attr("_src");
		$(this).attr("src",src);
		this.onload = function(){
			var imgHeight = Math.round($(this).height());
			//var imgWidth = Math.round($(this).width());
			var imgMarginTop = Math.round(imgHeight / 2);
			//var imgMarginLeft = Math.round(imgWidth / 2);
			$(this).css({"margin-top":-imgMarginTop,"height":imgHeight});
		};
	});

	$(".img-box").each(function(){
		var imgWidth = $(this).width();
		var imgHeight = Math.round(imgWidth * 9 / 16);
		$(this).css({"width":imgWidth,"height":imgHeight});
	});

	//隐藏车商电话列表
	$(".pop_bg").bind('click',function(){
		hidePhone();
	});

});

//显示电话
function showPhone(uid) {
	if($(".pop_tel li").length==1){
		var tel = $(".pop_tel li a").attr('href');
		window.location.href = tel;
		return false;
	}
    $(".pop_bg").show();
    $(".pop_tel").show();
    $(window).bind('touchmove',function(e){e.preventDefault();});
}

//隐藏电话
function hidePhone() {
	$(".pop_bg").hide();
	$(".pop_tel").hide();
	$(window).off("touchmove");
}