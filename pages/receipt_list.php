<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/ReceiptListModel.php';
$model = new ReceiptListModel();
//$model->test2($_SESSION["salon"]["id"]);
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
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/receipt_list.js?ver=20170925"></script>

<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../js/plan_manager.js"></script>
<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet"
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->
<!-- my_plugins -->
<link href="../css/slide_vertical.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../js/overlay.js"></script>
<script type="text/javascript" src="../js/slide_vertical.js"></script>

<link href="../my_plugin/my_receipt_status/my_receipt_status.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../my_plugin/my_receipt_status/my_receipt_status.js"></script>
<!-- ------- -->

<title>伝票</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/
/*--planManagerプロパティ------*/
var _planManager = <?php echo json_encode($model->planManager)?>;
/*---------------------------*/
var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _condition = <?php echo json_encode($_SESSION["rec_list_condition"])?>;
var _staffs = <?php echo json_encode($model->staffs)?>;
var _todayStr = <?php echo json_encode($model->_todaySQLStr)?>;
var _receipts = <?php echo json_encode($model->receipts)?>;
var _total = <?php echo json_encode($model->totalSale)?>;
var _recEnts = <?php echo json_encode($model->recEntries)?>;
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
#main_alea{
padding: 0;
}
.list_area dl dt{
	padding-left: 12px;
	padding-bottom: 12px;
}
.list_area dl dd .seg_contents{
	padding: 4px 0;
}
.list_area dl dd .seg_contents span.f,
.list_area dl dd .seg_contents span.s{
	padding-left: 4px;
}
.list_area dl dd .seg_contents span.f{
  color: rgb(72, 63, 185);
}
.list_area dl dd .seg_contents span.s{
  color: rgb(200, 87, 39);
}
div.menu img{
	width: 24px;
	height: 24px;
	margin-right: 2px;
}
select{
	margin: 4px 0;
}
div.rec_ent{
	float: left;
	width: 50%;
	/*text-align: center;*/
}
.rec_status{
	font-size: 12px;
}
.rec_status img{
	height: 14px;
}
.rem_comp_selecter li{
	list-style: none;
    padding: 2px 0;
    font-size: 14px;
}
.rem_comp_selecter img{
	width: 100px;
}

