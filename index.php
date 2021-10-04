<?php


require_once 'PHPClass/RootModel.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta content="予約帳・売上管理・労務管理。面貸し美容室の全てをスマホで。しかも無料で。PCやタブレットはもう必要ありません。Orangeなら、スマホだけでサロン管理のすべてが完結します。スマホの画面サイズに最適化された予約帳は、スタッフがどこにいても簡単なアクセスを可能にします。" name="description">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="image/OrangeIcon_114.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/list.css" rel="stylesheet" type="text/css"/>
<link href="css/home.css" rel="stylesheet" type="text/css"/>
<link href="my_plugin/my_footer/my_footer.css" rel="stylesheet" type="text/css"/>
<link href="css/heading.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>

<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/overlay.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="js/url_converter.js"></script>
<script type="text/javascript" src="my_plugin/my_footer/my_footer.js"></script>

<script type="text/javascript">
//common.jsのセッションチェックをキャンセル
_sessionCheckCancel = true;
_visiter = null;

//契約に関するプロパティ
//_freeDays = '<?php echo RootModel::FREE_DAYS;?>';
_price = '<?php echo RootModel::STANDARD_PRICE;?>';
/*_today = '<?php echo $todayY."年".$todayM."月".$todayD."日(".$todayW.")";?>';
_freeLastDate = '<?php echo $fldY."年".$fldM."月".$fldD."日(".$fldW.")";?>';
_startDate = '<?php echo $stY."年".$stM."月".$stD."日(".$stW.")";?>';
_starMonthLast = '<?php echo $stY."年".$stM."月".$stMLastD."日(".$stMLastW.")";?>';
_stNextMonth = '<?php echo $stNextM;?>';
_stMChargeDays = '<?php echo $stMChargeDays;?>';
_stMPrice = '<?php echo $stMPrice;?>';*/
</script>

<title>Orange[オレンジ]|スマホで無料で面貸し美容室の予約帳・売上管理</title>
</head>

<body>
<!-- Facebook -->
<div id="fb-root"></div>
<script id="fb-script">
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";
	fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));
</script>
<!-- -------- -->
<!-- JAPAN WAY -->
<script type="text/javascript">var smnAdvertiserId = '00003064';</script><script type="text/javascript" src="//cd.ladsp.com/script/pixel.js"></script>
<!-- End JAPAN WAY -->

<div id="container">

<!-- --video----------------- -->
<video id="orange_video_1" controls poster="image/orange_splash.png" preload="none">
	<source src="video/OrangeMovie_1.mp4" type="video/mp4" />
	<source src="video/OrangeMovie_1.webm" type="video/webm"/>
	<source src="video/OrangeMovie_1.ogg" type="video/ogg"/>
	<p>動画を再生するには、videoタグがサポートされたブラウザが必要です。</p>
</video>
<!-- ------------------------ -->

<article id="home_top_area">

	<div id="center_box">

	<div class="home_center_box_contents">
		<!-- <h1>
			予約帳　売上管理
			<br>サロンの全てがスマートフォンに
		</h1> -->
		<h1>
			すべての面貸しサロンに
		</h1>
	</div>
	<div class="home_center_box_contents">
		<img class="logo" alt="Orangeロゴイメージ" src="image/orange_logo_4.png">
	</div>
	<div class="home_center_box_contents">
		<button type="button" class="admission" data-admission_button_type="other"></button>
	</div>
	<div class="home_center_box_contents">
		<div class="video_button_area clearfix">
			<img alt="" src="image/video_play_shadow.png">
			<p>ビデオを見る</p>
		</div>
	</div>

	</div>

</article>


<div id="home_main_area">


<article class="home_contents">
	<div class="contents_area">
		<h2>
			スマホでサロンを管理する
			<br><span class="f_16">「面貸し美容室」専用サロン管理システム</span>
			<br><strong>Orange（オレンジ）</strong>
		</h2>
	</div>
	<!-- <div class="contents_area">
		<h2>
			スマホでサロンの全てを管理
			<br>それが<strong>Orange（オレンジ）</strong>です
		</h2>
	</div>
	<img class="big_img" alt="" src="image/Orange_summary.png">
	<div class="contents_area">
		<div class="long_message_box">
			<p>Orangeなら、スマホだけでサロン管理のすべてが完結します。</p>
			<p>スマホの画面サイズに最適化された予約帳は、スタッフがどこにいても簡単なアクセスを可能にします。</p>
			<p>充実した売上管理機能で、サロンオーナーはいつでもサロンの状況を確認することができます。</p>
			<br>
			<p>もう、タブレットを持ち歩く必要はありません。</p>
		</div>
	</div> -->
