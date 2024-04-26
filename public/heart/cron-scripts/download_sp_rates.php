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
$d = date("Y-m-d");
$url_rates = "https://www.cbr.ru/scripts/XML_val.asp?d=0";
$url = $url_rates;
$log = "../logs/auto-update-sp-rates.log"; # лог файл интеграции

$xml_string = file_get_contents($url); # получение данных
$xml = simplexml_load_string($xml_string); # преобразуем xml в объект

if ($xml === false) {
	foreach (libxml_get_errors() as $error) {
		$text = $d . " " . $error->message;
		file_put_contents($log, $text . PHP_EOL, FILE_APPEND);
	}
} else { # разбираем xml

	foreach ($xml->children() as $item) {
		$id = $item['ID'];
		$name = $item->Name;
		$en = $item->EngName;
		$nominal = $item->Nominal;

		# запись/обновление информации в бд
		$s = $mysqli->query('select id from sp_rates where code = "' . $id . '"');
		$cs = mysqli_num_rows($s);

		if ($cs == 0) {
			$q = $mysqli->query('insert into sp_rates (name,code,engname,nominal) values ("' . $name . '","' . $id . '","' . $en . '","' . $nominal . '")');
			if ($q) {
				$text = $d . " - Запись в бд добавлена. " . $name;
			} else {
				$text = $d . " - Ошибка записи в бд. " . $name;
			}
		} else {
			$q = $mysqli->query('update sp_rates set name = "' . $name . '", engname = "' . $en . '", nominal = "' . $nominal . '" where code = "' . $id . '"');
			$text = $d . " - Запись в бд обновлена. " . $name;
		}
		file_put_contents($log, $text . PHP_EOL, FILE_APPEND); # запись в лог
	}
}
?>