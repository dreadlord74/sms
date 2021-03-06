<?php

defined ("SCRIPT") or die ("Сюда нельзя!");
/**
 * Класс для работы с устройствами
 * @date 22.10.15
 * @author Dread_lord
 */
class devices extends device_sms implements _devices{
    
    /**
	* id всех устройств
	*
	*/
	private $devices;
    
    /**
	* id устройства по умолчанию
	*
	*/
    private $default_dev = null;

    /**
     * Конструктор класса
     * устанавливает массив с id устройств
     * задает токен для доступа к аккуанту
     * устанавливает устройства по умолчанию
     */
    function __construct(){
		$this->devices = array();

        $query = "SELECT device FROM devices WHERE is_active='1'";

        $db = new data_base();

        $devices = $db->super_query($query)->get_res();

        foreach ($devices as $device)
            $this->devices[] = $device[device];


        $query = "SELECT device FROM devices WHERE is_default='1'";

        $default = $db->super_query($query, false)->get_res();

        $this->set_token()->default_dev = $default[device];
	}
    
    /**
    * получить коды устройств
    * @return $devices[]
    */
    public function get_devices(){
        return $this->devices;
    }

    /**
     * Метод для получения id устройства по умолчанию
     * @return $this->default_dev
     */
    public function get_default(){
        return $this->default_dev;
    }

    /**
     * Метод для получения списка устройств и их свойств
     * @return $this - $this
     */
    public function device_status (){

        $url = "https://semysms.net/api/3/devices.php?token=".$this->get_token()."&list_id=".implode(",", $this->get_devices())."&is_arhive=0";
        
        $result = file_get_contents($url, FALSE);
        
        $result = json_decode($result, TRUE);
        
        $this->set_result($result);
        
        return $this;
    }

}