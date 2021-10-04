/**
 * 
 */
var FREE_MESSAGE1;
var FREE_MESSAGE2;


//アクセス禁止ページへのページリンク
var killPageLinkIds =
	[
	 "pagelink_monthly_reports",
	 "pagelink_staff_reports",
	 "pagelink_additional_remunerations",
	 "pagelink_deduction",
	 "pagelink_payment_management",
	 ];

jQuery(function ($) {
	
	
	
	if (_visiter == "salon") {
		FREE_MESSAGE1 = "現在ご利用中の無料プランでは";
		FREE_MESSAGE2 = "「マイアカウント」ページでカード情報を登録し、プレミアムプランに切り替えてください";
	}else if (_visiter == "staff") {
		FREE_MESSAGE1 = "現在"+ _postName +"様がご利用中の無料プランでは";
		FREE_MESSAGE2 = _postName +"様のプランをプレミアムプランに切り替える必要があります";
	}
	
	/*2016/03/11 フリープランの機能制限をしないように変更
	if (_planManager.planStatus.plan == "free") {
		$.reservDragCansel();
		$.limitateRecListMonthSelecter();
		$.limitateDailyReportMonthSelecter();
		$.limitateRemSpecMonthSelecter();
		$.limitateSalonSetting();
	}
	*/
});

(function($) {
	/*==freeプラン制限===============================*/
	//予約帳のドラッグモードキャンセル
	$.reservDragCansel = function() {
		//もともとの処理をキャンセルし新イベント設定
		$("#drag_controller").unbind().on("click",function(e){
			alert(FREE_MESSAGE1 + "「ドラッグモード機能」は使用できません" +
					"\n「ドラッグモード機能」をご利用になりたい場合は" + FREE_MESSAGE2);
		});
	}
	//伝票検索月セレクタの選択制限
	$.limitateRecListMonthSelecter = function() {
		
		$("#rec_list_start_month_selecter")
			.on("click",function(){
				var currentIndex = this.selectedIndex;
				
				$(this).data("currentIndex",currentIndex);
			})
			.on("change",function(){
				if (this.selectedIndex > 1) {
					alert(FREE_MESSAGE1 + "前月以前の月は選択できません" +
							"\n全ての月を選択可能にしたい場合は" + FREE_MESSAGE2);
					this.selectedIndex = $(this).data("currentIndex");
					//セレクタリセット
					$.resetSel($(this));//@receipt_list.js
				}
			});
	}
	//日報月セレクタの選択制限
	$.limitateDailyReportMonthSelecter = function() {
		$("#d_report_month_selecter")
			.unbind()//一旦既存のsubmit処理を削除
			.on("click",function(){
				var currentIndex = this.selectedIndex;
				
				$(this).data("currentIndex",currentIndex);
			})
			.on("change",function(e){
				if (this.selectedIndex > 1) {//選択禁止option
					
					alert(FREE_MESSAGE1 + "前々月以前の月は選択できません" +
							"\n全ての月を選択可能にしたい場合は" + FREE_MESSAGE2);
					this.selectedIndex = $(this).data("currentIndex");
				}else {//選択可能optionの場合はsubmit
					$(this).parent("form").submit();
				}
			});
	}
	//売上明細月セレクタの選択制限
	$.limitateRemSpecMonthSelecter = function() {
		$("#rem_spec_month_selecter")
			.unbind()//一旦既存のsubmit処理を削除
			.on("click",function(){
				var currentIndex = this.selectedIndex;
				
				$(this).data("currentIndex",currentIndex);
			})
			.on("change",function(e){
				if (this.selectedIndex > 1) {//選択禁止option
					
					alert(FREE_MESSAGE1 + "前々月以前の月は選択できません" +
							"\n全ての月を選択可能にしたい場合は" + FREE_MESSAGE2);
					this.selectedIndex = $(this).data("currentIndex");
				}else {//選択可能optionの場合はsubmit
					$(this).parent("form").submit();
				}
			});
	}
	//サロン設定制限
	$.limitateSalonSetting = function() {
		$("#salon_set_usg_sub, #salon_set_page_lock")
			.unbind()
			.on("click",function(){
				var text = $(this).find("div.list_body").text();
				alert(FREE_MESSAGE1 +
						"「" + text + "」" +
						"は使用できません" +
						"\n「" + text + "」を" +
						"使用したい場合は" + FREE_MESSAGE2);
			});
	}
	//ページアクセス制限（該当ページのmmenu設定後に呼び出す）
	$.limitatePageAccess = function() {
		/*if (_planManager.planStatus.plan == "free") {
			$.each(killPageLinkIds,function(index,val){
				var pagelink = $("#" + val);
				pagelink.on("click",function(e){
					//リンクを無効にしアラート
					e.preventDefault();
					alert(FREE_MESSAGE1 +
							"「" + $(this).text() + "」" +
							"へのアクセスはできません" +
							"\n「" + $(this).text() + "」" +
							"へアクセスしたい場合は" + FREE_MESSAGE2);
				});
			});
		}*/
	}
	/*===============================================*/
	//マイアカウントページの表示切り替え@my_account.js
	$.myAccountDispChange = function() {
		//プランステータス
		$("#plan_st").text(_planManager.localPlanName);
		//メッセージ
		if (_planManager.planStatus.plan == "trial") {
			$("#plan_trial_contents").show();
			
			var admissionButtonType = _planManager.planStatus.admission_button_type;
			if (admissionButtonType == "premium") {
				$("#premium_trial_detail").show();
			}else {
				$("#free_trial_detail").show();
			}
			
		}else if(_planManager.planStatus.plan == "free"){
			$("#plan_free_contents").show();
		}else if (_planManager.planStatus.plan == "premium") {
			$("#plan_premium_contents").show();
		}
	}
	
}(jQuery));