<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/turba_func_SalarySheetModel.php';
$model = new turba_func_SalarySheetModel();
?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/report_table.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>

<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/turba_func_salary_sheet.js"></script>

<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet"
      media="all and (min-width: 1024px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->

<title>turba社員給与明細</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
var _monthCondition = '<?php echo $_SESSION["sr_month_condition"]?>';
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _monthCondition = '<?php echo $_SESSION["tu_sal_month_condition"]?>';
var _staffs = JSON.parse('<?php echo json_encode($model->staffs)?>');
var _addRems = JSON.parse('<?php echo json_encode($model->addRems)?>');
var _totalAddRems = JSON.parse('<?php echo json_encode($model->totalAddrems)?>');
var _usedDeductions = JSON.parse('<?php echo json_encode($model->usedDeductions)?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting({
		//navTabName: "staff_report",
	});
});
</script>

<style type="text/css">
/*.report_table .title_row td,
.report_table .title_row th{
	height: 48px !important;
}*/
#data_table th{
	width: "150pt";
}
#data_table input{
	text-align: right;
}
.add_rem_meisai td,
tr[name=add_rem_meisai] td{
  text-align: right;
  font-size: 12px;
  vertical-align: top;
  height: 80px !important;
}
tr[name=add_rem_meisai] td p{
  overflow: scroll;
  height: 80px;
}

@MEDIA screen and (min-width:768px)  {
	title_table, #name_table{
		width: 20%;
	}

	#data_table_area{
		width: 80%;
		margin-left: 20%;
	}
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

<form action="turba_func_salary_sheet.php" method="post" onchange="submit()" class="hp_24 vp_12">
<select name="month"></select>
</form>

<div id="report_list_area">
<table id="name_table" class="report_table">
	<colgroup width="30px"></colgroup>
	<tr class="title_row"><th colspan="2">科目</th></tr>
	<tr><td rowspan="5" class="ta_center">給与</td><td>基本給</td></tr>
	<tr><td>歩合</td></tr>
	<tr><td>手当</td></tr>
  <tr class="add_rem_meisai"><td>手当明細<br>*スクロール可</td></tr>
	<tr name="salary_total"><td>合計</td></tr>
	<tr><td rowspan="5" class="ta_center">控除</td><td>厚生年金</td></tr>
	<tr><td>健康保険</td></tr>
	<tr name="kousei_kenkou"><td>社保計</td></tr>
	<tr><td>雇用保険</td></tr>
	<tr name="kousei_kenkou_koyou"><td>合計</td></tr>
	<tr name="sashihiki"><td colspan="2">差引支給額</td></tr>
	<tr><td colspan="2">源泉徴収</td></tr>
	<tr><td colspan="2">住民税</td></tr>
	<tr><td colspan="2">その他</td></tr>
	<tr name="shiharai"><td colspan="2">最終支払額</td></tr>
</table>
</div>

<div id="data_table_area">
<table id="data_table" class="report_table">
	<tr name="staff_name" class="title_row"></tr>
	<tr name="salary"></tr>
	<tr name="rem"></tr>
	<tr name="add_rem"></tr>
  <tr name="add_rem_meisai"></tr>
	<tr name="salary_total"></tr>
	<tr name="kousei" data-deduction_id="1"></tr>
	<tr name="kenkou" data-deduction_id="2"></tr>
	<tr name="kousei_kenkou"></tr>
	<tr name="koyou" data-deduction_id="4"></tr>
	<tr name="kousei_kenkou_koyou"></tr>
	<tr name="sashihiki"></tr>
	<tr name="gensen" data-deduction_id="5"></tr>
	<tr name="juumin" data-deduction_id="3"></tr>
	<tr name="other" data-deduction_id="6"></tr>
	<tr name="shiharai"></tr>
</table>
</div>

</div>

</div>
</div>

</body>
</html>
