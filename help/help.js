/**
 *
 */
/*-------ヘルプ項目-----------*/
//カテゴリ
var base_tutorial = {categoryId:"base_tutorial",title:"Orangeの基本的な使い方"};
var start = {categoryId:"start",title:"ユーザー登録が完了したら"};
var custom = {categoryId:"custom",title:"サロンに合わせてカスタムしよう"};
var staff_management = {categoryId:"staff_management",title:"スタッフ管理をさらに便利に"};
var report = {categoryId:"report",title:"レポートを活用しよう"};
var staff_account = {categoryId:"staff_account",title:"スタッフアカウント登録"};
var daily_tasks = {categoryId:"daily_tasks",title:"毎日のタスク"};
var monthly_tasks = {categoryId:"monthly_tasks",title:"毎月のタスク"};
var setting = {categoryId:"setting",title:"設定"};
var orange_master = {categoryId:"orange_master",title:"Orangeマスターになろう！"};
var other =  {categoryId:"other",title:"その他"};
var group_tutorial = {categoryId:"group_tutorial",title:"グループ管理"};
var group_master = {categoryId:"group_master",title:"グループ管理者ができること"};
//コンテンツ
var receipt_status = {id:"receipt_status",title:"伝票ステータスについて"};
var salon_workflow = {id:"salon_workflow",title:"サロン管理者の業務フローガイドライン"};
var reserve_tutorial = {id:"reserve_tutorial",title:"予約帳"};
var receipt_tutorial = {id:"receipt_tutorial",title:"お会計"};
var comp_tutorial = {id:"comp_tutorial",title:"来店処理"};
var area_setting = {id:"area_setting",title:"マルチエリア設定"};
var biz_time_setting = {id:"biz_time_setting",title:"営業時間を設定しよう"};
var staff_setting = {id:"staff_setting",title:"スタッフ情報を設定しよう"};
var menu_detail_setting = {id:"menu_detail_setting",title:"メニュー詳細項目を設定する"};
var product_setting = {id:"product_setting",title:"商品を設定する"};
var rec_entry_setting = {id:"rec_entry_setting",title:"伝票オプション項目を設定する"};
var subjects_setting = {id:"subjects_setting",title:"勘定科目を設定する"};
var rem_setting = {id:"rem_setting",title:"マルチ歩合設定"};
var add_rems = {id:"add_rems",title:"手当管理"};
var deductions = {id:"deductions",title:"給与控除管理"};
var daily_report = {id:"daily_report",title:"日報"};
var monthly_report = {id:"monthly_report",title:"月報を見る"};
var create_report = {id:"create_report",title:"月報を作成する"};
var staff_report = {id:"staff_report",title:"スタッフレポート"};
var payment = {id:"payment",title:"支払い管理"};
var page_rock = {id:"page_rock",title:"ページロック"};
var staff_signup = {id:"staff_signup",title:"スタッフアカウントを登録しよう"};
var sales_specification = {id:"sales_specification",title:"売上明細を見る"};
var rem_specification = {id:"rem_specification",title:"報酬を承認する"};
var what_is_group = {id:"what_is_group",title:"グループ管理とは"};
var group_signup = {id:"group_signup",title:"グループアカウントを登録しよう"};
var all_salon_sale = {id:"all_salon_sale",title:"日々の売上を把握"};
var group_payment = {id:"group_payment",title:"支払い管理"};
/*-------------------------------*/

//訪問者タイプ
var visiter = null;
//ページ
var currentPage = null;
//カテゴリー
var categoryPass = null;

/*--目次----------------*/
//サロン目次
var salonHelpIndex =
	[
	 {
		 category:start,
		 contents:[biz_time_setting,staff_setting,]
	 },
	 {
		 category:base_tutorial,
		 contents:[salon_workflow,receipt_status,reserve_tutorial,receipt_tutorial,comp_tutorial,]
	 },
	 {
		 category:daily_tasks,
		 contents:[comp_tutorial,daily_report,]
	 },
	 {
		 category:monthly_tasks,
		 contents:[create_report,payment,]
	 },
	 {
		 category:custom,
		 contents:[menu_detail_setting,product_setting,rec_entry_setting,]
	 },
	 {
		 category:staff_management,
		 contents:[add_rems,deductions,payment,]
	 },
	 {
		 category:report,
		 contents:[daily_report,monthly_report,create_report,staff_report]
	 },
	 {
		 category:orange_master,
		 contents:[area_setting,rem_setting,subjects_setting,page_rock]
	 },
	];
//スタッフ目次
var staffHelpIndex =
	[
	 {
		category:staff_account,
		contents:[staff_signup,]
	 },
	 {
		 category:base_tutorial,
		 contents:[receipt_status,reserve_tutorial,receipt_tutorial,]
	 },
	 {
		 category:daily_tasks,
		 contents:[sales_specification]
	 },
	 {
		 category:monthly_tasks,
		 contents:[rem_specification]
	 },
	];
