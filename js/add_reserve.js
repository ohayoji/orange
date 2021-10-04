/**
 *
 */
//登録、削除ボタンBOX
var button_boxies;

jQuery(function ($) {
	//var rapTime = $.rapTime("add_start");

	var slide = $("#right_slide");
	button_boxies = slide.find(".button_box");

	/*--月日セレクタ----------------*/
	//予約表の月日セレクタのoptionをクローン
	var monsel = $("#month");
	var datesel = $("#date");
	$("#ar_month").append(monsel.find("option").clone());
	$("#ar_date").append(datesel.find("option").clone());
	/*----------------------------*/
	/*--スタッフセレクタ----------*/
	var staffSel = $("#ar_staff");
	$.each(_staffs,function(index,val){
		if (_visiter == "staff") {
			if (val.id == _staffId) {
				staffSel.append($("<option>").val(val.id).text(val._name));
			}
		}else {
			staffSel.append($("<option>").val(val.id).text(val._name));
		}
	});
	/*-------------------------*/
	/*--メニュー--------*/
	$.each(_menus,function(index,val){
		if (val.um_id) {//createMenuIcon():@common.js
			var icon = $.createMenuIcon(val).appendTo($("#ar_menu"))
					.find("img").toggle();
		}
	});
	/*------------------*/
	//来店回数セレクタ addvisitOptions():@common.js
	$("#num_visit").addvisitOptions();
	/*--時間セレクタ-----------------*/
	$.each(_times,function(index,val){
		$("#ar_start, #ar_end").append($("<option>")
				.val(val.text).text(val.text));
	});
	//最終時刻を追加
	$("#ar_end").append($("<option>").val(_lastTime).text(_lastTime));
	/*------------------------------*/
	/*--有効伝票項目欄-------------*/
	$.changeVivoEntryName();

	$.each(_recEnts,function(index,val){
		if (val.ur_id) {
			var entName = val._name;

			var tr = $("<tr>").attr("class","rec_ent");
			var td1 = $("<td>").text(val.local_name);
			//getEntryInput():@common.js
			var td2 = $("<td>").append($.getEntryInput(val));
			/*--伝票項目の種類別にアレンジ---*/
			if (entName == "point") {
				td2.append(" P").setTextStrCheck();
			}else if (entName == "free") {
				td2.find("input:first").after(" 指名 ");
				td2.find("input:eq(1)").after(" フリー");
			}
			/*----------------------------*/

			$("#reserv_input").append(tr.append(td1).append(td2));
		}
	});
	/*------------------------------*/
	/*--サブミットボタン----------*/
	//登録ボタン
	$("#submit_btn").on("click",function(e){
		var btn = $(this);

		if ($.stringCheck()) {
			//データ
			var resData = $.sendingResData();

			//空席チェックモードを追加
			resData.mode = "seat_check";
			//データを作ったらボタンを無効
			btn.prop("disabled",true);

			$.ajax({
				async: false,
				url: '../PHPClass/ReserveModel.php',
				type: "POST",
				dataType: "json",
				data: resData,
				success: function(res) {
					if (res == true) {//空席チェックOK

						resData.mode = slide.data("my_data").mode;

						$.ajax({
							async: false,
							url: '../PHPClass/ReserveModel.php',
							type: "POST",
							data: resData,
							success: function() {
								window.location.reload();
							}
						});

					}else {
						alert(res+"〜の席が空いていないので予約を登録できません。登録するには時間かエリアを変更してください。");
						//ボタンを復活
						btn.prop("disabled",false);
					}
				}
			});
		}
	});

	/*-------------------------*/
	//rapTime = $.rapTime("add_goal",rapTime);
});

