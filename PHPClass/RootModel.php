<?php
if(!isset($_SESSION)){
 session_start();
}
header("Content-Type: text/html; charset=UTF-8");
require_once __DIR__.'/../env.php';
require_once 'MySQL.php';
require_once 'StringManager.php';

class RootModel extends MySQL {
	/*--訪問者プロパティ--*/
	//訪問者種類
	var $_visiter;
	//ヘッダー表示文字（サロン名or会社名）
	var $_postName;
	//訪問者名
	var $_personName;
	/*-----------------*/

	//今日のSQLDATE型文字列
	var $_todaySQLStr;

	/*--契約に関する定義--*/
	//月額料金
	const STANDARD_PRICE = 1980;
	//無料期間（日数）
	const FREE_DAYS = 90;
	//請求日（毎月?日）
	const CRAIM_DAY = 25;
	/*-----------------*/

	//分刻み単位(プログラム全体で統一する必要があるためRootで持つ)
	const MINUTE_UNIT = 30;
	//var $_minuteUnit = 30;

	/*--デバッグ---------*/
	//タイマー
	var $m;
	var $rap;
	/*-------------------*/

	//ユーザー登録時のパラメータなどに利用するランダム文字列の文字数定義
	const STR_LENGTH = 12;


	/*==WebPay APIキー============================================================*/
	/*--川口テスト環境-------------------*/
	//公開可能鍵
	const WP_PUBLIC_KEY = "test_public_3E43zz3qr0qt0mP2Y1aA84Ij";
	//非公開鍵
	const WP_SECRET_KEY = "test_secret_6sm5XMffZ4aAfNVbmF6VPeK7";
	/*-------------------------------*/
	/*--orange商用環境--------------------*/
	/*//公開可能鍵
	const WP_PUBLIC_KEY = "live_public_6zn5sg1Tr1Qh4V55vFfm9bdy";
	//非公開鍵
	const WP_SECRET_KEY = "live_secret_2hJd9i2ozdSi0tb70B2IR0JR";*/
	/*-------------------------------*/
	/*===================================================================================*/
    
    
    //ホームページ
	const URL_HOME_PAGE = env['URL_HOME_PAGE'];
	//登録ページ
	const URL_ADD_COMP = env['URL_ADD_COMP'];
	//パスワード再発行ページ
	const URL_PASS_REISSUE = env['URL_PASS_REISSUE'];
	//ログインページ
	const URL_LOGIN = env['URL_LOGIN'];
	//スタッフサインアップページ
	const URL_SIGNUP = env['URL_SIGNUP'];
	//送信専用メールアドレス
	const POSTING_SENDER = env['POSTING_SENDER'];
	//メインメールアドレス
	const MAIN_SENDER = env['MAIN_SENDER'];

	function __construct() {


		//$this->_start();
		$this->_visiter = $_SESSION["visiter"];

		if ($this->_visiter == "staff") {
			//スタッフログイン
			$this->_postName = $_SESSION["salon"]["_name"];
			$this->_personName = $_SESSION["staff"]["_name"];

		}elseif ($this->_visiter == "salon") {
			//サロン管理者ログイン
			$this->_postName = $_SESSION["salon"]["_name"];
			$this->_personName = "管理者";

		}else {
			//会社ログイン
			$this->_postName = $_SESSION["company"]["_name"];
			$this->_personName = "管理者";
		}

		$this->_todaySQLStr =
			StringManager::getSqlDateByDateCount(0);
		//$this->_stop("Rootコンストラクト完了");
		//$this->test4(1);
	}

