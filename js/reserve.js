/**
 *
 */
var initScrollValue = 0;
var editFlag = false;
var draggablePanel;
var reservLen;

//来店処理待ち伝票配列(会計済みのもの)
var notRemCompReceipts = [];

jQuery(function ($) {
	reservLen = _reservs.length;

	/*--日付セレクタ----------------------*/
	//月セレクタ
	var monsel = $("#month");
	$.each(_monthDatas,function(index,val){
		monsel.append($("<option>").val(val.value).text(val.text));
	});
	//月セレクタを選択済みに
	var monval = _condition.base_date.substr(0,7);
	monsel.val(monval).on("change",$(this).changeDate);
	//日セレクタ
	var datesel = $("#date");
	var y = _condition.base_date.substr(0,4);
	var m = _condition.base_date.substr(5,2);
	var d = _condition.base_date.substr(8,2);
	datesel.addDateOptions({year:y,month:m}).val(d)
		.on("change",$(this).changeDate);
	/*----------------------------------*/
	//rapTime = $.rapTime("a",rapTime);

	/*--今日明日ボタン-------*/
	$("#today, #tomorrow").on("click",$(this).changeDate);
	/*---------------------*/

	/*--ビュータイプボタン-----------------*/
	var btnArea = $("#viewtype_buttons")
	//エリアを追加
	if (_areas.length > 1) {
		$.each(_areas,function(index,val){
			//ボタンをクローンして追加
			var btn = btnArea.find("button:first").clone()
				.attr("id",val._name).text(val._name).appendTo(btnArea);
		});
	}

	//全ボタン設定
	$.each(btnArea.find("button"),function(index,val){
		//選択されているボタンはcssを変更
		if (val.id == _condition.view_type) {
			$(this).css({"background-color":"#e87e04","box-shadow":"0 0 0 0"})
				.prop("disabled",true);
		}
		$(this).on("click",$(this).changeViewType);
	});
	/*----------------------------------*/
	//rapTime = $.rapTime("b",rapTime);
	/*--時間バー生成----------------------------*/
	$.each(_times,function(index,val){
		$("#time_table")
			.append($("<tr>")
				.append($("<td>").text(val.text))
				.append($("<td>").attr("class","capa")));
	});
	/*------------------------------------------*/
	//rapTime = $.rapTime("c",rapTime);
	/*--予約表生成---------------------------------*/

	//ビュータイプに合わせてエリアテーブルを生成
	$.each(_areas,function(index,val){

		if (_condition.view_type == "nomal" || _condition.view_type == "all") {
			$.createInnerCapabar(val);
			$.createAreaReserveTable(val);
		}else {
			if (val._name == _condition.view_type) {
				$.createInnerCapabar(val);
				$.createAreaReserveTable(val);
			}
		}
	});
	/*-----------------------------------------------------*/
	//rapTime = $.rapTime("d",rapTime);
	/*==各要素の設定==========================================*/
	/*--値設定-------------*/
	var capaBarWidth = 20;//inner_capa_barの幅
	if (_areas.length == 1) { capaBarWidth = 0; }
	var captionHeight = 24;//area_reserv_table captionの高さ
	//var reservCellWidth = 36;//area_reserv_table td幅
	var reservCellWidth = $.reservCellWidth(capaBarWidth);

	/*-----------------------*/
	//time_colmun
	$("#time_colmun").css("padding-top",captionHeight);

	//inner_capa_bar
	$(".inner_capa_bar").css({"padding-top":captionHeight,"width":capaBarWidth});

	/*--area_reserv_table設定---------*/
	//caption
	$(".area_reserv_table caption").height(captionHeight);
	$.each($(".area_reserv_table"),function(index,value){
		var cellNum = this.rows[0].cells.length;
		var w = $.areaTableWidth(cellNum,reservCellWidth);
		$(this).find("caption").width(w).height(captionHeight);
	});

	//td
	$(".area_reserv_table tr td").css("width",reservCellWidth)
		.attr("title","off").on("click",function(){

			//スタッフ数が0の場合は拒否
			if (_staffs.length == 0) {
				alert("予約を登録するにはスタッフを登録する必要があります" +
						"\n「スタッフ設定」でスタッフを登録してください");
				return false;
			}

			//昨日以前の場合は拒否
			if (_dateType == "old") {
				alert("過去の予約を追加・編集する事はできません");
				return false;
			}

			/*--クリックされたセルの時間・席を取得する--------*/
			//該当エリア
			var area = $(this).parents("table").data("area_id");
			//席
			var seat = $(this).index();
			//セルの行
			var row = $(this).parent().index();
			//対応する時間文字列
			var time = $("#time_table tr:eq("+row+") td:first").text();
			/*------------------------------------*/

			//スライドopen 初期データを作りdataに情報を格納
			var data = {start:_condition.base_date+" "+time+":00",
					area_id:area,seat:seat,/*startTime:time,*/mode:"add"};
			$("#right_slide").open({
        dispContentsId: 'reserve_input',
        openFunc:true,
        closeFunc:true,
        data:data,
        title:"予約"
      });
		});
	/*-------------------------------*/

	//box
	$("#box").css("width",$.boxWidth(capaBarWidth));

	/*===============================================================*/
	//rapTime = $.rapTime("e",rapTime);
	/*==予約パネル====================================*/
	///予約パネルを表示
	if (_reservs.length > 0) { $.createReservPanel(); }
	//rapTime = $.rapTime("f",rapTime);
	//draggable
	//スタッフログインの場合は自分のパネルのみ
	if (_visiter == "staff") {
		draggablePanel = $( "div.panel" ).filter(function() {
			return $(this).data("res_data").staff_id == _staffId;

		});

	}else {
		draggablePanel = $( "div.panel" );
	}

	//$( "div.panel" ).draggable({
	draggablePanel.draggable({
		containment: 'div #reserv_alea',
		helper: "clone",
        revert: "invalid",
        revertDuration: 200,
        delay: 400,
        start: function(event, ui){
        	$(this).data("scrollposLeft", $("#reserv_alea").scrollLeft());
        	$(ui.helper).addClass("panel_popup");


    		if($('#reserv_alea').scrollLeft() != 0){
    			initScrollValue = $('#reserv_alea').scrollLeft();
    		}

        },
        drag: function(event, ui){
        	$("body").one("mousemove", function(e){
        		var x = e.pageX;
        		var y = e.pageY;
        		var reserv_alea = document.getElementById("reserv_alea");
        		var addScroll = function(){reserv_alea.scrollLeft += 30;};
        		var minusScroll = function(){reserv_alea.scrollLeft -= 30;};
        		if(window.innerWidth - x < 30){
        			addScroll();
        		}
        		if(x < 105){
        			minusScroll();
        		}
        	});
        	ui.position.left += ($('#reserv_alea').scrollLeft() - initScrollValue);


        },
	    stop: function(event, ui){
	    	//Reset
	    	initScrollValue = 0;

		    $(ui.helper).removeClass("panel_popup");
		}
	});
	//rapTime = $.rapTime("g",rapTime);
	//droppable
	//var td = $("div#reserv_alea table.area_reserv_table td:not(:has(div.panel))");
	//var td = $("#reserv_alea .area_reserv_table td[title=off]");


	//最初はdisableにしておく
	//$("div.panel").draggable("disable");
	draggablePanel.draggable("disable");

	$("#reserv_alea .area_reserv_table td:not(.noDrop)").droppable({
		accept: "div.panel",
		tolerance: "makoto",
		drop: function(event, ui){
			var row = $(this).parent().index();

			var startTime = $("#time_table tr:eq("+row+") td:first").text();
			//var startTime = _times[row].text;
			//var startSQLDate = _selectedDate + " " + startTime + ":00";
			var startSQLDate = _condition.base_date + " " + startTime + ":00";
			var startDate = startSQLDate.replace(/-/g, '/');
			var startMilliMinutes = Date.parse(startDate);

			//var timeHeight = getTimeHeight(_reservs[$(ui.draggable).data("reservsIndex")]["start"], _reservs[$(ui.draggable).data("reservsIndex")]["end"]);
			var timeHeight = $.getTimeHeight(_reservs[$(ui.draggable).data("reservsIndex")]["start"], _reservs[$(ui.draggable).data("reservsIndex")]["end"]);
			//var unit = parseInt(_timeUnit[1]);
			var unit = parseInt(_minUnit);
			var minutes = timeHeight * unit;
			var milliMinutes = minutes * 60000;

			var endMilliMinutes = startMilliMinutes + milliMinutes;
			var endDateObj = new Date(endMilliMinutes);
			var endHours =  endDateObj.getHours();
			var endMinutes = endDateObj.getMinutes();
			if(!endMinutes){endMinutes = "00"};
			var endTime = endHours + ":" + endMinutes;

			var table = $(this).parents("table");
			var areaName = table.children("caption").text();
			var seat = $(this).index();

			var recId = _reservs[$(ui.draggable).data("reservsIndex")]["rec_id"];

			//空席チェック



			var moveAuth = true;
			for (var i = row; i < row + timeHeight; i++) {

				var offCell = table.find("tr:eq("+i+") td[title=off]");
				var myRecCell = table.find("tr:eq("+i+") td[title="+recId+"]");



				if (offCell.length == 0 && myRecCell.length == 0) {

					alert(areaName + "の" +
							$("#time_table tr:eq("+i+") td:first").text() +
							"の席が空いていません");
					moveAuth = false;
				}
			}


			if (moveAuth) {
				if(window.confirm(areaName + "の" + startTime + "〜" + endTime + "に移動してもよろしいですか？")){
					//ajax
					sendingData = {
								   "date" : _condition.base_date,
								   "startTime" : startTime,
								   "endTime" : endTime,
								   "areaId" : $(this).parents("table").data("area_id"),

								   "recId" : recId,
								   "mode" : "panel_move",
								   "seat" : seat
								  };

					$.ajax({
						async: false,
						url: '../PHPClass/ReserveModel.php',
						type: "POST",
						dataType: "json",
						data: sendingData,
						success: function(data) {
							if(data.flag === true){
								location.reload(true);
							}
						}
					});
				}
			}


		}
	});
	//rapTime = $.rapTime("h",rapTime);
	//パネルを設定
	$.panelSetting();
	//rapTime = $.rapTime("i",rapTime);
	/*==============================================*/

	/*==空席数表示=================================================*/
	//inner_capa_barの数字をセット
	var innerCapaTable = $(".inner_capa_table");
	$.each(innerCapaTable,function(index,val){
		//エリアID
		var areaId = $(this).data("area_id");
		var rows = $(this).find("tr")
		var len = rows.length;
		for (var i = 0; i < len; i++) {
			var length = $("#area_"+areaId+" tr:eq("+i+") td[title=off]").length;
			rows.eq(i).children("td").text(length);
		}
	});
	//トータルの空席数をセット
	var timeTableTr = $("#time_table tr");
	var len = _times.length;
	for (var i = 0; i < len; i++) {
		var totalCapa = 0;
		var len2 = innerCapaTable.length;
		for (var n = 0; n < len2; n++) {
			totalCapa = totalCapa +
				parseInt(innerCapaTable.eq(n).find("tr").eq(i).find("td").first().text());
		}
		timeTableTr.eq(i).find("td.capa").text(totalCapa);
	}
	/*=====================================================*/

	/*==稼働率表示================================*/
	if (_visiter != "staff") {
		// var div = $("div#occupancy_rate");
		// $.occupancyRate(div);
		// div.show();
	}
	/*==========================================*/
	////rapTime = $.rapTime("j",rapTime);
	//rapTime = $.rapTime("j",rapTime);
	//$.rapTime("reserve_end",rapTime);

	//　edit buttonの設置

	var editButton = $('<img>').attr("src", "../image/drag_drop.png");
	var editingButton = $('<img>').attr("src", "../image/drag_drop_red.png").hide();

	var rightDiv = $('<div>').attr("id","drag_controller").addClass("rightTab").on("click", function(){

		if(editFlag == false){

			draggablePanel.draggable("enable");
			editFlag = true;

			var str = "";
			if (_visiter == "staff") {
				str = "あなたの";
			}
			alert("ドラッグモードに切り替えました\n" + str +
					"予約パネルをドラッグ&ドロップして、時間・席を変更できます");

			//予約ポップアップがを隠す
			$("#popup_view").hide();

			//@common.js
			$.showStateGuidePopup("ドラッグモード").attr("id","dragmode_popup").on("click",function(){
				$(this).remove();
			}).find(".closer").css("padding","0 10px");
		}
		else if(editFlag == true){

			draggablePanel.draggable("disable");
			editFlag = false;

			alert("ドラッグモードを解除しました");

			$("#dragmode_popup").remove();
		}
		$(this).children().toggle();
	});

	var rightTab = rightDiv.append(editButton).append(editingButton);
	$('#wrap').append(rightTab);


	/*--すべての表示が終わったあとに来店処理通知領域を表示----------*/
	if (_visiter == "salon" && notRemCompReceipts.length > 0) {

		$("#posting_rem_comp_area").fadeIn(1000);
		$("#num_not_rem_comp").text(notRemCompReceipts.length);
		$("#rem_comp_link").on("click",function(){
			var myPassword = prompt("パスワードを入力してください","");
			if(myPassword == _password){
				location.href = "receipt_list.php?rem_comp_check=true";
			}else if (myPassword == null){
				//何もしない
				return false;
			}else{
				alert("パスワードが間違っています");
				return false;
			}
		});
	}
	/*-------------------------------------------------*/
});





