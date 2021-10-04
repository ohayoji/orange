<?php
require_once 'StaffSettingModel.php';
require_once 'AddRemModel.php';
require_once 'DeductionModel.php';
require_once 'CompanySettingModel.php';
require_once 'PaymentModel.php';
require_once 'DeductionModel.php';
require_once 'RemSpecificationModel.php';

class turba_func_SalarySheetModel extends RootModel{

	//月セレクタで選択可能な月数
	const MON_COUNT = 36;
	//月リスト
	var $monthDatas;

	//サロン情報
	var $salonInfo;

	//社員情報
	var $staffs = array();
	//ローカルテスト用社員id配列
	//var $employeeIDs = array(9,10,11);
	var $employeeIDs = null;

	//手当明細
	var $addRems;
	//各スタッフ手当合計
	var $totalAddrems;
	//各スタッフ控除明細
	var $usedDeductions;

	function __construct() {

		parent::__construct();

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);

		if ($_POST) {
			$_SESSION["tu_sal_month_condition"] = $_POST["month"];
		}else {
			if (!$_SESSION["tu_sal_month_condition"]) {
				$_SESSION["tu_sal_month_condition"] =
				date("Y-m",mktime(0,0,0,date("m"),1,date("Y")));
			}
		}

		$this->salonInfo = CompanySettingModel::getSalonStatus();

		/*--社員情報作成---------*/
		$salonIDs = array();
		for($i=0; $i < count($this->salonInfo); $i ++){
			$salonIDs[$i] = $this->salonInfo[$i]['id'];
		}

    //対象sutaffuid配列
    if ($_SESSION["company"]["id"] == 1) {
      $this->employeeIDs = array(137,134,135,110,35,130,71,120,121,156,161,198,207);
    }elseif ($_SESSION["company"]["id"] == 4) {
      $this->employeeIDs = array(270);
    }


		$allStaffs = StaffSettingModel::getAllSalonStaffStatus($_SESSION["tu_sal_month_condition"]."-01", $salonIDs);
		//$allStaffs = PaymentModel::getStaffInfo();
		$count = count($allStaffs);
		for ($i = 0; $i < $count; $i++) {
			for ($n = 0; $n < count($this->employeeIDs); $n++) {
				if ($allStaffs[$i]["id"] == $this->employeeIDs[$n]) {
					//サロン名を追加
					for ($s = 0; $s < count($this->salonInfo); $s++) {
						if ($allStaffs[$i]["salon_id"] == $this->salonInfo[$s]["id"]) {
							$allStaffs[$i]["salon_name"] = $this->salonInfo[$s]["_name"];
							$allStaffs[$i]["total_incentive"] = turba_func_SalarySheetModel::getTotalIncentive($allStaffs[$i]["salon_id"], $_SESSION["tu_sal_month_condition"], $allStaffs[$i]);
							/*echo "<br>===";
							var_dump($allStaffs[$i]);
							echo "====<br>";*/
						}
					}
					//staffsに追加
					array_push($this->staffs, $allStaffs[$i]);
				}
			}
		}

		$this->addRems = AddRemModel::getAddRemsOfAllSalon($_SESSION["tu_sal_month_condition"], $salonIDs);
		$this->totalAddrems = AddRemModel::getTotalAmountsOfAllSalon(
				$_SESSION["tu_sal_month_condition"], $salonIDs);

		$this->usedDeductions = DeductionModel::getUsedDeductionsOfAllSalon(
				$_SESSION["tu_sal_month_condition"], $salonIDs);
		//var_dump($this->usedDeductions);

		/*----------------------------*/

	}

	//歩合合計をゲット
	//ほぼ同じ処理をRemSpecificationModelでやっているのでいずれ統合する
	public static function getTotalIncentive($salonID, $month, $staff){
		//伝票リスト
		$receipts = RemSpecificationModel::getReceipts($salonID, $month, $staff["id"]);
		//echo "sal:".$salonID." mon:". $month." staf".$staffID;

		//歩合合計
		$totalInc = 0;

		$count = count($receipts);
		for ($i = 0; $i < $count; $i++) {

			$rec = $receipts[$i];

			//技術歩合率設定済スタッフの場合はtec_rem_vを再設定
			if ($staff["percentage"]) {
				$rec["tec_rem_v"] = $staff["percentage"];
				//echo "<br>===P=".$staff["percentage"]."===<br>";
			}
			//echo "<br>===v=".$rec["tec_rem_v"]."===<br>";
			/*echo "r=<br>";
			var_dump($rec);
			echo "<br>";*/

			$tec = intval($rec["tec_sale"]);
			$pro = intval($rec["pro_sale"]);
			$tecRem = intval($rec["tec_rem_v"]);
			$proRem = intval($rec["pro_rem_v"]);

			//歩合計算
			$tecInc = parent::_getIncentive($tec, $tecRem);
			$proInc = parent::_getIncentive($pro, $proRem);

			$totalInc = $totalInc + $tecInc + $proInc;

		}
		//echo "<br>==totalinc=".$totalInc."==<br>";
		return $totalInc;
	}
}

//
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "update_used_deduction") {

  	unset($_POST["mode"]);

  	if ($_POST["id"]) {

  		if ($_POST["amount"] != "") {//update
  			//echo json_encode("update");
  			$id = $_POST["id"];
  			unset($_POST["id"]);

  			MySQL::_update(
  			"used_deductions",
  			MySQL::_setStringForUpdate($_POST),
  			"id=".$id);

  		}else {//delete

  			MySQL::_delete("used_deductions", "id=".$_POST["id"]);
  			echo "delete";
  		}

  	}else {//insert
  		//echo json_encode("insert");
  		MySQL::_insert(
  			"used_deductions",
  			MySQL::_columnStringForInsert($_POST),
  			MySQL::_valueStringForInsert($_POST)
  		);
  		echo "insert";
  	}
  }
}
