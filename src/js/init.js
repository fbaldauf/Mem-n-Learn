// Wird aufgerufen, sobald das HTML bereit ist
$(document).ready(
		function() {
			// Menü initialisiert
			menu.init({
				// Einzelne Menüpunkte
				items : $('.navbar').find('.navbar-brand').add($('.navbar').find('li')),
				
				// Container, der den Inhalt beinhaltet
				container : $('#content')
			});

			// Menü wird aktiv geschaltet, falls der Benutzer angemeldet ist
			menu.setActive($(document).data('loggedIn'));
		});