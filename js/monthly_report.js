jQuery(function ($) {
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*--検索フィールド----------------*/
	//サロンセレクタ
	if (_visiter == "company") {
		$("#salon_sel_area").css("display","block");
		$.each(_salonInfo,function(index,val){
			$("select[name=salon]")
				.append($("<option>").val(val.id).text(val._name));
		});
		$("select[name=salon]").val(_salon);
	}
	//月セレクタ初期設定
	var end = $(".monSel[name=end]"), start = $(".monSel[name=start]");
	$.each(_monthDatas,function(index,val){
		if (index < 12) {
			//endセレクタには過去１年分だけoptionを追加
			end.append($("<option>").val(val.value).text(val.text));
			//startセレクタにoption追加
			start.append($("<option>").val(val.value).text(val.text));
		}else {
			//startセレクタに残りのoption追加
			start.append($("<option>").val(val.value).text(val.text));
		}
	});
	//年セレクタ初期設定
	$.each(_yearDatas,function(index,val){
		$("select.yearSel[name=end]").append($("<option>").val(val.value +"-12").text(val.text));
		$("select.yearSel[name=start]").append($("<option>").val(val.value +"-01").text(val.text));
	});
	
	//全てのendセレクタにアクションを設定
	$("#search_field select[name=end]").on("change",$(this).resetBrother);
	
	//検索単位タイプradioボタン
	$("input[name=unit_type]").on("click",function(){
		
		$(this).disableBrother();
		$(this).parent().next("dd").find("select[name=end]").resetBrother();
	}).filter("#"+_unitType).click();
	
	//有効セレクタを選択済みに
	var enableSel = $("select:not([disabled=disabled])");
	enableSel.filter("[name=start]").val(_start);
	enableSel.filter("[name=end]").val(_end).resetBrother();
	
	//グラフ用勘定科目フィールド
	$.createSubList();
	/*------------------------------*/
	
	/*--メッセージ----------*/
	var message = $("#message");
	var salonName = null;
	if (_visiter == "company") {
		$("select[name=salon] option").each(function() {
			if ($(this).val() == _salon) { 
				salonName = $(this).text();
			}
		});
	}else {
		salonName = _postName;
	}
	/*----------------------------*/
	
	//画像をheaderにセット
	//$("#header_icons").appendTo("#header #icon_area");
	
	var len = _report.length;
	//_reportがある場合は月報を表示
	if (len > 0) {
		message.find("span:first").text(salonName);
		message.find("span:eq(1)").text(_report[0].month);
		message.find("span:eq(2)").text(_report[len-1].month);
		
		//アイコン切り替え
		$.changeViewModeIcon(_viewMode);
		
		if (_viewMode == "table") {
			/*==テーブルモード=====================================*/
			
			$("#report_list_area").show();
			
			/*--レポートテーブル--------------------*/
			//調節幅
			var space = 32;
			//月報テーブル幅(仮)
			var tableW = 150;
			//データテーブルmargin-right
			var tableMargin = 12;
			
			/*--data_table_box幅,月報テーブル幅(再設定)---------*/
			//データ表示エリア幅
			var areaW = $("#data_table_area").width();
			//仮のbox幅
			var tempW = _report.length*tableW + (_report.length-1)*tableMargin;
			//data_table_box幅
			var boxW = null;
			
			if (areaW > tempW) {
				//テーブル幅を調整
				tableW = (areaW - ((_report.length-1)*tableMargin)) /_report.length;
			}else {
				//(+500で余裕を持たせる)
				boxW = _report.length*tableW + (_report.length-1)*tableMargin +500;
			}
			/*------------------------------------------------*/
			
			//科目名テーブル
			$.each(_usingSub,function(index,val){
				var tr = $("<tr>").append($("<td>").attr("class","title").text(val.local_name));
				$("#title_table").append(tr);
			});
			$("#title_table tr th").css("padding-top",space);
			
			//データエリア
			$.each(_report,function(index,val){
				var caption = $("<caption>").text(val.month).css("height",space -2);
				var tr = $("<tr>").append($("<th>").text("金額")).append($("<th>").text("%"));
				var table = $("<table>").attr("class","report_table").css("width",tableW).append(caption).append(tr);
				
				$.each(_usingSub,function(sindex,sval){
					var total = parseInt(val.tec_sales) + parseInt(val.pro_sales);
					var ratio = (parseInt(val[sval._name]) / total) *100;
					var td1 = $("<td>").attr("class","amount").text($.delimiting(val[sval._name]));
					var td2 = $("<td>").attr("class","ratio").text(ratio.toFixed(1));
					
					table.append($("<tr>").append(td1).append(td2));
				});
				
				$("#data_table_box").append(table);
			});
			//行に色付け
			$("#report_list_area table").find("tr:odd").css("background-color","#f7fcfd");
			//月報テーブル間に隙間
			$("#data_table_box table:not(:last)").css("margin-right",tableMargin);
			//包括boxの幅を指定
			$("#data_table_box").css("width",boxW);
			
			/*=======================================================*/
		}else if (_viewMode == "graph") {
			/*==グラフモード===========================================*/
			$("#report_graph_area, #subject_sel_area").show();
			
			//$.createSubList();
			/*--graph--------------------*/
			//表示データ
			var viewData = [];
			//var viewDataChild = [];
			viewData[0] = ['日付', '売上'];//
			var i = 2;
			$.each(_usedSub,function(index,val){
				if(val=="checked"){
					viewData[0][i] = _usedSub_localName[index];
					i++;
				}
			});
			
			var totalUsed = i-2;
			
			for(var j=0;j < _report.length; j++){
				viewData[j+1] = [];
				viewData[j+1][0] = _report[j].month;
				var tec_sales = parseInt(_report[j]["tec_sales"]);
				var pro_sales = parseInt(_report[j]["pro_sales"]);
				var total_sales = tec_sales + pro_sales;
				
				viewData[j+1][1] = total_sales;
				var k = 2;
				$.each(_report[j], function(index, val){
					if(_usedSub[index] == "checked"){
						viewData[j+1][k] = parseInt(val)/total_sales ;
						k++;
					}
				});
			}

			
			var seriesArray = [];
			for(l=1; l<= totalUsed; l++){
				seriesArray[l] = {type: "line",targetAxisIndex: 1};
			}
			var hAxis = {slantedText: true, slantedTextAngle: 90};
			if (_unitType == "quarter_total" || _unitType == "quarter_ave") {
				hAxis.textStyle = {fontSize: 6};
			}
			var optionsChart = {
				seriesType: "bars",
				series: seriesArray,

				//isStackedはバーの積み上げのプロパティ
				isStacked: true,

				//縦軸の設定。1軸の場合はvAxis、2軸の場合はvAxesとなる点に注意
				hAxis: hAxis,
				vAxes: [{viewWindowMode:'pretty'}, {format:'percent'}],
				tooltip: { trigger: 'none' },
				enableInteractivity: false,
				chartArea:{width:"60%"},
				legend:{maxLines:10}
			};
			

			//描画処理：onloadで呼び出し
		    function drawVisualization() {
				var dataChart = google.visualization.arrayToDataTable(viewData);
				//描画
				//comboチャートを使うことで、バーと折れ線の組み合わせが可能
				var chart = new google.visualization.ComboChart(document.getElementById('report_graph_area'));
				chart.draw(dataChart, optionsChart);
				google.visualization.events.removeAllListeners(chart)
			}
			//ページ読み込み時にロード
			window.onload = drawProc;
			function drawProc(){
				drawVisualization();
			}
			/*=================================================*/
		}else {
			/*==リストモード========================================*/
			$("#report_list_list_area").show();
			
			var dl = $("#report_list_list_area").find("dl");
			var tempdd = $("#list_temp_dd");
			//データ
			$.each(_report,function(index,val){
				
				var dd = tempdd.clone().show().prependTo(dl);
				
				var tecSales = parseInt(val.tec_sales);
				var proSales = parseInt(val.pro_sales);
				
				dd.find(".tec").text(CURRENCY+ $.delimiting(tecSales));
				dd.find(".pro").text(CURRENCY+ $.delimiting(proSales));
				dd.find(".total").text(CURRENCY+ $.delimiting(tecSales + proSales));
				
				dd.find("p").text(val.month);
				
			});
			/*======================================================*/
		}
		
	}else {//レポートがない場合
		//いらない要素を削除しメッセージを表示
		$("#message *").remove();
		$("#title_table").remove();
		$("#message").text("指定された期間のデータがありません");
	}
	/*------------------------------------------------*/
});
(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide();
	}
	/*-----------------------------------------------------*/
	//アイコン切り替え
	$.changeViewModeIcon = function(viewMode) {
		var mode = ["graph","table","list"];
		var icons = $("#header_icons");
		
		for (var i = 0; i < mode.length; i++) {
			
			if (viewMode == mode[i]) {
				icons.find("."+ mode[i] +"_img.off").hide();
				icons.find("."+ mode[i] +"_img.on").show();
			}else {
				icons.find("."+ mode[i] +"_img.on").hide();
				icons.find("."+ mode[i] +"_img.off").show();
			}
		}
	}
	//radioボタン選択時
	$.fn.disableBrother = function() {
		var type = $(this).prop("id");
		//親dt
		var parentDt = $(this).parent();
		//叔父dtを取得し弟ddのselectを無効
		var uncles = parentDt.siblings("dt:not([title="+type+"])")
			.next("dd").find("select").attr("disabled","disabled");
		//自分の弟ddのselectを有効
		parentDt.next("dd").find("select").removeAttr("disabled");
	}
	//endセレクタのアクション startセレクタのoptionの一部を無効にする
	$.fn.resetBrother = function() {
		
		var index = $(this).find("option:selected").index();
		var unitType = $(this).parent().prev().prop("title");
		
		if (unitType != "monthly") {//月次セレクタ以外
			
			var brother = $(this).next("select");
			
			if (unitType == "quarter_total" || unitType == "quarter_ave") {
				//endで選択されている月から過去３年を有効、それ以外を無効にする
				$.each(brother.find("option"),function(i,val){
					if (i < index || i > index+35) {
						$(this).attr("disabled",true);
					}else {
						$(this).attr("disabled",false);
					}
				});
			}else if (unitType == "year") {
				//endで選択されているindex未満を無効にする
				brother.find("option:lt("+index+")").attr("disabled",true);
			}
			

			if (brother.find("option:selected").index() < index) {
				brother.find("option:eq("+index+")").prop("selected",true);
			}
		}else {//月次セレクタ
			//startにvalueをセットする
			
			$(this).next("input").val(_monthDatas[index +11].value);
		}
	}
	/*--勘定科目リスト---------------------------*/
	$.createSubList = function() {
		$.each(_usingSub,function(index,val){
			
			//技術売上、商品売上以外
			if (val["_name"] != "tec_sales" && val["_name"] != "pro_sales") {
				var div = $("<div>").css({"padding":4,"float":"left"});

				var input = $("<input>")
					.attr({"type":"checkbox","name":val["_name"], "value":"checked", "id":val["_name"]})
					.css("margin-right","2px");
				//使用項目にチェック（後ほど実装）
				if (_usedSub[val["_name"]] == "checked") { input.prop("checked", true); }
					
				var label = $("<label>").text(val["local_name"]).css("margin-right","5px").attr("for",val["_name"]);
				
				div.append(input).append(label);
				$("#subject_sel_contents").append(div);
			}
			
		});
	}
}(jQuery));