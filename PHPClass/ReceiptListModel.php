<?php
header("Content-Type: text/html; charset=UTF-8");
require_once 'SalonSettingModel.php';
require_once 'PlanManager.php';
class ReceiptListModel extends RootModel{

	var $planManager;

	//月セレクタでさかのぼる月数
	const MON_COUNT = 15;
	//月リスト
	var $monthDatas;
	//スタッフリスト
	var $staffs;
	//伝票リスト
	var $receipts;
	//リストの合計売上
	var $totalSale = 0;
	//有効伝票項目配列
	var $recEntries;
	//usd_menu_info検索時のwhere文
	var $whereStrForUSDM = "";


	function __construct() {
		parent::__construct();

		$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		//POST処理
		if ($_POST) {
      //$this->_showPost();
			//伝票リスト検索条件を保持するセッションをセット
			$_SESSION["rec_list_condition"] = $_POST;
		}else {
			if (empty($_SESSION["rec_list_condition"])) {
				//初回訪問時の検索条件設定
				$_SESSION["rec_list_condition"]["today"] = $this->_todaySQLStr;
				if ($this->_visiter == "salon") {
					$_SESSION["rec_list_condition"]["rem_comp"] = "0";
				}
			}

			/*--来店処理モードに変更-----------*/
			if ($this->_visiter == "salon" && !empty($_GET["rem_comp_check"])) {
				//一度conditionを削除
				unset($_SESSION["rec_list_condition"]);

				//過去30日間&会計済み&来店処理待ち
				//３０日前の日付
				$past30 = StringManager::getSqlDateByDateCount(-30);
				//echo $past30;

				$_SESSION["rec_list_condition"]["start_month"] = date('Y-m', strtotime($past30));
				$_SESSION["rec_list_condition"]["start_date"] = date('d', strtotime($past30));
				$_SESSION["rec_list_condition"]["end_month"] = "0";
				$_SESSION["rec_list_condition"]["end_date"] = "0";
				$_SESSION["rec_list_condition"]["rec_comp"] = "1";
				$_SESSION["rec_list_condition"]["rem_comp"] = "0";
				//var_dump($_SESSION["rec_list_condition"]);
			}
			/*-------------------------------*/
		}

		$this->receipts = $this->getReceiptList();


		/*------------------------------------------
		 * startを日付文字列に変換
		 * $whereStrForUSDMを作成
		 * それぞれに空のメニュー配列も作成
		 * 合計売上要素を追加
		 * 総合計売上金額を作成
		 ---------------------------------------------*/
		$count = count($this->receipts);

		for ($i = 0; $i < $count; $i++) {
			//startを日付文字列に変換
			$str = date('n月j日', strtotime($this->receipts[$i]["start"]));
			$this->receipts[$i]["start"] = $str;

			//$whereStrForUSDMを作成
			$this->whereStrForUSDM =
				$this->whereStrForUSDM."rec_id=".$this->receipts[$i]["rec_id"];
			if ($i < $count -1) {
				$this->whereStrForUSDM =
					$this->whereStrForUSDM." OR ";
			}

			//それぞれに空のメニュー配列も作成
			$this->receipts[$i]["menu_imgs"] = array();

			//合計売上要素を追加
			$this->receipts[$i]["sale"] =
				$this->receipts[$i]["tec_sale"] + $this->receipts[$i]["pro_sale"];

			//売上金額を合計する
			$this->totalSale =
				$this->totalSale + $this->receipts[$i]["sale"];
		}
		//echo $this->totalSale;
		/*---------------------------------------*/
		//伝票リストにメニュー情報を追加
		$this->addUsdMenus();


		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);
		if ($this->_visiter == "salon") {
			//$this->staffs = parent::_getStaffs($_SESSION["salon"]["id"]);
			$this->staffs = parent::_getStaffsWithDeleted($_SESSION["salon"]["id"]);
		}