(function($) {
	//スライドopen時メソッド
	$.rsOpenFunc = function() {
		var data = $("#right_slide").data("my_data");

		/*--各値をセット----------*/
		$.each(data,function(name,val){

			//input,select,textarea
			$("#right_slide").find("input[name="+name+"]"+
							",select[name="+name+"]").val(val);
			//メニューアイコンを選択済みに
			if (name == "menus") {
				$.each(val,function(i,v){
					$("div.menu_icon[title="+v.menu_id+"] img").toggle();
				});
			}
		});
		//月日セレクタ
		$("#ar_month").val(data.start.substr(0,7));
		$("#ar_date").val(data.start.substr(8,2));
		//時間セレクタ
		$("#ar_start").val(data.start.substr(11,5));
		if (data.end) {
			$("#ar_end").val(data.end.substr(11,5));
		}else {//新規追加時は３０分後を選択済みに
			var index = $("#ar_start option:selected").index() + 1;
			$("#ar_end option:eq("+index+")").prop("selected",true);
		}
		//メモ
		$("#memo").val(data.memo);

		//伝票項目
		if (data.net_id) {
			$("#net").prop("checked",true);
		}
		if (data.point_id) {
			$("#point").val(data.point_v);
		}
		if (data.free_id) {
			$("input[name=free][value="+data.free_v+"]").prop("checked",true);
		}
		if (data.student_id) {
			$("#student").prop("checked",true);
		}
		if (data.other_net_id) {
			$("#other_net").prop("checked",true);
		}
		/*----------------------*/

		/*--編集時の設定--------------------*/
		if (data.mode == "edit") {
			//登録ボタン削除ボタンに横並びクラスを追加 削除ボタンを表示
			button_boxies.addClass("parallel").eq(1).show();
			//削除ボタン
			$("#delete_btn").show().on("click",function(){

				if (confirm("予約を削除してよろしいですか？")) {
					var d = "mode=delete&rec_id="+data.rec_id;

					$.post("../PHPClass/ReserveModel.php",d,function(res){
						if (res) { window.location.reload(); }
					},"text");
				}

			});
		}
		/*---------------------------------------*/
	}
	//スライドclose時のメソッド
	$.rsCloseFunc = function() {
		var slide =  $("#right_slide");
		/*--コンテンツをリセット----------*/
		//select
		slide.find("select option:selected").prop("selected",false);
		//input(button以外のvalueを削除)
		slide.find("input").not("[type=button],[type=radio]")
			.val("").prop("checked",false);
		//input(radioを非選択に)
		slide.find("input[type=radio]").prop("checked",false);
		//メニューアイコン
		slide.find(".menu_icon img.on").hide();
		slide.find(".menu_icon img.off").show();
		/*------------------------------*/
		//データを削除
		$.removeData(slide);
		//削除ボタンを隠す
		button_boxies.removeClass("parallel").eq(1).hide();
		//$("#delete_btn").hide();
	}

	//送信データ作成
	$.sendingResData = function() {
		var data = $("#right_slide").data("my_data");

		var recId = null;
		if (data.rec_id) {
			recId = data.rec_id;
		}
		var mon = $("#ar_month").val();
		var date = $("#ar_date").val();
		var start = mon+"-"+date+" "+$("#ar_start").val()+":00";
		var end = mon+"-"+date+" "+$("#ar_end").val()+":00";
		var staff_id = $("#ar_staff").val();
		var costomer = $("#costomer").val();
		var num_visit = $("#num_visit").val();
		var seat = data.seat;
		var areaId = data.area_id;
		var memo = $("#memo").val().replace(/\r?\n/g, " ");
		var tec_sale = $("#tec_sale").val();
		//メニュー
		var menus = "";
		$.each($("#ar_menu img.on:visible"),function(index,val){
			if (menus != "") { menus = menus+"|"; }
			menus = menus+$(this).parent().prop("title");
		})


		//伝票項目 各項目の要素が存在していれば値をセット
		var net = $("#net");
		var point = $("#point");
		var free = $("input[name=free]:checked");
		var student = $("#student");
		var other_net = $("#other_net");
		var n = null;
		var p = null;
		var f = null;
		var s = null;
		var o = null;

		if (net.length > 0 && net.prop("checked")) {
			n = "on";
		}
		if (point.length > 0 && point.val() != "") {
			p = $("#point").val();
		}
		if (free.length > 0) {
			f = free.val();
		}
		if (student.length > 0 && student.prop("checked")) {
			s = "on";
		}
		if (other_net.length > 0 && other_net.prop("checked")) {
			o = "on";
		}

		var resData = {rec_id:recId,start:start,end:end,staff_id:staff_id,
				costomer:costomer,num_visit:num_visit,seat:seat,
				area_id:areaId,memo:memo,tec_sale:tec_sale,menus:menus,
				net:n,point:p,free:f,student:s,other_net:o};
		//編集時は伝票項目id情報も追加
		if (data.mode == "edit") {
			resData.net_id = data.net_id;
			resData.point_id = data.point_id;
			resData.free_id = data.free_id;
			resData.student_id = data.student_id;
			resData.other_net_id = data.other_net_id;
		}

		return resData;
	}
}(jQuery));
