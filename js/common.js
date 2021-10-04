/**
 *
 */
//ページタイトルに対応するヘッダー用ヘルプリンク
var headerHelpLinks = [
                       {title:"予約帳", helpLink:"reserve_tutorial"},
                       {title:"伝票", helpLink:"comp_tutorial"},
                       {title:"売上明細", helpLink:"sales_specification"},
                       {title:"設定", helpLink:null},
                       {title:"日報", helpLink:"daily_report"},
                       {title:"サロン設定", helpLink:null},
                       {title:"スタッフ設定", helpLink:"staff_setting"},
                       {title:"手当管理", helpLink:"add_rems"},
                       {title:"月報", helpLink:"monthly_report"},
                       {title:"スタッフレポート", helpLink:"staff_report"},
                       {title:"トップ", helpLink:null},
                       {title:"支払管理", helpLink:"payment"},
                       {title:"会社設定", helpLink:null},
                       {title:"マイアカウント", helpLink:null},
                       {title:"給与控除管理", helpLink:"deductions"},
                       {title:"月報作成", helpLink:"create_report"},
                       ];
//ページタイトル
var pageTitle;
//通貨
var CURRENCY = "¥";

jQuery(function ($) {
	//visiterがない場合は強制ログアウト
	
	if (typeof(_sessionCheckCancel) == "undefined") {
		if (!_visiter) {
			alert("ログインされていないか、セキュリティ保護のためログアウトしました。もう一度ログインしてください。");
			document.location = "login.php?logout=true";
		}
	}


	//ログアウトアイコンを設定
	/*$("#logout").on("click",function(){
		document.location = "login.php?logout=true";
	});*/
	//#header_iconsをヘッダーに移動
	//$("#header_icons").appendTo("#header #icon_area");

	//rapTime = $.rapTime("common_end",rapTime);

	/*--page lock ----------------*/
	if (_visiter == "salon") {
		var pagesForSalon = $._getNavigation();
		var nav = $("#menu");
		$.each(pagesForSalon,function(pIndex,val){

			if (val.local_name != "ログアウト") {
				if (val.sub_menu) {
					$.each(val.sub_menu,function(i,v){
						$("#pagelink_"+v._name).data("local_name", v.local_name).setPagelockIcon(v);
					});
				}else {
					$("#pagelink_"+val._name).data("local_name", val.local_name).setPagelockIcon(val);
				}
			}

			/*$("#menu ul li a").each(function(index, element){
				
				var img = $("<img>").attr("src","../image/Lock Filled-100.png")
					.css({"height":20,"width":20,"float":"right"});

				if($(element).text() == val.local_name){
					$(element).data("local_name", val.local_name);
					if(val.id_in_locking_salon_pages == null){
						img.removeClass("lock");
						img.addClass("noLock");
					}
					else{
						img.removeClass("noLock");
						img.addClass("lock");
					}
					$(element).addClass("clearfix").append(img);
				}
			});*/
				
		});
		//ロック処理
		$("#menu ul li a").on("click", function(){
			
			if($(this).find("img.lock").length){
				var myPassword = prompt("パスワードを入力してください","");
				
				if(myPassword == _password){
					
					location.href = $(this).data("url");
				}else if (myPassword == null){
					//何もしない
					return false;
				}
				else{
					alert("パスワードが間違っています");
					return false;
				}
			}
		});


	}

	/*-------------------------*/

  //サロンごとに通貨を設定
  if (_postName == "REBIRTH") CURRENCY = "$";
});


