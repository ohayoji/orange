/**
 * 
 */
jQuery(function ($) {
	
	
	var navContents = $.navContents();
	$.setNav(navContents);
	
	$("#salon_name").text(_salon._name);
	
	$("#no_downgrade").on("click",function(){
		document.location = "pages/my_account.php";
	});
	$("#downgrade").on("click",function(){
		//alert("ダウングレード処理");
		$.post("PHPClass/PlanDowngradeModel.php", {"mode":"downgrade"}, function(){
			alert("無料プランにダウングレードしました。");
			//ここに解約処理の続きを書く。
			document.location = "pages/my_account.php";
		});
	});
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});