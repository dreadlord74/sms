<?php

/**
 * Created by PhpStorm.
 * User: Данил
 * Date: 05.12.2015
 * Time: 21:56
 */
class settings
{
    private $us;

    function __construct(User $us)
    {
        $this->us = $us;
    }

    public function update(&$login, &$token, &$new_pass, &$def_dev, &$devices)
    {
        $query = "UPDATE admin SET  WHERE id=".user::id;

        if ($login != $_SESSION['login'])
        {

        }
    }
}