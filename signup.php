<?php
if(!isset($_SESSION)){
 session_start();
}
require_once 'PHPClass/SignUpModel.php';
$model = new SignUpModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/signup.js"></script>
<script type="text/javascript" src="js/string_check.js"></script>

<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/admission.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>

<title>スタッフサインアップ</title>

<script type="text/javascript">
var _salon = <?php echo json_encode($model->salon)?>;
var _staff = <?php echo json_encode($model->staff)?>;
var _condition = <?php echo json_encode($model->condition)?>;
</script>

</head>

<body>
<div id="container">

<div id="admission_area" class="clearfix">
<div id="messe_box">
<img alt="" src="image/orange_logo_3.png">
<p>さあ Orange をはじめましょう</p>
</div>

<form action="signup.php" method="post" class="border_radius_2"
	id="admission_form">
	<p id="admission_title">スタッフアカウント作成</p>
	
	<input type="hidden" name="id">
	
	<dl>
		<dt>サロン名</dt>
		<dd id="salon_name"></dd>
		<dt>スタッフ名</dt>
		<dd id="staff_name"></dd>
		<dt>メールアドレス</dt>
		<dd>
			<input name="e_mail" type="text"
				class="mail not_null not_unique_char">
		</dd>
		<dd class="f_small slategray">
			*登録したメールアドレスは、ログインのほか、パスワード再発行時などにも利用します。
			必ず有効なメールアドレスを登録してください。
		</dd>
		<dt>メールアドレス<span class="detail">*確認</span></dt>
		<dd>
			<input type="text"
				class="mail not_null not_unique_char">
		</dd>
		<dt>パスワード</dt>
		<dd>
			<input name="password" type="password"
				class="pass not_null not_unique_char not_0_start">
		</dd>
		<dt>パスワード<span class="detail">*確認</span></dt>
		<dd>
			<input type="password"
				class="pass not_null not_unique_char not_0_start">
		</dd>
	</dl>

<button type="submit" class="submit_button">サインアップ</button>
</form>

</div>

</div>
</body>
</html>