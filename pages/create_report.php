<?php
require_once '../PHPClass/CreateReportModel.php';
$model = new CreateReportModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/create_report.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/report_table.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>

<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet" 
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->
<!-- my_plugins -->
<link href="../css/slide_vertical.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/slide_vertical.js"></script>
<script type="text/javascript" src="../js/overlay.js"></script>
<!-- ------- -->

<title>月報作成</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _salons = <?php echo json_encode($model->salonInfo)?>;
var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _condition = <?php echo json_encode($_SESSION["cr_repo_conndition"])?>;
var _subjects = <?php echo json_encode($model->usingSubjects)?>;
var _report = <?php echo json_encode($model->report)?>;
var _repId = <?php echo json_encode($model->reportId)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');
var _createComp = <?php echo json_encode($model->createCompFlag)?>;
var _autoCalcSales = JSON.parse('<?php echo json_encode($model->autoCalcSales)?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});
</script>

<style type="text/css">
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
</style>

</head>

<body id="body">
<div id="wrap">
<div id="container">
<script type="text/javascript">
$._createHeader();
$._createNavigation();
</script>

<div id="main_alea">
<div class="contents_area">

<a class="right_link" href="monthly_report.php">月報ページに戻る▷</a>

<p id="message" class="slategray">
<span class="orange"></span>
/
<span class="orange"></span>
の月報を作成中
</p>

<form action="create_report.php" method="post">

<input type="hidden" name="rep_id">

<table class="report_table" style="width: 100%">
<colgroup width="50%"></colgroup>
<colgroup></colgroup>
<tr id="report_title"><th>科目</th><th>金額</th></tr>
</table>

<p class="slategray ta_right" style="font-size: 12px;">
			*値がない場合は"0"を入力してください</p>

<div class="button_box">
<input id="create_repo" type="submit" class="submit_button" value="登録">
</div>

</form>

</div>
</div>


<div class="my_slide">

<h4>サロン・月を切り替える</h4>

<table style="width: 100%;">
	<!-- <caption>サロン・月を切り替える</caption> -->
	<tr>
		<td>サロン</td>
		<td><select id="salon"></select></td>
	</tr>
	<tr>
		<td>月</td>
		<td><select id="month"></select></td>
	</tr>
</table>

<div class="button_box">
<input id="change_condition" type="button" class="submit_button" value="編集をはじめる">
</div>
</div>

</div>
</div>
</body>
</html>