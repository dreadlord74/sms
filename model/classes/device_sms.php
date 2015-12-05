<?php

class device_sms extends vivod{

    /**
     * токен для доступа к аккаунту
     */
    protected $token;

    /**
     * Установить токен
     * @param string
     * @return $this
     */
    protected function set_token($token = ""){
        if ($token == ""){
            $this->token = $_SESSION['token'];
        }else{
            $this->token = $token;
        }
        return $this;
    }

    /**
     * Получить токен
     * @return $token
     */
    protected function get_token(){
        return $this->token;
    }

    /**
     * Задает результат выполнения метода у наследника
     * @param $res - результат, чем бы он нибыл
     * @return $this
     */
    protected function set_result($res){
        $this->result = $res;
        return $this;
    }

    /**
     * Вернут результат выполнения метода у объекта - наследника
     * @return $this->result
     */
    public function get_result(){
        return $this->result;
    }

    /**
     * Вызывает метод родителя, который выведе результат
     * @param string $method - "json" или "string"
     */
    public function echo_res($method){
        $this->echo_result($method);
    }
}