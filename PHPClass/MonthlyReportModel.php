<?php
require_once 'RootModel.php';
require_once 'CompanySettingModel.php';
class MonthlyReportModel extends RootModel{

	/*---TimeProperties---
	 * 期間を管理するプロパティ
	 */
	//月セレクタで選択可能な月数
	//const MON_COUNT = 48;
	const NEAR_MONTH = -1;
	const FAR_MONTH = -48;
	//年セレクタで選択可能な年数
	const NEAR_YEAR = 0;
	const FAR_YEAR = -11;
	//月リスト
	var $monthDatas;
	//年リスト
	var $yearDatas;
	//期間指定
	const START_COUNT = -12;
	const END_COUNT = -1;
	var $start;
	var $end;
	/*--------------------*/
	var $usingSubjects;
	var $monthlyReport;

	/*--会社ログイン用プロパティ---*/
	//サロン情報配列
	var $salonInfo;
	/*------------------------*/

	/*--$_SESSION["mr_condition"]["unit_type"]----
	 * 月次：monthly
	 * 四半期合計：quarter_total
	 * 四半期平均：quarter_ave
	 * 年次：year
	 */

	function __construct() {
		parent::__construct();

		//parent::_showPost();

		$this->monthDatas = parent::_getRangeMonthDatasDesc(
					self::NEAR_MONTH,self::FAR_MONTH);
		$this->yearDatas = parent::_getRangeYearDatasDesc(
					self::NEAR_YEAR,self::FAR_YEAR);


		/*--$_SESSION["mr_condition"]作成---------------------*/
		if ($_POST) {
			if (!empty($_POST["view_mode"])) {
				$_SESSION["mr_condition"]["view_mode"] = $_POST["view_mode"];
			}else {
				$_SESSION["mr_condition"]["unit_type"] = $_POST["unit_type"];
				$_SESSION["mr_condition"]["start"] = $_POST["start"];
				$_SESSION["mr_condition"]["end"] = $_POST["end"];
				if ($this->_visiter == "company") {
					$_SESSION["mr_condition"]["salon"] = $_POST["salon"];
				}
			}

		}else {
			if (empty($_SESSION["mr_condition"])) {
				//$_SESSION["mr_condition"]["view_mode"] = "graph";
				//$_SESSION["mr_condition"]["view_mode"] = "table";
				$_SESSION["mr_condition"]["view_mode"] = "list";
				$_SESSION["mr_condition"]["unit_type"] = "monthly";
				$_SESSION["mr_condition"]["start"] =
					date("Y-m",mktime(0,0,0,date("m")+self::START_COUNT,1,date("Y")));
				$_SESSION["mr_condition"]["end"] =
					date("Y-m",mktime(0,0,0,date("m")+self::END_COUNT,1,date("Y")));

				if ($this->_visiter == "salon") {//サロンログイン時
					$_SESSION["mr_condition"]["salon"] =
									$_SESSION["salon"]["id"];
				}else {//会社ログイン時
					$_SESSION["mr_condition"]["salon"] = 0;
				}
			}
		}
		/*---------------------------------------------------------*/

		if ($this->_visiter == "salon") {//サロンログイン
			$this->salonInfo = null;
			$this->usingSubjects = self::getSalonUsingSubjects($_SESSION["mr_condition"]["salon"]);
		}else {//会社ログイン
			$this->salonInfo = CompanySettingModel::getSalonStatus();
			$this->usingSubjects = self::getCompanyUsingSubjects($this->salonInfo);
		}
		//$this->usingSubjects = self::getUsingSubjects();

		/*--$_SESSION["mr_condition_usedSub"]作成--------------------------*/
		if ($_POST) {
			if (empty($_POST["view_mode"])) {
				//using subjects
				for($i=0; $i < count($this->usingSubjects); $i++){
          if (!empty($_POST[$this->usingSubjects[$i]["_name"]])) {
            if($_POST[$this->usingSubjects[$i]["_name"]] == "checked"){
  						$_SESSION["mr_condition_usedSub"][$this->usingSubjects[$i]["_name"]] = "checked";
  					}
  					else{
  						$_SESSION['mr_condition_usedSub'][$this->usingSubjects[$i]["_name"]] = NULL;
  					}
          }
				}
			}
		}else {
			if (empty($_SESSION["mr_condition_usedSub"])){
				for($i=0; $i < count($this->usingSubjects); $i++){
					$_SESSION['mr_condition_usedSub'][$this->usingSubjects[$i]["_name"]] = NULL;
					$_SESSION["mr_condition_usedSub_localName"][$this->usingSubjects[$i]["_name"]] =
					$this->usingSubjects[$i]["local_name"];
				}
			}
		}
		/*--------------------------------------------------------------*/


		/*--monthlyReport---------*/
		if ($_SESSION["mr_condition"]["salon"] == 0) {
			$baseReport = $this->getMonthlyReportAllSalon();
		}else {
			$baseReport = $this->getMonthlyReport();
		}
		$baseReport = $this->arrangeBaseRepport($baseReport);
		$this->monthlyReport = $baseReport;
		/*------------------------------*/


	}

