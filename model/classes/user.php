<?php
defined ("SCRIPT") or die ("Сюда нельзя!");
    /**
     * Класс для работы с пользователем
     * @date 18.08.15
     * @author Данил Хандысь
     */
class user extends vivod implements _user, _devices, _sms
{
    /**
     * объект Класса data_base
     */
    protected $db;
    /**
     * объект Класса sms
     */
    protected $sms;
    /**
     * объект Класса devices
     */
    protected $devices;
    
    /**
     * id настроект пользователя
     */
    protected $settings_id;
    /**
     * Логин пользователя
     */
    protected $login;
    
    /**
     * id пользователя
     */
    public $id;
    
    /**
     * Права пользователя
     * 1 - admin
     * 2 - центральный штаб
     * 3 - область
     * 4 - город
     */
    protected $prava;
    
    /**
     * id города пользователя
     */
    public $gorod;
    
    /**
     * id области пользователя
     */
    public $obl;

    /**
     * @var $phone - номер телефона пользователя
     */
    private $phone;
    
    /**
     * нужно ли подтверждение телефона учатника
     */
    protected $confirm_reg;
    
    /**
     * Сообщение для подтверждения телефона
     */
    protected $confirm_msg;
    
    /**
     * Метод для установки id настроек пользователя
     * @param $id - id настроект
     * @return $this - объект класса user
     */
    protected function set_settings(&$id){
        $this->settings_id = $id;
        return $this;
    }
    
    /**
     * Метод для установки логина
     * @param $login - логин пользователя
     * @return $this - объект класса user
     */
    protected function set_login($login){
        $this->login = $login;
        return $this;
    }
    
    /**
     * Метод для установки id пользователя
     * @param $id - id пользователя
     * @return $this - объект класса user
     */
    protected function set_id($id){
        $this->id = (int)$id;
        return $this;
    }

    /**
     * Метод для установки прав пользователя
     * @param
     * @return $this
     */
    private function set_prava($prava){
        $this->prava = $prava;
        $_SESSION['access'] = $prava;
        return $this;
    }
    
    private function set_gorod($gorod){
        $this->gorod = $gorod;
        return $this;
    }
    
    private function set_obl($obl){
        $this->obl = $obl;
        return $this;
    }
    
    public function set_conf_reg($param){
        $this->confirm_reg = $param;
        return $this;
    }
    
    public function set_conf_msg($msg){
        $this->confirm_msg = $msg;
        return $this;
    }
    
    public function get_conf_reg(){
        return $this->confirm_reg;
    }
    
    public function get_conf_msg(){
        return $this->confirm_msg;
    }
    
    public function get_settings(){
        return $this->settings_id;
    }
    
    public function get_prava(){
        return $this->prava;
    }

    public function add_mass(){
        $f = fopen($_FILES['file']['tmp_name'], "r");
        
        $i = 0; $array2 = array();
        
        while (!feof($f)){
            $res = fgets($f);
            $array = explode(" ", $res);

            foreach($array as $key => $item){
                $array[$key] = clear($item);
            }
            
            $array2[$i]['fam'] = $array[0];
            $array2[$i]['name'] = $array[1];
            $array2[$i]['otch'] = $array[2];
            $array2[$i]['phone'] = $array[3];
            $array2[$i]['mail'] = $array[4];
            $array2[$i]['date'] = $array[5];
            $array2[$i]['obl'] = $array[6];
            $array2[$i]['gorod'] = $array[7];
            $i++;
        }
        fclose($f);
        
        echo json_encode($array2);
       
    }

    /**
     * Метод для изменения настроек пользователя
     * @param $setting
     * @param $mod
     */
    public function mod_settings(&$setting, &$mod){

        $res = true;

        switch ($setting){
            case "confirm_reg":
                $mod = ($mod == "true") ? 1: 0;
                $query = "UPDATE settings SET confirm_reg='$mod' WHERE id={$this->settings_id}";
                if ($mod == 1)
                    $opisanie = "Нужно подтверждать телефон";
                else
                    $opisanie = "Не нужно подтверждать телефон";
            break;
            
            case "confirm_msg":
                $query = "UPDATE settings SET cofirm_msg='$mod' WHERE id={$this->settings_id}";
                $opisanie = "Изменение собщения для подтверждения телефона";
            break;
        }
        $this->db->query($query) or $res = false;
        
        if ($res){
            $this->db->write_log(2, $opisanie);
            echo 1;
        }else{
            echo 0;
        }
        exit();
    }

    /**
     * Метод для инициализации объет девайсов
     * @return $this
     */
    private function devices_ini()
    {
        $this->devices = new devices();
        return $this->devices;
    }

    /**
     * Метод для иницилизации объекта смс
     * @return $this
     */
    private function sms_ini()
    {
        $this->devices_ini();
        $this->sms = new sms($this->devices, $this->phone);
        return $this->sms;
    }
    
