/**
 *
 */
jQuery(function ($) {
	/*----------------------------------------
	 * ２重登録を防ぐため戻るボタンで戻った時にリロード
	--------------------------------------- */
	//mobileSafari
	window.onpageshow = function(event) {
		if (event.persisted) {
			window.location.reload();
		}
	};
	//その他
	window.onunload = function(){};
	/*-------------------------------------------*/


	/*==日付,客名,担当者エリア=========================*/
	//日付
	$("#date").text(_dateStr);
	//客名
	var cosName = "";
	if (_receipt.costomer) {
		cosName = _receipt.costomer;
	}
	$("#costomer").text(cosName+" 様");
	//担当者
	$("#staff").text("担当："+_receipt.staff_icon);
	/*=========================================*/

	/*==伝票ステータスエリア============*/
	//伝票ステータスイメージをセット
	$.setReceiptStatusImages({floor:1});//@my_receipt_status.js

	var recStatus = $("#receipt_status");
	var imgContents = recStatus.children().eq(0);
	var textContents = recStatus.children().eq(1);

	//ステータスに応じて内容をセット
	if (!_receipt.come || _receipt.come == 0) {//来店なし
		imgContents.append($.getReceiptStatusObject(0));
		textContents.text("来店されていません");

	}else if (_receipt.rec_comp == 0) {//未会計
		imgContents.append($.getReceiptStatusObject(1));
		textContents.text("来店済み");

	}else if (_receipt.rem_comp == 0) {//会計済み
		imgContents.append($.getReceiptStatusObject(2));
		textContents.text("会計済み");

	}else {//来店処理済み
		imgContents.append($.getReceiptStatusObject(3));
		textContents.text("来店処理済み");

	}
	/*===============================*/

	/*==メニューアイコンリストエリア======================*/
	//MenuViewControllerセットにて実装
	/*=============================================*/

	/*==商品エリア=================================*/
	var proTable = $("#selected_products");
	//商品データを設定
	//テンプレtr
	var tempTr = proTable.find("tr.temp_pro");
	//商品追加ボタン
	var addPro = $("tr#add_pro");
	//+ボタンクリック
	addPro.find("img").on("click",function(){

		if (_addProFlag == "NO") {//有効商品がない場合
			alert("商品アイテムが登録されていません。サロン管理者に商品を登録してもらいましょう。");
		}else {
			if (addPro.addCheck()) {
				$.addNewTr(addPro);
			}
		}
	});
	//登録済みの商品行を追加
	$.each(_usdProducts,function(index,val){

		var tr = $.addNewTr(addPro);


		//値をセット
		tr.prop("title",val.id);
		tr.find("input").val(val.sales);
		tr.find("select.pro_num").val(val.num);
		tr.find("select.pro_sel").val(val.product_id);
	});
	/*=============================================*/

	/*==来店回数===============================*/
	//来店回数にoptionを追加 .addvisitOptions():@common.js
	$("#num_visit").addvisitOptions().val(_receipt.num_visit);
	/*===================================================*/

	/*==支払タイプ===========================================*/
	if (_masterSubFlag == "YES" || _staffEditFlag == "YES") {

		var payTypeContents = $(".pay_type");
		//表示
		payTypeContents.show();
		//チェっっく
		payTypeContents.find("input[value="+ _receipt.pay_type +"]").prop("checked",true);

		/*if (_receipt.pay_type == 1) {
			$("#pay_type p").toggle();
		}*/

		/*//スタッフログイン時は変更不可に
		if (_staffEditFlag == "YES") {
			payTypeContents.find("input").prop("disabled",true);
		}*/
	}

	/*===================================================*/

	/*==有効伝票項目エリア=======================================*/
	var recEntBox = $("#rec_entries").addClass($.getRecEntriesClassName());

	$.changeVivoEntryName();

	$.each(_recEnts,function(index,val){

		var entName = val._name;
		var div = $("<div>").attr("class","seg_contents rec_ent");

		//getEntryInput():@common.js
		var input = $.getEntryInput(val);

		div.append(input).appendTo(recEntBox);
		/*--項目別にアレンジ-----------*/
		if (entName == "point") {
			div.find("input").addClass("narrow02")
				.css({"font-size":14,"height":18}).after(" P")
				.setTextStrCheck();//setTextStrCheck():@string_check.js
		}else if (entName == "free") {
			div.find("input:first").attr("id","f1").after('<label for="f1">指 </label>');
			div.find("input:eq(1)").attr("id","f2").after('<label for="f2">F</label>');
		}else{
			div.find("input").after('<label for="'+ val._name +'">'+ val.local_name +'</label>');
		}
		/*--------------------------*/
	});
	//選択済み,値設定
	if (_receipt.net_id) { $("#net").prop("checked",true); }
	if (_receipt.other_net_id) { $("#other_net").prop("checked",true); }
	if (_receipt.net_id) { $("#net").prop("checked",true); }
	if (_receipt.point_id) { $("#point").val(_receipt.point_v); }
	if (_receipt.free_id) {
		$("input[name=free][value="+_receipt.free_v+"]").prop("checked",true);
	}
	if (_receipt.student_id) { $("#student").prop("checked",true); }
	/*========================================================*/

	/*==合計エリア============================*/
	/*--金額反映ボタン-------------*/
	$(".copy_sales_btn").on("click",function(e){

		//_masterSubFlagがYESの時は確認コンファーム表示
		if (_masterSubFlag == "YES") {
			if (!confirm("合計金額が変更される可能性があります" +
				"\n合計金額を反映してよろしいですか？")) {
				return false;
			}
		}


		var totalSaleBox;//合計表示対象
		var inputs;//合計対象範囲
		var disc;//対象割引率
		var key;//レコード更新対象カラム名

		if ($(".copy_sales_btn").index(this) == 0) {//技術
			totalSaleBox = $("#tec_sale");
			inputs = $(".menu_salebox input:visible");
			disc = $("#tec_disc").val() / 100;
			key = "tec_sale";
		}else {//商品
			totalSaleBox = $("#pro_sale");
			inputs = $("#selected_products input[type=text]");
			disc = $("#pro_disc").val() / 100;
			key = "pro_sale";
		}

		var total = 0;//各メニュー合計金額
		inputs.each(function(index,val){
			var amo = 0;
			if (val.value) { amo = parseInt(val.value); }
			total = total + amo;
		});

		var sale = parseInt(total - total*disc);

		if (sale > 0) { totalSaleBox.val(sale); }
	});
	/*--------------------------------*/
	//合計をセット
	$("#tec_sale").val(_receipt.tec_sale);
	$("#pro_sale").val(_receipt.pro_sale);
	//割引セレクタをセット
	$("#tec_disc").val(_receipt.tec_disc);
	$("#pro_disc").val(_receipt.pro_disc);
	/*======================================*/

	//メモをセット
	$("#memo").text(_receipt.memo);

	//*==フラグに対応================================*/




	//console.log("regiSub="+_regiSubFlag)


	//お会計許可
	if (_regiSubFlag == "NO") {//会計ボタン削除
		$("#register_sub").remove();
		//$("#register_buttons").remove();
	}else {
		$("#register_sub").on("click",function(){
			if ($.recSubCheck()) { $.sendAllData("register_sub"); }
		});
		/*$(".register_sub").on("click",function(){
			if ($.recSubCheck()) { $.sendAllData($(this).attr("id")); }
		});*/
	}
	//仮登録許可
	if (_preSubFlag == "YES") {
		$("#pre_sub").show().on("click",function(){
			if ($.recSubCheck()) { $.sendAllData("pre_sub"); }
		});
	}
	//合計金額編集許可
	if (_totalAmountFlag == "NO") {//合計金額disabled
		$("#tec_sale, #pro_sale, .copy_sales_btn").attr("disabled",true);
		//@common.js
		$.showStateGuidePopup("会計済み：合計金額は編集できません","slim");
	}
	//スタッフ編集許可
	if (_staffEditFlag == "YES") {
		$("#staff_edit_sub").show().on("click",function(){
			if ($.recSubCheck()) { $.sendAllData("staff_edit_sub"); }
		});
	}
	//技術歩合入力許可
	if (_tecRemFlag == "YES") {
		$.setRemSel("tec");
		$("#tec_rem_selecter_message").show();
	}
	//商品歩合入力許可
	if (_proRemFlag == "YES") {
		$.setRemSel("pro");
		$("#pro_rem_selecter_message").show();
	}
	//固定技術歩合率表示許可
	if (_staffPerFlag == "YES") {
		$("#staff_per").show()
			.text(_receipt.staff_percentage +"%");
		$("#staff_per_message").show();
	}
	//管理者登録許可
	if (_masterSubFlag == "YES") {
		$("#master_sub").show().on("click",function(){
			if ($.recSubCheck()) { $.sendAllData("master_sub"); }
		});
		$("#master_delete").show().on("click",function(){
			if (confirm("！！！！！注意！！！！！" +
					"\n！！！伝票を削除しようとしています！！！" +
					"\nこの伝票を削除してよろしいですか？")) {
				$.deleteReceipt();
			}
		});
	}else if (_masterSubFlag == "STAFF_APPROVAL") {
		//@common.js
		$.showStateGuidePopup("スタッフが報酬を承認済み：編集できません","slim");
	}
	//戻るリンク
	if (_backResFlag == "NO") { $("#back_link_reserve").remove();}
	if (_backRecListFlag == "YES") { $("#back_link_reclist").show();}
	/*===========================================*/








	/*==OROの場合は計算ガイドを表示===================*/
	if (_salonId == 1 || _salonId == 3 || _salonId == 4 || _salonId == 16) {
		if (_visiter == "salon" && _receipt.rec_comp == 1) {
			var guidebox = $("<div>").attr("class","guide_box");


			var carcPossible = true;//計算可能フラグ
			var str = null;
			var total = 0;//定価合計

			//選択済みのメニュー詳細配列
			var mds = $("#selected_menus tr:visible input[type=radio]:checked");



			//全てのメニューに詳細が設定されていたら計算
			if (_receipt.menus.length == mds.length) {
				//定価計算
				$.each(mds,function(index,val){
					var price = parseInt($(this).data("mdData").price);

					if (price) {
						if (index == 0) {
							str = "定価:"
						}else {
							str = str + "+";
						}
						str = str + price;
						total = total + price;
					}
				});
			}

			if (str) {
				str = str + "=" + total;
				//割引ガイド文字列を追加
				str = str + "<br>" +
					"10%off:"+Math.round(total*0.9)+" / "+
					"20%off:"+Math.round(total*0.8)+" / "+
					"30%off:"+Math.round(total*0.7)+" / "+
					"40%off:"+Math.round(total*0.6)+" / "+
					"50%off:"+Math.round(total*0.5);
			}else {
				str = "計算でギネッスー";
			}


			$("#tec").after(guidebox.append(str));
		}
	}
	/*=============================================*/
});

