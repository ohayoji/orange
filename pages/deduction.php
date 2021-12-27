<?php
if(!isset($_SESSION)){
 session_start();
}
require_once "../PHPClass/DeductionModel.php";
$model = new DeductionModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/deduction.js?ver=20180826"></script>
<script type="text/javascript" src="../js/string_check.js"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/list.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
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

<title>給与控除管理</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _monthCondition = '<?php echo $_SESSION["dd_condition"]["month"]?>';
var _staffs = <?php echo json_encode($model->staffs)?>;
var _deductions = <?php echo json_encode($model->deductions)?>;
var _usedDeductions = <?php echo json_encode($model->usedDeductions)?>;
var _totalDeductions = <?php echo json_encode($model->totalDeductions)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');
var _salonInfo = JSON.parse('<?php echo json_encode($model->salonInfo)?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});
</script>

<style type="text/css">
.memo{
text-indent: 4px;
}
#dd_filter_box dt{
background-color: #8a9fa5;
color: white;
border: none;
}
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

<div class="item_box">
	<div class="contents_area with_list_area">
		<form action="deduction.php" method="post" onchange="submit()">
			<select name="month"></select>
			<select id="salon_selecter" name="salon" class="hide">
				<option value="all">すべてのサロン</option>
			</select>
		</form>
	</div>
</div>

<div class="group_list_area" id="dd_filter_box">
	<dl>
		<dt>科目フィルタ</dt>
		<dd><ul><li class="total">
			<div class="segment02 clearfix">
				<div class="seg_contents">
					<select id="dd_filter">
						<option value="0">すべて</option>
					</select>
				</div>
				<div class="seg_contents orange" id="dd_total"></div>
			</div>
		</li></ul></dd>
	</dl>
</div>

<div class="group_list_area">

<dl>
	<!-- dt,ddテンプレ -->
	<dt id="temp_dt" style="display: none;">
	</dt>
	<dd id="temp_dd" class="accessory right_one clearfix f_14" style="display: none;">
		<ul>
			<!-- liテンプレ -->
			<li class="temp_li dd_list clearfix" style="display: none;">
				<div class="list_body">
					<div class="segment02 clearfix">
						<div class="seg_contents right">控除科目</div>
						<div class="seg_contents right name"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">金額</div>
						<div class="seg_contents right amount"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">メモ</div>
						<div class="seg_contents right memo"></div>
					</div>
				</div>
				<div class="list_accessory">
					<img alt="" src="../image/Forward-100.png">
				</div>
			</li>
			<!-- ---------- -->
		</ul>
	</dd>
	<!-- ------- -->
</dl>

</div>

</div>

<div class="my_slide">

<table style="width: 100%;">
	<caption>給与控除を追加する</caption>
	<tr>
		<td>スタッフ</td>
		<td>
		<select id="staff_id"></select>
		</td>
	</tr>
	<tr>
		<td>控除科目</td>
		<td>
			<select id="deduction_id"></select>
		</td>
	</tr>
	<tr>
		<td>金額</td>
		<td>
		<input id="amount" type="text" class="faint not_null not_unique_char only_num">
		</td>
	</tr>
	<tr>
		<td>メモ</td>
		<td>
		<input id="memo" type="text" class="faint not_unique_char">
		</td>
	</tr>
</table>
<input type="hidden" id="usd_deduction_id">
<div class="button_box">
	<input type="submit" class="submit_button" value="登録">
</div>
<div class="button_box" style="display: none;">
	<input type="submit" class="delete_button" value="削除">
</div>

</div>

</div>
</div>
</body>
</html>
