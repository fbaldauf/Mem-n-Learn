var testCards = [ {
	id : 1,
	word : 'test',
	image : 'test'
}, {
	id : 2,
	word : 'test2',
	image : 'test2'
}, {
	id : 3,
	word : 'test3',
	image : 'test3'
} ];

QUnit.test("game init parameters", function(assert) {
	var expectedBoard = 'testboard';
	var expectedCards = [ 'abc' ];
	var expectedCardContainer = 'testcontainer';
	assert.notEqual(game.data.board, expectedBoard);

	game.init({
		board : expectedBoard,
		cards : expectedCards,
		cardContainer : expectedCardContainer
	});

	assert.equal(game.data.board, expectedBoard);
	assert.equal(game.data.cards, expectedCards);
	assert.equal(game.data.cardContainer, expectedCardContainer);
});

QUnit.test("game init start values", function(assert) {
	game.init();

	assert.equal(game.data.turn, 0);
	assert.equal(game.data.openCards.length, 0);
});

QUnit.test("add Cards", function(assert) {
	game.init({
		cards : testCards,
		board : $('<div id="fixture" />'),
		cardContainer : '<div class="ganeona" />'
	});

	assert.equal($(game.data.board).children().length, testCards.length * 2);

});

QUnit.test("set openCards on click", function(assert) {
	game.init({
		cards : testCards,
		board : $('<div id="fixture" />'),
		cardContainer : '<div class="ganeona">tile</div>'
	});

	var expected = game.data.openCards.length + 1;

	$($(game.data.board).children()[0]).click();
	assert.ok(game.data.openCards.length, expected);

});


