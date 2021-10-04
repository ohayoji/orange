//登録、削除ボタン
var button_boxies;

jQuery(function ($) {


	//スタッフ追加時はアラート
	if (_addStaffCondition) {
		var addCompMessage;
		var name = _addStaffCondition.staff_name;
		if (_addStaffCondition.add_comp) {
			addCompMessage = name+"さんをスタッフに追加しました。";

			if (_addStaffCondition.mail_sent) {
				addCompMessage = addCompMessage +
					"\n" + _postName + " 様のメールアドレスに送信したメールを" +
					" " + name + "さんに転送して、スタッフアカウントを登録してもらいましょう！";
			}
			alert(addCompMessage);
		}
	}

	button_boxies = $(".button_box");
	/*--サブミットボタン----------*/
	$("input.submit_button").on("click",function(){
		if ($.stringCheck()) {//stringCheck():@string_check.js


			//if ($("table caption").data("mode") == "add") {
			if ($("#staff_title").data("mode") == "add") {
				$("input[name=mode]").val("add");
				$("form").submit();
			}else {
				$("input[name=mode]").val("edit");

				var staffData = $(this).parents("form.my_slide").data("staff");

				var sal_per = ["salary","percentage"];

				$.each(sal_per,function(index,val){
					var input = $("input[name="+val+"]");

					if (input.val() == staffData[val]
						|| input.val() == "" && !staffData[val]) {

						//salary,percentageの値が変更されていない場合は送信しない
						input.prop("disabled",true);


					}else {//値が変更されていた場合
						if (!staffData[val]) {
							//nullから変更された場合はtec_remデータ削除設定

							$("#del_tec_rem").prop("disabled",false);

						}
					}
				});

				/* 基本給、歩合給に変更がある場合
				 * 当月の登録済み伝票が１つでもある場合はキャンセル
				 */
				var editAuth = true;

				if (editAuth) {

					//アラートを表示
					if (confirm($.staffEditMessage(staffData._name))) {
						$("form").submit();
					}else {
						//disabled解除
						$("input[name=salary],input[name=percentage]").prop("disabled",false).val("");
					}
				}
			}
		}
	});
	$("input.delete_button").on("click",function(){
		var staffName = $(this).parents(".my_slide").data("staff")._name;
		if (confirm(staffName + "さんを削除してよろしいですか？ ")) {
			$("input[name=mode]").val("staff_delete");
			$("form")
				.append($("<input>").attr("name","deleted").val(1))
				.append($("<input>").attr("name","e_mail").val(""))
				.append($("<input>").attr("name","password").val(""))
				.submit();
		}
	});
	/*--------------------------*/

	//リスト作成
	$.createList();
});

(function($) {
	//内容変更時のアラーとメッセージ
	$.staffEditMessage = function(staffName) {

		var message = "名前、アイコン、役職を変更する場合は全ての過去データに反映されます。";

		if ($("input[name=salary]").prop("disabled") == false
				|| $("input[name=percentage]").prop("disabled") == false) {
			message = message +"\n基本給、技術歩合の変更は今月1日から反映されます。";
		}
		message = message +"\nスタッフデータを変更してよろしいですか？";

		return message;
	}
	/*//当月の該当スタッフの登録済み伝票があるかチェック
	$.staffRecCheck = function(staffId,staffName) {
		var auth = true;

		var data = {mode:"rec_count","staff_id":staffId};
		$.ajax({
			async : false,
			url: '../PHPClass/StaffSettingModel.php',
			type: "POST",
			dataType: "text",
			data: data,
			success: function(res) {
				if (res) {

					if (parseInt(res) > 0) {
						alert(
							"基本給または技術歩合を変更できません！"
							+"\nすでに今月の "+staffName+" さんの伝票が登録されているため変更できません"
							+"\n基本給または技術歩合の変更は、該当スタッフの当月の登録済み伝票がない状態（月初めなど）で行ってください");
						auth = false;
					}
				}
			}
		});
		return auth;
	}*/

	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide({hideTabImg:"../image/plus.png",
			openTabImg:"../image/close_2.png",closeFunc:true});
	}
	//スライドclose時の処理
	$.slideCloseFunc = function() {
		//テーブルcaptionリセット
		//$("table caption").data("mode","add").text("スタッフを追加する");
		$("#staff_title").data("mode","add").text("スタッフを追加する");
		//削除ボタンを非表示
		button_boxies.removeClass("parallel").eq(1).hide();
		//$("input.delete_button").css("display","none");
		//inputのvalueをリセット
		$("input[name=_name],input[name=icon]").val("");
		$("input[name=salary],input[name=percentage]").prop("disabled",false).val("");
		//ラジオボタンをリセット(attrでは効かないのでprop)
		$("form.my_slide input#s").prop("checked",true);
	}
	/*-----------------------------------------------------*/

	//リスト作成
	$.createList = function() {
		if (_staffs.length > 0) {
			var temp = $("#temp_dd");
			$.each(_staffs,function(index,val){
				if (val.deleted != 1) {//在籍スタッフのみ表示

					//各項目を表示用に設定
					var color = val.color;
					if (!color) { color = "gray"; }
					var e = val.e_mail;
					if (!e) { e = "未設定"; }
					var p = "スタイリスト";
					if (val.position == "a") { p = "アシスタント"; }
					var s = val.salary;
					if (!s) { s = "未設定"; }
					var _p = val.percentage;
					if (!_p) { _p = "未設定"; }else { _p = _p+"%"; }

					//テンプレ複製
					var dd = temp.clone().removeAttr("id")
						.css({"display":"block","background-color":color})
						.data("staff",val)
						.on("click",function(){ $(this).edit(); });

					dd.find("._name").text(val._name);
					dd.find(".e_mail").text(e);
					dd.find(".icon").text(val.icon);
					dd.find(".position").text(p);
					dd.find(".salary").text(s);
					dd.find(".percentage").text(_p);

					temp.before(dd);
					/*var dl = $("dl.temp_dl").clone().removeClass("temp_dl")
						.css({"display":"block","background-color":color})
						.data("staff",val)
						.on("click",function(){ $(this).edit(); });

					dl.find("dt").text(val._name).css("border-bottom-color","white");
					dl.find(".e_mail").text(e);
					dl.find(".icon").text(val.icon);
					dl.find(".position").text(p);
					dl.find(".salary").text($.delimiting(s));
					dl.find(".percentage").text(_p);

					$(".contents_area").append(dl);*/
				}
			});
			$.moveAccImgToMiddle();//@common.js
		}
	}
	//リストクリックメソッド
	$.fn.edit = function () {
		var staff = $(this).data("staff");
		var mySlide = $(".my_slide");
		mySlide.data("staff",staff);

		button_boxies.addClass("parallel").eq(1).show();
		//mySlide.find("input.delete_button").css("display","block");
		//mySlide.find("table caption").text("スタッフ情報を編集").data("mode","edit");
		$("#staff_title").text("スタッフ情報を編集").data("mode","edit");
		mySlide.find("input[name=id]").val(staff.id);
		//値をセット
		$.each(staff,function(name,val){

			if (name == "position") {//radioボタン
				mySlide.find("input[name="+name+"]").val([val]);
			}else {
				mySlide.find("input[name="+name+"]").val(val);
			}
		});

		mySlide.slideOpen();
	}


}(jQuery));
