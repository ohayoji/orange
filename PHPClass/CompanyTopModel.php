<?php
require_once 'CompanySettingModel.php';

class CompanyTopModel extends CompanySettingModel {
	//遡る月数
	const MON_COUNT = 2;
	
	//各月のサロンレポート
	var $salonReport;
	//月セレクタのoption
	var $selectorOptions;
	
	function __construct() {
		parent::__construct();
		if($_POST){
			$_SESSION["ct_selected_month"] = $_POST["month"];
		}else{
			if(!$_SESSION["ct_selected_month"]){
				$_SESSION["ct_selected_month"] = 
				date("Y-m", strtotime($this->_todaySQLStr));
			}
		}
		$this->selectorOptions = parent::_getMonthDatasDesc(self::MON_COUNT);
		
		$this->salonReport = array();
		$month = StringManager::deleteHyphen($_SESSION["ct_selected_month"]);
		for ($i = 0; $i < count($this->salonInfo); $i++) {
			$salonId = $this->salonInfo[$i]["id"];
			$report = parent::_getMonthlyData($salonId, $month);
			$report[0]["_name"] = $this->salonInfo[$i]["_name"];
			array_push($this->salonReport, $report[0]);
		}
	}
	
	/*==DB===========================================*/
	
	/*================================================*/
}