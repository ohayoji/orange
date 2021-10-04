<?php
require_once 'RootModel.php';
class LoginModel extends RootModel{
	/*
	 * 訪問者判別プロパティ
	 * staff,salon,company
	 */
	var $visiter;
	var $visiterName;
	var $initFlag;

	function __construct() {
    if (isset($_GET["logout"])) {
      if ($_GET["logout"] == "true") {
  			//既存のセッションを削除
  			$_SESSION = array();
  			session_destroy();
  		}
    }

		//parent::_showPost();
		if ($_POST) {
			//例外バグを回避
			if (!$_POST["e_mail"] || !$_POST["password"]) {
				return false;
			}

			//既存のセッションを削除(session_destroy()をするとバグるのでやらない)
			$_SESSION = array();

			//$visiterとセッションをセット
			if (parent::_getCountEmailAndPass("staffs",$_POST['e_mail'],$_POST['password']) > 0) {
				$this->visiter = "staff";
				$this->visiterName = "スタッフ";
				$this->setSession();

			}elseif (parent::_getCountEmailAndPass("salons",$_POST['e_mail'],$_POST['password']) > 0) {
				$this->visiter = "salon";
				$this->visiterName = "サロン管理者";
				$this->setSession();
				$this->initFlag = $this->getInitFlag($_SESSION["salon"]["id"]);
				/*****ログ外部ファイルに出力*******
				ob_start();//バッファ有効化
				var_dump($this->initFlag);
				var_dump($_SESSION);
				var_dump($this->visiter);
				$log = ob_get_contents();//バッファから値取り出し
				ob_end_clean();//バッファのclean及び無効化

				$fp = fopen("/Library/WebServer/Documents/logs/log.txt", "w+");//ファイル作成及びopen
				fputs($fp, $log);//書き込み
				fclose($fp);//ファイルclose
				*/



			}elseif (parent::_getCountEmailAndPass("companies",$_POST['e_mail'],$_POST['password']) > 0) {
				$this->visiter = "company";
				$this->visiterName = "グループ管理者";
				$this->setSession();

			}else {
				$this->visiter = null;
				echo '<script type="text/javascript">alert("IDかpasswordに誤りがあります");</script>';
			}

			/*echo "<br>loginSession<br>";
			parent::_showSession();*/

			//トップページに移動
			if ($this->visiter) {
				echo '<script type="text/javascript">
					alert("'.$this->visiterName.'でログインします")</script>';
				if ($this->visiter == "company") {
					echo '<script >document.location = "company_top.php";</script>';
				}elseif($this->visiter == "salon" && $this->initFlag != 0){
					echo '<script >document.location = "init_setting.php?initFlag='.$this->initFlag.'";</script>';
				}else{
					echo '<script >document.location = "reserve.php";</script>';
				}

			}
		}
	}

	//セッション変数をセット
	function setSession() {

		if ($this->visiter == "staff") {
			//スタッフログイン時
			$_SESSION["visiter"] = "staff";

			$array = self::getStaffAndSalonStatus();
			$_SESSION["staff"] = array("id"=>$array["staff_id"],
					"_name"=>$array["staff_name"]);
			$_SESSION["salon"] = array("id"=>$array["salon_id"],
					"_name"=>$array["salon_name"],"password"=>null,
					"e_mail"=>null,"plan"=>null);

		}elseif ($this->visiter == "salon") {
			//サロン管理者ログイン時
			$_SESSION["visiter"] = "salon";
			$array = self::getSalonStatus();
			$_SESSION["salon"] = array("id"=>$array["id"],
					"_name"=>$array["_name"],"password"=>$array["password"],
					"e_mail"=>$array["e_mail"],"plan"=>$array["plan"]
			);
			$_SESSION["company"]["id"] = $array["company_id"];

		}else {
			//会社ログイン時
			$_SESSION["visiter"] = "company";
			$_SESSION["company"] = self::getCompanyStatus();
		}
	}

	/*==DB===========================================*/

	//staffID,スタッフ名,salonID,サロン名を取得
	public static function getStaffAndSalonStatus() {
		$colStr = "staffs.id AS 'staff_id',staffs._name AS 'staff_name',
					salons.id AS 'salon_id',salons._name AS 'salon_name'";
		$whereStr = "staffs.e_mail='".$_POST['e_mail'].
					"' AND staffs.password='".$_POST['password']."'";

		$array = parent::_selectInnerJoin(
				"staffs", "salons", $colStr,
				"staffs.salon_id=salons.id", $whereStr, null);

		return $array[0];
	}
	//salonID,サロン名,companyIDを取得
	public static function getSalonStatus() {
		$colStr = "id,_name,company_id,password,e_mail,plan";
		$whereStr = "e_mail='".$_POST['e_mail']."' AND password='".$_POST['password']."'";

		$array = parent::_select("salons", $colStr, $whereStr);

		return $array[0];
	}
	//companyIDを取得
	public static function getCompanyStatus() {
		$colStr = "id,_name";
		$whereStr = "e_mail='".$_POST['e_mail']."' AND password='".$_POST['password']."'";

		$array = parent::_select("companies", $colStr, $whereStr);

		return $array[0];
	}

	function getInitFlag($salon_id){
		$colStr = 'init_flag';
		$whereStr = "id=$salon_id";
		$array = parent::_select("salons", $colStr, $whereStr);
		$initFlag = intval($array[0]['init_flag']);
		return $initFlag;
	}
	/*=====================================================*/
}