(function($) {
	//予約帳表示日付変更
	$.fn.changeDate = function() {

		var type;
		//切り替え許可フラグ
		var changeAuth = true;

		//登録日と選択日を比較チェック
		if (this.id == "month" || this.id == "date") {//月・日セレクタ選択時

			if (this.id == "month") {
				type = $("#month").val()+"-01";
			}else if (this.id == "date") {
				type = $("#month").val()+"-"+$("#date").val();
			}

			//登録日
			var admission_date = _planManager.planStatus.admission_date;
			var admissionDate = new Date(admission_date);
			//選択日
			var newDate = new Date(type);



			//ユーザー登録日以前の日付が選択された場合の処理
			if (newDate < admissionDate) {

				if (this.id == "month"
					&& newDate.getMonth() == admissionDate.getMonth()
					&& newDate.getDate() < admissionDate.getDate()) {
					//月セレクタ選択時で選択日が登録日と同月の登録日以前の場合は選択日を登録日に変更
					type = admission_date;
				}else {
					//それ以外はキャンセル
					changeAuth = false;
				}
			}

		}else {//今日明日ボタン
			type = this.id;
		}


		if (changeAuth) {
			var data = {mode:"change_date", type:type};
			$.ajax({
				url: '../PHPClass/ReserveModel.php',
				type: "POST",
				data: data,
				success: function() {
					window.location.reload();
				}
			});

		}else {
			alert("ユーザー登録日より前の予約帳を表示することはできません。");
			window.location.reload();
		}


	}
	//予約帳ビュータイプ変更
	$.fn.changeViewType = function() {
		//var rapTime = $.rapTime("change_viewtype_start");

		var data = {mode:"change_viewtype", type:this.id};
		$.ajax({
			url: '../PHPClass/ReserveModel.php',
			type: "POST",
			data: data,
			success: function() {
				window.location.reload();
			}
		});
	}


	/*==初期設定==========================================*/
	//エリア別Capabar生成
	$.createInnerCapabar = function(area) {

		var table = $("<table>")
			.attr({"class":"inner_capa_table","data-area_id":area.id});

		//時間軸にあわせて行を追加
		$.each(_times,function(index,val){
			table.append($("<tr>").append($("<td>")));
		});

		$("#box").append(
				$("<div>").attr("class","inner_capa_bar").append(table));
	}
	//エリアテーブル生成
	$.createAreaReserveTable = function(area) {
		//エリア名
		var areaName = area._name;
		if (_areas.length == 1) {
			areaName = "";
		}
		var table = $("<table>")
			.attr({"class":"area_reserv_table","data-area_id":area.id})
			.attr("id","area_"+area.id).append($("<caption>").text(/*area._name*/areaName));

		//時間軸にあわせて行を追加
		$.each(_times,function(index,val){

			//営業時間前後の時間帯の背景色
			var bg = "white";
			if (val.biz_type == "outbiz") { bg = "#ecf0f1"; }
			//ボーダースタイル指定
			var bd = "1px solid #95a5a6";
			if (val.min_type == "un_oclock") { bd = "1px dashed #95a5a6"; }

			var tr = $("<tr>");
			//席数分のtdを追加
			for (var i = 0; i < area.seats; i++) {
				tr.append($("<td>").addClass(val.biz_type).css({"background-color":bg,
					"border-right":"1.0px solid #95a5a6","border-top":bd}));
			}
			table.append(tr);
		});
		//return table;
		$("#box").append(table);
	}

	//予約帳セル幅
	$.reservCellWidth = function (capaBarWidth) {
		//リザーブエリアの幅
		var areaWidth = $("#reserv_alea").width();
		//セル幅
		var w;


		if (_condition.view_type == "nomal") {//通常表示
			w = 36;
			//通常表示がareaWidthよりも狭くなる場合は全席表示

			if (totalWidth(w) < areaWidth) {
				cwAll();
			}
		}else if (_condition.view_type == "all") {//全席表示
			cwAll();
		}else {//単エリア表示
			for (var i = 0; i < _areas.length; i++) {
				if (_areas[i]._name == _condition.view_type) {

					//エリア席数
					var seats = _areas[i]["seats"];
					//$.areaTableWidthと逆の計算をする
					w = (areaWidth - capaBarWidth - (seats -1) -seats) /seats;
				}
			}
		}

		/*--reservCellWidth()内メソッド-------*/
		//全席表示にセル幅を設定
		function cwAll() {
			//エリア数
			var len = _areas.length;
			//全席数
			var allSeats = 0;
			$.each(_areas,function(index,val){
				allSeats = allSeats + parseInt(val["seats"]);
			});
			w = (areaWidth - capaBarWidth*len - (allSeats -1) -allSeats) /allSeats;

			//最低限のセル幅を確保する
			if (w < 12) {
				w = 12;
			}
		};
		//仮の予約帳幅を算出
		function totalWidth(cellW){
			var totalW = 0;
			$.each(_areas,function(index,val){
				totalW = totalW + capaBarWidth +
							$.areaTableWidth(parseInt(val["seats"]), cellW);
			});
			return totalW;
		};
		/*----------------------------------*/

		return w;
	}
	//エリアテーブル幅
	$.areaTableWidth = function(cellNum,reservCellWidth){
		//最後の「(cellNum -1)」は謎だが何故かこうなる
		return reservCellWidth * cellNum + cellNum + (cellNum -1);
	}
	//包括box幅
	$.boxWidth = function (capaBarWidth) {
		var w = 0;
		$.each($(".area_reserv_table"),function(index,val){
			w = w + capaBarWidth + $(this).width();
		});
		//各ブラウザ用に少し余裕を持たせる
		w = w +1;
		return w;
	}
	/*==============================================*/

	/*==予約パネル表示================================*/
	$.createReservPanel = function() {
		//alert("create!");


		//セルの高さ
		var cellHeight = $(".area_reserv_table tr td").height();

		//予約ごとにパネルを生成して表示
		//var reservLen = _reservs.length
		for ( var i = 0; i < reservLen; i++) {

			var reserve = _reservs[i];


			/*--予約情報を抽出----------------*/
			//スタート行位置
			var row = parseInt($.getRow(reserve.start));


			//席
			var seat = parseInt(reserve.seat);

			//コマ数
			var timeHeight =
				$.getTimeHeight(reserve.start, reserve.end);

			var panelHeight = cellHeight*timeHeight*0.98+(timeHeight-1);


			//客名
			var customer = "";
			if (reserve.costomer) {
				customer = reserve.costomer+"様";
			}

			//パネル透明度
			var panelOpacity = 0.8;

			/*--今日データ-----------------*/
			var come = null;//来店フラグ
			if (_dateType == "same") {
				come = reserve.come;
				if (reserve.out_ == 1) {panelOpacity = 0.4;}
			}
			/*----------------------------*/

			/*--パネル-------------------------*/
			//パネル
			var panel = $("<div>").attr("class","panel")
				               .css({"background-color":reserve.staff_color,
				            	   		'opacity':panelOpacity,
									'height':panelHeight,
									'top':panelHeight*0.01})
								.data("res_data",reserve)
								.data("reservsIndex", i);

			//パネル内コンテンツ
			var contents = $("<div>").attr("class","panel_contents")
							.css({"white-space":"nowrap"})
							.html(reserve.staff_icon+"<br>"+customer
									+"<br>"+$.getMenuStr(reserve.menus));

			panel.append(contents);
			if (reserve.come == 1 && reserve.out_ != 1) {
				panel.postitStamp({putStamp:come,className:"leftstamp"});
			}
			if (reserve.num_visit == 1) {
				panel.putStamp({src:"../image/star.png",className:"stampbox"});
				if (reserve.point_id) {
					panel.putStamp({src:"../image/p.png",className:"stampbox2"});
				}
			}else {
				if (reserve.point_id) {
					panel.putStamp({src:"../image/p.png",className:"stampbox"});
				}
			}

			/*---------------------------------------*/
			/*--------------------------------------*/
			//表示対象テーブル（予約表）
			var table = $("#area_"+reserve.area_id);


			//表示
			for (var n = 0; n < timeHeight; n++) {

				var putRow = n + row;
				var cell = table.find("tr:eq("+putRow+") td:eq("+seat+")");


				if (n != 0) {
					//２行目以降はパネルを削除
					panel = null;
				}

				if (cell.prop("title") == "off") {
					cell.attr("title",reserve.rec_id).append(panel);
				}else{

					var newCellIndex = cell.getNewCell(reserve, i)
						.attr("title",reserve.rec_id)
						.append(panel).index();
					//一時的に席番号を変更
					//ajaxでDBのnewCellIndexの値にseatをかきかえる, reserve.rec_id, $.postで。
					seat = newCellIndex;
					var sendingData = {
							mode: "update",
							table: "receipts_"+_salonId,
							seat: seat,
							id:reserve.rec_id
					};
					$.sendAjax(sendingData);
				}

				if(n == 0){
					//一番上のcellにはdropできないようにする
					//cellは変更されている可能性があるのでseatを使う
					//cell.addClass("noDrop");
					table.find("tr:eq("+putRow+") td:eq("+seat+")").addClass("noDrop");
				}
			}

			/*--サロンの場合は来店処理待ち配列を操作-----------*/
			if (_visiter == "salon") {
				if (reserve.rec_comp == 1 && reserve.rem_comp == 0) {
					notRemCompReceipts.push(reserve);
				}
			}
			/*--------------------------------------------*/
		}
	}
	//予約被り時の代替cellIndexを取得
	$.fn.getNewCell = function(reserve,indexAtReserves) {

		//該当の時間
		var start = reserve.start;
		//該当エリア
		var areaId = reserve.area_id;
		//該当席番号
		var thisIndex = $(this).index();
		//自分を含む同行のセル
		var cells = $(this).parent().children();

		/*--席状態管理配列を作成------------------*/
		//初期化
		var seatCondition = [];
		//現在の席状態を反映(titleをそのまま追加)
		cells.each(function(index,val) {
			seatCondition.push(val.title);
		});

		//まだ表示されていない予約分も反映
		var deadSeat = [];
		for (var i = indexAtReserves; i < reservLen; i++) {
			var r = _reservs[i];
			if (r.rec_id != reserve.rec_id) {//自分以外の
				if (r.start == start && r.area_id == areaId) {
					//同時間startの予約かつ同エリア
					seatCondition[r.seat] = r.rec_id;
				}else {//それ以外はスルー（時刻順に並んでいるので時刻が変わったらbreak）
					break;
				}
			}
		}

		/*----------------------------*/

		/*--左右それぞれ一番近い空席番号を取得---------------*/
		var leftCellIndex_ = null;
		var rightCellIndex_ = null;

		var len = seatCondition.length;

		//左側を探索
		for (var i = thisIndex - 1; i >= 0; i--) {
			if (seatCondition[i] == "off") {
				leftCellIndex_ = i;
				break;
			}
		}
		//右側を探索
		for (var i = thisIndex + 1; i < len; i++) {
			if (seatCondition[i] == "off") {
				rightCellIndex_ = i;
				break;
			}
		}

		/*------------------------------------*/

		/*--左右比較し近い方の席番号取得------------*/
		var newCellIndex;
		if (leftCellIndex_ == null) {//片方しかなければそのセルを返す
			newCellIndex = rightCellIndex_;
		}else if (rightCellIndex_ == null) {//片方しかなければそのセルを返す
			newCellIndex = leftCellIndex_;
		}else {//両方ある時はどちらが近いか判別
			//左との差分
			var leftDiff = thisIndex - leftCellIndex_;
			//右との差分
			var rightDiff = rightCellIndex_ - thisIndex;

			//差が少ない方の席番号を返す（同じ場合は左を返す）
			if (leftDiff <= rightDiff) {
				newCellIndex = leftCellIndex_;
			}else {
				newCellIndex = rightCellIndex_;
			}
		}

		/*----------------------------------*/

		return cells.eq(newCellIndex);
	}
	//予約開始時刻をテーブルの行に変換
	$.getRow = function(time) {
		var start = time.slice(11,-3);
		var len = _times.length;
		for (var i = 0; i < len; i++) {
			if (_times[i].text == start) {
				return i;
			}
		}
	}
	//start,endから時間コマ数を返す
	$.getTimeHeight = function(start,end) {
		//文字列を日付に変換
		var newS = start.replace(/-/g, '/');
		var newE = end.replace(/-/g, '/');
		var startD = Date.parse(newS);
		var endD = Date.parse(newE);
		//差分を計算
		var interval = endD - startD;
		//分単位に変換
		var minute = interval / 60000;
		//設定されたminute分割単位で分割
		var timeHeight = minute / _minUnit;
		return timeHeight;
	}
	//メニュー文字列返す
	$.getMenuStr = function (menus) {
		var str = "";
		var len = menus.length
		if (len > 0) {
			for (var n = 0; n < len; n++) {
				str = str + menus[n].str_icon;
			}
		}
		return str;
	}
	/*--全てのパネル設定-------------------*/
	$.panelSetting = function() {
		$(".panel").on("click",function(e){


			//レコード情報を取得
			var reserv = $(this).data("res_data");
			var resIndex = $(this).data("reservsIndex");

			/*--ポップアップのコンテンツ-------*/
			var resData = {"スタイリスト" : reserv.staff_icon,
							"名前" : reserv.costomer,
							"メニュー" : $.getMenuStr(reserv.menus),
							"施術料金" : reserv.tec_sale,
							"メモ" : reserv.memo};

			//リスト
			var list = $("<dl>").attr("class","conpact");
			for ( var key in resData) {
				list.append($("<dt>").text(key))
					.append($("<dd>").text(resData[key]));
			}

			/*--他スタッフの場合の拒否フラグ(編集ボタン、todaybox表示時に判別)------*/
			var editAuth = true;
			if (_visiter == "staff" && _staffId != reserv.staff_id) {
				editAuth = false;
			}
			/*----------------------*/

			//今日以降で未会計の場合は編集ボタン
			var edit = null;

			if (_dateType != "old" && reserv["rec_comp"] != 1 && editAuth) {
				edit = $("<a>").attr({"class":"button","href":"javascript:void(0)"})
				.on("click",function(e){
					//スライドopen dataに予約情報を格納
					var data = reserv;
					data.mode = "edit";
					$("#right_slide").open({
            dispContentsId: 'reserve_input',
            openFunc:true,
            closeFunc:true,
            data:data
          });
				}).text("編集する");
			}

			//今日の場合はtodayboxを表示
			var todaybox = null;
			if (_dateType == "same" && editAuth) {
				todaybox = $("<div>").attr("id","today_box");

				//来店チェックbox
				var comeFlag = false;
				if (reserv.come == 1) { comeFlag = true; }
				//会計済み時にはcheckboxを無効
				var recCompFlag = false;
				if (reserv["rec_comp"] == 1) { recCompFlag = true; }


				var come = $("<div>").attr("class","seg_contents")
						.append($("<input>")
							.attr({"type":"checkbox","id":"come",
									"checked":comeFlag,"disabled":recCompFlag})
							.on("click",function(e){

								var checked;
								if ($(this).prop("checked")) {
									checked = 1;
								}else {
									checked = 0;
								}

								var data = {mode:"check_come", come:checked, id:reserv.rec_id};
								$.ajax({
									async: false,
									url: '../PHPClass/ReserveModel.php',
									type: "POST",
									data: data,
									success: function() {
										//予約データを更新
										reserv.come = checked;
										if (checked) {
											todaybox.append($.createPayButton(reserv));
										}else {
											//payボタンを削除
											$("#pay_btn").remove();
										}

										//来店ラベルをセット @:stamp.js
										$("td[title="+reserv.rec_id+"] .panel")
											.postitStamp({putStamp:checked,className:"leftstamp"});
									}
								});
							}))
						.append($("<label>").attr("for","come").text(" 来店"));

				//荷物box
				var bag = $("<div>").attr("class","seg_contents")
						.text("荷物 ")
						.append($("<input>")
							.attr({"type":"text","id":"bag",
									"class":"narrow01","data-res_id":reserv.rec_id,
									"value":reserv.bag})
							.css({"border":"1px solid #bdc3c7","font-size":14,"color":"#637688","padding":2})
							.on("change",function(){
								//荷物を保存
								var bag = $(this).val();
								var data = {mode:"save_bag", bag:bag, id:reserv.rec_id};
								$.ajax({
									async: false,
									url: '../PHPClass/ReserveModel.php',
									type: "POST",
									data: data,
									success: function() {
										//データを更新
										reserv.bag = bag;
									}
								});
							}));
				//包括box
				var boxies = $("<div>").attr("class","clearfix segment02")
						.append(come).append(bag);

				//仮登録ボタン
				var presubBtn = null;
				if (!recCompFlag) {
					presubBtn = $.createPreSubButton(reserv);
				}
				//会計ボタン
				var payBtn = $.createPayButton(reserv);

				todaybox.append(boxies).append(presubBtn).append(payBtn);
			}

			var contents = $("<div>").attr("class","contents_area01")
							.append(list).append(edit).append(todaybox);
			/*----------------------------------*/
			var popupOption = {width:158,type:"left_top",contents:contents};
			$.createPopupView(popupOption);//@popup.js

			//バブリングを止める
			e.stopPropagation();
		});
	}
	//仮登録ボタン
	$.createPreSubButton = function(reserv) {
		var btn = $("<a>").attr({"class":"button",
			"href":"javascript:void(0)","id":"presub_btn"});
		btn.on("click",function(e){
			window.location = "receipt.php?mode=pre_sub" +
					"&rec_id="+reserv.rec_id;
			}).text("仮登録").css("background-color","slategray");
		return btn;
	}
	//会計ボタン
	$.createPayButton = function (reserv) {
		if (reserv.come == 1) {
			var payBtn = $("<a>").attr({"class":"button",
				"href":"javascript:void(0)","id":"pay_btn"});
			if (reserv.rec_comp != 1) {
				payBtn.on("click",function(e){
					//alert(reserv["id"]);
					window.location = "receipt.php?mode=register" +
							"&rec_id="+reserv.rec_id;
					}).text("お会計");
			}else{
				payBtn.text("会計済み").css("opacity",0.7);
			}
			return payBtn;
		}
		return null;
	}
	/*------------------------------------*/
	/*==============================================*/
}(jQuery));
