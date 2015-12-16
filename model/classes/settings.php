<?php

/**
 * Created by PhpStorm.
 * User: Данил
 * Date: 05.12.2015
 * Time: 21:56
 */
class settings extends vivod
{
    /**
     * Объект пользователя
     * @var
     */
    private $us;

    private $db;

    /**
     * Конструктор класса
     * задает объект пользователя
     * @param User $us
     */
    function __construct(User $us)
    {
        $this->us = $us;
        $this->db = new data_base();
    }

    /**
     * Функция изменения данных пользователя
     * ПОКА НЕ ДОДЕЛАНА
     * @param $login
     * @param $token
     * @param $new_pass
     * @param $def_dev
     * @param $devices
     * @return $this
     */
    public function update(&$login, &$token, &$new_pass, &$def_dev, &$devices)
    {
        $query = "UPDATE admin SET login='$login', token='$token', default_dev='$def_dev', devices='$devices' WHERE id=".$this->us->id;

        $this->result = $this->db->query($query)->get_res() or die("Не вышло изменить настройки!");

        return $this;
    }
}