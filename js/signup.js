jQuery(function ($) {
	
	
	
	
	if (_condition == "SUCCESS_SIGNUP") {
		alert("スタッフアカウントが登録されました" +
				"\nログインページからログインしてOrangeをお使いください");
		document.location = "pages/login.php";
		
	}else if (_condition == "FAIL_SIGNUP") {
		alert("サーバー通信エラーのためスタッフアカウント登録が完了できませんでした" +
				"\nお手数ですが、サロン管理者から送信されたメールのURLから再度やり直してください");
		document.location = "index.php";
		
	}else if (_condition == "NO_GET") {
		alert("不正なURLからアクセスされました。");
		document.location = "index.php";
	}
	
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("staff_signup");
	$.setNav(navContents);
	/*---------------------------*/
	
	/*--setting----------*/
	$("input[name=id]").val(_staff.id);
	$("#salon_name").text(_salon._name);
	$("#staff_name").text(_staff._name);
	/*------------------------------*/
	
	
	
	//サブミット
	$("form").submit(function() {

		//stringCheck():@string_check.js
		if (!$.stringCheck()) { return false; }
		
		if ($(".mail:eq(0)").val() != $(".mail:eq(1)").val()) {
			alert("メールアドレスが一致しません");
			return false;
		}
		if ($(".pass:eq(0)").val() != $(".pass:eq(1)").val()) {
			alert("パスワードが一致しません");
			return false;
		}
		
		//パスワードがすでに使用されていれたらキャンセル
		var exist = false;
		var data = {
				mode:"count",
				password:$("input[name=password]").val()
				};
		$.ajax({
			async : false,
			url: 'PHPClass/SignUpModel.php',
			type: "POST",
			dataType: "text",
			data: data,
			success: function(res) {
				
				if (parseInt(res) > 0) {
					alert("入力されたパスワードはすでに使用されています" +
							"\n別のパスワードを指定してください");
					exist = true;
				}
			}
		});
		if (exist) {
			return false;
		}
	});
});