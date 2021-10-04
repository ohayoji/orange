<?php
require_once 'RootModel.php';
require_once 'MailManager.php';
require_once 'PlanManager.php';

class StaffSettingModel extends RootModel{

	//プランマネージャ
	var $planManager;

	//スタッフ追加時の状態管理配列
	var $addStaffCondition = null;

	//サロンスタッフ配列
	var $staffs;

	function __construct() {
		parent::__construct();

		$this->planManager = new PlanManager($_SESSION["salon"]["id"]);

		if ($_POST) {
			$startDate = date("Y-m-01",strtotime($this->_todaySQLStr));

      if (!empty($_POST["mode"])) {
        if ($_POST["mode"] == "add") {
  				//parent::_showPost();

  				//スタッフ追加時の状態管理配列を生成
  				$this->addStaffCondition = array(
  					"staff_name"=>$_POST["_name"],
  					"add_comp"=>false,
  					"mail_sent"=>false
  				);

  				$res = self::insert_staffs();

  				if ($res) {
  					if ($_POST["salary"] != null) {
  						self::insert_staff_sal_set($res, $startDate);
  					}
  					if ($_POST["percentage"] != null) {
  						self::insert_staff_per_set($res, $startDate);
  					}
  					$this->addStaffCondition["add_comp"] = true;



  					/*==メール送信===================================================*/
  					/*--パラメータ作成-----------*/
  					//サロンid用ランダム文字列
  					$str1 = parent::_makeRandStr(parent::STR_LENGTH);
  					$str2 = parent::_makeRandStr(parent::STR_LENGTH);
  					$salonParam = $str1.$_SESSION["salon"]["id"].$str2;
  					//スタッフid用ランダム文字列
  					$str3 = parent::_makeRandStr(parent::STR_LENGTH);
  					$str4 = parent::_makeRandStr(parent::STR_LENGTH);
  					$staffParam = $str3.$res.$str4;
  					/*-------------------------*/

  					$to = $_SESSION["salon"]["e_mail"];
  					$subject = "Orangeより-スタッフが追加されました";
  					$message =
  						$_SESSION["salon"]["_name"]."　様".PHP_EOL.PHP_EOL.
  						"スタッフに ".$_POST["_name"]." さんが追加されました。".PHP_EOL.
  						"このメールを ".$_POST["_name"]." さんに転送し、スタッフアカウントを登録してもらいましょう！".PHP_EOL.PHP_EOL.
  						"↓↓ここからは ".$_POST["_name"]." さんがお読みください↓↓".PHP_EOL.PHP_EOL.
  						"==".$_POST["_name"]." さんへ====================================================".PHP_EOL.
  						$_POST["_name"]." さんへ".PHP_EOL.PHP_EOL.
  						$_POST["_name"]." さんが、".$_SESSION["salon"]["_name"]."のスタッフに登録されました。".PHP_EOL.
  						"以下のURLに接続して、スタッフアカウントを登録してください。".PHP_EOL.PHP_EOL.
  						"【スタッフアカウント登録用URL：】".PHP_EOL.
  						parent::URL_SIGNUP."?a=".$salonParam."&b=".$staffParam.PHP_EOL.PHP_EOL.
  						"スタッフアカウントでOrangeにログインすれば、".
  						$_POST["_name"]."さんのスマホから予約帳にアクセスしたり、".
  						$_POST["_name"]."さんの専用の売上明細やお会計伝票を確認することができます。".PHP_EOL.PHP_EOL.
  						"======================================================".PHP_EOL.PHP_EOL.
  						"Orange".PHP_EOL.
  						parent::URL_HOME_PAGE.PHP_EOL;

  					$this->addStaffCondition["mail_sent"] = MailManager::send(1, $to, $subject, $message);

  					/*==============================================================*/
  				}

  			}elseif ($_POST["mode"] == "edit") {
  				//parent::_showPost();

  				self::updateStaffs();

          if (isset($_POST["salary"])) {
            if ($_POST["salary"] !== null) {//0,空白を許容
    					self::update_staff_sal_set($startDate);

    					if ($_POST["salary"] != "0") {
    						self::insert_staff_sal_set($_POST["id"], $startDate);
    					}
    				}
          }
          if (isset($_POST["percentage"])) {
            if ($_POST["percentage"] !== null) {//0,空白を許容
    					self::update_staff_per_set($startDate);

    					if ($_POST["percentage"] !== "") {
    						self::insert_staff_per_set($_POST["id"], $startDate);
    					}

    					//未設定状態から変更された場合は当月のtec_remレコードを削除
              if (isset($_POST["del_tec_rem"])) {
                if ($_POST["del_tec_rem"]) {
      						//当月の全伝票のid
      						$ids = $this->getThisMonthRecId();
      						//var_dump($ids);
      						if (count($ids) > 0) {
      							$this->delete_tec_rem($ids);
      						}
      					}
              }
    				}
          }

  			}else {//staff_delete
  				self::updateStaffs();
  				self::update_staff_sal_set($startDate);
  				self::update_staff_per_set($startDate);
  			}
      }
		}

		$this->staffs = self::getSalonStaffStatus($this->_todaySQLStr);
	}
	/*==DB===============================*/
	//サロン全スタッフのステータスを取得
	public static function getSalonStaffStatus($date) {
		$sql = "SELECT staffs.id,_name,e_mail,icon,color,position,staffs.deleted,
				 staff_salary_setting.salary,staff_percentage_setting.percentage
				 FROM staffs LEFT JOIN staff_salary_setting
				 ON staffs.id=staff_salary_setting.staff_id
				 AND ".
				parent::_getWhereStr_salary_percentage("staff_salary_setting",$date).
				" LEFT JOIN staff_percentage_setting
				 ON staffs.id=staff_percentage_setting.staff_id
				 AND ".
				parent::_getWhereStr_salary_percentage("staff_percentage_setting",$date).
				" WHERE staffs.salon_id=".$_SESSION["salon"]["id"].
				" ORDER BY staffs.id";
		$array = parent::_query($sql, "select");

		return $array;
	}
	public static function getAllSalonStaffStatus($date, $salonIDs) {

		$sql = "SELECT staffs.id,_name,e_mail,icon,color,position,staffs.deleted,
				 staff_salary_setting.salary,staff_percentage_setting.percentage,staffs.salon_id
				 FROM staffs LEFT JOIN staff_salary_setting
				 ON staffs.id=staff_salary_setting.staff_id
				 AND ".
					 parent::_getWhereStr_salary_percentage("staff_salary_setting",$date).
					 " LEFT JOIN staff_percentage_setting
				 ON staffs.id=staff_percentage_setting.staff_id
				 AND ".
					 parent::_getWhereStr_salary_percentage("staff_percentage_setting",$date).
					 " ".parent::_getWhereStr_salonIDs($salonIDs).
					 " ORDER BY staffs.salon_id, staffs.id";
		$array = parent::_query($sql, "select");

		return $array;
	}



