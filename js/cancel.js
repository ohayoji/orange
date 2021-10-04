/**
 * 
 */
jQuery(function ($) {
	
	
	var navContents = $.navContents();
	$.setNav(navContents);
	
	$("#salon_name").text(_salon._name);
	
	$("#no_cancel").on("click",function(){
		document.location = "pages/my_account.php";
	});
	$("#cancel").on("click",function(){
		//alert("解約処理");
		$.post("PHPClass/CancelModel.php", {"mode":"cancel"}, function(){
			alert("解約処理が完了いたしました。ご利用ありがとうございました。");
			document.location = "pages/login.php?logout=true";
		});
		//url指定で相対パス使えなかった
	});
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});