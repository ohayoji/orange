jQuery(function ($) {
	
	
	
	////console.log("_todayRecCompReserves",_todayRecCompReserves);//
	
	
	/*--月セレクタ----------------*/
	var monSel = $("select[name=month]");
	
	//月セレクタ初期設定
	$.each(_monthDatas,function(index,val){
		monSel.append($("<option>").val(val.value).text(val.text));
	});
	monSel.val(_selectedMonth)
		.on("change",function(e){ $("#dr_form").submit(); });
	/*------------------------------*/
	
	/*--月合計表示----------------------*/
	var totalTec = parseInt(_totalReport.tec_sale);
	var totalPro = parseInt(_totalReport.pro_sale);
	var totalCash = parseInt(_totalReport.cash_tec) + parseInt(_totalReport.cash_pro);
	var totalCard = parseInt(_totalReport.card_tec) + parseInt(_totalReport.card_pro);
	
	$("#total [title=num]").text($.delimiting(_totalReport.count)+"人");
	
	$("#total [title=all]").text($.delimiting(totalTec+totalPro));
	
	$("#total [title=tec]").text($.delimiting(totalTec));
	$("#total [title=pro]").text($.delimiting(totalPro));
	
	$("#total [title=cash]").text($.delimiting(totalCash));
	$("#total [title=card]").text($.delimiting(totalCard));
	/*---------------------------------*/
	
	/*--リスト表示------------------------*/
	//テンプレ
	var templi = $("#temp_li");
	$.each(_dailyReport,function(index,val){
		
		var tec = 0, pro = 0;
		if (val["tec_sale"]) { tec = parseInt(val["tec_sale"]);}
		if (val["pro_sale"]) { pro = parseInt(val["pro_sale"]);}
		var cash = 0, card = 0;
		if (val["cash_tec"]) { cash = cash+parseInt(val["cash_tec"]);}
		if (val["cash_pro"]) { cash = cash+parseInt(val["cash_pro"]);}
		if (val["card_tec"]) { card = card+parseInt(val["card_tec"]);}
		if (val["card_pro"]) { card = card+parseInt(val["card_pro"]);}
		
		//日別リスト
		var li = templi.clone().show().attr("id","date_"+val["date"]);
		
		li.find(".date").text(
				val["date"]+
				"日（"+$.createDayFromSQLDAYNAME(val["dayname"])+"）");
		li.find(".num").text($.delimiting(val["count"]));
		li.find(".tec").text($.delimiting(tec));
		li.find(".pro").text($.delimiting(pro));
		li.find(".cash").text($.delimiting(cash));
		li.find(".card").text($.delimiting(card));
		li.find(".total").text($.delimiting(tec+pro));
		
		templi.before(li);
	});
	
	//今月なら今日に近い日までスクロール
	if (monSel.val() == _today.substr(0,7)) {
		var today = parseInt(_today.substr(8));
		for (var i = today; i > 0; i--) {
			var dateBox = $("#date_"+i);
			if (dateBox.length > 0) {

				$("html,body").animate({
				    scrollTop : dateBox.offset().top,
				    duration: 100
				}, {queue : false});
				//return false;
				break;//純粋なjsではbreak
			}
		}
		//会計情報をアラート表示
		setTimeout(function (){
			alert("本日の予約は "+ _todayReserves +" 件です" +
					"\n会計済み "+ _todayRecCompReserves +" 件" +
							"\n未会計 "+ _todayNotRecCompReserves +" 件" +
									"\nです");
		},100);
	}
	
	/*----------------------------------*/
});