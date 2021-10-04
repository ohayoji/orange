
//メニュー詳細メニュー名dtテンプレ
var tempMdMenuDt;
//メニュー詳細メニュー名ddテンプレ
var tempMdMenuDd;

//商品リストテーブルのtbody
var proTBody;
//商品リストテンプレ
var tempProTr;

//ページロックdl
var pagelockDl;
//ページロックddテンプレ
var tempPagelockDd;

//各リストのdetailラベル（list_detail）
var areaListDetail;//マルチエリア設定
var tecRemListDetail;//マルチ歩合設定（技術）
var proRemListDetail;//マルチ歩合設定（商品）

//ヘルプリンク配列
var helpLinks =
	[
	 	{name:"area_set", url:"../help/index.html?visiter=salon&show_target=area_setting"},//エリア設定
	 	{name:"tec_rem", url:"../help/index.html?visiter=salon&show_target=rem_setting"},//マルチ歩合設定
	 	{name:"pro_rem", url:"../help/index.html?visiter=salon&show_target=rem_setting"},//マルチ歩合設定
	 	{name:"usg_m_detail", url:"../help/index.html?visiter=salon&show_target=menu_detail_setting"},//メニュー詳細
	 	{name:"page_lock", url:"../help/index.html?visiter=salon&show_target=page_rock"},//ページロック
	 	{name:"usg_sub", url:"../help/index.html?visiter=salon&show_target=subjects_setting"},//勘定科目
	 	{name:"pro_setting", url:"../help/index.html?visiter=salon&show_target=product_setting"},//商品設定
	 	{name:"usg_rec_ent", url:"../help/index.html?visiter=salon&show_target=rec_entry_setting"},//伝票オプション
    ];
var helpLinkIcon;

