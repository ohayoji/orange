<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/PaymentModel.php';
$model = new PaymentModel();

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/payment.js"></script>
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
<script type="text/javascript" src="../js/flip_button.js"></script>
<!-- ------- -->

<title>支払管理</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _condition = <?php echo json_encode($_SESSION["payment_condition"])?>;
var _staffInfo = <?php echo json_encode($model->staffInfo)?>;
var _approvedRems = <?php echo json_encode($model->approvedRems)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');

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

<div class="item_box">
	<div class="contents_area with_list_area slategray">
		<p>検索件数 : <span id="numRecords" class="orange f_18"></span> 件</p>
		<p>合計金額 : <span id="totalamo" class="orange f_18"></span></p>
		
	</div>
</div>

<div class="group_list_area">

<dl id="aprms">
	<!-- dt,ddテンプレ -->
	<dt id="temp_dt" style="display: none;"></dt>
	<dd id="temp_dd" class="clearfix" style="display: none;">
		<ul>
			<!-- liテンプレ -->
			<li id="temp_li" style="display: none;">
				<div class="segment03 clearfix">
					<div class="seg_contents right month"></div>
					<div class="seg_contents right amount"></div>
					<div class="seg_contents right">
						<input type="button">
					</div>
				</div>
			</li>
			<!-- ---------- -->
		</ul>
	</dd>
	<!-- ---------- -->
</dl>

</div>
</div>

<form id="search_field" class="my_slide" action="payment.php" method="post">

<h4>検索条件</h4>

<dl>
	<dt>月</dt>
	<dd>
		<select name="start_month" class="monSel"><option value="">------</option></select>
		〜
		<select name="end_month" class="monSel"><option value="">------</option></select>
	</dd>
	<dt>スタッフ</dt>
	<dd>
		<select name="staff_id"><option value="">------</option></select>
	</dd>
	<dt>金額</dt>
	<dd>
		<input type="text" name="start_amount" class="faint narrow03 not_unique_char only_num chkcode">
		〜 <input type="text" name="end_amount" class="faint narrow03 not_unique_char only_num chkcode">
	</dd>
	<dt>未払い／支払済み</dt>
	<dd>
		<select name="paid">
			<option value="">----</option>
			<option value="0">未払い</option>
      		<option value="1">支払済</option>
		</select>
	</dd>
</dl>

<div class="button_box">
<input type="submit" class="submit_button" value="検索">
</div>

</form>
</div>
</div>
</body>
</html>