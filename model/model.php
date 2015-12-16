<?php

defined ("SCRIPT") or die ("Сюда нельзя!");
/**
 * Модель системы
 */
session_start();

require_once "functions.php";

require_once "interfaces.php";
/**
 * Функция автозагрузки классов
 * @param $class - имя класса
 */
function __autoload($class){
    require_once CLASSES.$class.".php";
}