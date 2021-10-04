<?php
require_once '../PHPClass/InitSettingModel.php';
$model = new InitSettingModel();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="../image/4cube.png" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />

<!-- css -->
<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/list.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>

<!-- plugins -->
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../plugin/jquery-ui-1.11-2.2/jquery-ui.js"></script>
<!-- 改造版jquery.ui.touch-punch.js -->
<script type="text/javascript" src="../plugin/js/jquery.ui.touch-punch.js"></script>
<link href="../plugin/jquery-ui-1.11-2.2/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<!-- 改造版jquery.bxslider.js -->
<script type="text/javascript" src="../plugin/js/jquery.bxslider.js"></script>
<link href="../plugin/css/jquery.bxslider.css" rel="stylesheet" type="text/css"/>

<!-- 必要ファイル -->
<!-- TODO session管理無視のため_sessionCheckCancelに値を入れる（開発用） -->
<script>_sessionCheckCancel = "test";</script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/string_check.js"></script>

<!-- page controller -->
<script type="text/javascript" src="../js/init_setting.js"></script>

<title>初期設定</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
var _today = <?php echo json_encode($model->_todaySQLStr)?>;
/*---------------------------*/

var _salonId = <?php echo $_SESSION["salon"]["id"]?>;
var _areas = <?php echo json_encode($model->areas)?>;
var _menus = <?php echo json_encode($model->menus)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');

//スライドのページ数
var _totalSlide = parseInt('<?php echo initSettingModel::_TOTAL_SLIDE?>');
//var _pageNo = parseInt('<?php echo $_GET['initFlag']?>') - 1;
var _initFlag = parseInt('<?php echo $_GET['initFlag']?>') - 1;
//var _nextAuth = false;
var _nextAuth = true;

</script>

<style type="text/css">
.bx-wrapper{
margin-bottom: 36px;
}
.init_page_title{
padding: 12px;
text-align: center;
}
.init_page_contents{
width: 96%;
margin: auto;
}
.init_page_detail{
padding: 12px;
font-size: 12px;
text-align: center;
}
#page0, #page4{
color: #e67e22;
}
#usg_menu{
  margin: auto;
  width: 248px;
}
.narrow_group_list_area dl dt{
background-color: white;
color: #e67e22;
}
.outside{
padding: 12px 24px;
}
.outside #slider-next{
float: right;
}
.outside #slider-prev{
float: left;
}
</style>
</head>

<body id="body">
	<div id="container">
		<!--<div id="main_alea">  -->
			<ul class="bxslider">
  				<li id="page0">
  					<div class="init_page_title" style="font-size: 20px;">ようこそ Orange へ</div>
  					<div class="init_page_title">ほんの少しの初期設定をしましょう</div>
  				</li>
 				<li id="page1">
 					<div class="init_page_title">メニューを設定しましょう</div>
 					<div class="init_page_detail">
 						<p>「メニュー設定」からいつでも変更できます</p>
 					</div>
 					<div class="init_page_contents">
 						<div class="clearfix" id="usg_menu"></div>
 					</div>
 				</li>
				<li id="page2">
					<div class="init_page_title">メニュー詳細を設定しましょう</div>
					<div class="init_page_detail">
						<p>「メニュー詳細設定」からいつでも変更できます</p>
						<p>後で設定する場合はスキップしてください</p>
					</div>
					<div class="init_page_contents">
						<div id="usg_m_detail" class="narrow_group_list_area">
						<dl>
							<!-- テンプレ -------------------------->
							<dt id="temp_md_menu_dt" style="display: none;"></dt>
							<dd id="temp_md_menu_dd" style="display: none;">
								<table class="list_table">
									<thead>
										<tr class="slategray">
											<th>詳細名</th><th>金額</th><th></th><th></th>
										</tr>
									</thead>
									<tbody>
										<!-- trテンプレ -->
										<tr class="temp_md_tr" style="display: none;">
											<td class="md_name">
												<div class="selected_icon">
													<img alt="" src="../image/label_star.png" class="on" style="display: none;">
													<img alt="" src="../image/label_star_gray.png" class="off">
												</div>
												<input type="text" class="_name faint not_null not_unique_char">
											</td>
											<td>
												<input type="text" class="price faint narrow02 not_null not_unique_char only_num chkcode">
											</td>
											<td class="delete_cell "></td>
											<td class="sort_cell">
												<div>&#9776;</div>
											</td>
										</tr>
										<!-- -------------- -->
									</tbody>
								</table>
								<img alt="" src="../image/plus_2.png" class="right_icon add_md">
							</dd>
							<!-- ---------------------------- -->
						</dl>
						</div>
					</div>
					
				</li>
				<!-- <li id="page3">スタッフ設定をしましょう
					<div id="staff_set" class="clearfix">
						<table id="staff_table" class="list_table">
							<thead>
								<tr class="slategray"><th>スタッフ名</th><th>アイコン表示名</th></tr>
							</thead>
							<tbody>
								<tr class="staff_foam">
									<td> <input title="_name" class="faint not_null not_unique_char" data-sid="1"></td>
									<td> <input title="icon" class="faint narrow02 not_null not_unique_char" data-sid="1"></td>
								</tr>
							</tbody>
						</table>
						<div>
							<img alt="" id="add_staff" src="../image/plus_2.png" class="right_icon">
						</div>
					</div>
				</li> -->
				<li id="page3">
					<div class="init_page_title">エリアを設定しましょう</div>
					<div class="init_page_detail">
						<p>「エリア設定」からいつでも変更できます</p>
						<p>エリアが１つでよければ、サロン名または「メインフロア」とネーミングしましょう</p>
						<p>あと少しです！</p>
					</div>
					<div class="init_page_contents">
						<div id="area_set" class="clearfix">
							<table id="area_sortable" class="list_table">
								<thead>
									<tr class="slategray"><th>エリア名</th><th>席数</th><th></th><th></th></tr>
								</thead>
							</table>
							<div>
								<img alt="" id="add_area" src="../image/plus_2.png" class="right_icon">
							</div>
						</div>
					</div>
				</li>
				<li id="page4">
					<div class="init_page_title">おめでとうございます！</div>
					<div class="init_page_title">初期設定が完了しました</div>
					<div class="init_page_title">
						<a style="text-decoration: underline;" href="./reserve.php">Orange をお楽しみください</a>				
					</div>
				</li>
			</ul>
			<div class="outside clearfix">
  				<span id="slider-next"></span>
  				<span id="slider-prev"></span>
			</div>
		<!-- </div> -->
	</div>
</body>

</html>