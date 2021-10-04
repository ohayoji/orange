<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/home.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>


<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<!-- facebook plugin setup -->
<script type="text/javascript" src="js/facebook_setup.js"></script>
<script type="text/javascript" src="js/select_signup.js"></script>
<script type="text/javascript">
//common.jsのセッションチェックをキャンセル
_sessionCheckCancel = true;
_visiter = null;
_admissionButtonType = '<?php echo $_GET["admission_button_type"]?>';
_page = "signup";
</script>

<style type="text/css">
.center_box{
	background-color: rgba(255,255,255,0.8);
	padding: 24px;
	-webkit-border-radius: 8px;	/* Safari、Google Chrome */
    -moz-border-radius: 8px;	/* Firefox */
}
.center_box button{
	width: 100%;
}
button#normal{
	background-color: #ffab01;
}
#container{
	background-image: url('image/e05_gaus_dark-1.png');
}
</style>

</head>
<body>
<div id="container" class="bg_full">
<div id="main_area" class="after_orange_nav_area">
	<div class="center_box">
		<div class="vp_24">
			<button id="normal" class="submit_button"></button>
		</div>
		<div class="vp_24">
			<button id="facebook" class="facebook_button"></button>
		</div>
	</div>
</div>
</div>
</body>
</html>