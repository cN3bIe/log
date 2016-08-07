<?php
namespace liw\Logger;

abstract class Logger
{
    protected $db;
    public static $config;
    abstract public function logRecord($msg, $dt);
    abstract public function printLogbook();
    protected function __clone(){}
    public static function getConfig()
    {
        if(empty(self::$config)) {
            self::$config = parse_ini_file(__DIR__.DIRECTORY_SEPARATOR.'config.ini');
        }
    }
    protected function datetime($dt = '')
    {
        if(empty($dt)) {
            $dt = time();
        }
        return date(self::$config['system']['time.format'], $dt);
    }
    protected function clearData($msg='')
    {
        $msg = trim($msg);
        if (empty($msg)) {
            $msg = '[Передана пустая строка.]';
        }
        return $msg;
    }
}
