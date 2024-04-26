<?
include "tmpl/header.php";
?>
<!-- WORK AREA -->
<div class="container">
	<div class="row mb-3 mt-3">
		<nav class="nav text-ex">
			<a class="nav-link" href="/">Курс сегодня</a>
			<a class="nav-link active" href="table.php">Таблица курсов</a>
			<a class="nav-link" href="grafs.php">Динамика</a>
		</nav>
	</div>
	<div class="row">
		<h3>Таблица курсов валют</h3>
	</div>
	<div class="row alert color-ex mt-3">
		<div class="col-12">
			<div class="row p-2">
				<div class="col-3">
					<span class="text-white">Валюта</span>
				</div>
				<div class="col-9">
					<select class="form-control" multiple name="valute" size="5">
						<option value="0" selected></option>
						<?
						$arVal = $mysqli->query("select code, name from sp_rates"); # вывод справочника валют
						while ($rowVal = mysqli_fetch_array($arVal)) {
							?>
							<option value="<?= $rowVal['code'] ?>"><?= $rowVal['name'] ?></option>
						<?
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row p-2">
				<div class="col-3">
					<span class="text-white">Дата начала</span>
				</div>
				<div class="col-9">
					<input class="form-control col-4" type="date" name="date1"
						value="<?= date("Y-m-d", strtotime(date("Y-m-d") . "- 7 day")); ?>">
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row p-2">
				<div class="col-3">
					<span class="text-white">Дата окончания</span>
				</div>
				<div class="col-9">
					<input class="form-control col-4" type="date" name="date2" value="<?= date("Y-m-d") ?>">
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row p-2">
				<div class="col-12">
					<button class="btn btn-success" id="save-val">Применить фильтр</button>
					<button class="btn btn-primary" id="clean-filter">Очистить</button>
					<button style="display:none;" class="btn btn-primary float-right" id="save-json">Сохранить в
						json</button>
				</div>
			</div>
		</div>
	</div>
	<div class="container bg-light mb-3" id="print-table">
	</div>
</div>
<?
include "tmpl/footer.php";
?>