<?php
if(!isset($_SESSION)){
  session_start(); 
}

require_once 'RootModel.php';

class CompanySettingModel extends RootModel{

	//基本プロパティ
	var $eMail;
	var $password;
	var $homepage;
	//var $creditCard;
	//var $bank;
	var $numSalon;
	//var $plan;
	//詳細プロパティ
	//var $usingSubjects;
	//var $tecPP;//技術歩合パターン
	//var $proPP;//帳品歩合パターン
	//サロン情報配列
	var $salonInfo;

	function __construct() {
		parent::__construct();

		//アップデート処理
		if ($_POST) {
			self::update_companies();
		}

		//基本プロパティをセット
		$array = self::getStatus();
		//var_dump($array);
		$this->eMail = $array[0]["e_mail"];
		$this->password = $array[0]["password"];
		$this->homepage = $array[0]["homepage"];
		//$this->creditCard = $array[0]["credit_card"];
		//$this->bank = $array[0]["bank"];
		$this->numSalon = $array[0]["num_salon"];
		//$this->plan = $array[0]["plan"];

		//詳細プロパティ
		//$this->usingSubjects = self::getSubjects();
		//$this->tecPP = self::getPercentagePatterns("tec");
		//$this->proPP = self::getPercentagePatterns("pro");
		//サロン情報配列をセット
		$this->salonInfo = self::getSalonStatus();
	}


	/*==DB====================================================*/
	//会社IDから基本ステータスを取得
	public static function getStatus() {
		/*$colStr = "e_mail,password,homepage,credit_card,
				bank,num_salon,plan";*/
		$colStr = "e_mail,password,num_salon";
		$array = parent::_select(
			"companies", $colStr, "id=".$_SESSION["company"]["id"], null);

		return $array;
	}
	//会社の全サロンのid,_nameを取得
	public static function getSalonStatus() {
		$array = parent::_select(
			"salons", "id,_name", "company_id='".$_SESSION["company"]["id"]."' AND deleted = '0'", null);
		return $array;
	}
	/*勘定科目配列を取得
	 * 会社で有効にしている勘定科目には値が入る
	 */
	public static function getSubjects() {
		$colStr = "subjects.id AS sb_id,subjects._name,subjects.local_name,using_subjects.id AS us_id";
		$joinStr = "subjects.id=using_subjects.subject_id AND using_subjects.company_id=".$_SESSION["company"]["id"];
		$array = parent::_selectOuterJoin(
				"subjects", "using_subjects", "LEFT",
				$colStr, $joinStr, null, "sb_id");
		return $array;
	}
	//会社独自の歩合率設定を配列で取得
	public static function getPercentagePatterns($type) {
		$colStr = "id,percentage,selected";
		$array = parent::_select(
				"percentage_pattern_setting_".$type,
				$colStr,
				"company_id=".$_SESSION["company"]["id"],
				"percentage DESC");
		return $array;
	}

	/*--using_subjects更新--*/
	public static function insert_usg_subjects() {
		parent::_insert("using_subjects",
				"company_id,subject_id",
				$_SESSION["company"]["id"].",".$_POST["sb_id"]);
	}
	public static function delete_usg_subjects() {
		parent::_delete("using_subjects",
				"company_id=".$_SESSION["company"]["id"]." AND subject_id=".$_POST["sb_id"]);
	}
	/*---------------------*/


	/*--percentage_pattern_setting--*/
	//insert
	public static function insert_per_pat_set() {
		$colVal["percentage"] = $_POST["percentage"];
		$colVal["company_id"] = $_SESSION["company"]["id"];
		$res = parent::_insert(
				"percentage_pattern_setting_".$_POST["type"],
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
		return $res;
	}
	//update
	public static function update_per_pat_set() {
		//既存のレコードのselectedを０にする
		parent::_update(
				"percentage_pattern_setting_".$_POST["type"],
				"selected=0", "company_id=".$_SESSION["company"]["id"]);
		//指定されたレコードのselectedを１にする
		parent::_update("percentage_pattern_setting_".$_POST["type"],
				"selected=1", "id=".$_POST["id"]);
	}
	//delete
	public static function delete_per_pat_set(){
		parent::_delete(
				"percentage_pattern_setting_".$_POST["type"],
				"id=".$_POST["id"]);
	}
	/*----------------------*/
	/*---companiesテーブル更新---*/
	public static function update_companies() {
		$res =parent::_update("companies",
				parent::_setStringForUpdate($_POST),
				"id=".$_SESSION["company"]["id"]);
		return $res;
	}

	/*---------------------*/
	/*======================================================*/
}

/*--ajax----------------------------------------------*/
if (!empty($_POST["sb_id"])) {
  //using_subjects更新
  if ($_POST["use"] == "true") {
		CompanySettingModel::insert_usg_subjects();
	}else {
		CompanySettingModel::delete_usg_subjects();
	}
}

if (!empty($_POST["mode"])) {
  //per_pat_setにレコード追加
  if ($_POST["mode"] == "insert_pp") {
  	$res = CompanySettingModel::insert_per_pat_set($_POST["type"]);
  	echo $res;
  }
  //per_pat_setのselectedを更新
  if ($_POST["mode"] == "update_pp") {
  	CompanySettingModel::update_per_pat_set();
  }
  //per_pat_setのレコードを削除
  if ($_POST["mode"] == "delete_pp") {
  	CompanySettingModel::delete_per_pat_set();
  }

  if($_POST["mode"] == "salonDelete"){
  	MySQL::_update("salons", "company_id = NULL", "id = '".$_POST["salon_id"]."'");
  }
}

/*-----------------------------------------------------*/
