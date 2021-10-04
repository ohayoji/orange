/**
 *
 */
//登録、削除ボタン
var button_boxies;
//モード(insert(デフォルト),update,delete)
var mode = "insert";
var usedDeductionsId = null;

jQuery(function ($) {







	button_boxies = $(".button_box");

	/*--月セレクタ----------------*/
	//月セレクタ初期設定
	var select = $("select[name=month]");
	$.each(_monthDatas,function(index,val){
		select.append($("<option>").val(val.value).text(val.text));
	});
	select.val(_monthCondition);
	/*------------------------------*/

	/*--スタッフセレクタ-------------*/
	$.each(_staffs,function(index,val){
		if (select.prop("selectedIndex") == 0) {//当月の場合
			if (val.deleted == 0) {
				$("select#staff_id").append($("<option>").val(val.id).text(val._name));
			}
		}else {//過去月の場合
			if (val.deleted == 1) {
				val._name = "(" + val._name + ")";
			}
			$("select#staff_id").append($("<option>").val(val.id).text(val._name));
		}
	});
	/*----------------------------*/

	/*--控除科目セレクタ---------------*/
	$.each(_deductions,function(index,val){
		$("#deduction_id").append($("<option>").val(val.id).text(val.local_name));
	});
	/*--------------------------------*/

	/*--金額input--------------------------*/
	//chkCode():@string_check.js
	$("#amount").on("change",function(){ $(this).chkCode(); });
	/*-------------------------------------*/

	/*--登録ボタン---------------*/
	$("input.submit_button").on("click",function(e){

		if ($.stringCheck()) {//stringCheck():@string_check.js

			var data = {
					mode:mode,
					table:"used_deductions",
					month:_monthCondition +"-01",
					deduction_id:$("#deduction_id").val(),
					amount:$("#amount").val(),
					staff_id:$("#staff_id").val(),
					memo:$("#memo").val()
			};
			if (usedDeductionsId) {//update時
				data.id = usedDeductionsId;
			}

			$.sendAjax(data,{
				async: true,
				success: function(res) {
					if (mode == "insert") {//追加時
						alert("給与控除を追加しました");
					}else {//編集時
						alert("給与控除を変更しました");
					}
					location.reload();
				}
			});
		}
	});
	/*----------------------------*/

	/*--削除ボタン-----------------------*/
	$("input.delete_button").on("click",function(e){

		var data = {
				mode:"delete",
				table:"used_deductions",
				id:usedDeductionsId
		};

		$.sendAjax(data,{
			async: true,
			success: function(res) {
				alert("明細を削除しました");
				location.reload();
			}
		})
	});
	/*------------------------------------*/

	/*--リスト表示--------------------*/
	if (_usedDeductions.length > 0) {
		$.createDeductionList();
	}else {
		$(".contents_area").append(
			$("<p>").attr("class","slategray").text("給与控除明細がありません"));
		$("#dd_filter_box").hide();
	}
	/*----------------------------------*/

	/*--リストクリック------------------------------------*/
	var mySlide = $(".my_slide");
	$(".dd_list").on("click",function(e){

		mode = "update";
		usedDeductionsId = $(this).attr("title");
		mySlide.find("caption").text("編集");


		//スライド内コンテンツに値をセット
		$.each(_usedDeductions,function(){
			if (this.id == usedDeductionsId) {
				mySlide.find("#staff_id").val(this.staff_id);
				mySlide.find("#deduction_id").val(this.deduction_id);
				mySlide.find("#amount").val(this.amount);
				mySlide.find("#memo").val(this.memo);
			}
		});
		//削除ボタんを表示
		button_boxies.addClass("parallel").eq(1).show();

		mySlide.slideOpen();
	});
	/*-----------------------------------------------------*/

	var ddFilter = $("#dd_filter");

	$.each(_deductions,function(index,val){
		ddFilter.append($("<option>").val(val.id).text(val.local_name));
	});

	ddFilter.on("change",function(){
		$(this).ddFiltering();
	}).ddFiltering();

});

(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide({hideTabImg:"../image/plus.png",
			openTabImg:"../image/close_2.png",closeFunc:true});
	}
	//スライドclose時の処理
	$.slideCloseFunc = function() {
		//モードリセット
		mode = "insert";
		usedDeductionsId = null;
		//caprionリセット
		$("table caption").text("給与控除を追加する");
		//削除ボタンを非表示
		button_boxies.removeClass("parallel").eq(1).hide();
		//inputのvalueをリセット
		$("#amount,#usd_deduction_id,#memo").val("");
		$("#staff_id option:first").attr("selected",true);
		$("#deduction_id option:first").attr("selected",true);
	}
	/*-----------------------------------------------------*/

	//リスト表示
	$.createDeductionList = function() {
		var tempdt = $("#temp_dt");
		var tempdd = $("#temp_dd");

		$.each(_totalDeductions,function(index,val){
			//テンプレをクローン
			var dt = tempdt.clone().removeAttr("id").show();
			var dd = tempdd.clone().removeAttr("id").show();
			tempdt.before(dt);
			tempdt.before(dd);

			//スタッフ名セット
			$.each(_staffs,function(){
				var salonId;
				if (this.id == val.staff_id) {
					dt.text(this._name);

					//company
					if (_visiter == "company") {
						salonId = this.salon_id;
						$.each(_salonInfo,function(i,v){
							if (salonId == v.id) {
								var salonName = " " +v._name.substr(0,3);
								var salonLabel =
									$("<span>").text(salonName)
										.css({"font-size":8});
								dt.append(salonLabel);
							}
						});
					}
				}
			});
			//合計額
			dt.append(
					$("<span>").attr("class","right")
					.text("合計："+$.delimiting(val.total)));



			//控除明細
			var templi = dd.find(".temp_li");
			$.each(_usedDeductions,function(){
				var usedDiduction = this;

				if (usedDiduction.staff_id == val.staff_id) {
					var li = templi.clone().removeClass("temp_li").show()
						.attr({"title":usedDiduction.id,
								"data-deduction_id":usedDiduction.deduction_id});

					li.find(".name").text(usedDiduction.local_name);
					li.find(".amount").text($.delimiting(usedDiduction.amount));
					li.find(".memo").text(usedDiduction.memo);

					templi.before(li);
				}
			});

			$.moveAccImgToMiddle();//@common.js
		});
	}
	//科目フィルタリング合計額表示
	$.fn.ddFiltering = function() {
		var ddtype = $(this).val();
		var total = 0;

		if (ddtype == 0) {
			$.each(_totalDeductions,function(index,val){
				total = total + parseInt(val.total);
			});
		}else {
			$.each(_usedDeductions,function(index,val){
				if (val.deduction_id == ddtype) {
					total = total + parseInt(val.amount);
				}
			});
		}

		$("#dd_total").text("合計：" + $.delimiting(total));

		$("li.dd_list[data-deduction_id="+ ddtype +"]")
			.css({"background-color":"#e67e22","color":"white","opacity":"0.7"});
		$("li.dd_list:not([data-deduction_id="+ ddtype +"])")
			.css({"background-color":"white","color":"#34495e","opacity":"1"});
	}
}(jQuery));
