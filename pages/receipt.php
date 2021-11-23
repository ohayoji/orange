<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/ReceiptModel.php';
$model = new ReceiptModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../js/ViewController/MenuViewController.js"></script>
<script type="text/javascript" src="../js/ViewController/SelectedMenuRowViewController.js"></script>
<script type="text/javascript" src="../js/ViewController/MenuDetailCellViewController.js"></script>
<script type="text/javascript" src="../js/receipt.js?ver=20180816"></script>
<script type="text/javascript" src="../js/vivo_func.js"></script>


<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>
<link href="../css/receipt.css" rel="stylesheet" type="text/css"/>
<!--<link href="../css/register_buttons.css" rel="stylesheet" type="text/css"/>-->
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<!-- plugins -->
<!--<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet"
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>  -->
<!-- ------- -->
<!-- my_plugins -->
<link href="../my_plugin/my_receipt_status/my_receipt_status.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../my_plugin/my_receipt_status/my_receipt_status.js"></script>
<!-- ---------- -->

<!--font-awesome----------->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<!-------------------------->

<title>お会計伝票</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = JSON.parse('<?php echo json_encode($model->_visiter)?>');
var _postName = JSON.parse('<?php echo json_encode($model->_postName)?>');
var _personName = JSON.parse('<?php echo json_encode($model->_personName)?>');
/*---------------------------*/

var _salonId = '<?php echo $_SESSION["salon"]["id"]?>';
var _receipt = JSON.parse('<?php echo json_encode($model->receipt)?>');
var _dateStr = '<?php echo $model->dateStr?>';
var _menus = JSON.parse('<?php echo json_encode($model->menus)?>');
var _recEnts = JSON.parse('<?php echo json_encode($model->usgRecEntries)?>');
var _tecPP = JSON.parse('<?php echo json_encode($model->tecPP)?>');
var _proPP = JSON.parse('<?php echo json_encode($model->proPP)?>');
var _products = JSON.parse('<?php echo json_encode($model->products)?>');
var _usdProducts = JSON.parse('<?php echo json_encode($model->usdProducts)?>');
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');
//フラグ
var _tecRemFlag = '<?php echo $model->tecRemInputFlag?>';
var _proRemFlag = '<?php echo $model->proRemInputFlag?>';
var _staffPerFlag = '<?php echo $model->staffPercentageFlag?>';
var _totalAmountFlag = '<?php echo $model->totalAmountFlag?>';
var _staffEditFlag = '<?php echo $model->staffEditSubFlag?>';
var _regiSubFlag = '<?php echo $model->registerSubFlag?>';
var _preSubFlag = '<?php echo $model->preSubFlag?>';
var _addProFlag = '<?php echo $model->addProFlag?>';
var _masterSubFlag = '<?php echo $model->masterSubFlag?>';
var _backResFlag = '<?php echo $model->backReserveFlag?>';
var _backRecListFlag = '<?php echo $model->backRecListFlag?>';

$(function(){
  $("span.currency").text(CURRENCY);
});

</script>

<style type="text/css">
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
/*.seg_contents.pay_type{
	background-color: rgb(250, 52, 9);
  color: white;
  border-radius: 4px;
}*/
#pay_type i{
	font-size: 24px;
	margin-right: 6px;
}
</style>

</head>

<body>

<div id="container">
<script type="text/javascript">
$._createHeader();
//$._createNavigation();
</script>

<div id="main_alea">
<div id="receipt_area">

<div class="contents_area">
<a id="back_link_reserve" class="right_link" href="reserve.php">
	予約帳に戻る▷</a>
<a id="back_link_reclist" class="right_link" href="receipt_list.php" style="display: none;">
	伝票リストに戻る▷</a>
</div>

<div class="contents_area">
<p>お会計伝票</p>

<div class="segment03 clearfix">
<div class="seg_contents" id="date"></div>
<div class="seg_contents" id="costomer"></div>
<div class="seg_contents" id="staff"></div>
</div>
</div>

<div id="receipt_status" class="segment02 clearfix vp_12 hp_12 f_14">
	<div class="seg_contents"></div>
	<div class="seg_contents"></div>
</div>

<div id="menu_list" class="clearfix"></div>

<div class="contents_area conttents_title">MENU</div>
<table id="selected_menus">
<!-- <caption>MENU</caption>-->
</table>


<div class="contents_area conttents_title">商品</div>
<table id="selected_products">
<!-- <caption>商品</caption> -->
<!-- テンプレtr------------------------- -->
<tr class="temp_pro" style="display: none;">
	<td class="pro_icon">
		<img alt="" src="../image/bag_.png">
	</td>
	<td>
		<select class="pro_sel">
			<option value="0">商品を選択</option>
		</select>
	</td>
	<td>
		<select class="pro_num">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
	</td>
	<td>
		<input type="text" class="faint not_unique_char only_num chkcode">
	</td>
	<td class="del_icon">
	</td>
