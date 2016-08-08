<?php
namespace liw\Logger;

abstract class Logger
{
    protected $db;
    public static $config;
    abstract public function logRecord($msg, $dt);
    abstract public function printLogbook();
    protected function __clone(){}
    public static function getConfig($pathINI = __DIR__.DIRECTORY_SEPARATOR.'config.ini')
    {
        if(empty(self::$config)) {
            $r_str = '';
            $str = file_get_contents($pathINI);
            if(preg_match('/^system\[\'path\'\]=\".+\"/im', $str, $match)) {
                $r_str = parse_ini_string($match[0])['system']['path'].DIRECTORY_SEPARATOR;
            }
            self::$config = parse_ini_string(str_replace('[path]', $r_str, $str));
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
