<?php
require_once 'RootModel.php';
require_once 'MailManager.php';
require_once 'SalonSettingModel.php';
class AdmissionCompModel extends RootModel{
	
	var $salon;
	/*--状態管理プロパティ
	 * NO_GET：パラメータを消されてアクセスされた場合
	 * NO_PRE_ADMISSION：仮登録レコードが存在しない場合
	 * SALON_EXISTENCE：salonsテーブルにすでにレコードが存在している場合
	 * SUCCESS_ADMISSION：サロン登録が成功した場合
	 * FAIL_ADMISSION：サロン登録に失敗した場合
	 * E_MAIL_UPDATE：Eメール更新に成功
	 */
	var $condition;
	//メール送信成功フラグ
	var $sendComp = null;
	
	function __construct() {
		//セッションチェックをキャンセルする「NO」を渡す
		parent::__construct("NO");
		
		if ($_GET) {			
			
			if ($_GET["mode"] == "m_upd") {//e_mail変更処理
				if ($_GET["user_type"] == "salon") {
					$table = "salons";
				}
				
				$colVal["e_mail"] = $_GET["new_mail"];
				$res = parent::_update(
						$table,
						parent::_setStringForUpdate($colVal),
						"id=".parent::_getRealStr($_GET["a"]));
				
				if ($res) {
					$this->condition = "E_MAIL_UPDATE";
				}
				
			}else {//登録処理
				
				//GETからpre_admission_salonsのidを抽出
				$preSalonId = parent::_getRealStr($_GET["a"]);
					
				$this->salon = self::getPreAdmSalon($preSalonId);
					
				if ($this->salon) {
					//すでにサロンレコードが存在する場合はキャンセル
					$count = parent::_getCountEmailAndPass(
							"salons",$this->salon["e_mail"], $this->salon["password"]);
					if ($count > 0) {
						$this->condition = "SALON_EXISTENCE";
						return false;
					}
				
					/*--サロン本登録-------------*/
					//サロンレコード追加
					$this->salon["admission_date"] = $this->_todaySQLStr;
					$res = $this->insert_salons();
				
					if ($res) {
						//receiptテーブル作成
						self::create_receipts($res);
						//used_menu_detailsテーブル作成
						self::create_usd_menu_d($res);
						//using_subjectsテーブルに必要な勘定科目レコードを追加
						self::insert_usg_subjects($res);
						//using_menusテーブルに全メニューレコードを追加
						self::insert_usg_menus($res);
						//area_settingテーブルにエリアレコードを１つ追加
						//self::insert_area_set($res, $this->_todaySQLStr);
						//rec_info_<salon_id>ビューを作る
						parent::_create_rec_info($res);
						//usd_menu_info_<salon_id>ビューを作る
						parent::_create_usd_menu_info($res);
						//daily_report_info_<salon_id>ビューを作る
						parent::_create_daily_report_info($res);
						
						$this->insert_area($res);
							
						$this->condition = "SUCCESS_ADMISSION";
					}else {
						$this->condition = "FAIL_ADMISSION";
					}
					/*-------------------------*/
				
				
					/*--登録完了メール送信----------------*/
					// if ($this->condition == "SUCCESS_ADMISSION") {
					// 	/*--仮登録レコード削除--------------------------*/
					// 	parent::_delete("pre_admission_salons", "id=".$preSalonId);
					// 	/*--------------------------------------------*/
						 	
					// 	/*--メール送信----------------------------*/
					// 	$to = $this->salon["e_mail"];
					// 	$subject = "おめでとうございます!";
					// 	$message =
					// 	$this->salon["_name"]." 様".PHP_EOL.PHP_EOL.
					// 	"「Orange」へようこそ！".PHP_EOL.
					// 	"「Orange」へのユーザー登録が完了しました。".PHP_EOL.PHP_EOL.
					// 	"ユーザー名：".$this->salon["_name"].PHP_EOL.
					// 	"登録メールアドレス：".$this->salon["e_mail"].PHP_EOL.
					// 	"※パスワードはセキュリティ上の都合によりメールでは送信しておりません。".PHP_EOL.PHP_EOL.
					// 	"パスワードを忘れてしまった場合は、下記のURLから再発行をおこなってください。".PHP_EOL.
					// 	parent::URL_PASS_REISSUE.PHP_EOL.
					// 	"ログインページはこちら".PHP_EOL.
					// 	parent::URL_LOGIN.PHP_EOL.
					// 	"「Orange」をお楽しみください。".PHP_EOL.PHP_EOL.
					// 	"Orange".PHP_EOL.
					// 	parent::URL_HOME_PAGE;
				
					// 	$this->sendComp = MailManager::send(1, $to, $subject, $message);
					// 	/*------------------------------------------------*/
					// }
				
					/*--------------------------------------------*/
				}else {//仮登録レコードが存在しない場合
					$this->condition = "NO_PRE_ADMISSION";
				}
			}
			
		}else {//GETなしでアクセスされた場合
			$this->condition = "NO_GET";
		}
	}
	
