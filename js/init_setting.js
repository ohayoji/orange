jQuery(function ($) {
	
	tempMdMenuDt = $("#temp_md_menu_dt");
	tempMdMenuDd = $("#temp_md_menu_dd");
	
	
	
	//slideの高さの最小値
	//$("#page2").css("height", "400px");
	//page contents
	/*--メニュービューとメニュー詳細項目ビュー-------*/
	$.each(_menus,function(index,val){
		/*--メニュービュー--*/
		var icon = $.createMenuIcon(val,{boxW:50})
			.on("click",function(e){
				//表示されているimgのclassを取得し、data文字列を作成
				var data;
				var state = $(this).find("img:visible").prop("class");
				if (state == "on") {
					data = "mode=menu_on&menu_id="+$(this).prop("title");
				}else {
					data = "mode=menu_off&menu_id="+$(this).prop("title");
				}
				
				$.post("../PHPClass/SalonSettingModel.php",data);
			});
		$("#usg_menu").append(icon);
		//未使用の場合はoff
		if (!val.um_id) { icon.find("img").toggle(); }
		/*--------------------*/
		
		/*--メニュー詳細項目ビュー-(div.right_slide_body内)-*/
		//メニュー詳細dl
		var dl = $("#usg_m_detail dl");
		//使用されているメニューのみ詳細項目リストを表示
		if (val.um_id) {
			//メニュー詳細配列
			var menuDetails = val.menu_datails;
			
			var dt = tempMdMenuDt.clone().removeAttr("id").show()
						.text(val.local_name).appendTo(dl);
			var dd = tempMdMenuDd.clone().removeAttr("id").show();
			dt.after(dd);
			

			//詳細追加ボタン
			var addMd = dd.find(".add_md").data("menu_id",val.menu_id)
				.on("click",function(){
					if ($(this).checkAddMd()) {$(this).addNewMd();}	
				});
			if (menuDetails.length > 0) {
				$.each(menuDetails,function(dIndex,dVal){
					if (dVal.deleted == 0) {
						addMd.addNewMd(dVal);
					}
				});
			}
			
			
			//sortable
			var chara = "md_menu_table" + val.menu_id;
			var table = addMd.prev("table");
			table.attr("id",chara);
			
			$("#" + chara).sortable({
				//移動方向
				axis: "y",
				//キャンセル対象
				//cancel: "td.noGrab",
				handle:"td.sort_cell",
				//擬似tr
				helper: function(e, tr) {
							var $originals = tr.children();
							var $helper = tr.clone().addClass("sort_helper");
							$helper.children().each(function(index) {
								$(this).width($originals.eq(index).width());
							});
							return $helper;
						},
				//並べ替え対象
				items:"tbody tr:not(.newmd):not(.temp_md_tr)",
				//置いた時の処理
				update: function(event, ui){
					//操作対象tr
					var targetTr = table.find("tbody tr:not(.newmd):not(.temp_md_tr)");
					//送信データ
					var dataToSend = {};
					var dataIndexStr = 0;
					
					//商メニュー詳細データを更新 & 送信データに追加
					var trlen = targetTr.length;
					var mdlen = menuDetails.length;
					
					for (var i = 0; i < trlen; i++) {
						//商品id
						var id = targetTr.eq(i).prop("title");
						
						for (var n = 0; n < mdlen; n++) {
							//同じメニュー詳細の_orderが変更されていた場合
							var md = menuDetails[n];
							
							if (id == md.id && i != md._order) {
								//項目書き換え
								md._order = i;
								//送信データに追加
								dataToSend[dataIndexStr] = {"id": id,"_order": i};	
								//インデックス番号を加算
								dataIndexStr++;
							}
						}
					}
					
					/*//trのステータス_orderを書き換え
					for(var i = 0; i < targetTr.length - 1; i++){
						for(var j = 0; j < targetTr.length - 1 - i; j++){
							if(parseInt(targetTr.eq(j).data("status")._order) > 
									parseInt(targetTr.eq(j+1).data("status")._order)){
								var temp = targetTr.eq(j).data("status")._order;
								targetTr.eq(j).data("status")._order = 
									targetTr.eq(j+1).data("status")._order;
								targetTr.eq(j+1).data("status")._order = temp;	
							}
						}
					}
					//送信データ
					var dataToSend = {};
					
					//送信データに情報を追加
					targetTr.each(
						function(index, element){
							dataToSend[index] = {"id": $(element).data("status").id,
												"_order": $(element).data("status")._order};		
						}
					);*/
			
					dataToSend["mode"] = "md_sort";
					
					$.ajax({
						async : true,
						url: '../PHPClass/SalonSettingModel.php',
						type: "POST",
						dataType: "json",
						data: dataToSend
					});
				}		
			});
		}
		/*----------------------*/
	});
	/*------------------------------------------*/
	
	/*--エリアビュー---------------------------------------*/
	$.each(_areas,function(index,val){
		var tr = $("<tr>").data("status",val).createAreaView().setAreaInfo();
		$("#area_set table").append(tr);
		//tr.find("input[title=seats]").prop("disabled", true).css("border","none");
	});
	//エリア追加ボタン
	$("#add_area").on("click",function(){
		
		var tr =  $("<tr>").addClass("trForAddition").createAreaView();
		
		//deleteアイコンを隠しaddアイコンを表示
		tr.find("td:eq(2) img[title=delete]").css("display","none");
		tr.find("td:eq(2) img[title=add]").css("display","block").on("click",function(){
			$(this).decision();
			//tr.find("input[title=seats]").prop("disabled", true);
			
		});
		
		//追加ボタンを無効に
		$(this).toggle();
		//tbodyを末尾に書くとareaが0の時エリア追加できないので削除した
		$("#area_set table").append(tr);
	});
	
	$("#area_sortable tbody").sortable({
		//移動方向
		axis: "y",
		handle:"td.sort_cell",
		//擬似tr
		helper: function(e, tr) {
					var $originals = tr.children();
					var $helper = tr.clone().addClass("sort_helper");
					$helper.children().each(function(index) {
						$(this).width($originals.eq(index).width());
					});
					return $helper;
				},
		//並べ替え対象
		items:"tr:not(.trForAddition)",
		//置いた時の処理
		update: function(event, ui){
			//操作対象tr
			var targetTr = $("table#area_sortable tbody tr").not(".trForAddition");
			
			//trのステータス_orderを書き換え
			for(var i = 0; i < targetTr.length - 1; i++){
				for(var j = 0; j < targetTr.length - 1 - i; j++){
					if(parseInt(targetTr.eq(j).data("status")._order) > 
						parseInt(targetTr.eq(j+1).data("status")._order)){
						var temp = targetTr.eq(j).data("status")._order;
						targetTr.eq(j).data("status")._order = 
							targetTr.eq(j+1).data("status")._order;
						targetTr.eq(j+1).data("status")._order = temp;	
					}
				}
			}
			//送信データ
			var dataToSend = {};
			
			//送信データに情報を追加
			targetTr.each(
				function(index, element){
					dataToSend[index] = {"id": $(element).data("status").id,
										"_order": $(element).data("status")._order};		
				}
			);
			dataToSend["mode"] = "area_sort";
			
			$.ajax({
				async : true,
				url: '../PHPClass/SalonSettingModel.php',
				type: "POST",
				dataType: "json",
				data: dataToSend
			});
		}
				
	});
	/*---------------------------------------------------------*/
	
	/*----スタッフビュー---------------------------------------*/
	
	//レコード作成更新
	/*$('tr.staff_foam input').on("change", function(){
		
		$(this).chkCode();//chkCode():@string_check.js	
		if ($(this).strCheck()){
			if($(this).prop("title") == '_name'){
				
				var dataToSend = {
					mode: 'staff_name',
					id: $(this).data('sid'),
					_name: $(this).val()
				};
				$.ajax({
					async: true,
					url: '../PHPClass/InitSettingModel.php',
					type: 'POST',
					dataType: 'text',
					data: dataToSend
				});
				_nextAuth = true;
			}
			if($(this).prop("title") == 'icon'){
				var dataToSend = {
					mode: 'staff_icon',
					id: $(this).data('sid'),
					icon: $(this).val()
				};
				$.ajax({
					async: true,
					url: '../PHPClass/InitSettingModel.php',
					type: 'POST',
					dataType: 'text',
					data: dataToSend
				});
			}
		}
	});*/
	
	//スタッフ追加ボタン
	/*$("#add_staff").on("click",function(){
		var count = $('tr.staff_foam').length;
		var lastIndex = count - 1;
		var newIndex = count;
		var $oldTr = $('tr.staff_foam:eq('+lastIndex+')');
		
		var newTr =  $oldTr.clone();
		newTr.find('input').val('').data('sid', newIndex + 1).on("change", function(){
			
			$(this).chkCode();//chkCode():@string_check.js	
			if ($(this).strCheck()){
				if($(this).prop("title") == '_name'){
					
					var dataToSend = {
						mode: 'staff_name',
						id: $(this).data('sid'),
						_name: $(this).val()
					};
					$.ajax({
						async: true,
						url: '../PHPClass/InitSettingModel.php',
						type: 'POST',
						dataType: 'text',
						data: dataToSend
					});
					_nextAuth = true;
				}
				if($(this).prop("title") == 'icon'){
					var dataToSend = {
						mode: 'staff_icon',
						id: $(this).data('sid'),
						icon: $(this).val()
					};
					$.ajax({
						async: true,
						url: '../PHPClass/InitSettingModel.php',
						type: 'POST',
						dataType: 'text',
						data: dataToSend
					});
				}
			}
		});
		
		//追加ボタンを無効に
		//$(this).toggle();
		//tbodyを末尾に書くとareaが0の時エリア追加できないので削除した
		//newTr.find('input').data('sid', newIndex + 1);
		$("#staff_table tbody").append(newTr);
		
	});*/
	/*------------------------------------------------*/
	
	//option for bxSlider
	var option = {};
	option = {
			infiniteLoop: false,
			hideControlOnEnd: true,
			nextSelector: '#slider-next',
			prevSelector: '#slider-prev',
			nextText: '次へ →',
			prevText: '← 戻る',
			touchEnabled: false,
			onSlideNext: function($slideElement, oldIndex, newIndex){
				
				//最後のスライドで次へボタンをなくす
				
				//_nextAuthを一旦リセット
				/*if(_nextAuth == true){
					_nextAuth = false;
				}*/
				//次のスライドがmenuかmenu_detailの場合はtrue
				/*if(newIndex == 1 || newIndex == 2){
					_nextAuth = true;
				}*/
				//次がエリア設定でエリア未設定の場合は_nextAuthをfalse
				if (newIndex == 3 && $("#area_sortable tbody tr.exist").length == 0) {
						_nextAuth = false;
				}				
				
				//init_flagの更新
				$.updateInitFlag(newIndex);
				//「次へ」「戻る」表示切り替え
				$.next_prevControll(newIndex);
			},
			onSlidePrev: function($slideElement, oldIndex, newIndex) {
				
				_nextAuth = true;
				//init_flagの更新
				$.updateInitFlag(newIndex);
				//「次へ」「戻る」表示切り替え
				$.next_prevControll(newIndex);
				
			},
			//startSlide: _pageNo,
			startSlide: _initFlag
			
	};
	
	//execute bxlider
	var slider = $('.bxslider').bxSlider(option);
	//スライドの下の丸のリンクを無効化
	$('a.bx-pager-link').on('click', function(){
		return false;
	});
	
	
	
	//detail設定スライドをスクロールできるようする
	setTimeout(function(){
		/*--デザイン修正------------------*/
		
		var viewportH = $("body").height() * 0.82;
		$(".bx-viewport").css({
			"border":"none", "left":0, "height":viewportH,
			"-moz-box-shadow":"0 0 0", "-webkit-box-shadow":"0 0 0", "box-shadow":"0 0 0",
			"-webkit-overflow-scrolling":"touch"
		})
		//$('a.bx-next').css({'width':'90px', 'position':'relative', 'left':'50%', 'margin-left':'-45px'});
		/*--------------------------*/
		
		$("#page2,#page3").css({
			'overflow': 'scroll',"height":viewportH});
	}, 100);
	//$('#page2').on('click', function(){$(this).css('overflow', 'scroll');});
	//$('#page3').on('click', function(){$(this).css('overflow', 'scroll');});
	
	//所定のページでは_nextAuthロード時trueに。
	$(window).load(function(){
		var current = slider.getCurrentSlide();
		
		/*if(current == 0 || current == 1 || current == 2){
			_nextAuth = true;
		}*/
		//エリア設定ページでエリアが未設定の場合は_nextAuthをfalse
		
		if (current == 3 && $("#area_sortable tbody tr.exist").length == 0) {
			_nextAuth = false;
		}
		//「次へ」「戻る」表示切り替え
		$.next_prevControll(current);
		//エリア未設定時には追加ボタンをクリックしておく
		$("#add_area").click();
	});

});

