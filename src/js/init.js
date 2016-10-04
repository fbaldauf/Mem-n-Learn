var colors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#2196f3', '#4caf50', '#cddc39', '#ff9800', '#795548',
	'#9e9e9e'];

$(document).ready(function() {
    menu.init({
	items: $('.navbar').find('.navbar-brand').add($('.navbar').find('li')),
	container: $('#content')
    });
});
