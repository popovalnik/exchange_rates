<?
# вывод ошибок
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

$path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']); # определяем директорию скрипта
chdir($path_parts['dirname']);
include "../db.php"; # коннект к бд

# переменные
$xml = "";

$d = date("d/m/Y");
$d2 = date("Y-m-d");

$url_rates = "https://www.cbr.ru/scripts/XML_daily.asp?date_req=";
$url = $url_rates . $d;
$log = "../logs/auto-update-valutes.log"; # лог файл интеграции

$xml_string = file_get_contents($url); # получение данных
$xml = simplexml_load_string($xml_string); # преобразуем xml в объект

if ($xml === false) {
	echo "Failed loading XML: ";
	foreach (libxml_get_errors() as $error) {
		$text = $d . " " . $error->message;
		file_put_contents($log, $text . PHP_EOL, FILE_APPEND);
	}
} else { # разбираем xml

	foreach ($xml->children() as $valute) {
		$id = $valute['ID'];
		$numcode = $valute->NumCode;
		$charcode = $valute->CharCode;
		$value = str_replace(',', '.', $valute->Value);
		$vr = str_replace(',', '.', $valute->VunitRate);

		# вывод на экран
		echo $id . " " . $numcode . " " . $charcode . " " . $value . "<br>";

		# запись/обновление информации в бд
		$s = $mysqli->query('select id from valutes where rate = "' . $id . '" and d = "' . $d2 . '"');
		$cs = mysqli_num_rows($s);

		if ($cs == 0) {
			$q = $mysqli->query('insert into valutes (d,rate,charcode,value,vr) values ("' . $d2 . '","' . $id . '","' . $charcode . '","' . $value . '","' . $vr . '")');
			if ($q) {
				$text = $d . " - Запись в бд добавлена. " . $id;
			} else {
				$text = $d . " - Ошибка записи в бд. " . $id;
			}
		} else {
			$q = $mysqli->query('update valutes set value = "' . $value . '", vr = "' . $vr . '" where rate = "' . $id . '" and d = "' . $d2 . '"');
			$text = $d . " - Запись в бд обновлена. " . $id;
		}

		file_put_contents($log, $text . PHP_EOL, FILE_APPEND); # запись в лог

	}

}





?>