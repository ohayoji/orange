<?php
require_once 'RootModel.php';
class DataConvertModel extends RootModel {
	function __construct() {
		parent::__construct();
		
		if ($_POST) {
			//parent::_showPost();
		}
	}
	
}