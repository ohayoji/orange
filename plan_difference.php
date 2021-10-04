<?php
require_once 'PHPClass/PlanManager.php';
$planManager = new PlanManager();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/list.css" rel="stylesheet" type="text/css"/>
<link href="css/home.css" rel="stylesheet" type="text/css"/>
<link href="my_plugin/my_footer/my_footer.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>

<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/plan_difference.js"></script>
<script type="text/javascript" src="js/url_converter.js"></script>
<script type="text/javascript" src="my_plugin/my_footer/my_footer.js"></script>


<title>orange|料金プラン</title>

<script type="text/javascript">
//common.jsのセッションチェックをキャンセル
_sessionCheckCancel = true;
_visiter = null;

//契約に関するプロパティ
_planManager = <?php echo json_encode($planManager)?>;

//get
_planContents = '<?php echo $_GET["plan"];?>';
</script>

</head>
<body>
<div id="container">
<div id="home_main_area">


<div style="padding-top: 56px" class="home_contents">
	<div class="contents_area">
		<h1>Orange（オレンジ）料金プラン</h1>
		<!-- プラン別コンテンツ---------- -->
		<div class="item_box">
			<div id="plan_tab" class="clearfix">
				<ul>
					<li title="premium">プレミアムプラン</li>
					<li title="free">無料プラン</li>
				</ul>
			</div>
			
			<!-- 基本機能テンプレ -->
			<dl id="temp_base_enabled_func_box" class="enabled_func_box base_enabled_func_box hide">
				<dt style="background-color: #aaa;">基本的な機能</dt>
				<dd>
					<span class="f_small slategray"></span>
				</dd>
				<dd>予約帳</dd>
				<dd>お会計</dd>
				<dd>伝票検索</dd>
				<dd>日報</dd>
				<dd>スタッフ設定</dd>
				<dd>サロン設定
					<span class="f_small slategray">営業時間設定／エリア設定／メニュー設定／メニュー詳細設定／商品設定／伝票オプション設定／マルチ歩合設定</span>
				</dd>
				<dd>売上明細
					<span class="f_small slategray">スタッフアカウントでログイン時のみ</span>
				</dd>
			</dl>
			<!-- ------------- -->
			
			<!-- 無料プランコンテンツ--------------------------------- -->
			<div id="free_contents" class="hide plan_contents">
				<h2>無料プラン詳細</h2>
				
				<h3 class="heading04">ご利用料金</h3>
				<div class="contents_area plice">無料</div>
				
				<h3 class="heading04">無料プランで使える主な機能</h3>
				<div id="free_enabled_func_area" class="enabled_func_area clearfix contents_area">
					
				</div>
				
				<h3 class="heading04">★お試し期間９０日</h3>
				<div class="contents_area f_14">
					<ul style="list-style-position: inside;">
						<li class="orange">ユーザー登録後のお試し期間中（９０日）はプレミアムプランの全ての機能をお使いいただけます</li>
						<li>お試し期間中にOrangeにログイン後、「マイアカウント」ページよりクレジットカード情報を登録いただければ、お試し期間終了後にプレミアムプラン（<span class="premium_price"></span>円／月）にアップグレードできます</li>
						<li>クレジットカード情報を登録しなければ無料プランが継続されます</li>
						<li>無料プランからいつでもプレミアムプランにアップグレードすることができます</li>
					</ul>
				</div>
			</div>
			<!-- end 無料プランコンテンツ----------------------- -->
			
			
			
			<!-- プレミアムプランコンテンツ-------------------------------- -->
			<div id="premium_contents" class="hide plan_contents">
				<h2>プレミアムプラン詳細</h2>
				
				<h3 class="heading04">ご利用料金</h3>
				<div class="contents_area plice">月々 <span class="premium_price"></span> 円</div>
				
				<h3 class="heading04">プレミアムプランで使える主な機能</h3>
				<div id="premium_enabled_func_area" class="enabled_func_area clearfix contents_area">
					
					<dl class="enabled_func_box premium_enabled_func_box">
						<dt style="background-color: #f1c40f;">便利な機能（プレミアムプランのみ）</dt>
						<dd>予約ドラッグ移動</dd>
						<dd>月報</dd>
						<dd>スタッフレポート</dd>
						<dd>手当管理</dd>
						<dd>給与控除管理</dd>
						<dd>支払い管理</dd>
						<dd>サロン設定
							<span class="f_small slategray">勘定科目設定／ページロック機能</span>
						</dd>
					</dl>
				</div>
				
				<h3 class="heading04">★お試し期間９０日</h3>
				<div class="contents_area f_14">
					<ul style="list-style-position: inside;">
						<li class="orange">ユーザー登録後のお試し期間中（９０日）は無料でお使いいただけます</li>
						<li>お試し期間中にOrangeにログイン後、「マイアカウント」ページよりクレジットカード情報を登録することで、お試し期間終了後もプレミアムプラン（<span class="premium_price"></span>円／月）を継続してご利用いただけます</li>
						<li>お試し期間中にクレジットカード情報を登録しなければ、お試し期間終了後に自動的に無料プランに切り替わります</li>
						<li>一旦無料プランに切り替わった後でも、いつでもプレミアムプランにアップグレードすることができます</li>
					</ul>
				</div>
				
				<h3 class="heading04">お支払いシミュレーション</h3>
				<div class="item_box contents_area simuration">
				<table id="simuration">
					<caption>本日お申し込みの場合</caption>
					<thead>
						<tr class="span_price_header">
							<th>期間</th><th>金額</th>
						</tr>
					</thead>
					<thead class="span_title">
						<tr>
							<th colspan="2">お試し期間９０日（無料期間）</th>
						</tr>
					</thead>
					<tbody>
						<tr class="free_span">
							<td class="span">
								<div class="start_date"></div>
								<div class="end_date"></div>
							</td>
							<td class="price"></td>
						</tr>
					</tbody>
					<thead class="span_title">
						<tr>
							<th colspan="2">有料期間開始日</th>
						</tr>
					</thead>
					<tbody>
						<tr class="plan_start">
							<td class="span"></td><td class="price"></td>
						</tr>
					</tbody>
					<thead class="span_title">
						<tr>
							<th colspan="2" class="strong01">1ヶ月目の日割料金</th>
						</tr>
					</thead>
					<tbody>
						<tr class="first_month">
							<td class="span">
								<div class="start_date"></div>
								<div class="end_date"></div>
							</td>
							<td class="price"></td>
						</tr>
					</tbody>
					<thead class="span_title">
						<tr>
							<th colspan="2" class="strong01">２ヶ月目以降のお支払金額</th>
						</tr>
					</thead>
					<tbody>
						<tr class="second_month">
							<td class="span"></td><td class="price"></td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>
		</div>
		<!-- end プレミアムプランコンテンツ----------------------- -->
		
		
		<!-- -------------------------- -->
		
		
		
		
		
		
		<!-- 機能詳細---------------- -->
		<h3 class="heading04">プラン別機能詳細</h3>
		<div class="contents_area item_box">
		<table id="plan_def_table">
			<!-- <caption>プラン別機能詳細</caption> -->
			<colgroup width="42%"></colgroup>
			<colgroup width="32%"></colgroup>
			<colgroup width="26%"></colgroup>
			<thead>
				<tr>
					<th style="background-color: #cccccc">機能</th>
					<th style="background-color: #f1c40f">プレミアム</th>
					<th style="background-color: #aaa">無料</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>予約帳</th><td class="orange">○</td><td class="orange">○</td>
				</tr>
				<tr class="sub">
					<th>（ドラッグ移動）</th><td>可</td><td>不可</td>
				</tr>
				<tr>
					<th>伝票検索</th><td class="orange">○</td><td class="orange">○</td>
				</tr>
				<tr class="sub">
					<th>（検索範囲）</th><td>過去１年間</td><td>当月のみ</td>
				</tr>
				<tr>
					<th>日報</th><td class="orange">○</td><td class="orange">○</td>
				</tr>
				<tr class="sub">
					<th>（検索範囲）</th><td>過去１年間</td><td>当月・前月</td>
				</tr>
				
				<tr>
					<th>月報</th><td class="orange">○</td><td>-</td>
				</tr>
				<tr>
					<th>スタッフレポート</th><td class="orange">○</td><td>-</td>
				</tr>
				<!-- <tr>
					<th>スタッフ設定</th><td class="orange">○</td><td class="orange">○</td>
				</tr> -->
				<tr>
					<th>手当管理</th><td class="orange">○</td><td>-</td>
				</tr>
				<tr>
					<th>給与控除管理</th><td class="orange">○</td><td>-</td>
				</tr>
				<tr>
					<th>支払い管理</th><td class="orange">○</td><td>-</td>
				</tr>
				<tr>
					<th>売上明細</th><td class="orange">○</td><td class="orange">○</td>
				</tr>
				<tr class="sub">
					<th>（検索範囲）</th><td>過去１年間</td><td>当月・前月</td>
				</tr>
				<tr>
					<th>バナー広告</th><td>なし</td><td>あり</td>
				</tr>
			</tbody>
		</table>
		</div>
		<!-- -------------------------------- -->
	</div>
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