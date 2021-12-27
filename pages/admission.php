<?php
require_once '../PHPClass/AdmissionModel.php';
new AdmissionModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/signup.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../js/popup.js"></script>
<script type="text/javascript" src="../js/admission.js"></script>
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/popup.css" rel="stylesheet" type="text/css"/>
<link href="../css/admission.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>

<title>申し込み</title>

</head>
<body id="body">
<div id="container">

<div id="popup_view"></div>
<!-- ポップアップコンテンツテンプレート -->
<div id="temp_popup_contents" style="display: none;">
	<div>
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	利用規約利用規約利用規約利用規約利用規約利用規約利用規約利用規約
	</div>
	
	<div class="clearfix btns">
		<button class="agree_btn">同意する</button>
		<button class="no_agree_btn">同意しない</button>
	</div>
</div>
<!-- ------------------------------- -->

<div id="main_alea" class="clearfix">
<div id="messe_box">
<p>さあ ハコぴた をはじめましょう</p>
</div>

<form action="admission.php" method="post" class="border_radius_2"
	id="admission_form">
	<p id="admission_title">新規アカウント作成</p>
	
	<dl>
		<dt>サロン名</dt>
		<dd>
			<input name="_name" type="text"
				class="not_null not_unique_char">
		</dd>
		<dt>メールアドレス</dt>
		<dd>
			<input name="e_mail" type="text"
				class="mail not_null not_unique_char">
		</dd>
		<dt>メールアドレス<span class="detail">*確認</span></dt>
		<dd>
			<input type="text"
				class="mail not_null not_unique_char">
		</dd>
		<dt>パスワード</dt>
		<dd>
			<input name="password" type="text"
				class="pass not_null not_unique_char">
		</dd>
		<dt>パスワード<span class="detail">*確認</span></dt>
		<dd>
			<input type="text"
				class="pass not_null not_unique_char">
		</dd>
	</dl>

<div id="agree" class="clearfix">
<input id="agreement" type="checkbox">
<label for="agreement">利用規約に同意する</label>
<a href="javascript:void(0)">利用規約</a>
</div>


<button type="submit" class="submit_button">スタート！</button>
</form>
</div>
</div>
</body>
</html>