	//スタッフ追加
	public static function insert_staffs() {
		$colVal = self::getColValForStaffs();
		$colVal["salon_id"] = $_SESSION["salon"]["id"];
		$res = parent::_insert("staffs",
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
		return $res;
	}
	//更新
	public static function updateStaffs() {
		$id = $_POST["id"];
		//unset($_POST["id"]);
		$colVal = self::getColValForStaffs();
		$res = parent::_update("staffs",
				parent::_setStringForUpdate($colVal),
				"id=".$id);
		return $res;
	}
	//colVal
	private static function getColValForStaffs(){
		$colVal = $_POST;
		unset($colVal["id"]);
		unset($colVal["mode"]);
		unset($colVal["salary"]);
		unset($colVal["percentage"]);
		unset($colVal["del_tec_rem"]);
		return $colVal;
	}


	//salary追加
	public static function insert_staff_sal_set($staffId,$today) {
		$colVal["staff_id"] = $staffId;
		$colVal["salary"] = $_POST["salary"];
		$colVal["start_date"] = $today;
		parent::_insert("staff_salary_setting",
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
	}
	//percentage追加
	public static function insert_staff_per_set($staffId,$today) {
		$colVal["staff_id"] = $staffId;
		$colVal["percentage"] = $_POST["percentage"];
		$colVal["start_date"] = $today;
		parent::_insert("staff_percentage_setting",
				parent::_columnStringForInsert($colVal),
				parent::_valueStringForInsert($colVal));
	}
	//salary更新
	public static function update_staff_sal_set($today) {
		$whereStr = "staff_id=".$_POST["id"]." AND "
				.parent::_getWhereStr_salary_percentage(
						"staff_salary_setting", $today);
		$colVal["disable_date"] = $today;
		parent::_update("staff_salary_setting",
				parent::_setStringForUpdate($colVal),
				$whereStr);
	}
	//percentage更新
	public static function update_staff_per_set($today) {
		$whereStr = "staff_id=".$_POST["id"]." AND "
				.parent::_getWhereStr_salary_percentage(
						"staff_percentage_setting", $today);
		$colVal["disable_date"] = $today;
		parent::_update("staff_percentage_setting",
				parent::_setStringForUpdate($colVal),
				$whereStr);
	}
	//スタッフの当月伝票id
	private function getThisMonthRecId() {

		$month = date("Ym",strtotime($this->_todaySQLStr));
		//echo $month;
		$colStr = "id";
		$whereStr = "EXTRACT(YEAR_MONTH FROM start)=".$month
				." AND rem_comp=1 AND staff_id=".$_POST["id"];
		$array = parent::_select(
				"receipts_".$_SESSION["salon"]["id"],
				$colStr, $whereStr);
		return $array;
	}
	//tec_remからデータを削除
	private function delete_tec_rem($ids) {
		$whereStr = "salon_id=".$_SESSION["salon"]["id"]." AND (";
		$count = count($ids);
		for ($i = 0; $i < $count; $i++) {
			if ($i > 0) {
				$whereStr = $whereStr." OR ";
			}
			$whereStr = $whereStr."rec_id=".$ids[$i]["id"];
		}
		$whereStr = $whereStr.")";
		//echo $whereStr;
		parent::_delete("tec_rem", $whereStr);
	}
	/*=============================================*/
}
//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "rec_count") {
  	$thisMonth = date("Ym",mktime(0,0,0,date("m"),1,date("Y")));
  	$whereStr = "staff_id=".$_POST["staff_id"]
  		." AND EXTRACT(YEAR_MONTH FROM start)=".$thisMonth
  		." AND rem_comp=1";

  	$res = MySQL::_count(
  			"receipts_".$_SESSION["salon"]["id"],"id",$whereStr);
  	echo $res;
  }
}
