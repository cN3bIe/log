<?php
namespace liw\Logger;

abstract class Logger
{
	protected $_db;
	static public $config;
	static public $pathDIR;
	abstract public function logRecord($msg, $dt);
	abstract public function printLogbook();
	protected function __clone(){}
	static public function getConfig()
	{
		if(empty(self::$config)) {
			self::$config = parse_ini_file(__DIR__.DIRECTORY_SEPARATOR.'config.ini');

			// die(var_dump(self::$config));
		}
	}
	private function myforeach($array, $callback)
	{
		foreach ($array as $key => $value) {
			if(is_array($array[$key])) {
				self::myforeach($array[$key], $callback);
			} else {

			}
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