    function __construct ($login, $pass = "", $id = false){
        $this->db = new data_base;

        $settings = $this->db->super_query("SELECT * FROM system_settings", false)->get_res();

        if ($id){
            $query = "SELECT admin.id, admin.login, admin.phone, settings.id AS settings, settings.confirm_reg, settings.cofirm_msg, obl.id AS obl, goroda.id AS gorod, prava.id AS prava FROM admin
                            INNER JOIN obl ON obl.id = admin.obl
                                INNER JOIN goroda ON goroda.id = admin.gorod
                                    INNER JOIN prava ON prava.id = admin.prava
                                        INNER JOIN settings ON settings.id = admin.settings_id
                            WHERE admin.login='$login' AND admin.id='$id' AND admin.is_activ='1'";
                            
            $res = $this->db->super_query($query, false)->get_res() or $res = false;
            
            if ($res){
                $this->set_login($login)->set_id($res['id'])->set_prava($res['prava'])->set_gorod($res['gorod'])->set_obl($res['obl'])->set_conf_reg($res['confirm_reg'])->set_conf_msg($res['cofirm_msg'])->set_settings($res['settings']);

                /*$devices = explode(",", $res['devices']);
                
                $i = 0;
                foreach ($devices as $item){
                     $_SESSION['devices'][$i] = $item;
                    $i++;
                }
               
                $_SESSION['count_dev'] = count($devices);
                
                $_SESSION['default'] = $res['default_dev'];*/

                $this->phone = $res['phone'];
             }    
        }else{
            $pass = md5($pass);
            $_SESSION['added'] = "";
            $query = "SELECT admin.id, login, prava.id AS prava, auth_pass, phone FROM admin
                            INNER JOIN prava ON prava.id = admin.prava                             
                            WHERE login='$login' AND pass='$pass' AND is_activ='1'";
                            
            $res = $this->db->super_query($query, false)->get_res() or $res = false;

            $count_rows = $this->db->rows;

            //глобальная переменная, определяющая успешность авторизации
            global $auth;
            if ($count_rows == 1){

                if ($res[auth_pass] == 0)
                {
                    $auth = "pass";

                    $password = gen_pass();

                    $this->send_sms(str_replace("[пароль]", $password, $settings[auth_masg]), $res[phone]);


                    $this->db->query("UPDATE admin SET auth_pass=".$password." WHERE id=".$res[id]);
                    //return false;
                }
                else if ($res[auth_pass] == $_POST[password])
                {
                    $_SESSION['login'] = $res['login'];
                    $_SESSION['id'] = $res['id'];
                    $_SESSION['access'] = $res['prava'];
                    $this->db->write_log(1, "Вход! IP: ".$_SERVER[REMOTE_ADDR]."; ".$_SERVER[HTTP_USER_AGENT]);
                    $this->db->query("UPDATE admin SET auth_pass=0 WHERE id=".$res[id]);
                    $auth = "true";
                }
            }else if ($count_rows == 0){
                $query = "SELECT login, id FROM admin WHERE login='$login'";

                $res = $this->db->super_query($query, false)->get_res();

                $this->db->write_log(1, "Неудачная попытка входа в учетную запись ".$res[login]."! IP: ".$_SERVER[REMOTE_ADDR]."; ".$_SERVER[HTTP_USER_AGENT], $res[id]);
                $auth = "false";
            }
        }
    }

    /**
     * Метод для получения исходящих смс
     * @param $ids
     * @return sms
     */
    public function get_out_sms(&$ids)
    {
        return $this->sms_ini()->get_out_sms($ids);
    }

    /**
     * Метод для получения входящих смс
     * @return sms
     */
    public function get_in_sms()
    {
        return $this->sms_ini()->get_in_sms();
    }

    /**
     * Метод для получения списка девайсов
     * @return devices
     */
    public function get_devices()
    {
        return $this->devices_ini()->get_devices();
    }

    /**
     * Метод для смс-рассылки
     * @param $msg
     * @param $phones
     * @param $tema
     * @param $id
     * @param $gorod
     * @return sms
     */
    public function send_mass (&$msg, &$phones, &$tema, &$id, &$gorod)
    {
        return $this->sms_ini()->send_mass($msg, $phones, $tema, $id, $gorod);
    }

    /**
     * @param $msg
     * @param $phone
     * @return sms
     */
    public function send_sms(&$msg, &$phone)
    {
        return $this->sms_ini()->send_sms($msg, $phone);
    }

    /**
     * Метод для проверки статуса девайсов
     * @return devices
     */
    public function device_status ()
    {
        return $this->devices_ini()->device_status();
    }

    /**
     * Метод для отмены отправки смс
     * @return sms
     */
    public function cancel_sms()
    {
        return $this->sms_ini()->cancel_sms();
    }

    /**
     * Метод для проверки введенного пароля, для подтверждения рассылки
     * @param $id
     * @param $pass
     * @return sms
     */
    public function check_pass($id, $pass)
    {
        return $this->sms_ini()->check_pass($id, $pass);
    }

