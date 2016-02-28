<?php

defined ("SCRIPT") or die ("Сюда нельзя!");
/**
 * Класс для работы с базой данных
 * @author Хандысь Данил
 * @date 18.08.15
 */

class sms extends device_sms implements _sms{
    /**
    * Объект класса device
    */
    private $device_class;

    /**
     * @object data_base
     */
    private $db;

    private $phone;
    
    /**
    * Конструктор класса
    * @param $devices - id устройств
     * @param $phone - телефон пользователя
    * @param $token - токен для доступа к аккаунту
    */

    function __construct (devices $devices, $phone, $token = ""){
    	$this->set_token($token)->device_class = $devices;
        $this->phone = $phone;
        $this->db = new data_base();
    }

    /**
     * Метод для проверки введенного пароля, для подтверждения рассылки
     * @param $id
     * @param $pass
     * @return sms
     */
    public function check_pass($id, $pass)
    {
        $query = "SELECT pass FROM want_to_send WHERE id=$id";

        $res = $this->db->super_query($query, false)->get_res() or false;

        if ($res['pass'] == $pass)
        {
            $this->set_result($id);
            $query = "UPDATE want_to_send SET can='1' WHERE id=$id";
            $this->db->query($query);
        }else
            $this->set_result(false);

        return $this;
    }

    /**
     * Метод для генерации пароля для подтверждения рассылки
     * @param $tema
     * @param $msg
     * @return sms
     */
    public function generate_pass($tema, $msg)
    {
        $this->db->write_log(6, $tema."; ".$msg);

        $this->set_result(true);

        //генерация пароля
        $rand = gen_pass();

        $query = "INSERT INTO want_to_send (pass, user_id, tema, msg)
                            VALUES ('$rand', '{$_SESSION['id']}', '$tema', '$msg')";

        $this->db->query($query) or $this->set_result(false);

        if ($this->get_result())
        {
            $msg = "Немедленно сообщите администратору, если вы не совершали рассылку! Пароль для рассылки: $rand. Тема рассылки: ".$tema.". Сообщение: ".$msg;
            $result = $this->send_sms($msg, $this->phone)->get_result();
            if ($result['code'] == 0)
                $this->set_result($this->db->get_last_id());
            else
                $this->set_result(false);
        }

        return $this;
    }
        /**
        * Функция для записи отправленных смс данных в БД
        * @param $id - id sms(возвращается из semysms)
        * @param $msg - сообщение
        * @param $phone - номер телефона
        * @param $device - id устройства
        * @param $id_rassilki - 0, если это одиночное сообщение или id рассылки 
        */
    private function to_database($id, $msg, $phone, $device, $id_rassilki = 0){
        
        $today = date("Y-m-d");
        
        $query = "INSERT INTO sended_sms (id_sms, msg, phone, date, device, id_rassilki)
                                VALUES ('$id', '$msg', '$phone', '$today', '$device', '$id_rassilki')";
                                
        unset($today);
        
        $db = new data_base();
        
        $db->query($query);
    }
    
    /**
    * Метод отправки одного сообщения
    * @param $msg - сообщение 
    * @param $phone - номер телефона<br />
    * @return $this
    */
    public function send_sms (&$msg, &$phone){
    
    	 $url = "https://semysms.net/api/3/sms.php?"; //Адрес url для отправки СМС
         
         $data = array(
    	        "phone" => $phone,
    	        "msg" => $msg,
    	        "device" => $this->device_class->get_default(),
    	        "token" => $this->get_token(),
                "priority" => 10
    	 	);
        $data = http_build_query($data);

        $result = file_get_contents($url.$data,FALSE);
                
        unset($url, $data);
             
        $result = json_decode($result, TRUE);
        if ($result['code'] == 0)
            $this->to_database($result['id'], $msg, $phone, $this->device_class->get_default());     
             
        $this->set_result($result);
        return $this;
    }

    /**
     * Метод для массовой рассылки сообщений
     * @date 18.08.15
     * @param $msg - сообщение
     * @param $phones - массив телефонов
     * @param $tema - тема сообщения
     * @param $id
     * @return $this $result['code'] - вернет 0 при успехе, -1 при ошибке
     */
    