(function($) {

	/*==選択済み商品テーブルを操作するメソッド=====================*/
	//追加許可メソッド
	$.fn.addCheck = function() {
		var prevTr = $(this).prev();//追加ボタンtrの兄tr

		//追加許可フラグ
		var auth = true;
		if (!prevTr.hasClass("temp_pro")) {
			//商品が選択されていなければ拒否
			if (prevTr.find("select").val() == 0) {
				alert("最後に追加された商品が選択されていません");
				auth = false;
			}
		}
		return auth;
	}
	//商品行追加
	$.addNewTr = function(addpro) {
		var tr = $("tr.temp_pro").clone().toggle().removeClass("temp_pro");
		var pronum = tr.find("select.pro_num");
		var input = tr.find("input");
		var sel = tr.find("select.pro_sel");

		/*テキストボックスに文字チェック&onchengeを設定
		 * setTextStrCheck():@string_check.js
		 */
		input.setTextStrCheck().on("change",input.chengeText);


		//selectにoptionを追加&onchangeセット
		$.each(_products,function(index,val){
			if (!val.hide) {//隠し設定されていないものを表示
				sel.append(
						$("<option>").val(val.id)
							.text(val._name).data("price",val.price));
			}

		});
		sel.on("click",function(){
			$(this).data("preVal",$(this).val());
		}).on("change",function(){
			if ($(this).val() != 0) {//０以外
				$(this).selectProduct();
			}else {//0を選択した場合はキャンセル
				alert("未選択にはできません");
				$(this).val($(this).data("preVal"));
			}
		});

		//個数セレクタにonchangeをセット
		pronum.on("change",pronum.selectProNum);

		//削除アイコンをセット
		tr.find("td.del_icon").append($.delIcon(tr,{size:20}));

		addpro.before(tr);

		return tr;
	}
	//商品セレクタ選択
	$.fn.selectProduct = function() {
		var tr = $(this).parents("tr");
		var input = tr.find("input");
		var selectedOption = tr.find(".pro_sel option:selected");
		//個数セレクタをリセット
		tr.find("select.pro_num option:first").prop("selected",true);
		//金額inputに金額を表示
		input.val(selectedOption.data("price"));

	}
	//個数セレクタ選択
	$.fn.selectProNum = function() {

		//商品が選択済みの場合のみ処理を実行
		if ($(this).parent().prev().find("select").val() != 0) {
			var tr = $(this).parents("tr");
			//金額を計算し反映
			var price = tr.find("select.pro_sel option:selected").data("price");

			var input = tr.find("input")
			input.val(parseInt(price) * parseInt($(this).val()));
		}
	}
	/*========================================================*/

	//伝票項目エリアのクラス名を返す
	$.getRecEntriesClassName = function() {
		switch (_recEnts.length) {
		case 1:
			return null;
			break;
		case 2:
			return "segment02 ";
			break;
		case 3:
			return "segment03 ";
			break;
		case 4:
			return "segment04 ";
			break;

		default:
			return "segment03 ";
			break;
		}
	}

	/*==歩合セレクタの初期設定======================================*/
	$.setRemSel = function(type) {
		var sel = $("#"+type+"_rem");
		var selectedV = null;

		var array;
		if (type == "tec") { array = _tecPP; }else { array = _proPP; }

		$.each(array,function(index,val){
			//セレクタにoptionを追加
			sel.append($("<option>").val(val.percentage).text(val.percentage+"%"));

			//選択指定の場合は設定
			if (val.selected == 1) { selectedV = val.percentage; }
		});

		if (_receipt[type+"_rem_v"]) {//歩合決定済みの場合は選択
			sel.val(_receipt[type+"_rem_v"]);
		}else {//未決定の場合、選択指定があれば選択
			if (selectedV) { sel.val(selectedV); }
		}
		sel.show();
	}
	/*===================================================*/


	//サブミットチェック
	$.recSubCheck = function() {

		var selectedTr = $("#selected_menus tr:visible");
		var proSel = $(".pro_sel:visible");

		//選択されていない商品がある場合はfalse
		var proAuth = true;
		if (proSel.length > 0) {
			$.each(proSel,function(index,val){
				if ($(this).val() == 0) {
					proAuth = false;
				}
			});
		}
		if (!proAuth) {
			alert("選択されていない商品があります");
			return false;
		}

		//会計時と管理者登録時

		if (_regiSubFlag == "YES" || _masterSubFlag == "YES") {
			if (selectedTr.length > 0 && $("#tec_sale").val() == 0) {
				alert("技術売上が入力されていません");
				return false;
			}
			if (proSel.length > 0 && $("#pro_sale").val() == 0) {
				alert("商品売上が入力されていません");
				return false;
			}
		}

		//管理者登録時
		if (_masterSubFlag == "YES") {
			if (window.confirm("ここで変更した内容は全て更新されます。\n来店処理をしてよろしいですか？")) {
				return true;
			}else {
				return false;
			}
		}


		//お会計時
		if (_regiSubFlag == "YES") {
			//技術、商品ともに０円ならfalse
			if ($("#tec_sale").val() == 0 && $("#pro_sale").val() == 0) {
				alert("売上がありません");
				return false;
			}

			/*---------最終確認------------------*/
			if (window.confirm("この内容でお会計をしてよろしいですか？")) {
				return true;
			}else {
				return false;
			}
		}

		return true;
	}

	/*==データ送信メソッド===========================*/
	//選択済みtrの情報からメニュー詳細データ配列を返す
	$.menuData = function(tr) {

		var m = tr.prop("title");
		var d = tr.find(".detail_cell input:checked").val();
		if (!d) { d = null; }
		var s = tr.find(".menu_salebox input").val();
		if (!s) { s = null; }

		var array = {menu_id:m, detail_id:d, sales:s}

		return array;
	}
	//選択済みtrの情報から商品詳細データ配列を返す
	$.productData = function(tr) {
		var id = tr.prop("title");
		if (!id) { id = null; }
		var p = tr.find(".pro_sel").val();
		var n = tr.find(".pro_num").val();
		var s = tr.find("input[type=text]").val();
		if (!s) { s = null; }

		var array = {id:id, product_id:p, num:n, sales:s}

		return array;
	}
	$.sendAllData = function(mode) {

		$("#" + mode).remove();


		//スタッフ編集時はrem_compが1の場合はキャンセル
		if (mode == "staff_edit_sub") {
			//alert("staff_edit_sub");
			if ($.timelyRemcomp() == "1") {
				alert("すでにサロン管理者が登録済みのため編集できません" +
						"\nサロン管理者に問い合わせてください");
				return false;
			}
		}


		//送信データ
		var data = {rec_id:_receipt.rec_id};

		/*--メニューデータ----------------------*/
		//既存メニューをデータに保存
		data.exist_menus = _receipt.menus;
		data.selected_menus = receiptMenus;
		/*-------------------------------*/

		/*--商品データ----------------------------*/
		//既存商品をデータに保存
		data.exist_products = _usdProducts;

		//選択されているメニュー情報配列をデータに追加
		var selectedTr = $("#selected_products tr:not(#add_pro,.temp_pro)");

		var selectedPs = [];
		$.each(selectedTr,function(index,val){
			selectedPs[selectedPs.length] = $.productData($(this));
		});
		data.selected_products = selectedPs;
		/*-------------------------------*/

		/*--net,point,free,student,tec_rem,pro_rem------*/
		//各項目の要素が存在していれば値をセット
		var net = $("#net");
		var point = $("#point");
		var free = $("input[name=free]:checked");
		var student = $("#student");
		var tecRem = $("#tec_rem:visible");
		var proRem = $("#pro_rem:visible");
		var other_net = $("#other_net");
		var n = null;
		var p = null;
		var f = null;
		var s = null;
		var trem = null;
		var prem = null;
		var o = null;

		if (net.length > 0 && net.prop("checked")) {
			n = "on";
		}
		if (point.length > 0 && point.val() != "") {
			p = point.val();
		}
		if (free.length > 0) {
			f = free.val();
		}
		if (student.length > 0 && student.prop("checked")) {
			s = "on";
		}
		if (tecRem.length > 0) {
			trem = tecRem.val();
		}
		if (proRem.length > 0) {
			prem = proRem.val();
		}
		if (other_net.length > 0 && other_net.prop("checked")) {
			o = "on";
		}

		data.net = n; data.point = p;
		data.other_net = o;
		data.free = f; data.student = s;
		data.tec_rem = trem; data.pro_rem = prem;
		data.net_id = _receipt.net_id;
		data.other_net_id = _receipt.other_net_id;
		data.point_id = _receipt.point_id;
		data.free_id = _receipt.free_id;
		data.student_id = _receipt.student_id;
		data.tec_rem_id = _receipt.tec_rem_id;
		data.pro_rem_id = _receipt.pro_rem_id;
		data.pay_type = $("div#pay_type").find("input:checked").val();
		/*-------------------------------*/

		/*--receiptsテーブルデータ-----------------------------*/
		//値に変化があるものだけデータ登録
		var array = ["num_visit","tec_disc","tec_sale","pro_disc","pro_sale","memo"];
		$.each(array,function(index,val) {

			if ($("#"+val).val() != _receipt[val]) {
				if (val == "memo") {
					//改行を空文字に変換
					data.memo = $("#memo").val().replace(/\r?\n/g, " ");

				} else {
					data[val] = $("#"+val).val();
				}
			}
		});
		//お会計時
		if (mode == "register_sub") {
			data.out_ = 1;
			data.rec_comp = 1;
		}

		//管理者登録時
		if (mode == "master_sub") {
			data.rem_comp = 1;
		}

		/*-------------------------------*/

		//送信
		$.sendAjax(data,{
			url: '../PHPClass/ReceiptModel.php',
			dataType: "text",
			success: function(res) {

				if (mode == "pre_sub") {
					document.location = "reserve.php";
				}else if (mode == "register_sub") {
					document.location = "rec_display.php?rec_id="+_receipt.rec_id;
				}else {
					document.location = "receipt_list.php";
				}
			}
		});
	}
	//receiptのタイムリーなrem_comp情報を取得する
	$.timelyRemcomp = function() {
		var remComp;
		var data = {mode:"timely_remcomp","rec_id":_receipt.rec_id};

		$.sendAjax(data,{
			async: false,
			url: '../PHPClass/ReceiptModel.php',
			dataType: "text",
			success: function(res) {
				remComp = res;
			}
		});
		return remComp;
	}
	//伝票削除
	$.deleteReceipt = function() {
		//二度押し防御
		$("#master_delete").remove();


		var data = {
				mode:"mester_delete",
				rec_id:_receipt.rec_id
		};
		$.ajax({
			url: '../PHPClass/ReceiptModel.php',
			type: "POST",
			data: data,
			success: function(res) {

				alert("伝票を削除しました");
				document.location = "receipt_list.php";
			}
		})
	}
	/*===========================================*/
}(jQuery));
/*--------------------------------------------------*/
