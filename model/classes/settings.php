<?php

/**
 * Created by PhpStorm.
 * User: �����
 * Date: 05.12.2015
 * Time: 21:56
 */
class settings extends vivod
{
    /**
     * ������ ������������
     * @var
     */
    private $us;

    private $db;

    /**
     * ����������� ������
     * ������ ������ ������������
     * @param User $us
     */
    function __construct(User $us)
    {
        $this->us = $us;
        $this->db = new data_base();
    }

    /**
     * ������� ��������� ������ ������������
     * ���� �� ��������
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

        $this->result = $this->db->query($query)->get_res() or die("�� ����� �������� ���������!");

        return $this;
    }
}