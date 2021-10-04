<?php
require_once '../PHPClass/MyAccountModel.php';
$model = new MyAccountModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/my_account.js"></script>
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

<title>マイアカウント</title>

<script type="text/javascript">

/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
var _today = <?php echo json_encode($model->_todaySQLStr)?>;

/*---------------------------*/
var _planManager = <?php echo json_encode($model->planManager)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _salonId = <?php echo $_SESSION["salon"]["id"]?>;
var _salonStatus = <?php echo json_encode($model->salonStatus)?>;
var _plice = JSON.parse('<?php echo json_encode($model->price)?>');
var _remainderDays = '<?php echo $model->remainderDays?>';
var _craimDay = '<?php echo $model::CRAIM_DAY?>';

var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');
//var _premiumSuccess = JSON.parse('<?php echo json_encode($model->premiumSuccess)?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();

	//プランによるページアクセス制限@plan_manager.js
	$.limitatePageAccess();
});

</script>

<style type="text/css">
/*#e_mail{
float: right;
}*/
#email_submit{
width: 200px;
}
strong {
	color: #379cbf;
	font-weight: bold;
}
a.plan_diff{
	display: inline;
	text-decoration: underline;
	color: #845b96;
}
/*#local_p_message{
	margin-left: 4px;
}*/
.trial_detail_changer a{
	padding: 6px;
	font-size: 14px;
	text-decoration: underline;
	color: #845b96;
}
#webpay_form{
	text-align: center;
}
#WP_checkoutBox input[type=button]{
	font-size: 18px !important;
	line-height: 1 !important;
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

<div class="group_list_area">
	<dl>
		<dt>Eメール</dt>
		<dd>
		<ul>
			<li class="total">
			<form id="e_mail_form" action="my_account.php" method="post">
				<input class="faint not_null not_unique_char status" type="text" id="e_mail" name="e_mail">
				<div class="button_box">
					<button id="email_submit" type="submit" class="submit_button">Eメールを変更する</button>
				</div>
			</form>
			</li>
		</ul>
		</dd>
		<dt>パスワード</dt>
		<dd>
			<ul>
				<li class="total">
					<a href="../password_reissue.php" class="right_link" target="_blank">パスワードを変更する</a>
				</li>
			</ul>
		</dd>
  						</div>
					</form>
				</li>
			</ul>
		</dd>
		<dt class="f_14 slategray">解約</dt>
		<dd>
			<ul>
				<li class="total">
					<a id="cancel_link" class="right_link f_14" href="">Orangeを解約する</a>
				</li>
			</ul>
		</dd>
	</dl>
</div>

</div>
</div>
</div>
</body>
</html>
