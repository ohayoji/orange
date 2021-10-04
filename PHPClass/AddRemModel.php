<?php
require_once 'RootModel.php';
class AddRemModel extends RootModel{

	//月セレクタで選択可能な月数
	const MON_COUNT = 2;
	//月リスト
	var $monthDatas;
	//サロンstaffs
	var $staffs;
	//追加報酬リスト　
	var $addRems;
	//合計額リスト
	var $totalAmounts;
	//報酬承認済みスタッフid配列
	var $approvedStaffs = array();

	function __construct() {
		parent::__construct();

		if ($_POST) {
			$_SESSION["ar_month_condition"] = $_POST["month"];
		}else {
			if (empty($_SESSION["ar_month_condition"])) {
				$_SESSION["ar_month_condition"] =
					date("Y-m",mktime(0,0,0,date("m"),1,date("Y")));
			}
		}

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);

		//$this->staffs = parent::_getStaffs($_SESSION["salon"]["id"]);
		$this->staffs = parent::_getStaffsWithDeleted($_SESSION["salon"]["id"]);

		$this->addRems = self::getAddRems();

		$this->totalAmounts = self::getTotalAmounts($_SESSION["ar_month_condition"]);

		//当月でない場合は承認済みリストを取得
		if ($_SESSION["ar_month_condition"] != date("Y-m",mktime(0,0,0,date("m"),1,date("Y")))
				&& count($this->staffs) > 0) {
			$protAppStaffs = $this->getRemApprovedStaffs();

			$count = count($protAppStaffs);
			for ($i = 0; $i < $count; $i++) {
				array_push($this->approvedStaffs, $protAppStaffs[$i]["staff_id"]);
			}
		}
		//var_dump($this->approvedStaffs);
	}



	/*==DB==================================================*/
	private function getRemApprovedStaffs() {

		$whereStr = "month='".$_SESSION["ar_month_condition"]."-01' AND (";

		$count = count($this->staffs);
		$whereStr = $whereStr."";

		for ($i = 0; $i < $count; $i++) {
			$whereStr = $whereStr."staff_id=".$this->staffs[$i]["id"];
			if ($i != $count -1) {
				$whereStr = $whereStr." OR ";
			}else {
				$whereStr = $whereStr.")";
			}
		}

		$array = parent::_select("approved_rems", "staff_id", $whereStr);

		return $array;
	}
	public static function getAddRems() {

		$colStr = "add_rem_id,title,amount,staff_id,color";
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
					StringManager::deleteHyphen($_SESSION["ar_month_condition"])
					." AND salon_id=".$_SESSION["salon"]["id"];
		$array = parent::_select("add_rems_info", $colStr, $whereStr, "staff_id");

		return $array;
	}
	public static function getTotalAmounts($month) {
		$sql = "SELECT SUM(amount) AS total,staff_id FROM add_rems_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
				 StringManager::deleteHyphen($month)
				." AND salon_id=".$_SESSION["salon"]["id"]
				." GROUP BY staff_id";
		$array = parent::_query($sql, "select");
		return $array;
	}
	public static function getAddRemsOfAllSalon($month, $salonIDs) {

		$str = " AND (";
		for($i=0 ; $i < count($salonIDs) ; $i ++){
			$subStr = " salon_id=".$salonIDs[$i];
			$str = $str.$subStr;
			if ($i < count($salonIDs) -1) {
				$str = $str." OR";
			}
		}
		$str = $str.")";

		$sql = "SELECT amount,staff_id,title FROM add_rems_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
					 StringManager::deleteHyphen($month)
					 .$str
					 ." ORDER BY staff_id";
		//echo "sql=".$sql;
		$array = parent::_query($sql, "select");
		return $array;
	}
	public static function getTotalAmountsOfAllSalon($month, $salonIDs) {
		$str = " AND (";
		for($i=0 ; $i < count($salonIDs) ; $i ++){
			$subStr = " salon_id=".$salonIDs[$i];
			$str = $str.$subStr;
			if ($i < count($salonIDs) -1) {
				$str = $str." OR";
			}
		}
		$str = $str.")";

		$sql = "SELECT SUM(amount) AS total,staff_id FROM add_rems_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
					 StringManager::deleteHyphen($month)
					 .$str
					 ." GROUP BY staff_id";
		//echo "sql=".$sql;
		$array = parent::_query($sql, "select");
		return $array;
	}
	public static function insert_add_rems(){
		unset($_POST["id"]);
		unset($_POST["mode"]);
		$colVal = $_POST;
		$colVal["month"] = $_SESSION["ar_month_condition"]."-01";
		$res = parent::_insert("add_rems",
					parent::_columnStringForInsert($colVal),
					parent::_valueStringForInsert($colVal));
		return $res;
	}
	public static function update_add_rems(){
		$id = $_POST["id"];
		unset($_POST["id"]);
		unset($_POST["mode"]);
		$colVal = $_POST;
		$res = parent::_update("add_rems",
				parent::_setStringForUpdate($colVal),
				"id=".$id);
		return $res;
	}
	public static function delete_add_rems(){
		parent::_delete("add_rems", "id=".$_POST["id"]);
	}
	/*===================================================*/
}
//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "add") {
  	if (AddRemModel::insert_add_rems()) {
  		echo "新しい手当が登録されました";
  	}else {
  		echo "手当の登録に失敗しました";
  	}
    return;
  }
  if ($_POST["mode"] == "edit") {
  	if (AddRemModel::update_add_rems()) {
  		echo "手当が変更されました";
  	}else {
  		echo "手当の変更に失敗しました";
  	}
    return;
  }
  if ($_POST["mode"] == "add_rems_delete") {
  	AddRemModel::delete_add_rems();
  	echo "手当が削除されました";
    return;
  }
}
