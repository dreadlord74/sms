<?php

defined("SCRIPT") or die ("Сюда нельзя!");

class user_central extends user implements _central, _obl {
    function add_on_contry(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = '', &$obl = ''){
        $this->add_new($name, $fam, $otch, $phone, $mail, $date, $gorod, $obl);
    }
    
    function add_on_obl(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = ''){
        $this->add_new($name, $fam, $otch, $phone, $mail, $date, $gorod);
    }
}