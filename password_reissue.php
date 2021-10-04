<?php
require_once 'PHPClass/PasswordReissueModel.php';
$model = new PasswordReissueModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />

<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/string_check.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/password_reissue.js"></script>

<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/admission.css" rel="stylesheet" type="text/css"/>

<title>パスワード再発行</title>

<style type="text/css">
.center_box{
width: 280px;
}
ul{
list-style: none;
padding: 12px;
}
ul, #admission_form{
background-color: #f2f2f2;
}
.salon_e_mail{
display: none;
}
p.title{
margin-top: 24px;
}
</style>

<script type="text/javascript">
var _mode = '<?php echo $model->mode?>';
var _userType = <?php echo json_encode($model->userType)?>;
var _id = <?php echo json_encode($model->id)?>;
</script>

</head>

<body id="body">
<div id="container">
<div class="center_box">
	
	<h3>パスワード再発行</h3>
	<div id="step1">
	<p class="title">1.あなたのユーザー属性を選択してください</p>
	<ul>
		<li>
			<input type="radio" name="category" value="salon" id="salon">
			<label for="salon">サロン管理者</label>
		</li>
		<li>
			<input type="radio" name="category" value="staff" id="staff">
			<label for="staff">サロンスタッフ</label>
		</li>
		<li>
			<input type="radio" name="category" value="company" id="company">
			<label for="company">グループ管理者</label>
		</li>
	</ul>
	</div>
	
	<div id="step2">
	<p class="title">2.すべての項目を入力し「送信」ボタンを押してください</p>
	<!-- サロンメールinput -->
	<input name="salon_e_mail" type="text"
		class="mail salon_e_mail">
	<!--------------------->

	<!-- <form id="admission_form" action="password_reissue.php" method="post"> -->
	<form action="password_reissue.php" method="post">
	<!-- <div id="admission_form"> -->
		<input type="hidden" name="mode">
		<input type="hidden" name="id">
		<input type="hidden" name="user_type">
		<dl>
			<dt>登録済みのメールアドレス</dt>
			<dd>
				<input name="e_mail" type="text"
					class="mail not_null not_unique_char">
			</dd>
			<dt class="salon_e_mail">サロンのメールアドレス</dt>
			<dd class="salon_e_mail">
			</dd>
		</dl>
			
		<button type="submit" class="submit_button">送信</button>
	</form>
	<!-- </div> -->
	</div>

	<div id="step3">
	<p class="title">2.新しいパスワードを入力し「再発行」ボタンを押してください</p>	

	<!-- <form id="admission_form" action="password_reissue.php" method="post"> -->
	<form action="password_reissue.php" method="post">
		<input type="hidden" name="mode">
		<input type="hidden" name="id">
		<input type="hidden" name="user_type">
		<dl>
			<dt>新しいパスワード</dt>
			<dd>
				<input name="password" type="password"
					class="pass not_null not_unique_char not_0_start">
			</dd>
			<dt>新しいパスワード<span class="detail">*確認</span></dt>
			<dd>
				<input type="password"
					class="pass not_null not_unique_char not_0_start">
			</dd>
		</dl>
			
		<button type="submit" class="submit_button">再発行</button>
	</form>
	</div>
</div>
</div>
</body>
</html>