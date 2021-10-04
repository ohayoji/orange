<?php
require "../vendor/autoload.php";
require_once 'RootModel.php';
use WebPay\WebPay;

if (!empty($_POST["mode"])) {
  //退会処理
  if($_POST["mode"] == "cancel"){
  	//$salonStatus = MySQL::_select("salons", "recursion_id", "id = '".$_SESSION["salon"]["id"]."'")[0]["recursion_id"];

  	//recursion削除
  	$recursionID = MySQL::_select("salons", "recursion_id", "id = '".$_SESSION["salon"]["id"]."'")[0]["recursion_id"];

  	if ($recursionID) {

  		$webpay = new WebPay(RootModel::WP_SECRET_KEY);
  		$result = $webpay->recursion->delete(array("id"=>$recursionID));

  		if($result->deleted == true){
  			salonDelete();
  		}else{

  		}
  	}else {
  		salonDelete();
  	}
  }
}

//salonテーブル更新
function salonDelete() {
	$today = StringManager::getSqlDateByDateCount(0);

	/*--salonsテーブル更新---------------------*/
	$colVal = array(
			"e_mail" => null,
			"password" => null,
			"withdrawal_date" => $today,
			"deleted" => 1,
			"recursion_id" => null,
			"charge_id" => null
	);
	MySQL::_update("salons",
	MySQL::_setStringForUpdate($colVal),
	"id=".$_SESSION["salon"]["id"]);
	/*--------------------------------------*/
}
