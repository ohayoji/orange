/**
 * 
 */

jQuery(function ($) {
	
	var navContents = $.navContents();
	$.setNav(navContents);
	
	/*--plan_tab--------------------*/
	var planTab = $("#plan_tab");
	var planTabList = planTab.find("li");
	var freeContents = $("#free_contents");
	var premiumConrents = $("#premium_contents");
	
	planTabList.on("click",function(){
		var title = $(this).prop("title");
		
		if (title == "free") {//無料プランタブ
			$(this).css({"background-color":"#aaa","color":"white"});
			planTab.css("border-bottom","2px solid #aaa");
			freeContents.show();
			premiumConrents.hide();
		}else {//puremiumプランタブ
			$(this).css({"background-color":"#f1c40f","color":"white"});
			planTab.css("border-bottom","2px solid #f1c40f");
			freeContents.hide();
			premiumConrents.show();
		}
		//兄弟要素をリセット
		$(this).siblings().css({"background-color":"#f2f2f2","color":"#aaa"});
	});
	
	//プランコンテンツ表示切り替え
	
	if (!_planContents) { _planContents = "free"; }
	planTabList.filter("[title="+ _planContents +"]").click();
	/*------------------------------------*/
	
	//料金表示
	$(".premium_price").text($.delimiting(_planManager.price));
	
	/*--base_enabled_func_boxを設置-------------*/
	var tempBEFB = $("#temp_base_enabled_func_box");
	$("#free_enabled_func_area").prepend(tempBEFB.clone().removeAttr("id").show());
	$("#premium_enabled_func_area").prepend(tempBEFB.clone().removeAttr("id").show());
	tempBEFB.remove();
	/*--------------------------------------*/
	
	/*--支払いシミュレーション--------------------*/
	
	
	
	
	
	
	
	
	
	var simTable = $("#simuration");
	
	var freeSpan = simTable.find("tr.free_span");
	freeSpan.find("td.span .start_date").text(_planManager.today_JP +" ~ ");
	freeSpan.find("td.span .end_date").text(_planManager.trialLastDate_JP);
	freeSpan.find("td.price").text("無料");
	var planStart = simTable.find("tr.plan_start");
	planStart.find("td.span").text(_planManager.planStartDate_JP);
	planStart.find("td.price").text("-");
	var firstMonth = simTable.find("tr.first_month");
	firstMonth.find("td.span .start_date").text(_planManager.planStartDate_JP +" ~ ");
	firstMonth.find("td.span .end_date").text(_planManager.planStartMonthLastDate_JP);
	firstMonth.find("td.price").text($.delimiting(_planManager.planStartMonthChargePrice) + " 円");
	var secondMonth = simTable.find("tr.second_month");
	secondMonth.find("td.span").text(_planManager.planStartNextMonth_JP +" 以降");
	secondMonth.find("td.price").text($.delimiting(_planManager.price) + " 円");
	
	/*----------------------------------------*/
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});

(function($) {
	
}(jQuery));

