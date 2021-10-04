jQuery(function ($) {
	
	/*--サロンリスト----------------*/
	var info = $("#salon_info");
	$.each(_salonInfo,function(index,val){
		var salonId = val["id"];
		var trushIcon = $("<img>").css({"width":20,"height":20}).attr({"src":"../image/trash_2.png"})
																		 .addClass("salonDelete")
																		 .data("id", salonId)
																		 .data("name", val["_name"]);
		info.after($("<dd>").text(val["_name"]).append(trushIcon));
	});
	$(".salonDelete").on("click", function(){


		var data = {"salon_id": $(this).data("id"), "mode": "salonDelete"};
		if(window.confirm($(this).data("name")+"を管理から外してもよろしいですか？")){
			$.post("../PHPClass/CompanySettingModel.php", data, function(){
				location.reload();
			});
		}
	});
	/*----------------------------*/
	var adress = "../admission.php?company_id="+_companyId;
	$("#admission").attr("href", adress);



	/*--サブミットボタン------------*/
	$("button.submit_button").on("click",function(e){
		//$.stringCheck():@string_check.js
		if ($.stringCheck()) {
			$("#setting_form").submit();
		}
	});
	/*--------------------------------*/

	/*--歩合追加ボタンクリック時------------------*/
	/*$.fn.createAddContents = function() {
		//追加
		var addContents = $("<dd>",{"class": "segment02 clearfix",
									id: "add_form"});
		//コンテンツをセット
		$("<div>",{"class": "seg_contents"})
			.append($("<input>",{"class": "faint narrow01 not_null not_unique_char only_num",
								id: "percentage"})
								.on("change",function(){
									$(this).chkCode();
								}))
			.append(" %")
			.appendTo(addContents);
		$("<div>",{"class": "seg_contents"})
			.append($("<input>",{type: "button",value: "登録",
								id: "decision_btn"})
						.on("click",$(this).clickDec))
						.appendTo(addContents);
		return addContents;
	}*/
	/*-----------------------------------------*/

	//勘定科目リスト
	//$.createSubList();
	/*--歩合率リスト-------------*/
	/*$("#tec_pp_list").createPPList();
	$("#pro_pp_list").createPPList();
	//追加・削除ボタン setflip(),flip():@flip_button.js
	$(".add_pp_btn")
		.setflip({faceTex:"＋",backTex:"キャンセル",
				faceBgCol:"#a3c600",backBgCol:"#e74c3c"})
		.on("click",function(e){

			if ($(this).data("disp_side") == "face") {
				$(this).before($(this).createAddContents());
				$(this).flip("back");
			}else {
				$("#add_form").remove();
				$(this).flip("face");
			}
		});*/
	/*----------------------------*/
});

