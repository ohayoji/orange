function chkCode(id) {
	  work='';
	  for (var lp=0;lp<id.value.length;lp++) {
	    unicode=id.value.charCodeAt(lp);
	    if ((0xff0f<unicode) && (unicode<0xff1a)) {
	      work+=String.fromCharCode(unicode-0xfee0);
	    } else if ((0xff20<unicode) && (unicode<0xff3b)) {
	      work+=String.fromCharCode(unicode-0xfee0);
	    } else if ((0xff40<unicode) && (unicode<0xff5b)) {
	      work+=String.fromCharCode(unicode-0xfee0);
	    } else {
	      work+=String.fromCharCode(unicode);
	    }
	  }
	  id.value=work; /* 半角処理のみ */
	  //id.value=work.toUpperCase(); /* 大文字に統一する場合に使用 */
	  //id.value=work.toLowerCase(); /* 小文字に統一する場合に使用 */
}
function searchSpace(string) {
	var bool = null;
	
	var index1 = string.indexOf(" ");//半角スペース
	var index2 = string.indexOf("　");//全角スペース
	
	if (index1 >= 0 || index2 >= 0) {
		bool = true;
	}else {
		bool = false;
	}
	return bool;
}
//特殊文字チェック
function checkUniqueChars(str) {
	var uniqueChars = /[\\\/\:\*\?\"\<\>\|\']/gi;
	if (str.match(uniqueChars)) {
		alert("不適切な文字が含まれています。"+
				"次の文字は使用できません「\ \ / : * ? \" ' < > |」");
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
//文字列があるかどうかチェック
function checkStringExistence(str) {
	if (str == "") {
		return false;
	}else {
		return true;
	}
}

function totalNumber(array) {
	var value = 0;
	for ( var i = 0; i < array.length; i++) {
		value = value + array[i];
	}
	return value;
}

/*function resetSelecter(selecter) {
	selecter.selectedIndex = 0;
}*/

/*---------input-----------*/
//ラジオボタン
function radiosChecked(radios) {
	var checked = false;
	for ( var n = 0; n < radios.length; n++) {
		if (radios[n].checked == true) {
			checked = true;
		}
	}
	return checked;
}
function radiosCheckedIndex(radios) {
	for ( var n = 0; n < radios.length; n++) {
		if (radios[n].checked == true) {
			return n;
		}
	}
	return false;
}
function radiosCheckedValue(radios) {
	for ( var n = 0; n < radios.length; n++) {
		//alert(n+" checked="+radios[n].checked);
		if (radios[n].checked == true) {
			//alert("CheckedIndex="+n);
			return radios[n].value;
		}
	}
	return false;
}


/*--------文字列操作-----------------*/
//分割
function spritString(symbol, string) {
	var array = string.split(symbol);
	//最後のオブジェクトを削除
	array.pop();
	return array;
}
//桁区切りカンマを削除
function deleteComma(string) {
	var deletedStr = string.replace(/,/g,"");
    return deletedStr;
}
/*-----------------------*/

/*------サブウィンドウ-------------------------------------*/
function displaySubWindow(url) {
	window.open(url, "subWindow", "width=350,height=250,scrollbars=yes");
}
function displaySubWindowTall(url) {
	window.open(url, "subWindow", "width=320,height=550,scrollbars=yes");
}
function displaySubWindowRand(url) {
	window.open(url, "subWindow", "width=768,height=180,scrollbars=yes");
}
function displaySubWindowRand2(url) {
	window.open(url, "subWindow", "width=768,height=300,scrollbars=yes");
}

/*-------------------------------------------*/

/*--日付関係--------*/
//year, month, dateから曜日を返す
function createDay(year, month, date) {
	var myD = new Date(year+"/"+month+"/"+date);
	myDay = new Array("日","月","火","水","木","金","土");
	var index = myD.getDay();
	return myDay[index];
}
//月の日数を返す
function getNumOfDays(year,month){
    return new Date(year,month,0).getDate();
};
/*--------------------*/

//カラーリスト
function colors() {
	var backgroundColors = [
	                        "CadetBlue",
	                        "lime",
	                        "green",
	                        "limegreen",
	                        "DarkTurquoise",
	                        "DarkCyan",
	                        "olivedrab",
	                        "DarkSeaGreen",
	                        "CornflowerBlue",
	                        "DodgerBlue",
	                        "RoyalBlue",
	                        "skyblue",
	                        "DarkBlue",
	                        "midnightblue",
	                        "SlateGray",
	                        "Purple",
	                        "mediumpurple",
	                        "mediumorchid",
	                        "darkviolet",
	                        "MediumVioletRed",
	                        "plum",
	                        "Crimson",
	                        "Coral",
	                        "DeepPink",
	                        "DarkSalmon",
	                        "DarkOrange",
	                        "IndianRed ",
	                        "LightCoral",
	                        "OrangeRed ",
	                        "Tomato",
	                        "Red",
	                        "GoldenRod",
	                        "Maroon",
	                        "Sienna",
	                        "Chocolate",
	                        "tan"
	                        ];
	return backgroundColors;
}
function colorPanels() {
	var panels = "";
	for ( var i = 0; i < colors.length; i++) {
		panels = panels+
			'<div id="'+colors[i]+'" class="color_panel"'+
			' style="background-color:'+colors[i]+
			'" onclick="clickColorPanel(this)"></div>';
	}
	return panels;
}







/*-----セキュリティ---------------------------*/
/*パスワードチェック
 * @salon_setting.js
 * @reserv_list.php
 * oldPass:正解のパスワード
 * confMessage:パスワードが一致した場合に表示するconfirmメッセージ
 *	   (null指定あり)
 */
function passwordCheckAndConfirm(oldPass, confMessage) {
	var pass = window.prompt("現在のパスワードを入力してください");
	if (pass != oldPass) {
		alert("パスワードが違います");
		return false;
	}else {
		 if (confMessage) {
			 if (!confirm(confMessage)) {
				 return false;
			 }
	 	 }
	}
	return true;
}
/* PHPセッション変数　$_SESSION["master_security_passing"]
 * に"passing"を指定し、セキュリティロックを解除する
 * jsから直接PHP変数を操作できないため、ajaxを使う
 * @reserv_rist.php
 */ 
function passingMasterSecurity(alertMessage) {
	var postBody = 'mode=master_security&type=passing';
	var request = createXMLHttpRequest();

	set_open_sendRequest(request,"../PHPFiles/ajax.php",
				false,postBody,showAlert(),false);
	
	function showAlert() {
		alert(alertMessage);
	}
}
/* PHPセッション変数　$_SESSION["master_security_passing"]
 * にnullを指定し、セキュリティロックをかける
 * jsから直接PHP変数を操作できないため、ajaxを使う
 * 引数
 * alertMessage：表示したいアラートメッセージ
 * location：ロック後の遷移先URL(null指定あり) 
 * @reserv_rist.php
 */ 
function rockMasterSecurity(alertMessage,location) {
	
	var postBody = 'mode=master_security&type=rock';
	var request = createXMLHttpRequest();

	set_open_sendRequest(request,"../PHPFiles/ajax.php",
				false,postBody,showAlert(),false);
	
	function showAlert() {
		alert(alertMessage);
		if (location) {
			window.location = location;
		}
	}
}
/*----------------------------------------------*/

/*----------header-----------*/
function createHeader(salonName,staffName) {
	document.write('<div id="header">');
	document.write('<div class="segment03 clearfix">');
	document.write('<div class="seg_contents">'+salonName+'</div>');
	if (!staffName) {
		document.write('<div class="seg_contents">'+
						'<span style="font-size: 12px;">'+
						'login：</span>管理者</div>');
	}else {
		document.write('<div class="seg_contents">'+
						'<span style="font-size: 12px;">'+
						'login：</span>'+staffName+'</div>');
	}
	document.write('<div class="seg_contents">');
	document.write('<img alt="" src="../image/logout.png"'+
				'onclick="clickLogout()">');
	document.write('</div>');
	document.write('</div>');
	document.write('</div>');
}

function createHeaderOfHostApp(companyName) {
	document.write('<div id="header" style="background-color: #f39c12;">');
	document.write('<div class="segment03 clearfix">');
	
	document.write('<div class="seg_contents"><a href="#menu" id="menu_icon" style="text-align: left;font-size: 14pt; color: white">　&#9776;</a></div>');
	document.write('<div class="seg_contents">'+companyName+'</div>');
	document.write('<div class="seg_contents">');
	document.write('<img alt="" src="../image/logout.png"'+
				'onclick="clickLogoutForHostApp()">');
	document.write('</div>');
	document.write('</div>');
	document.write('</div>');
}


//logout 
function clickLogout() {
	document.location = "../PHPFiles/logout.php?pattern=reserv";
}

function clickLogoutForHostApp() {
	document.location = "../PHPFiles/logout.php?pattern=company";
}
/*----------------------------------------------*/

/*---------navigation-----------*/
function setNavigationArray(pattern) {
	
	if (pattern == "salon") {
		var top = {
				"title":"予約",
				//"url":"top.php",
				"url":"reserv.php",
		};
		var dailyReport = {
				"title":"日報",
				"url":"daily_report.php",
		};
		var remInput = {
				"title":"伝票",
				"url":"reserv_rist.php",
		};
		var salonSetting = {
				"title":"サロ",
				"url":"salon_setting.php",
		};
		var staffSetteing = {
				"title":"ス設",
				"url":"staff_setting.php",
		};
		var addRem = {
				"title":"追報",
				"url":"add_rem.php",
		};
		var monthlyReport = {
				"title":"月報",
				"url":"monthly_report.php",
		};
		var staffReport = {
				"title":"ス売",
				"url":"staff_report.php",
		};
		var nav = [top,dailyReport,remInput,
		           salonSetting,staffSetteing,addRem,
		           monthlyReport,staffReport];
		return nav;
	}else if (pattern == "staff") {
		var top = {
				"title":"予約",
				//"url":"top.php",
				"url":"reserv.php",
		};
		var recInput = {
				"title":"伝票",
				"url":"reserv_rist.php",
		};
		var remSpec = {
				"title":"報酬明細",
				"url":"rem_specification.php",
		};
		var personalSetiing = {
				"title":"設定",
				"url":"personal_setting.php",
		};
		var nav = [top,recInput,remSpec,personalSetiing];
		return nav;
	}else if (pattern == "company") {
		var top = {
				"title":"サロン",
				"url":"company_top.php",
		};
		var payment = {
				"title":"支払管理",
				//"url":"payment.php?flag=on",
				"url":"payment.php",
		};
		var companySetting = {
				"title":"会社設定",
				"url":"company_setting.php",
		};

		var nav = [top, payment, companySetting];
		return nav;
	}	
}

function createNavigation(pattern, page) {
	document.write('<div id="navigation">');
	document.write('<div class="contents_area">');
	
	document.write('<table id="nav_table" cellspacing="0"><tr>');
	
	/*setNavigationArray()
	 * 引数に訪問者タイプ（master or staff）を渡す
	 */
	var array = setNavigationArray(pattern);
	
	/*作られた配列分のタブ（td）を作成
	 * タイトルとURLを設定する
	 */
	if (array) {
		for ( var i = 0; i < array.length; i++) {
			document.write('<td>');
			document.write('<a href="'+array[i]["url"]+'">'+
					array[i]["title"]+'</a>');
			document.write('</td>');
		}
	}
	
	document.write('</tr></table>');
	document.write('</div>');
	document.write('</div>');
	
	/*選択されているページのタブはデザインを変える
	 * これで選択されている事を明示する
	 */
	var table = document.getElementById("nav_table");
	table.rows[0].cells[page].style.backgroundColor = "white";
	table.rows[0].cells[page].firstChild.style.color = "#e67e22";
}

function createNavigationOfHostApp(pattern, page) {
	document.write('<div id="navigation" style="background-color: #f39c12;">');
	document.write('<div class="contents_area HostApp_header">');
	
	document.write('<table id="nav_table" cellspacing="0"><tr>');
	
	/*setNavigationArray()
	 * 引数に訪問者タイプ（master or staff）を渡す
	 */
	var array = setNavigationArray(pattern);
	
	/*作られた配列分のタブ（td）を作成
	 * タイトルとURLを設定する
	 */
	if (array) {
		for ( var i = 0; i < array.length; i++) {
			document.write('<td>');
			document.write('<a href="'+array[i]["url"]+'">'+
					array[i]["title"]+'</a>');
			document.write('</td>');
		}
	}
	
	document.write('</tr></table>');
	document.write('</div>');
	document.write('</div>');
	
	/*選択されているページのタブはデザインを変える
	 * これで選択されている事を明示する
	 */
	var table = document.getElementById("nav_table");
	table.rows[0].cells[page].style.backgroundColor = "white";
	table.rows[0].cells[page].firstChild.style.color = "#e67e22";
}