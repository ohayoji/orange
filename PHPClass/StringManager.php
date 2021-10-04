<?php
class StringManager{
	/*
	 * 引数または返り値の種類によるネーミングルール
		引数または返り値の種類		ネーミング
		日数カウント整数			dateCount
		月数カウント整数			monthCount
		年数カウント整数			yearCount
		MySQLDATE型文字列			sqlDate
		'2014年08月'のような年月文字列		monthStr
	 */
	/*
	 *2014年08月のような独自の年月文字列（monthStr）を作るメソッド
	* 引数$intで今月から＋ー何ヶ月めかを判断する
	* ０で今月、１で来月、−１で先月
	*/
	public static function getMonthStrByMonthCount($int) {
		$Y_m = date("Y年m月",mktime(0,0,0,date("m")+$int,1,date("Y")));
		return $Y_m;
	}
	/*MySQlDATE型文字列をmonthStringに変換
	*/
	public static function getMonthStrBySqlDate($mysqlDate) {
		$array = explode("-", $mysqlDate);
		$ymStr = $array[0]."年".$array[1]."月";
		return $ymStr;
	}
	/*
	 * 年、月それぞれの文字列
	 * ０で今月、１で来月、−１で先月
	 */
	public static function getYearByMonthCount($int) {
		$year = date("Y",mktime(0,0,0,date("m")+$int,1,date("Y")));
		return $year;
	}
	public static function getMonthByMonthCount($int) {
		$month = date("m",mktime(0,0,0,date("m")+$int,1,date("Y")));
		return $month;
	}
	/*
	 * 日付文字列
	* ０で今日、１で明日、−１で昨日
	*/
	public static function getDateByDateCount($int) {
		$d = date('d', strtotime($int.' day'));
		return $d;
	}
	/*
	 * 渡された引数から月の差分を返す
	* 引数int：０で今日、１で明日、−１で昨日
	* 明日が来月の場合は１、昨日が先月の場合は−１が返る
	*/
	public static function getMonthDifferenceByDateCount($int) {
		//日付文字列を作成
		$today = self::getSqlDateByDateCount(0);
		$specifiedDate = self::getSqlDateByDateCount($int);

		//計算
		$date1=strtotime($today);
		$date2=strtotime($specifiedDate);
		$month1=date("Y",$date1)*12+date("m",$date1);
		$month2=date("Y",$date2)*12+date("m",$date2);

		$diff = $month2 - $month1;
		
		return $diff;
	}
	/*
	 * ２つの指定日の日数の差分を返す
	 */
	public static function getDayDifferenceByTwoSQLDate($date1, $date2) {
 
    		// 日付をUNIXタイムスタンプに変換
    		$timestamp1 = strtotime($date1);
    		$timestamp2 = strtotime($date2);
 		
    		// 何秒離れているかを計算
    		$seconddiff = abs($timestamp2 - $timestamp1);
 		
    		// 日数に変換
    		$daydiff = $seconddiff / (60 * 60 * 24);
 		
    		return $daydiff;
	}
	
