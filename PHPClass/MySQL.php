<?php
class MySQL {

	// //Macローカルサーバー
	// const MY_SERVER = "127.0.0.1";//yosemiteアップグレード時に変更
	// const USER_NAME = 'root';
	// const PASSWORD = 'komazawa';
	// const DB_NAME = "salon_management_app";

  //Dockerサーバー
	// const MY_SERVER = "0.0.0.0:3307"; // <-macから見た場合のホストなのでwebコンテナからは使用できない
  const MY_SERVER = "db"; //docker-compose.ymlのlinksで指定している名前が使用できる
	const USER_NAME = 'root';
	const PASSWORD = 'QazxSw';
	const DB_NAME = "turba_orange";

	// dummy-01 (2015.04.19スタートの社内運用用サーバー)
    // const MY_SERVER = "mysql504.db.sakura.ne.jp";
    // const USER_NAME = 'turba';
    // const PASSWORD = 'komazawa_test01';
    // const DB_NAME = "turba_test_db";

	// Orange01.jp (一般公開本番サーバー)
	/*const MY_SERVER = "mysql504.db.sakura.ne.jp";
	 const USER_NAME = 'turba';
	 const PASSWORD = 'komazawa_test01';
	 const DB_NAME = "turba_orange";*/

   // 2018新DB 本番
 	 // const MY_SERVER = "mysql635.db.sakura.ne.jp";
 	 // const USER_NAME = 'turba';
 	 // const PASSWORD = 'komazawa_test01';
 	 // const DB_NAME = "turba_orange";

   // 2018新DB STG
 	 // const MY_SERVER = "mysql635.db.sakura.ne.jp";
 	 // const USER_NAME = 'turba';
 	 // const PASSWORD = 'komazawa_test01';
 	 // const DB_NAME = "turba_orange_test";

	//mysqliインスタンス生成メソッド
	public static function _createMysqli() {

		$mysqli = new mysqli(self::MY_SERVER,
				self::USER_NAME,
				self::PASSWORD,
				self::DB_NAME);

		if( $mysqli->connect_errno ) {
			echo '<script type="text/javascript">alert("データベースが存在しません")</script>';
			exit;
		}

		/* 文字セットを utf8 に変更 */
		$mysqli->set_charset("utf8");
		/*if (!$mysqli->set_charset("utf8")) {
			printf("Error loading character set utf8: %s\n", $mysqli->error);
		} else {
			printf("Current character set: %s\n", $mysqli->character_set_name());
		}*/

		return $mysqli;
	}

	//SQLResultのレコードごとに連想配列を生成するメソッド
	public static function _getChildArrayFromRecord($record) {
		foreach ($record as $key => $value) {
			$childArray[$key] = $value;
		}
		return $childArray;
	}

