<?php

function clear ($var){
    
    $db = new data_base();
    
    return strip_tags(mysqli_real_escape_string($db->get_id_db() ,trim($var)));
}

function clear_rep(&$var){
    $db = new data_base();

    strip_tags(mysqli_real_escape_string($db->get_id_db() ,trim($var)));
}

function redirect(){
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    header("Location: $redirect");
    exit();
}

function print_arr(&$arr){
  echo"<pre>";
  print_r($arr);
  echo"</pre>";
}