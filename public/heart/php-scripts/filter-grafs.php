<?
if ($_POST['val']) {
	include "../db.php"; # коннект к бд
	$valute = $_POST['val']; # код валюты из фильтра
	$d = $_POST['d']; # дата начала из фильтра
	$d2 = $_POST['d2']; # дата окончания из фильтра

	if (($d2 < $d) || (($d2 - $d) > 30)) { # не пускаем дальше, если проблема с датами
		die("1");
	}

	if ($d != $d2) { # получаем все даты диапазона
		$period = new DatePeriod(
			new DateTime($d),
			new DateInterval('P1D'),
			new DateTime($d2)
		);
		foreach ($period as $key => $value) {
			$dates[] = $value->format('Y-m-d');
		}
		$dates[] = $d2; # и добавляем последнюю
	} else {
		$dates[] = $d; # если равны, то записываем в массив одну
	}

	# Функция загрузки данных в БД
	function update_val($d, $mysqli)
	{

		$xml = ""; # переменная для xml
		$d2 = date("d/m/Y", strtotime($d)); # дата в нужном формате
		$url_rates = "https://www.cbr.ru/scripts/XML_daily.asp?date_req="; # ссылка на ЦБ РФ
		$url = $url_rates . $d2;
		$log = "../logs/update-valutes.log"; # лог файл интеграции

		$xml_string = file_get_contents($url); # получение данных
		$xml = simplexml_load_string($xml_string); # преобразуем xml в объект

		if ($xml === false) {
			foreach (libxml_get_errors() as $error) {
				$text = $d . " " . $error->message;
				file_put_contents($log, $text . PHP_EOL, FILE_APPEND); # запись в лог в случае ошибки
			}
		} else { # разбираем xml
			foreach ($xml->children() as $valute) {
				$id = $valute['ID'];
				$numcode = $valute->NumCode;
				$charcode = $valute->CharCode;
				$value = str_replace(',', '.', $valute->Value); # меняем запятую на точку для FLOAT
				$vr = str_replace(',', '.', $valute->VunitRate);

				# запись/обновление информации в бд
				$s = $mysqli->query('select id from valutes where rate = "' . $id . '" and d = "' . $d . '"');
				$cs = mysqli_num_rows($s);

				if ($cs == 0) {
					$q = $mysqli->query('insert into valutes (d,rate,charcode,value,vr) values ("' . $d . '","' . $id . '","' . $charcode . '","' . $value . '","' . $vr . '")');
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
		return;
	}

	foreach ($dates as $date) { # ручная догрузка данных в бд, если данных нет в системе
		$arVal = $mysqli->query('select id from valutes where d = "' . $date . '"');
		$countVal = mysqli_num_rows($arVal);
		if ($countVal == 0) {
			update_val($date, $mysqli); # вызов функции загрузки
		} else {

		}
	}

	# отдаем данные для графика в браузер в json
	$sql = 'select * from valutes t1 join sp_rates t2 on t1.rate = t2.code where t1.rate = "' . $valute . '" and t1.d between "' . $d . '" and "' . $d2 . '" order by d asc';
	$arVal = $mysqli->query($sql);
	$c = mysqli_num_rows($arVal);
	$m = array();
	if ($c > 0) {
		while ($rowVal = mysqli_fetch_array($arVal)) {
			$m[] = ['date' => $rowVal['d'], 'value' => round($rowVal['value'], 2)]; # формируем массив данных
		}
		echo json_encode($m); # декодируем в json и отдаем в браузер
	} else {
		echo "0"; # если данных нет и на ЦБ РФ, отдаем ошибку в браузер (описана в main.js)
	}

} else {
	echo "Пустой ответ сервера.";
}
?>