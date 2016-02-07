<?php

interface _devices {
    function get_devices();
    function device_status();
}

interface _sms {
    function send_sms(&$msg, &$phone);
    function send_mass(&$msg, &$phones, &$tema, &$id, &$gorod);
    function get_in_sms();
    function get_out_sms(&$ids);
    function cancel_sms();
    function generate_pass($tema, $msg);
    function check_pass($id, $pass);
}

interface _user {
    function add_new(&$name = "", &$fam = "", &$otch = "", &$phone, &$mail = "", &$gorod = "", &$obl = "");
    function add_mass();
    function mod_settings(&$setting, &$mod);
}

interface _obl {
    function add_on_obl(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "");
}

interface _central {
    function add_on_contry(&$name, &$fam, &$otch, &$phone, &$mail, &$date, &$gorod = "", &$obl = "");
}