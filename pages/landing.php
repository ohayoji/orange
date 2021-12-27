<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,user-scalable=no" />
<script type="text/javascript" src="../js/main.js"></script>
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/landing.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>

<title>「ハコぴた」|クラウド予約帳</title>
<script type="text/javascript">
function clickBtn(int) {
	switch (int) {
	case 0:
		window.location = "admission.php?plan=free";
		break;
	case 1:
		window.location = "admission.php?plan=small";
		break;
	case 2:
		window.location = "admission.php?plan=big";
	break;

	default:
		break;
	}
}
</script>
</head>
<body>
<div id="container">
<div id="top_band">
<div id="top_image">
<img alt="ハコぴたイメージ" src="../image/hocopita_image_1.png">
</div>
</div>



<div id="main_alea" class="landing">
<div class="contents">
<div class="middle_contents">
<p id="top_title">予約帳をオンラインで</p>
<p id="top_message">
クラウド予約帳「ハコぴた」なら、インストール不要ですぐにご利用いただけます。<br>
とかなんとかいろいろアピール文。。。
</p>
</div>
</div>


<div class="contents clearfix">
<div class="big_heading">
<p><span class="large bold">クラウド予約帳「ハコぴた」</span>
	<br><span class="large">の３つの特徴</span></p>
</div>
<div class="middle_contents flex">
<img alt="" src="../image/calender.png">
<p class="heading">空席をぴたっ！と埋めてくれます</p>
<p class="statement">
「ハコぴた」は全ての予約を席数で管理します。<br>サロンの混雑状況が一目で把握でき、席効率を最大にする事ができます。
	</p>
</div>
<div class="middle_contents flex">
<img alt="" src="../image/cloud.png">
<p class="heading">いつでもどこでも空席確認ができるので安心・便利！</p>
<p class="statement">
予約情報は全てクラウド管理。<br>どこでも予約状況の確認ができるので、忙しいオーナー様も安心です。
	</p>
</div>
<div class="middle_contents flex">
<img alt="" src="../image/smart_phone.png">
<p class="heading">スマホでもカンタン入力！</p>
<p class="statement">
予約を入れたい場所をタップするだけ。<br>表示タイプの切り替えで、スマホでもその日の予約が一目瞭然です。
	</p>
</div>
</div>

<div class="contents clearfix">
<div class="big_heading">
<p><span class="large bold">クラウド予約帳「ハコぴた」</span>
	<br><span class="large">の料金プラン</span>
	<span class="small">*1サロン様あたりの料金です</span>
	</p>
</div>
<div class="middle_contents flex">
<div class="plan_box border_radius_2">
<p class="title">無料プラン</p>
<p class="belt">無料</p>
<div class="statement">
<p>・サロン様のご負担なし</p>
<p>・面倒なインストール不要</p>
<p></p>
<a onclick="clickBtn(0)" href="javascript:void(0)" class="submit_button">
		いますぐ無料で試してみる</a>
</div>
</div>
</div>

<div class="middle_contents flex">
<div class="plan_box border_radius_2">
<p class="title">通常プラン</p>
<p class="belt">2,480円
<span class="medium">/月(税込)</span>
</p>
<div class="statement">
<p>・全ての機能をお使いいただけます</p>
<p>・面倒なインストール不要</p>
<p></p>
<a onclick="clickBtn(1)" href="javascript:void(0)" class="submit_button">
		いますぐ無料で試してみる</a>
</div>
</div>
</div>

<div class="middle_contents flex">
<div class="plan_box border_radius_2">
<p class="title">法人プラン</p>
<p class="belt">1,980円
<span class="medium">/月(税込)</span>
</p>
<div class="statement">
<p>・１店舗あたりがお得になります</p>
<p>・全ての機能をお使いいただけます</p>
<p>・面倒なインストール不要</p>
<a onclick="clickBtn(2)" href="javascript:void(0)" class="submit_button">
		いますぐ無料で試してみる</a>
</div>
</div>
</div>
</div>

</div>

</div>
<script type="text/javascript">
document.getElementById("main_alea").style.fontFamily = "Verdana, Arial, Helvetica, sans-serif";
</script>
</body>
</html>