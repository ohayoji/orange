//ポップアップ
$.createPopupView = function(options) {
	var popupView = $("#popup_view");
	//既存のビューを非表示にする
	popupView.closePopup();
	
	//子要素をセット
	popupView.attr("class","border_radius_2")
		.css({"width":options.width,/*"height":options.height,*/"display":"block"})
		.append(
			$("<div>").attr({id:"close_box","class":"clearfix"})
				.html(
					$("<input>").attr({id:"close_popup",type:"button"})
						.on("click",function(e){
							//非表示にする
							popupView.closePopup();
							}).val("×")))
		.append(options.contents);
	
	if (options.type == "center") {
		var leftMargin = 0 - (options.width / 2);
		popupView.css({"top":12,"left":"50%","margin-left":leftMargin});
		/*var leftMargin = 0 - (options.width / 2);
		var topMargin = 0 - (options.height / 2);
		popupView.css({"top":"50%","left":"50%",
				"margin-left":leftMargin,"margin-top":topMargin});*/
	}else if (options.type == "left_top") {
		popupView.css({"top":6,"left":6});
	}
}
//クローズ時の処理
$.fn.closePopup = function (options) {
	//子要素を全て削除
	this.empty();
	//非表示にする
	this.css("display","none");
}
