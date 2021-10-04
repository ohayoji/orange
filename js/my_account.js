/**
 * 
 */
var e_mail;


jQuery(function ($) {
	
	
	
	
	
	//プレミアム登録成功時
	/*if (_premiumSuccess) {
		alert("ありがとうございます！" +
				"\nプレミアムプランへのアップグレードが完了いたしました。");
		document.location = "my_account.php";
		//↑window.location.reload()だとなぜか白紙になる
	}*/
	
	e_mail = $("#e_mail");
	
	/*--基本ステータス------------*/
	e_mail.val(_salonStatus.e_mail);
	
	$(".standard_price").text($.delimiting(_planManager.price));
	$(".free_last_date").text(_planManager.trialLastDate_JP);
	$(".remainder_days").text(_remainderDays);
	$(".standard_start_date").text(_planManager.planStartDate_JP);
	$(".plan_start_month_last").text(_planManager.planStartMonthLastDate_JP);
	$(".plan_start_month_charge").text($.delimiting(_plice));
	$(".plan_start_nextmonth").text(_planManager.planStartNextMonth_JP);
	$(".craim_day").text(_craimDay);
	$(".first_craim_day").text(_planManager.planStartNextMonth_JP + _craimDay +"日");
	$(".webpay_btn_text").text($("#wp_btn_script").attr("data-text"));
	
	//表示切り替え@plan_manager.js
	$.myAccountDispChange();
	/*-------------------------------*/
	
	$("#e_mail_form").submit(function() {
		if (!$.stringCheck()) {
			return false;
		}
		//e_mailに変更がない場合はキャンセル
		if (_salonStatus.e_mail == e_mail.val()) {
			//e_mail.removeAttr("name");
			alert("Eメールアドレスが変更されていません");
			return false;
		}else {
			alert(e_mail.val()+"宛にメールを送信します。\n" +
					"本文中のURLをクリックしてEメールの変更を完了してください");
		}
	});
	
	//上部隙間削除
	$("#main_alea").css("padding-top",0);
	
	//解約ぺーじリンク
	$("#cancel_link").on("click",function(){
		if (!confirm("これから解約手続きページに移動します。" +
				"\n移動してよろしいですか？")) {
			return false;
		}
	});
	//ダウングレードページリンク
	$(".plan_downgrade_link").on("click",function(){
		if (!confirm("これからプランダウングレード手続きページに移動します。" +
				"\n移動してよろしいですか？")) {
			return false;
		}
	});

	//プランごとにwebpayフォーム表示切り替え
	if (_planManager.planStatus.plan == "premium") {
		//プレミアムプランの場合はリストごと非表示
		$("#webpaay_button_list").hide();
		
	}else if (_planManager.planStatus.plan == "trial") {
		//カード情報がある場合はwebpayのformを表示させない
		//メッセージの一部を表示させない
		if(_planManager.planStatus.recursion_id
				&& _planManager.planStatus.customer_id
				&& _planManager.planStatus.charge_id){
			$("#webpay_form").hide();
			$("#webpay_comp_mess").show();
			$(".no_webpay_comp_mess").hide();
		}		
	}
	
	
	
});

(function($) {
	
}(jQuery));