var menu = {
	init : function(settings) {
		menu.config = {
			// Standardwerte
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
						// Standardroutine deaktivieren (kein HTTP-Request)
						event.preventDefault();
						
						// Rufe showItem mit dem geklickten link auf
						menu.showItem($(event.currentTarget).find('[href]').attr('href'));
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
			// GET request ausführen
			$.get(target, null, menu.switchPage);
			break;
		default:
			// console.log('Kein spezifischer Handler für ', target);
			$.get(target, null, menu.switchPage);
		}
	},

	newGame : function() {
		$.ajax({
			type : 'GET',
			url : 'new-game',
			data : {

			},
			success : function(page) {
				page = $.parseJSON(page);
				menu.switchPage(page.view, function(container) {
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
				// page = $.parseJSON(page);
				menu.switchPage(page/* .view */, function(container) {
					// initChart(page);
				});
			},
		});
	},

	/**
	 * Wird immer aufgerufen, wenn die Seite gewechselt wird
	 */
	switchPage : function(page, callback) {
		// Beende ein Spiel 
		game.exitGame();
		
		// Tausche den Inhalt der Seite 
		menu.config.container.empty();
		menu.config.container.html(page);

		if (typeof callback === 'function') {
			// Callback Funktion aufrufen
			callback(menu.config.container);
		}
	},
	
	/**
	 * Aktiviert oder deaktiviert alle Menüpunkte
	 * @param bool active Sollen die Menüpunkte aktiviert werden
	 */
	setActive : function(active) {
		// Alle Menüpunkte durchgehen
		menu.config.items.each(function() {
			if (active) {
				// Menüpunkt aktivieren
				$(this).removeClass('disabled');
			} else {
				// Menüpunkt deaktivieren
				$(this).addClass('disabled');
			}
		});
	}
};