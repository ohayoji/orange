/**
 * 
 */
jQuery(function ($) {
	//コンテナ直下にナビゲーション領域を設置
	$("#container").prepend(
		'<div id="home_header">'+
			'<div class="contents_area clearfix">'+
				'<img id="home_header_logo" class="logo" alt="" src="image/orange_logo_3.png">'+
				'<a id="home_nav_tab" href="javascript:void(0);">&#9776;</a>'+
				'<nav id="nav1"><ul></ul></nav>'+
				'<nav id="nav2"><ul></ul></nav>'+
			'</div>'+
		'</div>'
	);
	
	//ナビゲーション下のエリア上部にナビゲーション分のpadding
	var navHeight = $("#home_header").outerHeight();
	
	$(".after_orange_nav_area").css("padding-top",navHeight);
	$("#home_header_logo").on("click",function(){
		document.location = "https://orange01.jp/";
	})
});

(function($) {
	$.setNav = function(navContents) {
		var nav = $("#home_header nav");
		$.each(navContents,function(index,val){
			nav.find("ul").append(
					$("<li>").append(
						$("<a>").attr("href",val.url).text(val.name)));
		});
		//nav.hide();
		$("#home_nav_tab").on("click",function(){
			//ナビゲーションを表示
			nav.filter("#nav2").toggle();
		});
		$("nav a").on("click",function(){
			nav.filter("#nav2").hide();
		});
	}
	$.navContents = function(type) {
		if (type == "home") {
			return [
		            {url:"pages/login.php",name:"ログイン"},
		            {url:"#feature",name:"特徴"},
		            {url:"#other_func",name:"機能"},
		            //{url:"#feature_plan",name:"料金プラン"},
		            {url:"help/index.html",name:"ヘルプ"},
		            {url:"FAQ/index.html",name:"FAQ"},
		            ];
		}else if (type == "help") {
			return [
		            {url:"index.php",name:"Orangeホームへ"},
		            {url:"pages/login.php",name:"ログイン"},
		            ];
		}else if (type == "admission") {
			return [
		            {url:"index.php",name:"Orangeホームへ"}
		            ];
		}else {
			return [
		            {url:"index.php",name:"Orangeホームへ"}
		            ];
		}
	}
}(jQuery));