jQuery(function ($) {











	////console.log("_pages", _pages)



	tempMdMenuDt = $("#temp_md_menu_dt");
	tempMdMenuDd = $("#temp_md_menu_dd");

	proTBody = $("#pro_setting table tbody");
	tempProTr = $("#temp_pro_setting");

	areaListDetail = $("#area_list_detail");
	tecRemListDetail = $("#tec_rem_list_detail");
	proRemListDetail = $("#pro_rem_list_detail");

	helpLinkIcon = $("<a>").attr({"class":"inline_help_link", "href":"../help/index.html", "target":"_blank"})
					.text("?");
	/*--基本ステータス------------*/

	$("#salon_setting dd.accessory li").append(
		$("#list_acc_temp").clone().removeAttr("id").show()
	);

	//時間セレクタ
	$.each(_hours,function(index,val){
		$("#bsh, #beh, #rsh, #reh").append(
				$("<option>").val(val.value).text(val.value));
	});
	//分セレクタ
	$.each(_minutes,function(index,val){
		$("#bsm, #bem, #rsm, #rem").append(
				$("<option>").val(val.value).text(val.value));
	});
	//時間ステータスを分解し、各セレクタのval()をセット
	var times = $.splitTimes();
	$.each(times,function(name,val){
		$("#"+name).val(val);
	});

	//　予約帳表示時間のチェックのコードを分離させる。
	//値変更時
	$(".status").on("focus",function(e){
		//値をデータに保存する
		$(this).data("before",$(this).val());


		targetElement = $(this);
	}).on("change",function(e){

		var data = null;

		if ($(this).prop("tagName") == "INPUT") {//input要素の場合
			if ($.stringCheck()) {//stringCheck():@string_check.js
				data = "mode=status&"+$(this).prop("id")+"="+$(this).val();
			}
		}

		if ($(this).hasClass("time")) {//時間セレクタの場合

			var timeAuth = true;//許可フラグ
			if ($(this).hasClass("hour")) {
				timeAuth = $(this).checkTimeSel();
			}
			if (timeAuth) {
				data = "mode=status&"+$(this).dataStr();
			}
		}

		if (data && ($(this).prop("id") == "rsh" || $(this).prop("id") == "rsm"
			|| $(this).prop("id") == "reh" || $(this).prop("id") == "rem")) {
			var recTimeCheck = false;
			var htmlId = $(this).prop("id");
			// ここに予約時間の判定ajax文を書く。
			var dataToSend = {};
			dataToSend["salon_id"] = _salonId;
			// ここにセレクタで指定した時間を変数に保存する
			var reserve_time;
			if(htmlId == "rsh" || htmlId == "rsm"){
				dataToSend["mode"] = "startTime_check";
				var rsh = $("#rsh").val();
				var rsm = $("#rsm").val();
				reserve_time = rsh+':'+rsm;
			}
			else if(htmlId == "reh" || htmlId == "rem"){
				dataToSend["mode"] = "endTime_check";
				var reh = $("#reh").val();
				var rem = $("#rem").val();
				reserve_time = reh+':'+rem;
			}
			dataToSend["reserve_time"] = reserve_time;
			////console.log("reserve_time", reserve_time);
			$.ajax({
				async : true,
				url: '../PHPClass/SalonSettingModel.php',
				type: "POST",
				dataType: "text",
				data: dataToSend
			}).then(function(dataReceived){
				if(dataReceived == "true"){
					recTimeCheck = true;
				}

				if(recTimeCheck){

					$.post("../PHPClass/SalonSettingModel.php",data);
				}
				else{

					alert("予約帳にその時間外の予約が入っているため時間幅を縮められません" +
							"\n時間外に予約が無いことを確認のうえで時間幅を調節してください");



					targetElement.val(targetElement.data("before"));
				}
			});
		}
		else if(data){
			$.post("../PHPClass/SalonSettingModel.php",data);
		}
	});

	/*----------------------------------*/
	//page-lock

	$(".detail_contents[data-name=page_lock]").on("click", function(){
		var myPassword = prompt("パスワードを入力してください","");
		if(myPassword == _password){

			var dataName = $(this).data("name")

			var option = {
					dispContentsId:dataName,
					title:$(this).prop("title")
					};
			$("#right_slide").open(option);

			$.setHelpLinkIcon(dataName);

		}else if (myPassword == null){
			//何もしない
			return false;
		}
		else{
			alert("パスワードが間違っています");
			return false;
		}
	});


	/*--詳細項目リンク　リンククリックで右からメニュー詳細項目slideを表示------------------------*/


	//var rightSlideTitle = $("#right_slide_title");

	$(".detail_contents:not([data-name=page_lock])").on("click",function(){
		var dataName = $(this).data("name")
		var option = {
				dispContentsId:dataName,
				title:$(this).prop("title"),
				//openFunc:true
				};
		if (dataName == "usg_menu") {
			option.width = 210;
		}
		//スライドopen
		$("#right_slide").open(option);
		$("#right_slide").css("overflow", "");


		/*--ヘルプリンク設置-----------*/
		$.setHelpLinkIcon(dataName);
		/*$("#right_slide_title").append(helpLinkIcon);
		var url = null;
		$.each(helpLinks,function(i,v){
			if (v.name == dataName) {
				url = v.url;
			}
		});
		if (url) {
			helpLinkIcon.attr("href",url).show();
		}else {
			helpLinkIcon.hide();
		}*/

		/*---------------------------*/


		//$("#right_slide").delay(int).click()だとうまくいかなかった
		setTimeout(function(){
			$("#right_slide").click();
		}, 100);

	});
	/*
	 * safariの仕様で、rightslideを開いてクリックした時（多分イベント起きた時）
	 * #right_slideのoverflow値が
	 * 動的に変わるとスクロールできるようになる。
	 *
	$("img.right_icon").on("click", function(e){
		$("#right_slide").css("overflow", "");
		e.stopImmediatePropagation();
	});
	*/
	$("#right_slide:not(.right_icon)").on("click", function(){
		$(this).css("overflow", "scroll");

		});

	//helpLink.appendTo("#right_slide_title");

	/*----------------------------------------*/


	/*==詳細コンテンツをセット=================*/

	/*--メニュービューとメニュー詳細項目ビュー-------*/
	$.each(_menus,function(index,val){
		/*--メニュービュー--*/
		//createMenuIcon():@common.js
		var icon = $.createMenuIcon(val,{boxW:50})
			.on("click",function(e){
				//表示されているimgのclassを取得し、data文字列を作成
				var data;
				var state = $(this).find("img:visible").prop("class");
				if (state == "on") {
					data = "mode=menu_on&menu_id="+$(this).prop("title");
				}else {
					data = "mode=menu_off&menu_id="+$(this).prop("title");
				}

				$.post("../PHPClass/SalonSettingModel.php",data);
			});
		$("#usg_menu").append(icon);
		//未使用の場合はoff
		if (!val.um_id) { icon.find("img").toggle(); }
		/*--------------------*/

		/*--メニュー詳細項目ビュー-(div.right_slide_body内)-*/
		//メニュー詳細dl
		var dl = $("#usg_m_detail dl");
		//使用されているメニューのみ詳細項目リストを表示
		if (val.um_id) {
			//メニュー詳細配列
			var menuDetails = val.menu_datails;

			var dt = tempMdMenuDt.clone().removeAttr("id").show()
						.text(val.local_name).appendTo(dl);
			var dd = tempMdMenuDd.clone().removeAttr("id").show();
			dt.after(dd);


			//詳細追加ボタン
			var addMd = dd.find(".add_md").data("menu_id",val.menu_id)
				.on("click",function(){
					if ($(this).checkAddMd()) {$(this).addNewMd();}
				});
			if (menuDetails.length > 0) {
				$.each(menuDetails,function(dIndex,dVal){
					if (dVal.deleted == 0) {
						addMd.addNewMd(dVal);
					}
				});
			}


			//sortable
			var chara = "md_menu_table" + val.menu_id;
			var table = addMd.prev("table");
			table.attr("id",chara);

			$("#" + chara).sortable({
				//移動方向
				axis: "y",
				//キャンセル対象
				//cancel: "td.noGrab",
				handle:"td.sort_cell",
				//擬似tr
				helper: function(e, tr) {
							var $originals = tr.children();
							var $helper = tr.clone().addClass("sort_helper");
							$helper.children().each(function(index) {
								$(this).width($originals.eq(index).width());
							});
							return $helper;
						},
				//並べ替え対象
				items:"tbody tr:not(.newmd):not(.temp_md_tr)",
				//置いた時の処理
				update: function(event, ui){
					//操作対象tr
					var targetTr = table.find("tbody tr:not(.newmd):not(.temp_md_tr)");
					//送信データ
					var dataToSend = {};
					var dataIndexStr = 0;

					//商メニュー詳細データを更新 & 送信データに追加
					var trlen = targetTr.length;
					var mdlen = menuDetails.length;

					for (var i = 0; i < trlen; i++) {
						//商品id
						var id = targetTr.eq(i).prop("title");

						for (var n = 0; n < mdlen; n++) {
							//同じメニュー詳細の_orderが変更されていた場合
							var md = menuDetails[n];

							if (id == md.id && i != md._order) {
								//項目書き換え
								md._order = i;
								//送信データに追加
								dataToSend[dataIndexStr] = {"id": id,"_order": i};
								//インデックス番号を加算
								dataIndexStr++;
							}
						}
					}
					/*//trのステータス_orderを書き換え
					for(var i = 0; i < targetTr.length - 1; i++){
						for(var j = 0; j < targetTr.length - 1 - i; j++){
							if(parseInt(targetTr.eq(j).data("status")._order) >
									parseInt(targetTr.eq(j+1).data("status")._order)){
								var temp = targetTr.eq(j).data("status")._order;
								targetTr.eq(j).data("status")._order =
									targetTr.eq(j+1).data("status")._order;
								targetTr.eq(j+1).data("status")._order = temp;
							}
						}
					}
					//送信データ
					var dataToSend = {};

					//送信データに情報を追加
					targetTr.each(
						function(index, element){
							dataToSend[index] = {"id": $(element).data("status").id,
												"_order": $(element).data("status")._order};
						}
					);*/
					dataToSend["mode"] = "md_sort";

					$.ajax({
						async : false,
						url: '../PHPClass/SalonSettingModel.php',
						type: "POST",
						dataType: "json",
						data: dataToSend
					});
				}
			});
		}
		/*----------------------*/
	});
	/*------------------*/

	/*--商品ビュー-----------------------------------------------*/
	//追加ボタン
	var addPro = $("#add_pro").on("click",function(){
		if ($(this).checkAddPro()) { $(this).addNewPro(); }
	});
	$.each(_products,function(index,val){
		//削除済み以外を表示
		if (val.deleted == 0) { addPro.addNewPro(val); }
	});

	//sortable
	$("#pro_setting table tbody").sortable({
		//移動方向
		axis: "y",
		handle:"td.sort_cell",
		//擬似tr
		helper: function(e, tr) {
					var $originals = tr.children();
					var $helper = tr.clone().addClass("sort_helper");
					$helper.children().each(function(index) {
						$(this).width($originals.eq(index).width());
					});
					return $helper;
				},
		//並べ替え対象
		items:"tr:not(.newpro):not(#temp_pro_setting)",
		//置いた時の処理
		update: function(event, ui){
			//操作対象tr
			var targetTr = proTBody.find("tr:not(.newpro):not(#temp_pro_setting)");
			//送信データ
			var dataToSend = {};
			var dataIndexStr = 0;

			//商品データを更新 & 送信データに追加
			var trlen = targetTr.length;
			var prolen = _products.length;

			for (var i = 0; i < trlen; i++) {
				//商品id
				var id = targetTr.eq(i).prop("title");

				for (var n = 0; n < prolen; n++) {
					//同じ商品の_orderが変更されていた場合
					var pro = _products[n];

					if (id == pro.id && i != pro._order) {
						//_products書き換え
						pro._order = i;
						//送信データに追加
						dataToSend[dataIndexStr] = {"id": id,"_order": i};
						//インデックス番号を加算
						dataIndexStr++;
					}
				}
			}
			dataToSend["mode"] = "pro_sort";




			/*//trのステータス_orderを書き換え
			for(var i = 0; i < targetTr.length - 1; i++){
				for(var j = 0; j < targetTr.length - 1 - i; j++){
					if(parseInt(targetTr.eq(j).data("status")._order) >
					parseInt(targetTr.eq(j+1).data("status")._order)){
						var temp = targetTr.eq(j).data("status")._order;
						targetTr.eq(j).data("status")._order =
							targetTr.eq(j+1).data("status")._order;
						targetTr.eq(j+1).data("status")._order = temp;
					}
				}
			}
			//送信データ
			var dataToSend = {};

			//送信データに情報を追加
			targetTr.each(
				function(index, element){
					dataToSend[index] = {"id": $(element).data("status").id,
										"_order": $(element).data("status")._order};
				}
			);
			dataToSend["mode"] = "pro_sort";*/

			$.ajax({
				async : false,
				url: '../PHPClass/SalonSettingModel.php',
				type: "POST",
				dataType: "json",
				data: dataToSend
			});
		}
	});

	/*----------------------------------------------------------*/

	/*--オプション伝票項目ビュー------------*/
	//テンプレ
	var temp = $("#temp_dd_usg_rec_ent");
	//dl
	var dl = $("#usg_rec_ent dl");
	$.each(_recEntries,function(index,val){
		if (val._name != "tec_rem" && val._name != "pro_rem") {

			var dd = temp.clone().removeAttr("id").show().appendTo(dl);

			dd.find(".ent_name").text(val.local_name);

			var checkbox = dd.find("input[type=checkbox]");
			if (val.ur_id) {checkbox.prop("checked",true);}
			checkbox.on("click",function(){
				var data;
				if ($(this).prop("checked")) {
					data = "mode=rec_ent_add&receipt_entry_id="+val.ent_id;
				}else {
					data = "mode=rec_ent_del&receipt_entry_id="+val.ent_id;
				}

				$.post("../PHPClass/SalonSettingModel.php",data);
			});
		}
	});
	/*---------------------*/

	/*--エリアビュー---------------------------------------*/
	$.each(_areas,function(index,val){
		var tr = $("<tr>").data("status",val).createAreaView().setAreaInfo();
		$("#area_set table").append(tr);
		//席数変更を有効に
		//tr.find("input[title=seats]").prop("disabled", true).css("border","none");
	});
	//エリア追加ボタン
	$("#add_area").on("click",function(){

		//var tr =  $.areaTr();
		var tr =  $("<tr>").addClass("trForAddition").createAreaView();

		//deleteアイコンを隠しaddアイコンを表示
		tr.find("td:eq(2) img[title=delete]").css("display","none");
		tr.find("td:eq(2) img[title=add]").css("display","block").on("click",function(){
			$(this).decision();
			//席数変更可能に。
			//tr.find("input[title=seats]").prop("disabled", true);
		});

		//追加ボタンを無効に
		$(this).toggle();
		//tbodyを末尾に書くとareaが0の時エリア追加できないので削除した
		$("#area_set table").append(tr);
	});


	$("#area_sortable tbody").sortable({
		//移動方向
		axis: "y",
		handle:"td.sort_cell",
		//擬似tr
		helper: function(e, tr) {
					var $originals = tr.children();
					var $helper = tr.clone().addClass("sort_helper");
					$helper.children().each(function(index) {
						$(this).width($originals.eq(index).width());
					});
					return $helper;
				},
		//並べ替え対象
		items:"tr:not(.trForAddition)",
		//置いた時の処理
		update: function(event, ui){
			//操作対象tr
			var targetTr = $("table#area_sortable tbody tr").not(".trForAddition");

			//trのステータス_orderを書き換え
			for(var i = 0; i < targetTr.length - 1; i++){
				for(var j = 0; j < targetTr.length - 1 - i; j++){
					if(parseInt(targetTr.eq(j).data("status")._order) >
						parseInt(targetTr.eq(j+1).data("status")._order)){
						var temp = targetTr.eq(j).data("status")._order;
						targetTr.eq(j).data("status")._order =
							targetTr.eq(j+1).data("status")._order;
						targetTr.eq(j+1).data("status")._order = temp;
					}
				}
			}
			//送信データ
			var dataToSend = {};

			//送信データに情報を追加
			targetTr.each(
				function(index, element){
					dataToSend[index] = {"id": $(element).data("status").id,
										"_order": $(element).data("status")._order};
				}
			);
			dataToSend["mode"] = "area_sort";

			$.ajax({
				async : false,
				url: '../PHPClass/SalonSettingModel.php',
				type: "POST",
				dataType: "json",
				data: dataToSend
			});
		}
	});
	/*---------------------------------------------------------*/

	/*--勘定科目ビュー-------------------------------------------*/
	//テンプレ
	var temp = $("#temp_dd_usg_sub");
	//dl
	var dl = $("#usg_sub dl");
	$.each(_subjects,function(index,val){

		var dd = temp.clone().removeAttr("id").show().appendTo(dl);

		dd.find(".sub_name").text(val.local_name);

		var checkbox = dd.find("input[type=checkbox]");
		if (val.us_id) {checkbox.prop("checked",true);}
		//売上項目は変更不可にする
		if (val._name == "tec_sales" || val._name == "pro_sales") {
			checkbox.prop("disabled",true);
		}
		checkbox.attr({"data-sb_id":val.sb_id,"data-us_id":val.us_id})
			.on("click",function(){
				var checked = $(this).prop("checked");
				var data;
				if (checked) {
					data = {
							mode:"insert",table:"using_subjects",
							subject_id:$(this).data("sb_id"),salon_id:_salonId
					};

				}else {
					data = {
							mode:"delete",table:"using_subjects",
							id:$(this).data("us_id")
					};

				}
				$.sendAjax(data);
		});
	});
	/*--------------------------------------------------------*/

	/*--歩合率設定ビュー-----------------------------------*/
	var tec_pro = ["tec","pro"];
	$.each(tec_pro,function(index,val){

		/*--有効無効切り換えチェックボックス-----------------*/
		var remValid = $("#"+val+"_rem_valid");
		var listDetail;
		if (val == "tec") {
			listDetail = tecRemListDetail;
		}else {
			listDetail = proRemListDetail;
		}
		//有効ならチェック
		if (_salonStatus[val+"_rem"] == 1) {
			remValid.prop("checked",true);
			listDetail.changeOptionDetailList(true);
		}else {
			listDetail.changeOptionDetailList(false);
		}
		remValid.on("click",function(){
			var checked = $(this).prop("checked");
			var updateAuth = true;
			/*--------*/
			if (!checked) {
				if (confirm("伝票ごとの歩合設定を無効にしてよろしいですか？" +
						"\n(歩合率リストがある場合は全て削除されます)")) {
					//歩合リストが存在する場合はリスト削除
					$("#"+val+"_pp_list tr:not(:first)").remove();
					//全レコード削除
					var data = {mode:"all_pp_del",type:val};
					$.ajax({
						async : false,
						url: '../PHPClass/SalonSettingModel.php',
						type: "POST",
						data: data,
					});
				}else {
					//チェックし直してキャンセル
					$(this).prop("checked",true);
					updateAuth = false;
				}
			}
			if (updateAuth) {
				//salons更新
				var data = {mode:"update",table:"salons",id:_salonId};
				data[val+"_rem"] = Number(checked);
				$.sendAjax(data);
				//ListDetail更新
				listDetail.changeOptionDetailList(checked);
			}
			/*---------------------------------------------*/


		});
		/*------------------------------------------*/

		/*--パターン追加ボタン----------------------*/
		var addRem = $("#add_"+val+"_rem");
		addRem.on("click",function(){
			if (remValid.prop("checked") == false) {
				remValid.click();
				//listDetail.changeOptionDetailList(true);
			}
			$(this).addPPList(val,null);
		});
		/*----------------------------------------*/

		/*--リスト表示----------------------------*/
		var ppList;
		if (val == "tec") { ppList = _tecpp;}else {	ppList = _propp;}
		$.each(ppList,function(i,v){
			addRem.addPPList(val,v);
		});
		/*-------------------------------------*/
	});
	/*------------------------------------------------*/
	/*--ページロックビュー-------------------------------------------*/
	//テンプレ
	tempPagelockDd = $("#temp_dd_page_lock");
	//dl
	pagelockDl = $("#page_lock dl");
	$.each(_pagesForSalon,function(pIndex,val){
		if (val.local_name != "ログアウト") {
			if (val.sub_menu) {
				$.each(val.sub_menu,function(i,v){
					$.addPagelockList(v);
				});
			}else {
				$.addPagelockList(val);
			}
		}
	});
	/*--------------------------------------------------------*/
	//マルチエリア設定
	if(_areas.length > 1){
		//areaListDetail.text("ON").css("color", "red");
		areaListDetail.changeOptionDetailList(true);
	}
	else{
		//areaListDetail.text("OFF").css("color", "gray");
		areaListDetail.changeOptionDetailList(false);
		$("#seats_num_area").css("display", "block");
		$("#seats_num").val(_areas[0].seats).on("change", function(){
			var status = _areas[0];

			$that = $(this);
			if(parseInt(status.seats) < parseInt($(this).val())){
				$(this).chkCode();//chkCode():@string_check.js

				if ($(this).strCheck()) {//strCheck():@string_check.js
					var data = "mode=area_change&id="+status.id+
						"&"+$(this).prop("title")+"="+$(this).val();

					$.post("../PHPClass/SalonSettingModel.php",data);

					//area_settingテーブルの書き換え
					var data = "mode=area_change&id="+status.id+
					"&"+$that.prop("title")+"="+$that.val();
					$.post("../PHPClass/SalonSettingModel.php",data);

					//area_seats_settingテーブルのアップデートとインサート
					var dataForSeatsUpdate = {
							"mode": "seatsUpdate",
							"area_id": status.id,
							"seats": $that.val(),
							"end_date": _today
					};

					var dataForInsert = {mode: "insert",
										table: "area_seats_setting",
										area_id: status.id,
										seats: $that.val(),
										start_date:_today,
										end_date:'',
										disabled:0
								};
					//.then()使ってもinsertとupdateの処理順番がなぜか逆になる。なのでまずselectしそのあとid指定でupdateするように修正
					$.post("../PHPClass/SalonSettingModel.php",dataForSeatsUpdate).then($.sendAjax(dataForInsert));

				}
			}
			else if(parseInt(status.seats) > parseInt($(this).val())){
				$(this).chkCode();//chkCode():@string_check.js


				if ($(this).strCheck()) {//strCheck():@string_check.js
					//receiptsのチェック
					var dataForCheck = {"mode": "receipts_check",
										"oldSeats": status.seats,
										"newSeats": $(this).val(),
										"areaId": status.id};
					$.post("../PHPClass/SalonSettingModel.php",dataForCheck,
							function(dataReceived){
								var dataNow = JSON.parse(dataReceived);

								if(dataNow["flag"] == "NO"){
									var str = "削除対象の席にすでに予約があります。予約を他の場所に移動してください。\n【該当予約】\n" +
											"日付: "+dataNow["row"]["start"]+"〜"+dataNow["row"]["end"]+"\nスタッフ名: "+dataNow["row"]["staff_name"];
									alert(str);
									$that.val(status.seats);
								}
								else{
									//area_settingテーブルの書き換え
									var data = "mode=area_change&id="+status.id+
									"&"+$that.prop("title")+"="+$that.val();
									$.post("../PHPClass/SalonSettingModel.php",data);

									//area_seats_settingテーブルのアップデートとインサート
									var dataForSeatsUpdate = {
											"mode": "seatsUpdate",
											"area_id": status.id,
											"seats": $that.val(),
											"end_date": _today
									};

									var dataForInsert = {mode: "insert",
														table: "area_seats_setting",
														area_id: status.id,
														seats: $that.val(),
														start_date:_today,
														end_date:'',
														disabled:0
												};
									//.then()使ってもinsertとupdateの処理順番がなぜか逆になる。なのでまずselectしそのあとid指定でupdateするように修正
									$.post("../PHPClass/SalonSettingModel.php",dataForSeatsUpdate).then($.sendAjax(dataForInsert));
								}

							}
					);
				}
			}
		});
	}


	/*===============================*/

	/*--option_dt----------------------*/
	var optionDd = $("#option_dd");
	var changer = $("#option_disp_changer");
	var optionDt = $("#option_dt").on("click",function(){
		optionDd.toggle();
		if (optionDd.css("display") == "none") {
			changer.text("▼");
		}else {
			changer.text("▲");
		}
	});

	/*--------------------------------*/
});

