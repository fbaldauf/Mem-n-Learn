
<?php

echo '
    <div align="center">
		';

echo "<h1>Dies sind die Ergebnise von " . $_SESSION ['username'] . "!</h1>";

echo "Dein schnellstes Spiel: " . $this->_ ['data'] ['fastest'] . "<br />";

echo "Anzahl Spiele: " . sizeof ( $this->_ ['data'] ['games'] ) . '<br />';

echo "<br><br><strong>Deine Spiele:<br></strong>";

$anzahl = 1;
echo '
		<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-h31u{font-family:Arial, Helvetica, sans-serif !important;;vertical-align:top}
.tg .tg-ejgj{font-family:Verdana, Geneva, sans-serif !important;;vertical-align:top}
</style>
		';
echo '<table class="tg">';
echo "<tr>";
echo '<th class="tg-ejgj">Nr.</th>';
echo '<th class="tg-ejgj">Datum</th>';
echo '<th class="tg-ejgj">Zeit</th>';
echo "</tr>";
// while($row = mysqli_fetch_object($ergebnistabellerow))
foreach ( $this->_ ['data'] ['games'] as $row ) {
	
	// $tmpdate = $row->date;
	// $splitdate = explode ( "-", $tmpdate );
	echo '
 			<tr>
 				<td class="tg-h31u">' . $anzahl . "." . '</td>
 				<td class="tg-h31u">' . $row ['date'] . '</td>
 				<td class="tg-h31u">' . $row ['time'] . '</td>
 			</tr>
 	';
	$anzahl = $anzahl + 1;
}
echo "</table>";

echo '
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		';

?>