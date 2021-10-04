<?php
require "../vendor/autoload.php";
//require_once 'RootModel.php';
require_once 'PlanManager.php';
use WebPay\WebPay;


//退会処理
if (!empty($_POST["mode"])) {
  if($_POST["mode"] == "downgrade"){

  	$planManager = new PlanManager($_SESSION["salon"]["id"]);

  	//recursion削除
  	$recursionID = MySQL::_select("salons", "recursion_id", "id = '".$_SESSION["salon"]["id"]."'")[0]["recursion_id"];
  	$webpay = new WebPay(RootModel::WP_SECRET_KEY);
  	$result = $webpay->recursion->delete(array("id"=>$recursionID));


  	if($result->deleted == true){
  		//削除に成功した場合何か返したい場合ここに記述
  		if ($planManager->inTrialPeriod == true) {//お試し期間中
  			$colVal = array(
  					"recursion_id" => null,
  					"charge_id" => null
  			);
  		}else {
  			$colVal = array(
  					"plan" => "free",
  					"recursion_id" => null,
  					"charge_id" => null
  			);
  		}

  		MySQL::_update("salons",
  						MySQL::_setStringForUpdate($colVal),
  						"id=".$_SESSION["salon"]["id"]);

  	}else{

  	}
  }
}
