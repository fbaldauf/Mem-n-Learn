<h1>Dashboard</h1>
<p>Willkommen <?php echo ucfirst($this->_['username']); ?></p>

<p>Deine aktuelle Sprache ist: <?php echo ucfirst($_SESSION['config']->getLanguage()) ?></p>

<p>Bitte waehle deine Muttersprache/Anzeigesprache:</p>
<a href="lang-german"><img alt="German" src="templates/img/flags/png/Germany-01.png" height="20px"> German</a>
<a href="lang-english"><img alt="English" src="templates/img/flags/png/United Kingdom-01.png" height="20px"> English</a>
<a href="lang-french"><img alt="French" src="templates/img/flags/png/France-01.png" height="20px"> French</a>
<a href="lang-spanish"><img alt="Spanish" src="templates/img/flags/png/Spain-01.png" height="20px"> Spanish</a>
<a href="lang-pashto"><img alt="Pashto" src="templates/img/flags/png/Pakistan-01.png" height="20px"> Pashto</a>