	/*--凡庸メソッド-----------------*/
	/**
	 * ランダム文字列生成 (英数字)
	 * $length: 生成する文字数
	 */
	public static function _makeRandStr($length) {
		$keys = array_flip(array_merge(
				range('0', '9'),
				range('a', 'z'),
				range('A', 'Z')
		));
		$s = '';
		for ($i = 0; $i < $length; $i++) {
			$s .= array_rand($keys);
		}
		return $s;
	}
	//前後にランダム文字列を付与
	public static function _pinchRandStr($str) {
		$rand1 = self::_makeRandStr(self::STR_LENGTH);
		$rand2 = self::_makeRandStr(self::STR_LENGTH);
		return $rand1.$str.$rand2;
	}
	//前後にランダム文字列が付与された文字列から本物の文字列を取り出す
	public static function _getRealStr($str) {
		//$str = $_GET["a"];
		//echo "str=".$str."<br>";
		$str = substr($str , self::STR_LENGTH , strlen($str)-self::STR_LENGTH );
		//echo "str=".$str."<br>";
		$str = substr($str , 0 , strlen($str)-self::STR_LENGTH);
		//echo "str=".$str."<br>";
		return $str;
	}
	/*--デバッグ--------------------------*/
	//post表示
	public static function _showPost() {
		echo "<br>post=";
		foreach ($_POST as $key => $value) {
			echo "<br>".$key."=".$value;
		}
	}
	//sessionを表示
	function _showSession() {
		echo "<br>session=";
		foreach ($_SESSION as $key => $value) {
			echo "<br>".$key."=".$value;
		}
	}
	//タイマー
	function _start() {
		echo "PHPTimerStart<br>";
		$this->m = microtime(true);
		$this->rap = $this->m;
	}
	function _stop($str) {
		$now = microtime(true);
		$t_from_m = $now - $this->m;
		$t_from_rap = $now - $this->rap;
		$this->rap = $now;
		echo $str." / "
			.sprintf('%0.5f',$t_from_m)
			." sec<br>--FROM_LAST / "
			.sprintf('%0.5f',$t_from_rap)." sec<br>";
	}
	//タイマー（public）
	public static function _rapTime($str, $lastTime) {
		$now = microtime(true);
		$rap = $now - $lastTime;
		echo $str." / ".sprintf('%0.5f',$rap)." sec<br>";
		return $now;
	}
	/*----------------------------------------*/
	//降順月データ配列（現在から過去に遡る）
	function _getMonthDatasDesc($count) {
		$monthDatas = array();
		for ($i = 0; $i > 0 - $count; $i--) {
			$monData["text"] = date("Y年n月",mktime(0,0,0,date("m")+$i,1,date("Y")));
			$monData["value"] = date("Y-m",mktime(0,0,0,date("m")+$i,1,date("Y")));
			array_push($monthDatas, $monData);
		}
		return $monthDatas;
	}
	/*降順月データ配列（開始、終了月を指定）
	 * 開始、終了月は数値で指定
	 * 現在の月を0とし、来月なら1、前月なら-1
	 */
	function _getRangeMonthDatasDesc($new,$old) {
		$monthDatas = array();
		for ($i = $new; $i >= $old; $i--) {
			$monData["text"] = StringManager::getMonthStrByMonthCount($i);
			$monData["value"] = date("Y-m",mktime(0,0,0,date("m")+$i,1,date("Y")));
			array_push($monthDatas, $monData);
		}
		return $monthDatas;
	}
	/*降順年データ配列（開始、終了年を指定）
	 * 開始、終了年は数値で指定
	 * 現在の年を0とし、来年なら1、前年なら-1
	 */
	function _getRangeYearDatasDesc($new,$old) {
		$yearDatas = array();
		for ($i = $new; $i >= $old; $i--) {
			$yearData["text"] = date("Y年",mktime(0,0,0,1,1,date("Y")+$i));
			$yearData["value"] = date("Y",mktime(0,0,0,1,1,date("Y")+$i));
			array_push($yearDatas, $yearData);
		}
		return $yearDatas;
	}
	/*時間配列
	 * 一桁の場合はvalueに前0をつける
	 */
	function _getHours() {
		$hours = array();
		for ($i = 0; $i <= 24; $i++) {
			$h["value"] = str_pad($i, 2, "0", STR_PAD_LEFT);
			$h["text"] = $i;
			array_push($hours, $h);
		}
		return $hours;
	}
	/*分配列
	 * 一桁の場合はvalueに前0をつける
	 */
	function _getMitutes() {
		$minutes = array();
		$val = 0;
		while ($val < 60) {
			$m["value"] = str_pad($val, 2, "0", STR_PAD_LEFT);
			$m["text"] = $val;
			array_push($minutes, $m);
			$val = $val + self::MINUTE_UNIT;
		}
		return $minutes;
	}
	/*--TRUNCATE文　(四捨五入) を生成する-------------------
	 * !!これ以外の方法で報酬を計算する事を禁止!!*/
	/*//技術報酬
	protected static function _getTecincString(){
		return "TRUNCATE(tec_sale*(tec_rem_v/100)+0.5, 0)";
	}
	//商品報酬
	protected static function _getProincString(){
		return "TRUNCATE(pro_sale*(pro_rem_v/100)+0.5, 0)";
	}*/
	/*------------------------------*/
	/*--四捨五入で報酬計算をする-------------------
	 * !!これ以外の方法で報酬を計算する事を禁止!!*/
	protected static function _getIncentive($sales, $rem){
		return round($sales * ($rem / 100));
	}
	/*------------------------------*/


