<?php
if(!isset($_SESSION)){
 session_start();
}
require_once __DIR__.'/../PHPClass/SalonSettingModel.php';
$model = new SalonSettingModel();
//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/common.js?ver=20190114"></script>
<script type="text/javascript" src="../js/salon_setting.js?ver=20180826"></script>
<script type="text/javascript" src="../js/string_check.js"></script>
<script type="text/javascript" src="../js/plan_manager.js"></script>

<link href="../css/base.css" rel="stylesheet" type="text/css"/>
<link href="../css/items.css" rel="stylesheet" type="text/css"/>
<link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>
<link href="../css/list.css" rel="stylesheet" type="text/css"/>
<link href="../css/layout.css" rel="stylesheet" type="text/css"/>
<!-- plugins -->
<link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
<link href="../plugin/css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet"
      media="all and (min-width: 768px)" />
<script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
<script type="text/javascript" src="../plugin/jquery-ui-1.11-2.2/jquery-ui.js"></script>
<!-- 改造版jquery.ui.touch-punch.js -->
<script type="text/javascript" src="../plugin/js/jquery.ui.touch-punch.js"></script>
<link href="../plugin/jquery-ui-1.11-2.2/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<!-- ------- -->
<!-- my_plugins -->
<link href="../css/right_slide.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/right_slide.js"></script>
<script type="text/javascript" src="../js/overlay.js"></script>
<!-- ------- -->

<title>サロン設定</title>

<script type="text/javascript">
/*--RootModelプロパティ-------*/
var _visiter = <?php echo json_encode($model->_visiter)?>;
var _postName = <?php echo json_encode($model->_postName)?>;
var _personName = <?php echo json_encode($model->_personName)?>;
var _today = <?php echo json_encode($model->_todaySQLStr)?>;
/*---------------------------*/
/*--planManagerプロパティ------*/
var _planManager = <?php echo json_encode($model->planManager)?>;
/*---------------------------*/
var _salonId = <?php echo $_SESSION["salon"]["id"]?>;
var _salonStatus = <?php echo json_encode($model->salonStatus)?>;
var _hours = <?php echo json_encode($model->hours)?>;
var _minutes = <?php echo json_encode($model->minutes)?>;
var _menus = <?php echo json_encode($model->menus)?>;
var _products = <?php echo json_encode($model->products)?>;
var _recEntries = <?php echo json_encode($model->receiptEntries)?>;
var _areas = <?php echo json_encode($model->areas)?>;
var _subjects = <?php echo json_encode($model->subjects)?>;
var _tecpp = <?php echo json_encode($model->tecPP)?>;
var _propp = <?php echo json_encode($model->proPP)?>;
var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
var _pagesForSalon = $._getNavigation();
var _password = JSON.parse('<?php echo json_encode($_SESSION["salon"]["password"])?>');

jQuery(function ($) {
	//mmenuの設定（ここでやらないとうまく動作しない）
	$.mmenuSetting();

	//プランによるページアクセス制限@plan_manager.js
	$.limitatePageAccess();
});


</script>

<style type="text/css">
<!--以下の内容はitems.cssに移動した。 -->
<!--
.time_select_box{
text-align: right;
padding: 4px;
}
.icon_box{
width: 25%;
float: right;
}
.icon_box img{
float: right;
}
/*--rem_infomation--*/
.rem_infomation{
padding: 24px;
border-bottom: 1px solid #cccccc;
background-color: #f2f2f2;
}
.rem_infomation .chkbx{
text-align: right;
margin-bottom: 8px;
}
.rem_infomation .guide_box p{
padding: 2px 0;
}
/*-------------------*/
#right_slide .list_table,
#right_slide .narrow_list_area{
padding-top: 12px;
}
#usg_menu{
padding: 12px;
}
#usg_menu .menu_icon{
padding: 6px;
}
/*--table--*/
.list_table{
width: 100%;
border-collapse: collapse;
border-spacing: 0;
text-align: center;
}
.list_table td{
border-bottom: 1px solid #cccccc;
padding: 12px 8px;
}
#pro_setting table input._name,
#usg_m_detail table input._name{
width: 128px;
}
#usg_m_detail td.md_name{
position: relative;
}
#usg_m_detail .selected_icon{
position: absolute;
top: 0;
left: 0;
height: 28px;
width: 28px;
}
#usg_m_detail .selected_icon img{
width: 100%;
}
#usg_m_detail .selected_icon img.off{

}

