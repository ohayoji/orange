<?php
mb_language("Japanese");
mb_internal_encoding("UTF-8");

require_once 'RootModel.php';

class MailManager{
	
	/*//Orange送信専用SENDER
	const POSTING = "From: posting@orange01.jp";
	//テスト用SENDER
	const KAWAGUCHI = "From: kawaguchi@turba-hm.com";*/
	
	/*----------send-----------------------*/
	public static function send($senderType,$to,$subject,$message){
		//return mb_send_mail($to, $subject,$message, self::SENDER);
		switch ($senderType) {
			case 1:
				$sender = RootModel::POSTING_SENDER;
			break;
			
			default:
				;
			break;
		}
		return mb_send_mail($to, $subject,$message, "From: ".$sender);
	}
	/*-----------------------------------*/
	
	//e_mail変更手続きメール
	public static function sendChangeE_mail($mode,$userType,$to,$salonIdParam) {
		$subject = "メールアドレスの変更を完了してください";
		$message =
			"このメールは、「Orange」にてアカウント情報の変更手続き中の方にお送りしています。".PHP_EOL.
			"※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。".PHP_EOL.PHP_EOL.
			"以下のURLに接続して、メールアドレスの変更を完了してください。".PHP_EOL.
			RootModel::URL_ADD_COMP."?mode=".$mode.
								"&user_type=".$userType.
								"&new_mail=".$to.
								"&a=".$salonIdParam.PHP_EOL.
			"※URLが改行されている場合は、1行につなげてブラウザのアドレスバーに入力してください。".PHP_EOL.PHP_EOL.
			"Orange".PHP_EOL.
			RootModel::URL_HOME_PAGE;
			
		self::send(1, $to, $subject, $message);
	}
}
