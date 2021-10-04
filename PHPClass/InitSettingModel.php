<?php
header("Content-Type: text/html; charset=UTF-8");
require_once 'SalonSettingModel.php';


class initSettingModel extends SalonSettingModel{
	//初期設定のページ数
	const _TOTAL_SLIDE = 5;
	
}

if($_POST['mode'] == 'updateInitFlag'){
	unset($_POST['mode']);
	$colVal = $_POST;
	$whereStr = 'id='.$_SESSION['salon']['id'];
	SalonSettingModel::_update('salons', SalonSettingModel::_setStringForUpdate($colVal), $whereStr);
}
/*if($_POST['mode'] == 'staff_name'){
	unset($_POST['mode']);
	$id = $_POST['id'];
	$mysqli = MYSQL::_createMysqli();
	$sql = 'SELECT * FROM staffs WHERE id = '.$id;
	$res = $mysqli->query($sql);*/
	/*****ログ外部ファイルに出力******
	ob_start();//バッファ有効化
	var_dump($_POST);
	var_dump($res);
	var_dump($res->num_rows);
	$log = ob_get_contents();//バッファから値取り出し
	ob_end_clean();//バッファのclean及び無効化
	
	$fp = fopen("/Library/WebServer/Documents/logs/log.txt", "w+");//ファイル作成及びopen
	fputs($fp, $log);//書き込み
	fclose($fp);//ファイルclose
	*/
	
	/*if($res->num_rows > 0){
		$sql_update = "UPDATE staffs SET _name = '".$_POST['_name']."' WHERE id = ".$id;
		$mysqli->query($sql_update);*/
		/*****ログ外部ファイルに出力******
		ob_start();//バッファ有効化
		var_dump($res);
		$log = ob_get_contents();//バッファから値取り出し
		ob_end_clean();//バッファのclean及び無効化
		
		$fp = fopen("/Library/WebServer/Documents/logs/log.txt", "a+");//ファイル作成及びopen
		fputs($fp, $log);//書き込み
		fclose($fp);//ファイルclose*/
		
	/*}else{
		$sql_insert = "INSERT INTO staffs (id, _name) VALUES (".$id.", '".$_POST['_name']."')";
		$mysqli->query($sql_insert);
		
	}
}*/
/*if($_POST['mode'] == 'staff_icon'){
	unset($_POST['mode']);
	$id = $_POST['id'];
	$mysqli = MYSQL::_createMysqli();
	$sql = 'SELECT * FROM staffs WHERE id = '.$id;
	$res = $mysqli->query($sql);
	if($res->num_rows > 0){
		$sql_update = "UPDATE staffs SET icon = '".$_POST['icon']."' WHERE id = ".$id;
		$mysqli->query($sql_update);
	}else{
		$sql_insert = "INSERT INTO staffs (id, icon) VALUES (".$id.", '".$_POST['icon']."')";
		$mysqli->query($sql_insert);

	}
}*/