	/*--ベース月報配列を単位タイプに合わせて組み替える---------------*/
	function arrangeBaseRepport($baseReport) {

		$unitType = $_SESSION["mr_condition"]["unit_type"];
		$arrangedRep = array();//baseReportアレンジ後の配列

		if ($unitType == "monthly") {//月次
			//month文字を変換
			for ($i = 0; $i < count($baseReport); $i++) {
				$baseReport[$i]["month"] =
					date("Y年n月",strtotime($baseReport[$i]["month"]));
			}
			$arrangedRep = $baseReport;

		}elseif ($unitType == "quarter_total"
				|| $unitType == "quarter_ave") {//四半期

			$q = "format";
			$monthNum = 0;//四半期配列に追加された月数

			for ($i = 0; $i < count($baseReport); $i++) {
				$m = $baseReport[$i]["month"];

				$quaterArray;
				$newq = StringManager::getQuaterBySqlDate($m);

				if ($q == $newq) {//同四半期の場合
					//各科目を足す
					foreach ($baseReport[$i] as $key => $value) {
						if ($key != "month") {
							$quaterArray[$key] = $quaterArray[$key]+$value;
						}
					}
					$monthNum++;

					//最後の場合は追加
					if ($i == count($baseReport) -1) {
						if ($unitType == "quarter_ave") {
							//各科目を月数で割り平均を出す(四捨五入)
							foreach ($quaterArray as $key => &$value) {
								if ($key != "month") {
									$value = round($value /$monthNum);
								}
							}
						}
						array_push($arrangedRep, $quaterArray);
					}

				}else {//四半期が変わった場合
					//四半期配列を追加
					if ($q != "format") {
						if ($unitType == "quarter_ave") {
							//各科目を３で割り平均を出す(四捨五入)
							foreach ($quaterArray as $key => &$value) {
								if ($key != "month") {
									$value = round($value /$monthNum);
								}
							}
						}
						array_push($arrangedRep, $quaterArray);
						$quaterArray = null;
					}
					//新しい年配列を初期設定
					$q = $newq;
					$quaterArray = array();
					foreach ($baseReport[$i] as $key => $value) {
						if ($key != "month") {
							$quaterArray[$key] = $value;
						}else {
							$y = substr($m, 0, 4);
							$quaterArray["month"] = $y."年".
								StringManager::getQuaterStr($q);
						}
					}

					$monthNum = 1;

					//最後の場合は追加
					if ($i == count($baseReport) -1) {
						array_push($arrangedRep, $quaterArray);
					}
				}
			}

		}elseif ($unitType == "year") {//年合計

			$yearStr = "format";//年文字列

			for ($i = 0; $i < count($baseReport); $i++) {

				$yearArray;//年ごとの配列
				$newYearStr = date("Y年",strtotime($baseReport[$i]["month"]));

				if ($yearStr == $newYearStr) {//年が同じ場合
					//各科目を足す
					foreach ($baseReport[$i] as $key => $value) {
						if ($key != "month") {
							$yearArray[$key] = $yearArray[$key]+$value;
						}
					}
					//最後の場合は追加
					if ($i == count($baseReport) -1) {
						array_push($arrangedRep, $yearArray);
					}

				}else {//年が変わった時
					//年配列を追加
					if ($yearStr != "format") {
						array_push($arrangedRep, $yearArray);
						$yearArray = null;
					}
					//新しい年配列を初期設定
					$yearStr = $newYearStr;
					$yearArray = array();
					foreach ($baseReport[$i] as $key => $value) {
						if ($key != "month") {
							$yearArray[$key] = $value;
						}else {
							$yearArray["month"] = $yearStr;
						}
					}
					//最後の場合は追加
					if ($i == count($baseReport) -1) {
						array_push($arrangedRep, $yearArray);
					}
				}
			}
		}
		return $arrangedRep;
	}
	/*----------------------------------------------------------*/

