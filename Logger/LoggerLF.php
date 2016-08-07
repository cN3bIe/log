<?php
namespace liw\Logger;

class LoggerLF extends Logger
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
        $this->db = fopen(self::$config['db.logfile.name'], 'a+');
    }
    public function logRecord($msg, $dt='')
    {
        fputs($this->db, self::datetime($dt).' '.self::clearData($msg)."\n");
    }
    public function printLogbook()
    {
        $array = array_reverse(file(self::$config['db.logfile.name']));
        foreach ($array as $log) {
            echo $log.'<br>';
        }
    }
    public function __destruct()
    {
        fclose($this->db);
    }
}
