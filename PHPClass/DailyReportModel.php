<?php
require_once 'RootModel.php';
require_once 'PlanManager.php';
require_once 'ReserveModel.php';

class DailyReportModel extends RootModel{

	//プランマネージャ
	var $planManager;

	//月セレクタでさかのぼる月数
	const MON_COUNT = 12;
	//日報データ
	var $dairyReport;
	//月集計データ
	var $totalReport;

	//今日の予約数
	var $todayReserves;
	//今日の会計済み予約数
	var $todayRecCompReserves;
	//今日の未会計予約数
	var $todayNotRecCompReserves;

	function __construct() {
		//$rap = parent::_rapTime("a",0);
		parent::__construct();
		//$rap = parent::_rapTime("b",$rap);

		$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		//月切り替えPOST処理
		if ($_POST) {
			//伝票リスト検索条件を保持するセッションをセット
			$_SESSION["dr_selected_month"] = $_POST["month"];
		}else {
			if (!$_SESSION["dr_selected_month"]) {
				//初回訪問時の検索条件設定
				$_SESSION["dr_selected_month"] =
						date("Y-m",strtotime($this->_todaySQLStr));
			}
		}
		//$rap = parent::_rapTime("c",$rap);
		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);
		//$rap = parent::_rapTime("d",$rap);
		$this->dairyReport = $this->getDailyReport();
		//$this->totalReport = self::getTotalReport();
		$this->totalReport = self::getTotalReport($_SESSION["salon"]["id"],$_SESSION["dr_selected_month"]);
		//$rap = parent::_rapTime("e",$rap);

		$this->todayReserves = ReserveModel::getCountReservs($_SESSION["salon"]["id"], $this->_todaySQLStr);
		$this->todayRecCompReserves = ReserveModel::getCountRecCompReserves($_SESSION["salon"]["id"], $this->_todaySQLStr);
		$this->todayNotRecCompReserves = ReserveModel::getCountNotRecCompReservs($_SESSION["salon"]["id"], $this->_todaySQLStr);
	}

	/*==DB====================================================*/
	public static function getDailyReport() {
		//$_SESSION["dr_selected_month"]を編集　
		$str = StringManager::deleteHyphen($_SESSION["dr_selected_month"]);

		$array = parent::_select(
				"daily_report_info_".$_SESSION["salon"]["id"],
				"DAY(date) AS date,dayname,tec_sale,pro_sale,
				cash_tec,card_tec,cash_pro,card_pro,e_money_tec,e_money_pro,count",
				"EXTRACT(YEAR_MONTH FROM date)=".$str,
				null);

		return $array;
	}
	public static function getTotalReport($salonId,$month) {
		//$_SESSION["dr_selected_month"]を編集　
		$str = StringManager::deleteHyphen($month);

		$sql = "SELECT
				SUM(tec_sale) AS tec_sale,SUM(pro_sale) AS pro_sale,
				SUM(cash_tec) AS cash_tec,
				SUM(card_tec) AS card_tec,
        SUM(e_money_tec) AS e_money_tec,
				SUM(cash_pro) AS cash_pro,
				SUM(card_pro) AS card_pro,
        SUM(e_money_pro) AS e_money_pro,
				SUM(count) AS count
				 FROM daily_report_info_".$salonId.
						 " WHERE EXTRACT(YEAR_MONTH FROM date)=".$str;

		$array = parent::_query($sql, "select");

		return $array[0];
	}
	/*==========================================================*/
}