	/*==凡庸 MySQL メソッド===========================*/
	//salons,staffs,companiesからレコード存在チェック
	public static function _getCountEmailAndPass($tableName,$eMail,$pass) {
		$count = parent::_count(
				$tableName,
				"*",
				"e_mail='".$eMail."' AND password='".$pass."'");
		return $count;
	}
	//salonIdからサロンスタッフのIDと名前を取得
	public static function _getStaffs($salonId) {
		$array = parent::_select("staffs",
				"id,_name",
				"salon_id=".$salonId, null);
		return $array;
	}
	//salonIdからサロンスタッフのID、名前、削除フラグを取得
	public static function _getStaffsWithDeleted($salonId) {
		$array = parent::_select("staffs",
				"id,_name,deleted",
				"salon_id=".$salonId, "deleted,id");
		return $array;
	}
	//現在生きているサロンスタッフのIDと名前を取得
	public static function _getLivedStaffs($salonId) {
		$array = parent::_select("staffs",
				"id,_name",
				"salon_id=".$salonId." AND deleted=0",
				null);
		return $array;
	}
	//サロンの月売上データを取得
	public static function _getMonthlyData($salonId,$month){

		$colStr = "SUM(tec_sale) AS tec_total,SUM(pro_sale) AS pro_total,
				SUM(CASE WHEN pay_type=0 THEN tec_sale+pro_sale ELSE 0 END) AS cash_total,
				SUM(CASE WHEN pay_type=1 THEN tec_sale+pro_sale ELSE 0 END) AS card_total,
        SUM(CASE WHEN pay_type=2 THEN tec_sale+pro_sale ELSE 0 END) AS e_money_total";
		$whereStr = "EXTRACT(YEAR_MONTH FROM start)=".$month.
					" AND rec_comp=1";

		$array = parent::_select("rec_info_".$salonId, $colStr, $whereStr, $orderStr);

		return $array;
	}
	/*--staffs更新------------*/
	public static function _updateStaffs() {
		$id = $_POST["id"];
		unset($_POST["id"]);
		$colVal = $_POST;
		$res = parent::_update("staffs",
				parent::_setStringForUpdate($colVal),
				"id=".$id);
		return $res;
	}
	/*------------------------*/
	/* staff_salary_setting,staff_percentage_settingから
	 * 該当月に有効のスタッフ基本給、歩合率を指定するWHERE句作成
	 * $table:staff_salary_setting or staff_percentage_setting
	 * $date:該当月のDATE型文字列
	 */
	public static function _getWhereStr_salary_percentage($table,$date) {
		return $table.".start_date<='".$date.
			"' AND (".$table.".disable_date>'".$date.
			"' OR ".$table.".disable_date IS NULL)";
	}
	/* スタッフの基本給設定を取得（該当月に有効のもの）
	 * $date:該当月のDATE型文字列
	 */
	public static function _getStaffSalary($date,$staffId) {
		$colStr = "salary";
		$whereStr = "staff_id=".$staffId." AND ".
				self::_getWhereStr_salary_percentage(
						"staff_salary_setting",$date);
		$array = parent::_select("staff_salary_setting",
				$colStr, $whereStr, null);
		return $array[0]["salary"];
	}
	/* スタッフの歩合設定を取得（該当月に有効のもの）
	 * $date:該当月のDATE型文字列
	 */
	public static function _getStaffPercentage($date,$staffId) {
		$colStr = "percentage";
		$whereStr = "staff_id=".$staffId." AND ".
			self::_getWhereStr_salary_percentage(
					"staff_percentage_setting",$date);
		$array = parent::_select("staff_percentage_setting",
				$colStr, $whereStr, null);
		return $array[0]["percentage"];
	}
	public static function _getWhereStr_salonIDs($salonIDs){
		$str = "WHERE";
		for($i=0 ; $i < count($salonIDs) ; $i ++){
			$subStr = " staffs.salon_id=".$salonIDs[$i]." OR";
			$str = $str.$subStr;
		}
		$str = substr($str, 0, -3);
		return $str;
	}
	/*
	//pageを$._getNavigation@common.js:47向けに取ってくるメソッド
	public static function _getSalonPages(){
		$tableName = "salon_pages";
		$colStr = "local_name,url";
		$array = parent::_select($tableName, $colStr);
		return $array;
	}
	*/
	function getSalonPages(){
		$colStr = "salon_pages.id AS salon_pages_id,salon_pages._name,
				salon_pages.local_name,salon_pages.url";

		if ($this->_visiter == "salon") {
			$colStr = $colStr.",locking_salon_pages.id AS id_in_locking_salon_pages";
			$joinStr = "salon_pages.id=locking_salon_pages.salon_pages_id AND locking_salon_pages.salon_id="
					.$_SESSION["salon"]["id"];
			$array = parent::_selectOuterJoin(
					"salon_pages", "locking_salon_pages", "LEFT",
					$colStr, $joinStr, null, "salon_pages_id");
		}else {
			$array = parent::_select("salon_pages", $colStr, null, null);
		}

		return $array;
	}