(function($) {
	//「次へ」「戻る」表示切り替え
	$.next_prevControll = function(pageIndex) {
		
		if(pageIndex == _totalSlide - 1){//最終ページ
			$('#slider-next').hide();
		}else if (pageIndex == 0) {//最初のページ
			$("#slider-prev").hide();
		}else {
			$('#slider-next').show();
			$("#slider-prev").show();
		}
	}
	//滞在ページデータ更新
	$.updateInitFlag = function(newIndex){
		_initFlag = newIndex + 1;
		if (_initFlag == _totalSlide){ _initFlag = 0; }
		
		var dataToSend = {
				init_flag: _initFlag,
				mode: 'updateInitFlag'
				};
		$.ajax({
			async: true,
			url: '../PHPClass/InitSettingModel.php',
			type: 'POST',
			dataType: 'text',
			data: dataToSend
		});
	}
	
	/*==メニュー詳細項目のlist作成================================*/
	//メニュー詳細リスト追加許可
	$.fn.checkAddMd = function() {
		var auth = true;
		//新規行が存在していたらキャンセル
		if ($(this).prev("table").find("tr.newmd").length) {
			alert("最後のメニュー詳細項目が保存されていません");
			auth = false;
		}
		return auth;
	}
	//メニュー詳細リスト追加
	$.fn.addNewMd = function(mdData) {
		var self = $(this);
		//テンプレクローンし追加
		var table = $(this).prev("table");
		var tempMdTr = table.find(".temp_md_tr");
		var tbody = table.find("tbody");
		var tr = tempMdTr.clone().removeClass("temp_md_tr").show()/*.data("status",mdData)*/;
		tbody.append(tr);
		
		
		
		//setTextStrCheck():@string_check.js
		var nameInput = tr.find("input._name").setTextStrCheck()
			.on("change",function(){
				var data = {mode:"update", table:"menu_detail_setting",
							id:tr.prop("title"), _name:$(this).val()};
				
				$.sendAjax(data);
			});
		var priceInput = tr.find("input.price").setTextStrCheck()
			.on("change",function(){
				var data = {mode:"update", table:"menu_detail_setting",
							id:tr.prop("title"), price:$(this).val()};
				$.sendAjax(data);
			});
		
		//選択済み切り替えアイコン
		var selectedIcon = tr.find(".selected_icon");
		var selIconImgs = selectedIcon.find("img");
		selectedIcon.on("click",function(){
				//offの時だけ
				if ($(this).find("img.off").css("display") != "none") {
					if (confirm("伝票の初回入力時に「" + nameInput.val()
							+ "」を選択済みにしてよろしいですか？")) {
						//DB更新
						var data = {
								mode:"md_change", id:tr.attr("title"),
								selected:1, menu_id:self.data("menu_id")
						}
						
						$.ajax({
							url: '../PHPClass/SalonSettingModel.php',
							type: "POST",
							data: data,
							success: function() {
								
								$.each(_menus,function(index,val){
									
									if (val.menu_id == self.data("menu_id")) {
										
										$.each(val.menu_datails,function(i,v){
											
											//同メニューの選択済み項目を非選択に
											if (v.selected == 1) {
												//menu_detailsを更新
												v.selected = 0;
												//アイコン切り替え
												tbody.find("tr[title="+ v.id +"] .selected_icon img").toggle();
											}
											
											//自分の
											if (v.id == tr.attr("title")) {
												v.selected = 1;
											}
										});
									}
								});
								
								//自分のアイコン切り替え
								selIconImgs.toggle();
							}
						});
					}
				}
				
			});

		//削除ボタンをセット
		tr.find(".delete_cell").append($.delIcon(tr,{
			size:24,
			beforeFunc:function(){
				return confirm(nameInput.val()+"を削除してもよろしいですか？");
			},
			afterFunc:function(){
				var data = {mode:"update", table:"menu_detail_setting",
						id:tr.prop("title"), deleted:1};
				$.sendAjax(data);
			}
		}).addClass("del_icon"));
		
		
		//値をセット
		if (mdData) {//メニュー詳細データがある時
			
			tr.attr("title",mdData.id);
			nameInput.val(mdData._name);
			priceInput.val(mdData.price);
			if (mdData.selected == 1) {
				selIconImgs.toggle();
			}
			
			
		}else {//追加ボタンで挿入された時
			//新規行クラスを付与
			tr.addClass("newmd");
			//選択済みアイコンを隠す
			selectedIcon.hide();
			//保存アイコンを生成し削除アイコンを隠す
			tr.find(".delete_cell").append(
					$("<img>").attr("src","../image/save_slim.png").css("width",24)
						.addClass("save_icon").on("click",function(){
							//名前、金額がある事を確認
							if (nameInput.val() != "" && priceInput.val() != "") {
			
								//並び順
								var order;
								//該当メニューの詳細項目配列
								var menuDetails;
								for (var i = 0; i < _menus.length; i++) {
									if (_menus[i].menu_id == self.data("menu_id")) {
										menuDetails = _menus[i].menu_datails;
										order = menuDetails.length;
									}
								}
								
								//ステータスを作成
								var status = {
										_name:nameInput.val(), price:priceInput.val(),
										_order:order, menu_id:self.data("menu_id"),
										selected:0
								}
								
								var data = {
										mode:"insert", table:"menu_detail_setting",
										_name:status._name, price:status.price,
										_order:status._order, menu_id:status.menu_id,
										salon_id:_salonId, data_type:"text"
								}
								
								$.sendAjax(data,{success:function(res){
									
									//dl:titleにレコードidをセット
									tr.attr("title",res);
									//ステータスをセット
									status.id = res;
									menuDetails.push(status);
									//tr.data("status",status);
									//アイコンきりかえ
									selectedIcon.show();
									tr.find(".save_icon").remove();
									tr.find(".del_icon").toggle();
									//新規行クラスを削除
									tr.removeClass("newmd");
								}});
							}else {
								alert("項目を入力してください");
							}
						}));
			tr.find(".del_icon").toggle();
		}
	}
	/*================================================*/
	
	/*==エリア=========================================*/
	/*--エリア設定テーブルtrメソッド---------------*/
	$.fn.createAreaView = function() {
		var tr = $(this);
		//削除アイコン $.delIcon @common.js
		var deleteIcon = 
			$.delIcon(tr,{
				size:24,
				beforeFunc:function(){

					if ($("#area_set table").find("tr:gt(0)").length <= 1) {
						alert("エリアを０にする事はできません");
						return false;
					}else {
						
						return tr.deleteArea();
					}
				},
			}).attr("title","delete");
		
		var td1 = $("<td>").append(
					$("<input>").attr({"title":"_name","type":"text"
							,"class":"faint not_null not_unique_char"}));
		var td2 = $("<td>").append(
				$("<input>").attr({"title":"seats","type":"text"
					,"class":"faint narrow01 not_null not_unique_char only_num chkcode"}));
		var td3 = $("<td>").append(deleteIcon)
					.append('<img title="add" alt="" src="../image/save_slim.png" style="display: none; width:24px;">');
		var td4 = $('<td>').addClass("sort_cell").append($('<div>').html("&#9776;"));
		return $(this).append(td1).append(td2).append(td3).append(td4);	
	}
	
	$.fn.setAreaInfo = function() {
		
		var status = $(this).data("status");
		
		$(this).addClass("exist");
		
		//エリア名input
		$(this).find("input[title=_name]").val(status._name);
		
		//席数input
		$(this).find("input[title=seats]").val(status.seats);
		
		//onchange
		$(this).find("input").on("change",function(){
			//全角数値を半角に
			$(this).chkCode();//chkCode():@string_check.js
			
			if ($(this).strCheck()) {//strCheck():@string_check.js
				var data = "mode=area_change&id="+status.id+
					"&"+$(this).prop("title")+"="+$(this).val();
				
				$.post("../PHPClass/SalonSettingModel.php",data);
			}
		});
		
		return $(this);
	}
	
	$.fn.deleteArea = function() {
		var tr = $(this);
		
		var areaId = tr.data("status").id;
		//レコード更新許可フラグ
		var deleteAuth = false;
		//レコード更新完了フラグ
		var deleted = false;
		
		var sendingData = {
				"mode" : "check_last_res",
				"area_id" : areaId
				};
		
		$.ajax({
			async : false,
			url: '../PHPClass/SalonSettingModel.php',
			type: "POST",
			dataType: "json",
			data: sendingData,
			success: function(res){
				/*if (res.last_res_date) {
					if (confirm(res.delete_date+"日以降の予約表でエリアの削除が反映されます。"
							+res.last_res_date+"日まではすでに予約が入っているため" +
							"エリアの削除は反映されません。"+
							"すぐにこのエリアの削除を予約帳に反映させたい場合は、" +
							"今日以降の全ての予約を他のエリアに移動してからやり直してください。")) {
						deleteAuth = true;
					}
				}else {
					if (confirm("エリアを削除してよろしいですか？")) {
						deleteAuth = true;
					}
				}*/
				
				if (confirm("エリアを削除してよろしいですか？")) {
					deleteAuth = true;
				}
				
				if (deleteAuth) {
					var data = "mode=area_del&id="+areaId+
					"&deleted=1&delete_date="+res.delete_date;
					
					$.post("../PHPClass/SalonSettingModel.php",data);
					//tr.remove();
					deleted = true;
				}
			}
		});
		return deleted;
	}
	/*-----------------------------------------*/
	//追加確定ボタンクリック時
	$.fn.decision = function() {
		
		//許可フラグ
		var authFlag = true;
		var tr = $(this).parents("tr");
		//文字チェック
		tr.find("input[type=text]").each(function(index,val) {
			//全角数値を半角に
			$(this).chkCode();//chkCode(),strCheck():@string_check.js
			authFlag = $(this).strCheck();
		});
		
		if (authFlag) {
			/*if (tr.parents("div").prop("id") == "area_set") {		
				//エリア設定の場合
				if (confirm("エリアを追加してよろしいですか？追加する場合は今日から予約帳に反映されます")) {
					////console.log($("table#area_sortable tbody tr"))
					
				}
			}*/
			var maxOrder = 0;
			$("table#area_sortable tbody tr").each(function(index, element){
				if(index < $("table#area_sortable tbody tr").length - 1){
					if(maxOrder < parseInt($(element).data("status")._order)){
						maxOrder = parseInt($(element).data("status")._order);
					}
				}
			});
			
			var orderToInsert = parseInt(maxOrder) + 1;
			var data = "mode=area_add&_name="+tr.find("[title=_name]").val()+
				"&seats="+tr.find("[title=seats]").val()+
				"&start_date="+_today+"&_order="+orderToInsert;
			
			$.post("../PHPClass/SalonSettingModel.php",data,function(res){
				
				alert("エリアを追加しました");
				//アイコンを切り換え
				tr.find("img").toggle();
				//追加アイコンを表示
				$("#add_area").toggle();					
				//返されたデータをセット
				tr.removeClass("trForAddition").data("status",JSON.parse(res)).setAreaInfo();
				//trにクラスを設定
				tr.addClass("exist");
				//nextAuth
				_nextAuth = true;
				
			});
		}
	}
	/*=========================================*/
}(jQuery));