</article>

<article class="home_contents clearfix" id="feature">
	<div class="contents_area">
		<h2>Orange（オレンジ）の３つの特徴</h2>
		<div class="clearfix">
		<div class="middle_contents flex">
			<div class="contents_area">
				<img alt="" src="image/smart_phone.png">
				<h3>スマホですべて管理</h3>
				<p>スマホに最適化されたインターフェイスがいつでもどこでも予約管理を可能にします。</p>
				<!-- <p>サロン全体の予約状況もスマホで一目瞭然。</p> -->
				<p>自由出勤な面貸しサロンにぴったりフィット。</p>
				<p>
					<a class="help_link" href="#feature_smartphone">詳しく見てみる</a>
				</p>
			</div>
		</div>
		<div class="middle_contents flex">
			<div class="contents_area">
				<img alt="" src="image/cloud_paper.png">
				<h3>労務管理はペーパーレス</h3>
				<p>サロンスタッフの売上明細や給与明細はすべてクラウドに保存。</p>
				<p>面倒な書類のやりとりは不要です。</p>
				<p>
					<a class="help_link" href="#feature_peparress">詳しく見てみる</a>
				</p>
			</div>
		</div>
		<div class="middle_contents flex">
			<div class="contents_area">
				<!-- <img alt="" src="image/cash.png">
				<h3>サロンにやさしい料金プラン</h3>
				<p>高額なシステム費用は必要ありません。</p>
				<p>無料で始められるOrangeはサロンの経営もお手伝いします。</p>
				<p>
					<a class="help_link" href="#feature_plan">詳しく見てみる</a>
				</p> -->
				<img alt="" src="image/zero_tag.png">
				<h3>無料でサロンにやさしい</h3>
				<p>高額なシステム費用は必要ありません。</p>
				<p>無料のOrangeはサロンの経営もお手伝いします。</p>
			</div>
		</div>
		<!-- <div class="middle_contents flex">
			<div class="contents_area">
				<img alt="" src="image/graph_orange.png">
				<h3>充実した売上管理機能</h3>
				<p>グラフと表で見る売上推移は、サロン管理をとてもシンプルにします。</p>
				<p>もちろんスマホで。</p>
				<p>
					<a class="help_link" href="#feature_report">詳しく見てみる</a>
				</p>
			</div>
		</div>
		<div class="middle_contents flex">
			<div class="contents_area">
				<img alt="" src="image/pay.png">
				<h3>様々な給与形態に対応</h3>
				<p>固定給制・歩合給制・完全歩合制など多様な給与・報酬形態にすべて対応しています。</p>
				<p>
					<a class="help_link" href="#feature_multipaytype">詳しく見てみる</a>
				</p>
			</div>
		</div> -->
		</div>
		<div class="vp_24">
			<button type="button" class="admission" data-admission_button_type="other"></button>
		</div>
	</div>
</article>


<article class="home_contents" id="feature_smartphone">
	<div class="contents_area">
		<h2>
			Orange（オレンジ）の特徴 その１
			<br><span style="color: #e87e04;">スマホですべて管理</span>
		</h2>
		<div class="long_message_box">
			<p>これまでスマホで実現できなかった「みやすい予約帳」がとてもシンプルで使いやすく構成されているので、サロンスタッフやサロン管理者様の予約帳へのアクセスがよりカンタンになります。</p>
			<p>サロンスタッフはいつでもスマホで予約管理ができるので、顧客への対応もスムーズになります。</p>
			<p>サロン管理者は、伝票処理・売上分析・スタッフ管理やレポート表示まですべてスマホで操作できるので、サロンの状況が常に手元で把握できるようになります。</p>
		</div>
		<div id="smartphone_view_images" class="clearfix">
			<h3>スマホビューイメージ</h3>
			<figure>
				<img alt="Orangeスマホビューイメージ" src="image/device/iphone_1.png">
				<figcaption>予約帳</figcaption>
				<a class="help_link f_small" href="help/index.html?visiter=salon&show_target=reserve_tutorial">予約帳操作ガイド→</a>
			</figure>
			<figure>
				<img alt="Orangeスマホビューイメージ" src="image/device/iphone_2.png">
				<figcaption>伝票リスト</figcaption>
			</figure>
			<figure>
				<img alt="Orangeスマホビューイメージ" src="image/device/iphone_4.png">
				<figcaption>月報</figcaption>
				<a class="help_link f_small" href="help/index.html?visiter=salon&show_target=monthly_report">月報操作ガイド→</a>
			</figure>
			<figure>
				<img alt="Orangeスマホビューイメージ" src="image/device/iphone_3.png">
				<figcaption>スタッフ管理</figcaption>
				<a class="help_link f_small" href="help/index.html?visiter=salon&show_target=staff_setting">スタッフ設定ガイド→</a>
			</figure>
		</div>



		<div class="item_box">
			<a id="pc_view_images_opener">more ▽</a>
		</div>
		<div id="pc_view_images" class="clearfix" style="display: none;">
			<h3>PC・タブレットビューイメージ</h3>
			<figure>
				<img alt="OrangePCビューイメージ" src="image/device/pc_1.png">
				<figcaption>予約帳</figcaption>
			</figure>
			<figure>
				<img alt="OrangePCビューイメージ" src="image/device/pc_2.png">
				<figcaption>伝票リスト</figcaption>
			</figure>
			<figure>
				<img alt="OrangePCビューイメージ" src="image/device/pc_3.png">
				<figcaption>スタッフレポート</figcaption>
				<a class="help_link f_small" href="help/index.html?visiter=salon&show_target=staff_report">スタッフレポートガイド→</a>
			</figure>
		</div>
	</div>
