/**
 * 右から出てくるスライドビュー
 * 
 * 事前準備
 * ①#container直下に<div id="right_slide">を用意しておく
 * ②#right_slideのなかに表示させるコンテンツを用意しておく（複数可）
 * --コンテンツが１つの場合はidに"rs_contents"を指定しておく
 * --コンテンツが複数の場合はそれぞれにidをふっておく
 * ③コンテンツはすべて非表示にしておく
 * 
 * ページ読み込み後に#right_slide内にタブとボディが作成され、
 * ボディ内に全てのコンテンツが格納される
 * 
 * --open()--------
 * コントローラから呼び出す
 * 引数（options）
 * dispContentsId:表示するコンテンツのid(デフォルトは"rs_contents")
 * など
 * ----------------
 * 
 * --close()--------
 * 
 * ----------------
 */
jQuery(function ($) {	
	
	//right_slide
	var slide = $("#right_slide");
	//最大幅をデータに保存
	slide.data("max_w",350);
	
	//width初期値を設定
	slide.outerWidth(slide.getWidth());
	
	
	//closeボタンタブ
	var img = $("<img>")
			.attr({"src":"../image/close_2.png","class":"right_icon"})
			.on("click",function(){ slide.close(); });
	var title = $("<div>").attr("id","right_slide_title");
	var tab = $("<div>")
			.attr("class","close_tab clearfix").append(img).append(title);
	
	//ボディ
	var body = $("<div>").attr("class","right_slide_body");
	body.append($("<div>").attr("id","right_slide_tab_space"));
	//right_slide内の全ての要素をボディに移動
	$.each(slide.children(),function(index,val){
		body.append(val);
	});
	body.append($("<div>").attr("class","right_slide_tab_space"));
	
	slide.append(tab).append(body);
	
	//overlayをセット
	var overlay = new $.overlay({click_close:false});
	slide.data("overlay",overlay);
	
	slide.close({time:0});
});

(function($) {
	//幅
	$.fn.getWidth = function() {
		var w = $("body").width() *0.96;
		if (w > $(this).data("max_w")) {
			w = $(this).data("max_w");
		}
		return w;
	}
	
	//表示
	$.fn.open = function(options) {
		var setting = $.extend({
			//表示コンテンツ
			dispContentsId : "rs_contents",
			//幅指定
			width : null,
			//オーバレイ使用フラグ（デフォルトは使用）
			overlay : true,
			//open,close時メソッド呼び出しフラグ
			openFunc : false,
			closeFunc : false,
			//速度
			time : 100,
			//クローズ時まで保持される一時的なデータ
			data : null,
			//タイトル
			title : ""
		},options);
		
		//データを保存
		$(this).data("my_data",setting.data)
			.data("closefunc",setting.closeFunc);
		
		
		//openメソッドがある場合は実行
		if (setting.openFunc) { $.rsOpenFunc(); }
		
		/*--表示-----------*/
		//幅を設定 realWiindowHeight():@common.js
		if (setting.width) {
			$(this).width(setting.width);
		}else {
			$(this).outerWidth($(this).getWidth());
		}
		
		//タブスペースを表示
		$("#right_slide_tab_space").show();
		//タイトルを表示
		$("#right_slide_title").text(setting.title);
		//指定コンテンツを表示
		$("#"+setting.dispContentsId).css("display","block");
		
		var leftMargin = $("body").width()-$(this).outerWidth();
		$(this).smoothtTranslate(leftMargin,0,0,setting.time)
			.css("right",leftMargin -1);

		//overlay
		if (setting.overlay) { this.data("overlay").open(); }
		//ナビゲーション表示タブを隠す
		//$("#nav_tab").css("display","none");
		$("#nav_tab a").prop("disabled",true);
		/*-----------------*/
		
		//body,htmlのスクロールを一時的に禁止
		$("body, html").css("overflow","hidden");
		
		return $(this);
	}
	//非表示
	$.fn.close = function(options) {
		var setting = $.extend({
			//オーバレイ使用フラグ（デフォルトは使用）
			overlay : true,
			//速度
			time : 100
		},options);
		
		//body,htmlのスクロール禁止を解除
		$("body, html").css("overflow","visible");
		
		/*--非表示-----------------------*/
		//スライド
		$(this).smoothtTranslate($("body").width(),0,0,setting.time);
		//画面外に固定
		$(this).css("right",0 - $(this).outerWidth());
		
		//overlay
		if (setting.overlay) { this.data("overlay").close(); }
		
		//ナビゲーション表示タブを表示
		//$("#nav_tab").css("display","block");
		$("#nav_tab a").prop("disabled",false);
		
		//right_slideボディ内の全ての要素を非表示
		var mybody = $(this).find(".right_slide_body");
		
		$.each(mybody.children(),function(index,val){
			$(this).css("display","none");
		});
		/*------------------------------------*/
		
		//closeメソッドがある場合は実行
		if ($(this).data("closefunc")) { $.rsCloseFunc(); }
		//データを削除
		$(this).data("my_data",null);
		
		return $(this);
	}
}(jQuery));