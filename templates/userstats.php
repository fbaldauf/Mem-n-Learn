
<?php



$user = 'root';
$pass = '';
$db = 'julian1828';
$sessionuserName = $_SESSION['username'];
$sql = "SELECT userID FROM `user` WHERE name = \"Julian\"";
$db = new mysqli("localhost", $user, $pass, $db) or die("Unable");
$rowuserID = mysqli_query($db, $sql);

while($row = mysqli_fetch_object($rowuserID))
{
	
	$sessionuserID = $row->userID;
}
$sql = "SELECT date,totaltime FROM `result` WHERE F_userID = $sessionuserID ORDER By date asc";
$ergebnistabellerow = mysqli_query($db, $sql);

while($row = mysqli_fetch_object($ergebnistabellerow))
{
	$tmpdate = $row->date;
	$splitdate = explode("-", $tmpdate);
	$graphtab[] = array('Date' => $splitdate[2].".".$splitdate[1].".".$splitdate[0],
			'zeit'   => $row->totaltime);
}


echo '
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load(\'current\', {\'packages\':[\'corechart\']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
		';

echo '[\'Datum\', \'Zeit\'],';
foreach ($graphtab as $iterator){
	echo "['" . $iterator['Date'] . "'," . $iterator['zeit'] . "],";
}

/*        ['2004',  1000],
          [\'2005\',  1170],
          [\'2006\',  660],
          [\'2007\',  1030]*/

echo ' 
          		
        ]);

        var options = {
          title: \'Zeitverlauf\',
          curveType: \'function\',
          legend: { position: \'bottom\' }
        };

        var chart = new google.visualization.LineChart(document.getElementById(\'curve_chart\'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div align="center">
		';




echo "<h1>Dies sind die Ergebnise von $sessionuserName!</h1>";


$sql = "SELECT MIN(totaltime) as min\n"
		. "FROM `result`\n"
		. "WHERE F_userID = $sessionuserID";
$best = mysqli_query($db, $sql);
while($row = mysqli_fetch_object($best))
		{
			echo "<strong>Dein schnellstes Spiel: " . $row->min . "</strong><BR>";
		}

$sql = "SELECT COUNT(F_userID) AS Anzahl FROM `result` WHERE F_userID = $sessionuserID";
$anzahlSpiele = mysqli_query($db, $sql);
while($row = mysqli_fetch_object($anzahlSpiele))
{
	echo "<br>Anzahl Spiele: $row->Anzahl";
}

		
echo "<br><br><strong>Deine Spiele:<br></strong>";
$sql = "SELECT date,totaltime FROM `result` WHERE F_userID = $sessionuserID ORDER By date asc";
$ergebnistabellerow = mysqli_query($db, $sql);
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
while($row = mysqli_fetch_object($ergebnistabellerow))
{
	$tmpdate = $row->date;
	$splitdate = explode("-", $tmpdate);
	echo '
			<tr>
				<td class="tg-h31u">'.$anzahl.".".'</td>
				<td class="tg-h31u">'.$splitdate[2] . "." . $splitdate[1] . "." . $splitdate[0].'</td>
				<td class="tg-h31u">'.$row->totaltime.'</td>
			</tr>
	';
	$anzahl = $anzahl + 1;
}
echo "</table>";

echo '
		<div align="center" id="curve_chart" style="width: 900px; height: 500px"></div>
		</div>
		</body>
		';


?>