	//給与控除項目リスト
	public static function _getDeductions() {
		return parent::_select(
				"deductions", "id,local_name", null, "_order");
	}

	/*===============================================*/

	public static function _create_rec_info($salonId) {
		$v = "CREATE VIEW rec_info_".$salonId." AS ";
		$t = $v.
		"SELECT receipts_".$salonId.".id AS rec_id,
		start,end,staff_id,costomer,num_visit,seat,area_id,memo,come,out_,bag,pay_type,tec_disc,pro_disc,tec_sale,pro_sale,rec_comp,rem_comp,
		net.id AS net_id,
		student.id AS student_id,
		free.id AS free_id,
		free.value AS free_v,
		point.id AS point_id,
		point.value AS point_v,pro_rem.id AS pro_rem_id,
		pro_rem.value AS pro_rem_v,
		tec_rem.id AS tec_rem_id,
		tec_rem.value AS tec_rem_v,
		staffs._name AS staff_name,
		staffs.icon AS staff_icon,
		staffs.color AS staff_color
		 FROM receipts_".$salonId."
		 INNER JOIN staffs
		 ON staff_id = staffs.id
		 LEFT JOIN net
		 ON net.salon_id = ".$salonId."
		 AND net.rec_id = receipts_".$salonId.".id
		 LEFT JOIN student
		 ON student.salon_id = ".$salonId."
		 AND student.rec_id = receipts_".$salonId.".id
		 LEFT JOIN free
		 ON free.salon_id = ".$salonId."
		 AND free.rec_id = receipts_".$salonId.".id
		 LEFT JOIN point
		 ON point.salon_id = ".$salonId."
		 AND point.rec_id = receipts_".$salonId.".id
		 LEFT JOIN pro_rem
		 ON pro_rem.salon_id = ".$salonId."
		 AND pro_rem.rec_id = receipts_".$salonId.".id
		 LEFT JOIN tec_rem
		 ON tec_rem.salon_id = ".$salonId."
		 AND tec_rem.rec_id = receipts_".$salonId.".id
		 ORDER BY start";
		parent::_query($t,null);
	}
	/*function test3($salonId) {
		$v = "CREATE VIEW usg_menu_info AS ";
		$s =
		"SELECT
		 GROUP_CONCAT(menu_detail_setting.id) AS umd_id,
		GROUP_CONCAT(menu_detail_setting._name) AS umd_name,
		menu_detail_setting.menu_id,
		GROUP_CONCAT(menu_detail_setting.price) AS umd_price,
		GROUP_CONCAT(menu_detail_setting._order) AS umd_order,
		GROUP_CONCAT(menu_detail_setting.selected) AS umd_selected,
		GROUP_CONCAT(menu_detail_setting.deleted) AS umd_deleted,
		menus.local_name,menus.on_img,menus.off_img
		 FROM menu_detail_setting INNER JOIN menus
		 ON menu_id=menus.id
		 WHERE menu_detail_setting.salon_id=".$salonId.
		" GROUP BY menu_id";
		parent::_query($s, "select");
	}**/
	public static function _create_usd_menu_info($salonId) {
		$v = "CREATE VIEW usd_menu_info_".$salonId." AS ";
		$s =
		"SELECT
		 used_menu_details_".$salonId.".id,
		used_menu_details_".$salonId.".rec_id,
		receipts_".$salonId.".start AS start,
		used_menu_details_".$salonId.".menu_id,
		menus.local_name AS menu_name,
		used_menu_details_".$salonId.".sales,
		used_menu_details_".$salonId.".detail_id,
		menus.on_img,menus.off_img,menus.str_icon
		 FROM used_menu_details_".$salonId."
		 LEFT JOIN menus ON used_menu_details_".$salonId.".menu_id=menus.id
		 LEFT JOIN receipts_".$salonId.
		" ON used_menu_details_".$salonId.".rec_id=receipts_".$salonId.".id
		 ORDER BY rec_id";
		parent::_query($v.$s,null);
	}
	public static function _create_daily_report_info($salonId) {
		$v = "CREATE VIEW daily_report_info_".$salonId." AS ";
		$s =
		"SELECT
		 DATE_FORMAT(start, '%Y-%m-%d') AS date,
		DAYNAME(start) AS dayname,
		SUM(tec_sale) AS tec_sale,SUM(pro_sale) AS pro_sale,
		SUM(CASE WHEN pay_type=0 THEN tec_sale ELSE 0 END) AS cash_tec,
		SUM(CASE WHEN pay_type=1 THEN tec_sale ELSE 0 END) AS card_tec,
    SUM(CASE WHEN pay_type=2 THEN tec_sale ELSE 0 END) AS e_money_tec,
		SUM(CASE WHEN pay_type=0 THEN pro_sale ELSE 0 END) AS cash_pro,
		SUM(CASE WHEN pay_type=1 THEN pro_sale ELSE 0 END) AS card_pro,
    SUM(CASE WHEN pay_type=2 THEN pro_sale ELSE 0 END) AS e_money_pro,
		COUNT(id) AS count
		 FROM receipts_".$salonId.
		" WHERE rec_comp=1 GROUP BY DATE_FORMAT(start, '%Y-%m-%d'),DAYNAME(start)
		 ORDER BY DATE_FORMAT(start, '%Y-%m-%d')";
		parent::_query($v.$s,null);
	}
}

