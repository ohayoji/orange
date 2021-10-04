<?php
require_once 'RootController.php';
require_once 'AdmissionModel.php';
//require_once 'MailManager.php';

class AdmissionController extends RootController{
	
	//プラン配列
	var $plans;
	
	function __construct() {
		if ($_GET) {
			$this->plans = array("free","small","big");
		}elseif ($_POST) {
			$this->registerUserAccount();
		}
	}
	//プランセレクタ
	function createPlanSelecter() {
		echo '<select name="plan" id="plan_selecter">';
		for ($i = 0; $i < count($this->plans); $i++) {
		
			if ($this->plans[$i] == $_GET["plan"]) {
				$selectedStr = ' selected="selected"';
			}else {
				$selectedStr = "";
			}
			echo '<option value="'.$this->plans[$i].'"'.$selectedStr.'>'.$this->plans[$i].'</option>';
		}
		echo '</select>';
	}
	////////
	function createSalonInputArea() {
		echo <<< BBB
		<div class="entry">
		<div class="title">店舗情報
		<a href="javascript:void(0)" onclick="addSalon()">
							＋サロンを追加する</a>
		</div>
		<div class="contents" id="sdc">
		</div>
		</div>
BBB;
	}
	
	//ユーザー登録処理
	function registerUserAccount() {
		//会社レコード追加
		$companyId = AdmissionModel::insert_companies();
		//using_subjectsテーブルにレコードを追
		AdmissionModel::insert_usg_subjects($companyId);
		
		for ($i = 1; $i <= intval($_POST["comp_num_salon"]); $i++) {

			//サロンレコード追加
			$salonId = AdmissionModel::insert_salons($i,$companyId);
			//receiptテーブル作成
			AdmissionModel::create_receipts($salonId);
			//used_menu_detailsテーブル作成
			AdmissionModel::create_usd_menu_d($salonId);
			//テーブルにレコードを追加
			AdmissionModel::insert_usg_menus($salonId);
			//area_settingテーブルにレコードを追加
			AdmissionModel::insert_area_set($salonId,
					StringManager::getSqlDateByDateCount(0));
			//using_receipt_entriesテーブルにレコードを追加
			//AdmissionModel::insertUsingReceiptEntries($salonId);
			/*--その他必要な処理--------------------------
			 * using_subjectsの技術売上、商品売上を有効にする
			 * rec_info_<salon_id>ビューを作る
			 * usd_menu_info_<salon_id>ビューを作る
			 */
		}
	}
	//登録完了メール送信処理
	/*public static function sendThankYouMail() {
		$message = $_POST["comp_name"]." 様".PHP_EOL.PHP_EOL
				."「ハコぴた」にユーザー登録いただきありがとうございます！"
				.PHP_EOL
				."下記URLにアクセスしてユーザーパスワードを登録してください。";
		$mailM = new MailManager("kawaguchi@turba-hm.com",
				$_POST["comp_em_1"],
				"ハコぴたユーザー登録完了のお知らせ",
				$message);
		
		$succesAlert = "メールを送信しました";
		$errorAlert = "メール送信に失敗しました";
		$mailM->send($succesAlert, $errorAlert);
	}*/
}