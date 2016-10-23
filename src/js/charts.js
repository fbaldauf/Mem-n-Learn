function initChart(data) {

    var dim = [];
    $.each(data.data.games, function(key, value) {
	dim.push(value.date);
    });

    var vals = [];
    $.each(data.data.games, function(key, value) {
	vals.push(parseInt(value.timeMinutes));
    });

    $('#container').highcharts({
	title: {
	    text: 'Lernverlauf',
	    x: -20
	// center
	},
	subtitle: {
	    text: '',
	    x: -20
	},
	xAxis: {
	    categories: dim
	},
	yAxis: {
	    title: {
		text: 'Minuten'
	    },
	    plotLines: [{
		value: 0,
		width: 1,
		color: '#808080'
	    }]
	},
	tooltip: {
	    valueSuffix: 'Min'
	},
	legend: {
	    layout: 'vertical',
	    align: 'right',
	    verticalAlign: 'middle',
	    borderWidth: 0
	},
	series: [{
	    name: data.user,
	    data: vals
	}]
    });
}