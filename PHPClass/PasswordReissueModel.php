<?php
require_once 'RootModel.php';
require_once 'MailManager.php';
class PasswordReissueModel extends RootModel{

	var $userType;
	var $id;

	//モード
	var $mode = "e_mail_verification";

	function __construct() {

		if ($_POST) {
			//parent::_showPost();

			//mode:e_mail_verification
      if (!empty($_POST["mode"])) {
        if ($_POST["mode"] == "send_mail") {
  				/*--パラメータ作成------------------*/
  				$userType = $_POST["user_type"];
  				$str1 = parent::_makeRandStr(RootModel::STR_LENGTH);
  				$str2 = parent::_makeRandStr(RootModel::STR_LENGTH);
  				$idStr = $str1.$_POST["id"].$str2;
  				/*-------------------------------*/

  				/*--メール送信-----------------------------*/
  				$to = $_POST["e_mail"];
  				$subject = "パスワード再発行までもう少しです！";
  				$message =
  				"このメールは「Orange」パスワード再発行手続き中のユーザー様にお送りしています。".PHP_EOL.
  				"※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。".PHP_EOL.PHP_EOL.
  				"パスワード再発行までもう少しです！".PHP_EOL.
  				"以下のURLに接続して、パスワード再発行手続きを進めてください。".PHP_EOL.PHP_EOL.
  				"【パスワード再発行URL：】".PHP_EOL.
  				parent::URL_PASS_REISSUE."?a=".$userType."&b=".$idStr.PHP_EOL.
  				"※URLが改行されている場合は、1行につなげてブラウザのアドレスバーに入力してください。".PHP_EOL.PHP_EOL.
  				"Orange".PHP_EOL.
  				parent::URL_HOME_PAGE;

  				//echo $message;

  				$res = MailManager::send(1, $to, $subject, $message);
  				/*-----------------------------------*/

  			}elseif ($_POST["mode"] == "reissue") {//reissueモード

  				$colVal["password"] = $_POST["password"];
  				$res = parent::_update(
  						self::getTableName(),
  						parent::_setStringForUpdate($colVal),
  						"id=".$_POST["id"]);

  				if ($res) {
  					$this->mode = "comp";
  				}else {
  					$this->mode = "fail";
  				}
  			}
      }


		}

		if ($_GET) {
			//モード切り替え
			$this->mode = "reissue";

			$this->userType = $_GET["a"];
			$this->id = parent::_getRealStr($_GET["b"]);

		}
	}

	static function getTableName() {
		if ($_POST["user_type"] == "staff") {
			$table = "staffs";
		}elseif ($_POST["user_type"] == "salon") {//サロン
			$table = "salons";
		}else {//グループ
			$table = "companies";
		}
		return $table;
	}
}

if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "e_mail_verification") {

  	if ($_POST["user_type"] == "staff") {//スタッフ
  		$res = MySQL::_selectInnerJoin(
  				"staffs", "salons", "staffs.id",
  				"staffs.salon_id=salons.id",
  				"staffs.e_mail='".$_POST["e_mail"].
  				"' AND salons.e_mail='".$_POST["salon_e_mail"]."'",
  				null);

  	}elseif ($_POST["user_type"] == "salon") {//サロン
  		$res = MySQL::_select(
  				"salons",
  				"id",
  				"e_mail='".$_POST["e_mail"]."'",
  				null);

  	}else {//グループ
  		$res = MySQL::_select(
  				"companies",
  				"id",
  				"e_mail='".$_POST["e_mail"]."'",
  				null);
  	}
  	echo json_encode($res);
  	//echo $res;
  }
  if ($_POST["mode"] == "count") {

  	$count = MySQL::_count(
  			PasswordReissueModel::getTableName(),
  			"id", "password='".$_POST["password"]."'");
  	echo $count;
  }

}