/*---------*/
@font-face {
	font-family: mplus;
	src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
-->
</style>

</head>
<body>
<div id="wrap">
<div id="container">

<div id="right_slide">

<!-- 内部コンテンツ ------------------------>
<div style="display: none;" class="clearfix" id="usg_menu"></div>


<div style="display: none;" id="usg_m_detail" class="narrow_group_list_area">
	<dl>
		<!-- テンプレ -------------------------->
		<dt id="temp_md_menu_dt" style="display: none;">
		</dt>
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
						<td class="delete_cell ">
						</td>
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

<div style="display: none;" id="pro_setting" class="clearfix">
	<table class="list_table">
		<thead>
		<tr class="slategray">
			<th>商品名</th><th>金額</th><th></th><th></th>
		</tr>
		</thead>
		<tbody>
		<!-- trテンプレ -->
		<tr id="temp_pro_setting" style="display: none;">
			<td>
				<input type="text" name="_name" class="_name faint not_null not_unique_char">
			</td>
			<td>
				<input type="text" name="price" class="price faint narrow02 not_null not_unique_char only_num chkcode">
			</td>
			<td class=" delete_cell">
			</td>
			<td class="sort_cell">
				<div>&#9776;</div>
			</td>
		</tr>
		<!-- -------------- -->
		</tbody>
	</table>
	<img alt="" src="../image/plus_2.png" class="right_icon" id="add_pro">
</div>


<div style="display: none;" id="usg_rec_ent" class="narrow_list_area">
	<dl>
		<!-- テンプレ -->
		<dd id="temp_dd_usg_rec_ent" style="display: none;">
			<div class="segment02 clearfix">
				<div class="seg_contents ent_name"></div>
				<div class="seg_contents">
					<input type="checkbox">
				</div>
			</div>
		</dd>
		<!-- -------- -->
	</dl>
</div>

<div style="display: none;" id="area_set" class="clearfix">
<table id="area_sortable" class="list_table">
<thead>
<tr class="slategray"><th>エリア名</th><th>席数</th><th></th><th></th></tr>
</thead>
</table>
<div>
<img alt="" id="add_area" src="../image/plus_2.png" class="right_icon">
</div>
</div>


<div style="display: none;" id="usg_sub" class="narrow_list_area">
<dl>
		<!-- テンプレ -->
		<dd id="temp_dd_usg_sub" style="display: none;">
			<div class="segment02 clearfix">
				<div class="seg_contents sub_name"></div>
				<div class="seg_contents">
					<input type="checkbox">
				</div>
			</div>
		</dd>
		<!-- -------- -->
	</dl>
</div>

<div style="display: none;" id="tec_rem" class="clearfix">
	<div class="rem_infomation">
		<p><input type="checkbox" id="tec_rem_valid"> マルチ歩合を有効にする</p>
		<!-- -<p class="chkbx"><input type="checkbox" id="tec_rem_valid"></p> -->
		<div class="guide_box">
			<p>
			*伝票ごとに技術歩合率を切り替える必要がある場合はチェックしてください
			</p>
			<p>
			*チェックを入れると、伝票画面に「歩合切り替えセレクタ」が表示され、伝票ごとの歩合設定が可能になります
			<br>（「スタッフ設定」で技術歩合率が設定されていないスタッフのみ有効）
			</p>
			<p>
			*「スタッフ設定」で技術歩合率が設定されたスタッフの場合はその歩合率が優先され、伝票ごとの歩合率切り替えができなくなります。
			</p>
		</div>
	</div>

	<table id="tec_pp_list" class="list_table">
		<tr class="slategray"><td>歩合パターン</td><td>選択済</td><td></td></tr>
	</table>
	<img alt="" id="add_tec_rem" src="../image/plus_2.png" class="right_icon">
</div>


<div style="display: none;" id="pro_rem" class="clearfix">
	<div class="rem_infomation">
		<p><input type="checkbox" id="pro_rem_valid"> マルチ歩合を有効にする</p>
		<!-- <p class="chkbx"><input type="checkbox" id="pro_rem_valid"></p> -->
		<div class="guide_box">
			<p>
			*伝票ごとに商品歩合率を切り替える必要がある場合はチェックしてください
			</p>
			<p>
			*チェックを入れると、伝票画面に「歩合切り替えセレクタ」が表示され、伝票ごとの歩合設定が可能になります
			</p>
		</div>
	</div>
	<table id="pro_pp_list" class="list_table">
		<tr class="slategray"><td>歩合パターン</td><td>選択済</td><td></td></tr>
	</table>
	<img alt="" id="add_pro_rem" src="../image/plus_2.png" class="right_icon">
</div>


<div style="display: none;" id="page_lock" class="narrow_list_area">
	<div class="segment02 clearfix slategray" style="border-bottom: 1px solid #cccccc;">
		<div class="seg_contents">ページ</div>
		<div class="seg_contents">ロック</div>
	</div>
 	<dl>
 		<!-- テンプレ -->
		<dd id="temp_dd_page_lock" style="display: none;">
			<div class="segment02 clearfix">
				<div class="seg_contents page_name"></div>
				<div class="seg_contents">
					<input type="checkbox">
				</div>
			</div>
		</dd>
		<!-- -------- -->
 	</dl>
</div>




<!-- ---------------------------------- -->

</div>

<script type="text/javascript">
$._createHeader();
$._createNavigation();
</script>

<div id="main_alea">

<div id="salon_setting" class="group_list_area">

<!-- list_accessoryテンプレ -->
<div id="list_acc_temp" class="list_accessory" style="display: none;">
	<img alt="" src="../image/Forward-100.png">
</div>
<!-- --------------------- -->
	<dl>
		<dt>基本設定</dt>
		<dd class="middle_dd">
			<ul>
				<li class="total">
					<p>営業時間</p>
					<div id="biz_times" class="time_select_box">
						<span id="biz_start">
						<select id="bsh" class="status time hour" name="start"></select>
						：<select id="bsm" class="status time"></select>
						</span>
						~
						<span id="biz_end">
						<select id="beh" class="status time hour" name="end"></select>
						：<select id="bem" class="status time"></select>
						</span>
					</div>
				</li>
				<li class="total">
					<p>予約帳時間幅</p>
					<div id="res_times" class="time_select_box">
						<span id="reserv_start">
						<select id="rsh" class="status time hour" name="start"></select>
						：<select id="rsm" class="status time"></select>
						</span>
						~
						<span id="reserv_end">
						<select id="reh" class="status time hour" name="end"></select>
						：<select id="rem" class="status time"></select>
						</span>
					</div>
				</li>
				<li id="seats_num_area" class="total"  style="display:none;">
					<p>サロンの席数</p>
					<div id="seats_num_area" class="time_select_box">
						<input id="seats_num" title="seats" type="text" class="faint narrow01 not_null not_unique_char only_num">
					</div>
				</li>
			</ul>
		</dd>

		<dt>伝票設定</dt>
		<dd class="accessory right_one">
			<ul>
				<li class="detail_contents" data-name="usg_menu" title="メニュー設定">
					<div class="list_body">メニュー</div>
				</li>
				<li class="detail_contents" data-name="usg_m_detail" title="メニュー詳細設定">
					<div class="list_body">メニュー詳細</div>
				</li>
				<li class="detail_contents" data-name="pro_setting" title="商品設定">
					<div class="list_body">商品</div>
				</li>
				<li class="detail_contents" data-name="usg_rec_ent" title="オプション項目">
					<div class="list_body">オプション項目</div>
				</li>
			</ul>
		</dd>
		<!-- <dt>その他の設定</dt>
		<dd class="accessory right_one">
			<ul>
				<li class="detail_contents" data-name="tec_rem" title="技術歩合設定">
					<div class="list_body">技術歩合</div>
				</li>
				<li class="detail_contents" data-name="pro_rem" title="商品歩合設定">
					<div class="list_body">商品歩合</div>
				</li>
				<li id="salon_set_usg_sub" class="detail_contents" data-name="usg_sub" title="勘定科目設定">
					<div class="list_body">勘定科目</div>
				</li>
			</ul>
		</dd> -->
		<dt>ページロック</dt>
		<dd class="accessory right_one">
			<ul>
				<li id="salon_set_page_lock" class="detail_contents" data-name="page_lock" title="ページロック設定">
					<div class="list_body">ページロック</div>
				</li>
			</ul>
		</dd>
		<dt id="option_dt">オプション <span id="option_disp_changer" class="slategray">▼</span></dt>
		<dd class="accessory right_one hide" id="option_dd">
			<ul>
				<li class="detail_contents" data-name="area_set" title="エリア設定">
					<div class="list_body">
						マルチエリア設定
						<span id="area_list_detail" class="list_detail"></span>
					</div>
				</li>
				<li class="detail_contents" data-name="tec_rem" title="マルチ歩合（技術）">
					<div class="list_body">
						マルチ歩合設定
						<span class="f_14 slategray">（技術歩合）</span>
						<span id="tec_rem_list_detail" class="list_detail"></span>
					</div>
				</li>
				<li class="detail_contents" data-name="pro_rem" title="マルチ歩合（商品）">
					<div class="list_body">
						マルチ歩合設定
						<span class="f_14 slategray">（商品歩合）</span>
						<span id="pro_rem_list_detail" class="list_detail"></span>
					</div>
				</li>
				<li id="salon_set_usg_sub" class="detail_contents" data-name="usg_sub" title="勘定科目設定">
					<div class="list_body">勘定科目</div>
				</li>
			</ul>
		</dd>
	</dl>
</div>

</div>
</div>
</div>
</body>
</html>
