<?php

require_once 'RootModel.php';
require_once 'StringManager.php';
require_once 'PlanManager.php';

class SalonSettingModel extends RootModel{

	//プランマネージャ
	var $planManager;

	//ReserveModelから移動してきた
	const NEW_MONTH = 4;
	const OLD_MONTH = -1;

	//時間配列
	var $hours;
	var $minutes;

	//基本プロパティ
	var $salonStatus;

	//詳細プロパティ
	var $menus;
	var $products;
	var $receiptEntries;
	var $areas;
	var $subjects;
	var $tecPP;
	var $proPP;

	function __construct() {
		parent::__construct();

		$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		$this->hours = parent::_getHours();
		$this->minutes = parent::_getMitutes();

		//基本プロパティをセット
		$array = self::getStatus();
		$this->salonStatus = $array[0];

		//詳細プロパティ
		$this->menus = self::getMenus();
		$this->addMenudetails();
		$this->products = self::getLivedProducts();
		$this->receiptEntries = self::getReceiptEntries();
		$this->areas = self::getAreas();
		$this->subjects = self::getSubjects();
		$this->tecPP = self::getPercentagePatterns("tec");
		$this->proPP = self::getPercentagePatterns("pro");
	}

	//menusに詳細項目を統合
	function addMenudetails() {
		//メニュー詳細項目配列
		$menuDetails = self::getLivedMenuDetails();

		for ($i = 0; $i < count($this->menus); $i++) {
			$this->menus[$i]["menu_datails"] = array();
			for ($n = 0; $n < count($menuDetails); $n++) {
				if ($menuDetails[$n]["menu_id"] == $this->menus[$i]["menu_id"]) {
					array_push($this->menus[$i]["menu_datails"], $menuDetails[$n]);
				}
			}
		}
	}



	/*==DB===========================================*/
	//サロンIDから基本ステータスを取得
	public static function getStatus() {
		$colStr = "e_mail,password,reserv_start,reserv_end,
				biz_start,biz_end,tec_rem,pro_rem,admission_date,plan";
		$array = parent::_select(
				"salons", $colStr, "id=".$_SESSION["salon"]["id"], null);

		return $array;
	}

	/*メニュー配列を取得
	 * サロンで有効にしているメニューには値が入る
	 */
	//メニュー配列
	public static function getMenus() {
		$colStr = "menus.id AS menu_id,menus.local_name,menus._name,
				menus.on_img,menus.off_img,using_menus.id AS um_id";
		//$joinStr = "using_menus.menu_id=menus.id";
		$joinStr = "menus.id=using_menus.menu_id AND using_menus.salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_selectOuterJoin(
				"menus", "using_menus", "LEFT",
				$colStr, $joinStr, "menus._name!='products'", "menu_id");
		return $array;
	}
	//メニューごとの詳細項目配列を取得
	public static function getMenuDetails() {
		$colStr = "id,menu_id,_name,price,_order,selected,deleted";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"];
		//"menu_id→_orderの順でソートしてSELECT
		$array = parent::_select("menu_detail_setting",
				$colStr, $whereStr, "menu_id,_order");
		return $array;
	}
	//生きているメニュー詳細配列を取得
	public static function getLivedMenuDetails() {
		$colStr = "id,menu_id,_name,price,_order,selected,deleted";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"]." AND deleted=0";
		//"menu_id→_orderの順でソートしてSELECT
		$array = parent::_select("menu_detail_setting",
				$colStr, $whereStr, "menu_id,_order");
		return $array;
	}
	//商品配列を取得
	public static function getProducts() {
		$colStr = "id,_name,price,_order,deleted,delete_date";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_select("product_setting",
				$colStr, $whereStr, "_order");
		return $array;
	}
	//生きている商品配列を取得
	public static function getLivedProducts() {
		$colStr = "id,_name,price,_order,deleted,delete_date";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"]." AND deleted=0";
		$array = parent::_select("product_setting",
				$colStr, $whereStr, "_order");
		return $array;
	}
	/*伝票項目配列を取得
	 * サロンで有効にしている項目には値が入る
	 */
	public static function getReceiptEntries() {
		$colStr = "receipt_entries.id AS ent_id,receipt_entries._name,receipt_entries.local_name,using_receipt_entries.id AS ur_id";
		$joinStr = "receipt_entries.id=using_receipt_entries.receipt_entry_id AND using_receipt_entries.salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_selectOuterJoin(
				"receipt_entries", "using_receipt_entries", "LEFT",
				$colStr, $joinStr, null, "ent_id");

		return $array;
	}
	//エリア設定配列を取得
	public static function getAreas() {
		$colStr = "id,_name,seats,_order";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"]." AND deleted=0";
		$array = parent::_select("area_setting",
				$colStr, $whereStr, "_order");
		return $array;
	}
	//指定エリアに入っている最終予約の日を取得
	public static function getLastRes() {
		$sql = "SELECT DATE_FORMAT(end,'%Y-%m-%d') AS end FROM receipts_".$_SESSION["salon"]["id"].
		" WHERE area_id=".$_POST["area_id"]." ORDER BY end DESC LIMIT 1";
		$res = parent::_query($sql, "select");

		return $res;
	}

	/*勘定科目配列を取得
	 * サロンで有効にしている勘定科目には値が入る
	 */
	public static function getSubjects() {
		/*$colStr = "subjects.id AS sb_id,subjects._name,subjects.local_name,using_subjects.id AS us_id";
		$joinStr = "subjects.id=using_subjects.subject_id AND using_subjects.salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_selectOuterJoin(
				"subjects", "using_subjects", "LEFT",
				$colStr, $joinStr, null, "sb_id");
		return $array;*/
		$colStr = "subjects.id AS sb_id,subjects._name,subjects.local_name,using_subjects.id AS us_id";
		$joinStr = "subjects.id=using_subjects.subject_id AND using_subjects.salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_selectOuterJoin(
				"subjects", "using_subjects", "LEFT",
				$colStr, $joinStr, null, "subjects._order");
		return $array;
	}
	//歩合率設定を配列で取得
	public static function getPercentagePatterns($type) {
		$colStr = "id,percentage,selected";
		$array = parent::_select(
				"percentage_pattern_setting_".$type,
				$colStr,
				"salon_id=".$_SESSION["salon"]["id"],
				"percentage DESC");
		return $array;
	}

	//歩合設定のselectedを更新
	public static function update_per_pat_set() {
		//既存のレコードのselectedを０にする
		parent::_update(
				"percentage_pattern_setting_".$_POST["type"],
				"selected=0", "salon_id=".$_SESSION["salon"]["id"]);
		//指定されたレコードのselectedを１にする
		parent::_update("percentage_pattern_setting_".$_POST["type"],
				"selected=1", "id=".$_POST["id"]);
	}
	/*====================================================*/
}