(function($) {
	/*--勘定科目リスト---------------------------*/
	/*$.createSubList = function() {
		$.each(_usingSub,function(index,val){

			var input = $("<input>")
				.attr({"type":"checkbox","id":"sb_"+val["sb_id"]})
				.on("click",function(e){
					$(this).clickSbCheck();
				});
			//使用項目にチェック
			if (val["us_id"]) { input.attr("checked",true); }
			//技術売上、商品売上は選択必須なのでdisabled
			if (val["_name"] == "tec_sales" || val["_name"] == "pro_sales") {
				input.attr("disabled",true);
			}

			var label = $("<label>")
				.attr("for","sb_"+val["sb_id"]).text(val["local_name"]);

			$("#sub_list").append(input).append(label);
		});
	}*/
	//チェックボックスメソッド
	/*$.fn.clickSbCheck = function() {
		var array = $(this).prop("id").split("_");
		var sb_id = array[1];
		//using_subjects操作
		var data = "sb_id="+sb_id+"&use="+$(this).prop("checked");
		$.post("../PHPClass/CompanySettingModel.php",data,null,null);
	}*/
	/*-----------------------------------------*/

	/*--歩合率パターンリスト-------------------------*/
	//テーブルIDからtype（tec,pro）を返す
	/*$.ppType = function(tableId) {
		var type = null;
		if (tableId == "tec_pp_list") {
			type = "tec";
		}else {
			type = "pro";
		}
		return type;
	}*/
	/*$.fn.createPPList = function() {
		var table = $(this);
		//対象配列
		var array = null;
		if (table.prop("id") == "tec_pp_list") {
			array = _tecPP;
		}else {
			array = _proPP;
		}

		if (array.length > 0) {//レコードがあれば行追加
			$.each(array,function(index,val){
				table.addPPList(val);
			});
		}else {//なければメッセージ
			$(this).append('<tr id="not_record"><td colspan="3">歩合率は設定されていません</td></tr>');
		}
	}*/
	//リスト追加
	/*$.fn.addPPList = function(pp) {
		var type = $.ppType($(this).prop("id"));

		var tr = $("<tr>").attr("title",pp["id"]);
		var td1 = $("<td>").text(pp["percentage"]+"%");
		var td2 = $("<td>").append(
					$("<input>").attr({"name":"pp_selected_"+type,"type":"radio"})
								.on("click",$(this).selectedPP));
		if (pp["selected"] == 1) {
			td2.find("input").attr("checked",true);
		}
		var td3 = $("<td>").append(
					$("<input>").attr({"name":"pp_delete","type":"button"})
								.on("click",$(this).deletePP)
								.val("×"));

		$(this).append(tr.append(td1).append(td2).append(td3));
		return $(this);
	}*/
	//削除ボタンクリック時
	/*$.fn.deletePP = function() {
		var table = $(this).parents("table");
		var tr = $(this).parents("tr");
		var type = $.ppType(table.prop("id"));

		var data = "mode=delete_pp&type="+type+"&id="+tr.prop("title");
		$.post("../PHPClass/CompanySettingModel.php",data);
		//行削除
		tr.remove();
	}*/
	//selectedラヂオクリック時
	/*$.fn.selectedPP = function() {
		var type = $.ppType($(this).parents("table").prop("id"));

		var data = "mode=update_pp&type="+type+"&id="+$(this).parents("tr").prop("title");
		$.post("../PHPClass/CompanySettingModel.php",data);
	}*/
	/*-----------------------------------------------*/

	/*--歩合追加ボタンクリック時------------------*/
	/*$.fn.createAddContents = function() {
		//追加
		var addContents = $("<dd>",{"class": "segment02 clearfix",
									id: "add_form"});
		//コンテンツをセット
		$("<div>",{"class": "seg_contents"})
			.append($("<input>",{"class": "faint narrow01 not_null not_unique_char only_num",
								id: "percentage"})
								.on("change",function(){
									$(this).chkCode();
								}))
			.append(" %")
			.appendTo(addContents);
		$("<div>",{"class": "seg_contents"})
			.append($("<input>",{type: "button",value: "登録",
								id: "decision_btn"})
						.on("click",$(this).clickDec))
						.appendTo(addContents);
		return addContents;
	}*/
	/*-----------------------------------------*/

	//歩合登録クリック時の処理
	/*$.fn.clickDec = function() {
		//stringCheck():@string_check.js
		if ($("#percentage").strCheck()) {
			//値
			var percentage = $("#percentage").val();

			//table
			var table = $(this).parents("dd").prev().find("table");

			//type
			var type = $.ppType(table.prop("id"));

			var data = "mode=insert_pp&type="+type+"&percentage="+percentage;

			$.post("../PHPClass/CompanySettingModel.php",
					data,
					function(res){
						//未設定表示行を削除
						$("#not_record").remove();



						var pp = {id:res,percentage:percentage};
						table.addPPList(pp);
					},
					"text");
			$("#add_form").remove();
			$(".add_pp_btn").flip("face");
		}
	}*/
}(jQuery));
