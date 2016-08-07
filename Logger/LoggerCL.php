<?php
namespace liw\Logger;

class LoggerCL extends Logger
{
    private static $_instance;
    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    protected function __construct()
    {
        $this->db = fopen("php://stdout", 'r+');
    }
    public function logRecord($msg, $dt='')
    {
        fwrite($this->db, self::datetime($dt).' '.self::clearData($msg)."\n");
    }
    public function printLogbook()
    {
        rewind($this->db);
        echo stream_get_contents($this->db);
    }
    public function __destruct()
    {
        fclose($this->db);
    }
}
