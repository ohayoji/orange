/**
 *
 */
var myFooter;
var footerContents;
jQuery(function ($) {

});

(function($) {
	$.setMyFooter = function(options) {
		var setting = $.extend({
			name: "株式会社 turba",
			address: "",
			copyright: "Copyright 2015 © turba All Rights Reserved.",
			bgColor: "#364755",
			textColor: "white",
			footerNav: null,
			facebook: true,//Facebookページリンク表示フラグ
		},options);

		//フッター作成
		$("#container").append(
			'<footer id="my_footer">'+
				'<div id="footer_contents" class="responsive_area_orthodox">'+
				'<div class="footer_contents_box">'+
					'<p id="company_name">'+ setting.name +'</p>' +
					'<address>'+ setting.address +'</address>' +
				'</div>'+
				'<div class="footer_contents_box">'+
					'<p id="copyright">'+ setting.copyright +'</p>' +
				'</div>'+
				'</div>'+
			'</footer>'
		);



		myFooter = $("#my_footer");
		footerContents = myFooter.find("#footer_contents");

		//サイトラベル
		var siteLabel = $("#site_label");
		if (siteLabel.length > 0) {
			$("<div>").addClass("footer_contents_box").append(siteLabel).prependTo(footerContents);
		}

		//FaceBook
		if (setting.facebook) {
			$.setFacebook();
		}


		////console.log("links",setting.links);
		if (setting.footerNav) {
			//フッターナビゲーション設置
			$.setFooterNav(setting.footerNav);
			//footerNavのa要素の擬似スタイル作成
			$.setfooterNavDummyStyle(setting.textColor);
		}


		//兄要素の下padding
		myFooter.prev().css("padding-bottom",myFooter.outerHeight());
		//色設定
		myFooter.css({"background-color":setting.bgColor,"color":setting.textColor})
				.find("*").css("color","white");
	}


	//Facebookプラグイン設置
	$.setFacebook = function() {

		/*--Facebookプラグインに必要なタグの設置
		 * 通常はこのメソッド内で必要タグを作成するが、例外として、
		 * 画像多用などで読み込みが遅いページの場合は確実に表示するためにページに直接必要タグを書く
		 * --必要タグを直書きしているページ--
		 * index.php
		 * -------------
		 * -----------------------------------------------*/
		var fbroot = $("#fb-root");
		var fbscript = $('#fb-script');

		//fb-rootがない場合は作って設置
		if (fbroot.length == 0) {
			fbroot = $("<div>").attr("id","fb-root");
			//設置
			$("body").append(fbroot)
		}
		//JavaScriptSDKがない場合は作って設置
		if (fbscript.length == 0) {
			fbscript = $("<script>").text(
				'(function(d, s, id) {' +
					'var js, fjs = d.getElementsByTagName(s)[0];' +
					'if (d.getElementById(id)) return;' +
					'js = d.createElement(s); js.id = id;' +
					'js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";' +
					'fjs.parentNode.insertBefore(js, fjs);' +
				'}(document, "script", "facebook-jssdk"));'
			);
			//設置
			$("body").append(fbscript)
		}
		/*-----------------------------------------------*/


		//表示
		$("<div>").addClass("footer_contents_box").html(
				'<div class="fb-page" data-href="https://www.facebook.com/orange01.jp" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/orange01.jp"><a href="https://www.facebook.com/orange01.jp">Orange（オレンジ）スマホ 美容室 予約・売上管理</a></blockquote></div></div>'
				).css("height",270).prependTo(footerContents);
	}

	/*--フッターナビゲーション設置------------------
	 * setting.linksの形式（多次元配列）
	 * [
	 * 	{
	 * 		category:"カテゴリータイトル",
	 * 		links:[
	 * 				{ title:"タイトル", href:"pass/to/exsample.html"},
	 * 				{ title:"タイトル", href:"pass/to/exsample.html"},
	 * 			]
	 * 	},
	 * 	{
	 * 		category:"カテゴリータイトル",
	 * 		links:[
	 * 				{ title:"タイトル", href:"pass/to/exsample.html"},
	 * 				{ title:"タイトル", href:"pass/to/exsample.html"},
	 * 				{ title:"タイトル", href:"pass/to/exsample.html"},
	 * 			]
	 * 	},
	 * 	....
	 * ]
	 ---------------------------------*/
	$.setFooterNav = function(footerNav) {

		//var footerContents = myFooter.find("#footer_contents");
		var nav = $("<nav>").attr("id","footer_nav");

		//ナビゲーションカテゴリーの数だけリストを作成しリンクを埋め込む
		$.each(footerNav,function(index,val){
			////console.log("val",val);
			//footer_contents_box作成
			var dl = $("<dl>").addClass("footer_contents_box footer_links")
						.append($("<dt>").text(val.category));

			//リンク埋め込み
			$.each(val.links,function(i,v){
				var li = $("<dd>").append(
						$("<a>").attr({"href":v.href}).text(v.title)
				).appendTo(dl);
			});

			nav.append(dl);
		});
		//$("#footer_contents").prepend(nav);
		footerContents.prepend(nav);
	}
	//フッターのa要素の擬似スタイル作成（headタグ内に<style>を埋め込む）
	$.setfooterNavDummyStyle = function(color) {
		var css = '#my_footer a:LINK{color:'+color+'} #my_footer a:VISITED{color:'+color+'} #my_footer a:HOVER{text-decoration: underline;}';
		var style = document.createElement('style');
		style.appendChild(document.createTextNode(css));
		document.getElementsByTagName('head')[0].appendChild(style);
	}
}(jQuery));