	/*--SELECT--------------------------------*/
	public static function _select(
			$tableName,$colStr,$whereStr,$orderStr = null) {

		$mysqli = self::_createMysqli();

		$sql = "SELECT ".$colStr." FROM ".$tableName
				.self::_getWhereStr($whereStr)
				.self::_getOrderByStr($orderStr);
		//echo "作成されたSQL分は".$sql;

		//SQLResultを配列に変換
		$resultArray = array();
		if ($result = $mysqli->query($sql)) {
			//echo 'SELECT成功';

			while ($record = $result->fetch_assoc()) {
				array_push($resultArray,
							self::_getChildArrayFromRecord($record));
			}
			$result->close();
		}else {
			$resultArray = null;
		}
		$mysqli->close();

		return $resultArray;
	}
	//INNERJOIN
	public static function _selectInnerJoin(
			$table1, $table2, $colStr, $joinStr, $whereStr, $orderStr) {

		$mysqli = self::_createMysqli();

		$sql = "SELECT ".$colStr." FROM ".$table1."
				INNER JOIN ".$table2."
				ON ".$joinStr
				.self::_getWhereStr($whereStr)
				.self::_getOrderByStr($orderStr);
		//echo "作成されたSQL分は".$sql;

		$resultArray = array();
		if ($result = $mysqli->query($sql)) {
			//echo 'SELECT成功';

			while ($record = $result->fetch_assoc()) {
				array_push($resultArray,
				self::_getChildArrayFromRecord($record));
			}
			$result->close();
		}else {
			$resultArray = null;
		}
		$mysqli->close();

		return $resultArray;
	}
	/*OUTERJOIN
	 * 一致しないレコードがあった場合でも軸となるテーブルのレコードは保持される
	 * この場合、２軸のテーブルのレコードはNULLになる
	 * 軸となるテーブルは$r_f(RIGHT,LEFT)で指定する
	 */
	public static function _selectOuterJoin(
			$table1, $table2, $r_f, $colStr, $joinStr, $whereStr, $orderStr) {

		$mysqli = self::_createMysqli();

		$sql = "SELECT ".$colStr." FROM ".$table1." "
				.$r_f." JOIN ".$table2."
				ON ".$joinStr
				.self::_getWhereStr($whereStr)
				.self::_getOrderByStr($orderStr);
		//echo "作成されたSQL分は".$sql;

		$resultArray = array();
		if ($result = $mysqli->query($sql)) {
			//echo 'SELECT成功';

			while ($record = $result->fetch_assoc()) {
				array_push($resultArray,
				self::_getChildArrayFromRecord($record));
			}
			$result->close();
		}else {
			$resultArray = null;
		}
		$mysqli->close();

		return $resultArray;
	}
	//WHERE句作成
	public static function _getWhereStr($whereStr){
		if ($whereStr) {
			$where = " WHERE ".$whereStr;
		}else {
			$where = "";
		}
		return $where;
	}
	//ORDER BY句作成
	public static function _getOrderByStr($orderStr) {
		if ($orderStr) {
			$order = " ORDER BY ".$orderStr;
		}else {
			$order = "";
		}
		return $order;
	}
	/*------------------------------------------*/

	/*---------INSERT-----------------*/
	public static function _insert($tableName, $colStr, $valStr) {

		$mysqli = self::_createMysqli();

		$sql = "INSERT INTO ".$tableName." (".$colStr.") VALUES (".$valStr.")";

		//echo "作成されたSQL文は　".$sql;
		$result = $mysqli->query($sql);

		if (!$result) {
			//echo '<script type="text/javascript">alert("レコードの追加に失敗しました")</script>';
			$mysqli->close();
			return false;
		}else {
			$insertId = $mysqli->insert_id;
			$mysqli->close();
			return $insertId;
		}
	}

	//カラムと値を複数持つ連想配列をinsert用文字列にして返す
	public static function _columnStringForInsert($colVal) {
		$colStr = "";
		foreach ($colVal as $key => $value) {
			$colStr = $colStr.$key.",";
		}
		//最後の,を削除
		$colStr = mb_substr($colStr, 0, -1, "UTF-8");
		return $colStr;
	}
	public static function _valueStringForInsert($colVal) {
		//quote_smartのためのconnectを作成
		$link = mysqli_connect(self::MY_SERVER,
								self::USER_NAME,
								self::PASSWORD,
								self::DB_NAME);
		$valStr = "";
		foreach ($colVal as $key => $value) {
			$valStr = $valStr.self::_quote_smart($value,$link).",";
		}
		//最後の,を削除
		$valStr = mb_substr($valStr, 0, -1, "UTF-8");
		return $valStr;
	}
	/*--------------------------*/

	/*----------UPDATE----------------*/

	public static function _update($tableName, $setStr, $whereStr) {

		$mysqli = self::_createMysqli();

		/*$sql = "UPDATE ".$tableName."
					SET ".$setStr." WHERE ".$whereStr.";";*/
		$sql = "UPDATE ".$tableName." SET ".$setStr.self::_getWhereStr($whereStr);
		/*
		ob_start();//バッファ有効化
		var_dump("作成されたSQL文は　".$sql);
		$log = ob_get_contents();//バッファから値取り出し
		ob_end_clean();//バッファのclean及び無効化

		$fp = fopen("/Library/WebServer/Documents/logs/log.txt", "w+");//ファイル作成及びopen
		fputs($fp, $log);//書き込み
		fclose($fp);//ファイルclose
		*/

		$result = $mysqli->query($sql);
		if (!$result) {
			//echo '<script type="text/javascript">alert("レコードの更新に失敗しました")</script>';
			$mysqli->close();
			return false;
		}else {
			//echo '<script type="text/javascript">alert("レコードを更新しました")</script>';
			$mysqli->close();
			return true;
		}

	}

