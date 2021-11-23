<?php
require_once __DIR__.'/../PHPClass/ReserveModel.php';
require_once __DIR__.'/../PHPClass/CompanySettingModel.php';
$rootModel = new RootModel();
if($rootModel->_visiter == "company"){

  $companySettingModel = new CompanySettingModel();
  if(!$_SESSION["res_condition"]){
    $_id = $companySettingModel->salonInfo[0]["id"];
    $_SESSION["salon"]["id"] = $_id;
  }else{
    $_id = $_SESSION["res_condition"]["salon"];
    $_SESSION["salon"]["id"] = $_id;
  }
}
$model = new ReserveModel();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="apple-touch-icon" href="../image/4cube.png" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
  <link href="../css/base.css" rel="stylesheet" type="text/css"/>
  <link href="../css/reserve.css" rel="stylesheet" type="text/css"/>
  <link href="../css/add_reserve.css?ver=20170925" rel="stylesheet" type="text/css"/>
  <link href="../css/stamp.css" rel="stylesheet" type="text/css"/>
  <link href="../css/popup.css" rel="stylesheet" type="text/css"/>
  <link href="../css/layout.css" rel="stylesheet" type="text/css"/>
  <link href="../css/items.css" rel="stylesheet" type="text/css"/>
  <link href="../css/mmenu.css" rel="stylesheet" type="text/css"/>

  <script src="../js/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../js/common.js"></script>
  <script type="text/javascript" src="../js/stamp.js"></script>
  <script type="text/javascript" src="../js/reserve.js?ver=20180826"></script>
  <script type="text/javascript" src="../js/add_reserve.js?ver=20170925"></script>
  <script type="text/javascript" src="../js/popup.js"></script>
  <script type="text/javascript" src="../js/string_check.js"></script>
  <script type="text/javascript" src="../js/plan_manager.js"></script>
  <script type="text/javascript" src="../js/vivo_func.js"></script>
  <!-- plugins -->
  <link href="../plugin/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="../plugin/js/jquery.mmenu.min.all.js"></script>
  <script type="text/javascript" src="../plugin/jquery-ui-1.11-2.2/jquery-ui.js"></script>
  <script type="text/javascript" src="../plugin/js/jquery.ui.touch-punch.min.js"></script>
  <link href="../plugin/jquery-ui-1.11-2.2/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
  <!-- ------- -->
  <!-- my_plugins -->
  <link href="../css/right_slide.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="../js/right_slide.js"></script>
  <script type="text/javascript" src="../js/overlay.js"></script>
  <!-- ------- -->

  <title>予約帳</title>

  <script type="text/javascript">
  /*--RootModelプロパティ-------*/
  var _visiter = <?php echo json_encode($model->_visiter)?>;
  var _postName = <?php echo json_encode($model->_postName)?>;
  var _personName = <?php echo json_encode($model->_personName)?>;
  var _today = <?php echo json_encode($model->_todaySQLStr)?>;
  var _minUnit = <?php echo json_encode(RootModel::MINUTE_UNIT)?>;
  /*---------------------------*/
  /*--planManagerプロパティ------*/
  var _planManager = <?php echo json_encode($model->planManager)?>;
  /*---------------------------*/
  var _condition = <?php echo json_encode($_SESSION["res_condition"])?>;
  var _monthDatas = <?php echo json_encode($model->monthDatas)?>;
  var _areas = <?php echo json_encode($model->areas)?>;
  var _times = <?php echo json_encode($model->times)?>;
  var _reservs = <?php echo json_encode($model->reservs)?>;
  var _minUnit = <?php echo json_encode($model::MINUTE_UNIT)?>;
  var _dateType = '<?php echo $model->dateType?>';
  var _staffs = <?php echo json_encode($model->staffs)?>;
  var _menus = <?php echo json_encode($model->menus)?>;
  var _lastTime = '<?php echo $model->lastTime?>';
  var _recEnts = <?php echo json_encode($model->receiptEntries)?>;
  var _staffId = <?php echo json_encode($model->staffID)?>;
  var _pages = JSON.parse('<?php echo json_encode($model->getSalonPages())?>');
  var _salonId = JSON.parse('<?php echo json_encode($_SESSION["salon"]["id"])?>');
  var _password = <?php echo json_encode($model->salonPassword)?>;

  jQuery(function ($) {
    //mmenuの設定（ここでやらないとうまく動作しない）
    $.mmenuSetting({
      navTabName: "reserve",
      extensions: ["theme-dark", "effect-slide-menu"]
    });

    //プランによるページアクセス制限@plan_manager.js
    $.limitatePageAccess();


    $("span.currency").text(CURRENCY);
  });


</script>

<style type="text/css">
@font-face {
  font-family: mplus;
  src: url("../css/mplus/mplus-1c-light.ttf") format("truetype");
}
.top_nav dl.inbiz,
.top_nav dl.outbiz{
  padding: 0px 2px;
    float: left;
    width: 50%;
    box-sizing: border-box;
}
</style>

