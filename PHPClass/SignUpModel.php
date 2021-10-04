<?php
require_once 'RootModel.php';

class SignUpModel extends RootModel{

	/*--状態管理プロパティ
	 * NO_GET：パラメータを消されてアクセスされた場合
	 * SUCCESS_SIGNUP：スタッフ登録が成功した場合
	 * FAIL_SIGNUP：スタッフ登録に失敗した場合
	 */
	var $condition = null;

	var $salon = null;
	var $staff = null;

	function __construct() {
		if ($_GET) {
			//GETからsalon_idとstaff_idを抽出
			$this->salon["id"] = parent::_getRealStr($_GET["a"]);
			$this->staff["id"] = parent::_getRealStr($_GET["b"]);
			//サロン名とスタッフ名
			$this->salon["_name"] = $this->getSalonName();
			$this->staff["_name"] = $this->getStaffName();
		}else {
			$this->condition = "NO_GET";
		}

		if ($_POST) {

			$res = self::_updateStaffs();

			if ($res) {
				$this->condition = "SUCCESS_SIGNUP";
			}else {
				$this->condition = "FAIL_SIGNUP";
			}
		}
	}

	/*==DB===========================================*/
	//salon_idからサロン名を取得
	private function getSalonName() {
		$array = parent::_select("salons","_name","id=".$this->salon["id"]."");
		return $array[0]["_name"];
	}
	//スタッフIdからスタッフ名取得
	private function getStaffName() {
		$array = parent::_select("staffs", "_name", "id=".$this->staff["id"], null);
		return $array[0]["_name"];
	}
	/*=====================================================*/
}

//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "count") {
  	$count = MySQL::_count(
  			"staffs", "id", "password='".$_POST["password"]."'");
  	echo $count;
  }
}
