<?php
require_once 'SalonSettingModel.php';
require_once 'PlanManager.php';

class ReserveModel extends SalonSettingModel{

/*NEW_MONTHとOLD_MONTHはSalonSettingModel.phpに移動した*/
	//プランマネージャ
	var $planManager;

	//月リスト
	var $monthDatas;
	//日付軸  昨日以前:old 今日:same 明日以降:new
	var $dateType;

	/*--予約情報プロパティ--*/
	var $areas;//選択日に有効なエリア配列
	var $reservs;//当日の全予約情報配列
	var $times;//時間軸配列
	/*-------------------*/

	//スタッフ
	var $staffs;

	/*--予約編集ビュープロパティ---*/
	const AR_NEW_MONTH = 2;
	const AR_OLD_MONTH = -1;
	//予約最終時間
	var $lastTime;
	/*-------------------------*/

  //スタッフログイン時プロパティ
  var $staffID = null;
  //サロンログイン時プロパティ
  var $salonPassword = null;


	function __construct() {

		parent::__construct();

    if ($this->_visiter == "staff") {
      $this->staffID = $_SESSION["staff"]["id"];

    }elseif ($this->_visiter == "salon") {
      $this->salonPassword = $_SESSION["salon"]["password"];
    }

		$this->setCondition($_SESSION["salon"]["id"]);

		$this->planManager = new PlanManager($_SESSION["res_condition"]["salon"]);

		$this->monthDatas = parent::_getRangeMonthDatasDesc(
				self::NEW_MONTH, self::OLD_MONTH);

		//日付軸
		$this->dateType = StringManager::getDateDifferenceBySqlDate(
				$_SESSION["res_condition"]["base_date"]);

		/*--予約情報プロパティ----------------*/
		//エリア情報配列をセット
		$this->areas = self::getAreas();

		/*--当日予約情報をセット----------*/
		$this->reservs = self::getReservs();

		//メニュー詳細配列を予約配列に統合
		$this->addUsdMenus();
		/*-----------------------------*/

		//時間軸配列を作成
		$this->setTimes();
		/*----------------------------------*/


		$this->staffs = parent::_getLivedStaffs($_SESSION["res_condition"]["salon"]);

		$this->lastTime = StringManager::
			getHmBySqlTime($this->salonStatus["reserv_end"]);

	}

	//セッションをセット
	function setCondition($id) {
		if (empty($_SESSION["res_condition"])) {
			//選択日付
			$_SESSION["res_condition"]["base_date"] = $this->_todaySQLStr;
			//ビュータイプ
			$_SESSION["res_condition"]["view_type"] = "nomal";
			//対象サロン
			$_SESSION["res_condition"]["salon"] = $id;
		}
	}
	//メニュー詳細配列を予約配列に統合
	function addUsdMenus() {
		$usdMenus = self::getUsedMenus();
		for ($i = 0; $i < count($this->reservs); $i++) {
			$this->reservs[$i]["menus"] = array();
			for ($n = 0; $n < count($usdMenus); $n++) {
				if ($usdMenus[$n]["rec_id"] == $this->reservs[$i]["rec_id"]) {
					array_push($this->reservs[$i]["menus"], $usdMenus[$n]);
				}
			}
		}
	}
	////時間軸配列を作成
	function setTimes() {

		$this->times = array();
		//親クラスプロパティ
		$hs = $this->hours;
		$ms = $this->minutes;
		for ($i = 0; $i < count($hs); $i++) {
			for ($n = 0; $n < count($ms); $n++) {

				//時間文字列を作成
				$timeStr = $hs[$i]["value"].":".$ms[$n]["value"].":00";
				//予約帳表示時間内なら追加
				if ($timeStr >= $this->salonStatus["reserv_start"]
						&& $timeStr < $this->salonStatus["reserv_end"]) {

					$time["text"] = $hs[$i]["value"].":".$ms[$n]["value"];

					//営業時間内外でタイプ分け
					if ($timeStr >= $this->salonStatus["biz_start"]
							&& $timeStr < $this->salonStatus["biz_end"]) {
						$time["biz_type"] = "inbiz";
					}else {
						$time["biz_type"] = "outbiz";
					}
					//分単位でタイプ分け
					if ($ms[$n]["value"] == "00") {
						$time["min_type"] = "oclock";
					}else {
						$time["min_type"] = "un_oclock";
					}
					array_push($this->times, $time);
				}
			}
		}
	}





