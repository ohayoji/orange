//登録、削除ボタン
var button_boxies;

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

	/*--金額input--------------------------*/
	//chkCode():@string_check.js
	$("#amount").on("change",function(){ $(this).chkCode(); });
	/*-------------------------------------*/

	/*--登録ボタン---------------*/
	$("input.submit_button").on("click",function(e){

		//報酬承認済みならキャンセル
		var staffId = $("#staff_id").val();
		if (_approvalStaffs.indexOf(staffId) !== -1) {
			alert("すでに"+$("#staff_id").find("option[value="+staffId+"]").text()+"さんが報酬を承認しているため追加・変更できません");
			return false;
		}

		if ($.stringCheck()) {//stringCheck():@string_check.js

			var mode = $("table caption").data("mode");
			//var staffId = $("#staff_id").val();
			var title = $("#title").val();
			var amount = $("#amount").val();
			var id = $("#add_rem_id").val();

			var data = "mode="+mode+"&staff_id="+staffId+
						"&title="+title+"&amount="+amount+"&id="+id;


			$.post("../PHPClass/AddRemModel.php",data,function(res){
				alert(res);
				location.reload();
			},"text");
		}
	});
	/*----------------------------*/

	/*--削除ボタン-----------------------*/
	$("input.delete_button").on("click",function(e){

		//報酬承認済みならキャンセル
		var staffId = $("#staff_id").val();
		if (_approvalStaffs.indexOf(staffId) !== -1) {
			alert("すでに"+$("#staff_id").find("option[value="+staffId+"]").text()+"さんが報酬を承認しているため削除できません");
			return false;
		}

		var data = "mode=add_rems_delete&id="+$("#add_rem_id").val();

		$.post("../PHPClass/AddRemModel.php",data,function(res){
			alert(res);
			location.reload();
		},"text");
	});
	/*------------------------------------*/

	/*--リスト表示--------------------*/
	if (_addRems.length > 0) {
		$.createAddRemList();
	}else {
		$(".contents_area").append(
			$("<p>").attr("class","slategray").text("手当明細がありません"));
	}
	/*----------------------------------*/


	/*--リストクリック------------------------------------*/
	var mySlide = $(".my_slide");
	$(".adrm_list").on("click",function(e){
		var id = $(this).data("add_rem_id");

		//スライド内コンテンツに値をセット
		$.each(_addRems,function(){
			if (this.add_rem_id == id) {
				mySlide.find("caption").data("mode","edit").text("編集");
				mySlide.find("#staff_id").val(this.staff_id);
				mySlide.find("#title").val(this.title);
				mySlide.find("#amount").val(this.amount);
				mySlide.find("#add_rem_id").val(id);
			}
		});
		//削除ボタんを表示
		button_boxies.addClass("parallel").eq(1).show();
		//$("input.delete_button").css("display","block");

		mySlide.slideOpen();
	});
	/*
	$("body").on('click', function(){

		mySlide.css('overflow', '');
		setTimeout(function(){
			mySlide.click();
		}, 400);
		//return false;
	});
	*/

	mySlide.on('click', function(e){

		mySlide.css('overflow', 'auto');
		e.stopPropagation();
		//return false;
	});

	//これは効かないらしい
	/*
	$("#menu_icon").on("click", function(){


	});
	*/
	/*---------------------------------------------------*/
	/*------------------------------------------------*/
	//$.mmenuSetting();
	/*
	var api = $("#menu").data("mmenu");
	api.bind("openPanel", function(){

	});

	mySlide.on('click', function(e){
		api.openPanel($('#mm-1'));
	});
	*/


});

(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide({hideTabImg:"../image/plus.png",
			openTabImg:"../image/close_2.png",closeFunc:true});
	}
	//スライドclose時の処理
	$.slideCloseFunc = function() {
		//テーブルcaptionリセット
		$("table caption").data("mode","add").text("手当を追加する");
		//削除ボタンを非表示
		button_boxies.removeClass("parallel").eq(1).hide();
		//$("input.delete_button").css("display","none");
		//inputのvalueをリセット
		$("#title,#amount,#add_rem_id").val("");
		$("#staff_id option:first").attr("selected",true);
	}
	/*-----------------------------------------------------*/

	//リスト表示
	$.createAddRemList = function() {
		var tempdt = $("#temp_dt");
		var tempdd = $("#temp_dd");

		$.each(_totalAmo,function(index,val){
			//テンプレをクローン
			var dt = tempdt.clone().removeAttr("id").show();
			var dd = tempdd.clone().removeAttr("id").show();
			tempdt.before(dt);
			tempdt.before(dd);

			//スタッフ名セット
			$.each(_staffs,function(){
				if (this.id == val.staff_id) {
					dt.text(this._name);
				}
			});
			//合計額
			dt.append(
					$("<span>").attr("class","right")
					.text("合計："+$.delimiting(val.total)));



			//追加報酬明細
			var templi = dd.find(".temp_li");
			$.each(_addRems,function(){

				if (this.staff_id == val.staff_id) {
					var li = templi.clone().removeClass("temp_li").show()
						.attr("data-add_rem_id",this.add_rem_id);
					li.find(".title").text(this.title);
					li.find(".amount").text($.delimiting(this.amount));

					templi.before(li);
				}
			});

			$.moveAccImgToMiddle();//@common.js
		});
	}


}(jQuery));
