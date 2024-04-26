<?
if ($_POST['d']) {
	$valute = implode('","', $_POST['selectednumbers']); # массив кодов валют из фильтра
	$d = $_POST['d']; # дата начала из фильтра
	$d2 = $_POST['d2']; # дата окончания из фильтра
	$m = array(); # массив для json

	include "../db.php"; # коннект к бд

	$dates = array(); # переменная массивов дат

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

	# формирование json массива (отталкиваясь от дат)
	foreach ($dates as $date) {
		$sql = 'select * from valutes t1 join sp_rates t2 on t1.rate = t2.code where t1.rate IN ("' . $valute . '") and t1.d = "' . $date . '"';
		$arVal = $mysqli->query($sql);
		$c = mysqli_num_rows($arVal);
		if ($c > 0) {
			$i = 0;
			while ($rowVal = mysqli_fetch_array($arVal)) { # записываем данные в двумерный массив
				$m[$date][$i] = ['rate' => $rowVal['rate'], 'charcode' => $rowVal['charcode'], 'name' => $rowVal['name'], 'value' => $rowVal['value']];
				$i++;
			}
		} else {

		}
	}

	$json = json_encode($m); # декодируем массив в json
	$json_file = "json-" . date("Y-m-d-H-i-s") . "-" . rand(11111, 99999) . ".json"; # задаем имя файла
	$p = "../../upload/" . $json_file; # путь к файлу от скрипта
	$download = "/upload/" . $json_file; # путь к файлу для пользователя
	file_put_contents($p, $json); # записываем json в файл

	echo $download; # отдаем ссылку на скачивание в браузер

} else {
	echo "Пустой ответ сервера.";
}
?>