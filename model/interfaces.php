<?php

interface _devices {
    function get_devices();
}

interface _sms {
    function send_sms(&$msg, &$phone);
    function send_mass(&$msg, &$phones, &$tema, &$id);
    function get_in_sms();
    function get_out_sms(&$ids);
    function cancel_sms();
}

interface _user {
    function add_new(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "", &$obl = "");
    function add_mass();
    function mod_settings(&$setting, &$mod);
}

interface _obl {
    function add_on_obl(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "");
}

interface _central {
    function add_on_contry(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "", &$obl = "");
}