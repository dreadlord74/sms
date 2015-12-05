<?php

defined("SCRIPT") or die ("Сюда нельзя!");

class user_obl extends user implements _obl{    
    public function add_on_obl(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = ""){
        $this->add_new($name, $fam, $otch, $phone, $mail, $date, $gorod);
    }
}