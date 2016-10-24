var game = {

	settings : {
		mute : false
	},

	data : {
		cards : [],
		cardContainer : '',
		board : '',
		turn : 0,
		openCards : [],
		defaultCard : '',
		language : '',
		elapsedTime : 0,
		timer : null,
		completed : [],
		modal: null
	},
	init : function(settings) {

		// menu.data = {

		// cards: [],
		// turn: 0,
		// openCards: 0
		// };

		// Standardwerte mit Parametern überschreiben
		$.extend(game.data, settings);
		game.data.openCards = [];
		game.data.turn = 0;

		game.addCards();
		game.preloadImages();
		// game.data.nav.mute.flip();

		game.startTimer();
	},
	
	getDialog : function() {
		if (game.data.modal == null) {
			game.data.modal = $('#myModal').modal({});
			// Beim ersten holen das Event binden
			game.data.modal.on('hidden.bs.modal', function(e) {
				game.resumeTimer();
				game.checkEnd();
			});
		}
		return game.data.modal;
	},

	startTimer : function() {
		game.pauseTimer();
		game.data.elapsedTime = 0;
		game.resumeTimer();
	},
	resumeTimer : function() {
		game.data.timer = setInterval(function() {
			game.addTimer();
		}, 1000);
	},
	pauseTimer : function() {
		if (game.data.timer != null) {
			clearInterval(game.data.timer);
		}
	},
	addTimer : function() {
		game.data.elapsedTime++;
		var t = new Date(2016, 1, 1, 0, 0, game.data.elapsedTime, 0);
		$('.expired-time').html(t.toLocaleTimeString());
	},

	addCards : function() {
		var list = [];

		$.each(game.data.cards, function(key, value) {
			var card = new Card(value.id, value.word, value.image);
			var imageTile = new Tile(card, TILETYPE.IMAGE);
			list.push(imageTile);
			var wordTile = new Tile(card, TILETYPE.WORD);
			list.push(wordTile);
		});

		list = shuffleArray(list);
		$(game.data.board).empty();

		$.each(list, function(key, value) {
			var item = $(game.data.cardContainer);
			// item.find('.card-front').css('background-image',
			// "url('" + game.data.defaultCard + "')");

			item.data('tile', value);
			$(game.data.board).append(item);

			item.flip({
				trigger : 'manual',

			});
			item.click(game.flipCard);
		});

	},
	
	addTurn: function() {
		game.data.turn++;
		$('.count-flips').html(game.data.turn);
	},
	
	flipCard : function(event) {
		var tile = $(event.currentTarget);
		var data = tile.data('tile');
		game.addTurn();

		var flip = tile.data("flip-model");

		if (!flip.isFlipped && game.data.openCards.length < 2) {
			if (data.type === TILETYPE.IMAGE) {
				tile.find('.card-front-text').css('display', 'none');
				tile.find('.card-front-img').css('display', 'block').attr(
						'src', data.card.image);
				// .css('background-image',"url('" + data.card.image +
				// "')");
			} else {
				tile.find('.card-front-img').css('display', 'none')
				tile.find('.card-front-text').css('display', 'table-cell')
						.html(data.card.word);
				tile.find('.card-front-text').parent().css('display', 'table');
			}
			game.data.openCards.push(tile);

			if (game.data.openCards.length > 1) {
				if (game.data.openCards[0].data('tile').card.id === data.card.id) {

					game.pauseTimer();
					
					var img = new Image();
					img.src = data.card.image;
					var modal = game.getDialog();

					modal.find('.modal-body .image').html(img);
					modal.find('.modal-body .word').html(data.card.word);
					modal.modal('show');
					modal.modal();
					
					var a = 'b';
					tile.on('flip:done', {
						obj : game.data.openCards[0]
					}, function(event) {
						game.setItemCompleted($(this));
						game.setItemCompleted(event.data.obj);
					});
					game.data.openCards = [];

				} else {
					setTimeout(function() {
						$.each(game.data.openCards, function(key, value) {
							value.flip(false);
							game.data.openCards = [];
						});
					}, 1000);
				}
			}

			if (!game.settings.mute) {
				var audiofile = 'data/audio/' + game.data.language + '/'
						+ data.card.word + '.mp3';
				var audio = new Audio(audiofile);
				audio.play();
			}

			tile.flip(true);
		}

	},
	preloadImages : function() {
		// Lädt die benötigten Bilder in den Cache, sodass sie nicht im Moment
		// des Aufdeckens geladen werden müssen
		var images = [];
		$.each(game.data.cards, function(key, value) {
			// Erzeuge ein Bild-Objekt, was dazu führt, dass das Bild vom
			// Browser geladen wird
			images[key] = new Image();
			images[key].src = value.image;
		});
	},
	checkEnd : function() {
		// Prüft, ob das Spiel beendet wurde
		if (game.data.cards.length * 2 == game.data.completed.length) {
			game.pauseTimer();
			
			// Ergebnisse speichern
			game.saveResults();

			// Anzeige der Ergebnisse
			// TODO
			$.each(game.data.completed, function(key, item) {
				setTimeout(function() {
					setInterval(function() {
						item.flip('toggle');
					}, 1000)
				}, 500);
			});
		}
	},
	saveResults : function() {
		$.ajax({
			type : 'POST',
			url : 'save-game',
			data : {
				time : game.data.elapsedTime,
				flips : game.data.turn
			},
			beforeSend : function() {
				// $('#ajax-panel').html('<div class="loading"><img
				// src="/images/loading.gif" alt="Loading..." /></div>');
			},
			success : function(response) {
				var data = $.parseJSON(response);
			},
			error : function() {
				$('#ajax-panel').empty();
			}
		});
	},

	setItemCompleted : function(tile) {
		tile.data('flip-model').backElement.addClass('match', 1000);
		if ($.inArray(tile, game.data.completed) < 0) {
			game.data.completed.push(tile);
		}
	}
};

function Tile(card, type) {
	this.card = card;
	this.type = type;
}

var TILETYPE = {
	WORD : 0,
	IMAGE : 1
}

function Card(id, word, image) {
	this.id = id;
	this.word = word;
	this.image = image;
}

function shuffleArray(array) {
	for (var i = array.length - 1; i > 0; i--) {
		var j = Math.floor(Math.random() * (i + 1));
		var temp = array[i];
		array[i] = array[j];
		array[j] = temp;
	}
	return array;
}