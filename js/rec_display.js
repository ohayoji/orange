var totalSales;
var totalSalesDisplay;
var registerButtons;//会計ボタン
var cashCarcBox;//現金会計計算ボックス
var thankyouMassage;//御会計後のありがとうメッセージ

var finished = false;//お会計完了フラグ

jQuery(function ($) {

	totalSales = parseInt(_receipt.tec_sale) + parseInt(_receipt.pro_sale);
	totalSalesDisplay = $("#total");
	registerButtons = $("#register_buttons button.register_sub");
	cashCarcBox = $(".cash_carc");
	thankyouMassage = $(".thankyou_message");

	
	//閉じるボタン
	$("#close").on("click",function(){
		if (!finished) {
			alert("まだお会計が済んでいません。\nお会計をしてください。")
		}else {
			window.location = "reserve.php";
		}
	});
	/*//修正するボタン
	$("#back").on("click",function(){
		
		window.location = "receipt.php?mode=register&rec_id=" + _receipt.rec_id + "&back_link=no";
	});*/

	var cosName = "";
	if (_receipt.costomer) {
		cosName = _receipt.costomer+" 様";
	}
	$("#h dl dt").text(_dayStr).append(
			$("<span>").attr("class","right").text(cosName));
	$("#h dl dd").text("スタイリスト："+_receipt.staff_name);

	$.each(_receipt.menus,function(index,val){
		var sales = "";
		if (val.sales) {
			sales = CURRENCY + " "+$.delimiting(val.sales);
		}
		$("<dl>").append(
			$("<dt>").text(val.menu_name).append(
				$("<span>").attr("class","right").text(sales)))
			.appendTo("#menus");
	});

	var tecDiscStr = "　";
	if (_receipt.tec_disc > 0) { tecDiscStr = _receipt.tec_disc+"%off"; }
	$("#t_disc").text(tecDiscStr);
	$("#t_sale").text(CURRENCY + " "+$.delimiting(_receipt.tec_sale));

	var proDiscStr = " 　";
	if (_receipt.pro_disc > 0) { proDiscStr = _receipt.pro_disc+"%off"; }
	$("#p_disc").text(proDiscStr);
	$("#p_sale").text(CURRENCY + " "+$.delimiting(_receipt.pro_sale));


	totalSalesDisplay.text($.delimiting(totalSales));



	

	$.setCashCarcBox();
	//会計ボタンクリック処理
	registerButtons.on("click",function(e){
		
		button = $(this);

		if (button.attr("id") == "rgstr_cash") {//現金

			if (confirm("現金でお会計をしてよろしいですか？")) {
				$.updatePayType(0);
			}

		}else {//カード

			if (confirm("カードでお会計をしてよろしいですか？")) {
				$.updatePayType(1);
			}
		}
	});

});

(function($) {
	//cashCarcBoxをセット
	$.setCashCarcBox = function(){

		var charge = cashCarcBox.find(".cash_carc_charge input");
		var back = cashCarcBox.find("div.cash_carc_back");

		charge.change(function(){
			
			var amount = $(this).val() - totalSales;
			back.text($.delimiting(amount));
		});
	}
	//支払タイプアップデート
	$.updatePayType = function(payType){
		var data = {
				mode:"update",
				table:"receipts_"+_salonId,
				id:_receipt.rec_id,
				pay_type:payType
		}
		
		$.sendAjax(data,{
			success:function(res){
				

				//会計ボタンを隠す
				registerButtons.hide();

				if (payType == 1) {
					//alert("カード払いでお会計が完了しました");
					cashCarcBox.hide();

					thankyouMassage.find("span.card").show();
				}else {
					//alert("現金払いでお会計が完了しました");
					//お預かり金額をいい感じに表示
					var chargeBox = cashCarcBox.find(".cash_carc_charge");
					var input = chargeBox.find("input");
					chargeBox.text($.delimiting(input.val()));
					input.remove();

					thankyouMassage.find("span.cash").show();
				}
				thankyouMassage.show();
				finished = true;
				/*
				//修正ボタンを削除
				$("#back").remove();
				*/
			}
		});
	}
}(jQuery));
