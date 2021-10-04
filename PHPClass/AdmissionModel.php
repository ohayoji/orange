<?php
require_once 'RootModel.php';
require_once 'MailManager.php';
class AdmissionModel extends RootModel{

	//仮登録処理モードかフラグ
	var $aferPreAdmission = 0;
	//送信成功フラグ
	var $sendComp = null;
	//admission_button_type
	var $admissionButtonType = null;

	var $company_id = null;
	var $facebook_id = null;
	var $email = null;

	function __construct() {
		//parent::_showSession();
		//セッションチェックをキャンセルする「NO」を渡す
		parent::__construct("NO");

		//parent::_showPost();

		 if ($_POST) {
		 	//仮登録サロンレコード追加
		 	$_POST["pre_admission_date"] = $this->_todaySQLStr;

		 	$res = self::insert_pre_adm_salons();

		 	// echo '仮登録サロンレコード追加' . $res;
            // var_dump('仮登録サロンレコード追加 res', $res);

		 	if ($res) {
                 
		 		/*--パラメータ作成-----------*/
		 		//ランダム文字列
		 		$str1 = parent::_makeRandStr(parent::STR_LENGTH);
		 		$str2 = parent::_makeRandStr(parent::STR_LENGTH);
		 		$param = $str1.$res.$str2;
		 		/*-------------------------*/
                 
                var_dump('仮登録サロンレコード追加 url', parent::URL_ADD_COMP."?a=".$param);

		 		// /*--仮登録完了メール送信----------------*/
		 		// $to = $_POST["e_mail"];
		 		// $subject = "お申し込みありがとうございます!";
		 		// $message =
		 		// 	$_POST["_name"]." 様".PHP_EOL.PHP_EOL.
		 		// 	"この度は、「Orange」にお申し込みいただきありがとうございます。".PHP_EOL.
		 		// 	"※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。".PHP_EOL.PHP_EOL.
		 		// 	"現在、".$_POST["_name"]."さんは、仮登録中です。".PHP_EOL.
		 		// 	"以下のURLに接続して、本登録をおこなってください。".PHP_EOL.PHP_EOL.
		 		// 	"【本登録用URL：】".PHP_EOL.
		 		// 	parent::URL_ADD_COMP."?a=".$param.PHP_EOL.
		 		// 	"※URLが改行されている場合は、1行につなげてブラウザのアドレスバーに入力してください。".PHP_EOL.PHP_EOL.
		 		// 	"Orange".PHP_EOL.
		 		// 	parent::URL_HOME_PAGE;

		 		// $this->sendComp = MailManager::send(1, $to, $subject, $message);
		 		// /*--------------------------------------------*/
		 	}
		 	$this->aferPreAdmission = 1;
		}

		//admission_button_typeフラグ
		//変更した
		if ($_GET["admission_button_type"]) {
			$this->admissionButtonType = $_GET["admission_button_type"];
		}
		if ($_GET["company_id"]) {
			$this->company_id = $_GET["company_id"];
		}
		if ($_GET["facebook_id"]) {
			$this->facebook_id = $_GET["facebook_id"];
		}
		if ($_GET["email"]) {
			$this->email = urldecode($_GET["email"]);
		}

	}
	/*==DB==================================================*/
	public static function insert_pre_adm_salons() {
		$res = parent::_insert("pre_admission_salons",
				parent::_columnStringForInsert($_POST),
				parent::_valueStringForInsert($_POST));
		return $res;
	}
}

//ajax
if (!empty($_POST["mode"])) {

  if ($_POST["mode"] == "count") {
  	$count = MySQL::_count(
  			"salons", "id", "password='".$_POST["password"]."'");
  	echo $count;
  }
}
