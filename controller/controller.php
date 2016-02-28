<?php
defined ("SCRIPT") or die ("Сюда нельзя!");

/**
 * Контроллер системы
 */

//подключение модели
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

        if (isset($_GET)){
            foreach ($_GET as $item){
                clear_rep($item);
            }
        }

    }else{
        $view = 'auth';
    }
}else{
    $view = 'auth';
}

switch ($view) {
    case 'search':
        $title = TITLE." - Поиск";
        
        switch($get){
            case 'phone':
                $phone = $_POST['phone'];
                phoneReplace($phone);
                $query = "SELECT fam, name, otch, date FROM users WHERE phone='$phone'";
                
                $db->super_query($query, false)->echo_result("json");
                
                exit;
            break;
        }
        
        break;
        
    case 'auth':
        $title = TITLE." - Авторизация";
        $auth = false;
        if ($_GET['do'] == 'auth'){
            $us = new user($_POST['login'], $_POST['pass']);
           /* if ($auth) {
                echo "true";
                exit();
            }
            else{
                echo "false";
                exit;
            }*/

            echo $auth;
            exit;
        }
    break;
	case 'send_sms':
		//$sms->send_sms($_POST['msg'], $_POST['phone']);
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

                $query = "SELECT * FROM goroda WHERE obl=".$us->obl;

                $goroda = $db->super_query($query)->get_res();

                $dopOptions = "<label for='gorod'>Выберите город: <select style='position: relative; float: right;
width: 173px; height: 20px;'>";

                foreach ($goroda as $gorod){
                    $dopOptions .= "<option name='gorod' value='".$gorod['id']."'>".$gorod['gorod']."<option>";
                }

                $dopOptions .= "</select></label>";
                
            break;

            case 1:
                $query = "SELECT * FROM goroda WHERE obl=".$us->obl;

                $goroda = $db->super_query($query)->get_res();

                $dopOptions = "<label for='gorod'>Выберите город: <select style='position: relative; float: right;
width: 173px; height: 20px;'>";

                foreach ($goroda as $gorod){
                    $dopOptions .= "<option name='gorod' value='".$gorod['id']."'>".$gorod['gorod']."<option>";
                }

                $dopOptions .= "</select></label>";
                break;
            
            default:
                $dopOptions = "";
            break;
        }

		break;
    
    case 'add_mass':
        $title = TITLE." - Массовая загрузка";
        
        break;
    
    case 'sended':

        $title = TITLE." - Статус рассылок";

        function get ($id){
            $query = "SELECT sended_sms.delivered, sended_sms.is_error, sended_sms.msg, sended_sms.id_rassilki, sended_sms.msg, sended_sms.id, sended_sms.phone as phone1,
                        users.name, users.otch, users.phone, users.id, users.fam,
                          sended_mass.tema
                              FROM sended_sms
                                Left Join users ON users.phone = sended_sms.phone
                                  LEFT JOIN sended_mass ON sended_mass.id = sended_sms.id_rassilki
                                    WHERE sended_sms.id_rassilki =  $id";
            $db = new data_base();

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

            case "get_ras":

                $result = array();
                
                if (isset($_GET['sub'])){
                    foreach ($_GET as $key => $value)
                        if ($key == "on")
                            $result[] = get($value);
                }else
                    if (isset($_SESSION['last_id']))
                        $result[] = get($_SESSION['last_id']);
                    else
                        redirect($sended);

                break;

            default:

                $query = "SELECT tema, id, msg FROM sended_mass WHERE user_id=".$_SESSION[id];

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

        $fio = array();
        $fio[0] = empty($_POST['name']) ? "" : $_POST['name'];
        $fio[1] = empty($_POST['fam']) ? "" : $_POST['fam'];
        $fio[2] = empty($_POST['otch']) ? "" : $_POST['otch'];

		$us->add_new($fio[0], $fio[1],$fio[2], $_POST['phone'], $_POST['mail'], $gorod, $obl)->echo_result("string");

		break;
    
    case 'send_mass':
        $title = TITLE." - Массовая рассылка";

        switch($do){
            case "send":
                $query = "SELECT phone FROM `users` WHERE phone_send='1' AND gorod='{$us->gorod}'";

                $res = $db->super_query($query)->get_res();

                $phones = array();

                foreach($res as $item){
                    $phones[] = $item['phone'];
                }

                $us->send_mass($_POST['text'], $phones, $_POST['tema'], $_POST['id'], $us->gorod)->echo_res("json");

                break;
            case "get_pass":
                $us->generate_pass($_POST['tema'], $_POST['text'])->echo_result("string");

                break;
            case "attempt":
                $us->check_pass($_POST['id'], $_POST['pass'])->echo_result("string");

                break;
        }

        $res = $us->device_status()->get_result();
        
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
                $devices = $us->device_status()->get_result();
            
                echo json_encode($devices['data']);
            break;
            
            case "upload":
                $us->add_mass();
            break;
            
            case "cancel":
                $update = new update($us);

                $update->update_status();

                $us->cancel_sms()->echo_res("json");

            break;
        }
        exit();
        break;
        
    case 'view_ver':
        $title = TITLE." - Все подтвержденные";
    
        $query = "SELECT * FROM users WHERE phone_send='1' OR email_send='1' AND gorod={$us->gorod}";
        
        $res = $db->super_query($query)->get_res();
        
        break;
    
    case 'exit':
        $us->log_out();
        break;

    case 'settings':
        $title = TITLE." - Изменение данных";

        switch($do)
        {
            case "update":
                $settings = new settings($us);

                $settings->update($_POST['login'], $_POST['token'], $_POST['new_pass'], $_POST['default_dev'], $_POST['devices'])->echo_result("string");

                break;
        }

        break;
}
unset($db, $query, $do, $get);

require_once "view/index.php";