//グループ目次
var groupHelpIndex =
	[
	 {
		 category:group_tutorial,
		 contents:[what_is_group,group_signup,]
	 },
	 {
		 category:group_master,
		 contents:[all_salon_sale,group_payment,]
	 },
	];


/*---------------------------------*/

jQuery(function ($) {

	/*--ナビゲーション-------------*/
	var navContents = $.navContents("help");
	$.setNav(navContents);
	/*---------------------------*/

	//訪問者設定
	$.setProperty();


	/*--タブ----------------*/
	var categolyTab = $("#help_visiter_tab ul li");
	categolyTab.on("click",function(){
		document.location = "index.html?visiter="+$(this).attr("title");
	}).each(function(index,val) {
		if (val.title == visiter) {
			$(this).css({"background-color":"#364755","color":"white"});
		}
	});
	/*------------------------*/
	/*--目次/ヘルプコンテンツ-------------*/
	var helpIndex;
	if (visiter == "salon") {
		helpIndex = salonHelpIndex;
	}else if (visiter == "staff") {
		helpIndex = staffHelpIndex;
	}else {
		helpIndex = groupHelpIndex;
	}
	////console.log(helpIndex);
	var dl = $("#help_index_list");
	$.each(helpIndex,function(index,val){

		var category = val.category;
		var dt = $("<dt>").attr("id",category.categoryId).text(category.title);
		dl.append(dt);


		var contents = val.contents;
		$.each(contents,function(i,v){
			var dd = $("<dd>").append(
						$("<a>").attr("href","index.html?visiter="+ visiter
								+"&show_target=" + v.id
								+ "&category_pass=" + index)
							.text(v.title));
			dl.append(dd);


			if (v.id == currentPage) {
				//ページタイトル
				$("title").text("Orange[オレンジ]|ヘルプ|" +v.title);
				//コンテンツタイトル
				$("#help_contents_title h2").text(v.title);
				//カテゴリ
				var helpContentsCategory = $("#help_contents_caregory");
				if (categoryPass) {
					helpContentsCategory.text("カテゴリー：" +helpIndex[categoryPass].category.title);
				}else {
					helpContentsCategory.text("カテゴリー：" +category.title);
				}
				//$("#help_contents_caregory").text("カテゴリー：" +category.title);
				//コンテンツアイテムを移動して表示
				var contentsItem = $("#" +v.id);
				contentsItem.appendTo("#help_contents").show();
				$.each(contentsItem.find("img"),function(){
					$(this).attr("src","../image/help/"+ $(this).attr("title") +".png");
				});
			}
		});
	});
	/*-------------------*/
	//コンテンツ指定がない場合はwelcome
	if (currentPage == "welcome") {
		//ページタイトル
		$("title").text("Orange[オレンジ]|ヘルプ|目次");
		//コンテンツタイトル
		$("#help_contents_title h2").text("Orangeヘルプセンターへようこそ");
		//コンテンツアイテムを移動して表示
		$("#welcome").appendTo("#help_contents").show();
	}

	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories(1);//@url_converter.js

	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});

(function($) {
	$.setProperty = function() {
		//パラメータから訪問者を判別
		/*var url = location.href;
		////console.log("url",url);

		if (url.indexOf('?') != -1) {//パラメータがセットされている場合

			var param = url.split("?");
			if (param[1]) {
				//param[1]の構成
				//visiter=???&show_target=???
				var visiterParam = null;
				var pageParam = null;
				if (url.indexOf('&') != -1) {//パラメタが複数の場合
					var detailParam = url.split("&");
					visiterParam = detailParam[0].split("=");
					pageParam = detailParam[1].split("=");
				}else {
					visiterParam = param[1].split("=");
					//pageParam = ["reserve_tutorial", null];
					pageParam = ["welcome", null];
				}
				visiter = visiterParam[1];
				currentPage = pageParam[1];
			}

		}*/

		//$.getParamAtKey():@url_converter.js
		visiter = $.getParamAtKey("visiter");
		currentPage = $.getParamAtKey("show_target");
		categoryPass = parseInt($.getParamAtKey("category_pass"));

		//不正アクセス対策
		if (!visiter || visiter != "salon" && visiter != "staff" && visiter != "group") {
			visiter = "salon";
		}
		if (!currentPage) {
			//currentPage = "reserve_tutorial";
			currentPage = "welcome";
		}
		////console.log("visiter",visiter,"currentPage",currentPage);

		//currentPage = $.currentPageName();//@currentpage_name.js
		////console.log("currentPage",currentPage)
	}
}(jQuery));
