<?php

function clear ($var){
    
    $db = new data_base();
    
    return strip_tags(mysqli_real_escape_string($db->get_id_db() ,trim($var)));
}

function clear_rep(&$var){
    $db = new data_base();

    strip_tags(mysqli_real_escape_string($db->get_id_db() ,trim($var)));
}

function redirect($link =''){
    if ($link =''){
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
        header("Location: $redirect");
    }else{
        header("Location: http://".PATH."?view=".$link);
    }
    exit();
}

function print_arr(&$arr){
  echo"<pre>";
  print_r($arr);
  echo"</pre>";
}