</article>
<article class="home_contents" id="feature_peparress">
	<div class="contents_area">
		<h2>
			Orange（オレンジ）の特徴 その２
			<br><span style="color: #e87e04;">労務管理はペーパーレス</span>
		</h2>
		<div class="long_message_box">
			<p>「報酬承認機能」と「支払い管理機能」を使えば労務管理がとてもスムーズになります。</p>
			<p>給与明細書や請求書をやりとりする必要はなくなります。</p>
		</div>
		<div class="vp_12">
			<h3 class="mh_left_border_nomal mh_left_border_color_theme_orange02 ta_left">報酬承認機能</h3>
			<figure class="vp_12">
				<img class="big_img" alt="" src="image/big_image/rem_check.png">
			</figure>
			<div class="long_message_box">
				<p>サロンスタッフは自分だけの売上明細や給与明細をいつでも確認することができます。</p>
				<p>給与明細の内容を確認して「承認ボタン」をタップするだけ。</p>
				<p>承認された明細の金額はサロン管理者の「支払い待ちリスト」に自動で追加されます。</p>
				<a class="help_link" href="help/index.html?visiter=staff&show_target=rem_specification">報酬承認ガイドを見る</a>
			</div>
		</div>
		<div class="vp_12">
			<h3 class="mh_left_border_nomal mh_left_border_color_theme_orange02 ta_left">支払い管理機能</h3>
			<figure class="vp_12">
				<img class="big_img" alt="" src="image/big_image/payment.png">
			</figure>
			<div class="long_message_box">
				<p>承認済み明細の支払い金額はサロン管理者の「支払い待ちリスト」にリストアップされます。</p>
				<p>振込が完了したら「支払い切り替えボタン」をタップするだけです。</p>
				<p>全てのスタッフの支払い状況がタップ１つで管理できます。</p>
				<a class="help_link" href="help/index.html?visiter=&show_target=payment">支払い管理ガイドを見る</a>
			</div>
		</div>
	</div>
</article>
<article class="home_contents" id="feature_plan">
	<div class="contents_area">
		<h2>
			Orange（オレンジ）の特徴 その３
			<br><span style="color: #e87e04;">無料でサロンにやさしい</span>
		</h2>
		<!-- <img class="big_img" alt="" src="image/free_or_1980.png"> -->
		<div class="ta_center">
			<p style="font-size: 100px; color: #aaa">¥0</p>
		</div>
		<div class="long_message_box">
			<p>Orangeの機能はすべて無料でお使いいただけます。</p>
			<!-- <p>Orangeの基本的な機能は無料プランでお使いいただけます。</p>
			<p>全ての機能が使えるプレミアムプランでも、サロンの経営を圧迫することはありません。</p>
			<p>契約期間もありません。プレミアムプランからはいつでも無料プランに変更できるので安心です。</p>
			<p>もちろん、サロンスタッフに料金が発生することもありません。</p> -->
		</div>

		<!-- <div id="plan_box_area" class="clearfix">
			<div class="plan_box border_radius_2">
				<div class="title free">
					無料プラン
					<p class="belt">0円</p>
				</div>
				<table class="price_table">
					<tbody>
						<tr>
							<th><span>使える</span>機能</th>
							<td>
								<ul>
									<li>予約帳</li>
									<li>伝票検索</li>
									<li>日報</li>
									<li>その他基本的な機能</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th><span>お試し</span>９０日</th>
							<td class="f_14">９０日間プレミアムプランの機能をお試しできます</td>
						</tr>
					</tbody>
				</table>
				<div class="button_area">
					<button type="button" class="admission" data-admission_button_type="free"></button>
				</div>
				<div style="padding-bottom: 12px; font-size: 14px;">
					<a class="help_link" href="plan_difference.php?plan=free">無料プランを詳しく見る</a>
				</div>
			</div>
			<div class="plan_box border_radius_2">
				<div class="title premium">
					プレミアムプラン
					<p class="belt">
						<span id="plan_price"></span>円
						<span class="f_small">/月(税込)</span>
					</p>
				</div>
				<table class="price_table">
					<tbody>
						<tr>
							<th><span>便利な</span>機能</th>
							<td>
								<ul>
									<li>月報</li>
									<li>スタッフレポート</li>
									<li>手当管理</li>
									<li>給与控除管理</li>
									<li>支払い管理</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th><span>お試し</span>９０日</th>
							<td class="f_14">９０日間無料でお試しできます</td>
						</tr>
					</tbody>
				</table>
				<div class="button_area">
					<button type="button" class="admission" data-admission_button_type="premium"></button>
				</div>
				<div style="padding-bottom: 12px; font-size: 14px;">
					<a class="help_link" href="plan_difference.php?plan=premium">プレミアムプランを詳しく見る</a>
				</div>
			</div>
		</div> -->
	</div>
