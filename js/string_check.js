/*
 * 文字列チェックを管理するファイル
 * 主に input[type=text],textarea 要素に作用
 * !!!!!!!!!!!!!!!!!!!!!!!
 * onchangeメソッドを使っているので
 * 必ずページのコントローラファイルより先に読み込む必要あり
 * !!!!!!!!!!!!!!!!!!!!!!!
 */
(function($) {
	/**
	 * 文字列チェックメソッド
	 * 設定されたクラス名ごとにvalueの文字列をチェックしていく
	 * クラス名
	 * not_null:入力必須
	 * not_unique_char:特殊文字禁止
	 * only_num:半角数値以外禁止（負の整数も禁止）
	 * only_int:負の整数を含む整数以外禁止
	 * not_0_start:数字の0始まりを禁止
	 */
$.stringCheck = function () {
	//チェック許可フラグ
	var authFlag = true;
	
	//未入力チェック
	$.each($(".not_null"),function(index,obj){
		if (obj.value == "") {
			alert("必須項目は入力してください");
			authFlag = false;
			return false;
		}
	});
	//不正文字チェック
	$.each($(".not_unique_char"),function(index,obj){
		if (!checkUniqueChars(obj.value)) {
			alert("不適切な文字が含まれています。"+
			"次の文字は使用できません「\ \ / : * ? \" ' < > |」");
			authFlag = false;
			return false;
		}
	});
	//半角数値チェック
	$.each($(".only_num"),function(index,obj){
		if (checkNotNumber(obj.value)) {
			alert("数値入力欄は半角数字で入力してください");
			authFlag = false;
			return false;
		}
	});
	//整数チェック(負の整数も可)
	$.each($(".only_int"),function(index,obj){
		if (!isFinite(obj.value)) {
			alert("数値入力欄は半角数字で入力してください");
			authFlag = false;
			return false;
		}
	});
	//数字の0始まりチェック
	$.each($(".not_0_start"),function(index,obj){
		if (obj.value.substring(0, 1) == "0") {
			alert("最初の文字に「0」は使用できません");
			authFlag = false;
			return false;
		}
	});
	
	return authFlag;
}
//オブジェクトメソッド
$.fn.strCheck = function () {
	//チェック許可フラグ
	var authFlag = true;
	
	//未入力チェック
	if ($(this).hasClass("not_null")) {
		if ($(this).val() == "") {
			alert("必須項目は入力してください");
			authFlag = false;
		}
	}
	
	//不正文字チェック
	if ($(this).hasClass("not_unique_char")) {
		if (!checkUniqueChars($(this).val())) {
			alert("不適切な文字が含まれています。"+
			"次の文字は使用できません「\ \ / : * ? \" ' < > |」");
			authFlag = false;
		}
	}
	
	//半角数値チェック
	if ($(this).hasClass("only_num")) {
		if (checkNotNumber($(this).val())) {
			alert("数値入力欄は半角数字で入力してください");
			authFlag = false;
		}
	}
	
	//数字の0始まりチェック
	if ($(this).hasClass("not_0_start")) {
		if ($(this).val().substring(0, 1) == "0") {
			alert("最初の文字に「0」は使用できません");
			authFlag = false;
		}
	}
	
	return authFlag;
}

/*--------------------------*/
//文字列があるかどうかチェック
function checkStringExistence(str) {
	if (str == "") {
		return false;
	}else {
		return true;
	}
}
//特殊文字チェック
function checkUniqueChars(str) {
	var uniqueChars = /[\\\/\:\*\?\"\<\>\|\']/gi;
	if (str.match(uniqueChars)) {
		return false;
	}
	return true;
}



//数値かどうかチェック
/*これだと負の整数でfalseになるので
 * その場合はisFinite関数を使うと良い
 */
function checkNotNumber(str) {
	/* 数値以外の文字列が含まれていた場合 */
    if(str.match(/[^0-9]/g)){
    		return true;
    }
    return false;
}
//valueを半角英数に変換する
$.fn.chkCode = function() {
	//$(this)でもthisでもどちらでも良かった（実験した）
	var text = this.val();
	//置き換え
	var newTex = text.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){
        return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
        });
	this.val(newTex);
}


/*テキストボックスにonclick,onchangeを設定
 * onclickで既存のvalueをデータに保存
 * onchangeでstrCheck()→falseの場合は変更前に戻す
 */
//読み込み時に全てのテキストボックスに設定
$.setTextStrCheck = function() {
	$("input[type=text], textarea").on("click",function(){
		$(this).data("prestr",$(this).val());
	}).on("change",function(){
		//chkcodeクラスにはchkCode()を実行
		if ($(this).hasClass("chkcode")) { $(this).chkCode(); }
		
		if (!$(this).strCheck()) {
			$(this).val($(this).data("prestr"));
		}
	});
}
//動的に生成されるテキストボックスに設定(要素生成時に設定する必要あり)
$.fn.setTextStrCheck = function() {
	$(this).on("click",function() {
		$(this).data("prestr",$(this).val());
	}).on("change",function(){
		//chkcodeクラスにはchkCode()を実行
		if ($(this).hasClass("chkcode")) { $(this).chkCode(); }
		
		if (!$(this).strCheck()) {
			$(this).val($(this).data("prestr"));
		}
	});
	return $(this);
}
/*--------------------------*/
}(jQuery));

jQuery(function ($) {
	//var rapTime = $.rapTime("strCheck_start");
	//テキストボックスに文字チェックメソッドをセット
	$.setTextStrCheck();
	
	//rapTime = $.rapTime("strcheck_end",rapTime);
});