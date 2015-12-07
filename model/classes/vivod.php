<?php
/**
 * Класс для вывода результатов работы методов других объектов
 */
class vivod {
    /**
     * Результат, чем бы он не был
     */
    protected $result;

    /**
     * Метод для вывода результата
     * @param $method - строка "json" или "string"
     */
    function echo_result($method)
    {
        switch ($method)
        {
            case "json":
                echo json_encode($this->result);
                break;
            case "string":
                echo $this->result;
                break;
        }
        exit();
    }
} 