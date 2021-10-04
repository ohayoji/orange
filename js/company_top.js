/**
 *
 */
jQuery(function ($) {
	
	//console.log("_selectorOptions", _selectorOptions)
	/*--月セレクタ----------------*/
	var monSel = $("select[name=month]");

	//月セレクタ初期設定
	$.each(_selectorOptions,function(index,val){
		monSel.append($("<option>").val(val.value).text(val.text));
	});
	monSel.val(_selectedMonth)
		.on("change",function(e){ $("#ct_form").submit(); });
	/*------------------------------*/

	/*--centerbox-----------------------*/
	var allAmount = 0;
	$.each(_salonRep,function(index,val){

		//金額を計算
		var tec = 0, pro = 0;
		if (val.tec_total) { tec = val.tec_total; }
		if (val.pro_total) { pro = val.pro_total; }
		var total = parseInt(tec) + parseInt(pro);

		var dd = $("dd.temp_dd").clone().removeClass("temp_dd").css("display","block");
		dd.find("div.name").text(val._name);
		dd.find("div.amount").text($.delimiting(CURRENCY+total));

		$("dl").append(dd);

		allAmount = allAmount + total;
	});
	$("dt").text(CURRENCY + $.delimiting(allAmount));
	/*----------------------------------*/
});
