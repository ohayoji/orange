/**
 *
 */
var monthSelecter;
jQuery(function ($) {






	/*--月セレクタ----------------*/
	monthSelecter = $("select[name=month]");
	//月セレクタ初期設定
	$.each(_monthDatas,function(index,val){
		monthSelecter.append($("<option>").val(val.value).text(val.text));
	});
	monthSelecter.val(_monthCondition);
	/*------------------------------*/

	/*--スタッフ列追加---*/
	var dataTable = $("#data_table");
	var rows = dataTable.find("tr");

	$.each(_staffs,function(staffIndex,staffVal){

		$.each(rows,function(rowIndex,rowVal){

			//セル作成
			var cell;
			if (rowIndex == 0) {//名前
				cell = $("<th>").text(staffVal._name);
				var p = $("<p>").addClass("f_small").text(staffVal.salon_name);
				cell.append(p);
			}else {

				cell = $("<td>")
					.addClass("amount")
					.attr({
						"data-staff_id": staffVal.id,
						"name": rowVal.getAttribute("name")
						});
				cell.createChildNodes(staffVal, rowVal);
			}

			//行に追加
			$(rowVal).append(cell);
		});
	});
	/*-----------------*/

	/*--手当明細の高さ調整--------------*/
	/*var h = $("tr[name=add_rem_meisai] td").innerHeight();

	$(".add_rem_meisai td").innerHeight(h);*/
	/*--------------------------------*/


	//控除をinputに表示
	for (var i = 0; i < _usedDeductions.length; i++) {

		var record = _usedDeductions[i];

		var recordId = record.id;
		var staffId = record.staff_id;
		var deductionId = record.deduction_id;

		var input = $("td[data-staff_id="+ staffId
				+"] input[data-deduction_id="+ deductionId +"]");
		input.val(record.amount).attr("data-record_id",recordId);

	}


	//スタッフごとに合計計算
	$.each(_staffs,function(staffIndex,staffVal){
		$.carcTotal(staffVal.id);
	});

	//デザイン
	$("tr[name=salary_total] td, tr[name=kousei_kenkou_koyou] td")
		.css("background-color","#f2f2f2");
	$("tr[name=kousei_kenkou] td")
		.css({"color":"rgb(55, 156, 191)","font-size":14});
	$("tr[name=sashihiki] td")
		.css("background-color","#e6eff5");
	$("tr[name=shiharai] td")
		.css({"background-color":"#3a87ad", "color":"white"});
	$(".title_row > *").css("height","50pt");
});

