/**
 * 
 */
jQuery(function ($) {
	
	
	
	
	//_admissionButtonType用inputを作成
	if (_admissionButtonType) {
		var input = $("<input>")
						.attr({"type":"hidden","name":"admission_button_type"})
						.val(_admissionButtonType)
						.appendTo($("form"));
	}
	
	if (_companyId) {
		var input = $("<input>")
						.attr({"type":"hidden","name":"company_id"})
						.val(_companyId)
						.appendTo($("form"));
	}
	if (_facebookId) {
		var input = $("<input>")
						.attr({"type":"hidden","name":"facebook_id"})
						.val(_facebookId)
						.appendTo($("form"));
	}
	if (_email) {
		$("input.mail").val(_email);
	}
	
	/*--POSTが存在するとき（仮登録処理時）
	 * メール送信状況に応じてアラート
	 */
	if ("_name" in _postData) {
		if (_sendComp) {
			
			//JAPAN WAYコンバージョンタグ
			$("body").prepend('<script type="text/javascript">var smnAdvertiserId = "00003064";</script><script type="text/javascript" src="//cd.ladsp.com/script/conv.js"></script>');
			
			//タグを完全に実行してからアラート
			setTimeout(function() {
				alert("お申し込みありがとうございます！\n" +
						"ユーザー登録はまだ完了していません\n" +
						_postData.e_mail+" に仮登録完了メールを送信しました\n" +
						"記載されたURLにアクセスしてユーザー登録を完了してください");
				document.location = "index.php";
			}, 100);
			
			return false;
		}else {
			alert(_postData.e_mail+" へのメール送信に失敗しました\n" +
					"メールアドレスに間違いがないかご確認ください");
		}
	}
	/*-------------------------------*/
	
	/*--ナビゲーション-------------*/
	var navContents = $.navContents("admission");
	$.setNav(navContents);
	/*---------------------------*/
	
	/*--利用規約リンク----------------*/
	var popupWidth = $("body").width() * 0.9;
	
	$("#agree a").on("click",function(){
		var type = this.id;
		
		var contents = $("#temp_popup_contents")
				.clone().attr("id","popup_contents").show();
		contents.find("div[title="+type+"]").show();
		
		$.createPopupView({
			contents:contents,type:"center",width:popupWidth
		});
	});
	/*------------------------------*/
	
	/*--登録ボタン--------------------*/
	$("#admission_form").submit(function() {
		var auth = true;
		
		if (!$.stringCheck()) {
			auth = false;
		}
		
		if ($(".mail:eq(0)").val() != $(".mail:eq(1)").val()) {
			alert("メールアドレスが一致しません");
			auth = false;
		}
		if ($(".pass:eq(0)").val() != $(".pass:eq(1)").val()) {
			alert("パスワードが一致しません");
			auth = false;
		}
		if ($("#agreement").prop("checked") == false) {
			alert("ユーザー登録をするには利用規約に同意する必要があります\n" +
					"「利用規約に同意する」にチェックを入れてください");
			auth = false;
		}
		
		//パスワードがすでに使用されていれたらキャンセル
		if (auth) {
			var data = {
					mode:"count",
					password:$("input[name=password]").val()
					};
			$.ajax({
				async : false,
				url: '../PHPClass/AdmissionModel.php',
				type: "POST",
				dataType: "text",
				data: data,
				success: function(res) {
					
					if (parseInt(res) > 0) {
						alert("入力されたパスワードはすでに使用されています" +
								"\n別のパスワードを指定してください");
						auth = false;
					}
				}
			});
		}
		
		return auth;
	});
	/*-------------------------------*/
	
	/*--フッター設置--------------------------*/
	//リンク配列
	var navLinks = $.getOrangePassCategories();//@url_converter.js
	
	//フッター作成
	$.setMyFooter({footerNav:navLinks});
	/*---------------------------------------*/
});