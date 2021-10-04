<?php
require_once 'RootModel.php';
require_once 'StaffSettingModel.php';
require_once 'CompanySettingModel.php';
class DeductionModel extends RootModel{
	//月セレクタで選択可能な月数
	const MON_COUNT = 2;
	//月リスト
	var $monthDatas;
	//サロンstaffs
	var $staffs;
	//給与控除項目リスト
	var $deductions = array();
	//給与控除リスト
	var $usedDeductions = array();
	//合計額リスト
	var $totalDeductions = array();

	/*--会社ログイン用プロパティ---*/
	//サロン情報配列
	var $salonInfo;
	/*------------------------*/

	function __construct() {
		parent::__construct();

		/*--セッション作成-------*/
		if ($_POST) {
			$_SESSION["dd_condition"]["month"] = $_POST["month"];
			$_SESSION["dd_condition"]["salon"] = $_POST["salon"];
		}else {

			if (empty($_SESSION["dd_condition"])) {
				$_SESSION["dd_condition"]["month"] =
				date("Y-m",mktime(0,0,0,date("m"),1,date("Y")));
				if ($this->_visiter == "company") {
					$_SESSION["dd_condition"]["salon"] = "all";
				}
			}
		}
		/*----------------------------------*/

		/*----------------------------------*/
		//parent::_showPost();
		/*echo "<br>";
		echo "dd_month_condition=<br>";
		var_dump($_SESSION["dd_month_condition"]);*/
		/*echo "<br>";
		echo "dd_condition=<br>";
		var_dump($_SESSION["dd_condition"]);*/
		/*----------------------------------------*/

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);
		$this->deductions = parent::_getDeductions();

		/*--プロパティ作成-----------------*/
		//visiterごとに作成
		if ($this->_visiter == "salon") {

			//staffs
			$this->staffs = parent::_getStaffsWithDeleted($_SESSION["salon"]["id"]);

			//スタッフが存在していればusedDeductionsをセット
			if (count($this->staffs) > 0) {
				$this->usedDeductions = self::getUsedDeductions($_SESSION["dd_condition"]["month"]);
				$this->totalDeductions = self::getTotalDeductions($_SESSION["dd_condition"]["month"]);
			}

		}else {//company

			//salonInfo
			$this->salonInfo = CompanySettingModel::getSalonStatus();
			$salonIDs = array();
			for($i = 0; $i < count($this->salonInfo); $i++){
				$salonIDs[$i] = $this->salonInfo[$i]['id'];
			}

			if ($_SESSION["dd_condition"]["salon"] == "all") {//全サロン

				$this->staffs = StaffSettingModel::getAllSalonStaffStatus(
						$_SESSION["dd_condition"]["month"]."-01", $salonIDs);

				//スタッフが存在していればusedDeductionsをセット
				if (count($this->staffs) > 0) {
					$this->usedDeductions = self::getUsedDeductionsOfAllSalon(
							$_SESSION["dd_condition"]["month"], $salonIDs);
					$this->totalDeductions = self::getTotalDeductionsOfAllSalon(
							$_SESSION["dd_condition"]["month"], $salonIDs);
				}

			}else {//単サロン
				$this->staffs = parent::_getStaffsWithDeleted($_SESSION["dd_condition"]["salon"]);

				//スタッフが存在していればusedDeductionsをセット
				if (count($this->staffs) > 0) {
					$this->usedDeductions = self::getUsedDeductionsWithSalonID(
							$_SESSION["dd_condition"]["month"], $_SESSION["dd_condition"]["salon"]);
					$this->totalDeductions = self::getTotalDeductionsWithSalonID(
							$_SESSION["dd_condition"]["month"], $_SESSION["dd_condition"]["salon"]);
				}
			}


		}





		/*---------------------------------*/
	}

	/*==DB====================================*/
	public static function getUsedDeductions($month) {
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
				StringManager::deleteHyphen($month).
				" AND salon_id=".$_SESSION["salon"]["id"];

		$array = parent::_select(
				"usd_deductions_info",
				"usd_dd_id AS id,deduction_id,amount,staff_id,memo,local_name",
				$whereStr,
				"staff_id,deduction_id");
		return $array;
	}
	public static function getTotalDeductions($month) {
		$sql = "SELECT SUM(amount) AS total,staff_id FROM usd_deductions_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
					 StringManager::deleteHyphen($month)
					 ." AND salon_id=".$_SESSION["salon"]["id"]
							." GROUP BY staff_id";
		$array = parent::_query($sql, "select");
		return $array;
	}
	public static function getUsedDeductionsWithSalonID($month, $salonID) {
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
				StringManager::deleteHyphen($month).
				" AND salon_id=".$salonID;

		$array = parent::_select(
				"usd_deductions_info",
				"usd_dd_id AS id,deduction_id,amount,staff_id,memo,local_name",
				$whereStr,
				"staff_id,deduction_id");
		return $array;
	}
	public static function getTotalDeductionsWithSalonID($month, $salonID) {
		$sql = "SELECT SUM(amount) AS total,staff_id FROM usd_deductions_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
					 StringManager::deleteHyphen($month)
					 ." AND salon_id=".$salonID
					 ." GROUP BY staff_id";
		$array = parent::_query($sql, "select");
		return $array;
	}
	public static function getUsedDeductionsOfAllSalon($month, $salonIDs) {
		$str = " AND (";
		for($i = 0 ; $i < count($salonIDs) ; $i++){
			$subStr = " salon_id=".$salonIDs[$i];
			$str = $str.$subStr;
			if ($i < count($salonIDs) -1) {
				$str = $str." OR";
			}
		}
		$str = $str.")";

		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
				StringManager::deleteHyphen($month).$str;

		$array = parent::_select(
				"usd_deductions_info",
				"usd_dd_id AS id,deduction_id,amount,staff_id,memo,local_name",
				$whereStr,
				"staff_id,deduction_id");
		return $array;
	}
	public static function getTotalDeductionsOfAllSalon($month, $salonIDs) {
		$str = " AND (";
		for($i=0 ; $i < count($salonIDs) ; $i ++){
			$subStr = " salon_id=".$salonIDs[$i];
			$str = $str.$subStr;
			if ($i < count($salonIDs) -1) {
				$str = $str." OR";
			}
		}
		$str = $str.")";

		$sql = "SELECT SUM(amount) AS total,staff_id FROM usd_deductions_info
				 WHERE EXTRACT(YEAR_MONTH FROM month)=".
					 StringManager::deleteHyphen($month)
					 .$str
					 ." GROUP BY staff_id";
		//echo "q==".$sql;
		$array = parent::_query($sql, "select");
		return $array;
	}
	/*============================================*/
}