	/*==DB====================================================*/
	/*--area---------------------------------------------*/
	//エリア設定配列を取得
	public static function getAreas() {

		//seats用に書き換え
		$table1 = "area_setting";
		$table2 = "area_seats_setting";
		$colStr = "area_setting.id, _name, area_seats_setting.seats, _order";
		$joinStr = "area_setting.id = area_seats_setting.area_id";
		$whereStr = "salon_id=".$_SESSION["res_condition"]["salon"].
			" AND area_setting.start_date<='".$_SESSION["res_condition"]["base_date"].
			"' AND (delete_date>'".$_SESSION["res_condition"]["base_date"]."' OR delete_date IS NULL)".
			" AND area_seats_setting.start_date<='".$_SESSION["res_condition"]["base_date"].
			"' AND (end_date>'".$_SESSION["res_condition"]["base_date"]."' OR end_date IS NULL)";
		$array = parent::_selectInnerJoin($table1, $table2, $colStr, $joinStr, $whereStr, "_order");

		return $array;
	}
	//特定のエリアの情報
	public static function getAreaStatus($areaId) {
		$colStr = "seats";
		$array = parent::_select("area_setting",
		 	$colStr, "id=".$areaId, null);
		return $array[0];
	}
	/*---------------------------------------------------*/


	/*--予約情報-------------------------------------------*/

	//予約情報を取得
	function getReservs() {

		$whereStr = "start BETWEEN '".$_SESSION["res_condition"]["base_date"].
		" 00:00:00' AND '".$_SESSION["res_condition"]["base_date"]." 23:59:59'";

		$array = parent::_select("rec_info_".$_SESSION["res_condition"]["salon"],"*",$whereStr,"start");
		return $array;
	}

	/*--count----------------*/
	public static function getCountReservs($salonId, $date) {
		$whereStr = "start BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59'";
		$array = parent::_count("rec_info_".$salonId, "rec_id", $whereStr);
		return $array;
	}
	public static function getCountRecCompReserves($salonId, $date) {
		$whereStr = "start BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59'"
				." AND rec_comp=1";
		$array = parent::_count("rec_info_".$salonId, "rec_id", $whereStr);
		return $array;
	}
	public static function getCountNotRecCompReservs($salonId, $date) {
		$whereStr = "start BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59'"
				." AND rec_comp=0";
		$array = parent::_count("rec_info_".$salonId, "rec_id", $whereStr);
		return $array;
	}
	/*------------------------*/

	//特定エリア特定時間帯の予約レコードを取得
	public static function getPinpointReservs(
			$salonId,$areaId,$start,$end) {
		$whereStr = "area_id=".$areaId." AND start<='".$start."' AND end>='".$end."'";
		$array = parent::_select("receipts_".$salonId, "id", $whereStr, null);
		return $array;
	}

