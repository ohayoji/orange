<?php
if(!isset($_SESSION)){
 session_start();
}
require_once '../PHPClass/StaffSettingModel.php';
$model = new StaffSettingModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/staff_setting.js?ver=20180826"></script>
<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../js/plan_manager.js"></script>

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

<title>スタッフ設定</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/
/*--planManagerプロパティ------*/
var _planManager = <?php echo json_encode($model->planManager)?>;
/*---------------------------*/
var _addStaffCondition = <?php echo json_encode($model->addStaffCondition)?>;
var _staffs = <?php echo json_encode($model->staffs)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');


jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();

	//プランによるページアクセス制限@plan_manager.js
	$.limitatePageAccess();
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
<div class="list_area">

<div class="contents_area">
<p class="f_small slategray">
スタッフ情報を追加・編集・削除します
</p>
<!-- <p class="f_small slategray">
*基本給または技術歩合の変更は、該当スタッフの当月の登録済み伝票がない状態（月初めなど）で行ってください
</p> -->
</div>

	<dl>
		<!-- テンプレ -->
		<dd id="temp_dd" class="accessory staff_list right_one clearfix" style="display: none;">
			<div class="list_body">
				<div class="_name"></div>
				<div class="f_14">
					<div class="segment02 clearfix">
						<div class="seg_contents right">Eメール</div>
						<div class="seg_contents e_mail right"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">予約アイコン</div>
						<div class="seg_contents icon right"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">役職</div>
						<div class="seg_contents position right"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">基本給</div>
						<div class="seg_contents salary right"></div>
					</div>
					<div class="segment02 clearfix">
						<div class="seg_contents right">技術歩合</div>
						<div class="seg_contents percentage right"></div>
					</div>
				</div>
			</div>
			<div class="list_accessory">
				<img alt="" src="../image/Forward-100_white.png">
			</div>
		</dd>
		<!-- --------- -->
	</dl>

<!-- リストテンプレ -->
<!-- <dl class="temp_dl color_type02 opacity08 shadow01" style="display: none;">
	<dt></dt>
	<dd class="segment02 clearfix">
		<div class="seg_contents right">E_mail</div>
		<div class="seg_contents right e_mail"></div>
		<div class="seg_contents right">予約アイコン</div>
		<div class="seg_contents right icon"></div>
		<div class="seg_contents right">役職</div>
		<div class="seg_contents right position"></div>
		<div class="seg_contents right">基本給</div>
		<div class="seg_contents right salary"></div>
		<div class="seg_contents right">技術歩合</div>
		<div class="seg_contents right percentage"></div>
	</dd>
</dl> -->
<!-- ----------------------------- -->

</div>
</div>

<form class="my_slide" action="staff_setting.php" method="post">
<input type="hidden" name="id">
<input type="hidden" name="mode">
<input id="del_tec_rem" type="hidden" name="del_tec_rem" value="1" disabled="disabled">

<h4 id="staff_title" data-mode="add">スタッフを追加する</h4>

<dl>
	<dt>名前</dt>
	<dd>
		<input type="text" name="_name" class="faint not_null not_unique_char">
	</dd>
	<dt>予約帳アイコン</dt>
	<dd>
		<input type="text" name="icon" class="faint narrow02 not_null not_unique_char">
	</dd>
	<dt>役職</dt>
	<dd>
		<input type="radio" name="position" value="s" id="s" checked="checked">
		<label for="s">スタイリスト</label>
		<input type="radio" name="position" value="a" id="a">
		<label for="a">アシスタント</label>
	</dd>
	<dt>基本給</dt>
	<dd>
		<input type="text" name="salary" class="faint not_unique_char only_num chkcode">
	</dd>
	<dt>技術歩合(%) <span class="f_small">*技術売上に対する歩合設定</span></dt>
	<dd>
		<input type="text" name="percentage" class="faint narrow02 not_unique_char only_num chkcode">
		<div>
			<span class="f_small">
			*ここで設定した歩合率は、今月１日以降のすべての伝票に適用されます
			<br>*0％にする場合は「0」を入力してください<br>（空白の場合は「未設定」扱いになります）
			</span>
		</div>

	</dd>

</dl>

<div class="button_box">
	<input type="button" class="submit_button" value="登録">
</div>
<div class="button_box" style="display: none;">
	<input type="button" class="delete_button" value="削除">
</div>

</form>
</div>
</div>
</body>
</html>
