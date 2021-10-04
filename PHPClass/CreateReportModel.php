<?php
require_once 'RootModel.php';
require_once 'CompanySettingModel.php';
require_once 'MonthlyReportModel.php';
require_once 'DailyReportModel.php';

class CreateReportModel extends RootModel{

	//月セレクタで選択可能な月数
	const NEW_MONTH = -1;
	const OLD_MONTH = -36;
	//月リスト
	var $monthDatas;
	//レポート
	var $report = null;
	//レポートID
	var $reportId;
	//自動計算売上
	var $autoCalcSales = NULL;

	var $usingSubjects;

	/*--会社ログイン用プロパティ---*/
	//サロン情報配列
	var $salonInfo;
	/*------------------------*/

	//月報作成完了フラグ
	var $createCompFlag = FALSE;

	function __construct() {
		parent::__construct();

		if ($this->_visiter == "salon") {
			$this->salonInfo = array($_SESSION["salon"]);
			$this->usingSubjects = MonthlyReportModel::getSalonUsingSubjects($_SESSION["salon"]["id"]);
		}else {
			$this->salonInfo = CompanySettingModel::getSalonStatus();
			$this->usingSubjects = MonthlyReportModel::getCompanyUsingSubjects($this->salonInfo);
		}

		if ($_POST) {
			//parent::_showPost();
			if ($this->update_mon_rep()) {
				$this->createCompFlag = true;
			}
		}else {

			if (empty($_SESSION["cr_repo_conndition"])) {

				//初回訪問時はサロンと先月をセットする
				if ($this->_visiter == "company") {//会社ログイン時
					$_SESSION["cr_repo_conndition"]["salon"] =
								$this->salonInfo[0]["id"];
				}else {//サロンログイン時
					$_SESSION["cr_repo_conndition"]["salon"] =
								$_SESSION["salon"]["id"];
				}

				$_SESSION["cr_repo_conndition"]["month"] =
					date("Y-m",mktime(0,0,0,date("m")-1,1,date("Y")));
			}
		}

		$this->monthDatas = parent::_getRangeMonthDatasDesc(
					self::NEW_MONTH, self::OLD_MONTH);

		//レポート
		$report = $this->getMonthlyReport();
		//勘定科目配列


		if ($report[0]) {//レコードあり
			$this->report = $report[0];
			$this->reportId = $this->report["id"];
			unset($this->report["id"]);
		}else {//レコードなし
			//空のレコードを挿入
			$res = $this->insert_mon_rep();
			//レポートIDをセット
			$this->reportId = $res;
		}

		//自動計算売上
		$totalReport = DailyReportModel::getTotalReport(
				$_SESSION["cr_repo_conndition"]["salon"], $_SESSION["cr_repo_conndition"]["month"]);
		//var_dump($totalReport);
		$this->autoCalcSales["tec_sales"] = $totalReport["tec_sale"];
		$this->autoCalcSales["pro_sales"] = $totalReport["pro_sale"];
	}

	/*==DB===========================================*/
	//月合計売上計算
	//空の月報レコードを挿入
	function insert_mon_rep() {
		$res = parent::_insert(
				"monthly_reports",
				"salon_id,month",
				$_SESSION["cr_repo_conndition"]["salon"].",'"
				.$_SESSION["cr_repo_conndition"]["month"]."-01'");
		return $res;
	}
	//update
	function update_mon_rep() {
		$id = $_POST["rep_id"];
		unset($_POST["rep_id"]);
		$colVal = $_POST;
		$res = parent::_update("monthly_reports",
				parent::_setStringForUpdate($colVal),
				"id=".$id);
		return $res;
	}
	//月報データを取得
	function getMonthlyReport() {
		$month = StringManager::deleteHyphen(
				$_SESSION["cr_repo_conndition"]["month"]);

		$colStr = "id,month";
		//使用されている科目だけ取得
		for ($i = 0; $i < count($this->usingSubjects); $i++) {
			$colStr = $colStr.",".$this->usingSubjects[$i]["_name"];
		}
		$whereStr = "salon_id="
				.$_SESSION["cr_repo_conndition"]["salon"].
				" AND EXTRACT(YEAR_MONTH FROM month)=".$month;

		$array = parent::_select(
				"monthly_reports",$colStr, $whereStr);
		return $array;
	}
	/*================================================*/
}
//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "change") {
  	//セッションを切り替える
  	$_SESSION["cr_repo_conndition"]["salon"] = $_POST["salon"];
  	$_SESSION["cr_repo_conndition"]["month"] = $_POST["month"];
  }
}