	/*==DB=======================================================*/
	//pre_admission_salonsからレコード取得
	public static function getPreAdmSalon($preSalonId) {
		$res = parent::_select("pre_admission_salons",
				"_name,e_mail,password,admission_button_type,company_id,facebook_id",
				//"id=".parent::_getRealStr($_GET["a"]),
				"id=".$preSalonId,
				null);
		return $res[0];
	}
	
	//insert
	private function insert_salons() {
		$res = parent::_insert("salons",
				parent::_columnStringForInsert($this->salon),
				parent::_valueStringForInsert($this->salon));
		return $res;
	}
	/*public static function insert_salons() {
		$res = parent::_insert("salons",
				parent::_columnStringForInsert($_POST),
				parent::_valueStringForInsert($_POST));
		return $res;
	}*/
	
	/*--サロンごとのテーブル作成-----*/
	public static function create_receipts($salonId) {
		$sql = "CREATE TABLE `receipts_".$salonId."` (
  				`id` int(11) NOT NULL AUTO_INCREMENT,
  				`start` datetime DEFAULT NULL,
  				`end` datetime DEFAULT NULL,
  				`staff_id` int(11) DEFAULT NULL,
  				`costomer` varchar(100) DEFAULT NULL,
  				`num_visit` int(11) DEFAULT  '0',
  				`seat` int(11) DEFAULT NULL,
				`area_id` int(11) DEFAULT NULL,
  				`memo` varchar(100) DEFAULT NULL,
 				`come` tinyint(4) DEFAULT NULL,
  				`out_` tinyint(4) DEFAULT NULL,
  				`bag` varchar(100) DEFAULT NULL,
  				`pay_type` tinyint(4) DEFAULT '0',
  				`tec_disc` int(11) DEFAULT '0',
  				`pro_disc` int(11) DEFAULT '0',
  				`tec_sale` int(11) DEFAULT '0',
  				`pro_sale` int(11) DEFAULT '0',
  				`rec_comp` tinyint(4) DEFAULT '0',
  				`rem_comp` tinyint(4) DEFAULT '0',
  				PRIMARY KEY (`id`)
				)";
		parent::_create($sql);
	}
	public static function create_usd_menu_d($salonId) {
		$sql = "CREATE TABLE `used_menu_details_".$salonId."` (
  				`id` int(11) NOT NULL AUTO_INCREMENT,
  				`menu_id` int(11) DEFAULT NULL,
  				`sales` int(11) DEFAULT NULL,
  				`detail_id` int(11) DEFAULT NULL,
  				`memo` varchar(100) DEFAULT NULL,
  				`rec_id` int(11) DEFAULT NULL,
  				PRIMARY KEY (`id`)
				)";
		parent::_create($sql);
	}
	/*---------------------------*/
	
	/*--設定系レコード追加-------------------------*/
	public static function insert_usg_subjects($salonId) {
		//subjectsテーブルのレコードを取得
		$whereStr = "_name='tec_sales' OR _name='pro_sales'";
		$subjectIdArray = parent::_select("subjects", "id", $whereStr);
	
		//using_subjectsテーブルに項目の数だけレコードを追加
		$colStr = "salon_id,subject_id";
		$valStr = self::getMultiValuesStrForInsert($salonId, $subjectIdArray);
	
		parent::_insert("using_subjects", $colStr, $valStr);
	}
	public static function insert_usg_menus($salonId) {
		//menusテーブルのレコードを取得
		$menuIdArray = parent::_select("menus", "id", null);
	
		//using_menusテーブルにメニューの数だけレコードを追加
		$colStr = "salon_id,menu_id";
		$valStr = self::getMultiValuesStrForInsert($salonId,$menuIdArray);
	
		parent::_insert("using_menus", $colStr, $valStr);
	}
	/*---------------------------*/
	
	/*--その他のメソッド---------------------------*/
	//複数insertのVALUES文字列を返す
	private static function getMultiValuesStrForInsert($salonId,$array) {
		$valStr = "";
		for ($i = 0; $i < count($array); $i++) {
			$valStr = $valStr.$salonId.",".$array[$i]["id"];
			if ($i < count($array) -1) {
				$valStr = $valStr."),(";
			}
		}
		return $valStr;
	}
	
	function insert_area($salonId){
		
		$colVal = array();
		$colVal["_order"] = 1;
		$colVal["salon_id"] = $salonId;
		$colVal["start_date"] = $this->_todaySQLStr;
		
		
		$colVal["area_id"] = MySQL::_insert("area_setting",SalonSettingModel::_columnStringForInsert($colVal),
			SalonSettingModel::_valueStringForInsert($colVal));
		unset($colVal["salon_id"]);
		unset($colVal["_order"]);
		MySQL::_insert("area_seats_setting",SalonSettingModel::_columnStringForInsert($colVal),
			SalonSettingModel::_valueStringForInsert($colVal));
			
	}
	
	/*=========================================================*/
	/*============================================================*/
}