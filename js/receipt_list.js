/**
 *
 */
//伝票数
var receiptsLength;


jQuery(function ($) {


	//伝票ステータスイメージをセット
	$.setReceiptStatusImages({imgSize:"middle", floor:1});//@my_receipt_status.js


	/*--来店処理モード通知領域------------*/
	if (_visiter == "salon" && _condition.rem_comp == 0) {
		var ecma = $("#rem_check_mode_area").fadeIn(1000);
		var p = ecma.find("p");
		if (_receipts && _receipts.length > 0) {
			p.text("来店処理をしてください");
		}else {
			p.text("来店処理待ちの伝票はありません");
		}
	}
	/*---------------------------------*/


	/*---------------------------------------
	 * 検索フォーム
	 ---------------------------------------*/
	/*--今日チェックボックス------------*/
	var todayCheckbox = $("input[name=today]");

	todayCheckbox
		.val(_todayStr)
		.on("change",function(e){
			//日付範囲セレクタを操作
			if ($(this).prop("checked") == true) {
				//無効にする
				//$(".start_end select").attr("disabled","disabled");
				//値を0に
				$(".start_end select").val(0);
			}else {
				//有効にする
				//$(".start_end select").removeAttr("disabled");
			}
	});
	/*--------------------------------*/
	/*--月セレクタ----------------*/
	var monSel = $("select.monSel");

	//月セレクタ初期設定
	$.each(_monthDatas,function(index,val){
		monSel.append($("<option>").val(val.value).text(val.text));
	});
	monSel.on("change",function(e){

		//いずれか一方が指定されていれば今日チェックを外す
		$.each(monSel,function(index,val){
			if ($(this).val != 0) {
				todayCheckbox.prop("checked",false);
			}
		});

		$.resetSel($(this));
	});

	/*------------------------------*/
	/*--スタッフセレクタ---------------*/
	var staffSel = $("select[name=staff_id]");
	if (_staffs) {
		//スタッフoptionを追加
		$.each(_staffs,function(index,val){

			//削除済みのスタッフにはカッコをつける
			var name = val._name;
			if (parseInt(val.deleted) == 1) {
				name = "("+ name +")";
			}

			staffSel.append($("<option>").val(val.id).text(name));
		});
	}else {
		//スタッフログイン時にはstaffsが生成されない セレクタを削除
		$(".staff_select").remove();
	}
	/*---------------------------------*/
	/*--来店回数セレクタ、支払方法セレクタ---------------------*/
	$("select[name=num_visit]").addvisitOptions();
	$("select[name=pay_type]").addPaytypeOptions();
	/*---------------------------------------*/
	/*--伝票ステータスセレクタ-----------------------*/
	if (_visiter == "staff") {
		$(".rem_comp_selecter").remove();
	}else {//サロン
		//ラベルに画像と文字をセット
		$("label#for_rem_comp_0").append($.getReceiptStatusObject(2)).append(" 来店処理待ち");
		$("label#for_rem_comp_1").append($.getReceiptStatusObject(3)).append(" 来店処理済み");
	}
	/*----------------------------------------*/
	/*--その他チェックボックス------------------*/
	var salonCstmzRcent = $("#salon_customize_recent");
	$.each(_recEnts,function(index,val){
		//使用していない項目は削除
		if (!val.ur_id) {

			$("#"+val._name).parent().remove();
			return false;
		}

		//サロン専用項目
		if (val.salon_id) {
			salonCstmzRcent.append('<div class="rec_ent"><input type="checkbox" name="'+ val._name +'" id="'+ val._name +'"><label for="'+ val._name +'"> '+ val.local_name +'</label></div>')
		}
	});
	/*---------------------------------------*/

	/*--検索条件がある場合には選択状態にする-------------------*/
	if (_condition) {

		if (!_condition["today"]) {//今日
			//開始日をセット
			var startSel = $("select[name=start_month]");
			startSel.val(_condition["start_month"]);
			$.resetSel(startSel);
			$("select[name=start_date]").val(_condition["start_date"]);
			//終了日をセット
			var endSel = $("select[name=end_month]");
			endSel.val(_condition["end_month"]);
			$.resetSel(endSel);
			$("select[name=end_date]").val(_condition["end_date"]);

			//次の処理のために不要なものを削除
			delete _condition["start_month"];
			delete _condition["end_month"];
			delete _condition["start_date"];
			delete _condition["end_date"];
		}else {
			//今日にチェック
			$("input[name=today]").attr("checked",true);
		}

    //曜日
    for (var i = 0; i <= 6; i++) {
      var name = 'weekday_' + i;
      if (_condition[name]) {
        $('input[name=' + name + ']').prop("checked",true);
        delete _condition[name];
      }
    }

		//net,point
		if (_condition.net) {
			$("#net").prop("checked",true);
			delete _condition.net;
		}
		if (_condition.point) {
			$("#point").prop("checked",true);
			delete _condition.point;
		}
		//other_net
		if(_condition.other_net) {
			$("#other_net").prop("checked",true);
			delete _condition.other_net;
		}

		//伝票ステータス
		if (_condition.rem_comp) {
			$("#rem_comp_"+_condition.rem_comp).prop("checked",true);
			delete _condition.rem_comp;
		}
    //指名／フリー
    if (_condition.free_v) {
      $("#free_v_"+_condition.free_v).prop("checked",true);
      delete _condition.free_v;
    }


		//残りの各要素に値をセット
		$.each(_condition,function(name,val){
			$("*[name="+name+"]").val(val);
		});
	}

	/*---------------------------------------*/

	//サブミットボタン
	$("input.submit_button").on("click",function(e){
		//$.stringCheck():@string_check.js
		if ($.stringCheck()) {

			//日付範囲指定の場合に開始日が指定されていなければキャンセル
			if (todayCheckbox.prop("checked") == false) {

				if ($("#rec_list_start_month_selecter option:selected").index() == 0) {
					alert("日付を期間指定する場合は必ず開始日または開始月を指定してください");
					return false;
				}
			}

			$("#search_field").submit();
		}
	});
	/*---------------------------------------
	 * --------------------------------------
	 ---------------------------------------*/

	/*--slideセットアップ----------------------*/
	/*$(".my_slide").setSlide({hideTabImg:"../image/search.png",
							openTabImg:"../image/close_2.png"});
	//タブの高さ分だけ隙間あける
	$("#main_alea").css("padding-bottom",$(".my_slide_tab").height());*/
	/*-----------------------------------------------------*/

	if (_receipts) {
		receiptsLength = _receipts.length;
		//検索結果情報表示
		$("#numRecords").text(receiptsLength);
		//売上合計表示
		$("#totalSale").text(CURRENCY+$.delimiting(_total));
		//伝票リスト表示
		//var rap = $.rapTime("createListStart",0);

		//tempdd = $("#temp_dd");


		$.createList();
		//$.rapTime("createListStop",rap);
	}


});

