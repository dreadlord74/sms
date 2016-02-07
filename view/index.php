<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="<?=VIEW?>style.css"/>
    <link rel="stylesheet" type="text/css" href="<?=VIEW?>main.css" />
<script type="text/javascript" src="<?=VIEW?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?=VIEW?>js/jquery-ui-1.8.22.custom.min.js"></script>
<script src="<?=VIEW?>js/Chart.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=VIEW?>js/cancel.js"></script>
    <script type="text/javascript" src="<?=VIEW?>js/mask.js"></script>
    <script type="text/javascript" src="<?=VIEW?>js/pagination.js"></script>
<title><?=$title?></title>
</head>

<body>


<?php

    if (isset($_SESSION['id'])){
        include VIEW."/inc/menu.php";
    }
	include $view.".php";
?>

</body>
</html>