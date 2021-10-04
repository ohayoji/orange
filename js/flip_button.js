/**
 * 登録／削除など、ユーザーアクションに応じて
 * 文字やスタイルが切り替わるボタン
 * 対象の要素はinput[type=button]に限定
 * optionsに渡された配列によりデフォルト設定を変更できる
 */
(function($) {
	$.fn.setflip = function(options) {
		
		var setting = $.extend({
			/*デフォルト設定
			 * optionsがない場合はこの設定になる
			 */
			//表示文字
			faceTex : "削除",
			backTex : "登録",
			//文字色
			faceCol : "white",
			backCol : "white",
			//文字サイズ
			fontSize : 16,
			//背景色
			faceBgCol : "#e74c3c",
			backBgCol : "#379cbf"
		},options);
		
		//dataをセット
		this.attr("data-face_tex",setting.faceTex);
		this.attr("data-face_col",setting.faceCol);
		this.attr("data-face_bgcol",setting.faceBgCol);
		this.attr("data-back_tex",setting.backTex);
		this.css("font-size",setting.fontSize);
		this.attr("data-back_col",setting.backCol);
		this.attr("data-back_bgcol",setting.backBgCol);
		
		//初期スタイルをセット
		this.reset();

		return this;
	}
	//表裏切り換え
	$.fn.flip = function() {
		if (this.data("disp_side") == "back") {
			this.reset();
		}else {
			this.val(this.data("back_tex"));
			this.css({"color":this.data("back_col"),
					"background-color":this.data("back_bgcol")});
			this.data("disp_side","back");
		}
	}
	//ボタン初期化
	$.fn.reset = function() {
		this.val(this.data("face_tex"));
		this.css({"color":this.data("face_col"),
				"background-color":this.data("face_bgcol")});
		this.data("disp_side","face");
		
		return this;
	}
}(jQuery));