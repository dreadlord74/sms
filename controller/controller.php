<?php
defined ("SCRIPT") or die ("Сюда нельзя!");

/**
 * Контроллер системы
 */

require_once MODEL;

$db = new data_base();

if ((isset($_SESSION['login'])) and (isset($_SESSION['id']))){
    switch ($_SESSION['access']){
        case 2:
            //ЦШ
            $us = new user_central($_SESSION['login'], "", $_SESSION['id']);
        break;
        
        case 3:
            //Область
            $us = new user_obl($_SESSION['login'], "", $_SESSION['id']);
        break;
        
        case 4:
            //город
            $us = new user($_SESSION['login'], "", $_SESSION['id']);
        break;
    }
    
    if ($us){

        $view = empty($_GET['view']) ? "add" : clear((string)$_GET['view']);
        $do = empty($_GET['do']) ? "nothing" : clear((string)$_GET['do']);
        $get = empty($_GET['get']) ? "nothing" : clear((string)$_GET['get']);

        if(isset($_POST)){
            foreach($_POST as $item){
                clear_rep($item);
            }
        }

    }else{
        $view = 'auth';
        $echo = 'Авторизация не удалась!';
    }
}else{
    $view = 'auth';
}

switch ($view) {
    case 'search':
        $title = TITLE." - Поиск";
        
        switch($get){
            case 'phone':
                $query = "SELECT fam, name, otch, date, date_ver FROM users WHERE phone='{$_POST['phone']}' AND phone_ver='1'";
                
               // $db->super_query($query)->echo_result("json");
                
                exit;
            break;
        }
        
        break;
        
    case 'auth':
        $title = TITLE." - Авторизация";
        
        if ($_GET['do'] == 'auth'){
            $us = new user($_POST['login'], $_POST['pass']);
            
            if ($us){
                redirect();
            }else{
                $echo = "Пользователь с таким паролем не обнаружен";
            }
        }
    break;
	case 'send_sms':
		$sms->send_sms($_POST['msg'], $_POST['phone']);
		break;
	
	case 'add':
        $title = TITLE." - Добавление в базу рассылки";

        switch($_SESSION['access']){
            case 2:
                //по стране
                
                $vivod_left = "<p>Выберите область: </p>
                <p>Введите город: </p>";
                
                $query = "SELECT * FROM obl";
                
                $res = $db->super_query($query)->get_res();
                
                $vivod_right = "<p><select><option>Выберите область: </option>";
                
                foreach ($res as $obl){
                    $vivod_right .= "<option value='{$obl['id']}'>{$obl['obl']}</option>";
                }
                
                unset($res);
                
                $vivod_right .= "</select></p>
                <p><input type=\"text\" id=\"gorod\" name=\"gorod\"/></p>";
                
            break;
            
            case 3:
                //по области
                $vivod_left = "<p>Введите город: </p>";
                $vivod_right = "<p><input type=\"text\" id=\"gorod\" name=\"gorod\"/></p>";
                
            break;
            
            default:
                $vivod_left = "";
                $vivod_right = "";
            break;
        }

		break;
    
    case 'add_mass':
        $title = TITLE." - Массовая загрузка";
        
        break;
    
    case 'sended':

        $title = TITLE." - Статус рассылок";

        function get ($id){
            $query = "SELECT
                            users.id,
                            users.fam,
                            users.name,
                            users.otch,
                            users.phone,
                            sended_sms.delivered,
                            sended_sms.`date`,
                            sended_sms.is_error,
                            sended_sms.id_rassilki,
                            sended_sms.msg,
                            sended_mass.tema
                            FROM
                            users
                            Inner Join sended_sms ON users.phone = sended_sms.phone
                            INNER JOIN sended_mass ON sended_mass.id = sended_sms.id_rassilki
                            WHERE
                            sended_sms.id_rassilki = $id AND sended_sms.user_id = {$_SESSION['id']} ORDER BY users.fam";
            
            $db = new data_base;

            return $db->super_query($query)->get_res();
        }

        switch ($do){
            case "get":
                if(isset($_POST['id'])){
                    $id = (int)$_POST['id'];

                    echo json_encode(get($id));

                }
                exit();
                break;

            case "get_ras"://работает неправильно - доделать!!

                $result = array();
                print_arr($_GET);
                if (isset($_GET['sub'])){
                    foreach ($_GET as $key => $value)
                        if ($_GET[$key] == "on")
                            $result[] = get($key);
                }else
                    if (isset($_SESSION['last_id']))
                        $result[] = get($_SESSION['last_id']);

                break;

            default:

                $query = "SELECT tema, id, msg FROM sended_mass";

                $res = $db->super_query($query)->get_res();

                break;
        }

        break;
	
	case 'add_new':
        switch ($us->get_prava()){
            case 2:
                //ЦШ
                $obl = (isset($_POST['obl'])) ? (string)$_POST['obl'] : "";
                $gorod = (isset($_POST['gorod'])) ? (string)$_POST['gorod'] : "";
                
            break;
            
            case 3:
                //область
                $obl = "";
                $gorod = (isset($_POST['gorod'])) ? (string)$_POST['gorod'] : "";
            break;

            default:
                $obl = "";
                $gorod = "";
            break;
        }
            
		$us->add_new($_POST['name'], $_POST['fam'], $_POST['otch'], $_POST['phone'], $_POST['mail'], $_POST['date'], $gorod, $obl)->echo_result("string");
        exit();
		break;
    
    case 'send_mass':
        $title = TITLE." - Массовая рассылка";

        switch($do){
            case "send":
                $query = "SELECT phone FROM `users` WHERE phone_ver='1'";

                $res = $db->super_query($query)->get_res();

                $phones = array();

                foreach($res as $item){
                    $phones[] = $item['phone'];
                }

                $us->get_sms_obj()->send_mass($_POST['text'], $phones, $_POST['tema'], $_POST['id'])->echo_res("json");
                exit();

                break;
            case "get_pass":
                $us->get_sms_obj()->generate_pass($_POST['tema'], $_POST['text'])->echo_result("string");
                exit();

                break;
            case "attempt":
                $us->get_sms_obj()->check_pass($_POST['id'], $_POST['pass'])->echo_result("string");
                exit();

                break;
        }

        $res = $us->get_dev_obj()->device_status()->get_result();
        
        break;
        
    case 'not_view':
        
        switch ($do){
            case "confirm_reg":
                $us->mod_settings($do, clear($_POST['ver']));
            break;
            case "confirm_msg":
                $us->mod_settings($do, clear($_POST['text']));
            break;
            
            case "get_status":
                $devices = $us->get_dev_obj()->device_status()->get_result();
            
                echo json_encode($devices['data']);
            break;
            
            case "upload":
                $us->add_mass();
            break;
            
            case "cancel":
                /*
                   file_get_contents("http://localhost/sms1/model/classes/auto.php", false);

                   $query = "SELECT id_sms, device FROM sended_sms WHERE delivered='0' AND is_error='0'";

                   $res = $db->super_query($query)->get_res();

                   $ids = array();

                   foreach ($res as $item){
                       $ids[$item['device']][] = $item['id_sms'];
                   }

                   $out_sms = $us->get_sms_obj()->get_out_sms($ids)->get_result();

                   foreach($out_sms as $device){
                       foreach($device['data'] as $item){
                           if($item['is_sended_to_phone'] != 1){
                               $query = "DELETE FROM sended_sms WHERE phone='{$item['phone']}'";

                               $db->query($query);
                           }
                       }
                   }
               */

                $update = new update($us);

                $update->update_status();

                $us->get_sms_obj()->cancel_sms()->echo_res("json");

            break;
        }
        exit();
        break;
        
    case 'view_ver':
        $title = TITLE." - Все подтвержденные";
    
        $query = "SELECT * FROM users WHERE phone_ver='1' OR email_ver='1'";
        
        $res = $db->super_query($query)->get_res();
        
        break;
    
    case 'exit':
        $us->log_out();
        break;

    case 'settings':
        $title = TITLE." - Изменение данных";

        switch($do){
            case "update":


                break;
        }

        break;
		
	default:
		
		break;
}
unset($db, $query, $do, $get);

require_once "/view/index.php";