</head>
<body id="body">

  <div id="popup_view"></div>

  <div id="wrap">

    <div id="container">

      <!-- right_slide -------------------------->
      <div id="right_slide">

        <!-- area_select Vue object -->
        <div id="area_select" style="display: none;" class="">
          <p class="mmdl_text_box">選択したエリアで稼働率を計算します</p>
          <ul class="mdl-list">
            <li v-for="area in areas" class="mdl-list__item">
              <span class="mdl-list__item-primary-content">
                {{area.name}}
              </span>
              <span class="mdl-list__item-secondary-action">
                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" >
                  <input type="checkbox" v-bind:checked="area.selectedForOccupancy" v-model="area.selectedForOccupancy" v-on:change="select" class="mdl-switch__input">
                </label>
              </span>
            </li>
          </ul>
        </div>
        <!-- end area_select -->

        <!-- reserv_input -->
        <div id="reserve_input" class="contents_area clearfix" style="display: none;">
          <table id="reserv_input">
            <tr>
              <td colspan="2">
                <select id="ar_month" name="month"></select>
                <select id="ar_date" name="date"></select>
              </td>
            </tr>
            <tr>
              <td>担当者</td>
              <td>
                <select id="ar_staff" name="staff_id"></select>
              </td>
            </tr>
            <tr>
              <td>顧客名</td>
              <td>
                <input type="text" name="costomer"
                id="costomer" class="not_unique_char">様
              </td>
            </tr>
            <tr>
              <td style="font-size: 14px;">メニュー</td>
              <td id="ar_menu">
              </td>
            </tr>
            <tr>
              <td>来店回数</td>
              <td>
                <select id="num_visit" name="num_visit">
                </select>
              </td>
            </tr>
            <tr>
              <tr>
                <td>施術料金</td>
                <td>
                  <span class="currency"></span>
                  <input type="text" id="tec_sale" name="tec_sale"
                  class="only_num not_unique_char chkcode">
                </td>
              </tr>
              <tr>
                <td>開始</td>
                <td>
                  <select id="ar_start" name="start_time"></select>
                </td>
              </tr>
              <tr>
                <td>終了</td>
                <td>
                  <select id="ar_end" name="end_time"></select>
                </td>
              </tr>
              <tr>
                <td>メモ</td>
                <td>
                  <textarea name="memo" id="memo" class="not_unique_char"></textarea>
                </td>
              </tr>
            </table>

            <div class="button_box">
              <input type="button" class="submit_button" id="submit_btn" value="登録">
            </div>
            <div class="button_box" style="display: none">
              <input type="button" class="delete_button" id="delete_btn" value="削除">
            </div>
          </div>
          <!-- end reserv_input -->

        </div>
        <!-- ----------- --------------------------->

        <script type="text/javascript">
        $._createHeader();
        $._createNavigation();
      </script>

      <div id="top_nav_salon" class="top_nav clearfix" style="display: none;">
        <form action="reserve.php">
          <select id="salon"></select>
        </form>
      </div>

      <!-- 来店処理通知領域 -->
      <div id="posting_rem_comp_area" class="posting_area">
        <p>来店処理待ち伝票が<span id="num_not_rem_comp" class="orange"></span>件あります</p>
        <a id="rem_comp_link" class="like_button01">来店処理 〉</a>
      </div>
      <!-- -------------- -->

      <div class="top_nav clearfix date">
        <select id="month"></select>
        <select id="date"></select>

        <a class="like_button01" href="javascript:void(0);" id="today">今日</a>
        <a class="like_button01" href="javascript:void(0);" id="tomorrow">明日</a>
      </div>


      <div class="top_nav clearfix">
        <div id="viewtype_buttons">
          <button class="button01 overflow_ellipsis" id="nomal">
            通常表示</button>
            <button class="button01 overflow_ellipsis" id="all">
              全席表示</button>
            </div>
          </div>

          <div class="top_nav clearfix" id="occupancy_rate_display" v-on:click="select" style="display: none">
            <dl class="inbiz">
              <dt class="f_14">営業内稼働率</dt>
              <dd class="orange">{{floor.businesstimeOccupancyRateForDisplay}}</dd>
            </dl>
            <dl class="outbiz clearfix">
              <dt class="f_14">営業外稼働率</dt>
              <dd class="orange">{{floor.notBusinesstimeOccupancyRateForDisplay}}</dd>
            </dl>
            <div class="f_14 hp_6" v-if="areaSelectable">
              稼働率対象エリア：

              <span v-for="(area, index) in floor.areas"  v-if="area.selectedForOccupancy">
                <slash-separate-list-item v-bind:slashable="index > 0" v-bind:text="area.name"></slash-separate-list-item>
              </span>
            </div>
          </div>

          <div id="main_alea">

            <div id="time_colmun">
              <table id="time_table"></table>
            </div>


            <div id="reserv_alea"><!-- id="reserve_area"に今後修正 -->
              <div id="box" class="clearfix">
              </div>
            </div>

          </div>
        </div>

      </div>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
      <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
      <script type="text/javascript" src="../js/bundle_reserve_after.js?ver=20180715"></script>
    </body>
    </html>
