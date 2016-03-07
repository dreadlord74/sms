<?php

define ("SCRIPT", TRUE);

require_once 'interfaces.php';

require_once "../config.php";

function print_arr($arr){
  echo"<pre>";
  print_r($arr);
  echo"</pre>";
}
/*
require_once "classes/vivod.php";
require_once "classes/device_sms.php";
*/
function __autoload($class){
    require_once "classes/".$class.".php";
}

$db = new data_base();

$query = "SELECT id, login FROM admin";

$result = $db->super_query($query)->get_res();

unset($db, $query);

foreach ($result as $item){

    $us = new user($item['login'], "", $item['id']);

    $update = new update($us);

    $update->update_status();

    unset($us, $update);
}
unset($result, $query, $db);


 /*
$handle = fopen("cronLog.txt", "a");
    $str = "Last timestamp: ".date("d.m.Y")." ".date("H:i:s")."\n";
    fwrite($handle, $str);
    fclose($handle);