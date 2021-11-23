<?php
require_once __DIR__.'/../PHPClass/PersonalSettingModel.php';
$model = new PersonalSettingModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/personal_setting.js"></script>
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
<title>アカウント設定</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _status = <?php echo json_encode($model->status)?>;
var _usedColors=  <?php echo json_encode($model->usedColors)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');


jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});

</script>

<style type="text/css">
<!--
#color_selecter{
	width: 48%;
	color: white;
	text-align: center;
	padding: 2px;
}
.color_panel{
	width: 24px;
	height: 24px;
	margin: 2px;
	float: left;
}
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
-->
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

<form action="personal_setting.php" method="post" class="group_list_area">
	<dl>
		<dt>Eメール</dt>
		<dd>
			<ul>
				<li class="total">
					<input type="text" name="e_mail" class="faint not_null not_unique_char">
				</li>
			</ul>
		</dd>
		<dt>カラー</dt>
		<dd>
			<ul>
				<li class="total">
					<input type="text" name="color" id="color_selecter" readonly="readonly">
				</li>
				<li style="display: none;" id="color_picker" class="total clearfix">
				</li>
			</ul>
		</dd>
	</dl>
	<div class="button_box contents_area with_list_area">
		<button type="submit" class="submit_button border_radius">登録</button>
	</div>
</form>


</div>
</div>
</div>
</body>
</html>