<?php

defined ("SCRIPT") or die ("Сюда нельзя!");
/**
 * Класс для работы с базой данных
 * @author Данил
 * @date 07.07.15
 */
class data_base extends vivod
{
    
    private $q_id = null;
    
    private $db = false;

    /**
     * Конструктор класса
     * Устанавливает соединение с базой данных
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    function __construct($host=HOST, $user=USER, $pass=PASS) {
        $this->db = mysqli_connect($host, $user, $pass);
        
        if (!$this->db){
            $this->display_error(mysqli_error($this->db), mysqli_errno($this->db));
            
        }else{
            mysqli_select_db($this->db, DB);
            mysqli_query($this->db, "SET NAMES utf8");
        }
    }
    
    /**
     * Деструктор класса
     * Закрывает соединение с БД
     */
    function __destruct(){
        mysqli_close($this->db);
        foreach ($this as $item)
            unset($item);
    }
    /**
    * Установить результат
    * @param $res - результат работы метода
    * @return $this
    */
    private function set_res($res){
        $this->result = $res;
        return $this;
    }
    
    private function set_id($id){
        $this->q_id = $id;
        return $this;
    }
    
    public function get_id(){
        return $this->q_id;
    }
    
    public function get_res(){
        return $this->result;
    }
    
    public function get_last_id(){
        return mysqli_insert_id($this->db);
    }
    
    public function get_id_db(){
        return $this->db;
    }

    public function count_rows($query){
        $this->query($query)->set_res(mysqli_num_rows($this->q_id));
        return $this;
    }

    /**
     * Метод для записи логов в БД
     * @param $action_id - id действия, которое совершил пользователь
     * @param $opisanie - описание действия
     */
    public function write_log($action_id, $opisanie = ""){
        $query = "INSERT INTO history (user_id, action_id, date_time, opisanie) VALUES ('{$_SESSION['id']}', '$action_id', '".date("Y-m-d H:i:s")."', '$opisanie')";
        $this->db->query($query);
    }

	/**
  * Запрос в БД 
  * @param string $query - запрос
  * @return $this
  */
	public function query($query)
    {
		$this->set_id(mysqli_query($this->db, $query));
        if ($this->get_id())
            $this->set_res($this->get_id());
        else
            $this->display_error(mysqli_error($this->db), mysqli_errno($this->db), $query);

        return $this;
	}
    /**
    * Запрос в БД, который задает результат
    * @param string $query - запрос
    * @param bool $multi - устанавливает нужно ли вернуть всего одну строку из БД (false - да, True - нет)
    * @return $this
    */
    public function super_query($query, $multi=true){
        if($multi){
            $this->query($query);
            if ($this->get_id()){
                $this->result = array();
                while ($row = mysqli_fetch_assoc($this->q_id)){
                    $this->result[] = $row;
                }
            }else{
                $this->display_error(mysqli_error($this->db), mysqli_errno($this->db), $query);
            }
        }else{
            $this->query($query);
            $this->result = mysqli_fetch_assoc($this->get_id());
        }
        return $this;
    }

    /**
     * @param $error
     * @param $error_num
     * @param string $query
     */
    private function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
		}

		$query = htmlspecialchars($query, ENT_QUOTES, 'ISO-8859-1');
		$error = htmlspecialchars($error, ENT_QUOTES, 'ISO-8859-1');

		$trace = debug_backtrace();

		$level = 0;
		if ($trace[1]['function'] == "query" ) $level = 1;
		if ($trace[2]['function'] == "super_query" ) $level = 2;

		$trace[$level]['file'] = str_replace(PATH, "", $trace[$level]['file']);

		echo <<<HTML
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MySQL Fatal Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<style type="text/css">
<!--
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-style: normal;
	color: #000000;
}
.top {
  color: #ffffff;
  font-size: 15px;
  font-weight: bold;
  padding-left: 20px;
  padding-top: 10px;
  padding-bottom: 10px;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
  background-color: #AB2B2D;
  background-image: -moz-linear-gradient(top, #CC3C3F, #982628);
  background-image: -ms-linear-gradient(top, #CC3C3F, #982628);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#CC3C3F), to(#982628));
  background-image: -webkit-linear-gradient(top, #CC3C3F, #982628);
  background-image: -o-linear-gradient(top, #CC3C3F, #982628);
  background-image: linear-gradient(top, #CC3C3F, #982628);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#CC3C3F', endColorstr='#982628',GradientType=0 ); 
  background-repeat: repeat-x;
  border-bottom: 1px solid #ffffff;
}
.box {
	margin: 10px;
	padding: 4px;
	background-color: #EFEDED;
	border: 1px solid #DEDCDC;

}
-->
</style>
</head>
<body>
	<div style="width: 700px;margin: 20px; border: 1px solid #D9D9D9; background-color: #F1EFEF; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; -moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); -webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3); box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);" >
		<div class="top" >MySQL Error!</div>
		<div class="box" ><b>MySQL error</b> in file: <b>{$trace[$level]['file']}</b> at line <b>{$trace[$level]['line']}</b></div>
		<div class="box" >Error Number: <b>{$error_num}</b></div>
		<div class="box" >The Error returned was:<br /> <b>{$error}</b></div>
		<div class="box" ><b>SQL query:</b><br /><br />{$query}</div>
		</div>		
</body>
</html>
HTML;
		
		exit();
	}
}