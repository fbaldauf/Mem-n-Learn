var game = {

    data: {
	cards: [],
	cardContainer: '',
	board: '',
	turn: 0,
	openCards: 0,
	defaultCard: ''
    },
    init: function(settings) {

	// menu.data = {

	// cards: [],
	// turn: 0,
	// openCards: 0
	// };

	// Standardwerte mit Parametern überschreiben
	$.extend(game.data, settings);

	game.addCards();

    },

    addCards: function() {
	$.each(game.data.cards, function(key, value) {
	    var item = $(game.data.cardContainer);
	    item.find('.card-front').css('background-image', "url('" + game.data.defaultCard + "')");

	    $(game.data.board).append(item);
	    console.log(item.flip({
		trigger: 'manual',

	    }));
	    item.click(game.flipCard);
	});

    },
    flipCard: function(event) {
	var flip = $(event.currentTarget).data("flip-model");

	if (!flip.isFlipped && game.data.openCards < 2) {
	    game.data.openCards++;
	    $(event.currentTarget).flip(true);
	}

    }
};

function Tile() {
    this.isFlipped = false;
    this.flip = function() {
	this.isFlipped = !this.isFlipped;
    }
}

function Card(word, image) {
    this.word = word;
    this.image = image;
}