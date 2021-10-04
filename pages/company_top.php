<?php
if(!isset($_SESSION)){
 session_start();
}
require_once '../PHPClass/CompanyTopModel.php';
$model = new CompanyTopModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/company_top.js"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>
<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet" 
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<!-- ------- -->

<title>トップページ</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/
//月セレクタのオプション
var _selectorOptions = JSON.parse('<?php echo json_encode($model->selectorOptions)?>');
//月セレクタで選択済みの値
var _selectedMonth = '<?php echo $_SESSION["ct_selected_month"]?>';

var _salonRep = <?php echo json_encode($model->salonReport)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});

</script>

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
		<form action="company_top.php" method="post" id="ct_form">
			<select id="ct_month_selecter" name="month"></select>
		</form>
	</div>
</div>

<div class="center_box">
<dl>
	<dt class="orange" style="text-align: center; font-size: 24px;"></dt>
	<dd class="segment02 clearfix temp_dd" style="display: none;">
	<div class="seg_contents name"></div>
	<div class="seg_contents amount"></div>
	</dd>
</dl>
</div>

</div>
</div>
</div>

</body>
</html>