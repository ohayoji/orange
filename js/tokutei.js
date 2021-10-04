/**
 * 
 */
jQuery(function ($) {
	$.setNav($.navContents());
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});