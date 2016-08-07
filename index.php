<?php
header("Content-Type: text/html; charset=utf-8");
//    Автозагрузка классов
require_once __DIR__.'/vendor/autoload.php';
//    Указываем необходимые псевдонимы для удобства дальнейшей работы
use liw\Logger\Logger AS Logger;
use liw\Logger\LogFactory AS LogFactory;
//    Подгружаем файл конфигураций класса config.ini
Logger::getConfig();
//    Отправка формы для пополнения БД данными лог, либо же лог-файлы
if($_SERVER["REQUEST_METHOD"]=="POST") {
	$msg = $_POST['msg'];
//    Используем "фабрику" для того чтобы занести данные во все указанные БД или лог-файлы
//    Указанные в файле config.ini пункт db.array
//    В данном примере используется список из PDO, CL, FL
	foreach (Logger::$config['db.array'] as $key) {
		LogFactory::DB($key)->logRecord($msg);
	}
	header("Location: http://".$_SERVER["SERVER_NAME"]);
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ТЗ php/Ершов</title>
	<link href="./css/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
</head>
<body>
		<!-- phpLiteAdmin Для мониторинга БД SQLite -->
	<a class="phpLiteAdmin" href="./phpLiteAdmin/">Перейти в phpLiteAdmin</a>
	<div class="block-create-log">
		<form action="./" method="POST">
			<textarea class="message" name="msg">Простое поле ввода текста</textarea>
			<input type="submit" class="bt-send">
		</form>
	</div>
	<div class="block-conclusion">
		<header class="header-coclusion">
			<h5>Для вывода используется метод <span class="fun">printLogbook()</span> Выводит все содержимое.</h5>
		</header>
		<div class="wrapper-view-log-file left">
			<div class="header-log">В какой то файл (logbook.log)</div>
			<div class="view-log-file">
				<?php
				LogFactory::DB()->printLogbook();
				?>
			</div>
		</div>
		<div class="wrapper-view-log-file right">
			<div class="header-log">В базу SQL через модуль PDO</div>
			<div class="view-log-file">
				<?php
				LogFactory::DB('PDO')->printLogbook();
				?>
			</div>
		</div>
	</div>
</body>
</html>