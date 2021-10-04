/**
 * タブのついたスライドビュー（画面下からでてくる）
 * 事前準備
 * ①my_slideクラスを用意しておく
 * ②my_slideクラス要素内に表示するコンテンツを書いておく
 * ③呼び出し元コントローラにslideSetUp()メソッド（データ設定メソッド）を書いておく
 * 
 * ページ読み込み後にmy_slide内にタブとボディが作成され、
 * ボディ内に全てのコンテンツが格納される
 * 
 * 隠れている状態でタブだけ見える
 * 
 * --setSlide()---my_slideクラスの要素から呼び出す初期設定メソッド--
 * ・overlay:オーバーレイ使用フラグ
 * 		（使用する場合はページでoverlay.jsを読み込む必要あり）
 * 
 * --slideOpen()---my_slideクラスの要素から呼び出すopenメソッド-----
 * setSlide()でcloseFuncにtrueを指定した場合は
 * スライドアップ処理に各コントローラ独自メソッド「$.slideOpenFunc()」を呼び出される
 * 各コントローラには「$.slideOpenFunc()」を必ず実装する
 * 
 * --slideClose()---my_slideクラスの要素から呼び出すcloseメソッド-----
 * setSlide()でcloseFuncにtrueを指定した場合は
 * 処理の最後に各コントローラ独自メソッド「$.slideCloseFunc()」が呼び出される
 * 各コントローラには「$.slideCloseFunc()」を実装しておく
 */
jQuery(function ($) {
	//my_slide
	var mySlide = $(".my_slide");
	
	//タブエリア
	var tab = $("<div>").attr("class","my_slide_tab clearfix")
				.append($("<img>").attr("class","right_icon"));
	//ボディエリア
	var body = $("<div>").attr("class","my_slide_body");
	
	//my_slide内の全ての要素をボディに移動
	$.each(mySlide.children(),function(index,val){
		body.append(val);
	});
	
	//タブとボディをmy_slideに追加
	mySlide.append(tab).append(body);
	/*--ボディ高さ調整------------*/
	var maxHeight = $("body").height() *0.8;
	if (body.height() > maxHeight) {
		body.height(maxHeight);
	}
	/*--------------------------*/
	//各コントローラ独自のセットアップメソッド呼び出し
	mySlide.slideSetUp();
});

(function($) {
	//セットアップ
	$.fn.setSlide = function(options) {
		var setting = $.extend({
			//hide時タブイメージ
			hideTabImg : "../image/up.png",
			//open時タブイメージ
			openTabImg : "../image/down.png",
			//オーバレイ使用フラグ（デフォルトは使用）
			overlay : true,
			//openメソッド呼び出しフラグ
			openFunc : false,
			//closeメソッド呼び出しフラグ
			closeFunc : false,
			//open速度
			openTime : 100,
			//close時速度
			closeTime : 100
		},options);
		
		/*--データ設定------------------*/
		this.data("hide_img",setting.hideTabImg)
			.data("open_img",setting.openTabImg)
			.data("openFunc",setting.openFunc)
			.data("closeFunc",setting.closeFunc)
			.data("openTime",setting.openTime)
			.data("closeTime",setting.closeTime);
		
		var overlay = null;
		if (setting.overlay) {
			overlay = new $.overlay({click_close:false});
		}
		this.data("overlay",overlay);
		//
		/*--------------------------------*/
		
		/*--読み込み時初期設定（画面下部に隠す）---------*/
		//var left = ($("body").width() - this.width()) /2;
		var mbh = $(".my_slide_body").outerHeight();//スライドボディ部のheight
		
		this.css({/*"left":left,*/"bottom":-mbh})//left,bottom
			.data("base_height",mbh)//slidebodyの初期高さを保存
			.data("open",false)//openフラグ
			.find(".my_slide_tab img").attr("src",this.data("hide_img"));//タブイメージ
		
		/*---------------------------------------------*/
		
		//タブクリック
		$(".my_slide_tab img").on("click",function(e){
			var mySlide = $(this).parents(".my_slide");

			if (mySlide.data("open") == false) {
				mySlide.slideOpen();
			} else {
				mySlide.slideHide();
			}
		});
	}
	//open
	$.fn.slideOpen = function() {
		
		/*--chromeなどで表示時にスクロールが効かない場合の対策--*/
		var slideBody = this.find(".my_slide_body");
		slideBody.css("overflow", "");
		setTimeout(function(){
			slideBody.css("overflow", "scroll");
		}, 100);
		/*----------------------------------*/
		
		//表示
		this.smoothtTranslate(0,-this.data("base_height"),0,this.data("openTime"));
		//this.animate({"left":left,/*"top":top*/"bottom":0},/*100*/this.data("openTime"));
		//タブイメージ切り換え
		this.find(".my_slide_tab img").attr("src",this.data("open_img"));
		//openフラグ
		this.data("open",true);
		//オーバーレイ表示
		if (this.data("overlay")) {
			this.data("overlay").open();
			//確実に画面内に表示する
			$("#pageOverlay0").css("position","fixed");
		}
		//各コントローラ独自のメソッド
		if (this.data("openFunc")) {
			$.slideOpenFunc();
		}
		
		//ナビゲーション表示タブを無効
		$("#nav_tab a").prop("disabled",true);
		//ナビゲーション表示タブより上に持ってくる
		this.css("z-index",20);
		
		//body,htmlのスクロールを一時的に禁止
		$("body, html").css("overflow","hidden");
		
		return this;
	}
	//hide
	$.fn.slideHide = function(options) {
		//各コントローラ独自のメソッド
		if (this.data("closeFunc")) {
			$.slideCloseFunc();
		}
		
		//body,htmlのスクロール禁止を解除
		$("body, html").css("overflow","visible");
		
		var setting = $.extend({
			time:this.data("closeTime")
		},options);
		
		//非表示(初期値に戻すためsmoothtTranslate()に0を指定)
		this.smoothtTranslate(0,0,0,setting.time);
		//this.animate({"left":left,"bottom":bottom},setting.time);
		//タブイメージ切り換え
		this.find(".my_slide_tab img").attr("src",this.data("hide_img"));
		//openフラグ
		this.data("open",false);
		//オーバーレイ非表示
		if (this.data("overlay")) {
			this.data("overlay").close();
		}
		
		//ナビゲーション表示タブを有効
		$("#nav_tab a").prop("disabled",false);
		//ナビゲーション表示タブの下に潜らせる
		this.css("z-index",10);
		
		return this;
	}
}(jQuery));