(function($) {
	//tdの子要素作成メソッド
	$.fn.createChildNodes = function(staffVal, rowVal) {

		var rowName = rowVal.getAttribute("name");

		if (rowName == "salary") {//基本給
			this.text(staffVal.salary);

		}else if (rowName == "rem") {//歩合

			if (staffVal.total_incentive) {
				this.text(staffVal.total_incentive);
			}

		}else if (rowName == "add_rem") {//手当
			for (var i = 0; i < _totalAddRems.length; i++) {
				if (_totalAddRems[i].staff_id == staffVal.id) {
					this.text(_totalAddRems[i].total);
				}
			}

		}else if (rowName == "add_rem_meisai") {//手当明細

			//明細を表示
			var str = "";
			for (var i = 0; i < _addRems.length; i++) {
				var adrm = _addRems[i];
				if (adrm.staff_id == staffVal.id) {
					if (i > 0) {
						str = str + "<br>";
					}
					str = str + adrm.title + "／" + adrm.amount;
				}
			}
			//表示、高さも確実に指定
			var p = $("<p>").html(str);
			this.append(p);
			//this.css("height",100).html("<p>" + str + "</p>");

		}else if (rowName == "kousei"
					|| rowName == "kenkou"
					|| rowName == "koyou"
					|| rowName == "gensen"
					|| rowName == "juumin"){
			//厚生年金、健康保険、雇用保険、源泉、住民税

			//deduction_id
			var deductionId = rowVal.getAttribute("data-deduction_id");


			var input = $.getDataInput().attr("data-deduction_id",deductionId)
				.on("change",function(){

					//データ送信
					$.post(
							"../PHPClass/turba_func_SalarySheetModel.php",
							{
								mode: "update_used_deduction",
								id: $(this).attr("data-record_id"),
								month: monthSelecter.val() +"-01",
								deduction_id: $(this).attr("data-deduction_id"),
								staff_id: $(this).parent().attr("data-staff_id"),
								amount: $(this).val()
							},
							function(res) {

								if (res) {
									//insert及びdelete時はdata-record_id属性を確実に付与するためにリロード
									location.reload();
								}
							},
							"text"
							);
				});

			this.append(input);

		}else if (rowName == "other") {//その他

			var otherAmount = 0;
			$.each(_usedDeductions,function(index,val){
				if (val.deduction_id == 6 && val.staff_id == staffVal.id) {
					otherAmount = otherAmount + parseInt(val.amount);
				}
			});

			if (otherAmount > 0) {
				this.text(otherAmount);
			}
		}
	}


	//inputテンプレ
	$.getDataInput = function() {
		var input = $("<input>")
						.addClass("faint narrow03")
						.attr({"type": "number"});
		return input;
	}


	//合計計算
	$.carcTotal = function(staffId) {

		/*--給与合計-------*/
		var salary = parseInt(
				$("td[data-staff_id="+ staffId +"][name=salary]").text());
		if (!salary) {salary = 0;}
		var incentive = parseInt($("td[data-staff_id="+ staffId +"][name=rem]").text());
		if (!incentive) {incentive = 0;}
		var addRem =  parseInt(
				$("td[data-staff_id="+ staffId +"][name=add_rem]").text());
		if (!addRem) {addRem = 0;}


		var salaryTotal =
			$("td[data-staff_id="+ staffId +"][name=salary_total]");
		salaryTotal.text(salary + incentive + addRem);
		/*------------------*/

		/*--社保計,控除合計-------------*/
		var kousei = parseInt(
				$("td[data-staff_id="+ staffId +"][name=kousei] input").val());
		if (!kousei) {kousei = 0;}
		var kenkou = parseInt(
				$("td[data-staff_id="+ staffId +"][name=kenkou] input").val());
		if (!kenkou) {kenkou = 0;}
		var koyou = parseInt(
				$("td[data-staff_id="+ staffId +"][name=koyou] input").val());
		if (!koyou) {koyou = 0;}
		var kouseiKenkou = $("td[data-staff_id="+ staffId +"][name=kousei_kenkou]");
		kouseiKenkou.text(kousei + kenkou);
		var kouseiKenkouKoyou = $("td[data-staff_id="+ staffId +"][name=kousei_kenkou_koyou]");
		kouseiKenkouKoyou.text(kousei + kenkou + koyou);
		/*---------------------*/

		/*--差引支給額-------------*/
		var sashihiki = $("td[data-staff_id="+ staffId +"][name=sashihiki]");
		sashihiki.text(parseInt(salaryTotal.text()) - parseInt(kouseiKenkouKoyou.text()));
		/*-----------------------------*/

		/*--最終支払い額----------*/
		var gensen = parseInt(
				$("td[data-staff_id="+ staffId +"][name=gensen] input").val());
		if (!gensen) {gensen = 0;}
		var juumin = parseInt(
				$("td[data-staff_id="+ staffId +"][name=juumin] input").val());
		if (!juumin) {juumin = 0;}
		var other = parseInt(
				$("td[data-staff_id="+ staffId +"][name=other]").text());
		if (!other) {other = 0;}
		var shiharai = $("td[data-staff_id="+ staffId +"][name=shiharai]");
		shiharai.text(parseInt(sashihiki.text()) - gensen - juumin - other);
		/*---------------------------*/
	}
}(jQuery));
