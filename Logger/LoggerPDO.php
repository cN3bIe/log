<?php
namespace liw\Logger;

class LoggerPDO extends Logger
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
        try {
            switch (self::$config['db.name']) {
                case 'MySQL':
                    $this->db = new \PDO(self::$config['db.conn'], self::$config['db.mysql.user'], self::$config['db.mysql.password']);
                    $this->db->exec(self::$config['db.request.create.table']);
                    break;
                case 'SQLite':
                    $this->db = new \PDO(self::$config['db.conn']);
                    $this->db->exec(self::$config['db.request.create.table']);
                    break;
                //    Пример расширения
                // case 'имя_PDO_драйвера':
                // $this->db = new \PDO([Особенность подключения к БД]]);
                // $this->db->exec([Дополнительные запросы создания БД, таблицы и т.д.]);
                // break;
                default:
                    throw new \Exception("Ошибка подключения к базе данных.");
                break;
            }
        } catch (\Exception $e) {
            echo 'Произошла ошибка:';
            echo $e->getMessage();
            echo $e->getLine();
            echo $e->getFile();
        }
    }
    public function logRecord($msg, $dt = '')
    {
        $msg = $this->db->quote(self::clearData($msg));
        $dt = self::datetime($dt);
        $sql = "INSERT INTO logbook (date, msg) VALUES ('".$dt."', ".$msg.")";
        $this->db->exec($sql);
    }
    public function printLogbook()
    {
        $sql = "SELECT * FROM logbook ORDER BY id DESC";
        $result = $this->db->query($sql);
        if($result) {
            while ($log = $result->fetch(\PDO::FETCH_ASSOC)) {
                echo $log['date'].' '.nl2br($log['msg']).'<br>';
            }
        }
    }
}
