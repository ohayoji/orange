/**
 *
 */

//現ページのURL
var url = location.href;

jQuery(function ($) {

});
(function($) {
	//パラメータの値を取得（引数に取得したいパラメータのキーを投げる）
	$.getParamAtKey = function(key) {
		var value = null;

		//urlを取得
		//var url = location.href;

		if (url.indexOf('?') != -1) {//パラメータがセットされている場合

			var array = url.split("?");
			var params = array[1];

			/* paramsの構成
			 * key1=???&key2=???
			 */

			if (params.indexOf('&') != -1) {//パラメタが複数の場合
				var paramArray = params.split("&");
				var len = paramArray.length;
				for (var i = 0; i < len; i++) {
					var param = paramArray[i].split("=");
					if (param[0] == key) {
						value = param[1];
					}
				}
			}else {
				var param = params.split("=");
				if (param[0] == key) {
					value = param[1];
				}
			}
		}
		return value;
	}

	//呼び出し元階層(floor／ルート直下が０、その下が１)に対応する文字列を返す
	$.getClimbFloorPass = function(floor) {
		var climbFloorPass = "";
		for (var i = 0; i < floor; i++) {
			climbFloorPass = climbFloorPass + "../";
		}
		return climbFloorPass;
	}

	/*--ホームページのリンク配列を呼び出し元階層に合わせて返す-----------------
	* 返すパス配列の形式（多次元配列）
	* [
	* 	{
	* 		category:"カテゴリータイトル",
	* 		links:[
	* 				{ title:"タイトル", href:"pass/to/exsample.html"},
	* 				{ title:"タイトル", href:"pass/to/exsample.html"},
	* 			]
	* 	},
	* 	{
	* 		category:"カテゴリータイトル",
	* 		links:[
	* 				{ title:"タイトル", href:"pass/to/exsample.html"},
	* 				{ title:"タイトル", href:"pass/to/exsample.html"},
	* 				{ title:"タイトル", href:"pass/to/exsample.html"},
	* 			]
	* 	},
	* 	....
	* ]
	---------------------------------*/
$.getOrangePassCategories = function(floor) {

		var cfp = $.getClimbFloorPass(floor);

		var orangePassCategories =
			[
			 {
				 category:"ページリンク",
				 links:[
				        { title:"Orangeホーム", href:cfp +"index.php"},
				        { title:"ログイン", href:cfp +"pages/login.php"},
				        //{ title:"料金プランの詳細", href:cfp +"plan_difference.php?plan=free"},
				        { title:"ユーザー登録", href:cfp +"admission.php?admission_button_type=other"},
				        { title:"FAQ", href:cfp +"FAQ/index.html"},
				        // { title:"特定商取引法に基づく表記", href:cfp +"tokutei.php"},
				        /*{ title:"利用規約", href:"#plan"},
				        { title:"プライバシーポリシー", href:"#plan"},*/
				        ]
			 },
			 {
				 category:"ヘルプセンター",
				 links:[
				        { title:"サロン管理者ヘルプ", href:cfp +"help/index.html"},
				        { title:"サロンスタッフヘルプ", href:cfp +"help/index.html?visiter=staff"},
				        ]
			 },
			];

		return orangePassCategories;
	}


}(jQuery));