    public function send_mass(&$msg, &$phones, &$tema, &$id, &$gorod){

        $query = "SELECT can FROM want_to_send WHERE id=$id";

        $res = $this->db->super_query($query, false)->get_res() or false;

        if (!$res['can'] == '1'){
            $this->set_result(false);
            return $this;
        }

        $query = "DELETE FROM want_to_send WHERE id=$id";

        $this->db->query($query);

    	 $url = "https://semysms.net/api/3/sms_more.php"; //Адрес url для отправки СМС 
    
    	 $params = array('token'  => $this->get_token());
         
                 $query = "INSERT INTO sended_mass (phones, date, tema, msg, gorod, user_id)
                                       VALUES ('".implode(',', $phones)."', '". date("Y-m-d") ."', '$tema', '$msg', $gorod, {$_SESSION['id']})";
                 
                 $db = new data_base();
                 
                 $res = $db->query($query);
                 
                 $id = $db->get_last_id(); // id рассылки

                 $_SESSION['last_id'] = $id;
                 
                 unset($db, $res, $query);
                    
                    $sms_on_dev = (int)(count($phones) / count($this->device_class->get_devices()));
                    
                    $last = 0; $inc = 0; $resultat = array();

                    foreach ($this->device_class->get_devices() as $device){

                        $inc += $last;

                        while ($last < $sms_on_dev+$inc){
                            $params['data'][] = array(
                                'my_id' => $id,
                                'device' => $device,
                                'phone' => $phones[$last],
                                'msg' => $msg,
                                'priority' => 1
                            );
                            $last++;
                        }

                        $last += $sms_on_dev;

                        $params = json_encode($params);
                    
                    	 $curl = curl_init($url);
                    	 curl_setopt($curl, CURLOPT_URL, $url); 
                    	 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    	 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    	 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    	 curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    	    'Content-Type: application/json',
                    	    'Content-Length: ' . strlen($params))
                    	  );
                    	 curl_setopt($curl, CURLOPT_POST, true);
                    	 curl_setopt($curl, CURLOPT_POSTFIELDS,  $params);
                    	 $result = curl_exec($curl);
                    	 curl_close($curl);	   
                    	 
                    	 $result = json_decode($result, TRUE);

                         if ($result['code'] == 0){
                            foreach($result['data'] as  $key => $item){
                                    $this->to_database($item['id'], $msg, $phones[$key], $device, $item['my_id']);
                            }
                            $this->db->write_log(4, $tema);
                         }
                         $resultat[$device] = $result['code'];
                    }

                 $this->set_result($resultat);
                 
                 return $this;
    }
    
    /**
     * Метод для получения входящих сообщений
     * @param
     * @return $this
     */
    public function get_in_sms(){

        $result = array();
        foreach($this->device_class->get_devices() as $device){
            //массив, который будет передан с запросом
            $params = array (
                'token' => $this->get_token(),
                'device' => $device
            );

            $url = "https://semysms.net/api/3/inbox_sms.php?";

            $result[$device] = $this->get_sms($params, $url);
        }

        unset($params, $url);
        
        $this->set_result($result);
        
        return $this;
    }
    
    /**
     * Метод для получения исвходящих смс
     * @param $ids - массив с id смс и кодами устройств
     * @return $this
     */
    public function get_out_sms(&$ids)
    {
        $result = array();
        foreach ($ids as $device => $id){
            //массив, который будет передан с запросом
            $params = array (
                'token' => $this->get_token(),
                'device' => $device,
                'list_id' => implode(',', $id)
            );

            $url = "https://semysms.net/api/3/outbox_sms.php?";

            $result[$device] = $this->get_sms($params, $url);
        }

        unset($params, $url);
    
    	$this->set_result($result);

        return $this;
    }
    
    /**
     * Метод для отмены отправки неотправленных на устройства смс
     * @return $this
     */
    public function cancel_sms()
    {
        $res = array();
        foreach ($this->device_class->get_devices() as $device){
            $result = file_get_contents("https://semysms.net/api/3/cancel_sms.php?token=".$this->get_token()."&device=".$device, FALSE);
            $result = json_decode($result, TRUE);
            
            $res[$device] = $result['code'];
        }
        
        $this->set_result($res);
        
        return $this;
    }

    /**
     * Метод для получения входящих/исходящих смс
     * используется в соответствующих методах
     * @param $param - массив с переменными для передачи
     * @param $url - url для запроса
     * @return mixed $result - массив с данными, вернувшийся с semysms
     */
    private function get_sms(&$param, &$url){
    	// преобразуем массив в URL-кодированную строку
    	$vars = http_build_query($param);
    
    	$result = file_get_contents($url.$vars, FALSE); //отправляем запрос
        
        unset($vars);
    
    	return json_decode($result, TRUE);
    }    
}