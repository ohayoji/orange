<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/StaffReportModel.php';
require_once __DIR__.'/../PHPClass/CompanySettingModel.php';
//$test = RootModel::_getWhereStr_salonIDs([1, 2, 3]);
//POST処理
if(!empty($_POST["salon"])){
	$_SESSION["sr_condition"]["salon"] = $_POST["salon"];
}
$model = new StaffReportModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/report_table.css?ver=20190428" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>


<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/staff_report.js?ver=20190428"></script>
<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet"
      media="all and (min-width: 1024px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->
<!-- my_plugins -->
<link href="../css/slide_vertical.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/slide_vertical.js"></script>
<script type="text/javascript" src="../js/overlay.js"></script>

<!-- ------- -->
<title>スタッフレポート</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _monthCondition = '<?php echo $_SESSION["sr_month_condition"]?>';
var _staffReport = <?php echo json_encode($model->staffReports)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting({
		navTabName: "staff_report",
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
<script type="text/javascript">
$._createHeader();
$._createNavigation();
</script>

<div id="main_alea">
<!-- <div class="contents_area"> -->
<!--
<form action="staff_report.php" method="post" onchange="submit()" class="contents_area item_box">
<select name="month"></select>
</form>
-->

<!-- <table class="report_table">
<tr><th>スタッフ</th><th>売上</th><th>報酬</th></tr>
</table> -->
<div id="report_list_area">

<table id="name_table" class="report_table">
	<tr><th colspan="2">スタッフ</th></tr>
</table>

<div id="data_table_area">
<table id="data_table" class="report_table">
<tr>
	<th>
    売上
  </th>
  <th>
    <span class="detail">技術</span>
  </th>
  <th>
    <span class="detail">商品</span>
  </th>
	<th class="f_14">基本給</th>
	<th class="f_14">歩合</th>
	<th class="f_14">手当</th>
	<th class="f_14">給与控除</th>
	<th>支給額</th>
	<th class="f_14">支払</th>
</tr>
</table>
</div>

</div>

<div class="contents_area">
<p class="slategray" id="no_message" style="display: none;">スタッフレポートがありません</p>
<p class="slategray f_small ta_right detail">*売上は会計済みのもの</p>
</div>

<form id="search_field" class="my_slide"
	action="staff_report.php" method="post">

<div id="salon_sel_area" class="segment02 clearfix"
	style="display: none; margin-top: 8px;">
	<div class="seg_contents">サロン</div>
	<div class="seg_contents">
	<select name="salon"><!-- <option value="0">全サロン合計</option> --></select>
	</div>
</div>
<div id="month_sel_area" class="segment02 clearfix" style= "margin-top: 8px;">
	<div class="seg_contents">日付</div>
	<div class="seg_contents">
	<select name="month"></select>
	</div>
</div>
<div class="button_box">
	<input type="submit" class="submit_button" value="集計して表示">
</div>
</form>

<!-- </div> -->
</div>

</div>
</div>
</body>
</html>