	//カラムと値を複数持つ連想配列をupdate用文字列にして返す
	public static function _setStringForUpdate($colVal) {
		//quote_smartのためのconnectを作成
		$link = mysqli_connect(self::MY_SERVER,
								self::USER_NAME,
								self::PASSWORD,
								self::DB_NAME);

		$setStr = "";
		foreach ($colVal as $key => $value) {
			$setStr = $setStr.$key."=".self::_quote_smart($value,$link).",";
		}
		//最後の,を削除
		$setStr = mb_substr($setStr, 0, -1, "UTF-8");
		return $setStr;
	}
	/*--------------------------*/

	/*--------DELETE------------------*/
	public static function _delete($tableName, $whereStr) {

		$mysqli = self::_createMysqli();

		$sql = "DELETE FROM ".$tableName." WHERE ".$whereStr;
		//echo "作成されたSQL文は　".$sql;

		$result = $mysqli->query($sql);

		$mysqli->close();
		//return $result;
	}
	/*--------------------------*/

	/*--------COUNT------
	 * レコードの数を調べる
	 * $colNameにカラム名を指定する
	 * カラム指定しない場合は "*" を渡す
	 * ------------*/
	public static function _count($tableName, $colName, $whereStr) {

		$mysqli = self::_createMysqli();

		$sql = "SELECT COUNT(".$colName.") AS `_count` FROM "
				.$tableName." WHERE ".$whereStr;
		//echo "作成されたSQL文は　".$sql;

		if ($result = $mysqli->query($sql)) {
			while ($record = $result->fetch_assoc()) {
				//echo "<br>co=".$record["_count"];
				$count = $record["_count"];
			}
		}else {
			$count = null;
		}
		$mysqli->close();

		return $count;
	}
	/*--------------------------*/

	/*-------テンプレメソッドを使用しないクエリ実行メソッド---------------------*/
	public static function _query($sql, $queryType){

		$mysqli = self::_createMysqli();

		//echo "作成されたSQL文は　".$sql;

		if ($queryType == "select"){
			$resultArray = array();
			if ($result = $mysqli->query($sql)) {

				while ($record = $result->fetch_assoc()) {
					array_push($resultArray,
					self::_getChildArrayFromRecord($record));
				}
				$result->close();
			}else {
				$resultArray = null;
			}
			$mysqli->close();
			return $resultArray;
		}elseif ($queryType == "insert") {
			if ($result = $mysqli->query($sql)) {
				$insertId = $result;
			}else {
				$insertId = false;
			}
			$mysqli->close();
			return $insertId;
		}else {
			$mysqli->query($sql);
			$mysqli->close();
		}

	}
	/*--------------------------*/

	/*-------CREATE---------------------*/
	public static function _create($sql){

		$mysqli = self::_createMysqli();

		//echo "作成されたSQL文は　".$sql;

		$result = $mysqli->query($sql);

		if (!$result) {
			echo '<script type="text/javascript">alert("テーブル作成に失敗しました")</script>';
		}
		$mysqli->close();
	}
	/*--------------------------*/

	/*------ クウォート用のユーザー定義関数 ------*/
	public static function _quote_smart($value,$link){
		if ($value == null) {
			$value = "NULL";
		}else {
			/* magic_quotesが有効ならクォートされた文字列のクウォート部分を除去 */
			if(get_magic_quotes_gpc()){
				$value=stripslashes($value);
			}
			/* 数値あるいは数値形式の文字列以外をクウォート */
			if(!is_numeric($value)){
				$value="'".mysqli_real_escape_string($link, $value)."'";
			}
		}

		return $value;
	}
	/*--------------------------------*/
}
