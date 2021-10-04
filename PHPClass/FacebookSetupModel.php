<?php
require_once 'MySQL.php';
if (!empty($_POST["mode"])) {
  if($_POST["mode"] == "checkAccount"){

  	$mysqli = MySQL::_createMysqli();
  	$colStr = "id,e_mail,password";
  	$tableName = "salons";
  	$whereStr = "facebook_id = '".$_POST["facebook_id"]."'";

  	$sql = "SELECT ".$colStr." FROM ".$tableName
  	.MySQL::_getWhereStr($whereStr)." LIMIT 1";
  	$result = $mysqli->query($sql);
  	$record = $result->fetch_assoc();
  	/*****ログ外部ファイルに出力*****
  	ob_start();//バッファ有効化
  	var_dump($result, $result->num_rows, $_POST["facebook_id"]);
  	$log = ob_get_contents();//バッファから値取り出し
  	ob_end_clean();//バッファのclean及び無効化

  	$fp = fopen("/Library/WebServer/Documents/logs/log.txt", "a+");//ファイル作成及びopen
  	fputs($fp, $log);//書き込み
  	fclose($fp);//ファイルclose
  	*/
  	$data = array();
  	if($result->num_rows > 0){
  		$data["flag"] = "true";
  		$data["record"] = $record;
  	}
  	else{
  		$data["flag"] = "false";
  		$data["record"] = null;
  	}
  	echo json_encode($data);

  }
}
