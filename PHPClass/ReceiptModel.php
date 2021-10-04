<?php
require_once 'SalonSettingModel.php';
require_once 'ReserveModel.php';

class ReceiptModel extends SalonSettingModel{

	//伝票レコード
	var $receipt;
	//商品データ
	var $usdProducts;
	//日付文字列
	var $dateStr;
	//有効伝票項目（net,point,free,student）
	var $usgRecEntries;

	/*-----------各種制限・許可フラグ-----------*/
	/*デフォルト状態
	 * 報酬入力：禁止
	 * 合計金額編集：許可
	 * 仮登録（ボタン表示）:許可
	 * 管理者登録（ボタン表示）:禁止
	 */
	//報酬入力許可フラグ
	var $tecRemInputFlag = "NO";
	var $proRemInputFlag = "NO";
	//スタッフ歩合表示フラグ
	var $staffPercentageFlag = "NO";
	//合計金額編集許可フラグ
	var $totalAmountFlag = "YES";
	//会計ボタンフラグ
	var $registerSubFlag = "YES";
	//仮登録ボタンフラグ
	var $preSubFlag = "NO";
	//スタッフ編集ボタンフラグ
	var $staffEditSubFlag = "NO";
	//管理者登録ボタンフラグ
	var $masterSubFlag = "NO";
	//商品追加許可フラグ
	var $addProFlag = "NO";
	//予約帳戻るリンク
	var $backReserveFlag = "YES";
	//伝票リスト戻るリンク表示
	var $backRecListFlag = "NO";

	/*--------------------------------------*/

	function __construct(){
		parent::__construct();

		$this->receipt = self::getReceipt();
		//歩合率をreceiptに追加
		$this->receipt["staff_percentage"] =
			parent::_getStaffPercentage(
					date("Y-m-d",strtotime($this->receipt["start"])),
					$this->receipt["staff_id"]);

		$this->usdProducts = self::getUsedProducts($this->receipt["rec_id"]);
		$this->dateStr = date("n月j日",strtotime($this->receipt["start"]));

		//使用メニューを取得しreservに追加
		$usedMenus = self::getUsedMenus($this->receipt["rec_id"]);
		$this->receipt["menus"] = $usedMenus;

		//有効伝票項目
		$this->usgRecEntries = array();
		for ($i = 0; $i < count($this->receiptEntries); $i++) {
			if ($this->receiptEntries[$i]["ur_id"]) {//有効項目
				array_push($this->usgRecEntries, $this->receiptEntries[$i]);
			}
		}

		//商品リストの非表示設定 削除済み、かつ削除日が昨日以前のものは非表示
		for ($i = 0; $i < count($this->products); $i++) {
			//delete_dateをdatetime型に変換しstartと比較
			$dateTime = $this->products[$i]["delete_date"]." 23:59:59";
			if ($this->products[$i]["deleted"] == 1
					&& $dateTime < $this->receipt["start"]) {
				$this->products[$i]["hide"] = true;
			}else {//有効な商品が１つでもあれば商品追加フラグをYESに
				$this->addProFlag = "YES";
			}
		}

		/*--フラグ設定--------------------------*/
    if (isset($_GET["mode"])) {
      //会計時はデフォルト状態のまま
  		if ($_GET["mode"] == "register") {
  		}
  		//仮登録時
  		if ($_GET["mode"] == "pre_sub") {//仮登録モード
  			$this->registerSubFlag = "NO";
  			$this->preSubFlag = "YES";
  		}
      //リストからピックアップ時（サロン）
  		if ($_GET["mode"] == "salon_pickup") {
  			$this->registerSubFlag = "NO";

  			//場合分けここだけ to-do
  			//sql準備
  			//today == startの準備
  			$today = StringManager::getMonthStrBySqlDate($this->_todaySQLStr);
  			//var_dump($today);
  			$salon_id = $_SESSION["salon"]["id"];
  			$rec_id = $_GET["rec_id"];
  			$target_receipts = 'receipts_'.$salon_id;
  			//var_dump($target_receipts);
  			$result_for_start = MySQL::_select($target_receipts, "start", "id = ".$rec_id);
  			$startSQLStr = $result_for_start[0]["start"];
  			$start = StringManager::getMonthStrBySqlDate($startSQLStr);
  			//var_dump($startSQLStr);
  			$startSQLStr_ymd = explode(" ", $startSQLStr)[0];
  			$startSQLStr_ym_ = substr($startSQLStr_ymd, 0, -2);
  			//var_dump($startSQLStr_ym_);
  			$startSQLStr_ym01 = $startSQLStr_ym_.'01';
  			//var_dump($startSQLStr_ym01);

  			//
  			$result_for_staff_id = MySQL::_select($target_receipts, "staff_id", "id = ".$rec_id);
  			$staff_id = $result_for_staff_id[0]["staff_id"];
  			//var_dump($staff_id);
  			$mysqli = MySQL::_createMysqli();
  			$sql = "SELECT id FROM approved_rems WHERE staff_id = ".$staff_id." AND month = '"
  					.$startSQLStr_ym01."'";
  			$result = $mysqli->query($sql);
  			//var_dump($result);
  			$mysqli->close();
  			//var_dump($result->num_rows);
  			if($today == $start || $result->num_rows == 0){
  				$this->masterSubFlag = "YES";
  			}else {
  				$this->masterSubFlag = "STAFF_APPROVAL";
  			}
  			/*
  			 * 歩合が有効になっている、かつ歩合パターンがある、かつ売上があれば報酬入力YES
  			 * 技術報酬入力は、さらにスタッフの歩合設定が決まっていなければYES
  			 */
  			if (count($this->tecPP) > 0
  					&& $this->receipt["tec_sale"] > 0
  					//&& !$this->receipt["staff_percentage"]
  					&& $this->receipt["staff_percentage"] === null
  					&& $this->salonStatus["tec_rem"] == 1) {
  				$this->tecRemInputFlag = "YES";
  			}
  			if (count($this->proPP) > 0
  					&& $this->receipt["pro_sale"] > 0
  					&& $this->salonStatus["pro_rem"] == 1) {
  				$this->proRemInputFlag = "YES";
  			}
  			//技術歩合が設定されている場合は歩合率表示YES
  			if ($this->receipt["staff_percentage"] !== null) {
  				$this->staffPercentageFlag = "YES";
  			}
  			//戻るフラグ
  			$this->backReserveFlag = "NO";
  			$this->backRecListFlag = "YES";
  		}
  		//リストからピックアップ時（スタッフ）
  		if ($_GET["mode"] == "staff_pickup") {
  			$this->registerSubFlag = "NO";
  		 	//合計金額編集NO
  			$this->totalAmountFlag = "NO";
  			//スタッフ編集YES
  			$this->staffEditSubFlag = "YES";
  			//戻るフラグ
  			$this->backReserveFlag = "NO";
  			$this->backRecListFlag = "YES";
      }
		}
		/*---------------------------------------*/

	}

