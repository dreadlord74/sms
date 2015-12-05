<?php

defined ("SCRIPT") or die ("Сюда нельзя!");

//домен
define("PATH", "http://localhost/sms/");

//модель    
define("MODEL", "model/model.php");

//классы
define("CLASSES", "model/classes/");

//контроллер
define("CONTROLLER", "controller/controller.php");

//виды
define("VIEW", "view/");

//сервер
define("HOST", "localhost");

//пользователь
define("USER", "dreadlord");

//пароль
define("PASS", "123gde456bzxd");

//имя бд
define("DB", "nod");

//Название - title
define("TITLE","Смс-рассылка");

//email администратора
define("ADMIN_EMAIL", "dreadlord74@yandex.ru");

error_reporting ( E_ALL ^ E_NOTICE);