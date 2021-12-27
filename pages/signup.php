<?php
session_start();
require_once '../PHPClass/SignUpModel.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/signup.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<title>SignUp</title>
<?php new SignUpModel();?>

<style type="text/css">
<!--
#staff_contents, #signup_btn{
	display: none;
}
-->
</style> 

</head>

<body>
<div id="container_01">
<div class="center_box">

<h3>サインアップ</h3>

<form action="signup.php" method="post">

<dl>
<dt>
サロン　E-mail<br>
<span class="detail">*まずはEメールでサロンを検索してください</span>
</dt>
<dd>
<input type="text" id="salon_mail">
<a class="right_link" href="javascript:void(0)">サロンを検索</a>
</dd>
</dl>


<div id="staff_contents">

<dl>
<dt>サロン名</dt>
<dd id="searched_salon"></dd>
</dl>

<dl>
<dt>名前</dt>
<dd><select name="id" id=staff_selecter></select></dd>
</dl>

<dl>
<dt>E-mail</dt>
<dd>
<input id="mail1" type="text" name="e_mail" class="not_null not_unique_char">
</dd>
</dl>

<dl>
<dt>E-mail *確認用</dt>
<dd>
<input id="mail2" type="text" class="not_null not_unique_char">
</dd>
</dl>

<dl>
<dt>パスワード</dt>
<dd>
<input id="pass1" type="password" name="password" class="not_null not_unique_char">
</dd>
</dl>

<dl>
<dt>パスワード *確認用</dt>
<dd>
<input id="pass2" type="password" class="not_null not_unique_char">
</dd>
</dl>

</div>

<button id="signup_btn" type="submit" class="submit_button">登録</button>
</form>
</div>


</div>
</body>
</html>