/*--ajax----------------------------*/
/* データを返すコード
 * if ($dataType == "json") { }elseif{ }...
 * の部分はまとめて別メソッドにするとバグるので各メソッドにそれぞれ書く
 */
if (!empty($_POST["mode"])) {
  //insert
  if ($_POST["mode"] == "insert") {

  	$colVal = $_POST;
  	$table = $colVal["table"];

  	if ($colVal["data_type"]) {
  		$dataType = $colVal["data_type"];
  		unset($colVal["data_type"]);
  	}

  	unset($colVal["mode"]);
  	unset($colVal["table"]);

  	$res = MySQL::_insert($table,
  			MySQL::_columnStringForInsert($colVal),
  			MySQL::_valueStringForInsert($colVal));

  	if ($dataType == "json") {
  		echo json_encode($res);
  	}elseif ($dataType == "text") {
  		echo $res;
  	}
  }
  //update
  if ($_POST["mode"] == "update") {

  	$colVal = $_POST;
  	$id = $colVal["id"];
  	$table = $colVal["table"];

  	if ($colVal["data_type"]) {
  		$dataType = $colVal["data_type"];
  		unset($colVal["data_type"]);
  	}

  	unset($colVal["mode"]);
  	unset($colVal["id"]);
  	unset($colVal["table"]);

  	$res = MySQL::_update($table,
  			MySQL::_setStringForUpdate($colVal),
  			"id=".$id);

  	if ($dataType == "json") {
  		echo json_encode($res);
  	}elseif ($dataType == "text") {
  		echo $res;
  	}else {
  		echo $res;
  	}
  }
  //delete
  if ($_POST["mode"] == "delete") {
  	$res = MySQL::_delete($_POST["table"], "id=".$_POST["id"]);

  	if ($dataType == "json") {
  		echo json_encode($res);
  	}elseif ($dataType == "text") {
  		echo $res;
  	}
  }
}
/*-------------------------------------*/
