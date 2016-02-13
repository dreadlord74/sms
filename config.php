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
define("HOST", "127.0.0.1");

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

//Токен для доступа к аккуанту
define ("TOKEN", "78e34841c362cee67622a678e5f1c01f");

error_reporting ( E_ALL ^ E_NOTICE);