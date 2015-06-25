<?php
$_SESSION['uid'] = 848;

if (isset($_SESSION['uid']) && isset($_GET['type']) && ($_SESSION['uid'] != "") && ($_GET['type'] != "")) {
	if ($_GET['type'] == "input") {
		if (isset($_POST['sid']) && ($_POST['sid'] == $_SESSION['uid']) && isset($_POST['kategorie']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['wiederholen']) && isset($_POST['wiederholen_ende_boolean']) && isset($_POST['wiederholen_ende_datetime']) && isset($_POST['hash'])) {
			mysql_connect("XXXXX","XXXXX","XXXXX");
			mysql_select_db("XXXXX");
			mysql_query("SET NAMES 'utf8'");
			
			# check if already submitted
			$result = mysql_query("SELECT id FROM oeffnungszeiten_ausnahmen WHERE hash='".$_POST['hash']."'");
			while ($row = mysql_fetch_object($result)) {
				exit("schon_gespeichert");
			}
			
			# save
			$sql = "
				INSERT INTO oeffnungszeiten_ausnahmen (
					sid,
					kategorie,
					start,
					end,
					wiederholen,
					wiederholen_ende_boolean,
					wiederholen_ende_datetime,
					hash
				) VALUES (
					'".$_SESSION['uid']."',
					'".$_POST['kategorie']."',
					'".$_POST['start']."',
					'".$_POST['end']."',
					'".$_POST['wiederholen']."',
					'".$_POST['wiederholen_ende_boolean']."',
					'".$_POST['wiederholen_ende_datetime']."',
					'".$_POST['hash']."'
				)
			";
			@mysql_query($sql);
			echo "ok";
		} else {
			echo "fehler";
		}
	} elseif ($_GET['type'] == "output") {
		if (isset($_GET['id']) && ($_GET['id'] != "")) {
			mysql_connect("XXXXX","XXXXX","XXXXX");
			mysql_select_db("XXXXX");
			mysql_query("SET NAMES 'utf8'");
			
			$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && id='".$_GET['id']."'");
			while ($row = mysql_fetch_object($result)) {
				$html = '';
				$html .= '
					<h1 style="margin-top:0;text-align:center;">';
						if ($row->kategorie == 'oeffnungszeit') {
							$html .= 'Öffnungszeiten-Ausnahme';
						} else {
							$html .= 'Blockierte Zeit';
						}
					$html .= '</h1>
					<p id="event_loeschen_fehlermeldungsausgabe" style="color:red;"></p>
					<p>
						<table>';
							if ($row->start != $row->end) {
								$html .= '
								<tr>
									<td>Tag</td>
									<td><strong>'.date_format(date_create($row->start),'d.m.Y').'</strong></td>
								</tr>
								<tr>
									<td>Anfang</td>
									<td>'.date_format(date_create($row->start),'H:i').' Uhr</td>
								</tr>
								<tr>
									<td>Ende</td>
									<td>'.date_format(date_create($row->end),'H:i').' Uhr</td>
								</tr>';
							} else {
								$html .= '
								<tr>
									<td>Tag</td>
									<td><strong>'.date_format(date_create($row->start),'d.m.Y').'</strong></td>
								</tr>
								<tr>
									<td>Zeit</td>
									<td>geschlossen</td>
								</tr>';
							}
							$html .= '
							<tr>
								<td>Wiederholen</td>
								<td>';
									if ($row->wiederholen == 'nie') {
										$html .= "nein";
									} elseif (($row->wiederholen == 'taeglich') && ($row->wiederholen_ende_boolean == 'false')) {
										$html .= "ja, täglich ohne Ende";
									} elseif (($row->wiederholen == 'taeglich') && ($row->wiederholen_ende_boolean == 'true')) {
										$html .= "ja, täglich bis zum ".date_format(date_create($row->wiederholen_ende_datetime),'d.m.Y');
									} elseif (($row->wiederholen == 'woechentlich') && ($row->wiederholen_ende_boolean == 'false')) {
										$html .= "ja, wöchentlich ohne Ende";
									} elseif (($row->wiederholen == 'woechentlich') && ($row->wiederholen_ende_boolean == 'true')) {
										$html .= "ja, woechentlich bis zum ".date_format(date_create($row->wiederholen_ende_datetime),'d.m.Y');
									}
								$html .= '</td>
							</tr>
						</table>
						
						<button class="abbrechen" onclick="event_loeschen_abbrechen()">
							Abbrechen
						</button><button class="loeschen" onclick="event_loeschen_ausfuehren('.$_GET['id'].')">
							Löschen
						</button>
					</p>
				';
				exit($html);
			}
		}
		echo '<h1 style="margin-top:0;text-align:center;">Fehler</h1><p id="event_loeschen_fehlermeldungsausgabe" style="color:red;">Es ist ein unbekanntes Problem aufgetreten. Bitte wiederholen Sie es später noch einmal.</p><button class="loeschen" onclick="event_loeschen_abbrechen()" style="width:100%;">Abbrechen</button>';
	} elseif ($_GET['type'] == 'loeschen') {
		if (isset($_POST['sid']) && isset($_POST['id']) && ($_POST['sid'] != '') && ($_POST['id'] != '') && ($_POST['sid'] == $_SESSION['uid'])) {
			mysql_connect("XXXXX","XXXXX","XXXXX");
			mysql_select_db("XXXXX");
			mysql_query("SET NAMES 'utf8'");
			
			$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && id='".$_POST['id']."'");
			while ($row = mysql_fetch_object($result)) {
				$sql = "DELETE FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && id='".$_POST['id']."' LIMIT 1";
				@mysql_query($sql);
				exit("ok");
			}
			echo 'schon_geloescht';
		} else {
			echo "fehler";
		}
	} else {
		echo "fehler";
	}
} else {
	echo "fehler";
}
?>