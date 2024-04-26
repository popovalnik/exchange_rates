<?
include "tmpl/header.php";
?>
<!-- WORK AREA -->
<div class="container">
	<div class="row mb-3 mt-3">
		<nav class="nav text-ex">
			<a class="nav-link active" href="/">Курс сегодня</a>
			<a class="nav-link" href="table.php">Таблица курсов</a>
			<a class="nav-link" href="grafs.php">Динамика</a>
		</nav>
	</div>
	<div class="row">
		<h3>Курсы валют ЦБ РФ на сегодня <?= date("d.m.Y") ?></h3>
	</div>
	<div class="row mt-3">
		<?
		$d = date("Y-m-d"); # дата сегодня
		$y = date("Y-m-d", strtotime(date("Y-m-d") . "- 1 day")); # вчерашняя дата
		$arVal = $mysqli->query('select * from valutes t1 join sp_rates t2 on t1.rate = t2.code where t1.d = "' . $d . '"'); # вывод котировок валют за сегодня
		while ($rowVal = mysqli_fetch_array($arVal)) {
			$arPercent = $mysqli->query('select t1.value from valutes t1 join sp_rates t2 on t1.rate = t2.code where t1.rate = "' . $rowVal['code'] . '" and t1.d = "' . $y . '"'); # вывод конкретной котировки валюты за вчера для сравнения
			$rowPercent = mysqli_fetch_array($arPercent);
			$tz = round($rowVal['value'], 2);
			?>
			<div class="col-12 col-md-6 col-lg-3 p-2 justify-content-around">
				<div class="card p-3 h-100">
					<div class="card-body text-center">
						<h5 class="card-title"><?= $rowVal['charcode'] ?></h5>
						<p class="card-text"><?= $rowVal['name'] ?></p>
						<h6 class="card-text"><?= $tz ?> ₽</h6>

						<?
						$yz = round($rowPercent['value'], 2); # соотношение валют к предыдущему дню в процентах
						$percent = round((($tz / $yz) * 100 - 100), 4);
						if ($percent > 0) {
							$tab = "<span class='text-success'><i class='ti-arrow-up'></i> " . $percent . "</span>";
						} else {
							$tab = "<span class='text-danger'><i class='ti-arrow-down'></i> " . $percent . "</span>";
						}
						?>
						<?= $tab ?>

					</div>
				</div>
			</div>
		<?
		}
		?>
	</div>
</div>

<?
include "tmpl/footer.php";
?>