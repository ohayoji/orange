<?php
require_once 'ReceiptModel.php';

class RecDisplayModel extends ReceiptModel{
	
	var $todayStr;
	
	function __construct() {
		parent::__construct();
		
		$this->todayStr = 
			date("n月 j日",strtotime($this->_todaySQLStr));
	}
}