(function($) {

	/*---header----------------------*/
	$._createHeader = function() {
		//ページタイトルセット
		pageTitle = $("title").text();
		
		document.write(
			'<div id="header" class="clearfix">'+
				'<div class="segment02 clearfix">'+
					'<div class="seg_contents" id="post_name">'+
						_postName+
					'</div>'+
					'<div class="seg_contents" id="page_title">'+
						//$("title").text()+
						pageTitle+
						'<div id="icon_area"></div>'+
					'</div>'+
				'</div>'+
			'</div>'
		);

		//#header_iconsをヘッダーに移動
		$("#header_icons").appendTo("#header #icon_area");
		
		$.setHelpLink();
	}
	//ヘルプリンク設置
	$.setHelpLink = function() {
		$.each(headerHelpLinks,function(index,val){
			//titleに対応するリンクを設置
			if (val.title == pageTitle && val.helpLink) {
				//伝票ページでスタッフログインの場合はキャンセル
				if (pageTitle == "伝票" && _visiter == "staff") {
					return false;
				}

				//ヘルプリンクimg
				var img = $("<img>")
						.attr({"class":"header_icon",
								"src":"../image/Help-50.png",
								"target":"_blank"})
						.on("click",function(){
							window.open("../help/index.html?visiter="+
									_visiter +"&show_target="+ val.helpLink);
						});

				//header_iconsがなければ作成
				var hIcons = $("#header_icons");

				if (hIcons.length == 0) {
					hIcons = $("<div>").attr("id","header_icons").append(img);
					//ヘッダーに移動
					hIcons.appendTo("#header #icon_area");
				}else {//ある場合はイメージを追加
					hIcons.prepend(img);
				}


			}
		});
	}
	/*--------------------------------*/

	/*--navigation--------------------*/
	$._createNavigation = function() {
		document.write(
				'<nav id="menu">'+
				'<ul>'+
				'<li style="font-size: 12px;"><a>ログイン：'+_personName+'</a></li>'
		);

		$.each($._getNavigation(),function(index,val){
			var menu;
			if (val.url) {
				document.write('<li><a id="pagelink_'+val._name+'" href="'+val.url+'" data-url="'+val.url+'"><img src="'+val.icon+'">'+val.local_name+'</a>');
			}else {
				document.write('<li><a href="" data-url="" class="parent_menu"><img src="'+val.icon+'">'+val.local_name+'</a>');
			}

			if (val.sub_menu) {
				document.write('<ul>');
				$.each(val.sub_menu,function(i,v){
					document.write(
							'<li><a id="pagelink_'+v._name+'" href="'+v.url+'" data-url="'+v.url+'">'+v.local_name+'</a></li>');
				});
				document.write('</ul>');
			}

			document.write('</li>');
		})
		document.write('</ul></nav>');
	}

	$._getNavigation = function() {

		var logout = {
				_name:"logout",
				url:"login.php?logout=true",
				local_name:"ログアウト"};

		if (_visiter == "staff") {
			var res = _pages[0];
			var rec = _pages[1];
			var rem = _pages[2];
			var per = _pages[3];
			var nav = [res,rec,rem,per,logout];
			$.setPageIcon(nav);
			return nav;
		}else if (_visiter == "salon") {
			var res = _pages[0];
			var rec = _pages[1];
			var dr = _pages[4];
			var mr = _pages[8];
			var stm = {
					_name:"staff_management",
					local_name:"スタッフ管理",
					url:null,
					sub_menu:[_pages[9],_pages[6],_pages[7],_pages[14],_pages[11]]};
			var sal = _pages[5];
			var acc = _pages[13];
			var nav = [res,rec,dr,mr,stm,sal,acc,logout];
			$.setPageIcon(nav);
			return nav;
		}else {
			var top = _pages[10];
			var res = _pages[0];
			var mr = _pages[8];
			var sr  = _pages[9];
			var dd = _pages[14];
			var payment = _pages[11];
			var companySetting = _pages[12];
			var nav = [top, res, mr, sr, dd, payment, companySetting,logout];

			/*--turba_func----*/
			if (_postName == "turba") {
			//if (_postName == "くそ美容室") {
				//新しいページ作成
				var turba_page =  {salon_pages_id: null, _name: "salary_sheet", local_name: "turba社員給与明細", url: "turba_func_salary_sheet.php"}
				nav.push(turba_page);
			}else if (_postName == "CA") {
        var ca_page =  {salon_pages_id: null, _name: "salary_sheet", local_name: "CA社員給与明細", url: "turba_func_salary_sheet.php"}
				nav.push(ca_page);
			}
			/*--------------------*/

			return nav;
		}
	}

	$.setPageIcon = function(pages) {
		$.each(pages,function(index,val){
			if (val._name) {
				val.icon = "../image/navbar/"+val._name+".png";
			}
		});
		
	}
	/*--------------------------------*/


	/*--日付関係--------*/
	//year, month, dateから曜日を返す
	function createDay(year, month, date) {
		var myD = new Date(year+"/"+month+"/"+date);
		myDay = new Array("日","月","火","水","木","金","土");
		var index = myD.getDay();
		return myDay[index];
	}
	//DAYNAMEから曜日を返す
	$.createDayFromSQLDAYNAME = function(dayName) {
		if (dayName == "Sunday") {
			return "日";
		}else if (dayName == "Monday") {
			return "月";
		}else if (dayName == "Tuesday") {
			return "火";
		}else if (dayName == "Wednesday") {
			return "水";
		}else if (dayName == "Thursday") {
			return "木";
		}else if (dayName == "Friday") {
			return "金";
		}else if (dayName == "Saturday") {
			return "土";
		}
	}
	//月の日数を返す
	function getNumOfDays(year,month){
	    return new Date(year,month,0).getDate();
	};
	/*--デバッグ----------------*/
	//経過時間を出力し現在時刻を返す
	$.rapTime = function(str,lastTime) {
		var now = $.now();
		var rap = (now -lastTime) /1000;
		
		return now;
	}
	//クリック時に波紋を表示（動画作成用）
	$.fn.pointer = function (options) {
		var settings = {
			size : 80,
			spd : 300,
			color : "#e74c3c"
		}
		settings = $.extend(settings, options);

		var circle_style = {
			"position":"absolute",
			"z-index":9999,
			"height":10,
			"width":10,
			"border":"solid 4px "+settings.color,
			"border-radius":settings.size
		}
		return this.each(function() {
			var $this = $(this);
			$this.css({"position":"relative"});
			$(document).click(function(e){
				var x = e.pageX-5;
				var y = e.pageY-5;

				var pos = {
					top :(-settings.size/2)+y,
					left :(-settings.size/2)+x
				}

				$this.append('<div class="circle"></div>');
				$this.find(".circle:last").css(circle_style).css({
					"top":y,
					"left":x
				}).animate({"height":settings.size,"width":settings.size,"left":pos.left,"top":pos.top},{duration:settings.spd,queue:false})
				.fadeOut(settings.spd*1.8,function(){
					$(this).remove();
				});
			});
		});
	}
	/*-------------------------*/
	/*--------------------*/

	/*--数値、文字列--------------*/
	//数値を３桁区切りに
	$.delimiting = function(num) {
		if (!num) { num = 0; }
		return String(num).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
	}
	/*---------------------------*/

	/*--サイズ--------------------*/
	/* 表示領域の確実な高さを返す
	 * iphone, ipadで$(window).height()は実際よりも小さい値を返すため
	 * window.innerHeightを使用する。
	 */
	$.realWiindowHeight = function() {
		return window.innerHeight ? window.innerHeight : $(window).height();
	}
	/*----------------------------*/

	/*--iOSなどのモバイルで滑らかなアニメーションをさせる-----*/
	/* --------移動-----------
	 * 引数
	 * x:leftに相当
	 * y:topに相当
	 * z:z-indexに相当？
	 * time:速度
	 */
	$.fn.smoothtTranslate = function(x,y,z,time) {
		$(this).css({
		    "-webkit-transform":"translate3d("+x+"px,"+y+"px,"+z+"px)",
		    //"-webkit-transition":"-webkit-transform "+time+"ms cubic-bezier(0,0,0.25,1)"
		    "-webkit-transition":"-webkit-transform "+time+"ms"
		});
		return $(this);
	}
	/*-----------------------------------------*/

	/*--月に応じて日付セレクタのoptionを追加する--*/
	$.fn.addDateOptions = function(settings) {
		var date = new Date(settings.year,settings.month,0);
		var num = date.getDate();

		for (var i = 1; i <= num; i++) {
			//前0付加
			var val = ("0"+i).slice(-2);
			this.append($("<option>")
				/*.val(i)*/.val(val)
				.text(i+"("+createDay(settings.year,settings.month,i)+")"));
		}
		return this;
	}
	/*----------------------------------------*/

	/*--来店回数セレクタにoptionを追加する-------*/
	$.fn.addvisitOptions = function () {
		var visits = ["常連","新規","2回目","3回目"];
		for (var i = 0; i < visits.length; i++) {
			this.append($("<option>").val(i).text(visits[i]));
		}
		return this;
	}
	/*--------------------------------------*/
	/*--支払方法セレクタにoptionを追加する-------*/
	$.fn.addPaytypeOptions = function () {
		var payType = ["現金","カード"];
		for (var i = 0; i < payType.length; i++) {
			this.append($("<option>").val(i).text(payType[i]));
		}
		return this;
	}
	/*--------------------------------------*/
	/*--伝票項目input(net,point,free,student)を作成する--*/
	$.getEntryInput = function(entry) {
		if (entry._name == "point") {
			return '<input type="text" id="point" name="point"' +
 					'class="not_unique_char only_num chkcode">';
		}else if (entry._name == "free") {
			return '<input type="radio" name="free" value="0">' +
 				'<input type="radio" name="free" value="1">';
		}else{
      return '<input type="checkbox" id="'+ entry._name +'" name="'+ entry._name +'">';
    }
	}
	/*--------------------------------------*/

	/*--名前付きメニューアイコンを生成する
	 * 引数
	 * menu-メニューオブジェクト / boxWidth-boxの幅
	 * -----------*/
	$.createMenuIcon = function(menu,options) {

		var setting = $.extend({
			//box幅
			boxW : 36
		},options);

		var onImg = $("<img>").attr({"class":"on","src":"../image/"+menu.on_img});
		var offImg = $("<img>").attr({"class":"off","src":"../image/"+menu.off_img})
								.css("display","none");
		var name = $("<p>").attr("class","overflow_clip").text(menu.local_name);

		var box = $("<div>")
					.attr({"class":"menu_icon","title":menu.menu_id})
					.css("width",setting.boxW)
					.append(onImg).append(offImg).append(name)
					.on("click",function(){
						//イメージを切り替える
						$(this).find("img").toggle();
					});

		return box;
	}
	/*-----------------------------------------------*/

	/*削除アイコンを作成
	 * 引数
	 * target:削除対象ビュー
	 */
	$.delIcon = function(target,options) {
		var setting = $.extend({
			//正方形1辺のながさ
			size : 36,
			//iconイメージ
			image : "../image/trash_2.png",
			//削除前実行メソッド
			beforeFunc : null,
			//削除成功時実行メソッド
			success : null,
			//削除失敗時実行メソッド
			failure : null,
			//成功、失敗に関わらず最後に実行するメソッド
			afterFunc : null
		},options);

		var icon = $("<img>")
				.css({"width":setting.size,"height":setting.size})
				.attr("src",setting.image)
				.on("click",function(){

					//beforeFuncが設定されていれば実行
					if (setting.beforeFunc) {
						if (setting.beforeFunc()) {
							//返り値がtrueなら対象ビューを削除
							target.remove();
							//successを実行
							if (setting.success) {
								setting.success();
							}
						}else {
							 //返り値がfalseならfailureを実行
							if (setting.failure) {
								setting.failure();
							}
						}
					}else {
						target.remove();
					}

					//afterFuncが設定されていれば実行
					if (setting.afterFunc) {
						setting.afterFunc();
					}
				});
		return icon;
	}
	/*-----------------------------------------*/

	/*--ajax送信--------------------------------*/
	/* --sendingData-----------------------------------
	 * postデータ(オブジェクト)をコントローラで作成する
	 *
	 * -必須データ
	 * --mode:insert,update,deleteのいずれか
	 * --table:操作するテーブル名
	 * --カラム名:対応する値を入れる
	 *
	 * -updateモード,deleteモードのみ必須のデータ
	 * --id:レコードのid
	 *
	 * -データタイプ指定(select)
	 * --data_type:json,textなどのdataTypeに相当する文字列を指定
	 *
	 * -------------------------------------------------
	 *
	 * --options----------------------------------------
	 * コントローラで必要に応じてオブジェクトで指定
	 * async : 同期通信、非同期通信
	 * url: 送信先ファイル
	 * type: POST,GET
	 * dataType: 初期値はnull
	 * --POSTデータにdata_typeで指定があれば変更
	 * data: 必ずsendingDataを使用する仕様
	 * success: 成功時に実行する無名関数
	 * -------------------------------------------------
	 *
	 */
	$.sendAjax = function(sendingData,options) {
		var setting = $.extend({
			async : true,
			url: '../PHPClass/RootModel.php',
			type: "POST",
			dataType: null,
			data: sendingData,
			success: null
		},options);
		//データタイプ指定がある場合は設定
		if (sendingData.data_type) {
			setting.dataType = sendingData.data_type;
		}

		
		$.ajax(setting);
	}
	/*------------------------------------------*/


	/*======================================================
	 * dd.accessory .list_accessoryの画像の縦位置を中央にする
	 *
	 * --$.fn.moveImgToMiddle---------------------
	 * list_accessory単体のオブジェクトメソッド
	 * dd.accessoryの高さにばらつきがある場合は.list_accessoryごとにこのメソッドを呼び出す
	 *
	 * --$.moveAccImgToMiddle-----------------------
	 * 全てのlist_accessoryを操作するメソッド
	 * dd.accessoryの高さが統一されている場合はこのメソッドを使う方が処理が速い
	 * ===================================================*/
	$.fn.moveImgToMiddle = function() {
		var img = $(this).find("img")
		var imgSize =img.height();
		var accW = $(this).width();
		//var ddH = $(this).parents("dd").height();
		var parentH = $(this).parent().height();
		//var top = (ddH - imgSize) / 2;
		var top = (parentH - imgSize) / 2;

		img.css({"position":"absolute","top":top});
	}
	$.moveAccImgToMiddle = function() {
		var acc = $(".list_accessory:first");
		var imgSize = acc.find("img").height();
		var accW = acc.width();
		//var ddH = acc.parents("dd").height();
		var parentH = acc.parent().height();

		$(".list_accessory img").each(function(index,val) {

			//var top = (ddH - imgSize) / 2;
			var top = (parentH - imgSize) / 2;

			$(this).css({"position":"absolute","top":top});
		});
	}
	/*====================================================*/

	/*==mmenuセッティング=============================*/
	$.mmenuSetting = function(options) {
		var setting = $.extend({
			navTabName: "default",
			extensions: ["widescreen", "theme-dark", "effect-slide-menu"]
		},options);

		/*--タブ-----------------*/
		var tab = $("<div>").attr("id","nav_tab").css({"height":46,"position":"fixed",
					"z-index":15,"bottom":0}).attr("name",setting.navTabName);
		var img = $("<img>").attr("src","../image/left_tab.png")
					.css({"height":40,"width":40});
		var a = $("<a>").attr({"id":"menu_icon","href":"#menu"})
				.append(img).css({"text-align":"left","font-size":"14pt","color":"white"});

		$("body").append(tab.append(a));

		//タブの高さ分だけ隙間あける
		$("#main_alea").css("padding-bottom",tab.height() +6);
		/*------------------------------*/


		$("#menu").mmenu({
			extensions: setting.extensions,
			"slidingSubmenus": false,
			"navbar": {"title": _postName},
			"navbars": [
		                  {
		                     "position": "top",
		                  },
		                  {
		                     "position": "bottom",
		                     "content": [
		                                 '<a href="https://orange01.jp" target="_blank">' +
		                                 	'<img alt="" src="../image/orange_logo_white.png" style="height:30px;">' +
		                                 '</a>',
		                                 '<a href="https://orange01.jp/help/index.html?visiter='+ _visiter +'" target="_blank">' +
		                                 	'<img alt="" src="../image/orange_logo_help.png" style="height:30px;">' +
		                                 '</a>',
		                                 '<a href="https://www.facebook.com/orange01.jp" target="_blank">' +
		                                 	'<img alt="" src="../image/FB-f-Logo__blue_50.png" style="height:30px;">' +
		                                 '</a>',
		                     ]
		                  }
		               ]
		});

		//サブメニューの親リンクは無効にする
		$("a.parent_menu").on("click",function(){
			return false;
		});

		//空白領域に要素をセット（#nav_tabが消えるのを防止）
		var container = $("#container");
		
		
		var heightDef = $("body").height() - container.height();
		if (heightDef > 0) {
			
			var dummy = $("<div>").attr("id","dummy").css("height",heightDef);
			$("#main_alea").append(dummy);
		}

		/*--現在ページのnavリンクをマーク-------------------*/
		var title = $("title").text();
		//該当リンクを色分け
		var pagelink = $("#menu a:contains("+ title +")").css("background-color","#f39c12");
		
		//サブメニューの場合は親メニューをクリックして開放
		var parentDivId = pagelink.parents("div:first").prop("id");
		
		$("#menu a[data-target=#"+parentDivId+"]").click();
		//例外
		if (title == "月報作成") {
			$("#menu a:contains(月報)").css("background-color","#f39c12");
		}
		/*---------------------------------------------------*/
	}
	/*================================================*/

	/*--状態お知らせポップアップ------------------------*/
	$.showStateGuidePopup = function(message,classType){

		var stateGuidePopup = $("<div>").addClass("stateGuidePopup");
		if (classType) { stateGuidePopup.addClass(classType); }

		var closer = $("<div>").addClass("closer").text("×").on("click",function(){
			stateGuidePopup.remove();
		});

		stateGuidePopup.append($("<p>").text(message).append(closer)).appendTo("#container");

		return stateGuidePopup;
	}
	/*----------------------------------------------*/

	//ページロックアイコンセット
	//メニューリスト内aのオブジェクトメソッド
	$.fn.setPagelockIcon = function(page) {
		var img = $("<img>").attr("src","../image/Lock Filled-100.png")
			.css({/*"height":20,"width":20,*/"float":"right"});

		if (page.id_in_locking_salon_pages == null) {
			img.addClass("noLock");
		}else {
			img.addClass("lock");
		}
		this/*.addClass("clearfix")*/.append(img);
		return this;
	}
}(jQuery));
