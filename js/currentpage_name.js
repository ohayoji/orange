/**
 * 
 */
(function($) {
	$.currentPageName = function() {
		//url取得
		var url = location.href;
		
		
		
		if (url.indexOf('?') != -1) {//パラメータがあれば削除
			var param = url.split("?");
			url = param[0];
		}
	    
		var array = url.split("/");
		var length = array.length;
		
		var page = array[length -1];
		
		var array2 = page.split(".");
		
		return array2[0];
	}
}(jQuery));