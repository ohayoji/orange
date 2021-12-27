<?php
if(!isset($_SESSION)){
 session_start();
}
require_once "../PHPClass/AddRemModel.php";
$model = new AddRemModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/add_rem.js?ver=20180826"></script>
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


<title>手当管理</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _monthCondition = '<?php echo $_SESSION["ar_month_condition"]?>';
var _staffs = <?php echo json_encode($model->staffs)?>;
var _approvalStaffs = <?php echo json_encode($model->approvedStaffs)?>;
var _addRems = <?php echo json_encode($model->addRems)?>;
var _totalAmo = <?php echo json_encode($model->totalAmounts)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');


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
	<div class="contents_area with_list_area">
		<form action="add_rem.php" method="post" onchange="submit()">
			<select name="month"></select>
		</form>
	</div>
</div>


<div class="group_list_area">

<dl>
	<!-- dt,ddテンプレ -->
	<dt id="temp_dt" style="display: none;">
	</dt>
	<dd id="temp_dd" class="accessory right_one clearfix f_14" style="display: none;">
		<ul>
			<!-- liテンプレ -->
			<li class="temp_li adrm_list clearfix" style="display: none;">
				<div class="list_body">
					<div class="segment02 clearfix">
						<div class="seg_contents right">手当名</div>
						<div class="seg_contents right title"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">金額</div>
						<div class="seg_contents right amount"></div>
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

<!-- ------------ -->

</div>

</div>

<div class="my_slide">

<table style="width: 100%;">
	<caption data-mode="add">手当を追加する</caption>
	<tr>
		<td>スタッフ</td>
		<td>
		<select id="staff_id"></select>
		</td>
	</tr>
	<tr>
		<td>手当名</td>
		<td>
		<input id="title" type="text" class="faint not_null not_unique_char">
		</td>
	</tr>
	<tr>
		<td>金額<p class="detail f_small">*マイナスも入力可</p></td>
		<td>
		<input id="amount" type="text" class="faint not_null not_unique_char only_int">
		</td>
	</tr>
</table>
<input type="hidden" id="add_rem_id">
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
