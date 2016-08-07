<?php
namespace liw\Logger;

class LoggerPDO extends Logger
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
		try {
			switch (self::$config['db.name']) {
				case 'MySQL':
				$this->_db = new \PDO(self::$config['db.conn'], self::$config['db.mysql.user'], self::$config['db.mysql.password']);
				$this->_db->exec(self::$config['db.request.create.table']);
				break;
				case 'SQLite':
				$this->_db = new \PDO(self::$config['db.conn']);
				$this->_db->exec(self::$config['db.request.create.table']);
				break;
				// case 'имя_PDO_драйвера':
				// $this->_db = new \PDO([Особенность подключения к базе]]);
				// $this->_db->exec([Дополнительные запросы создания базы, таблицы и т.д.]);
				// break;
				default:
				throw new \Exception("Error database.");
				break;
			}
		} catch (\Exception $e) {
			die('Error: database!');
		}
	}
	public function logRecord($msg, $dt = '')
	{
		$msg = $this->_db->quote(self::clearData($msg));
		$dt = self::datetime($dt);
		$sql = "INSERT INTO logbook (date, msg) VALUES ('".$dt."', ".$msg.")";
		$this->_db->exec($sql);
	}
	public function printLogbook()
	{
		$sql = "SELECT * FROM logbook ORDER BY id DESC";
		$result = $this->_db->query($sql);
		if($result) {
			while ($log = $result->fetch(\PDO::FETCH_ASSOC)) {
				echo $log['date'].' '.$log['msg'].'<br>';
			}
		}
	}
}
