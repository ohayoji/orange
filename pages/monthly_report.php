<?php
require_once __DIR__.'/../PHPClass/MonthlyReportModel.php';
$model = new MonthlyReportModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/list.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>
<link href="../css/report_table.css" rel="stylesheet" type="text/css"/>
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/monthly_report.js"></script>
<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->
<!-- my_plugins -->
<link href="../css/slide_vertical.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/slide_vertical.js"></script>
<script type="text/javascript" src="../js/overlay.js"></script>
<!-- ------- -->
<!-- Graph -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('visualization', '1', {packages: ['corechart']});</script>

<title>月報</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _yearDatas = <?php echo json_encode($model->yearDatas)?>;
var _viewMode = '<?php echo $_SESSION["mr_condition"]["view_mode"]?>';
var _unitType = '<?php echo $_SESSION["mr_condition"]["unit_type"]?>';
var _start = '<?php echo $_SESSION["mr_condition"]["start"]?>';
var _end = '<?php echo $_SESSION["mr_condition"]["end"]?>';
var _salon = '<?php echo $_SESSION["mr_condition"]["salon"]?>';
var _usingSub = <?php echo json_encode($model->usingSubjects)?>;
var _report = <?php echo json_encode($model->monthlyReport)?>;
var _salonInfo = <?php echo json_encode($model->salonInfo)?>;
var _usedSub = <?php echo json_encode($_SESSION["mr_condition_usedSub"])?>;
var _usedSub_localName = <?php echo json_encode($_SESSION["mr_condition_usedSub_localName"])?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');


jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting({
		navTabName: "monthly_report",
		extensions: ["theme-dark", "effect-slide-menu"]
	});
});

</script>

<style type="text/css">
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
</style>

</head>
<body>

<div id="wrap">
<div id="container">

<form action="monthly_report.php" method="post" id="header_icons">
	<!--graphアイコン----------->
	<input type="image" src="../image/Bar Chart-64.png"
		class="header_icon graph_img off" name="view_mode" value="graph">
	<input type="image" src="../image/Bar Chart-64-2.png"
		class="header_icon graph_img on" name="view_mode" value="graph" style="display: none;">
	<!--tableアイコン----------->
	<input type="image" src="../image/Insert Table-64.png"
		class="header_icon table_img off" name="view_mode" value="table">
	<input type="image" src="../image/Insert Table-64-2.png"
		class="header_icon table_img on" name="view_mode" value="table" style="display: none;">
	<!-- listアイコン -->
	<input type="image" src="../image/List Filled-64.png"
		class="header_icon list_img off" name="view_mode" value="list">
	<input type="image" src="../image/List Filled-64-2.png"
		class="header_icon list_img on" name="view_mode" value="list" style="display: none;">
</form>

<script type="text/javascript">
$._createHeader();
$._createNavigation();
</script>



<div id="main_alea">

<div class="contents_area">
<a class="right_link" href="create_report.php">月報を作成する▷</a>
<!-- <p class="right_link" id="switch">表示切り替え（専用imgを用意して頂く）</p> -->


<p id="message" class="slategray">
<span class="orange"></span>
/
<span class="orange"></span>
~
<span class="orange"></span>
</p>
</div>

<!-- テーブルエリア -->
<div id="report_list_area" style="display: none">

<!-- 科目タイトルテーブル -->
<table id="title_table" class="report_table">
	<tr><th>科目</th></tr>
</table>

<div id="data_table_area">
<div id="data_table_box"></div>
</div>

</div>
<!-- ---------------------------- -->

<!-- グラフエリア -->
<div id="report_graph_area" style="display: none; min-height:450px; min-width=:320px; margin:0 auto;">graphをここに挿入</div>
<!-- </div> -->
<!-- ------------------ -->

<!-- リストエリア -->
<div id="report_list_list_area" class="hide">
	<div class="list_area">
		<dl>
			<!-- テンプレ -->
			<dd id="list_temp_dd" class="hide">
				<p class="slategray"></p>
				<div class="segment02 clearfix">
					<div class="seg_contents right">技術売上</div>
					<div class="seg_contents tec right"></div>
					<div class="seg_contents right">商品売上</div>
					<div class="seg_contents pro right"></div>
					<div class="seg_contents right f_18">合計</div>
					<div class="seg_contents total orange right f_18"></div>
				</div>
			</dd>
			<!-- ------- -->
		</dl>
	</div>
</div>
<!-- ------------ -->

<form id="search_field" class="my_slide"
	action="monthly_report.php" method="post">
	
<div id="salon_sel_area" class="segment02 clearfix"
	style="display: none; margin-top: 8px;">
	<div class="seg_contents">サロン</div>
	<div class="seg_contents">
	<select name="salon"><option value="0">全サロン合計</option></select>
	</div>
</div>

<div>
<!-- <p class="ta_center">表示する期間</p> -->
<h4>表示する期間</h4>

<dl>
	<dt title="monthly">
		<input type="radio" name="unit_type" id="monthly" value="monthly">
		<label for="monthly"> 月次データ</label>
	</dt>
	<dd>
		<select name="end" class="monSel"></select> から過去１２ヶ月間
　		<input type="hidden" name="start">
	</dd>
	
	<dt title="quarter_total">
		<input type="radio" name="unit_type" id="quarter_total" value="quarter_total">
		<label for="quarter_total"> 四半期合計<span class="detail">　*最大3年間</span></label>
	</dt>
	<dd>
		<select name="start" class="monSel"></select>
		〜
		<select name="end" class="monSel"></select>
	</dd>
	
	<dt title="quarter_ave">
		<input type="radio" name="unit_type" id="quarter_ave" value="quarter_ave">
		<label for="quarter_ave"> 四半期平均<span class="detail">　*最大3年間</span></label>
	</dt>
	<dd>
		<select name="start" class="monSel"></select>
　		〜
		<select name="end" class="monSel"></select>
	</dd>
	
	<dt title="year">
		<input type="radio" name="unit_type" id="year" value="year">
		<label for="year"> 年合計<span class="detail">　*最大12年間</span></label>
	</dt>
	<dd>
		<select name="start" class="yearSel"></select>
　		〜
		<select name="end" class="yearSel"></select>
	</dd>
</dl>

</div>

<div id="subject_sel_area" style="display: none;">
<h4>グラフに表示する勘定科目</h4>
<div class="clearfix">
	<!-- <p class="ta_center">グラフ表示勘定科目</p> -->
	<div id="subject_sel_contents"></div>
</div>
</div>

<div class="button_box">
	<input type="submit" class="submit_button" value="集計して表示">
</div>

</form>
</div>
</div>
</div>
</body>
</html>