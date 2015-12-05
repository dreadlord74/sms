<?php

define ("SCRIPT", TRUE);

//домен
define("PATH", "http://localhost/sms1/");
//сервер
define("HOST", "localhost");

//пользователь
define("USER", "dreadlord");

//пароль
define("PASS", "123gde456bzxd");

//имя бд
define("DB", "nod");

require_once 'interfaces.php';

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

$query = "SELECT id, login, devices FROM admin";

$result = $db->super_query($query)->get_res();

unset($db, $query);

foreach ($result as $item){
    $us = new user($item['login'], "", $item['id']);

    $update = new update($us);

    $update->update_status()->update_ver();

    unset($us, $update);
}
unset($result, $query, $db);

$handle = fopen("cronLog.txt", "a");
    $str = "Last timestamp: ".date("d.m.Y")." ".date("H:i:s")."\n";
    fwrite($handle, $str);
    fclose($handle);