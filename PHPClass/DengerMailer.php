<?php
require_once 'ReservManager.php';

class DengerMailer extends ReservManager{
	function sendDengerMailForEbisu() {
		//echo "sendDengerMailForEbisu";
		$this->init($_SESSION["salon"]["id"],
			$_SESSION["month"],$_SESSION["date"]);
		$indexes = $this->timeIndexesFromStart_end(
						$_POST["start"], $_POST["end"]);
		
		if ($_POST["seat"] < 7) {//2F
			$floor = "2F";
			$cap = $this->capas;
		}else {//6F
			$floor = "6F";
			$cap = $this->capas2;
		}
		
		$denger = false;
		$dengerMessage = $_SESSION["month"]."月"
				.$_SESSION["date"]."日 "
						.PHP_EOL.$floor.PHP_EOL;
		for ($n = 0; $n < count($indexes); $n++) {
			if ($cap[$indexes[$n]] < 2) {
				$denger = true;
				$dengerMessage = 
					$dengerMessage.$this->dengerStr($floor,
						$this->times[$indexes[$n]],
						$cap[$indexes[$n]]);
			}
		}
		$dengerMessage = $dengerMessage."になりましたよーん。";
		//echo "denM=".$dengerMessage;
		//echo "denger=".$denger;
		if ($denger) {
			$this->sendMail($dengerMessage);
			//echo $dengerMessage;
		}
	}
	
	function dengerStr($floor, $time, $capa) {
		$str = $time." の残り枠数が ".$capa.PHP_EOL;
		return $str;
	}
	
	function sendMail($message) {
		echo "sendMail";
		require_once 'MailManager.php';
		$to = "kadobeya@i.softbank.jp,
							nycp-003@docomo.ne.jp,
							electone.5.123@softbank.ne.jp,
							zu-sich-kommen@softbank.ne.jp";
		$mailM = new MailManager;
		$mailM->init("kawaguchi@turba-hm.com",
				$to,"デンジャーなお知らせ",$message);
		//$mailM->showProperty();
		$mailM->sendWithoutAlert();
		
	}
}