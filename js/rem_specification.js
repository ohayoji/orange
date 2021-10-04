//まだ来店処理されてない伝票の存在フラグ
var notRemComp = false;

jQuery(function ($) {
	
	
	
	
	
	/*--月セレクタ----------------*/
	//月セレクタ初期設定
	//var select = $("select[name=month]");
	var select = $("#rem_spec_month_selecter");
	$.each(_monthDatas,function(index,val){
		select.append($("<option>").val(val.value).text(val.text));
	});
	select.val(_monthCondition).on("change",function(){
		$(this).parent("form").submit();
	});
	/*------------------------------*/
	
	//合計表示領域
	$("#monthly_sales").text(CURRENCY + " "+$.delimiting(_report.total_sale));
	$("#rem_total").text($.delimiting(_report.total_rem));
	$("#salary").text($.delimiting(_report.salary));
	$("#incentive").text($.delimiting(_report.total_inc));
	$("#add_rem").text($.delimiting(_report.add_rem));
	$("#used_deductions").text($.delimiting(_report.used_deductions));
	
	/*--手当明細表示--------------------*/
	if (_addRems.length > 0) {
		var adrmbox = $("#adrm_box").show();
		var tempadrm = $("#temp_adrm");
		
		$.each(_addRems,function(index,val){
			var adrm = tempadrm.clone().removeAttr("id").show();
			
			adrm.find(".adrm_title").text(val.title);
			adrm.find(".adrm_amount").text($.delimiting(val.amount));
			
			tempadrm.before(adrm);
		});
	}else {
		//$("#no_adrm_message").show();
	}
	/*--------------------------------*/
	/*--給与控除明細表示--------------------*/
	if (_usedDeductions.length > 0) {
		var usdddbox = $("#usddd_box").show();
		var tempusddd = $("#temp_usddd");
		
		$.each(_usedDeductions,function(index,val){
			var usddd = tempusddd.clone().removeAttr("id").show();
			
			var ddname;
			$.each(_deductions,function(i,v){
				if (v.id == val.deduction_id) {
					ddname = v.local_name;
				}
			});
			
			usddd.find(".usddd_name").text(ddname);
			usddd.find(".usddd_amount").text($.delimiting(val.amount));
			
			tempusddd.before(usddd);
		});
	}else {
		//$("#no_adrm_message").show();
	}
	/*--------------------------------*/
	
	/*--承認ボタン----------------*/
	
	var appBtn = $("#approval_btn");
	if (_appBtnFlag == false) {
		$("#approval_li").remove();
		//appBtn.remove();
	}else {
		if (_approvedFlag == 0) {
			appBtn.on("click",function(){ $.clickBtn(); });
		}else {
			appBtn.kill();
		}
	}
	/*----------------------------*/
	
	/*--伝票明細表示--------------------*/
	if (_receipts.length > 0) {
		var chilTempdt = $("#child_temp_dt");
		var chilTempdd = $("#child_temp_dd");
		
		var date = null;
		
		var dt;
		var dd;
		var chilTempli;
		
		$.each(_receipts,function(index,val){
			//来店処理が未処理ならフラグを立てる
			if (val.rem_comp == 0) {
				notRemComp = true;
			}
			
			if (date != val.date) {
				//日が変わったら見出しを作成
				dt = chilTempdt.clone().removeAttr("id").show();
				dd = chilTempdd.clone().removeAttr("id").show();
				chilTempli = dd.find("#child_temp_li");
				
				date = val.date;
				dt.text(date+"日");

				chilTempdt.before(dt);
				dt.after(dd);
			}
			
			//伝票リスト
			var li = chilTempli.clone().removeAttr("id").show();
			
			/*--明細表示-----------*/
			var cosName = "";
			if (val.costomer) {
				cosName = val.costomer;
			}
			li.find("p.costomer").text(cosName+" 様");
			
			var array = ["tec_sale","tec_rem_v","tec_inc",
			             "pro_sale","pro_rem_v","pro_inc"];
			$.each(array,function(i,v){
				if (val[v]) {
					li.find("."+v).text($.delimiting(val[v]));
				}else {
					li.find("."+v).text(0);
				}
			});
			
			chilTempli.before(li);
			/*-----------------------*/
		});
	}else {
		$("#no_rec_message").show();
	}
	/*--------------------------------*/
});

(function($) {
	//承認ボタンクリック
	$.clickBtn = function() {
		if (notRemComp) {
			alert("まだ来店処理がされていない伝票があります" +
					"\nサロン管理者に来店処理をしてもらいましょう");
			return false;
		}
		if (confirm(
				$("select[name=month] option:selected").text() +
				" の報酬明細を承認してよろしいですか？"+
				"この操作は取り消せません")) {
			
			
			var data = {
					mode:"insert",table:"approved_rems",
					staff_id:_staffId,"month":_monthCondition+"-01",
					rem:_report.total_rem,data_type:"text"
			};
			
			
			$.sendAjax(data,{
				success:function(res){
					//alert(res);
					if (res) {
						alert("承認しました");
						$("#approval_btn").kill();
					}
				}
			});
			
		}
	}
	//承認ボタンを無効化
	$.fn.kill = function() {
		$(this).addClass("button_disabled").prop("disabled",true).val("報酬承認済み");
	}
}(jQuery));