		$this->recEntries = SalonSettingModel::getReceiptEntries();

	}

	/*--DB-----------------------------------------*/
	//伝票リスト取得
	function getReceiptList() {
    $colStr = "rec_id,start,DATE_FORMAT(start , '%w') as weekday,staff_icon,costomer,num_visit,tec_sale,pro_sale,rem_comp,free_v";
		$whereStr = self::getWhereStr();

		$array = parent::_select("rec_info_".$_SESSION["salon"]["id"],
				$colStr, $whereStr, "start");
		return $array;
	}
	/*--whereStr-----------*/
	private function getWhereStr() {
		$s = $_SESSION["rec_list_condition"];


		$whereStr = "rec_comp=1";
		//日付
		$dateCon = self::getDateCondition($s);
		$whereStr = $whereStr.$dateCon;

    //曜日
    $weekdayParams = array();
    $isAdded = false; //一つでも追加されればtrue
    for ($i=0; $i <= 6; $i++) {
      if (!empty($s['weekday_' . $i])) {
        //array_push($weekdayParams, $i);
        if (!$isAdded) {
          $whereStr .= ' AND ' . '(';
          $isAdded = true;
        }else {
          $whereStr .= ' OR ';
        }
        $whereStr .= "DATE_FORMAT(start , '%w') = " . $i;
      }
    }
    if ($isAdded) $whereStr .= ')';


		//スタッフid
		if ($this->_visiter == "staff") {
			$whereStr = $whereStr." AND staff_id=".$_SESSION["staff"]["id"];

		}else {
			if (!empty($s["staff_id"])) {
				$whereStr = $whereStr." AND staff_id=".$s["staff_id"];

			}
		}

		//売上
		if (!empty($s["start_sale"])) {
			$whereStr = $whereStr." AND tec_sale>=".$s["start_sale"];

		}

		if (!empty($s["end_sale"])) {
			$whereStr = $whereStr." AND tec_sale<=".$s["end_sale"];

		}
		//来店回数
    if (isset($s["num_visit"])) {
      if ($s["num_visit"] != null && $s["num_visit"] != 99) {
  			$whereStr = $whereStr." AND num_visit=".$s["num_visit"];

  		}
    }

		//支払方法
    if (isset($s["pay_type"])) {
      if ($s["pay_type"] != null && $s["pay_type"] != 99) {
  			$whereStr = $whereStr." AND pay_type=".$s["pay_type"];

  		}
    }

		//登録状態
    if (isset($s["rem_comp"])) {
      if ($s["rem_comp"] != null && $s["rem_comp"] != 99) {
  			$whereStr = $whereStr." AND rem_comp=".$s["rem_comp"];

  		}
    }
    //指名／フリー
    if (isset($s["free_v"])) {
      if ($s["free_v"] != null && $s["free_v"] != 99) {
        $whereStr = $whereStr." AND free_v=".$s["free_v"];

      }
    }
		//net
    if (!empty($s['net'])) {
      $whereStr = $whereStr." AND net_id IS NOT NULL";

    }
		//point
    if (!empty($s['point'])) {
      $whereStr = $whereStr." AND point_id IS NOT NULL";

    }

		//other_net
    if (!empty($s['other_net'])) {
      $whereStr = $whereStr." AND other_net_id IS NOT NULL";

    }

		return $whereStr;
	}
	//予約開始日付（start）条件
	private static function getDateCondition($s) {

		if (!empty($s["today"])) {//今日が選択されていたら今日
			//var_dump($s);
			return " AND start>='".$s["today"]." 00:00:00' AND
					start<='".$s["today"]." 23:59:59'";
		}

		$dateCon = "";
		if ($s["start_month"] == 0 && $s["end_month"] == 0) {
			//何もしない
		}else {
			/*--まず日付文字列を作成--*/
			if ($s["start_date"] == 0) {//開始日付指定なし
				$sdt = "-01 00:00:00";
			}else {//開始日付指定あり
				$sdt = "-".sprintf("%02d", $s["start_date"])." 00:00:00";
			}
			if ($s["end_date"] == 0) {//終了日付指定なし
				$edt = "-31 23:59:59";
			}else {//終了日付指定あり
				$edt = "-".sprintf("%02d", $s["end_date"])." 23:59:59";
			}
			/*--------------------------*/

			if ($s["start_month"] != 0 && $s["end_month"] != 0) {
				//両方指定の場合
				$dateCon = " AND start>='".$s["start_month"].$sdt.
						"' AND start<='".$s["end_month"].$edt."'";
			}else {
				if ($s["start_month"] != 0) {//開始月のみ指定
					$dateCon = " AND start>='".$s["start_month"].$sdt."'";
				}else {//終了月のみ指定
					$dateCon = " AND start<='".$s["end_month"].$edt."'";
				}
			}
		}
		return $dateCon;
	}
	/*---------------------*/

	//使用されているメニュー情報取得
	private function getUsdMenus() {
		$array = parent::_select(
				"usd_menu_info_".$_SESSION["salon"]["id"],
				"rec_id,on_img", $this->whereStrForUSDM, "rec_id,menu_id");

		return $array;
	}
	//メニュー詳細配列を伝票リストに統合
	private function addUsdMenus() {
		$usdMenus = $this->getUsdMenus();
		//var_dump($usdMenus);

		$count = count($usdMenus);
		for ($i = 0; $i < $count; $i++) {

			$recCount = count($this->receipts);
			for ($n = 0; $n < $recCount; $n++) {
				if ($usdMenus[$i]["rec_id"] == $this->receipts[$n]["rec_id"]) {
					array_push($this->receipts[$n]["menu_imgs"], $usdMenus[$i]["on_img"]);
				}
			}
		}
	}
	/*----------------------------------------------*/

}
