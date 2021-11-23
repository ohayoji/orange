<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/DailyReportModel.php';
$model = new DailyReportModel();
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
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/daily_report.js"></script>
<script type="text/javascript" src="../js/plan_manager.js"></script>

<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet" 
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->

<title>日報</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
var _today = '<?php echo $model->_todaySQLStr?>';
/*---------------------------*/
/*--planManagerプロパティ------*/
var _planManager = <?php echo json_encode($model->planManager)?>;
/*---------------------------*/
var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _selectedMonth = '<?php echo $_SESSION["dr_selected_month"]?>';
var _dailyReport = <?php echo json_encode($model->dairyReport)?>;
var _totalReport = <?php echo json_encode($model->totalReport)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');

var _todayReserves = <?php echo json_encode($model->todayReserves)?>;
var _todayRecCompReserves = <?php echo json_encode($model->todayRecCompReserves)?>;
var _todayNotRecCompReserves = <?php echo json_encode($model->todayNotRecCompReserves)?>;

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();

	//プランによるページアクセス制限@plan_manager.js
	$.limitatePageAccess();
});
</script>


<style type="text/css">
#total .num,
#total .sale{
/*border-bottom-color: #fbdba8;*/
}
#total li{

}
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

<div class="item_box">
	<div class="contents_area with_list_area">
		<form action="daily_report.php" method="post" id="dr_form">
			<select id="d_report_month_selecter" name="month"></select>
		</form>
	</div>
</div>

<div class="group_list_area">

<dl>
	<dt>
		今月の合計
	</dt>
	<dd id="total">
		<ul>
			<li class="num total">
				<div class="segment02 f_20 clearfix">
					<div class="seg_contents left slategray">来店人数</div>
					<div class="seg_contents right orange" title="num"></div>
				</div>
			</li>
			<li class="sale total">
				<div class="segment02 f_20 clearfix">
					<div class="seg_contents left slategray">売上</div>
					<div class="seg_contents right orange" title="all"></div>
				</div>
			</li>
			<li class="slategray total">
				<div class="segment02 clearfix">
					<div class="seg_contents right" title="tec_title">技術売上</div>
					<div class="seg_contents right" title="tec"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right" title="pro_title">店販売上</div>
					<div class="seg_contents right" title="pro"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right" title="cash_title">現金</div>
					<div class="seg_contents right" title="cash"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right" title="card_title">カード</div>
					<div class="seg_contents right" title="card"></div>
				</div>
			</li>
		</ul>
	</dd>
	
	<dt>
		日別リスト
	</dt>
	<dd id="daily_report" class="clearfix">
		<ul>
			<!-- リストテンプレ -->
			<li id="temp_li" style="display: none;">
				<div class="date slategray"></div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">来店人数</div>
					<div class="seg_contents right num"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">技術売上</div>
					<div class="seg_contents right tec"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">店販売上</div>
					<div class="seg_contents right pro"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">現金</div>
					<div class="seg_contents right cash"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">カード</div>
					<div class="seg_contents right card"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right">合計売上</div>
					<div class="seg_contents right total orange"></div>
				</div>
			</li>
			<!-- --------- -->
		</ul>
	</dd>
</dl>


</div>

<!-- <div class="contents_area report">

<form action="daily_report.php" method="post" id="dr_form">
<select name="month"></select>
</form>

<div id="total">
<dl class="color_type01">
	<dt class="f_20 slategray">
	来店人数
	<span class="right orange" title="num"></span>
	</dt>
</dl>
<dl class="color_type01">
	
	<dt class="f_20 slategray">
	合計売上
	<span class="right orange" title="all"></span>
	</dt>
	
	<dd class="segment02 clearfix slategray">
		<div class="seg_contents right" title="tec_title">技術売上</div>
		<div class="seg_contents right" title="tec"></div>
		<div class="seg_contents right" title="pro_title">店販売上</div>
		<div class="seg_contents right" title="pro"></div>
		<div class="seg_contents right" title="cash_title">現金</div>
		<div class="seg_contents right" title="cash"></div>
		<div class="seg_contents right" title="card_title">カード</div>
		<div class="seg_contents right" title="card"></div>
	</dd>
	
</dl>
</div>

</div>-->
</div>
</div>
</div>
</body>
</html>