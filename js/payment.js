jQuery(function ($) {
	
	
	
	
	/*--月セレクタ----------------*/
	var monSel = $("select.monSel");
	//月セレクタ初期設定
	$.each(_monthDatas,function(index,val){
		monSel.append($("<option>").val(val.value).text(val.text));
	});
	/*------------------------------*/
	
	/*--スタッフセレクタ---------------*/
	var staffSel = $("select[name=staff_id]");
	//スタッフoptionを追加
	$.each(_staffInfo,function(index,val){
		var text;
		if (_visiter == "salon") {
			text = val.staff_name;
		}else {
			text = val.staff_name+"/"+val.salon_name;
		}
		
		//退店済みスタッフ
		if (val.deleted == 1) {text = "("+text+")";}
		
		var option = $("<option>").val(val.staff_id).text(text);
		staffSel.append(option);
	});
	/*---------------------------------*/
	
	/*--検索条件がある場合には選択状態にする-------------------*/
	if (_condition) {
		$.each(_condition,function(name,val){
			$("*[name="+name+"]").val(val);
		});
	}
	/*----------------------------------------------------*/
	
	/*--サブミットチェック-------------------------*/
	$("form#search_field").submit(function() {
		//$.stringCheck():@string_check.js
		if (!$.stringCheck()) {
			return false;
		}
	})
	/*-----------------------------------------*/
	
	//リスト表示
	$.createList();
});
(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide({hideTabImg:"../image/search.png",
			openTabImg:"../image/close_2.png"});
	}
	/*-----------------------------------------------------*/
	//リスト表示
	$.createList = function() {
		var totalAmo = 0;
		
		if (_approvedRems.length > 0) {
			
			var tempdt = $("#temp_dt");
			var tempdd = $("#temp_dd");
			
			var dt;
			var dd;
			var templi;
			
			var staffId = null;
			
			
			$.each(_approvedRems,function(index,val){
				//合計金額にプラス
				totalAmo = totalAmo + parseInt(val.rem);
				
				if (staffId != val.staff_id) {
				
					//スタッフ変わったらdtを作成
					staffId = val.staff_id;
					//サロン名（サロンログイン時は表示しない）
					var salonName = val.salon_name;
					if (_visiter == "salon") {
						salonName = "";
					}
					
					dt = tempdt.clone().removeAttr("id")
						.text(val.staff_name)
						.append(
							$("<span>").attr("class","right").text(salonName))
						.show();
					dd = tempdd.clone().removeAttr("id").show();
					
					tempdt.before(dt);
					tempdt.before(dd);
					templi = dd.find("#temp_li");
				}
				
				var li = templi.clone().removeAttr("id").show();
				
				li.find(".month").text(val.month);
				li.find(".amount").text($.delimiting(val.rem));
				var btn = li.find("input[type=button]");
				btn.setflip(
						{faceTex:"支払済にする",backTex:"未払いに戻す",fontSize:14})
						.addClass("payment_change_btn")
						.on("click",function(e){ $(this).changePaid();})
						.data("aprms_id",val.id);
				//支払済なら切り換え
				if (val.paid == 1) { btn.flip(); }
				
				templi.before(li);
			});
		}
		
		//合計表示
		$("#numRecords").text(_approvedRems.length);
		$("#totalamo").text($.delimiting(totalAmo));
	}
}(jQuery));

(function($) {
	$.fn.changePaid = function() {
		var btn = $(this);
		var value;
		if ($(this).data("disp_side") == "face") {
			value = 1;
		}else{
			value = 0;
		}
		
		var data = {mode:"update",table:"approved_rems",
				id:this.data("aprms_id"),paid:value};
		$.sendAjax(data,{
			success: function(res) {
				if (res) {
					alert("レコードを更新しました");
					btn.flip();
				}else {
					alert("レコード更新に失敗しました");
				}
			}
		});
	}
}(jQuery));