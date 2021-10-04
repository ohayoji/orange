/**
 * 
 */
//imageFolderPass
var ifp = "my_plugin/my_receipt_status/image/";
//画像配列
var allReceiptStatusImages =
	{
		regular:[ifp+"rec-status-0.png",ifp+"rec-status-1.png",ifp+"rec-status-2.png",ifp+"rec-status-3.png"],
		middle:[ifp+"rec-status-0-m.png",ifp+"rec-status-1-m.png",ifp+"rec-status-2-m.png",ifp+"rec-status-3-m.png"],
		small:[ifp+"rec-status-0-s.png",ifp+"rec-status-1-s.png",ifp+"rec-status-2-s.png",ifp+"rec-status-3-s.png"]
	};

//呼び出し元ページで使用する画像配列
var receiptStatusImages;


jQuery(function ($) {
	
});

(function($) {
	//呼び出し元階層(floor／ルート直下が０、その下が１)に対応する文字列を返す
	$.getClimbFloorPass = function(floor) {
		var climbFloorPass = "";
		for (var i = 0; i < floor; i++) {
			climbFloorPass = climbFloorPass + "../";
		}
		return climbFloorPass;
	}
	
	//使用する画像をセット
	$.setReceiptStatusImages = function(options) {
		
		var setting = $.extend({
			imgSize: "regular",
			floor: 0
		},options);
		
		//規定のサイズ以外の文字列が投げられた時は修正
		if (setting.imgSize != "middle" && setting.imgSize != "small") {
			setting.imgSize = "regular";
		}
		receiptStatusImages = allReceiptStatusImages[setting.imgSize];
		
		var cfp = $.getClimbFloorPass(setting.floor);
		$.each(receiptStatusImages,function(index,val){
			receiptStatusImages[index] = cfp + val;
		});
		////console.log("receiptStatusImages",receiptStatusImages);
	}
	//imgオブジェクトを取得
	$.getReceiptStatusObject = function(index) {
		var imgObj = $("<img>").attr({"src":receiptStatusImages[index]});
		return imgObj;
	}
	
}(jQuery));