// // Функция очистки фильтра таблиц
$("body").on("click", "#clean-filter", function () {
	$("[name='date1']").val("");
	$("[name='date2']").val("");
	$("[name='valute']").val("");
	$("#print-table").html("");
	$("#save-json").hide();
});

// // Функция очистки фильтра графиков
$("body").on("click", "#clean-grafs-filter", function () {
	$("[name='date1']").val("");
	$("[name='date2']").val("");
	$("[name='valute']").val("");
	$("#print-grafs").html("");
});

// Функция для формирования таблицы
$("body").on("click", "#save-val", function () {
	var selectednumbers = [];
	$("[name='valute'] :selected").each(function (i, selected) {
		selectednumbers[i] = $(selected).val();
	});
	var d = $("[name='date1']")[0].value;
	var d2 = $("[name='date2']")[0].value;
	let today = new Date();
	let td = new Date(d);
	let td2 = new Date(d2);
	let rd = ((td2 - td) / (24 * 3600 * 1000));
	if ((selectednumbers[0] != 0) && (d.length > 0)) {
		if (rd > 30) {
			alert("Выбранный диапазон не может превышать 30 дней! Сейчас выбрано " + rd + " дней!");
		} else {
			if (td > td2) {
				alert("Дата окончания не может быть больше, чем дата начала!");
			} else {
				if ((today < td) && (today < td2)) {
					alert("Выбранная дата еще не наступила! Но скоро наступит.");
				} else {
					$("#spinner").show();
					$.post('heart/php-scripts/filter.php', { selectednumbers, d, d2 }, onSaveEvent);
					function onSaveEvent(data) {
						if (data == 0) {
							$("#save-json").hide();
							$("#print-table").html("<div class='p-3'><p class='mt-3'>В базе данных ЦБ РФ нет информации по этой валюте за данный период времени.</p></div>");
						} else if (data == 1) {
							alert("Произошла ошибка, свяжитесь с администратором системы!");
						} else {
							$("#save-json").show();
							$("#spinner").hide();
							$("#print-table").html(data);
							$('#table').DataTable({
								'paging': true,
								'lengthChange': true,
								'searching': false,
								'ordering': true,
								'info': true,
								'autoWidth': false,
								'order': [[0, 'desc']]
							})
						}
					}
				}
			}
		}
	} else {
		alert("Не заполнены поля фильтра!");
	}

});

// Функция для формирования графиков
$("body").on("click", "#save-grafs-val", function () {
	$("#print-grafs").html("");
	var val = $("[name='valute']")[0].value;
	var d = $("[name='date1']")[0].value;
	var d2 = $("[name='date2']")[0].value;
	let today = new Date();
	let td = new Date(d);
	let td2 = new Date(d2);
	let rd = ((td2 - td) / (24 * 3600 * 1000));
	if ((val.length > 1) && (d.length > 0) && (d2.length > 0)) {
		if (rd > 30) {
			alert("Выбранный диапазон не может превышать 30 дней! Сейчас выбрано " + rd + " дней!");
		} else {
			if (td > td2) {
				alert("Дата окончания не может быть больше, чем дата начала!");
			} else {
				if ((today < td) && (today < td2)) {
					alert("Выбранная дата еще не наступила! Но скоро наступит.");
				} else {
					$("#spinner").show();
					$.post('heart/php-scripts/filter-grafs.php', { val, d, d2 }, onSaveEvent);
					function onSaveEvent(data) {
						if (data == 0) {
							$("#print-grafs").html("<div class='p-3'><p class='mt-3'>В базе данных ЦБ РФ нет информации по этой валюте за данный период времени.</p></div>");
						} else if (data == 1) {
							alert("Произошла ошибка, свяжитесь с администратором системы!");
						} else {
							$("#spinner").hide();
							var data = JSON.parse(data);
							$(document).ready(function () {
								Morris.Line({
									element: 'print-grafs',
									data: data,
									xkey: 'date',
									ykeys: ['value'],
									labels: ['₽'],
									fillOpacity: 0,
									pointStrokeColors: ['#0d6efd'],
									behaveLikeLine: true,
									gridLineColor: '#e0e0e0',
									lineWidth: 3,
									hideHover: 'auto',
									lineColors: ['#008cd3'],
									parseTime: false,
									resize: true
								});
							});
						}
					}
				}
			}
		}
	} else {
		alert("Не заполнены поля фильтра!");
	}
});

// Функция для формирования json
$("body").on("click", "#save-json", function () {
	var selectednumbers = [];
	$("[name='valute'] :selected").each(function (i, selected) {
		selectednumbers[i] = $(selected).val();
	});
	var d = $("[name='date1']")[0].value;
	var d2 = $("[name='date2']")[0].value;
	$.post('heart/php-scripts/filter-json.php', { selectednumbers, d, d2 }, onSaveEvent);
	function onSaveEvent(data) {
		var link = document.createElement('a');
		link.setAttribute('href', data);
		link.setAttribute('download', 'report-json' + d + '_' + d2 + '.json');
		link.click();
		return false;
	}
})