.weekday{
  display: flex;
  list-style: none;
  justify-content: space-between;
  flex-wrap: wrap;
}
.weekday li{
  font-size: 16px;
  padding-right: 12px;
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

<!-- 通知領域 -->
<div id="rem_check_mode_area" class="posting_area red">
	<p></p>
</div>
<!-- -------------- -->

<div class="list_area">
	<dl>
		<dt class="slategray vp_12">
			検索件数 : <span id="numRecords" class="orange f_18"></span> 件
			<br>売上合計 : <span id="totalSale" class="orange f_18"></span>
			<span class="slategray datail">（技術 + 商品）</span>
		</dt>
		<!-- ddテンプレ -->
		<dd id="temp_dd" class="accessory right_one clearfix" style="display: none;">
			<div class="list_body">
				<div class="segment03 clearfix">
					<div class="seg_contents date left">
					</div>
					<div class="seg_contents costomer_name right">
					</div>
					<div class="seg_contents stylist_name right">
					</div>
				</div>
				<div class="segment02 clearfix slategray">
					<div class="seg_contents menu right opacity08">
					</div>
					<div class="seg_contents sale right orange">
					</div>
				</div>
				<div class="clearfix slategray rec_status">

				</div>
			</div>
			<div class="list_accessory">
				<img alt="" src="../image/Forward-100.png">
			</div>
		</dd>
		<!-- ------------ -->
	</dl>
</div>

</div>

<form id="search_field" class="my_slide"
	action="receipt_list.php" method="post">

	<h4>検索条件</h4>
	<dl>
		<dt>日付</dt>
		<dd class="middle_contents">
			<div>
				<input type="checkbox" name="today" id="today">
				<label for="today">今日</label>
			</div>
		</dd>
		<dd>
			<div class="start_end">
				<select id="rec_list_start_month_selecter" name="start_month" class="monSel"><option value="0">------</option></select>
				<select name="start_date"><option value="0">--</option></select>
			</div>
			<div class="start_end">
				　〜　
				<select name="end_month" class="monSel"><option value="0">------</option></select>
				<select name="end_date"><option value="0">--</option></select>
			</div>
		</dd>
		<dt class="staff_select">スタッフ</dt>
		<dd class="staff_select">
			<select name="staff_id"><option value="0">------</option></select>
		</dd>
    <dt>曜日</dt>
    <dd class="middle_contents">
      <ul class="weekday">
        <li>
          <input type="checkbox" name="weekday_0" id="sun">
          <label for="sun">日</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_1" id="mon">
          <label for="mon">月</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_2" id="tue">
          <label for="tue">火</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_3" id="wed">
          <label for="wed">水</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_4" id="thu">
          <label for="thu">木</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_5" id="fri">
          <label for="fri">金</label>
        </li>
        <li>
          <input type="checkbox" name="weekday_6" id="sat">
          <label for="sat">土</label>
        </li>
      </ul>
    </dd>
		<dt>技術売上</dt>
		<dd>
			<input type="text" name="start_sale" class="faint narrow03 not_unique_char only_num chkcode">
			〜 <input type="text" name="end_sale" class="faint narrow03 not_unique_char only_num chkcode">
		</dd>
		<dt>来店回数</dt>
		<dd>
			<select name="num_visit"><option value="99">----</option></select>
		</dd>
		<dt>支払い方法</dt>
		<dd>
			<select name="pay_type"><option value="99">----</option></select>
		</dd>
		<dt class="rem_comp_selecter">
			伝票ステータス<!-- （来店処理） -->
			<a class="inline_help_link" href="../help/index.html?visiter=salon&show_target=receipt_status" target="_blank">?</a>
		</dt>
		<dd class="rem_comp_selecter">
			<!-- <select name="rem_comp">
				<option value="99">----</option>
				<option value="0">会計済み／来店処理なし</option>
				<option value="1">会計済み／来店処理済み</option>
			</select> -->
			<ul>
			<li>
				<input type="radio" name="rem_comp" value="99" id="rem_comp_99">
				<label for="rem_comp_99" id="for_rem_comp_99">すべて（会計済み伝票）</label>
			</li>
			<li>
				<input type="radio" name="rem_comp" value="0" id="rem_comp_0" checked="checked">
				<label for="rem_comp_0" id="for_rem_comp_0"></label>
			</li>
			<li>
				<input type="radio" name="rem_comp" value="1" id="rem_comp_1">
				<label for="rem_comp_1" id="for_rem_comp_1"></label>
			</li>
			</ul>
		</dd>
    <dt>指名／フリー</dt>
    <dd>
      <div class="segment03 clearfix">
        <div class="seg_contents" style="text-align: left">
          <input type="radio" name="free_v" value="99" id="free_v_99" checked="checked">
          <label for="free_v_99">すべて</label>
        </div>
        <div class="seg_contents" style="text-align: left">
          <input type="radio" name="free_v" value="0" id="free_v_0">
          <label for="free_v_0">指名</label>
        </div>
        <div class="seg_contents" style="text-align: left">
          <input type="radio" name="free_v" value="1" id="free_v_1">
          <label for="free_v_1">フリー</label>
        </div>
      </div>
    </dd>
		<dt>その他</dt>
		<dd>
			<div class="rec_ent">
				<input type="checkbox" name="net" id="net">
				<label for="net"> ネット予約</label>
			</div>
			<div class="rec_ent">
				<input type="checkbox" name="point" id="point">
				<label for="point"> ポイント</label>
			</div>
      <div id="salon_customize_recent">
        <!-- サロン独自項目表示エリア -->
      </div>
		</dd>

	</dl>

	<p class="f_small ta_right slategray" style="margin-top: 6px;">*会計済み伝票</p>

	<div class="button_box">
		<input type="button" class="submit_button" value="検索">
	</div>

</form>

</div>
</div>
</body>
</html>
