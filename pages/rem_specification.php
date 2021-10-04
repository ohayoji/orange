<?php
if(!isset($_SESSION)){
 session_start();
}
require_once '../PHPClass/RemSpecificationModel.php';
$model = new RemSpecificationModel();
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
<script type="text/javascript" src="../js/rem_specification.js"></script>
<script type="text/javascript" src="../js/plan_manager.js"></script>

<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet" 
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->

<title>売上明細</title>

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
var _monthCondition = '<?php echo $_SESSION["rs_month_condition"]?>';
var _addRems = <?php echo json_encode($model->addRems)?>;
var _appBtnFlag = <?php echo json_encode($model->appBtnFlag)?>;
var _approvedFlag =  <?php echo json_encode($model->approvedFlag)?>;
var _report = <?php echo json_encode($model->staffReport)?>;
var _receipts = <?php echo json_encode($model->receipts)?>;
var _staffId = <?php echo $_SESSION["staff"]["id"]?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _deductions = JSON.parse('<?php echo json_encode($model->_getDeductions())?>');
var _usedDeductions = <?php echo json_encode($model->usedDeductions)?>;

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});


</script>

<style type="text/css">
<!--
#adrm_box,
#usddd_box{
font-size: 14px;
padding: 6px;
background-color: #f2f2f2;
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

<div class="item_box">
	<div class="contents_area with_list_area">
		<form action="rem_specification.php" method="post">
			<!-- <select id="rem_spec_month_selecter" name="month" onchange="submit()"></select> -->
			<select id="rem_spec_month_selecter" name="month"></select>
		</form>
	</div>
</div>

<div class="group_list_area">

<dl>
	<dt>今月の売上</dt>
	<dd>
		<ul>
			<li id="monthly_sales" class="ta_right f_20 orange total"></li>
		</ul>
	</dd>
	
	<dt>今月の報酬</dt>
	<dd>
		<ul>
			<li id="rem_total" class="ta_right f_20 orange total"></li>
			<li class="total">
				<div class="segment02 clearfix">
					<div class="seg_contents right slategray">基本給</div>
					<div class="seg_contents right" id="salary"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right slategray">歩合</div>
					<div class="seg_contents right" id="incentive"></div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right slategray">手当</div>
					<div class="seg_contents right">
						<p id="add_rem"></p>
						<!-- 手当明細テンプレ -->
						<div id="adrm_box" class="slategray" style="display: none;">
							<div id="temp_adrm" class="segment02 clearfix" style="display: none;">
								<div class="seg_contents right adrm_title"></div>
								<div class="seg_contents right adrm_amount"></div>
							</div>
						</div>
						<!-- -------------- -->
					</div>
				</div>
				<div class="segment02 clearfix">
					<div class="seg_contents right slategray">給与控除（天引き）</div>
					<div class="seg_contents right">
						<p id="used_deductions"></p>
						<!-- 手当明細テンプレ -->
						<div id="usddd_box" class="slategray" style="display: none;">
							<div id="temp_usddd" class="segment02 clearfix" style="display: none;">
								<div class="seg_contents right usddd_name"></div>
								<div class="seg_contents right usddd_amount"></div>
							</div>
						</div>
						<!-- -------------- -->
					</div>
				</div>
			</li>
			<li id="approval_li" class="total">
				<div class="button_box">
					<input type="button" class="submit_button" id="approval_btn" value="報酬を承認する">
				</div>
			</li>
		</ul>
	</dd>
	
	<dt>全ての売上明細</dt>
	<dd>
		<ul>
		<li class="child_list_area">
			<p id="no_rec_message" class="slategray" style="display: none;">売上明細はありません</p>
			<dl>
				<!-- チャイルドdtテンプレ -->
				<dt id="child_temp_dt" style="display: none;">
				</dt>
				<!-- ----------------- -->
				<!-- チャイルドddテンプレ -->
				<dd id="child_temp_dd" class="clearfix" style="display: none;">
					<ul>
						<!-- チャイルドliテンプレ -->
						<li id="child_temp_li" style="display: none;">
							<p class="costomer"></p>
							<div class="segment04 clearfix">
								<div class="seg_contents">　</div>
								<div class="seg_contents slategray">売上</div>
								<div class="seg_contents slategray">歩合率</div>
								<div class="seg_contents slategray">歩合</div>
							</div>
							<div class="segment04 clearfix">
								<div class="seg_contents slategray">技術</div>
								<div class="seg_contents right tec_sale"></div>
								<div class="seg_contents right tec_rem_v"></div>
								<div class="seg_contents right tec_inc"></div>
							</div>
							<div class="segment04 clearfix">
								<div class="seg_contents slategray">商品</div>
								<div class="seg_contents right pro_sale"></div>
								<div class="seg_contents right pro_rem_v"></div>
								<div class="seg_contents right pro_inc"></div>
							</div>
						</li>
						<!-- ----------------- -->
					</ul>
				</dd>
				<!-- ----------------- -->
			</dl>
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