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

function sende_mail (&$mail, &$tema, &$msg)
{
    $message = '
        <html>
            <head>
                <title>'.$tema.'</title>
            </head>
            <body>
                <p>'.$msg.'</p>
            </body>
        </html>';

    $headers  = "Content-type: text/html; charset=windows-1251 \r\n";
    $headers .= "From: nod74.ru\r\n";
   // $headers .= "Bcc: birthday-archive@example.com\r\n";

    mail($mail, $tema, $message, $headers);
}