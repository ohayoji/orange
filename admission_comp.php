<?php
require_once 'PHPClass/AdmissionCompModel.php';
$model = new AdmissionCompModel();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/orange_nav.js"></script>
<script type="text/javascript" src="js/admission_comp.js"></script>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/items.css" rel="stylesheet" type="text/css"/>
<link href="css/orange_nav.css" rel="stylesheet" type="text/css"/>
<link href="css/admission.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>

<title>登録完了</title>
<script type="text/javascript">
var _salon = <?php echo json_encode($model->salon)?>;
var _condition = '<?php echo $model->condition?>';
var _sent = <?php echo json_encode($model->sendComp)?>;
</script>
</head>

<body>
<div id="container">
<div id="admission_area" class="after_orange_nav_area">
	<div id="thankyou_messe_box" class="">
		<p></p>
		<a href="admission.php" class="right_link" id="adm_link" style="display: none;">
			申し込みページへ</a>
	</div>
</div>
</div>
</body>