	//予約レコード追加
	public static function insert_receipts() {
		$colVal = self::colValForReceipts();
		$res = parent::_insert("receipts_".$_SESSION["res_condition"]["salon"],
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
		return $res;
	}
	//予約レコード更新
	public static function update_receipts(){
		$colVal = self::colValForReceipts();
		$res = parent::_update(
				"receipts_".$_SESSION["res_condition"]["salon"],
				parent::_setStringForUpdate($colVal),
				"id=".$_POST["rec_id"]);

		return $res;
	}
	//colVal
	private static function colValForReceipts() {
		$colVal["start"] = $_POST["start"];
		$colVal["end"] = $_POST["end"];
		$colVal["staff_id"] = $_POST["staff_id"];
		$colVal["costomer"] = $_POST["costomer"];
		$colVal["num_visit"] = $_POST["num_visit"];
		$colVal["seat"] = $_POST["seat"];
		$colVal["area_id"] = $_POST["area_id"];
		$colVal["memo"] = $_POST["memo"];
		if ($_POST["tec_sale"] != "") {
			$colVal["tec_sale"] = $_POST["tec_sale"];
		}
		return $colVal;
	}
	//予約に関するレコードをまとめて削除
	public static function deleteRecDatas() {
		//receipts
		parent::_delete("receipts_".$_SESSION["res_condition"]["salon"],
				"id=".$_POST["rec_id"]);
		//used_menu_details
		parent::_delete("used_menu_details_".$_SESSION["res_condition"]["salon"],
				"rec_id=".$_POST["rec_id"]);
		//used_products,net,point,free,student,tec_rem,pro_rem
		$array = array("used_products","net","point","free","student","tec_rem","pro_rem",);
		for ($i = 0; $i < count($array); $i++) {
			parent::_delete($array[$i],
					"salon_id=".$_SESSION["res_condition"]["salon"].
					" AND rec_id=".$_POST["rec_id"]);
		}
		return true;
	}
	/*-------------------------------------------------------------*/



	/*--メニュー--------------------------------------------------*/
	//使用されているメニュー配列を取得
	public static function getUsedMenus() {
		$colStr = "rec_id,menu_id,str_icon";
		$whereStr = "start BETWEEN '".$_SESSION["res_condition"]["base_date"].
		" 00:00:00' AND '".$_SESSION["res_condition"]["base_date"]." 23:59:59'";
		$array = parent::_select(
				"usd_menu_info_".$_SESSION["res_condition"]["salon"],
				$colStr, $whereStr, "rec_id,menu_id");
		return $array;
	}
	//予約レコードに登録されているメニュー配列を取得
	public static function getUsedMenusAtRec($receiptId,$salonId) {
		$array = parent::_select("used_menu_details_".$salonId,
				"menu_id", "rec_id=".$receiptId, "menu_id");
		return $array;
	}

	//$usedMnusからinsert用valStrを返す
	public static function getValStr($usedMnus,$receiptId) {
		$valStr = "";
		for ($i = 0; $i < count($usedMnus); $i++) {
			$valStr = $valStr.$usedMnus[$i].",".$receiptId;
			if ($i != count($usedMnus) -1) {
				$valStr = $valStr."),(";
			}
		}
		return $valStr;
	}
	//$_POST["menus"]を配列に変換
	public static function getSelectedMenus() {
		return explode("|", $_POST["menus"]);
	}
	//レコード追加
	public static function insert_usd_menu_d($receiptId,$usedMnus) {
		$colStr = "menu_id,rec_id";
		$valStr = self::getValStr($usedMnus,$receiptId);
		$res = parent::_insert(
				"used_menu_details_".$_SESSION["res_condition"]["salon"],
				$colStr, $valStr);
		return $res;
	}
	//used_menu_details_更新/非選択のレコードを削除
	public static function deleteUnusedMenus($selectedMenus) {
		$wherStr = "rec_id=".$_POST["rec_id"];
		if ($selectedMenus) {
			for ($i = 0; $i < count($selectedMenus); $i++) {
				$wherStr = $wherStr." AND menu_id!=".$selectedMenus[$i];
			}
		}
		parent::_delete(
				"used_menu_details_".$_SESSION["res_condition"]["salon"],
				$wherStr);
	}
	//used_menu_details_更新/選択済みのレコードがなければ追加
	public static function insertNewUsedMenus($selectedMenus) {
		//既存レコードを取得
		$existingRecords = self::getUsedMenusAtRec(
					$_POST["rec_id"], $_SESSION["res_condition"]["salon"]);

		//$addMenus（既存レコードにないmenu配列）を作成
		$addMenus = self::getNewUsedMenus($selectedMenus, $existingRecords);

		//insert
		if (count($addMenus) > 0) {
			self::insert_usd_menu_d($_POST["rec_id"], $addMenus);
		}
	}
	//既存レコードにないmenu配列を作成
	public static function getNewUsedMenus($selectedMenus,$existingRecords) {
		$newUsedMenus = array();
		for ($i = 0; $i < count($selectedMenus); $i++) {
			$addFlag = true;
			for ($n = 0; $n < count($existingRecords); $n++) {
				if ($selectedMenus[$i] == $existingRecords[$n]["menu_id"]) {
					$addFlag = false;
				}
			}
			if ($addFlag) {
				array_push($newUsedMenus, $selectedMenus[$i]);
			}
		}
		return $newUsedMenus;
	}
	/*---------------------------------*/

	/*--伝票項目テーブル----------------------------*/
	//insert
	public static function insert_rec_ent($tableName,$salonId,$recId) {
		$colVal["rec_id"] = $recId;
		$colVal["salon_id"] = $salonId;

		if ($tableName == "point" || $tableName == "free"
				|| $tableName == "tec_rem" || $tableName == "pro_rem") {
			$colVal["value"] = $_POST[$tableName];
		}

		$res = parent::_insert($tableName,
			parent::_columnStringForInsert($colVal),
			parent::_valueStringForInsert($colVal));
		return $res;
	}
	//insert,update,deleteを場合わけして実行する
	public static function multiOperation_rec_ent($salonId) {

		$entries = array("net","point","free","student","tec_rem","pro_rem");

		for ($i = 0; $i < count($entries); $i++) {
			if ($_POST[$entries[$i]] != null) {//値あり
				if ($_POST[$entries[$i]."_id"]) {
					//レコードidあり update
					if ($entries[$i] == "point" || $entries[$i] == "free"
							|| $entries[$i] == "tec_rem" || $entries[$i] == "pro_rem") {
						parent::_update($entries[$i],
							"value=".$_POST[$entries[$i]],
							"id=".$_POST[$entries[$i]."_id"]);
					}
				}else {
					//レコードidなし insert
					self::insert_rec_ent($entries[$i],
							$salonId,$_POST["rec_id"]);
				}
			}else {//値なし
				if ($_POST[$entries[$i]."_id"]) {
					//レコードidあり delete
					parent::_delete($entries[$i],
					"id=".$_POST[$entries[$i]."_id"]);
				}
				//レコードidがない場合は何もしない
			}
		}
	}
	/*--------------------------------------*/

	/*==========================================================*/


	/*======================================================*/
	//予約可能かチェックするメソッド
	public static function checkAddReservPossible($data) {
		//該当エリアの席数
		$area = self::getAreaStatus($data["area_id"]);
		$seatNum = $area["seats"];

		//エリアの各コマの予約数を取得。席数いっぱいになっていたらキャンセル
		$startTime = $data["start"];

		while ($startTime < $data["end"]) {
			$endTime = StringManager::getSqlDateByMinuteCount(
					$startTime, parent::MINUTE_UNIT);

			$res = self::getPinpointReservs(
					$_SESSION["res_condition"]["salon"],
					$data["area_id"],$startTime, $endTime);

			if (count($res) >= $seatNum) {

				//予約許可フラグ
				$auth = true;

				if ($data["rec_id"]) {
					//編集時には自分の予約が入っていないか確認
					$myReserveExist = false;
					for ($i = 0; $i < count($res); $i++) {
						if ($res[$i]["id"] == $data["rec_id"]) {
							$myReserveExist = true;
						}
					}
					// 自分の予約がない場合のみ許可フラグをfalse
					if ($myReserveExist == false) {
						$auth = false;
					}

				}else {
					//新規登録時は無条件にfalse
					$auth = false;
				}

				if ($auth == false) {
					$str = date("n月j日 G時i分",strtotime($startTime));
					return $str;
				}
			}
			$startTime = $endTime;
		}
		//チェック通過の場合はセッションの日付を更新
		$_SESSION["res_condition"]["base_date"] =
			date("Y-m-d",strtotime($data["start"]));
		return true;
	}
	/*====================================================*/
}


if (!empty($_POST["mode"])) {
  /*==ajax( 予約追加、編集 )========================================*/
  //空席チェック
  if ($_POST["mode"] == "seat_check") {
  	$res = ReserveModel::checkAddReservPossible($_POST);
  	echo json_encode($res);
  }
  //新規登録
  if ($_POST["mode"] == "add") {
  	$res = ReserveModel::insert_receipts();

  	//予約登録に成功したら詳細情報を登録
  	if ($res) {
  		//メニュー情報登録
  		if ($_POST["menus"] != "") {
  			$selectedMenus = ReserveModel::getSelectedMenus();
  			ReserveModel::insert_usd_menu_d($res,$selectedMenus);
  		}

  		$_POST["rec_id"] = $res;
  		/*==伝票上登録===================================*/
  		ReserveModel::multiOperation_rec_ent(
  				$_SESSION["res_condition"]["salon"]);
  		/*============================================*/

  	}else {
  		echo json_encode(false);
  	}
  	//echo json_encode(true);
  }
  //編集
  if ($_POST["mode"] == "edit") {
  	$res = ReserveModel::update_receipts();
  	//予約更新に成功したら詳細を更新
  	if ($res) {
  		//メニュー情報登録
  		if ($_POST["menus"] != "") {
  			$selectedMenus = ReserveModel::getSelectedMenus();
  			ReserveModel::insertNewUsedMenus($selectedMenus);
  			ReserveModel::deleteUnusedMenus($selectedMenus);
  		}


  		/*==伝票上登録===================================*/
  		ReserveModel::multiOperation_rec_ent(
  				$_SESSION["res_condition"]["salon"]);
  		/*============================================*/
  	}
  }
  //削除
  if ($_POST["mode"] == "delete") {
  	echo json_encode(ReserveModel::deleteRecDatas());
  }

  /*--編集時の伝票項目値変更----------------*/
  if ($_POST["mode"] == "re_add") {
  	$colVal["salon_id"] = $_SESSION["res_condition"]["salon"];
  	$colVal["rec_id"] = $_POST["rec_id"];
  	if ($_POST["value"] != null) {//0を許容
  		$colVal["value"] = $_POST["value"];
  	}
  	ReserveModel::_insert($_POST["type"],
  		ReserveModel::_columnStringForInsert($colVal),
  		ReserveModel::_valueStringForInsert($colVal));
  }
  if ($_POST["mode"] == "re_del") {
  	ReserveModel::_delete($_POST["type"],
  		"salon_id=".$_SESSION["res_condition"]["salon"].
  		" AND rec_id=".$_POST["rec_id"]);
  }
  if ($_POST["mode"] == "re_change") {
  	ReserveModel::_update($_POST["type"],
  		"value=".$_POST["value"],
  		"salon_id=".$_SESSION["res_condition"]["salon"].
  		" AND rec_id=".$_POST["rec_id"]);
  }
  /*---------------------------------------*/

  if($_POST["mode"] == "panel_move"){

  	$date = $_POST["date"];
  	$start = $_POST["startTime"];
  	$end = $_POST["endTime"];
  	$areaId = $_POST["areaId"];
  	$recId = $_POST["recId"];
  	//$salonId = $_SESSION["salon"]["id"];
  	$salonId = $_SESSION["res_condition"]["salon"];

  	$startSQLDate = $date." ".$start.":00";
  	$endSQLDate = $date." ".$end.":00";
  	//check methodの結果
  	$checked = true;

  	if(checked){
  		$tableName = "receipts_".$salonId;

  		$colVal["start"] = $startSQLDate;
  		$colVal["end"] = $endSQLDate;
  		$colVal["area_id"] = $areaId;
  		$colVal["seat"] = $_POST["seat"];

  		$setStr = MySQL::_setStringForUpdate($colVal);
  		$whereStr = "id = ".$recId;


  		MySQL::_update($tableName, $setStr, $whereStr);
  		$res["flag"] = true;
  		$res["msg"] = "";
  		header("Content-Type: application/json; charset=UTF-8");
  		echo json_encode($res);
  	}
  	else{
  		$res["flag"] = false;
  		$res["msg"] = "その場所には移動できません。";
  		header("Content-Type: application/json; charset=UTF-8");
  		echo json_encode($res);
  	}
  }

  /*=========================================================*/

  /*==ajax( 予約表示 )================================================*/
  //予約表示日付変更
  if ($_POST["mode"] == "change_date") {
  	//日付文字列作成
  	if ($_POST["type"] == "today") {
  		$date = StringManager::getSqlDateByDateCount(0);
  	}elseif ($_POST["type"] == "tomorrow") {
  		$date = StringManager::getSqlDateByDateCount(1);
  	}else {
  		$date = $_POST["type"];
  	}
  	//セッションきりかえ
  	$_SESSION["res_condition"]["base_date"] = $date;
  }
  //ビュータイプ切り換え
  if ($_POST["mode"] == "change_viewtype") {
  	$_SESSION["res_condition"]["view_type"] = $_POST["type"];
  }
  //来店処理,荷物保存処理
  if ($_POST["mode"] == "check_come" || $_POST["mode"] == "save_bag") {
  	$id = $_POST["id"];
  	unset($_POST["mode"]);
  	unset($_POST["id"]);
  	$colVal = $_POST;

  	$res = MySQL::_update(
  			"receipts_".$_SESSION["res_condition"]["salon"],
  			MySQL::_setStringForUpdate($colVal),"id=".$id);
  	echo $res;
  }
  if($_POST["mode"] == "changeSalon"){
  	$_SESSION["res_condition"]["salon"] = $_POST["id"];
  }
  /*=================================================*/
}
