/**
 *
 */
jQuery(function ($) {

	// console.log('_staffReport', _staffReport);

	/*--月セレクタ----------------*/
	var select = $("select[name=month]");
	//月セレクタ初期設定
	$.each(_monthDatas,function(index,val){
		select.append($("<option>").val(val.value).text(val.text));
	});
	select.val(_monthCondition);
	/*------------------------------*/
	//サロンセレクタ

	if (_visiter == "company") {
		$("#salon_sel_area").css("display","block");
		//value0だとpostされない仕様みたいです
		$("select[name=salon]")
		.append($("<option>").val('all').text("全サロン"));
		$.each(_salonInfo,function(index,val){
			$("select[name=salon]")
				.append($("<option>").val(val.id).text(val._name));
		});
		$("select[name=salon]").val(_salon);
	}

	/*--レポート表示----------------------*/
	var nameTable = $("#name_table");
	var dataTable = $("#data_table");
	if (_staffReport.length > 0) {
		$.each(_staffReport,function(index,val){
			//カラー設定
			var color;
			if (val.position == "S") {
				color = "#379cbf";
			}else {
				color = "#e74c3c";
			}
			if(_visiter != "company"){
				nameTable.append($("<tr>")
						.append($("<td>").attr("class","icon").text(val.position).css({"color":color}))
						.append($("<td>").attr("class","title").text(val.staff_name)));
			}
			else if(_visiter == "company"){
				nameTable.append($("<tr>")
						.append($("<td>").attr("class","icon").text(val.position).css({"color":color}))
						.append($("<td>").css("font-size", "12px").attr("class","title").html(val.staff_name+"<br>"+val.salon_name)));
			}
			dataTable.append($("<tr>")
				.append($("<td>").attr("class","ta_right orange").text($.delimiting(val.total_sale)))
        .append($("<td>").attr("class","ta_right").append(
          $('<span class="detail_amount">' + $.delimiting(val.tec_sale) + '</span>')))
          .append($("<td>").attr("class","ta_right").append(
            $('<span class="detail_amount">' + $.delimiting(val.pro_sale) + '</span>')))
				.append($("<td>").attr("class","ta_right f_14").text($.delimiting(val.salary)))
				.append($("<td>").attr("class","ta_right f_14").text($.delimiting(val.total_inc)))
				.append($("<td>").attr("class","ta_right f_14").text($.delimiting(val.add_rem)))
				.append($("<td>").attr("class","ta_right f_14").text($.delimiting(val.deduction)))
				.append($("<td>").attr("class","ta_right").css("color","#379cbf").text($.delimiting(val.total_rem)))
				.append($("<td>").attr("class","ta_center f_14").css("color","#a3c600").text(val.paid)));
		});

		nameTable.find("tr").not(":first").filter(":odd")
				.css("background-color","#f5f4ed");
		dataTable.find("tr").not(":first").filter(":odd")
				.css("background-color","#f5f4ed");
	}else {
		$("#no_message").css("display","block");
		$("p.detail").css("display","none");
	}

	/*-----------------------------------*/
});
(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide();
	}
	/*-----------------------------------------------------*/
}(jQuery));
