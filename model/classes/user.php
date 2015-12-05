<?php
defined ("SCRIPT") or die ("Сюда нельзя!");
    /**
     * Класс для работы с пользователем
     * @date 18.08.15
     * @author Данил Хандысь
     */
class user extends vivod implements _user {
    /**
     * объект Класса data_base
     */
    protected $db = null;
    /**
     * объект Класса sms
     */
    protected $sms = null;
    /**
     * объект Класса devices
     */
    protected $devices = false;
    
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
    protected $gorod;
    
    /**
     * id области пользователя
     */
    protected $obl;
    
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
     */
    private function set_prava($prava){
        $this->prava = $prava;
        $_SESSION['access'] = $prava;
        return $this;
    }
    
    public function get_dev_obj(){
        return $this->devices;
    }
    
    public function get_sms_obj(){
        return $this->sms;
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
    
    private function devices_ini(){
        $this->devices = new devices();
        return $this;
    }
    
    private function sms_ini(&$phone){
        $this->sms = new sms($this->devices, $phone);
        return $this;
    }
    
    function __destruct(){
        foreach ($this as $item)
            unset($item);
        unset($this->sms, $this->devices);
    }
    
    public function __construct ($login, $pass = "", $id = false){
        $this->db = new data_base;
        
        if ($id){
            $query = "SELECT admin.id, admin.login, admin.devices, admin.phone, admin.token, admin.default_dev, settings.id AS settings, settings.confirm_reg, settings.cofirm_msg, obl.id AS obl, goroda.id AS gorod, prava.id AS prava FROM admin
                            INNER JOIN obl ON obl.id = admin.obl
                                INNER JOIN goroda ON goroda.id = admin.gorod
                                    INNER JOIN prava ON prava.id = admin.prava
                                        INNER JOIN settings ON settings.id = admin.settings_id
                            WHERE admin.login='$login' AND admin.id='$id' AND admin.is_activ='1'";
                            
            $res = $this->db->super_query($query, false)->get_res() or $res = false;
            
            if ($res){
                $this->set_login($login)->set_id($res['id'])->set_prava($res['prava'])->set_gorod($res['gorod'])->set_obl($res['obl'])->set_conf_reg($res['confirm_reg'])->set_conf_msg($res['cofirm_msg'])->set_settings($res['settings']);
                $_SESSION['token'] = $res['token'];

                $devices = explode(",", $res['devices']);
                
                $i = 0;
                foreach ($devices as $item){
                     $_SESSION['devices'][$i] = $item;
                    $i++;
                }
               
                $_SESSION['count_dev'] = count($devices);
                
                $_SESSION['default'] = $res['default_dev'];

                $this->devices_ini()->sms_ini($res['phone']);
             }    
        }else{
            $pass = md5($pass);
            $_SESSION['added'] = "";
            $query = "SELECT admin.id, login, prava.id AS prava FROM admin 
                            INNER JOIN prava ON prava.id = admin.prava                             
                            WHERE login='$login' AND pass='$pass' AND is_activ='1'";
                            
            $res = $this->db->super_query($query, false)->get_res() or $res = false;
            
            if ($res){
                $_SESSION['login'] = $res['login'];
                $_SESSION['id'] = $res['id'];
                $_SESSION['access'] = $res['prava'];
                $this->db->write_log(1);
            }
        }
    }

    /**
     * Функция для выхода пользователя
     */
    public function log_out(){
        $this->db->write_log(5);
        session_unset();
        session_destroy();
        unset($this);
        redirect();
    }

	public function add_new(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "", &$obl = ""){
	   
		$ret = $fam." ".$name." ".$otch;
        
        $conf_reg = ((int)$this->get_conf_reg() == 0) ? 1 : 0;

        $query = "INSERT INTO users (fam, name, otch, phone, mail, date, phone_ver, obl, gorod)
							  VALUES ('$fam', '$name', '$otch', '$phone', '$mail', '$date', '$conf_reg', ";
		
        switch ($this->get_prava()){
            case 4:
                //добавление в рамках города
                $query .= "$this->obl, $this->gorod)";
            break;
            
            case 3:
                //В рамках области
                $query .= empty($gorod) ? "$this->obl, $this->gorod)" : "$this->obl, $gorod)";
                
            break;
            
            case 2:
                //добавление по всей стране
                $query .= empty($obl) ? "$this->obl, " : "$obl, ";
                
                $query .= empty($gorod) ? "$this->gorod)": "$gorod)";
            break;
        }

        if ($this->db->count_rows("SELECT id FROM goroda WHERE obl={$this->obl} AND gorod='$gorod'")){

        }

        $this->db->query($query) or $ret = false;

        if ($ret){
            
            $_SESSION['count']++;
            $_SESSION['added'] .= "<p>".$ret."</p>";
            
                if (!$conf_reg){
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

                    $xd = $this->sms->send_sms($msg, $phone);
                    
                    if ($xd == 0){
                        $query = "UPDATE users SET date_send_ver='".date("Y-m-d")."' WHERE phone='$phone'";
                            
                        $this->db->query($query);
                    }
                }
        }
        
        $this->result = $ret;

        $this->db->write_log(3, $this->db->get_last_id());

        return $this;
	}
}