/**
 * 
 */
jQuery(function ($) {
	/*--ナビゲーション-------------*/
	var navContents = $.navContents();
	$.setNav(navContents);
	/*---------------------------*/
	
	/*--feature_multipaytype_link------*/
	$("a.feature_multipaytype_link").each(function(index,val) {
		
		var a = $(this);
		
		var type = a.attr("data-type");
		
		if (type == "staff") {//staff
			
			a.attr("href","../help/index.html?visiter=salon&show_target=staff_setting").text("スタッフ設定");
		}else if (type == "rem"){//rem
			////console.log(type);
			a.attr("href","../help/index.html?visiter=salon&show_target=rem_setting").text("マルチ歩合設定");
		}else {
			a.attr("href","../help/index.html?visiter=salon&show_target=comp_tutorial").text("来店処理");
		}
	});
	/*----------------------------------*/
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories(1);//@url_converter.js
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});