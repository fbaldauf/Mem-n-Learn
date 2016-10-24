function initChart(data) {
	var dim = [];
	// Daten für die Highcharts aufbereiten
	$.each(data.data.games, function(key, value) {
		dim.push(value.date);
	});

	var vals = [];
	$.each(data.data.games, function(key, value) {
		vals.push(parseInt(value.timeMinutes));
	});

	// Diagramm initialisieren
	$('#container').highcharts({
		title : {
			text : data.title,
			x : -20
		// center
		},
		subtitle : {
			text : '',
			x : -20
		},
		xAxis : {
			categories : dim
		},
		yAxis : {
			title : {
				text : data.unit
			},
			plotLines : [ {
				value : 0,
				width : 1,
				color : '#808080'
			} ]
		},
		tooltip : {
			valueSuffix : 'Min'
		},
		legend : {
			layout : 'vertical',
			align : 'right',
			verticalAlign : 'middle',
			borderWidth : 0
		},
		series : [ {
			name : data.user,
			data : vals
		} ]
	});
}