(function($) {
	/*--slideセットアップ-------------------------------------*/
	$.fn.slideSetUp = function() {
		$(".my_slide").setSlide({hideTabImg:"../image/search.png",
			openTabImg:"../image/close_2.png"});
	}
	/*-----------------------------------------------------*/
	//日付関係セレクタの調整
	$.resetSel = function(selecter) {
		var selectedIndex = selecter.find("option:selected").index();

		var dateSel = null;
		if (selecter.prop("name") == "start_month") {//開始セレクタ

			var endSel = $("select[name=end_month]");
			//一度disabled解除
			endSel.find("option").removeAttr("disabled");
			//endMonSelのselectedIndexより後のoptionを選択不可に
			endSel.find("option:gt("+selectedIndex+")").attr("disabled","disabled");

			dateSel = $("select[name=start_date]");
		}else {//終了セレクタ
			var startSel = $("select[name=start_month]");
			//一度disabled解除
			startSel.find("option").removeAttr("disabled");
			//endMonSelのselectedIndexより前のoptionを選択不可に
			startSel.find("option:lt("+selectedIndex+")").attr("disabled","disabled");

			dateSel = $("select[name=end_date]");
		}

		//日付セレクタのoptionを削除
		dateSel.find("option:gt(0)").remove();
		//日付セレクタのoptionをセット
		var array = selecter.find("option:selected").val().split("-");
		//addDateOptions():@common.js
		dateSel.addDateOptions({year:array[0], month:array[1]});
	}
	//伝票リスト表示
	$.createList = function() {
    //曜日
    var weekdays = ['日','月','火','水','木','金','土'];
		//テンプレdd
		var tempdd = $("#temp_dd");

		$.each(_receipts,function(index,val){
			var dd = tempdd.clone().removeAttr("id")
						.on("click",function(e){
							var mode = _visiter + "_pickup";
							document.location =
								"receipt.php?mode="+mode+"&rec_id="+val.rec_id;
						}).show();

			/*--項目表示--------------*/
			//日付
			dd.find(".date").text(val.start + ' (' + weekdays[+val.weekday]);

			//顧客名
			var cosName = "";
			if (val.costomer) cosName = val.costomer +"様";

      //指名orフリーなら顧客名の隣にマーク
      if (val.free_v) {
        if (val.free_v == 1) {
          cosName = cosName + '<span class="f">F</span>';
        }else {
          cosName = cosName + '<span class="s">指</span>';
        }
      }

			dd.find(".costomer_name").html(cosName);

			//スタイリスト名
			if (_visiter == "salon") {//管理者ログイン時
				dd.find(".stylist_name").text(val.staff_icon);
			}else {//スタッフログイン時

			}

			//メニューアイコン
			var menudd = dd.find(".menu");
			var len = val.menu_imgs.length;
			for (var i = 0; i < len; i++) {

				menudd.append($("<img>").attr("src","../image/"+val.menu_imgs[i]));
			}
			//商品がある場合は商品アイコンをプラス
			if (val.pro_sale != 0) {
				menudd.append($("<img>").attr("src","../image/bag_.png"));
			}

			//売上
			dd.find(".sale").text(CURRENCY + $.delimiting(parseInt(val.sale)));

			/*--伝票ステータス--------------*/
			var num;
			var str;
			if (val.rem_comp == 0) {//来店処理待ち
				num = 2;
				str = "来店処理されていません";
			}else {//来店処理済み
				num = 3;
				str = "来店処理済み";
			}
			dd.find(".rec_status").append($.getReceiptStatusObject(num)).append(" " +str);
			/*--------------------------*/
			/*-----------------------*/

			tempdd.before(dd);
		});
		$.moveAccImgToMiddle();//@common.js
	}

}(jQuery));
