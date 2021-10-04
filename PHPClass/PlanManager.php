<?php
require_once 'RootModel.php';

class PlanManager extends RootModel {
	
	var $planStatus;
	var $localPlanName;
	
	var $inTrialPeriod = FALSE;//お試し期間中かどうか
	
	var $price;
	
	/*--日付プロパティ--*/
	//数値,date型プロパティ
	var $today;
	var $trialLastDate;
	var $planStartDate;
	var $planStartMonth;
	var $planStartMonthLastDate;
	var $planStartMonthChargeDays;
	var $planStartMonthChargePrice;
	var $planStartNextMonth;
	
	//日本語プロパティ
	var $today_JP;
	var $trialLastDate_JP;
	var $planStartDate_JP;
	var $planStartMonthLastDate_JP;
	var $planStartNextMonth_JP;
	/*-----------------*/
	
	function __construct($salonId){
		//parent::__construct();
		
		$this->price = parent::STANDARD_PRICE;
		
		//今日プロパティ
		$this->today = StringManager::getSqlDateByDateCount(0);
		//$this->today = "2015-04-03";
		$this->today_JP = date("Y年m月d日(",strtotime($this->today)).StringManager::getJapaneseDayBySQLDate($this->today).")";
		
		if ($salonId) {//orangeログイン時
			$array = parent::_select(
					"salons", "admission_date,plan,admission_button_type,recursion_id,customer_id,charge_id",
					"id=".$salonId);
			$this->planStatus = $array[0];
			
			//trialLastDateを先にセット
			$this->setTrialLastDate($this->planStatus["admission_date"]);
			
			//お試し期間中なら$inTrialPeriodをtrueに書き換え
			if ($this->today <= $this->trialLastDate) {//お試し期間内
				$this->inTrialPeriod = true;
			}else {//お試し期間終了後
				//お試し期間外の場合はtrialLastDateを昨日に書き換え
				$this->trialLastDate = StringManager::getSqlDateByDateCount(-1);
			}
			
			//日付プロパティをセット
			$this->setDateProperties();
			
			
			
			//お試し期間を過ぎているのにplanがtrialの場合はplanを書き換え
			/*if ($this->inTrialPeriod == false && $this->planStatus["plan"] == "trial") {
				//echo "お試し期間を過ぎているのにplanがtrial";
				if ($this->planStatus["recursion_id"]
						&& $this->planStatus["customer_id"]
						&& $this->planStatus["charge_id"]) {//全てのカード情報があればpremium
					//planをpremiumへ変更する
					parent::_update("salons", "plan = 'premium'", "id = ".$salonId);
					$this->planStatus["plan"] = "premium";
					
				}else {//カード情報がなければfree
					//planをfreeへ変更する
					parent::_update("salons", "plan = 'free'", "id = ".$salonId);
					$this->planStatus["plan"] = "free";
				}
			}*/
			
			//ローカルネームをセット
			if ($this->planStatus["plan"] == "trial") {
				$this->localPlanName = "お試し期間（９０日）";
			}elseif ($this->planStatus["plan"] == "free") {
				$this->localPlanName = "無料プラン";
			}elseif ($this->planStatus["plan"] == "premium") {
				$this->localPlanName = "プレミアムプラン";
			}
			
		}else {//非ログイン時
			$this->setTrialLastDate($this->today);
			$this->setDateProperties();
		}
	}
	
	//trialLastDateをセット
	function setTrialLastDate($admissionDate) {
		$plusDays = parent::FREE_DAYS -1;
		$this->trialLastDate =
			date("Y-m-d",strtotime("+".$plusDays." day",strtotime($admissionDate)));
	}
	
	//日付プロパティをセット
	function setDateProperties(/*$admissionDate*/) {
		
		
		/*--数値,date型プロパティ--------------*/
		//$plusDays = parent::FREE_DAYS -1;
		/*$this->trialLastDate =
			date("Y-m-d",strtotime("+".$plusDays." day",strtotime($admissionDate)));*/
		$this->planStartDate =
			date("Y-m-d",strtotime("+1 day",strtotime($this->trialLastDate)));
			
		$planStartYear = date("Y",strtotime($this->planStartDate));
		$this->planStartMonth = date("m",strtotime($this->planStartDate));
		$planStartMonthDays = 
			StringManager::getDaysAtMonthByYear_Month($planStartYear, $this->planStartMonth);
		$this->planStartMonthLastDate = 
			date("Y-m-d",mktime(0,0,0,$this->planStartMonth,$planStartMonthDays,$planStartYear));
		/*$this->planStartMonthChargeDays = 
			$planStartMonthDays - date("d",strtotime($this->trialLastDate));*/
		$this->planStartMonthChargeDays =
			StringManager::getDayDifferenceByTwoSQLDate($this->trialLastDate, $this->planStartMonthLastDate);
		$this->planStartMonthChargePrice =
			floor(parent::STANDARD_PRICE / $planStartMonthDays * $this->planStartMonthChargeDays);
		$this->planStartNextMonth =
			date("Y-m",mktime(0,0,0,$this->planStartMonth,$planStartMonthDays+1,date("Y",strtotime($this->planStartDate))));
		/*---------------------------------------*/
		
		/*--日本語プロパティ---------------------*/
		//今日
		//$this->today = StringManager::getSqlDateByDateCount(0);
		//$this->today_JP = date("Y年m月d日(",strtotime($this->today)).StringManager::getJapaneseDayBySQLDate($this->today).")";
		$this->trialLastDate_JP =
			date("Y年m月d日(",strtotime($this->trialLastDate)).StringManager::getJapaneseDayBySQLDate($this->trialLastDate).")";
		$this->planStartDate_JP =
			date("Y年m月d日(",strtotime($this->planStartDate)).StringManager::getJapaneseDayBySQLDate($this->planStartDate).")";
		$this->planStartMonthLastDate_JP = 
			date("Y年m月d日(",strtotime($this->planStartMonthLastDate)).StringManager::getJapaneseDayBySQLDate($this->planStartMonthLastDate).")";
		$this->planStartNextMonth_JP =
			date("Y年m月",strtotime($this->planStartNextMonth));
		/*------------------------------------*/
	}
}