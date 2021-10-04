/**
 * 
 */
jQuery(function ($) {
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("password_reissue");
	$.setNav(navContents);
	/*---------------------------*/
	
	
	//mode設定
	if (_mode == "e_mail_verification") {
		//パスワード入力領域を削除
		$("#step3").remove();

		/*--radio------------*/
		$("input[type=radio]").on("click",function(){
			
			var category = $(this).val();
			
			$("input[name=user_type]").val(category);
			
			var salonMailInput = $("input[name=salon_e_mail]");
			var className = "not_null not_unique_char";
			if (category == "staff") {
				salonMailInput.addClass(className)
					.setTextStrCheck().appendTo($("dd.salon_e_mail"));
				$(".salon_e_mail").show();
			}else {
				$("form").before(salonMailInput.removeClass(className).val(""));
				$(".salon_e_mail").hide();
			}
		});
		/*---------------------*/
		
		$("form").attr("id","admission_form");
		
		$("input[type=radio]#salon").click();
		
	}else if (_mode == "reissue") {
		//パスワード入力領域以外を削除
		$("#step1, #step2").remove();
		
		//inputに値をセット
		$("input[name=mode]").val("reissue");
		$("input[name=id]").val(_id);
		$("input[name=user_type]").val(_userType);
		
		$("form").attr("id","admission_form");
		
	}else if (_mode == "comp") {
		$("#step1, #step2, #step3").remove();
		$("h3").after("パスワード再発行が完了しました");
	}else {//fail
		$("#step1, #step2, #step3").remove();
		$("h3").after("サーバー通信エラーのためパスワード再発行に失敗しました。お手数ですが、Orangeから送信したメールのURLをクリックし、もう一度やり直してください。");
	}
	
	
	
	/*--登録ボタン---------------------*/
	$("#admission_form").submit(function() {
		//stringCheck():@string_check.js
		if (!$.stringCheck()) { return false; }
		
		
		if (_mode == "e_mail_verification") {
			
			//レコード存在フラグ
			var recExist = false;
			
			//レコード存在していればsubmit
			var data = {
					user_type:$("input[type=radio]:checked").val(),
					mode:"e_mail_verification",
					e_mail:$("input[name=e_mail]").val()
			}
			if (data.user_type == "staff") {
				data.salon_e_mail = $("input[name=salon_e_mail]").val();
			}
			
			
			$.ajax({
				async : false,
				url: 'PHPClass/PasswordReissueModel.php',
				type: "POST",
				dataType: "json",
				data: data,
				success: function(res) {
					if (res.length > 0) {
						recExist = true;
						
						//inputに値をセット
						$("input[name=id]").val(res[0].id);
						$("input[name=mode]").val("send_mail");
					}
				}
			});
			
			if (recExist) {
				alert(data.e_mail + "あてにEメールを送信します" +
				"\nメール本文に記載されたURLをクリックしパスワード再発行を完了してください");
			}else {
				alert("入力された条件に一致するデータがありませんでした" +
				"\nメールアドレスに間違いがないか確認してください");
				return false;
			}
		}else {//reissueモード
			
			if ($(".pass:eq(0)").val() != $(".pass:eq(1)").val()) {
				alert("パスワードが一致しません");
				return false;
			}
			
			//パスワードがすでに使用されていれたらキャンセル
			var passExist = true;
			var data = {
					mode:"count",
					user_type:$("input[name=user_type]").val(),
					password:$("input[name=password]").val()
					};
			$.ajax({
				async : false,
				url: 'PHPClass/PasswordReissueModel.php',
				type: "POST",
				dataType: "text",
				data: data,
				success: function(res) {
					
					if (parseInt(res) > 0) {
						passExist = false;
					}
				}
			});
			if (!passExist) {
				alert("入力されたパスワードはすでに使用されています" +
				"\n別のパスワードを指定してください");
				return false;
			}
			
			if (!confirm(
					"”" + $(".pass").val() + "”でパスワードを再発行してよろしいですか？")) {
				return false;
			}
		}
		
	});
	/*----------------------------------*/
});