	/*==DB================================================*/
	//伝票レコード取得
	public static function getReceipt() {
		$whereStr = "rec_id=".$_GET["rec_id"];
		$array = parent::_select("rec_info_".$_SESSION["salon"]["id"],
				"*", $whereStr);
		return $array[0];
	}
	//伝票に登録されているメニュー配列を取得
	public static function getUsedMenus($recId) {
		$colStr = "id,menu_name,menu_id,detail_id,sales";
		$array = parent::_select(
				"usd_menu_info_".$_SESSION["salon"]["id"],
				$colStr, "rec_id=".$recId, "menu_id");
		return $array;
	}
	//伝票に登録されている商品配列を取得
	public static function getUsedProducts($recId) {
		$colStr = "id,sales,product_id,num";
		$whereStr = "salon_id=".$_SESSION["salon"]["id"].
				" AND rec_id=".$recId;
		$array = parent::_select(
				"used_products", $colStr, $whereStr);
		return $array;
	}
	/*--receipts更新-------------------------------*/
	public static function update_receipts() {
		$colVal = self::colValForReceipts();
		if (count($colVal) > 0) {
			$res = parent::_update(
					"receipts_".$_SESSION["salon"]["id"],
					parent::_setStringForUpdate($colVal),
					"id=".$_POST["rec_id"]);
			return $res;
		}
	}
	//colVal
	private static function colValForReceipts() {
		$colVal = array();
		$array = array(
				"num_visit","pay_type","tec_disc","tec_sale",
				"pro_disc","pro_sale","memo",
				"out_","rec_comp","rem_comp"
			);
		for ($i = 0; $i < count($array); $i++) {
			if ($_POST[$array[$i]] != null) {
				$colVal[$array[$i]] = $_POST[$array[$i]];
			}
		}
		return $colVal;
	}
	/*---------------------------------------------*/

