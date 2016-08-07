<?php
namespace liw\Logger;

class LoggerCL extends Logger
{
	static private $_instance;
	static public function getInstance()
	{
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	protected function __construct()
	{
		$this->_db = fopen("php://stdout", 'r+');
	}
	public function logRecord($msg, $dt='')
	{
		fwrite($this->_db, self::datetime($dt).' '.self::clearData($msg)."\n");
	}
	public function printLogbook()
	{
		rewind($this->_db);
		echo stream_get_contents($this->_db);
	}
	public function __destruct()
	{
		fclose($this->_db);
	}
}