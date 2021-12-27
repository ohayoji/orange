/**
 * 
 */
jQuery(function ($) {
	
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("help");
	$.setNav(navContents);
	/*---------------------------*/
	
	//すべてのヘルプコンテンツを一旦非表示
	$(".help_contents").hide();
	
	//パラメータ配列
	var params = $.getParams();
	//console.log(params);
	
	var visiter = params.visiter;
	//各配列
	var helpType = $.helpType(visiter);
	var helpContents = $.helpContents(visiter);
	
	//コンテンツ表示エリア
	var dispArea = $("#hc_disp_area");
	//目次リスト
	var hecList = $("#hec_list");
	
	
	/*--タブ----------------*/
	var categolyTab = $("#help_category_tab ul li");
	categolyTab.on("click",function(){
		document.location = "help.php?visiter="+$(this).attr("title");
	}).each(function(index,val) {
		if (val.title == visiter) {
			$(this).css({"background-color":"#364755","color":"white"});
		}
	});
		
	
	/*-----------------------------*/
	
	
	/*--目次を作成-------------------*/
	//大題
	$.each(helpType,function(index,val){
		hecList.append(
				$("<li>").text(val.title).css({"margin-top":12, "list-style":"none"})
					.append($("<ul>").css("list-style-position","inside").attr({"title":index})));
	});
	//小題
	$.each(helpContents,function(index,val){
		//対応するカテゴリ
		var category = helpType[val.typeIndex];
		
		//目次に追加
		var list = $("<li>").css("margin-top",6).append(
				$("<a>").on("click",function(){
					$("html,body").animate({
					    scrollTop : $("#"+val.id).offset().top,
					    duration: 0
					}, 50);
				}).text(val.title))
				.appendTo($("ul[title="+val.typeIndex+"]"));
		
		
		//各ヘルプコンテンツを移動、表示
		var entry = $("#help_entry_temp").clone().attr("id",val.id).show();
		entry.find("h3").text(val.title);
		entry.find("h5").text("カテゴリ："+category.title);
		
		var hc = $("#"+val.id).show();
		//イメージ描画
		hc.find(".conbox img").each(function(index,val) {
			$(this).attr("src","image/help/"+$(this).prop("title")+".png");
			//console.log(val);
		});
		
		entry.find("a.right_link").before(hc);
		
		dispArea.append(entry);
	});
	/*-------------------------------------*/
	
	
	$(".help_entry:even").css("background-color","#f2f2f2");
	
	//表示コンテンツが指定されている場合はそこまでスクロール
	/*if (params.show_target) {
		$("html,body").animate({
		    scrollTop : $("#"+params.show_target).offset().top,
		    duration: 0
		}, 0);
	}*/
	if (params.show_target) {
		setTimeout(function(){
			$("html,body").animate({
			    scrollTop : $("#"+params.show_target).offset().top,
			    duration: 0
			}, 100);
		}, 400);
	}
	
});

(function($) {
	//GETパラメータ配列
	$.getParams = function() {
		 var url = location.href;
		 //console.log("url",url);
	     var array = url.split("?");
	     //console.log("array",array);
	     if (!array[1]) {
			//console.log("no_array[1]");
			array[1] = "visiter=salon";
		}
	     var paramsArray = array[1].split("&");
	     //console.log("paramsArray",paramsArray);
	     var params = {};
	        for ( i = 0; i < paramsArray.length; i++ ) {
	            var neet = paramsArray[i].split("=");
	            //params.push(neet[0]);
	            params[neet[0]] = neet[1];
	        }
	     return params;
	}
	//ヘルプ分類配列
	$.helpType = function(type) {
		var helpType;
		if (type == "salon") {
			helpType = 
				[
				 {id:"base_tutorial",title:"Orangeの基本的な使い方"},
				 {id:"start",title:"ユーザー登録が完了したら"},
				 {id:"setting",title:"設定"},
				 {id:"orange_master",title:"Orangeマスターになろう！"}
				 ];
		}else if (type == "staff") {
			helpType = 
				[
				 {id:"base_tutorial",title:"Orangeの基本的な使い方"},
				 {id:"setting",title:"設定"},
				 {id:"other",title:"その他"}
				 ];
		}else if (type == "group"){
			helpType = 
				[
				 {id:"group",title:"グループ管理"},
				 {id:"group_master",title:"グループ管理者ができること"},
				 //{id:"setting",title:"設定"},
				 //{id:"other",title:"その他"}
				 ];
		}
		return helpType;
	}
	//ヘルプ項目配列
	$.helpContents = function (type) {
		//alert(type);
		var helpContents;
		if (type == "salon") {
			helpContents = 
				[
				 {id:"receipt_lifecycle",title:"伝票ステータスについて",typeIndex:0},
				 {id:"reserve_tutorial",title:"予約帳",typeIndex:0},
				 {id:"receipt_tutorial",title:"お会計",typeIndex:0},
				 {id:"comp_tutorial",title:"来店処理",typeIndex:0},
				 {id:"area_setting",title:"エリアを設定しよう",typeIndex:1},
				 {id:"biz_time_setting",title:"営業時間を設定しよう",typeIndex:1},
				 {id:"staff_setting",title:"スタッフ情報を設定しよう",typeIndex:1},
				 {id:"menu_detail_setting",title:"メニュー詳細項目を設定しよう",typeIndex:1},
				 //{id:"menu_setting",title:"メニューを設定する",typeIndex:2},
				 {id:"product_setting",title:"商品を設定する",typeIndex:2},
				 {id:"rec_entry_setting",title:"伝票オプション項目を設定する",typeIndex:2},
				 {id:"subjects_setting",title:"勘定科目を設定する",typeIndex:2},
				 {id:"rem_setting",title:"歩合パターンを設定する",typeIndex:2},
				 /*{id:"receipt_search",title:"伝票検索",typeIndex:3},*/
				 {id:"add_rems",title:"手当",typeIndex:3},
				 {id:"monthly_report",title:"月報",typeIndex:3},
				 /*{id:"rem_specification",title:"売上明細",typeIndex:3},*/
				 {id:"payment",title:"支払い管理",typeIndex:3},
				 ];
		}else if (type == "staff") {
			helpContents = 
				[
				 {id:"receipt_lifecycle",title:"伝票ステータスについて",typeIndex:0},
				 {id:"reserve_tutorial",title:"予約帳",typeIndex:0},
				 {id:"receipt_tutorial",title:"お会計",typeIndex:0},
				 {id:"staff_signup",title:"スタッフアカウントを登録しよう",typeIndex:1},
				 /*{id:"personal_setting",title:"個人設定",typeIndex:1},*/
				 /*{id:"receipt_search",title:"伝票検索",typeIndex:2},*/
				 {id:"rem_specification",title:"売上明細を承認する",typeIndex:2}
				 ];
		}else {
			helpContents = 
				[
				 {id:"what_is_group",title:"グループ管理とは",typeIndex:0},
				 {id:"company_signup",title:"グループアカウントを登録しよう",typeIndex:0},
				 {id:"all_salon_sale",title:"日々の売上を把握",typeIndex:1},
				 {id:"payment",title:"支払い管理",typeIndex:1},
				 ];
		}
		return helpContents;
	}
	
}(jQuery));
