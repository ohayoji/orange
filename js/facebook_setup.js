
jQuery(function ($) {
	//facebook SDK setup
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/ja_JP/sdk.js', function(){
	    FB.init({
	      appId: '908897202491355',//DummyOrange
	      version: 'v2.4', // or v2.0, v2.1, v2.2, v2.3
	      //cookie     : true,  // enable cookies to allow the server to access
          // the session
	      //xfbml      : true  // parse social plugins on this page
	    });
	    $('#loginbutton,#feedbutton').removeAttr('disabled');
	    /*
	    FB.getLoginStatus(function(response) {
	        statusChangeCallback(response);
	    });
	    */
	 });
});

(function($) {

}(jQuery));

//This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {


  // The response object is returned with a status field that lets the
  // app know the current login status of the person.
  // Full docs on the response object can be found in the documentation
  // for FB.getLoginStatus().
  if (response.status === 'connected') {
    // Logged into your app and Facebook.
    //testAPI();
	  connectedAction();

  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.

  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.

  }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}


// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {

  FB.api('/me', 'GET', {"fields": "id, name, email"}, function(response) {


  });
}

function connectedAction(){
	FB.api('/me', 'GET', {"fields": "id, email"}, function(response) {

	    var data = getAccountData(response.id);
	    if(data.flag == "true"){
	    	if(_page == "signup"){
	    		alert("既にアカウントが登録されています。");
	    	}
	    	else if(_page == "login"){
	    	//login処理


	    		$("input[name='e_mail']").val(data.record.e_mail);
	    		$("input[name='password']").val(data.record.password);
	    		$("form").submit();
	    	}
	    }
	    else if(data.flag == "false"){
	    	if(_page == "signup"){
	    		//admissionページへ

	    		var facebookId = encodeURIComponent(response.id);
	    		var email = encodeURIComponent(response.email);

	    		document.location = "admission.php?admission_button_type="+_admissionButtonType+"&email="+email+"&facebook_id="+facebookId;
	    	}
	    	else if(_page == "login"){
	    		alert("Facebookでアカウントは登録されていません。" +
	    				"\nFacebookアカウントはサロンのユーザー登録時にのみ登録することができます。" +
	    				"\n今後のアップデートで、現在通常登録のサロン様もFacebookアカウントに移行できるようになる予定です。");
	    	}
	    }
	  });
}
//該当のfacebookIdを持ったレコードがsalonsテーブルに存在するかチェック
function getAccountData(facebookId){
	var result = {};
	var data = {mode:"checkAccount", facebook_id: facebookId};
	//同期通信でレコードが存在するかチェックする
	//苦肉の策
	var url = "";
	if(_page == "login"){
		url = "../PHPClass/FacebookSetupModel.php";
	}
	else if(_page == "signup"){
		url = "./PHPClass/FacebookSetupModel.php";
	}
	$.ajax({async:false, type:"POST",
			url:url,
			data:data,
			dataType:"json",
			success: function(data){
									result = data;
					}
	});

	return result;
}
