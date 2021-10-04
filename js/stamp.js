/**
 * 画像スタンプに関するファイル
 */
jQuery(function ($) {
	//var rapTime = $.rapTime("stamp_start");
	
	
	/*--スタンプ画像パス-----------*/
	//ポストイットイメージ
	var postitImg = "../image/postit.png";
	/*--------------------------*/
	
	/*
	 * postitStamp表示きりかえ
	 * 引数：{putStamp:bool,className:string}
	 */
	$.fn.postitStamp = function (options) {
		
		if (options.putStamp == 1) {
			this.append(
				$("<div>").attr("class",options.className)
					.append($("<img>")
							.attr({"class":"stamp","src":postitImg})));
		}else {
			this.find("."+options.className).remove();
		}
	}
	
	/*
	 * stamp設置
	 * 引数：{img:画像パス,className:string}
	 */
	$.fn.putStamp = function(options) {
		$(this).append(
				$("<div>").attr("class",options.className).append(
						$("<img>").attr({"src":options.src,"class":"stamp"})));
		return $(this);
	}
	//rapTime = $.rapTime("stamp_end",rapTime);
});

