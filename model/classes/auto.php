<?php
define ("SCRIPT", TRUE);

//домен
define("PATH", "http://localhost/sms");
//сервер
define("HOST", "localhost");

//пользователь
define("USER", "dreadlord");

//пароль
define("PASS", "123gde456bzxd");

//имя бд
define("DB", "nod");


function print_arr($arr){
  echo"<pre>";
  print_r($arr);
  echo"</pre>";
}

abstract class lol{
    
    public function echo_result($otvet = ""){
        if (is_array($this->result)){
            echo json_encode($this->result);
        }else if(is_string($this->result))
            echo $this->result;
        else if (is_int($this->result))
            echo $this->result;
        else echo $otvet;
    }
}

include "mysql.php";
include "sms.php";

$db = new data_base();

$query = "SELECT token, devices FROM admin";

$result = $db->super_query($query)->get_res();

foreach ($result as $device){
    $devices[] = explode(",", $device['devices']); 
}

if (!isset($_COOKIE['PHPSESSID'])){
    
    foreach($result as $token){
        $sms = new sms($devices, $token['token']);
        
        $in = $sms->get_in_sms();

        if ($in['code'] == 0){
            foreach ($in['data'] as $value){
                $phone = str_replace("+7", "8", $value['phone']);
                
                $date_time = explode(" ", $value['date']);
                
                $query = "UPDATE `users` SET phone_ver='1', date_ver='".$date_time[0]."' WHERE phone='$phone' AND phone_ver='0'";
                
                $db->query($query);
            }
        }
        
        
        $query = "SELECT id_sms, delivered FROM `sended_sms` WHERE delivered='0' AND is_error='0'";
        
        $res = $db->super_query($query)->get_res();
        
        $ids = array();
        
        foreach ($res as $value){
            foreach($value as $key => $value1)
                if ($key == 'id_sms'){
                    $ids[] = $value1;
                }
        }
        
        $separated = (string)implode(",", $ids);
        
        $out = $sms->get_out_sms($separated);
        
        foreach($out['data'] as $value){
            if ($value['is_delivered'] == 1){
                $query = "UPDATE `sended_sms` SET delivered='1' WHERE id_sms={$value['id']}";
                $db->query($query);
            }else if ($value['is_error'] == 1){
                $query = "UPDATE `sended_sms` SET is_error='1' WHERE id_sms={$value['id']}";
                $db->query($query);
            }
        }
                
    }
}else{
    session_start();
    
    $sms = new sms($_SESSION['devices'], $_SESSION['token']);
    
    $in = $sms->get_in_sms();

        if ($in['code'] == 0){
            foreach ($in['data'] as $value){
                $phone = str_replace("+7", "8", $value['phone']);
                
                $date_time = explode(" ", $value['date']);
                
                $query = "UPDATE `users` SET phone_ver='1', date_ver='".$date_time[0]."' WHERE phone='$phone' AND phone_ver='0'";
                
                $db->query($query);
            }
        }
        
        
        $query = "SELECT id_sms, delivered FROM `sended_sms` WHERE delivered='0' AND is_error='0'";
        
        $res = $db->super_query($query)->get_res();
        
        $ids = array();
        
        foreach ($res as $value){
            foreach($value as $key => $value1)
                if ($key == 'id_sms'){
                    $ids[] = $value1;
                }
        }
        
        $separated = (string)implode(",", $ids);
        
        $out = $sms->get_out_sms($separated);
        
        foreach($out['data'] as $value){
            if ($value['is_delivered'] == 1){
                $query = "UPDATE `sended_sms` SET delivered='1' WHERE id_sms={$value['id']}";
                $db->query($query);
            }else if ($value['is_error'] == 1){
                $query = "UPDATE `sended_sms` SET is_error='1' WHERE id_sms={$value['id']}";
                $db->query($query);
            }
        }
}


/*
$handle = fopen("cronLog.txt", "a");
    $str = "Last timestamp: ".date("d.m.Y")." ".date("H:i:s")."\n";
    fwrite($handle, $str);
    fclose($handle);
    
*/