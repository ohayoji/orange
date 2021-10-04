/**
 *
 */
jQuery(function ($) {
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("admission_comp");
	$.setNav(navContents);
	/*---------------------------*/
	
	var message = $("#thankyou_messe_box p");

	if (_condition == "SUCCESS_ADMISSION") {
		//サロン登録が成功した場合
		message.append("ありがとうございます！<br>" +
				"ユーザー登録が完了しました。<br>" +
				"Orangeをお使いください。");

		if (_sent) {
			alert("ユーザー登録ありがとうございます！\n" +
					"登録完了メールを送信しましたのでご確認ください。");
		}

	}else if (_condition == "FAIL_ADMISSION") {
		//サロン登録に失敗した場合
		message.append("サーバー通信エラーのためユーザー登録が完了できませんでした。<br>" +
				"お手数ですが、仮登録完了時にお送りしたメールのURLから再度やり直してください。<br>");

	}else if (_condition == "SALON_EXISTENCE") {
		//salonsテーブルにすでにレコードが存在している場合
		message.append("すでに"+_salon._name+"さんのユーザー登録は完了しています。");

	}else if (_condition == "NO_PRE_ADMISSION") {
		//仮登録レコードが存在しない場合
		message.append("ユーザー登録がすでに完了しているかもしれません。<br>" +
				"メニューの「ログイン」からログインをお試し下さい。<br>" +
				"ログインできない場合は、お手数ですが、登録ページから再度やり直してください。");
		$("#adm_link").show();

	}else if (_condition == "NO_GET") {
		//パラメータを消されてアクセスされた場合
		alert("不正なURLからアクセスされました。");
		document.location = "index.php";
	}else if (_condition == "E_MAIL_UPDATE") {
		message.append("Eメールアドレスの変更が完了しました");
	}
});
