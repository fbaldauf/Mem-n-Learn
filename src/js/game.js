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
		elapsedTime : 0
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
		// console.log(game.data.nav.mute);
		// game.data.nav.mute.flip();

		game.startTimer();
	},

	startTimer : function() {
		setInterval(function() {
			game.addTimer();
		}, 1000);
	},
	addTimer : function() {		
		game.data.elapsedTime++;
		var t = new Date(2016, 1, 1, 0, 0, game.data.elapsedTime,0);
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
	flipCard : function(event) {
		var tile = $(event.currentTarget);
		var data = tile.data('tile');
		// console.log(data.card.id);

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
	setItemCompleted : function(tile) {
		// console.log(tile.data('flip-model'), $(this));

		tile.data('flip-model').backElement.addClass('match', 1000);
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