	/*==DB================================================*/
	//有効勘定科目を取得
	//サロンログイン
	public static function getSalonUsingSubjects($salonId) {
		/*$colStr = "subjects.local_name,subjects._name";
		$joinStr = "subjects.id=using_subjects.subject_id";
		$whereStr = "using_subjects.salon_id=".$salonId;
		$array = parent::_selectOuterJoin(
				"subjects", "using_subjects", "LEFT",
				$colStr, $joinStr, $whereStr, "subjects.id");
		return $array;*/
		$colStr = "subjects.local_name,subjects._name";
		$joinStr = "subjects.id=using_subjects.subject_id";
		$whereStr = "using_subjects.salon_id=".$salonId;
		$array = parent::_selectOuterJoin(
				"subjects", "using_subjects", "LEFT",
				$colStr, $joinStr, $whereStr, "subjects._order");
		return $array;
	}
	public static function getCompanyUsingSubjects($salons) {
		$whereStr = "(";
		for ($i = 0; $i < count($salons); $i++) {
			if ($i > 0) {
				$whereStr = $whereStr." OR ";
			}
			$whereStr = $whereStr."salon_id=".$salons[$i]["id"];
		}
		$whereStr = $whereStr.")";

		/*$sql = "SELECT subjects.local_name,subjects._name
				 FROM subjects INNER JOIN using_subjects
				 ON subjects.id=using_subjects.subject_id
				 AND ".$whereStr." GROUP BY subjects.id";*/
		$sql = "SELECT subjects.local_name,subjects._name
				 FROM subjects INNER JOIN using_subjects
				 ON subjects.id=using_subjects.subject_id
				 AND ".$whereStr." GROUP BY subjects._order";
		$array = parent::_query($sql, "select");
		return $array;
	}

	//月報データを取得
	function getMonthlyReport() {
		$start = StringManager::deleteHyphen($_SESSION["mr_condition"]["start"]);
		$end = StringManager::deleteHyphen($_SESSION["mr_condition"]["end"]);

		$colStr = "month";
		for ($i = 0; $i < count($this->usingSubjects); $i++) {
			$colStr = $colStr.",".$this->usingSubjects[$i]["_name"];
		}
		$whereStr = "salon_id=".$_SESSION["mr_condition"]["salon"].
			" AND EXTRACT(YEAR_MONTH FROM month)>=".$start.
			" AND EXTRACT(YEAR_MONTH FROM month)<=".$end;
		$array = parent::_select("monthly_reports",
				$colStr, $whereStr, "month");
		return $array;
	}
	//月報データを取得(全サロン)
	function getMonthlyReportAllSalon() {
		$start = StringManager::deleteHyphen($_SESSION["mr_condition"]["start"]);
		$end = StringManager::deleteHyphen($_SESSION["mr_condition"]["end"]);

		$colStr = "month";
		for ($i = 0; $i < count($this->usingSubjects); $i++) {
			$colStr = $colStr.",SUM("
					.$this->usingSubjects[$i]["_name"].
					") AS ".$this->usingSubjects[$i]["_name"];
		}
		$salonIds = "salon_id=";
		for ($i = 0; $i < count($this->salonInfo); $i++) {
			$salonIds = $salonIds.$this->salonInfo[$i]["id"];
			if ($i < count($this->salonInfo) -1) {
				$salonIds = $salonIds." OR salon_id=";
			}
		}
		$whereStr = "(".$salonIds.")".
		" AND EXTRACT(YEAR_MONTH FROM month)>=".$start.
		" AND EXTRACT(YEAR_MONTH FROM month)<=".$end;

		$sql = "SELECT ".$colStr." FROM monthly_reports".
				" WHERE ".$whereStr." GROUP BY month";
		$array = parent::_query($sql, "select");
		return $array;
	}
	/*====================================================*/
}
//ajax
if (!empty($_POST["mode"])) {
  if ($_POST["mode"] == "change_view_mode") {
  	$_SESSION["mr_condition"]["view_mode"] = $_POST["view_mode"];
  }
}
