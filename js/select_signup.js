
jQuery(function ($) {
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("select_signup");
	$.setNav(navContents);
	/*---------------------------*/
	
	$("#normal").on("click",function(){
		document.location = "admission.php?admission_button_type="+_admissionButtonType;
	}).text("メールアドレスで登録");
	$("#facebook").on("click",function(){
		FB.login(function(response){statusChangeCallback(response)}, {scope: 'public_profile,email'});
		//document.location = "admission.php?admission_button_type="+_admissionButtonType;
	}).text("Facebookで登録");

	
});

(function($) {
	
}(jQuery));