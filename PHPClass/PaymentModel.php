<?php
require_once 'RootModel.php';
class PaymentModel extends RootModel{

	//月セレクタでさかのぼる月数
	const MON_COUNT = 13;
	//月リスト
	var $monthDatas;
	//スタッフリスト
	var $staffInfo;
	//承認済み報酬リスト
	var $approvedRems;

	function __construct() {
		parent::__construct();

		if ($_POST) {
			$_SESSION["payment_condition"] = $_POST;
		}else {
			if (empty($_SESSION["payment_condition"])) {
				$_SESSION["payment_condition"]["paid"] = "0";
			}
		}
		//var_dump($_SESSION["payment_condition"]);

		$this->monthDatas = parent::_getMonthDatasDesc(self::MON_COUNT);
		$this->staffInfo = self::getStaffInfo();
		$this->approvedRems = self::getApprovedRems();
		//monthの文字列を変換
		for ($i = 0; $i < count($this->approvedRems); $i++) {
			$this->approvedRems[$i]["month"] =
				date("Y年m月",strtotime($this->approvedRems[$i]["month"]));
		}
	}

	public static function getStaffInfo() {
		if ($_SESSION["visiter"] == "salon") {//サロンログイン
			$whereStr = "salon_id=".$_SESSION["salon"]["id"];
		}else {//グループログイン
			$whereStr = "company_id=".$_SESSION["company"]["id"];
		}
		$array = parent::_select("staff_info", "*", $whereStr, " deleted,staff_id");
		return $array;
	}

	/*--approved_remsテーブル操作-----*/
	//検索結果をもとにセレクト
	public static function getApprovedRems() {
		$table1 = "approved_rems";
		$table2 = "staff_info";
		$colStr = $table1.".id,month,".$table1.".staff_id,paid,rem,staff_name,salon_name";
		$joinStr = $table1.".staff_id=".$table2.".staff_id";
		$whereStr = self::getWhereStr();
		$orderStr = "staff_id,month";

		$array = parent::_selectInnerJoin($table1, $table2,
					$colStr, $joinStr, $whereStr, $orderStr);
		return $array;
	}
	//whereStr
	private static function getWhereStr(){
		if ($_SESSION["visiter"] == "salon") {//サロンログイン
			$whereStr = "salon_id=".$_SESSION["salon"]["id"];
		}else {//グループログイン
			$whereStr = "company_id=".$_SESSION["company"]["id"];
		}

		foreach ($_SESSION["payment_condition"] as $key => $value) {

			if ($value != "") {
				$whereStr = $whereStr." AND ";

				if ($key == "start_month") {
					$whereStr = $whereStr."EXTRACT(YEAR_MONTH FROM month)>="
							.StringManager::deleteHyphen($value);
				}
				if ($key == "end_month") {
					$whereStr = $whereStr."EXTRACT(YEAR_MONTH FROM month)<="
							.StringManager::deleteHyphen($value);
				}
				if ($key == "staff_id") {
					$whereStr = $whereStr."approved_rems.staff_id=".$value;
				}
				if ($key == "start_amount") {
					$whereStr = $whereStr."rem>=".$value;
				}
				if ($key == "end_amount") {
					$whereStr = $whereStr."rem<=".$value;
				}
				if ($key == "paid") {
					$whereStr = $whereStr."paid=".$value;
				}
			}
		}
		return $whereStr;
	}
	/*-----------------------------*/
}