</article>


<article class="home_contents" id="other_func">
	<div class="contents_area clearfix">
		<h2>
			Orange（オレンジ）のその他の機能
		</h2>
		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/High Priority-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">すぐに使える</h3>
				<p>面倒なインストールは不要です。</p>
				<p>ユーザー登録は「サロン名」「Eメール」「パスワード」だけ。</p>
			</div>
		</div>
		</div>
		<!-- <div class="middle_contents_small flex clearfix">
			<div class="img_box">
				<img alt="" src="image/OrangeIcon_slim.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">無料で使える</h3>
				<p>無料でお使いいただけます。</p>
				<p>さらに便利なプレミアムプランも低価格でご利用できます。</p>
				<p><a class="help_link" href="#plan">料金プランを見る</a></p>
			</div>
		</div> -->
		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Centre of Gravity-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">空席がわかる</h3>
				<p>席数ベースで管理された予約帳は、いつどこに空席があるのかが一目でわかります。</p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Search Property-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">高度な伝票検索</h3>
				<p>日付・担当者・会計金額・支払方法など、様々な条件で伝票を検索できます。</p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Cash Register-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">お会計もスムーズ</h3>
				<p>お会計時のレシートもOrangeが作成します。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=receipt_tutorial">お会計ガイドを見る</a></p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Positive Dynamic-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">充実した売上管理機能</h3>
				<p>グラフと表で見る売上推移は、サロン管理をとてもシンプルにします。</p>
				<p>もちろんスマホで。</p>
				<p><a class="help_link" href="tec_detail/report.html">詳しく見てみる</a></p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Receive Cash-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">様々な給与形態に対応</h3>
				<p>固定給制・歩合給制・完全歩合制など多様な給与・報酬形態にすべて対応しています。</p>
				<p><a class="help_link" href="tec_detail/multipaytype.html">詳しく見てみる</a></p>
			</div>
		</div>
		</div>


		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Settings 3-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">サロン独自のカスタム設定</h3>
				<p>メニュー詳細や店販商品などを自由にカスタムして、サロンの実状にフィットした管理を可能にします。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=menu_detail_setting">
				メニュー詳細設定ガイドを見る</a></p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=product_setting">
				商品設定ガイドを見る</a></p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Report Card-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">スタッフ別の売上明細</h3>
				<p>最新の状態の売上データや給与データを、いつでもチェックすることができます。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=staff_report">
				スタッフレポートガイドを見る</a></p>
				<p><a class="help_link" href="help/index.html?visiter=staff&show_target=sales_specification">
				売上明細ガイドを見る</a></p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Split Files-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">エリア設定機能</h3>
				<p>サロン内に複数フロアがある場合はエリアを分けて管理することができます。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=area_setting">
				エリア設定ガイドを見る</a></p>
			</div>
		</div>
		</div>

		<div class="middle_contents_small flex clearfix">
		<div class="hp_6">
			<div class="img_box">
				<img alt="" src="image/icon/Percentage-100.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">マルチ歩合設定機能</h3>
				<p>歩合を会計ごとに管理することも可能です。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=rem_setting">
				マルチ歩合設ガイドを見る</a></p>
			</div>
		</div>
		</div>
		<!-- <div class="middle_contents_small flex clearfix">
			<div class="img_box">
				<img alt="" src="image/OrangeIcon_slim.png">
			</div>
			<div class="str_box">
				<h3 class="ta_left">支払い管理</h3>
				<p>支払い管理機能は、顧問税理士さんとのやりとりをシンプルにします。</p>
				<p><a class="help_link" href="help/index.html?visiter=salon&show_target=payment">
				支払い管理ガイドを見る</a></p>
			</div>
		</div> -->
	</div>
	<button type="button" class="admission" data-admission_button_type="other" style="margin-top: 24px"></button>
