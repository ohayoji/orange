/**
 * 
 */
jQuery(function ($) {
	
	
	//$(".free_days").text(_freeDays);
	
	var video = $("#orange_video_1");
	
	//overlayをセット
	var overlay = new $.overlay({click_close:function(){
		video.hide();
	}})
	
	
	$(".home_contents:odd").css("background-color","#f2f2f2");
	
	$("button.admission").on("click",function(){
		//document.location = "select_signup.php?admission_button_type="+$(this).data("admission_button_type");
		document.location = "admission.php?admission_button_type="+$(this).data("admission_button_type");
	}).text("いますぐ試してみる")/*.html(
			'<p class="admission_detail">プレミアムプラン９０日お試し無料！</p>いますぐ無料で試してみる'
		)*/;

	
	
	var navContents = $.navContents("home");
	$.setNav(navContents);
	
	$(".video_button_area").on("click",function(){
		
		
		overlay.open();
		
		//alert("youtube再生");
		
		var ww = $(window).width();
		var vw = video.width();
		var left = (ww - vw) / 2;
		
		
		
		//var vh = video.height();
		video.css({"left":left}).show();
		
	});
	
	/*--smartphone_view_images,pc_view_imagesのリンクなし項目の高さ調整--*/
	var linkH = $("#smartphone_view_images .help_link").outerHeight();
	//aを含まないfigure要素のpaddingを設定
	$("#smartphone_view_images figure:not(:has(a))").css("padding-bottom",linkH);
	$("#pc_view_images figure:not(:has(a))").css("padding-bottom",linkH);
	/*-------------------------------------------------------------*/
	//pc_view_images_opener
	var pcOpen = false;
	$("#pc_view_images_opener").on("click",function(){
		$("#pc_view_images").toggle();
		if (pcOpen == false) {
			$(this).text("more ×");
			pcOpen = true;
		}else {
			$(this).text("more ▽");
			pcOpen = false;
		}
	});
	
	/*--feature_multipaytype_link------*/
	/*$("a.feature_multipaytype_link").each(function(index,val) {
		
		var a = $(this);
		
		var type = a.attr("data-type");
		
		if (type == "staff") {//staff
			
			a.attr("href","help/index.html?visiter=salon&show_target=staff_setting").text("スタッフ設定");
		}else if (type == "rem"){//rem
			
			a.attr("href","help/index.html?visiter=salon&show_target=rem_setting").text("歩合パターン設定");
		}else {
			a.attr("href","help/index.html?visiter=salon&show_target=comp_tutorial").text("来店処理");
		}
	});*/
	/*----------------------------------*/
	
	/*--料金プランコンテンツ------------------*/
	$("#plan_price").text($.delimiting(_price));
	/*--------------------------------------*/
	
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	//navLinksの先頭にページ内リンクを追加
	navLinks.unshift({
		 category:"ページ内リンク", 
		 links:[
		        { title:"Orangeの特徴", href:"#feature"},
		        { title:"Orangeの機能", href:"#other_func"},
		        //{ title:"料金プラン", href:"#feature_plan"},
		        ]
	 });
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
	
	//完全無料に切り替え
	$.changeAllFreePlan();
});

(function($) {
	$.changeAllFreePlan = function() {
		//ナビの料金プランリンクを削除
		//$("#home_header").find("a[href=#feature_plan]").remove();
	}
}(jQuery));

