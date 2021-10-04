<?php
require_once 'RootModel.php';
class PersonalSettingModel extends RootModel{

	var $status;
	var $usedColors;

	function __construct() {
		parent::__construct();

		if ($_POST) {
			//parent::_showPost();
			$_POST["id"] = $_SESSION["staff"]["id"];
			parent::_updateStaffs();
		}

		//プロパティ設定
		$status = self::getStaffStatus();
		$this->status = $status[0];
		$this->usedColors = self::getUsedColors();
	}
	/*==DB================================*/
	//スタッフのステータスを取得
	public static function getStaffStatus() {
		$array = parent::_select("staffs",
					"e_mail,password,color",
					"id=".$_SESSION["staff"]["id"]);
		return $array;
	}
	//カラーがすでに使用されているかチェックする
	public static function countColor() {
		$res = parent::_count("staffs", "color",
			"color='".$_POST["color"].
			"' AND salon_id=".$_SESSION["salon"]["id"]);
		return $res;
	}
	//利用されているカラーを取得
	public static function getUsedColors() {
		$res = parent::_select("staffs", "color",
				"salon_id=".$_SESSION["salon"]["id"]." AND deleted=0");
		return $res;
	}
	/*===========================================*/
}
//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "check") {
  	$res = PersonalSettingModel::countColor();

  	if ($res > 0) {
  		echo "used";
  	}else {
  		echo "enabled";
  	}
  }
}