//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "status") {
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	SalonSettingModel::_update("salons",
  			SalonSettingModel::_setStringForUpdate($colVal),
  			"id=".$_SESSION["salon"]["id"]);
  }
  if ($_POST["mode"] == "menu_on") {
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	$colVal["salon_id"] = $_SESSION["salon"]["id"];
  	SalonSettingModel::_insert("using_menus",
  		SalonSettingModel::_columnStringForInsert($colVal),
  		SalonSettingModel::_valueStringForInsert($colVal));
  }
  if ($_POST["mode"] == "menu_off") {
  	SalonSettingModel::_delete("using_menus",
  		"menu_id=".$_POST["menu_id"].
  		" AND salon_id=".$_SESSION["salon"]["id"]);
  }
  if ($_POST["mode"] == "rec_ent_add") {
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	$colVal["salon_id"] = $_SESSION["salon"]["id"];
  		SalonSettingModel::_insert("using_receipt_entries",
  		SalonSettingModel::_columnStringForInsert($colVal),
  		SalonSettingModel::_valueStringForInsert($colVal));
  }
  if ($_POST["mode"] == "rec_ent_del") {
  	SalonSettingModel::_delete("using_receipt_entries",
  		"receipt_entry_id=".$_POST["receipt_entry_id"].
  		" AND salon_id=".$_SESSION["salon"]["id"]);
  }
  if ($_POST["mode"] == "area_change"
  		|| $_POST["mode"] == "area_del") {
  	$id = $_POST["id"];
  	$mode = $_POST["mode"];
  	unset($_POST["id"]);
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	SalonSettingModel::_update("area_setting",
  		SalonSettingModel::_setStringForUpdate($colVal),
  		"id=".$id);
  }
  if ($_POST["mode"] == "area_add") {

  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	$colVal["salon_id"] = $_SESSION["salon"]["id"];

  	$insertId = SalonSettingModel::_insert("area_setting",
  		SalonSettingModel::_columnStringForInsert($colVal),
  		SalonSettingModel::_valueStringForInsert($colVal));

  	//area_seats_settingテーブルへinsert
  	$colValForSeats = array();
  	$colValForSeats["area_id"] = $insertId;
  	$colValForSeats["seats"] = $_POST["seats"];
  	$colValForSeats["start_date"] = $_POST["start_date"];
  	$colStr = MySQL::_columnStringForInsert($colValForSeats);
  	$valStr = MySQL::_valueStringForInsert($colValForSeats);
  	SalonSettingModel::_insert("area_seats_setting", $colStr, $valStr);

  	$res = SalonSettingModel::_select("area_setting",
  			"id,_name,seats,_order", "id=".$insertId);
  	echo json_encode($res[0]);
  }
  if ($_POST["mode"] == "check_last_res") {
  	//現在指定エリアに入っている最終予約日を取得
  	$res = SalonSettingModel::getLastRes($_SESSION["salon"]["id"]);
  	/*---------------------------------------
  	 * 最終予約日が昨日以前またはnullなら"OK"
  	 * 今日以降なら配列を返す
  	*/

  	//返す配列を初期化
  	$array["last_res_date"] = null;
  	$array["delete_date"] = StringManager::getSqlDateByDateCount(0);

  	if (count($res) > 0) {
  		$date = $res[0]["end"];

  		$difference = StringManager::getDateDifferenceBySqlDate($date);
  		if ($difference == "new" || $difference == "same") {
  			//最終予約日の翌日
  			$nextDate = StringManager::getNextSqlDateBySqlDate($date);
  			$array["last_res_date"] = $date;
  			$array["delete_date"] = $nextDate;
  		}
  	}
  	echo json_encode($array);
  	/*-----------------------------------------*/
  }
  if ($_POST["mode"] == "md_change") {
  	$id = $_POST["id"];
  	unset($_POST["id"]);
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	//selectedの場合は既存のselectedを0に
  	if ($_POST["selected"]) {
  		SalonSettingModel::_update(
  			"menu_detail_setting",
  			"selected=0",
  			"menu_id=".$_POST["menu_id"].
  			" AND salon_id=".$_SESSION["salon"]["id"]);
  	}
  	//update
  	$res = SalonSettingModel::_update(
  		"menu_detail_setting",
  		SalonSettingModel::_setStringForUpdate($colVal),
  		"id=".$id);
  	echo json_encode($res);
  }
  if ($_POST["mode"] == "md_add") {
  	unset($_POST["mode"]);
  	$colVal = $_POST;
  	$colVal["salon_id"] = $_SESSION["salon"]["id"];
  	$insertId = SalonSettingModel::_insert("menu_detail_setting",
  		SalonSettingModel::_columnStringForInsert($colVal),
  		SalonSettingModel::_valueStringForInsert($colVal));
  	$res = SalonSettingModel::_select("menu_detail_setting",
  			"id,_name,_order,deleted,menu_id,price,selected", "id=".$insertId);
  	echo json_encode($res[0]);
  }
  if($_POST["mode"] == "area_sort"){
  	unset($_POST["mode"]);
  	$colVal = array();
  	for($i = 0; $i < count($_POST); $i++){
  		$colVal["_order"] = $_POST[$i]["_order"];
  		$id = $_POST[$i]["id"];
  		$setStr = MySQL::_setStringForUpdate($colVal);
  		$whereStr = "id = ".$id;
  		$tableName = "area_setting";
  		MySQL::_update($tableName, $setStr, $whereStr);
  	}
  }
  if($_POST["mode"] == "md_sort"){
  	unset($_POST["mode"]);
  	$colVal = array();
  	for($i = 0; $i < count($_POST); $i++){
  		$colVal["_order"] = $_POST[$i]["_order"];
  		$id = $_POST[$i]["id"];
  		$setStr = MySQL::_setStringForUpdate($colVal);
  		$whereStr = "id = ".$id;
  		$tableName = "menu_detail_setting";
  		MySQL::_update($tableName, $setStr, $whereStr);
  	}
  }
  if($_POST["mode"] == "pro_sort"){
  	unset($_POST["mode"]);
  	$colVal = array();
  	for($i = 0; $i < count($_POST); $i++){
  		$colVal["_order"] = $_POST[$i]["_order"];
  		$id = $_POST[$i]["id"];
  		$setStr = MySQL::_setStringForUpdate($colVal);
  		$whereStr = "id = ".$id;
  		$tableName = "product_setting";
  		MySQL::_update($tableName, $setStr, $whereStr);
  	}
  }
  if ($_POST["mode"] == "update_pp") {
  	SalonSettingModel::update_per_pat_set();
  }
  if ($_POST["mode"] == "all_pp_del") {
  	MySQL::_delete(
  		"percentage_pattern_setting_".$_POST["type"],
  		"salon_id=".$_SESSION["salon"]["id"]);
  }
  if($_POST["mode"] == "startTime_check"){

  	$newMonth = SalonSettingModel::NEW_MONTH + 1;
  	$oldMonth = SalonSettingModel::OLD_MONTH;

  	$newDate = StringManager::getSqlDateByMonthCount($newMonth).' 00:00:00';
  	$oldDate = StringManager::getSqlDateByMonthCount($oldMonth).' 00:00:00';

  	$reserv_start = $_POST["reserve_time"];
  	$reserv_start = StringManager::deleteColon($reserv_start);//colonを取り除く

  	$mysqli = MySQL::_createMysqli();
  	$colStr = "id";
  	$tableName = "receipts_".$_POST["salon_id"];
  	//AND ( start BETWEEN $oldDate AND $newDate )

  	$sql = "SELECT ".$colStr." FROM ".$tableName
  				." WHERE $reserv_start > EXTRACT( HOUR_MINUTE from
   start ) AND start BETWEEN '$oldDate' AND '$newDate' LIMIT 1";
  	$res = $mysqli->query($sql);
  	if($res->num_rows > 0){
  		$flag = "false";

  	}else{
  		$flag = "true";
  	}
  	echo $flag;
  }
  if($_POST["mode"] == "endTime_check"){

  	$newMonth = SalonSettingModel::NEW_MONTH + 1;
  	$oldMonth = SalonSettingModel::OLD_MONTH;

  	$newDate = StringManager::getSqlDateByMonthCount($newMonth).' 00:00:00';
  	$oldDate = StringManager::getSqlDateByMonthCount($oldMonth).' 00:00:00';

  	$reserv_end = $_POST["reserve_time"];
  	$reserv_end = StringManager::deleteColon($reserv_end);//colonを取り除く

  	$mysqli = MySQL::_createMysqli();
  	$colStr = "id";
  	$tableName = "receipts_".$_POST["salon_id"];

  	$sql = "SELECT ".$colStr." FROM ".$tableName
  	." WHERE $reserv_end < EXTRACT( HOUR_MINUTE from
  	end ) AND start BETWEEN '$oldDate' AND '$newDate' LIMIT 1";
  	$res = $mysqli->query($sql);
  	if($res->num_rows > 0){
  		$flag = "false";
  	}else{
  		$flag = "true";
  	}
  	echo $flag;
  }
  if($_POST["mode"] == "receipts_check"){
  	$flag = null;
  	$oldSeats = intval($_POST["oldSeats"]);
  	$newSeats = intval($_POST["newSeats"]);
  	$areaId	  = intval($_POST["areaId"]);


  	for($i=$oldSeats; $i > $newSeats; $i--){
  		$seatNumber = $i - 1;
  		$tableName = "rec_info_".$_SESSION["salon"]["id"];
  		$colStr = "rec_id, start, end, staff_name";
  		$rootModel = new RootModel();

  		$mysqli  = MySQL::_createMysqli();

  		$sql = "SELECT ".$colStr." FROM ".$tableName
  		." WHERE DATE(start) >= '".$rootModel->_todaySQLStr."' AND seat = '".
  		$seatNumber."' AND area_id = ".$areaId." LIMIT 1";
  		$res = $mysqli->query($sql);

  		if($res->num_rows > 0){
  			$flag = "NO";
  			$result = array();
  			$result = $res->fetch_assoc();

  			break;
  		}
  	}
  	$response = array();
  	$response["flag"] = $flag;
  	$response["row"] = $result;

  	echo json_encode($response);
  }
  if($_POST["mode"] == "seatsUpdate"){
  	//update　area_idでdisabled=0のレコードを抽出し、disabled=1、end_date=$todayに書き換え
  	$tableName = "area_seats_setting";
  	$setStr = "disabled='1',end_date='".$_POST["end_date"]."'";
  	$whereStr = "area_id='".$_POST["area_id"]."' AND disabled='0'";
  	$result = MySQL::_select($tableName, "id", $whereStr);
  	$id = $result[0]["id"];

  	MySQL::_update($tableName, $setStr, "id='".$id."'");
  }
  if($_POST["mode"] == "seatsDisable"){
  	$tableName = "area_seats_setting";
  	$setStr = "disabled='1',end_date='".$_POST["end_date"]."'";
  	$whereStr = "area_id='".$_POST["area_id"]."' AND disabled='0'";
  	MySQL::_update($tableName, $setStr, $whereStr);
  }
}
