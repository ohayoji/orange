<?php
if(!isset($_SESSION)){
 session_start();
}
require_once '../PHPClass/LoginModel.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../my_plugin/orange_nav/orange_nav.css" rel="stylesheet" type="text/css"/>
<!-- <link href="../css/home.css" rel="stylesheet" type="text/css"/> -->
<link href="../my_plugin/my_footer/my_footer.css" rel="stylesheet" type="text/css"/>

<script src="../js/jquery-2.1.1.min.js"></script>
<!-- facebook plugin setup -->
<script type="text/javascript" src="../js/facebook_setup.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../my_plugin/orange_nav/orange_nav.js"></script>
<script type="text/javascript" src="../js/login.js"></script>
<script type="text/javascript" src="../js/url_converter.js"></script>
<script type="text/javascript" src="../my_plugin/my_footer/my_footer.js"></script>
<script type="text/javascript">
	_page = "login";
</script>


<title>予約帳</title>
<?php $model = new LoginModel();?>

<style type="text/css">
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
/*--bace.cssキャンセル--------------*/
*{
	font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
	color: #637688;
}
/*----------------------*/
#login_box{
	width: 270px;
	margin: auto;
	padding: 48px 24px 100px;

}
#login_box *:not(input){
	font-family: mplus;
}
#login_table{
	width: 100%;
}
#login_table tr{
	height: 40px;
}
#login_table input {
	width: 100%;
	height: 100%;
	border: 1px solid #cccccc;
	padding: 4px 0;
}
</style>
</head>
<body>

<div id="container">
<div id="main_area" class="after_orange_nav_area">
	<div id="login_box" class="clearfix">
 		<form action="login.php" method="post">
   		<table id="login_table">
    			<caption class="f_24" style="margin-bottom: 24px; border-bottom-color: #637688;">Login</caption>
    			<colgroup width="25%"></colgroup>
    			<colgroup></colgroup>
    			<tr>
     			<td class="ta_right f_18">E-mail</td>
     			<td><input type="text" name="e_mail" class="not_null not_unique_char"></td>
    			</tr>
    			<tr>
     			<td class="ta_right f_18">Pass</td>
     			<td><input type="password" name="password" class="not_null not_unique_char"></td>
    			</tr>
   		</table>
   		<div class="button_box">
			<button type="submit" class="login_button">ログイン</button>
   		</div>
  		</form>
  		<!-- <button id="facebook" class="facebook_button">
  				<img id="logo" alt="" src="../image/FB-f-Logo__white_29.png">
  				ログイン
  		</button> -->
  		<!-- <div class="fb-login-button" data-max-rows="1" data-size="" data-show-faces="false" data-auto-logout-link="false" data-scope="public_profile,email" onlogin="checkLoginState();"></div> -->

  		<a class="right_link vp_24" href="../password_reissue.php">パスワード再発行</a>
 	</div>
</div>

	<!-- サイトラベル -->
	<div id="site_label" class="footer_contents contents_area">
		<script type="text/javascript" lang="JavaScript" src="https://trusted-web-seal.cybertrust.ne.jp/seal/getScript?host_name=orange01.jp&amp;type=45&amp;svc=4&amp;cmid=2012706"></script>
	</div>
	<!-- ----------- -->

</div>

</body>
</html>
