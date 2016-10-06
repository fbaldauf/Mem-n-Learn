var menu = {
	init : function(settings) {
		menu.config = {
			// Standardwerte, die keinen Sinn ergeben :D :D
			items : $("#myFeature li"),
			container : $("<div class='container'></div>"),
			urlBase : "/foo.php?item="
		};

		// Standardwerte mit Parametern überschreiben
		$.extend(menu.config, settings);

		// Menü vorbereiten
		menu.setup();
	},

	setup : function() {
		// Allen Menüeinträgen das Klick-Event zuweisen
		menu.config.items.each(function() {
			$(this).click(
					function(event, o, c) {
						event.preventDefault();
						menu.showItem($(event.currentTarget).find('[href]')
								.attr('href'));
					});
		});
	},

	showItem : function(target) {
		switch (target) {
		case '#':
			// Nichts unternehmen
			break;
		case 'new-game':
			menu.newGame();
			break;
		case 'statistic':
			menu.showStats();
			break;
		case 'logout':
			menu.setActive(false);
			$.get(target, null, menu.switchPage);
			break;
		default:
			//console.log('Kein spezifischer Handler für ', target);
			$.get(target, null, menu.switchPage);
		}
	},

	newGame : function() {
		$.ajax({
			type : 'GET',
			url : 'new-game',
			data : {

			},
			beforeSend : function() {
				// $('#ajax-panel').html('<div class="loading"><img
				// src="/images/loading.gif" alt="Loading..." /></div>');
			},
			success : function(page) {
				page = $.parseJSON(page);
				menu.switchPage(page.view, function(container) {
					// container.find('.card-container').flip();
					// var g = new Game();
					game.init({
						cards : page.cards,
						cardContainer : page.cardContainer,
						board : $('#thumb-wrap'),
						defaultCard : page.defaultCard,
						language : page.language,
						nav : {
							mute : $('#mute')
						}
					});
				});
			},
			error : function() {
				$('#ajax-panel').empty();
			}
		});
	},

	showStats : function() {
		$.ajax({
			type : 'GET',
			url : 'statistic',
			data : {

			},
			success : function(page) {
				page = $.parseJSON(page);
				menu.switchPage(page.view, function(container) {
					initChart(page);
				});
			},
		});
	},

	switchPage : function(page, callback) {
		menu.config.container.empty();
		menu.config.container.html(page);

		if (typeof callback === 'function') {
			callback(menu.config.container);
		}

		// TODO: Für später: Adressleiste aktualisieren
		// var stateObj = {foo: "bar"};
		// history.pushState(stateObj, "page 2", "bar.html");
	},
	setActive : function(active) {
		menu.config.items.each(function() {
			if (active) {
				$(this).removeClass('disabled');
			} else {
				$(this).addClass('disabled');
			}
		});
	}
};