	/*--used_menu_details操作-------------------------*/
	//insert,update,deleteを場合わけして実行する
	public static function multiOperation_usd_menu_d() {
		$table = "used_menu_details_".$_SESSION["salon"]["id"];
		$exM = $_POST["exist_menus"];//既存メニュー
		$selM = $_POST["selected_menus"];//選択済みメニュー

		for ($i = 0; $i < count($selM); $i++) {
			$oper = "insert";//操作モード
			for ($n = 0; $n < count($exM); $n++) {
				if ($selM[$i]["menu_id"] == $exM[$n]["menu_id"]) {
					$oper = "update";
				}
			}

			//操作モードに応じてinsert,updateを実行
			$colVal = self::colValFor_usd_menu_d($selM[$i]);
			if ($oper == "insert") {//insert
				$colVal["menu_id"] = $selM[$i]["menu_id"];
				$colVal["rec_id"] = $_POST["rec_id"];
				parent::_insert($table,
						parent::_columnStringForInsert($colVal),
						parent::_valueStringForInsert($colVal));
			}else {//update
				parent::_update($table,
						parent::_setStringForUpdate($colVal),
						"rec_id=".$_POST["rec_id"]." AND menu_id=".$selM[$i]["menu_id"]);
			}
		}

		//選択済みメニュー以外をdelete
		$whereStr = "";
		for ($i = 0; $i < count($exM); $i++) {
			$match = false;
			for ($n = 0; $n < count($selM); $n++) {
				if ($exM[$i]["menu_id"] == $selM[$n]["menu_id"]) {
					$match = true;
				}
			}
			//合致しなかった場合は削除対象
			if (!$match) {
				if ($whereStr != "") {
					$whereStr = $whereStr." OR ";
				}
				$whereStr = $whereStr."id=".$exM[$i]["id"];
			}
		}
		//削除実行
		if ($whereStr != "") {
			parent::_delete($table, $whereStr);
		}

	}
	//colVal
	private static function colValFor_usd_menu_d($selectedMenu) {
		$colVal = array();
		if ($selectedMenu["detail_id"]) {
			$colVal["detail_id"] = $selectedMenu["detail_id"];
		}else {
			$colVal["detail_id"] = null;
		}
		if ($selectedMenu["sales"] > 0) {
			$colVal["sales"] = $selectedMenu["sales"];
		}else {
			$colVal["sales"] = null;
		}
		return $colVal;
	}
	/*-------------------------------------------------*/

	/*--used_products操作----------------------------------*/
	//insert,update,deleteを場合わけして実行する
	public static function multiOperation_usd_pro() {
		$table = "used_products";
		$exP = $_POST["exist_products"];//既存商品
		$selP = $_POST["selected_products"];//選択済み商品

		for ($i = 0; $i < count($selP); $i++) {
			$oper = "insert";//操作モード
			if ($selP[$i]["id"]) { $oper = "update"; }

			//操作モードに応じてinsert,updateを実行
			$colVal = self::colValFor_usd_pro($selP[$i]);
			if ($oper == "insert") {//insert
				$colVal["salon_id"] = $_SESSION["salon"]["id"];
				$colVal["rec_id"] = $_POST["rec_id"];
				parent::_insert($table,
						parent::_columnStringForInsert($colVal),
						parent::_valueStringForInsert($colVal));
			}else {//update
				parent::_update($table,
						parent::_setStringForUpdate($colVal),
						"id=".$selP[$i]["id"]);
			}
		}

		//選択済みメニュー以外をdelete
		$whereStr = "";
		for ($i = 0; $i < count($exP); $i++) {
			$match = false;
			for ($n = 0; $n < count($selP); $n++) {
				if ($exP[$i]["id"] == $selP[$n]["id"]) {
					$match = true;
				}
			}
			//合致しなかった場合は削除対象
			if (!$match) {
				if ($whereStr != "") {
					$whereStr = $whereStr." OR ";
				}
				$whereStr = $whereStr."id=".$exP[$i]["id"];
			}
		}
		//削除実行
		if ($whereStr != "") {
			parent::_delete($table, $whereStr);
		}

	}
	//colVal
	private static function colValFor_usd_pro($selectedProduct) {
		$colVal = array();
		$colVal["product_id"] = $selectedProduct["product_id"];
		$colVal["num"] = $selectedProduct["num"];
		if ($selectedProduct["sales"] > 0) {
			$colVal["sales"] = $selectedProduct["sales"];
		}else {
			$colVal["sales"] = null;
		}
		return $colVal;
	}
	/*---------------------------------------------------*/
	/*==================================================*/
}

//ajax
if ($_POST) {
	if ($_POST["mode"] == "timely_remcomp") {
		$res = MySQL::_select(
			"receipts_".$_SESSION["salon"]["id"],
			"rem_comp", "id=".$_POST["rec_id"], null);
		echo $res[0]["rem_comp"];

	}elseif ($_POST["mode"] == "mester_delete") {

		if (!$_SESSION["res_condition"]["salon"]) {
			$_SESSION["res_condition"]["salon"] = $_SESSION["salon"]["id"];
		}

		echo json_encode(ReserveModel::deleteRecDatas());

	}else {
		//メニュー
		ReceiptModel::multiOperation_usd_menu_d();
		//商品
		ReceiptModel::multiOperation_usd_pro();
		//receipts
		ReceiptModel::update_receipts();
		//net,point,free,student,tec_rem,pro_rem
		ReserveModel::multiOperation_rec_ent($_SESSION["salon"]["id"]);
	}

}
