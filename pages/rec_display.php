<?php
require_once __DIR__.'/../PHPClass/RecDisplayModel.php';
$model = new RecDisplayModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/rec_display.js?ver=20190114"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/register_buttons.css?ver=20190114" rel="stylesheet" type="text/css"/>

<!--font-awesome----------->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<!-------------------------->

<title>レシート</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = JSON.parse('<?php echo json_encode($model->_visiter)?>');
var _postName = JSON.parse('<?php echo json_encode($model->_postName)?>');
var _personName = JSON.parse('<?php echo json_encode($model->_personName)?>');
/*---------------------------*/
var _salonId = '<?php echo $_SESSION["salon"]["id"]?>';
var _dayStr = '<?php echo $model->todayStr?>';
var _receipt = JSON.parse('<?php echo json_encode($model->receipt)?>');

var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');

</script>

<style type="text/css">
.rec_disp_contents{
	padding: 12px;
}
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
@MEDIA screen and (min-width:768px)  {
	.rec_disp_contents{
		box-sizing: border-box;
		width: 600px;
		margin: auto;
	}


}

</style>

</head>

<body>

<div id="container">
<div id=main_alea>
<div class="contents_area">
	<a id="close" class="right_link rec_disp_contents" href="javascript:void(0)">
		<i class="fa fa-times" aria-hidden="true"></i>
		閉じる
	</a>

	<h3 class="heading02 orange">お会計伝票</h3>

	<div class="rec_disp_contents" id="h">
		<dl><dt></dt><dd></dd></dl>
	</div>

	<div class="rec_disp_contents" id="menus">
	</div>

	<div class="rec_disp_contents">

	<div class="segment03 clearfix">
		<div class="seg_contents right">技術</div>
		<div class="seg_contents right" id="t_disc"></div>
		<div class="seg_contents right" id="t_sale"></div>
	</div>
	<div class="segment03 clearfix">
		<div class="seg_contents right">商品</div>
		<div class="seg_contents right" id="p_disc"></div>
		<div class="seg_contents right" id="p_sale"></div>
	</div>
	<div class="segment02 clearfix f_36">
		<div class="seg_contents orange right">合計</div>
		<div class="seg_contents orange right" id="total"></div>
	</div>
	<div class="cash_carc">
		<div class="segment02 clearfix f_18">
			<div class="seg_contents right">お預かり</div>
			<div class="seg_contents right cash_carc_charge">
				<input class="faint narrow03 ta_right" type="number" name="" value="">
			</div>
		</div>
		<div class="segment02 clearfix f_18">
			<div class="seg_contents right">お釣り</div>
			<div class="seg_contents right cash_carc_back"></div>
		</div>
	</div>


	</div>

	<div id="register_buttons" class="rec_disp_contents">
		<button type="button" class="register_sub" id="rgstr_cash">
			<span class="icon">
				<i class="fa fa-money" aria-hidden="true"></i>
			</span>
			<span class="text"><span>現金</span>でお会計</span>
		</button>
		<button type="button" class="register_sub" id="rgstr_card">
			<span class="icon">
				<i class="fa fa-credit-card" aria-hidden="true"></i>
			</span>
			<span class="text"><span>カード</span>でお会計</span>
		</button>
    <button type="button" class="register_sub" id="rgstr_e_money">
			<span class="icon">
				<i class="fa fa-credit-card" aria-hidden="true"></i>
			</span>
			<span class="text"><span>電子マネー</span>でお会計</span>
		</button>
	</div>

	<div class="thankyou_message ta_center hide">
		<p class="f_24 orange">ありがとうございました</p>
		<p class="f_18">
			お支払い方法：
			<span class="cash hide">現金</span>
			<span class="card hide">カード</span>
      <span class="e_money hide">電子マネー</span>
		</p>
	</div>

</div>
</div>
</div>
</body>
</html>
