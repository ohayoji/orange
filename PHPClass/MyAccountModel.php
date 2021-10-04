<?php
require_once 'SalonSettingModel.php';
require_once 'MailManager.php';
//require_once 'PlanManager.php';

class MyAccountModel extends SalonSettingModel {

	//プランマネージャ
	var $planManager;

	var $remainderDays;
	/*--webpay用プロパティ--*/
	var $price;//１ヶ月目の日割り料金
	var $craimDateStamp;//定期課金開始日のDATESTAMP
	/*----------------------*/
	//プレミアム登録成功フラグ
	var $premiumSuccess = NULL;


	function __construct() {
		parent::__construct();

		//SalonSettingModelで生成済み
		//$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		$this->price = $this->planManager->planStartMonthChargePrice;
		$craimDate = $this->planManager->planStartNextMonth."-".parent::CRAIM_DAY;
		$this->craimDateStamp = strtotime($craimDate);

		/*--メール変更処理-----------------------*/
    if (!empty($_POST["e_mail"])) {
      $eMail = $_POST["e_mail"];
  		if ($eMail) {
  			//parent::_showPost();
  			MailManager::sendChangeE_mail(
  						"m_upd",
  						"salon",
  						$eMail,
  						parent::_pinchRandStr($_SESSION["salon"]["id"]));
  		}
    }

		/*---------------------------------------*/

		$this->remainderDays =
			StringManager::getDayDifferenceByTwoSQLDate(
					$this->_todaySQLStr, $this->planManager->trialLastDate) +1;
	}
}