</article>


<!-- <article class="home_contents clearfix" id="plan">
	<div class="contents_area">
		<h2>
			Orange（オレンジ）の料金プラン
		</h2>

		<div id="plan_box_area" class="clearfix">
		<div class="plan_box border_radius_2">
			<div class="title free">
				無料プラン
				<p class="belt">0円</p>
			</div>

			<table class="price_table">
				<tbody>
					<tr>
						<th><span>使える</span>機能</th>
						<td>
							<ul>
								<li>予約帳</li>
								<li>伝票検索</li>
								<li>日報</li>
								<li>その他基本的な機能</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th><span>お試し</span>９０日</th>
						<td class="f_14">９０日間プレミアムプランの機能をお試しできます</td>
					</tr>
				</tbody>
			</table>
			<div class="button_area">
				<button type="button" class="admission" data-admission_button_type="free"></button>
			</div>
			<div style="padding-bottom: 12px; font-size: 14px;">
				<a class="help_link" href="plan_difference.php?plan=free">料金プランを詳しく見る</a>
			</div>
		</div>
		<div class="plan_box border_radius_2">
			<div class="title premium">
				プレミアムプラン
				<p class="belt">
					<span id="plan_price"></span>円
					<span class="f_small">/月(税込)</span>
				</p>
			</div>

			<table class="price_table">
				<tbody>
					<tr>
						<th><span>便利な</span>機能</th>
						<td>
							<ul>
								<li>月報</li>
								<li>スタッフレポート</li>
								<li>手当管理</li>
								<li>給与控除管理</li>
								<li>支払い管理</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th><span>お試し</span>９０日</th>
						<td class="f_14">９０日間無料でお試しできます</td>
					</tr>
				</tbody>
			</table>
			<div class="button_area">
				<button type="button" class="admission" data-admission_button_type="premium"></button>
			</div>
			<div style="padding-bottom: 12px; font-size: 14px;">
				<a class="help_link" href="plan_difference.php?plan=premium">料金プランを詳しく見る</a>
			</div>
		</div>
		</div>
	</div>

</article> -->

<article class="home_contents">
	<div class="contents_area">
		<!-- <div class="home_contents_title">
			<span class="f_18">CommingSoon...</span>　グループ管理機能
		</div> -->
		<h2>
			<span class="f_18">CommingSoon...</span>　グループ管理機能
		</h2>
		<img class="big_img" alt="" src="image/Orange_group_summary.png">
		<h3>他店舗経営者様・サロンブランド管理者様へ</h3>
		<div class="long_message_box">
			<p>複数のサロンデータを統合管理できる「グループ管理機能」をご利用ください。</p>
			<p>グループ管理者アカウントを作成しグループ管理者としてログインすれば、すべてのグループサロンの統合データまたは個別データを管理できます。</p>
			<p>追加料金は一切かかりません。</p>
			<p class="f_small">*グループ管理機能は現在開発中です。導入までもう暫くお待ち下さい。</p>
		</div>
	</div>
</article>

<article class="home_contents">
	<div class="contents_area">
		<!-- <div class="home_contents_title">
		動作環境
		</div> -->
		<h2>動作環境</h2>
		<div class="ta_left">
			<p style="margin-top: 12px">[推奨ブラウザ]</p>
			<ul style="list-style-position: inside;">
				<li>最新版の
					<a style="display: inline;" class="help_link" href="https://www.google.co.jp/intl/ja/chrome/browser/desktop/index.html">Google Chrome</a></li>
				<li>最新版のSafari</li>
			</ul>
		</div>
	</div>
</article>

<article class="home_contents">
	<div class="contents_area">
		<!-- <div class="home_contents_title">

		</div> -->
		<h2>顧客管理は「へあまね」で</h2>
		<div class="home_center_contents_box" id="heamane">
			<img alt="" src="image/cut__7.png">
			<a href="https://itunes.apple.com/jp/app/heamane/id548574821?mt=8"
				style="text-decoration: underline; margin-top: 12px;">ダウンロード(iPhone)はこちら</a>
		</div>
	</div>
</article>

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
