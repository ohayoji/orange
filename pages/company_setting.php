<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/CompanySettingModel.php';
$model = new CompanySettingModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/company_setting.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>

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
<!-- my_plugins -->
<!-- <script type="text/javascript" src="../js/flip_button.js"></script> -->
<!-- ------- -->

<title>会社情報設定</title>
<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
/*---------------------------*/

var _salonInfo = <?php echo json_encode($model->salonInfo)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _companyId = '<?php echo $_SESSION["company"]["id"]?>'


jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();
});

</script>
</head>

<body id="body">
<div id="wrap">
<div id="container">
<script type="text/javascript">
$._createHeader();
$._createNavigation();
</script>

<div id="main_alea">
<div class="contents_area">

<form action="company_setting.php" id="setting_form" method="post">

	<dl>
	<dt>会社名</dt>
	<dd>
		<input class="faint" type="text" name="company_name" id="company_name"
			value="<?php echo $_SESSION["company"]["_name"]?>" disabled="disabled">
	</dd>
	<dt>Eメール</dt>
	<dd>
		<input class="faint not_unique_char not_null" type="text" name="e_mail" id="e_mail"
			value="<?php echo $model->eMail?>">
	</dd>
	<dt>パスワード</dt>
	<dd>
		<input class="faint not_unique_char not_null not_0_start" type="password" name="password" id="password"
			value="<?php echo $model->password?>">
	</dd>
	<dt>ホームページ</dt>
	<dd id="homepage">
		<input type="text" class="faint" disabled ="disabled"
				name="homepage" id="homepage"
				value="<?php echo $model->homepage?>">
	</dd>
	
	<!-- <dt>クレジットカード情報</dt>
	<dd id="credit_card">
		<input type="text" class="faint" disabled ="disabled"
				name="credit_card" id="credit_card"
				value="<?php echo $model->creditCard?>">
	</dd>
	
	<dt>銀行口座情報</dt>
	<dd id="bank">
		<input type="text" class="faint" disabled ="disabled"
				name="bank" id="bank"
				value="<?php echo $model->bank?>">
	</dd> -->
	
	<dt id="salon_info">サロン情報</dt>
	<dd><a class="right_link" target="blank" id="admission">サロンを追加＋</a></dd>
	
	<!--<dt>契約プラン</dt>
	<dd id="plan">
		<input type="text" class="faint" disabled ="disabled"
				name="plan" id="plan"
				value="<?php echo $model->plan?>">
	</dd>  -->

	<dt>
	<!-- <a class="right_link" style="text-align: left; text-decoration: none;" href="create_report.php"
	 onclick="return confirm('ここまでの編集内容は失われますがよろしいですか？')">
	サロンの過去分月報を作成・編集する▷</a>
	</dt> -->
	
</dl>
<button type="submit" class="submit_button">登録</button>
</form>


<!-- <dl>
	<dt>会社で取り扱う勘定科目</dt>
	<dd id="sub_list" class="clearfix" style="text-align: left;">
	</dd>
	
	<dt>技術歩合率パターン
		<br>
		<span class="detail">
		*伝票ごとに歩合率を切り替える場合はここで歩合率候補を設定してください
		</span>
	</dt>
	<dd>
		<table id="tec_pp_list" style="width: 100%; text-align: center;">
			<tr><td>歩合率</td><td>選択済</td><td>削除</td></tr>
		</table>
	</dd>
	<dd>
		<input type="button" class="add_pp_btn" value="+">
	</dd>
	
	<dt>商品歩合率パターン
		<br>
		<span class="detail">
		*伝票ごとに歩合率を切り替える場合はここで歩合率候補を設定してください
		</span>
	</dt>
	<dd>
		<table id="pro_pp_list" style="width: 100%; text-align: center;">
			<tr><td>歩合率</td><td>選択済</td><td>削除</td></tr>
		</table>
	</dd>
	<dd>
		<input type="button" class="add_pp_btn" value="+">
	</dd>
	
</dl> -->




</div>
</div>
</div>
</div>
</body>
</html>
