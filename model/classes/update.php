<?php

defined ("SCRIPT") or die ("Сюда нельзя!");

/**
 * Класс для обновления информации об смс
 */
class update {
    /**
     * @object объект класса user
     */
    private $us;

    /**
     * @object объект класса data_base
     */
    private $db;

    /**
     * Конструктор класса
     * задает объект классов user и data_base
     * @param user $user - объект класса user
     */
    function __construct(user $user){
        $this->us = $user;
        $this->db = new data_base();
    }

    function __destruct(){
        unset($this->us, $this->db);
    }

    /**
     * Метод для обновления статусов смс
     * устанавливает доставку или ошибку доставки смс
     * @return $this
     */
    function update_status(){
        $query = "SELECT id_sms, device FROM `sended_sms` WHERE delivered='0' AND is_error='0' AND device IN (".implode(',', $this->us->get_dev_obj()->get_devices()).")";

        $res = $this->db->super_query($query)->get_res();

        if (count($res) > 0){
            $ids = array();

            foreach ($res as $item){
                $ids[$item['device']][] = substr($item['id_sms'], -7);///ПО НЕВЕДОМОЙ ПРИЧИНЕ ПЕРЕД АЙДИ СМС СТОИТ ХРЕНОВА ТУЧА НУЛЕЙ
                //ВРЕМЕННЫЙ КОСТЫЛЬ. НЕ ЗАБЫТЬ ПЕРЕДЕЛАТЬ!!1
            }

            $out = $this->us->get_sms_obj()->get_out_sms($ids)->get_result();

            unset($res, $ids);

            $id_deliv = array();
            $id_error = array();

            foreach($this->us->get_dev_obj()->get_devices() as $device){

                foreach($out[$device]['data'] as $value){

                    if ($value['is_delivered'] == 1){
                        $id_deliv[] = $value['id'];

                    }else if (($value['is_error'] == 1) or ($value['is_error_send'] == 1)){
                        $id_error[] = $value['id'];
                    }
                }

                if (count($id_deliv) > 0){
                    $query = "UPDATE `sended_sms` SET delivered='1' WHERE id_sms IN(".implode(',', $id_deliv).") AND device='$device'";

                    $this->db->query($query);
                }

                if (count($id_error) > 0) {
                    $query = "UPDATE `sended_sms` SET is_error='1' WHERE id_sms IN(".implode(',', $id_error).") AND device='$device'";
                    $this->db->query($query);
                }
            }
        }
        return $this;
    }

    /**
     * Метод для установки подтверждения номеров получателей рассылки
     * @return $this
     */
    function update_ver(){
        $in = $this->us->get_sms_obj()->get_in_sms()->get_result();
        print_arr($in);
        $phones = array();
        foreach($this->us->get_dev_obj()->get_devices() as $device){
            if (($in[$device]['code'] == 0) and ($in[$device]['count'] != 0))
                foreach ($in[$device]['data'] as $value){
                    $phone = str_replace("+7", "8", $value['phone']);
                    if ((int)$phone != 0){//является ли номер отправителя числовым
                        $phones[] = $phone;
                    }
                }
            else
                return $this;
            if (empty($phones)) return $this;

            $query = "UPDATE `users` SET phone_ver='1', date_ver='".date("Y-m-d")."' WHERE phone IN(".implode(',', $phones).") AND phone_ver='0'";

            $this->db->query($query);
        }
        return $this;
    }
}