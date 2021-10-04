<?php
if(!isset($_SESSION)){
 session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />

<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/home.css" rel="stylesheet" type="text/css"/>
<link href="my_plugin/my_footer/my_footer.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>

<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/plan_downgrade.js"></script>
<script type="text/javascript" src="js/url_converter.js"></script>
<script type="text/javascript" src="my_plugin/my_footer/my_footer.js"></script>


<title>プランダウングレード</title>
<script type="text/javascript">
var _salon = <?php echo json_encode($_SESSION["salon"])?>;
</script>
</head>

<body>
<div id="container">
<div id="main_alea" class="after_orange_nav_area">
	<div class="contents_area">
		<div class="item_box">
			<div class="ta_center">
				<p><span id="salon_name"></span>様</p>
				<p>本当に無料プランにダウングレードしますか？</p>
			</div>
			<p>
				無料プランにダウングレードするといくつかの機能が使えなくなります。
				<a target="_blank" style="display: inline;" class="right_link" href="plan_difference.php">→使えなくなる機能を見る</a>
			</p>
			<p>
				ダウングレードされた場合でも、すでに課金済みの月額利用料金または日割料金は返金されません。
			</p>
			<div class="clearfix">
			<div class="button_box parallel">
				<input id="no_downgrade" type="button" class="submit_button" value="変更しない">
			</div>
			<div class="button_box parallel">
				<input id="downgrade" type="button" class="delete_button" value="ダウングレード">
			</div>
			</div>
		</div>
	</div>
</div>
<!-- ----footer用コンテンツ---- -->
<!-- サイトラベル -->
<div id="site_label" class="footer_contents contents_area">
	<script type="text/javascript" lang="JavaScript" src="https://trusted-web-seal.cybertrust.ne.jp/seal/getScript?host_name=orange01.jp&amp;type=45&amp;svc=4&amp;cmid=2012706"></script>
</div>
<!-- ------------------------------ -->
</div>
</body>
</html>