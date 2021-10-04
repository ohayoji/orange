<?php
require_once 'RootModel.php';
require_once 'PlanManager.php';

class RemSpecificationModel extends RootModel{

	//プランマネージャ
	var $planManager;

	//月セレクタで選択可能な月数
	const MON_COUNT = 36;
	//月リスト
	var $monthDatas;

	//staffステータス
	var $status;
	//当月合計レポート
	var $staffReport;
	//伝票明細
	var $receipts;
	//手当リスト
	var $addRems;
	//控除科目リスト
	var $deductions = array();
	//控除リスト
	var $usedDeductions = array();
	//承認ボタン表示フラグ
	var $appBtnFlag = true;
	//報酬承認済みフラグ
	var $approvedFlag;

	function __construct() {
		parent::__construct();

		$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		if ($_POST) {
			$_SESSION["rs_month_condition"] = $_POST["month"];
		}else {
			if (empty($_SESSION["rs_month_condition"])) {
				$_SESSION["rs_month_condition"] =
					date("Y-m",mktime(0,0,0,date("m"),1,date("Y")));
			}
		}

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);

		$dateStr = $_SESSION["rs_month_condition"]."-01";
		$this->status = $_SESSION["staff"];
		$this->status["salary"] = parent::_getStaffSalary($dateStr, $_SESSION["staff"]["id"]);
		$this->status["percentage"] = parent::_getStaffPercentage($dateStr, $_SESSION["staff"]["id"]);
		//var_dump($this->status);


		$this->receipts = self::getReceipts($_SESSION["salon"]["id"],$_SESSION["rs_month_condition"],$_SESSION["staff"]["id"]);
		$this->setStaffReport_Receipts();

		$this->addRems = self::getAddRems();

		$this->usedDeductions = self::getUsedDeductions();

		if ($_SESSION["rs_month_condition"] ==
				date("Y-m",mktime(0,0,0,date("m"),1,date("Y")))) {
				//承認ボタン表示フラグをfalseに
				$this->appBtnFlag = false;
		}
		$this->approvedFlag = self::getCountApprovedRems();
	}

	/*----------------------------------------------*/
	/*-----------------------------------
	 * 報酬計算
	 * 計算方式を統一するために必ず
	 * RootModelクラスの_getIncentive()を使用する
	 * !!これ以外の方法で報酬を計算する事を禁止!!
	 */
	function setStaffReport_Receipts() {
		//スタッフ合計売上
		$totalSales = 0;
		//スタッフ合計歩合
		$totalInc = 0;

		$count = count($this->receipts);
		for ($i = 0; $i < $count; $i++) {
			//技術歩合率設定済スタッフの場合はtec_rem_vを再設定
			if ($this->status["percentage"]) {
				$this->receipts[$i]["tec_rem_v"] = $this->status["percentage"];
			}
			$rec = $this->receipts[$i];

			$tec = intval($rec["tec_sale"]);
			$pro = intval($rec["pro_sale"]);
			$tecRem = intval($rec["tec_rem_v"]);
			$proRem = intval($rec["pro_rem_v"]);

			//合計売上計算
			$totalSales = $totalSales + $tec + $pro;

			//歩合計算
			$tecInc = parent::_getIncentive($tec, $tecRem);
			$proInc = parent::_getIncentive($pro, $proRem);

			$totalInc = $totalInc + $tecInc + $proInc;

			//receiptsに追加
			$this->receipts[$i]["tec_inc"] = $tecInc;
			$this->receipts[$i]["pro_inc"] = $proInc;
		}

		$stRep["total_sale"] = $totalSales;
		$stRep["salary"] = $this->status["salary"];
		$stRep["total_inc"] = $totalInc;
		$stRep["add_rem"] = self::getTotalAdrm();
		$stRep["used_deductions"] = self::getTotalUsedDeductions();

		$stRep["total_rem"] =
			$stRep["salary"] +$stRep["total_inc"]
			+$stRep["add_rem"] -$stRep["used_deductions"];

		$this->staffReport = $stRep;
	}
	/*--------------------------------------------------*/

	/*==DB==================================================*/

	//スタッフの当月伝票
	public static function getReceipts($salonID, $month, $staffID) {
		$colStr = "costomer,tec_sale,pro_sale,tec_rem_v,pro_rem_v,rem_comp,
				EXTRACT(DAY FROM start) AS date";
		$whereStr = "EXTRACT(YEAR_MONTH FROM start)="
				.StringManager::deleteHyphen($month)
				." AND rec_comp=1 AND staff_id=".$staffID;
		$array = parent::_select(
				"rec_info_".$salonID,
				$colStr, $whereStr, "date");
		return $array;
	}
	//当月手当合計
	public static function getTotalAdrm() {
		$colStr = "SUM(amount) AS total";
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)="
				.StringManager::deleteHyphen($_SESSION["rs_month_condition"])
				." AND salon_id=".$_SESSION["salon"]["id"]
				." AND staff_id=".$_SESSION["staff"]["id"];
		$array = parent::_select("add_rems_info", $colStr, $whereStr);
		return $array[0]["total"];
	}

	//手当明細
	public static function getAddRems() {

		$colStr = "title,amount";
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
				StringManager::deleteHyphen($_SESSION["rs_month_condition"])
				." AND salon_id=".$_SESSION["salon"]["id"]
				." AND staff_id=".$_SESSION["staff"]["id"];
		$array = parent::_select("add_rems_info", $colStr, $whereStr, "add_rem_id");

		return $array;
	}
	//当月控除合計
	public static function getTotalUsedDeductions() {
		$colStr = "SUM(amount) AS total";
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)="
				.StringManager::deleteHyphen($_SESSION["rs_month_condition"])
				." AND staff_id=".$_SESSION["staff"]["id"];
		$array = parent::_select("used_deductions", $colStr, $whereStr);
		return $array[0]["total"];
	}
	//控除明細
	public static function getUsedDeductions() {

		$colStr = "deduction_id,amount";
		$whereStr = "EXTRACT(YEAR_MONTH FROM month)=".
				StringManager::deleteHyphen($_SESSION["rs_month_condition"])
				." AND staff_id=".$_SESSION["staff"]["id"];
		$array = parent::_select("used_deductions", $colStr, $whereStr, null);

		return $array;
	}

	/*--approved_rems-------------------------*/
	//レコード追加
	public static function insert_app_rems() {
		$colVal = $_POST;
		unset($colVal["mode"]);
		$colVal["staff_id"] = $_SESSION["staff"]["id"];

		$res = parent::_insert("approved_rems",
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
		return $res;
	}
	//レコード存在チェック
	public static function getCountApprovedRems() {
		return parent::_count("approved_rems", "id",
				"EXTRACT(YEAR_MONTH FROM month)="
				.StringManager::deleteHyphen($_SESSION["rs_month_condition"])
				." AND staff_id=".$_SESSION["staff"]["id"]);
	}
	/*------------------------------------------*/
	/*========================================================*/
}
