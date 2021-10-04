/**
 *
 */
jQuery(function ($) {
	
	
	
	
	
	
	

	//月報作成完了あらアート
	if (_createComp) {
		alert("月報が作成されました");
	}

	/*--slide内コンテンツ----------------------*/

	//サロンセレクタ
	$.each(_salons,function(index,val){
		$("#salon").append($("<option>").val(val.id).text(val._name));
	});
	$("#salon").val(_condition.salon);
	/*if (_visiter == "salon") {
		//サロンログイン時は外衣等サロン以外を選択不可に
		$("#salon option").not(":selected").attr("disabled",true);
	}*/

	//月セレクタ
	$.each(_monthDatas,function(index,val){
		$("#month").append($("<option>").val(val.value).text(val.text));
	});
	$("#month").val(_condition.month);
	//ボタン
	$("#change_condition").on("click",function(){
		var data = {mode:"change", salon:$("#salon").val(), month:$("#month").val()};
		$.ajax({
			url: '../PHPClass/CreateReportModel.php',
			type: "POST",
			data: data
		}).then(function() {
			/* window.location.reload();だと
			 * POSTが復元されてしまうのでdocument.location
			 */
			document.location = "create_report.php";
		})
	});
	/*-----------------------------------------------------*/

	/*--メッセージ----------*/
	var message = $("#message");
	$.each(_salons,function(){
		if (this.id == _condition.salon) {
			message.find("span:first").text(this._name);
		}
	});
	$.each(_monthDatas,function(){
		if (this.value == _condition.month) {
			message.find("span:eq(1)").text(this.text);
		}
	});
	/*--------------------*/

	/*--レポートIDinput-----------*/
	$("input[name=rep_id]").val(_repId);
	/*--------------------------*/

	/*--テーブル-----------------------------------------------*/
	var table = $("table.report_table");
	$.each(_subjects,function(index,val){
		var td1 = $.titleCell(val);
		var td2 = $.amountCell(val);

		table.append($("<tr>").append(td1).append(td2));
		/*if (val.us_id) {
			var td1 = $.titleCell(val);
			var td2 = $.amountCell(val);

			table.append($("<tr>").append(td1).append(td2));
		}*/
	});
	table.find("tr:even").not(":first").css("background-Color","#f7fcfd");

	//レポートを反映
	if (_report) {
		$.each(_report,function(name,val){
			table.find("tr td input[name="+name+"]").val(val);
		});
	}else {
		table.find("tr td input").val(0);
	}
	/*----------------------------------------------------------*/

	/*--サブミット--------------------------------------*/
	$("form").submit(function() {
		//stringCheck():@string_check.js
		return $.stringCheck();
	});
	/*---------------------------------------------------*/
});
(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide();
	}
	/*-----------------------------------------------------*/

	//科目セル作成
	$.titleCell = function(val) {
		var cell = $("<td>").attr("class","title").text(val.local_name);
		//利益セルには計算ボタンを設置
		if (val._name == "income") {
			cell.setCarcBtn();
		}
		if (val._name == "tec_sales" || val._name == "pro_sales") {
			if (_autoCalcSales[val._name]) {
				cell.setReflectionBtn(val._name);
			}
		}
		return cell;
	}
	//金額セル作成
	$.amountCell = function(val) {
		var cell = $("<td>").attr("class","amount");

		//クラス設定文字列
		var classStr = "faint narrow03 not_unique_char only_num not_null";
		if (val._name == "income") {
			//利益額はマイナスも許容する"only_int"を設置
			classStr = "faint narrow03 not_unique_char only_int not_null";
		}

		var input = $("<input>").attr({
			"type":"text","name":val._name,"class":classStr})
			.on("change",function(){ $(this).chkCode(); })
			.appendTo(cell);
		return cell;
	}
	//利益計算ボタン
	$.fn.setCarcBtn = function() {
		//type="button"を指定しないとsubmitされてしまう
		var btn = $("<button>")
			.attr({"class":"carc_button","type":"button"})
			.text("利益を計算 →").css({"float":"right","font-size":12})
			.on("click",function(){
				$.carcIncome();
			});
		$(this).append(btn);
	}
	//利益計算
	$.carcIncome = function() {
		var sales = 0;
		var cost = 0;

		$("td.amount input").each(function(index,val) {
			var subName = $(this).attr("name");
			var amount = $(this).val();

			//利益inputは無視
			if (subName != "income") {

				if (subName == "tec_sales" || subName == "pro_sales") {
					//売上を加算
					sales = sales + parseInt(amount);
				}else {
					//経費を加算
					cost = cost + parseInt(amount);
				}
			}
		})
		//利益額を表示
		$("input[name=income]").val(sales - cost);
	}
	//自動計算売上反映ボタン
	$.fn.setReflectionBtn = function(type) {
		var btn = $("<button>")
			.attr({"class":"carc_button","type":"button"})
			.text("自動計算（" + CURRENCY + $.delimiting(_autoCalcSales[type]) +"）→")
			.css({"float":"right","font-size":12})
			.on("click",function(){
				$("input[name="+ type +"]").val(_autoCalcSales[type]);
			});
		$(this).append(btn);
	}
}(jQuery));