(function($) {
	/*--ヘルプリンク設置-----------*/
	$.setHelpLinkIcon = function(dataName) {
		$("#right_slide_title").append(helpLinkIcon);
		var url = null;
		$.each(helpLinks,function(i,v){
			if (v.name == dataName) {
				url = v.url;
			}
		});
		if (url) {
			helpLinkIcon.attr("href",url).show();
		}else {
			helpLinkIcon.hide();
		}
	}
	/*---------------------------*/

	//rightSlideOpenFunc
	/*$.rsOpenFunc = function() {
		//alert("ss");
		var rsTitle = $("#right_slide_title");
		if (rsTitle.find("#help_link").length == 0) {
			rsTitle.append(helpLinkIcon);
		}
		rsTitle.append(helpLinkIcon);
	}*/
	//ページロックリスト生成
	$.addPagelockList = function(page) {

		var dd = tempPagelockDd.clone().removeAttr("id").show().appendTo(pagelockDl);
		dd.find(".page_name").text(page.local_name);

		var checkbox = dd.find("input[type=checkbox]");
		if (page.id_in_locking_salon_pages) {
			checkbox.prop("checked",true);
		}

		checkbox.attr({"data-salon_pages_id":page.salon_pages_id,
						"data-id_in_locking_salon_pages":page.id_in_locking_salon_pages})
				.on("click",function(){

					var checked = $(this).prop("checked");
					var data;
					var targetMenu = $("#pagelink_"+page._name)

					if (checked) {
						data = {
							mode:"insert",table:"locking_salon_pages",
							salon_pages_id:$(this).data("salon_pages_id"),
							salon_id:_salonId,
							data_type:"text"
						};

						targetMenu.find("img").removeClass("noLock").addClass("lock");

						/*targetMenuList.each(function(index, element){
							if($(element).data("local_name") == page.local_name){

								if($(element).find("img.lock").length > 0){

								}else {
									$(element).find("img").removeClass("noLock")
										.addClass("lock");
								}
							}
						});*/

						var insertId = $.sendAjax(data);

						$(this).data("id_in_locking_salon_pages", insertId);


					}else {
						data = {
								mode:"delete",table:"locking_salon_pages",
								id:$(this).data("id_in_locking_salon_pages")
						};

						targetMenu.find("img").removeClass("lock").addClass("noLock");

						/*targetMenuList.each(function(index, element){
							if($(element).data("local_name") == page.local_name){
								if($(element).find("img.lock").length > 0){
									$(element).find("img").removeClass("lock")
										.addClass("noLock");
								}
								else{

								}
							}
						});*/

						$.sendAjax(data);
					}
				});
	}
	//biz_start,biz_end,reserv_start,reserv_endを分解して配列で返す
	$.splitTimes = function() {
		var bs = _salonStatus.biz_start.split(":");
		var be = _salonStatus.biz_end.split(":");
		var rs = _salonStatus.reserv_start.split(":");
		var re = _salonStatus.reserv_end.split(":");
		var array = {
			bsh:bs[0], bsm:bs[1], beh:be[0], bem:be[1],
			rsh:rs[0], rsm:rs[1], reh:re[0], rem:re[1]
		};

		return array;
	}
	$.fn.checkTimeSel = function() {

		//チェックOKフラグ
		var authFlag = true;
		//値を数値に変換
		var myVal = parseInt($(this).val());
		//従兄弟selectに対応する名前
		var name = $(this).prop("name");
		//親divのタイプ(biz_times or res_times)
		var type = $(this).parents("div").prop("id");

		if (type == "biz_times") {//営業時間が変更された場合
			//従兄弟selectの値
			var brotherVal =
				parseInt($("div#res_times")
						.find("select[name="+name+"]").val());
			if (name == "start") {//開始時刻が変更された場合
				if (myVal <= brotherVal) {
					alert("営業開始時間は予約帳表示開始時間の１時間後以降に設定してください");
					authFlag = false;
				}
			}else {//終了時刻が変更された場合
				if (myVal >= brotherVal) {
					alert("営業終了時間は予約帳表示終了時間の１時間前以前に設定してください");
					authFlag = false;
				}
			}

		}else {//予約帳表示時間が変更された場合
			//従兄弟selectの値
			var brotherVal =
				parseInt($("div#biz_times")
						.find("select[name="+name+"]").val());
			if (name == "start") {//開始時刻が変更された場合
				if (myVal >= brotherVal) {
					alert("予約帳表示開始時間は営業開始時間の１時間前以前に設定してください");
					authFlag = false;
				}
			}else {//終了時刻が変更された場合
				if (myVal <= brotherVal) {
					alert("予約帳表示終了時間は営業終了時間の１時間後以降に設定してください");
					authFlag = false;
				}
			}
		}

		//許可が下りなかったらもとに戻す
		if (!authFlag) {
			$(this).val($(this).data("before"));
		}
		return authFlag;
	}
	//時間セレクタのデータ文字列を作成
	$.fn.dataStr = function() {
		var parent = $(this).parent();
		var timeStr = parent.find("select:first").val() + ":" +
						parent.find("select:eq(1)").val() + ":00";
		var data = parent.prop("id")+"="+timeStr;

		return data;
	}

	/*==オプション項目detail_list表示切り替えメソッド=============================*/
	$.fn.changeOptionDetailList = function(_switch) {
		if (_switch == true) {
			$(this).text("ON").removeClass("off").addClass("on");
		}else {
			$(this).text("OFF").removeClass("on").addClass("off");
		}
	}
	/*======================================================*/

	/*==メニュー詳細項目のlist作成================================*/
	//メニュー詳細リスト追加許可
	$.fn.checkAddMd = function() {
		var auth = true;
		//新規行が存在していたらキャンセル
		if ($(this).prev("table").find("tr.newmd").length) {
			alert("最後のメニュー詳細項目が保存されていません");
			auth = false;
		}
		return auth;
	}
	//メニュー詳細リスト追加
	$.fn.addNewMd = function(mdData) {
		var self = $(this);
		//テンプレクローンし追加
		var table = $(this).prev("table");
		var tempMdTr = table.find(".temp_md_tr");
		var tbody = table.find("tbody");
		var tr = tempMdTr.clone().removeClass("temp_md_tr").show()/*.data("status",mdData)*/;
		tbody.append(tr);



		//setTextStrCheck():@string_check.js
		var nameInput = tr.find("input._name").setTextStrCheck()
			.on("change",function(){
				var data = {mode:"update", table:"menu_detail_setting",
							id:tr.prop("title"), _name:$(this).val()};

				$.sendAjax(data);
			});
		var priceInput = tr.find("input.price").setTextStrCheck()
			.on("change",function(){
				var data = {mode:"update", table:"menu_detail_setting",
							id:tr.prop("title"), price:$(this).val()};
				$.sendAjax(data);
			});

		//選択済み切り替えアイコン
		var selectedIcon = tr.find(".selected_icon");
		var selIconImgs = selectedIcon.find("img");
		selectedIcon.on("click",function(){
				//offの時だけ
				if ($(this).find("img.off").css("display") != "none") {
					if (confirm("伝票の初回入力時に「" + nameInput.val()
							+ "」を選択済みにしてよろしいですか？")) {
						//DB更新
						var data = {
								mode:"md_change", id:tr.attr("title"),
								selected:1, menu_id:self.data("menu_id")
						}

						$.ajax({
							url: '../PHPClass/SalonSettingModel.php',
							type: "POST",
							data: data,
							success: function() {

								$.each(_menus,function(index,val){

									if (val.menu_id == self.data("menu_id")) {

										$.each(val.menu_datails,function(i,v){

											//同メニューの選択済み項目を非選択に
											if (v.selected == 1) {
												//menu_detailsを更新
												v.selected = 0;
												//アイコン切り替え
												tbody.find("tr[title="+ v.id +"] .selected_icon img").toggle();
											}

											//自分の
											if (v.id == tr.attr("title")) {
												v.selected = 1;
											}
										});
									}
								});

								//自分のアイコン切り替え
								selIconImgs.toggle();
							}
						});
					}
				}

			});

		//削除ボタンをセット
		tr.find(".delete_cell").append($.delIcon(tr,{
			size:24,
			beforeFunc:function(){
				return confirm(nameInput.val()+"を削除してもよろしいですか？");
			},
			afterFunc:function(){
				var data = {mode:"update", table:"menu_detail_setting",
						id:tr.prop("title"), deleted:1};
				$.sendAjax(data);
			}
		}).addClass("del_icon"));


		//値をセット
		if (mdData) {//メニュー詳細データがある時

			tr.attr("title",mdData.id);
			nameInput.val(mdData._name);
			priceInput.val(mdData.price);
			if (mdData.selected == 1) {
				selIconImgs.toggle();
			}


		}else {//追加ボタンで挿入された時
			//新規行クラスを付与
			tr.addClass("newmd");
			//選択済みアイコンを隠す
			selectedIcon.hide();
			//保存アイコンを生成し削除アイコンを隠す
			tr.find(".delete_cell").append(
					$("<img>").attr("src","../image/save_slim.png").css("width",24)
						.addClass("save_icon").on("click",function(){
							//名前、金額がある事を確認
							if (nameInput.val() != "" && priceInput.val() != "") {

								//並び順
								var order;
								//該当メニューの詳細項目配列
								var menuDetails;
								for (var i = 0; i < _menus.length; i++) {
									if (_menus[i].menu_id == self.data("menu_id")) {
										menuDetails = _menus[i].menu_datails;
										order = menuDetails.length;
									}
								}

								//ステータスを作成
								var status = {
										_name:nameInput.val(), price:priceInput.val(),
										_order:order, menu_id:self.data("menu_id"),
										selected:0
								}

								var data = {
										mode:"insert", table:"menu_detail_setting",
										_name:status._name, price:status.price,
										_order:status._order, menu_id:status.menu_id,
										salon_id:_salonId, data_type:"text"
								}

								$.sendAjax(data,{success:function(res){

									//dl:titleにレコードidをセット
									tr.attr("title",res);
									//ステータスをセット
									status.id = res;
									menuDetails.push(status);
									//tr.data("status",status);
									//アイコンきりかえ
									selectedIcon.show();
									tr.find(".save_icon").remove();
									tr.find(".del_icon").toggle();
									//新規行クラスを削除
									tr.removeClass("newmd");
								}});
							}else {
								alert("項目を入力してください");
							}
						}));
			tr.find(".del_icon").toggle();
		}
	}
	/*================================================*/

	/*==商品========================================*/
	//商品リスト追加許可
	$.fn.checkAddPro = function() {
		var auth = true;
		//新規行が存在していたらキャンセル
		if (proTBody.find("tr.newpro").length) {
			alert("最後の商品が保存されていません");
			auth = false;
		}
		return auth;
	}
	//商品行追加
	$.fn.addNewPro = function(proData) {
		//テンプレクローンし追加
		var tr = tempProTr.clone().removeAttr("id").show()/*.data("status",proData)*/;
		proTBody.append(tr);


		//setTextStrCheck():@string_check.js
		var nameInput = tr.find("input._name").setTextStrCheck()
			.on("change",function(){
				if ($(this).val() != $(this).data("prestr")) {
					var data = {mode:"update", table:"product_setting",
							id:tr.prop("title"), _name:$(this).val()}
					$.sendAjax(data);
				}
			});
		var priceInput = tr.find("input.price").setTextStrCheck()
			.on("change",function(){
				if ($(this).val() != $(this).data("prestr")) {
					var data = {mode:"update", table:"product_setting",
							id:tr.prop("title"), price:$(this).val()}
					$.sendAjax(data);
				}
			});

		//削除ボタンをセット
		tr.find(".delete_cell").append($.delIcon(tr,{
			size:24,
			beforeFunc:function(){
				return confirm(nameInput.val()+"を削除してもよろしいですか？");
			},
			afterFunc:function(){
				var data = {mode:"update", table:"product_setting",
						id:tr.prop("title"), deleted:1, delete_date:_today};
				$.sendAjax(data);
			}
		}).addClass("del_icon"));


		//値をセット
		if (proData) {//商品データがある時
			tr.attr("title",proData.id);
			nameInput.val(proData._name);
			priceInput.val(proData.price);

		}else {//追加ボタンで挿入された時
			//新規行クラスを付与
			tr.addClass("newpro");
			//保存アイコンを生成し削除アイコンを隠す
			tr.find(".delete_cell").append(
					$("<img>").attr("src","../image/save_slim.png").css("width",24)
						.addClass("save_icon").on("click",function(){
							//名前、金額がある事を確認
							if (nameInput.val() != "" && priceInput.val() != "") {

								//並び順を取得
								var myOrder = _products.length;


								//ステータスを作成
								var status = {
										_name:nameInput.val(),
										price:priceInput.val(),
										_order:myOrder
								}

								var data = {
										mode:"insert", table:"product_setting",
										_name:status._name, price:status.price,
										_order:status._order,
										salon_id:_salonId, data_type:"text"
								}
								$.sendAjax(data,{success:function(res){

									//dl:titleにレコードidをセット
									tr.attr("title",res);
									//_productsに追加
									status.id = res;
									_products.push(status);
									//アイコンきりかえ
									tr.find(".save_icon").remove();
									tr.find(".del_icon").toggle();
									//新規行クラスを削除
									tr.removeClass("newpro");
								}});
							}else {
								alert("項目を入力してください");
							}
						}));
			tr.find(".del_icon").toggle();
		}
	}

	/*============================================*/

	/*==エリア=========================================*/
	/*--エリア設定テーブルtrメソッド---------------*/
	$.fn.createAreaView = function() {
		var tr = $(this);
		//削除アイコン $.delIcon @common.js
		var deleteIcon =
			$.delIcon(tr,{
				size:24,
				beforeFunc:function(){

					if ($("#area_set table").find("tr:gt(0)").length <= 1) {
						alert("エリアを０にする事はできません");
						return false;
					}else {

						return tr.deleteArea();
					}
				},
				afterFunc:function(){
					//もし二つ以上trある場合基本設定の席数表示をなくしONにする。
					if($("#area_set table").find("tr:gt(0)").length == 1){
						/*
						areaListDetail.text("OFF").css("color", "gray");
						$("#seats_num_area").css("display", "block");
						$("#seats_num").val($("#area_set table").find("tr:gt(0)").find("input[type=seats]").val());
						*/
						location.reload();

					}
				}
			}).attr("title","delete");

		var td1 = $("<td>").append(
					$("<input>").attr({"title":"_name","type":"text"
							,"class":"faint not_null not_unique_char"})
							.setTextStrCheck());
		var td2 = $("<td>").append(
				$("<input>").attr({"title":"seats","type":"text"
					,"class":"faint narrow01 not_null not_unique_char only_num chkcode"})
					.setTextStrCheck());
		var td3 = $("<td>").append(deleteIcon)
					.append('<img title="add" alt="" src="../image/save_slim.png" style="display: none; width: 24px; height: 24px;">');
		var td4 = $('<td>').addClass("sort_cell").append($('<div>').html("&#9776;"));
		return $(this).append(td1).append(td2).append(td3).append(td4);
	}

	$.fn.setAreaInfo = function() {

		var status = $(this).data("status");


		//エリア名input
		$(this).find("input[title=_name]").val(status._name);

		//席数input
		$(this).find("input[title=seats]").val(status.seats);

		//onchange
		$(this).find("input[title=_name]").on("change",function(){
			//全角数値を半角に
			$(this).chkCode();//chkCode():@string_check.js

			if ($(this).strCheck()) {//strCheck():@string_check.js
				var data = "mode=area_change&id="+status.id+
					"&"+$(this).prop("title")+"="+$(this).val();

				$.post("../PHPClass/SalonSettingModel.php",data);
			}
		});
		$(this).find("input[title=seats]").on("change", function(){

			$that = $(this);
			if(parseInt(status.seats) < parseInt($(this).val())){
				$(this).chkCode();//chkCode():@string_check.js

				if ($(this).strCheck()) {//strCheck():@string_check.js
					var data = "mode=area_change&id="+status.id+
						"&"+$(this).prop("title")+"="+$(this).val();

					$.post("../PHPClass/SalonSettingModel.php",data);

					//area_settingテーブルの書き換え
					var data = "mode=area_change&id="+status.id+
					"&"+$that.prop("title")+"="+$that.val();
					$.post("../PHPClass/SalonSettingModel.php",data);

					//area_seats_settingテーブルのアップデートとインサート
					var dataForSeatsUpdate = {
							"mode": "seatsUpdate",
							"area_id": status.id,
							"seats": $that.val(),
							"end_date": _today
					};

					var dataForInsert = {mode: "insert",
										table: "area_seats_setting",
										area_id: status.id,
										seats: $that.val(),
										start_date:_today,
										end_date:'',
										disabled:0
								};
					//.then()使ってもinsertとupdateの処理順番がなぜか逆になる。なのでまずselectしそのあとid指定でupdateするように修正
					$.post("../PHPClass/SalonSettingModel.php",dataForSeatsUpdate).then($.sendAjax(dataForInsert));
				}
			}
			else if(parseInt(status.seats) > parseInt($(this).val())){
				$(this).chkCode();//chkCode():@string_check.js

				//席数が0にされた場合はキャンセル

				if (parseInt($(this).val()) == 0) {
					alert("席数を0にすることはできません");
					$that.val(status.seats);
					return false;
				}

				if ($(this).strCheck()) {//strCheck():@string_check.js
					//receiptsのチェック
					var dataForCheck = {"mode": "receipts_check",
										"oldSeats": status.seats,
										"newSeats": $(this).val(),
										"areaId": status.id};

					$.post("../PHPClass/SalonSettingModel.php",dataForCheck,
							function(dataReceived){
								var dataNow = JSON.parse(dataReceived);


								if(dataNow["flag"] == "NO"){
									var str = "削除対象の席にすでに予約があります。予約を他の場所に移動してください。\n【該当予約】\n" +
											"日付: "+dataNow["row"]["start"]+"〜"+dataNow["row"]["end"]+"\nスタッフ名: "+dataNow["row"]["staff_name"];
									alert(str);
									$that.val(status.seats);
								}
								else{
									//area_settingテーブルの書き換え
									var data = "mode=area_change&id="+status.id+
									"&"+$that.prop("title")+"="+$that.val();
									$.post("../PHPClass/SalonSettingModel.php",data);

									//area_seats_settingテーブルのアップデートとインサート
									var dataForSeatsUpdate = {
											"mode": "seatsUpdate",
											"area_id": status.id,
											"seats": $that.val(),
											"end_date": _today
									};

									var dataForInsert = {mode: "insert",
														table: "area_seats_setting",
														area_id: status.id,
														seats: $that.val(),
														start_date:_today,
														end_date:'',
														disabled:0
												};
									//.then()使ってもinsertとupdateの処理順番がなぜか逆になる。なのでまずselectしそのあとid指定でupdateするように修正
									$.post("../PHPClass/SalonSettingModel.php",dataForSeatsUpdate).then($.sendAjax(dataForInsert));
								}

							}
					);
				}
			}
		});
		return $(this);
	}

	$.fn.deleteArea = function() {
		var tr = $(this);

		var areaId = tr.data("status").id;
		//レコード更新許可フラグ
		var deleteAuth = false;
		//レコード更新完了フラグ
		var deleted = false;

		var sendingData = {
				"mode" : "check_last_res",
				"area_id" : areaId
				};
		//async: trueにするとエリアのtrが削除されない
		$.ajax({
			async : false,
			url: '../PHPClass/SalonSettingModel.php',
			type: "POST",
			dataType: "json",
			data: sendingData,
			success: function(res){
				if (res.last_res_date) {
					//エリアは削除されているのでエリア設定でやり直せない。（エリア設定画面でもう表示されない）
					if (confirm(res.delete_date+"日以降の予約表でエリアの削除が反映されます。"
							+res.last_res_date+"日まではすでに予約が入っているため" +
							"エリアの削除は反映されません。"+
							"すぐにこのエリアの削除を予約帳に反映させたい場合は、" +
							"今日以降の全ての予約を他のエリアに移動してからやり直してください。")) {
						deleteAuth = true;
					}
				}else {
					if (confirm("このエリアを削除しても現在の予約データの安全性は保たれます。" +
							"今日からこのエリアを削除してよろしいですか？")) {
						deleteAuth = true;
					}
				}

				if (deleteAuth) {
					var data = "mode=area_del&id="+areaId+
					"&deleted=1&delete_date="+res.delete_date;

					$.post("../PHPClass/SalonSettingModel.php",data);
					//tr.remove();
					var dataForSeats = {
							"mode": "seatsDisable",
							"end_date": res.delete_date,
							"area_id": areaId
					}
					$.post("../PHPClass/SalonSettingModel.php",dataForSeats);

					deleted = true;
				}
			}
		});
		return deleted;
	}
	/*-----------------------------------------*/
	//追加確定ボタンクリック時
	$.fn.decision = function() {

		//許可フラグ
		var authFlag = true;
		var tr = $(this).parents("tr");
		//文字チェック
		tr.find("input[type=text]").each(function(index,val) {
			//全角数値を半角に
			$(this).chkCode();//chkCode(),strCheck():@string_check.js
			authFlag = $(this).strCheck();
		});

		if (authFlag) {
			if (tr.parents("div").prop("id") == "area_set") {
				//エリア設定の場合
				if (confirm("エリアを追加してよろしいですか？追加する場合は今日から予約帳に反映されます")) {

					////console.log($("table#area_sortable tbody tr"))
					var maxOrder = 0;
					$("table#area_sortable tbody tr").each(function(index, element){
						if(index < $("table#area_sortable tbody tr").length - 1){
							if(maxOrder < parseInt($(element).data("status")._order)){
								maxOrder = parseInt($(element).data("status")._order);
							}
						}
					});

					var orderToInsert = parseInt(maxOrder) + 1;
					var data = "mode=area_add&_name="+tr.find("[title=_name]").val()+
						"&seats="+tr.find("[title=seats]").val()+
						"&start_date="+_today+"&_order="+orderToInsert;

					$.post("../PHPClass/SalonSettingModel.php",data,function(res){

						alert("エリアを追加しました");
						//アイコンを切り換え
						tr.find("img").toggle();
						//追加アイコンを表示
						$("#add_area").toggle();
						//返されたデータをセット
						tr.removeClass("trForAddition").data("status",JSON.parse(res)).setAreaInfo();
					});

					//もし二つ以上trある場合基本設定の席数表示をなくしONにする。
					if($("#area_set table").find("tr:gt(0)").length > 1){
						//areaListDetail.text("ON").css("color", "red");
						areaListDetail.changeOptionDetailList(true);
						$("#seats_num_area").css("display", "none");

					}
				}
			}
		}
	}
	/*=========================================*/

	/*==歩合パターン===============================*/
	//リスト追加
	$.fn.addPPList = function(type,ppRecord) {
		var tr = $("<tr>");
		var td1 = $("<td>");
		var td2 = $("<td>");
		var td3 = $("<td>");

		if (ppRecord) {
			tr.attr({"title":ppRecord.id,"data-not_yet":false});
			td1.text(ppRecord.percentage+" %");
			td2.appendRemRadio(type,ppRecord);
			td3.appendRemDel(tr,type);
		}else {
			tr.attr("data-not_yet",true);
			var perInput = $("<input>")
				.attr({"name":"percentage","class":"faint narrow02 chkcode not_null only_num not_unique_char"})
				.setTextStrCheck();
			td1.append(perInput).append(" %");
			td2.text("ー");
			td3.append($("<img>").attr({"src":"../image/save_slim.png"})
					.css({"width":24}).on("click",function(){

						if (perInput.val()) {
							var data = {
								mode:"insert",table:"percentage_pattern_setting_"+type,
								percentage:perInput.val(),salon_id:_salonId,
								data_type:"text"
							}

							$.sendAjax(data,{
								success:function(res){
									if (res) {

										tr.attr({"title":res,"data-not_yet":false});
										var rem = td1.find("input").val();
										td1.empty().text(rem+" %");
										td2.empty().appendRemRadio(type);
										td3.empty().appendRemDel(tr,type);
									}
								}
							});
						}else {
							alert("歩合率を入力してください");
						}
					}));
		}

		$("#"+type+"_pp_list").append(tr.append(td1).append(td2).append(td3));
		return $(this);
	}
	//削除ボタンをセットする
	$.fn.appendRemDel = function(tr,type) {

		$(this).append($.delIcon(tr,{
			size:24,
			beforeFunc:function(){
				//未登録の行を削除
				$("#"+type+"_pp_list tr[data-not_yet="+true+"]").remove();


				var success = false;
				var data = {
					mode:"delete",table:"percentage_pattern_setting_"+type,
					id:tr.prop("title")
				};
				$.sendAjax(data,{
					success: function() {
						success = true;
						//行削除
						tr.remove();
						//行が０になったら有効チェックボックスを無効に
						if ($("#"+type+"_pp_list tr[data-not_yet="+false+"]").length == 0) {
							//
							var remValid = $("#"+type+"_rem_valid");
							if (remValid.prop("checked") == true) {
								remValid.click();
							}
						}
					}
				});
				return success;
			}
		}));
	}
	//ラヂオボタンをセットする
	$.fn.appendRemRadio = function(type,ppRecord) {
		var radio = $("<input>").attr({"name":"selected_"+type,"type":"radio"})
				.on("click",function(){
					var data = {
						mode:"update_pp",type:type,
						id:$(this).parents("tr").prop("title")
					}

					$.ajax({
						async : true,
						url: '../PHPClass/SalonSettingModel.php',
						type: "POST",
						data: data,
					})
				});
		if (ppRecord) {
			if (ppRecord.selected == "1") {
				radio.prop("checked",true);
			}
		}
		$(this).append(radio);
	}
	/*============================================*/
}(jQuery));
