<?php
namespace liw\Logger;

class LogFactory
{
	static public function DB($type='')
	{
		switch ($type) {
			case 'PDO':
			return LoggerPDO::getInstance();
			break;
			case 'CL':
			return LoggerCL::getInstance();
			break;
			default:
			return LoggerLF::getInstance();
			break;
		}
	}
}
