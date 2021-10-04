<?php
require_once 'StaffSettingModel.php';
require_once 'AddRemModel.php';
require_once 'DeductionModel.php';
require_once 'CompanySettingModel.php';
class StaffReportModel extends RootModel{

	//月セレクタで選択可能な月数
	const MON_COUNT = 36;
	//月リスト
	var $monthDatas;

	var $staffs;
	var $staffReports;
	var $salonInfo;

	function __construct() {

		parent::__construct();

		if ($_POST) {
			$_SESSION["sr_month_condition"] = $_POST["month"];
		}else {
			if (empty($_SESSION["sr_month_condition"])) {
				$_SESSION["sr_month_condition"] =
					date("Y-m",mktime(0,0,0,date("m"),1,date("Y")));
			}
		}

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);


    $this->staffs = StaffSettingModel::getSalonStaffStatus(
        $_SESSION["sr_month_condition"]."-01");

  /*--スタッフレポート-----------*/
    $this->staffReports = array();

    $receipts = self::getReceipts();

    $addRems = AddRemModel::getTotalAmounts($_SESSION["sr_month_condition"]);
    $totalDeductions = DeductionModel::getTotalDeductions($_SESSION["sr_month_condition"]);
    $paidRems = parent::_select(
        "approved_rems","staff_id",
        "month='".$_SESSION["sr_month_condition"]."-01' AND paid=1 AND (".self::getStaffWhereStr().")");

    $count = count($this->staffs);
    for ($i = 0; $i < $count; $i++) {
      $stRep = $this->getStaffReport(
          $this->staffs[$i], $receipts, $addRems, $totalDeductions, $paidRems);
      if ($stRep) {
        array_push($this->staffReports,$stRep);
      }
    }
	}

	/*-------------------------------------------------------*/
	/*-----------------------------------
	 * 報酬計算
	 * 計算方式を統一するために必ず
	 * RootModelクラスの_getIncentive()を使用する
	 * !!これ以外の方法で報酬を計算する事を禁止!!
	 */
	function getStaffReport($staff,$receipts,$addRems,$totalDeductions,$paidRems) {
		//スタッフ合計売上
		$totalSales = 0;
		//スタッフ合計歩合
		$totalInc = 0;

		$count = count($receipts);
		for ($i = 0; $i < $count; $i++) {
			$rec = $receipts[$i];

			if ($staff["id"] == $rec["staff_id"]) {

				$tec = intval($rec["tec_sale"]);
				$pro = intval($rec["pro_sale"]);
				$tecRem = intval($rec["tec_rem_v"]);
				$proRem = intval($rec["pro_rem_v"]);

				//合計売上計算
				$totalSales = $totalSales + $tec + $pro;

				//歩合計算
				if ($staff["percentage"]) {//技術歩合率設定済スタッフ
					$per = intval($staff["percentage"]);
					$tecInc = parent::_getIncentive($tec, $per);
					$proInc = parent::_getIncentive($pro, $proRem);

				}else {//技術歩合率未設定スタッフ
					$tecInc = parent::_getIncentive($tec, $tecRem);
					$proInc = parent::_getIncentive($pro, $proRem);

				}
				$totalInc = $totalInc + $tecInc + $proInc;
			}
		}

		if ($staff["deleted"] == 1 && $totalSales == 0) {
			//削除済みスタッフで売上が0の場合はnull
			$stRep = null;
		}else {
			$stRep["staff_name"] = $staff["_name"];
			//大文字に変換
			$stRep["position"] = strtoupper($staff["position"]);
			$stRep["total_sale"] = $totalSales;
			$stRep["salary"] = $staff["salary"];
			$stRep["total_inc"] = $totalInc;
			$stRep["add_rem"] = 0;
			for ($i = 0; $i < count($addRems); $i++) {
				if ($staff["id"] == $addRems[$i]["staff_id"]) {
					$stRep["add_rem"] = $addRems[$i]["total"];
				}
			}
			$stRep["deduction"] = 0;
			for ($i = 0; $i < count($totalDeductions); $i++) {
				if ($staff["id"] == $totalDeductions[$i]["staff_id"]) {
					$stRep["deduction"] = $totalDeductions[$i]["total"];
				}
			}
			$stRep["paid"] = "";
			for ($i = 0; $i < count($paidRems); $i++) {
				if ($staff["id"] == $paidRems[$i]["staff_id"]) {
					$stRep["paid"] = "済";
				}
			}
			$stRep["total_rem"] =
				$stRep["salary"] +$stRep["total_inc"]
				+$stRep["add_rem"] -$stRep["deduction"];
		}


		return $stRep;
	}
	/*--------------------------------------------------------*/

	/*==DB=================================================*/
	public static function getReceipts() {
		$colStr = "staff_id,tec_sale,pro_sale,tec_rem_v,pro_rem_v";
		$whereStr = "EXTRACT(YEAR_MONTH FROM start)="
				.StringManager::deleteHyphen($_SESSION["sr_month_condition"])
				." AND rec_comp=1";
		$array = parent::_select(
				"rec_info_".$_SESSION["salon"]["id"],
				$colStr, $whereStr, "staff_id");
		return $array;
	}
	function getStaffWhereStr() {
		$staffWhereStr = "";
		$count = count($this->staffs);
		for ($i = 0; $i < $count; $i++) {
			$staffWhereStr = $staffWhereStr."staff_id=".$this->staffs[$i]["id"];
			if ($i < $count -1) {
				$staffWhereStr = $staffWhereStr." OR ";
			}
		}
		return $staffWhereStr;
	}
	public static function getAllSalonReceipts($salonIDs) {
		$arrayAll = [];
		$colStr = "staff_id,tec_sale,pro_sale,tec_rem_v,pro_rem_v";
		$whereStr = "EXTRACT(YEAR_MONTH FROM start)="
				.StringManager::deleteHyphen($_SESSION["sr_month_condition"])
				." AND rec_comp=1";

		$array = parent::_select(
				"rec_info_".$salonIDs[0],
				$colStr, $whereStr, "staff_id");

		for($i=0; $i < count($salonIDs); $i++){
			$array = parent::_select(
					"rec_info_".$salonIDs[$i],
					$colStr, $whereStr, "staff_id");
			$arrayAll = array_merge_recursive($arrayAll, $array);
		}


		return $arrayAll;
	}
	/*=====================================================*/
}
