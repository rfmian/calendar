<?php
$_SESSION['uid'] = 848;

if (isset($_SESSION['uid']) && isset($_GET['type']) && ($_SESSION['uid'] != "") && ($_GET['type'] != "")) {
	if ($_GET['type'] == "input") {
		if (isset($_POST['sid']) && isset($_POST['kategorie']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['wiederholen']) && isset($_POST['wiederholen_ende_boolean']) && isset($_POST['wiederholen_ende_datetime']) && isset($_POST['hash'])) {
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
			
			# check if already submitted
			/*$result = mysql_query("SELECT id FROM oeffnungszeiten_ausnahmen WHERE hash='".$_POST['hash']."'");
			while ($row = mysql_fetch_object($result)) {
				exit("schon_gespeichert");
			}*/
			
			echo "test";
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