	/*
	 * 2014-08-01のようなMySQLDATE型文字列を作成する
	 * 引数$intで今日から＋何日めかを判断する
	 */
	public static function getSqlDateByDateCount($int) {
		$Ymd = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$int,date("Y")));
		return $Ymd;
	}
	/*
	 * MySQLDATE型文字列から翌日を作成する
	 * 引数に元となるMySQLDATE型文字列（2014-08-01のような）
	 */
	public static function getNextSqlDateBySqlDate($sqlDate) {
		//年月日に分解
		$array = explode("-", $sqlDate);
		$old = mktime(0,0,0,$array[1], $array[2], $array[0]);
		//翌日を作成
		$next = date('Y-m-d', strtotime('+1 day', $old));
		return $next;
	}
	/*
	 * 日付文字列が今日と比較して新しいか、古いか、同じかを返す
	 * 引数に元となる日付文字列（2014-08-01や2014/08/01のような）
	 * 明日の場合はnew、昨日の場合はold,今日の場合はsameが返る
	 */
	public static function getDateDifferenceBySqlDate($dateStr) {
		//今日を作成
		$today = self::getSqlDateByDateCount(0);
		//日付をstrtotime()で変換して比較
		if (strtotime($dateStr) > strtotime($today)) {
			return "new";
		}elseif (strtotime($dateStr) == strtotime($today)) {
			return "same";
		}else {
			return "old";
		}
	}
	/*
	 * 特定の日時に分を加算した日時を返す
	 */
	public static function getSqlDateByMinuteCount($sqlDate,$minuteCount) {
		$date = date("Y-m-d H:i:s",
					strtotime(
						$sqlDate." +".$minuteCount." minute"));
		return $date;
	}
	/*
	 * 数値をMySQLdate型文字列に変換する
	* -1 > 前月のMySQLdate型文字列（１日付けに変換）
	*/
	public static function getSqlDateByMonthCount($int) {
		$Ymd = date("Y-m-d",mktime(0,0,0,date("m")+$int,1,date("Y")));
		return $Ymd;
	}
	/*
	 * monthCountとdateCountをMySQLdate型文字列に変換する
	 */
	public static function getSqlDateByMonth_DateCount($monthCount,$dateCount) {
		$Ymd = date("Y-m-d",mktime(0,0,0,date("m")+$monthCount,
										date("d")+$dateCount,
										date("Y")));
		return $Ymd;
	}
	/*
	 * monthCountとdateStrをMySQLdate型文字列に変換する
	 */
	public static function getSqlDateByMonth_DateStr($monthCount,$dateStr) {
		$Ymd = date("Y-m-d",mktime(0,0,0,date("m")+$monthCount,$dateStr,date("Y")));
		return $Ymd;
	}
	/*
	 * time文字列から時、分を抽出
	 * 14:15:00 → 14:15
	 */
	public static function getHmBySqlTime($time){
		$array = explode(":", $time);
		$hmStr = $array[0].":".$array[1];
		return $hmStr;
	}
	//$year, $monthで指定された月の日数を返す
	public static function getDaysAtMonthByYear_Month($year, $month) {
		$days =  date("t", mktime(0, 0, 0, $month, 1, $year));
		return $days;
	}
	//年月日から曜日を返す
	public static function getJapaneseDayByYear_Month_Date($year,$month,$date) {
		$datetime = new DateTime();
		$datetime->setDate($year, $month, $date);
		$week = array("日", "月", "火", "水", "木", "金", "土");
		$w = (int)$datetime->format('w');
		return $week[$w];
	}
	public static function getJapaneseDayBySQLDate($date) {
		$datetime = new DateTime($date);
		$week = array("日", "月", "火", "水", "木", "金", "土");
		$w = (int)$datetime->format('w');
		return $week[$w];
	}
	
	//文字列（例:2014-03）の - を削除する
	public static function deleteHyphen($str) {
		$deletedStr = str_replace("-", "", $str);
		return $deletedStr;
	}
	
	//文字列（例:10:30）の : を削除する
	public static function deleteColon($str) {
		$deletedStr = str_replace(":", "", $str);
		return $deletedStr;
	}
	
	//DATE文字列から四半期数値を返す
	public static function getQuaterBySqlDate($dateStr) {
		$month = substr($dateStr,5,2);
		$quater = ceil($month/3);
		return $quater;
	}
	//四半期数値から四半期文字列を返す
	public static function getQuaterStr($quater) {
		switch ($quater) {
			case 1:
			return "1~3月";
			case 2:
				return "4~6月";
				case 3:
					return "7~9月";
					case 4:
						return "10~12月";
			break;
			
			default:
				return null;
			break;
		}
	}
	
	//staffsをjs用文字列に変換
	/*public static function getStringForJSFromStaffs($staffs) {
		$str = "";
		for ($i = 0; $i < count($staffs); $i++) {
			$staffData = "";
			foreach ($staffs[$i] as $key => $value) {
				$staffData =
				$staffData.$key.":".$value.",";
			}
			$str = $str.$staffData."|";
		}
		return $str;
	}*/
	
}