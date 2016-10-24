var game = {
	/**
	 * Objekte, die das Spiel benötigt
	 */
	components : {
		dialog : null,
		timer : null,
		winTimer : []
	},

	/**
	 * Daten des aktuellen Spieles
	 */
	data : {
		// Alle Karten
		cards : [],
		// Container, der eine Karte beinhaltet
		cardContainer : '',
		// Spielfeld
		board : '',
		// Anzahl Züge des Spielers
		turn : 0,
		// Offene Karten des aktuellen Zuges (maximal 2)
		openCards : [],
		// Standard Kartenmotiv
		defaultCard : '',
		// Sprache der Karten
		language : '',
		// Abgelaufene Zeit in Sekunden
		elapsedTime : 0,
		// Alle offenen, gefundenen Pärchen
		completed : []
	},

	/**
	 * Initialisiert ein neues Spiel
	 */
	init : function(settings) {
		// Standardwerte mit Parametern überschreiben
		$.extend(game.data, settings);
		game.data.openCards = [];
		game.data.completed = [];
		game.data.turn = 0;
		game.components.winTimer = [];
		game.components.modal = null;

		// Karten dem Spielfeld hinzufügen
		game.addCards();
		// Bilder in den Browserchache laden
		game.preloadImages();
		// Sprachdateien in den Browserchache laden
		game.preloadAudio();

		// Timer pausieren
		game.pauseTimer();

		// Event binden. Bei Verlassen des Spieles alle Timer der
		// Siegesanimation entfernen
		$(game).on('exit', game.removeWinTimers);
	},

	/**
	 * Gibt das modale Fenster zurück Wird genutzt für das "Wiederholen" und die
	 * Anzeige des Ergebnisses
	 * 
	 * @return $() Overlay Dialog
	 */
	getDialog : function() {
		if (game.components.modal == null) {
			game.components.modal = $('#myModal').modal({});
			// Beim ersten holen das Event binden
			game.components.modal.on('hidden.bs.modal', function(e) {
				// Beim Schließen des Dialogfensters den Timer wieder aktivieren
				game.resumeTimer();
				// Prüfen, ob das Spiel zuende ist
				game.checkEnd();
			});
		}

		// jQuery-Objekt zurückgeben
		return game.components.modal;
	},

	/**
	 * Zeigt den modalen Dialog
	 */
	showDialog : function(mode, pdata) {
		var modal = game.getDialog();
		data = {
			img : '',
			word : '',
			score : ''
		};
		// Standardwerte des data-Objektes mit den Parameterdaten überschreiben
		$.extend(data, pdata);

		// Alle Inhalte im Dialogfenster ausblenden
		modal.find('.modal-content').hide();

		var content = null;
		switch (mode) {
		case 'score':
			content = modal.find('#score');
			break;
		case 'repeat':
		default:
			content = modal.find('#repeat');
		}

		// Gewünschten Inhalt wieder einblenden
		content.show();

		// Inhalt mit Daten füllen
		content.find('.modal-body .image').html(data.img);
		content.find('.modal-body .word').html(data.word);
		content.find('.modal-body .score').html(data.score);

		// Dialogfenster öffnen
		modal.modal('show');

	},

	/**
	 * Timer starten
	 */
	startTimer : function() {
		// Falls noch ein Timer läuft, diesen stoppen
		game.pauseTimer();
		// Zeit wieder auf 0 setzen
		game.data.elapsedTime = 0;
		// Timer wieder aktivieren
		game.resumeTimer();

	},

	/**
	 * Pausierten Timer wieder starten
	 */
	resumeTimer : function() {
		// setInterval ruft die angegebene Funktion jede Sekunde (1000ms) auf
		game.components.timer = setInterval(game.addTimer, 1000);
	},

	/**
	 * Timer pausieren
	 */
	pauseTimer : function() {
		if (game.components.timer != null) {
			// Es gibt ein Timer-Interval -> Diesen beenden
			clearInterval(game.components.timer);
		}
	},

	/**
	 * 1 Sekunde dem Timer hinzufügen
	 */
	addTimer : function() {
		// Vergangene Zeit um 1 erhöhen
		game.data.elapsedTime++;

		// HTML aktualisieren
		$('.expired-time').html(game.getFormattedTimeString());
	},

	/**
	 * Benötigte Spielzüge um 1 eröhen
	 */
	addTurn : function() {
		// Spielzüge um 1 eröhen
		game.data.turn++;

		// HTML aktualisieren
		$('.count-flips').html(game.data.turn);
	},

	/**
	 * Gibt die vergangende Zeit im Format 00:00:00 zurück
	 */
	getFormattedTimeString : function() {
		var t = new Date(2016, 1, 1, 0, 0, game.data.elapsedTime, 0);
		return t.toLocaleTimeString();
	},

	/**
	 * Fügt die Karten zum Spielfeld zurück
	 */
	addCards : function() {
		var list = [];

		// Vorhandene Daten aufbereiten
		$.each(game.data.cards, function(key, value) {
			var card = new Card(value.id, value.word, value.image);
			// Eine Kachel für das Bild ...
			var imageTile = new Tile(card, TILETYPE.IMAGE);
			list.push(imageTile);
			// ... und eine Kachel für das Wort
			var wordTile = new Tile(card, TILETYPE.WORD);
			list.push(wordTile);
		});

		// Alle Kacheln mischen
		list = shuffleArray(list);

		// Spielfeld leeren
		$(game.data.board).empty();

		// Allen Kacheln
		// - die Daten zuordnen
		// - Animation hinzufügen und
		// - Klick-Event binden
		$.each(list, function(key, value) {
			var item = $(game.data.cardContainer);

			// Daten an DOM binden
			item.data('tile', value);

			// Kachel dem Spielfeld hinzufügen
			$(game.data.board).append(item);

			// Flip-Animation hinzufügen
			item.flip({
				trigger : 'manual',

			});

			// Klick-Event binden
			item.click(game.flipCard);
		});

	},

	/**
	 * Eine Karte umdrehen
	 */
	flipCard : function(event) {
		// Aktuell geklicktes Objekt
		var tile = $(event.currentTarget);
		var data = tile.data('tile');
		var flip = tile.data("flip-model");

		if (!flip.isFlipped) {
			// Nur wenn die Karte noch nicht umgedreht ist, die Anzahl der Flips
			// erhöhen
			game.addTurn();
		}

		if (game.data.turn == 1) {
			// Nach der ersten Aktion den Timer starten
			game.startTimer();
		}

		if (flip.isFlipped || game.data.openCards.length > 1) {
			// Die Karte war schon umgedreht, oder es wurden schon 2 Karten
			// umgedreht
			// -> unzulässige Aktion
			return false;
		}

		if (data.type === TILETYPE.IMAGE) {
			// Text ausblenden, Bild einblenden
			tile.find('.card-front-text').css('display', 'none');
			tile.find('.card-front-img').css('display', 'block').attr('src',
					data.card.image);
		} else {
			// Bild ausblenden, Text einblenden
			tile.find('.card-front-img').css('display', 'none')
			tile.find('.card-front-text').css('display', 'table-cell').html(
					data.card.word);
			tile.find('.card-front-text').parent().css('display', 'table');
		}

		// Die nun umzudrehende Karte der Liste der offenen Karten hinzufügen
		game.data.openCards.push(tile);

		if (game.data.openCards.length > 1) {
			// Es wurden 2 Karten umgedreht, nun diese vergleichen

			if (game.data.openCards[0].data('tile').card.id === data.card.id) {
				// Pärchen gefunden!
				game.pauseTimer();

				// Dialog zum Wiederholen mit dem zugehörigen Daten anzeigen
				var img = new Image();
				img.src = data.card.image;
				game.showDialog('repeat', {
					img : img,
					word : data.card.word
				});

				tile.on('flip:done', {
					obj : game.data.openCards[0]
				}, function(event) {
					// Wenn die Animation fertig ist, dann die Kacheln grün
					// färben
					game.setItemCompleted($(this));
					game.setItemCompleted(event.data.obj);
				});

				// Offene Karten zurücksetzen -> Nächsten Zug ermöglichen
				game.data.openCards = [];

			} else {
				// Kein Pärchen gefunden
				// Nach einer Sekunde die beiden offenen Karten wieder verdecken
				setTimeout(function() {
					$.each(game.data.openCards, function(key, value) {
						// Alle offenen Karten wieder umdrehen
						value.flip(false);
						game.data.openCards = [];
					});
				}, 1000);
			}
		}

		// Sprachsample abspielen
		var audiofile = 'data/audio/' + game.data.language + '/'
				+ data.card.word + '.mp3';
		var audio = new Audio(audiofile);
		audio.play();

		// Animation starten
		tile.flip(true);

	},

	/**
	 * Lädt die benötigten Bilder in den Cache, sodass sie nicht im Moment des
	 * Aufdeckens geladen werden müssen
	 */
	preloadImages : function() {

		var images = [];
		$.each(game.data.cards, function(key, value) {
			// Erzeuge ein Bild-Objekt, was dazu führt, dass das Bild vom
			// Browser geladen wird
			images[key] = new Image();
			images[key].src = value.image;
		});
	},

	/**
	 * Lädt die benötigten Sprachsamples in den Cache, sodass sie nicht im
	 * Moment des Aufdeckens geladen werden müssen
	 */
	preloadAudio : function() {
		$.each(game.data.cards, function(key, value) {
			// Erzeuge ein Audio-Objekt, was dazu führt, dass das Bild vom
			// Browser geladen wird
			var audio = new Audio('data/audio/' + game.data.language + '/'
					+ value.word + '.mp3');
		});
	},

	/**
	 * Prüft, ob das Spiel beendet wurde
	 */
	checkEnd : function() {
		// Beispiel: Bei 15 Pärchen, müssen 30 Karten aufgedeckt werden
		if (game.data.cards.length * 2 == game.data.completed.length) {

			// Timer stoppen
			game.pauseTimer();

			// Close-Event vom Dialog ausschalten
			game.getDialog().off('hidden.bs.modal');

			// Ergebnisse speichern
			game.saveResults();

			// Anzeige der Ergebnisse
			game.showDialog('score', {
				score : game.getFormattedTimeString()
			});

			// Siegesanimation starten
			$.each(game.data.completed, function(key, item) {
				game.components.winTimer.push(setInterval(function() {
					item.flip('toggle');
				}, 1000));
			});
		}
	},

	/**
	 * Speichert die Ergebnisse des Spieles
	 */
	saveResults : function() {
		$.ajax({
			type : 'POST',
			url : 'save-game',
			data : {
				time : game.data.elapsedTime,
				flips : game.data.turn
			},
			success : function(response) {
				var data = $.parseJSON(response);
			},
			error : function() {
			}
		});
	},

	/**
	 * Setzt eine Karte als korrekt
	 */
	setItemCompleted : function(tile) {
		// CSS-Klasse hinzufügen (für Design)
		tile.data('flip-model').backElement.addClass('match', 1000);
		if ($.inArray(tile, game.data.completed) < 0) {
			// Kachel der Liste der fertigen Kacheln hinzufügen
			game.data.completed.push(tile);
		}
	},

	/**
	 * Beim Schließen des Spieles, wird das Event "exit" ausgelöst
	 */
	exitGame : function() {
		$(game).trigger('exit');
	},

	/**
	 * Beendet die Siegesanimation
	 */
	removeWinTimers : function() {
		// Alle Interval-Timer der Siegesanimation entfernen
		$.each(game.components.winTimer, function(key, value) {
			clearInterval(value);
		});
	}
}; // end game

/**
 * Prototyp für eine Kachel
 * 
 * @param card
 *            Card Daten der Karte
 * @param type
 *            TILETYPE Typ der Kachel
 */
function Tile(card, type) {
	this.card = card;
	this.type = type;
}

/**
 * Enumeration für alle möglichen Kacheltypen
 */
var TILETYPE = {
	WORD : 0,
	IMAGE : 1
}

/**
 * Prototyp für eine Karte
 * 
 * @param id
 *            string Eindeutige Identifikation der Karte (zum Vergleich)
 * @param word
 *            string Wort der Karte
 * @param image
 *            string Bild der Karte
 */
function Card(id, word, image) {
	this.id = id;
	this.word = word;
	this.image = image;
}

/**
 * Mischt ein Array
 * 
 * @param array
 *            Zu mischendes Array
 * @returns Gemischtes Array
 */
function shuffleArray(array) {
	for (var i = array.length - 1; i > 0; i--) {
		var j = Math.floor(Math.random() * (i + 1));
		var temp = array[i];
		array[i] = array[j];
		array[j] = temp;
	}
	return array;
}