</tr>
<!-- ---------------------------------------- -->
<tr id="add_pro">
	<td colspan="5">
		<img alt="" class="right_icon" src="../image/plus_2.png">
	</td>
</tr>
</table>

<div class="contents_area conttents_title">来店回数</div>
<div id="detail">
	<select id="num_visit" name="num_visit">
	</select>
</div>

<div class="contents_area conttents_title">その他</div>
<div id="rec_entries" class="clearfix rec_ent"></div>

<div class="contents_area conttents_title pay_type hide">お支払い</div>
<div id="pay_type" class="pay_type hide segment02 clearfix">
	<div class="seg_contents">
		<input id="pt_0" type="radio" name="pay_type" value="0">
		<label for="pt_0">現金</label>
	</div>
	<div class="seg_contents">
		<input id="pt_1" type="radio" name="pay_type" value="1">
		<label for="pt_1">カード</label>
	</div>
	<!--<p class="cash">
		<i class="fa fa-money" aria-hidden="true" style="color:rgb(67, 123, 163)"></i>現金
	</p>
	<p class="card hide">
		<i class="fa fa-credit-card" aria-hidden="true" style="color:rgb(67, 163, 89)"></i>カード
	</p>-->
</div>



<!-- 技術コンテンツ -->
<div id="total_contents_area" class="clearfix">
<div class="total_contents tec">
<dl>
	<dt>技術
	<span class="right">
		<select id="tec_disc">
			<option value="0">0</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
		</select>
		%off
	</span>
	</dt>
	<dd class="slategray">
	技術合計を計算して反映
	<button type="button" class="copy_sales_btn"><p>↓</p></button>
	</dd>
	<dd id="tec">
		合計 <span class="currency"></span>
		<input type="text" id="tec_sale" name="tec_sale" class="faint not_null not_unique_char only_num chkcode">
		<div class="vp_6">
			<span id="tec_rem_selecter_message" class="slategray hide">技術歩合を選択 </span>
			<select class="rem_selecter" id="tec_rem" style="display: none;"></select>
			<span id="staff_per_message" class="slategray hide">固定歩合 </span><span id="staff_per" style="display: none;">staff_per</span>
		</div>
	</dd>
</dl>
</div>
<!-- ------------- -->

<!-- 店販コンテンツ -->
<div class="total_contents pro">
<dl>
	<dt>商品
	<span class="right">
		<select id="pro_disc">
			<option value="0">0</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
		</select>
		%off
	</span>
	</dt>
	<dd class="slategray">
		商品の金額を反映
		<button type="button" class="copy_sales_btn"><p>↓</p></button>
	</dd>
	<dd id="pro">
		合計 <span class="currency"></span>
		<input type="text" id="pro_sale" name="pro_sale" class="faint not_null not_unique_char only_num chkcode">
		<div class="vp_6">
			<span id="pro_rem_selecter_message" class="slategray hide">商品歩合を選択 </span>
			<select class="rem_selecter" id="pro_rem" style="display: none;"></select>
		</div>
	</dd>
</dl>
</div>
</div>
<!-- ---------- -->

<div id="memo_area">メモ<br>
<textarea id="memo" class="not_unique_char" rows="3" cols="" name="memo">
</textarea>
</div>

<div class="button_box" id="receipt_buttons">
<input type="button" id="register_sub" class="submit_button" value="お会計をする">
<!--<input type="button" class="submit_button register_sub" value="現金でお会計">
<input type="button" class="submit_button register_sub" value="カードでお会計">-->
<!--<div id="register_buttons">
	<button type="button" class="register_sub" id="rgstr_cash">
		<span class="icon">
			<i class="fa fa-money" aria-hidden="true"></i>
		</span>
		<span class="text">現金でお会計</span>
	</button>
	<button type="button" class="register_sub" id="rgstr_card">
		<span class="icon">
			<i class="fa fa-credit-card" aria-hidden="true"></i>
		</span>
		<span class="text">カードでお会計</span>
	</button>
</div>-->
<input type="button" id="pre_sub" class="sub_button"
	value="仮登録" style="display: none">
<input type="button" id="staff_edit_sub" class="submit_button"
	value="編集内容を登録" style="display: none">
<input type="button" id="master_sub" class="submit_button"
	value="来店処理" style="display: none">
</div>
<div id="master_delete" class="contents_area" style="display: none;">
	<a class="right_link" href="javascript:void(0)">この伝票を削除する</a>
</div>

</div>
</div>
</div>
</body>
</html>
