jQuery(function ($) {
	
	/*--ナビゲーション-------------*/
	var navContents = $.navContents();
	$.setNav(navContents);
	
	/*---------------------------*/
	//サブミット
	$("form").submit(function() {
		//stringCheck():string_check.js
		return $.stringCheck();
	});
	/*//Facebookログインボタン
	$("#facebook").on("click",function(){
		//FB.login(function(response){statusChangeCallback(response)}, {scope: 'public_profile,email'});
		//document.location = "admission.php?admission_button_type="+_admissionButtonType;
	});*/
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories(1);//@url_converter.js
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks, facebook:false});
	/*---------------------------------------*/
});