    /**
     * Метод для генерации пароля для подтверждения расслки
     * @param $tema
     * @param $msg
     * @return sms
     */
    public function generate_pass($tema, $msg)
    {
        return $this->sms_ini()->generate_pass($tema, $msg);
    }
    /**
     * Функция для выхода пользователя
     */
    public function log_out(){
        $this->db->write_log(5);
        session_unset();
        session_destroy();
        redirect();
    }


    /**
     * Метод для добавление контактов в список рассылки
     * @param $name
     * @param $fam
     * @param $otch
     * @param $phone
     * @param $mail
     * @param $date
     * @param string $gorod
     * @param string $obl
     * @return $this
     */
	public function add_new(&$name = "", &$fam = "", &$otch = "", &$phone, &$mail = "", &$gorod = "", &$obl = "")
    {
        $ret = $fam." ".$name." ".$otch;
        phoneReplace($phone);

        //Если контакт с таким номером уже есть, то он не будет добавлен
        if ($this->db->count_rows("SELECT id FROM users WHERE phone='$phone'")->get_res())
        {
            $this->result = $ret;
            return $this;
        }

        //$conf_reg = ((int)$this->get_conf_reg() == 0) ? 1 : 0;

        $query = "INSERT INTO users (fam, name, otch, phone, mail, date, obl, gorod)
							  VALUES ('$fam', '$name', '$otch', '$phone', '$mail', '".date("Y-m-d")."', ";
		
        switch ($this->get_prava()){
            case 4:
                //добавление в рамках города
                $query .= "{$this->obl}, {$this->gorod})";
            break;
            
            case 3:
                //В рамках области
                if ($gorod == "")
                    $query .= $this->obl.", ".$this->gorod.")";
                else
                {
                    //Следующий код добавит город в текущую область, если его нет в базе
                    if (!$this->db->count_rows("SELECT id FROM goroda WHERE obl='{$this->obl}' AND gorod='$gorod'")->get_res())
                    {
                        $this->db->query("INSERT INTO goroda (gorod, obl) VALUES ('$gorod', '{$this->obl}')");
                        $gorod_id = $this->db->get_last_id();
                    }
                    else
                        $gorod_id = $this->db->super_query("SELECT id FROM goroda WHERE gorod='$gorod'")->get_res();

                    $query .= $this->obl.", ".$gorod_id.")";
                }
            break;
            
            case 2:
                //добавление по всей стране

                if ($obl == "")
                    $query .= $this->obl.", ";
                else
                {   //Добавит область, если её нет
                    if (!$this->db->count_rows("SELECT id FROM obl WHERE obl='$obl'")->get_res())
                    {
                        $this->db->query("INSERT INTO obl (obl) VALUES ('$obl')");
                        $obl_id = $this->db->get_last_id();
                    }
                    else
                        $obl_id = $this->db->super_query("SELECT id FROM obl WHERE obl='$obl'")->get_res();

                    $query .= $obl_id.", ";
                }

                if ($gorod == "")
                    $query .= $this->gorod.")";
                else
                {
                    //Следующий код добавит город в текущую область, если его нет в базе
                    if (!$this->db->count_rows("SELECT id FROM goroda WHERE obl='{$this->obl}' AND gorod='$gorod'")->get_res())
                    {
                        $this->db->query("INSERT INTO goroda (gorod, obl) VALUES ('$gorod', '{$this->obl}')");
                        $gorod_id = $this->db->get_last_id();
                    }
                    else
                        $gorod_id = $this->db->super_query("SELECT id FROM goroda WHERE gorod='$gorod'")->get_res();

                    $query .= $gorod_id.")";
                }
            break;
        }

        $this->db->query($query) or $ret = false;

        /*Этот код отвечает за проверку номеров. Удалять жалко
        if ($ret){
            
            $_SESSION['count']++;
            $_SESSION['added'] .= "<p>".$ret."</p>";
            
                if (!$conf_reg)
                {
                    $month = array (
                        '01' => "января",
                        '02' => "февраля",
                        '03' => "марта",
                        '04' => "апреля",
                        '05' => "мая",
                        '06' => "июня",
                        '07' => "июля",
                        '08' => "августа",
                        '09' => "сентября",
                        '10' => "октября",
                        '11' => "ноября",
                        '12' => "декабря"
                    );
                    
                    $date_arr = explode("-", $date);
                    
                    $date_arr[2] = (string)$date_arr[2];
                    
                    $date = $date_arr[2]." ".$month[$date_arr[1]]." ".$date_arr[0]." года";
                    
                    unset($month);
                    
                    $msg = $this->get_conf_msg();
                    
                    $msg = str_replace("[дата]", $date, $msg);

                    $xd = $this->send_sms($msg, $phone)->get_result('code');
                    
                    if ($xd == 0){
                        $query = "UPDATE users SET date_send_ver='".date("Y-m-d")."' WHERE phone='$phone'";
                            
                        $this->db->query($query);
                    }
                }
        }
        */


        $this->result = $ret;

        $this->db->write_log(3, $this->db->get_last_id());

        return $this;
	}
}