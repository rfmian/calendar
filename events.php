<?php
#header('Content-type: application/json');
$_SESSION['uid'] = 848;

if (isset($_SESSION['uid']) && isset($_GET['start']) && isset($_GET['end'])) {
	mysql_connect("XXXXX","XXXXX","XXXXX");
	mysql_select_db("XXXXX");
	mysql_query("SET NAMES 'utf8'");
	
	# 0 - Initialisierung
	$list = array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So");
	foreach ($list as $counter => $day) {
		$week_dates[$day] = date_create($_GET['start']." 00:00:00");
		date_add($week_dates[$day],date_interval_create_from_date_string($counter." days"));
	}
	function giveWochentag($datetime) {
		switch (date_format($datetime,"N")) {
			case 1:
				return "Mo";
				break;
			case 2:
				return "Di";
				break;
			case 3:
				return "Mi";
				break;
			case 4:
				return "Do";
				break;
			case 5:
				return "Fr";
				break;
			case 6:
				return "Sa";
				break;
			case 7:
				return "So";
				break;
			default:
				return "ERROR";
				break;
		}
	}
	$zeitspannen_ende = clone $week_dates['So'];
	date_add($zeitspannen_ende,date_interval_create_from_date_string("1 day"));
	$oeffnungszeiten_geoeffnet = array();
	$events = array();
	
	# 1.1 - AUSNAHMEÖFFNUNGSZEITEN - NICHT WIEDERHOLT
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='oeffnungszeit' && wiederholen='nie' && (start BETWEEN '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."' AND '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		if (!array_key_exists(giveWochentag(date_create($row->start)),$oeffnungszeiten_geoeffnet)) {
			if ($row->start == $row->end) {
				$end = date_create($row->start);
				date_add($end,date_interval_create_from_date_string("1 day"));
				
				$oeffnungszeiten_geoeffnet[giveWochentag(date_create($row->start))] = array(
					"title" => "Geschlossen", 
					"className" => "man_oeffnungszeiten_einm",
					"type" => "geschlossen",
					"start" => $row->start,
					"end" => date_format($end, "Y-m-d H:i:s"),
					"id" => $row->id
				);
			} else {
				$oeffnungszeiten_geoeffnet[giveWochentag(date_create($row->start))] = array(
					"title" => "Geschlossen", 
					"className" => "man_oeffnungszeiten_einm",
					"type" => "geoeffnet",
					"offen_start" => $row->start,
					"offen_end" => $row->end,
					"id" => $row->id
				);
			}
		}
	}
	
	# 1.2.1 - AUSNAHMEÖFFNUNGSZEITEN - TÄGLICH WIEDERHOLT
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='oeffnungszeit' && wiederholen='taeglich' && wiederholen_ende_boolean='true' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') && (wiederholen_ende_datetime >= '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$d1 = date_create(date_format(date_create($row->start),"Y-m-d ")." 00:00:00");
		$d2 = date_create(date_format(date_create($row->wiederholen_ende_datetime),"Y-m-d ")." 00:00:00");
		if ($d1 < $week_dates['Mo']) {
			if ($d2 < $zeitspannen_ende) {
				for ($i = clone $week_dates['Mo']; $i <= $d2; date_add($i,date_interval_create_from_date_string("1 day"))) {
					if (!array_key_exists(giveWochentag($i),$oeffnungszeiten_geoeffnet)) {
						if ($row->start == $row->end) {
							$e1 = date_create(date_format($i,"Y-m-d")." 00:00:00");
							$e2 = clone $e1;
							date_add($e2,date_interval_create_from_date_string("1 day"));
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geschlossen",
								"start" => date_format($e1,"Y-m-d H:i:s"),
								"end" => date_format($e2,"Y-m-d H:i:s"),
								"id" => $row->id
							);
						} else {
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geoeffnet",
								"offen_start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
								"offen_end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
								"id" => $row->id
							);
						}
					}
				}
			} else {
				for ($i = clone $week_dates['Mo']; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
					if (!array_key_exists(giveWochentag($i),$oeffnungszeiten_geoeffnet)) {
						if ($row->start == $row->end) {
							$e1 = date_create(date_format($i,"Y-m-d")." 00:00:00");
							$e2 = clone $e1;
							date_add($e2,date_interval_create_from_date_string("1 day"));
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geschlossen",
								"start" => date_format($e1,"Y-m-d H:i:s"),
								"end" => date_format($e2,"Y-m-d H:i:s"),
								"id" => $row->id
							);
						} else {
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geoeffnet",
								"offen_start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
								"offen_end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
								"id" => $row->id
							);
						}
					}
				}
			}
		} else {
			if ($d2 < $zeitspannen_ende) {
				for ($i = clone $d1; $i <= $d2; date_add($i,date_interval_create_from_date_string("1 day"))) {
					if (!array_key_exists(giveWochentag($i),$oeffnungszeiten_geoeffnet)) {
						if ($row->start == $row->end) {
							$d1 = date_create(date_format($i,"Y-m-d")." 00:00:00");
							$d2 = clone $d1;
							date_add($d2,date_interval_create_from_date_string("1 day"));
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geschlossen",
								"start" => date_format($d1,"Y-m-d H:i:s"),
								"end" => date_format($d2,"Y-m-d H:i:s"),
								"id" => $row->id
							);
						} else {
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geoeffnet",
								"offen_start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
								"offen_end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
								"id" => $row->id
							);
						}
					}
				}
			} else {
				for ($i = clone $d1; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
					if (!array_key_exists(giveWochentag($i),$oeffnungszeiten_geoeffnet)) {
						if ($row->start == $row->end) {
							$e1 = date_create(date_format($i,"Y-m-d")." 00:00:00");
							$e2 = clone $e1;
							date_add($e2,date_interval_create_from_date_string("1 day"));
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geschlossen",
								"start" => date_format($e1,"Y-m-d H:i:s"),
								"end" => date_format($e2,"Y-m-d H:i:s"),
								"id" => $row->id
							);
						} else {
							$oeffnungszeiten_geoeffnet[giveWochentag($i)] = array(
								"title" => "Geschlossen", 
								"className" => "man_oeffnungszeiten_wied",
								"type" => "geoeffnet",
								"offen_start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
								"offen_end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
								"id" => $row->id
							);
						}
					}
				}
			}
		}
	}
	
	# 1.2.2 - AUSNAHMEÖFFNUNGSZEITEN - WÖCHENTLICH WIEDERHOLT
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='oeffnungszeit' && wiederholen='woechentlich' && wiederholen_ende_boolean='true' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') && (wiederholen_ende_datetime >= '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$start_day = date_create(date_format(date_create($row->start),"Y-m-d")." 00:00:00");
		$end_day = date_create(date_format(date_create($row->wiederholen_ende_datetime),"Y-m-d")." 00:00:00");
		
		if (!array_key_exists(giveWochentag($start_day),$oeffnungszeiten_geoeffnet)) {
			if ($start_day >= $week_dates['Mo']) {
				if ($row->start == $row->end) {
					$d = date_create($row->start);
					date_add($d,date_interval_create_from_date_string("1 day"));
				
					$oeffnungszeiten_geoeffnet[giveWochentag($start_day)] = array(
						"title" => "Geschlossen", 
						"className" => "man_oeffnungszeiten_wied",
						"type" => "geschlossen",
						"start" => $row->start,
						"end" => date_format($d, "Y-m-d H:i:s"),
						"id" => $row->id
					);
				} else {
					$oeffnungszeiten_geoeffnet[giveWochentag($start_day)] = array(
						"title" => "Geschlossen", 
						"className" => "man_oeffnungszeiten_wied",
						"type" => "geoeffnet",
						"offen_start" => $row->start,
						"offen_end" => $row->end,
						"id" => $row->id
					);
				}
			} else {
				while (giveWochentag($end_day) != giveWochentag($start_day)) {
					date_sub($end_day,date_interval_create_from_date_string("1 day"));
				}
				
				if ($end_day >= $week_dates['Mo']) {
					if ($row->start == $row->end) {
						$d = clone $week_dates[giveWochentag($start_day)];
						date_add($d,date_interval_create_from_date_string("1 day"));
					
						$oeffnungszeiten_geoeffnet[giveWochentag($start_day)] = array(
							"title" => "Geschlossen", 
							"className" => "man_oeffnungszeiten_wied",
							"type" => "geschlossen",
							"start" => date_format($week_dates[giveWochentag($start_day)], "Y-m-d H:i:s"),
							"end" => date_format($d, "Y-m-d H:i:s"),
							"id" => $row->id
						);
					} else {
						$oeffnungszeiten_geoeffnet[giveWochentag($start_day)] = array(
							"title" => "Geschlossen", 
							"className" => "man_oeffnungszeiten_wied",
							"type" => "geoeffnet",
							"offen_start" => date_format(date_create(date_format($week_dates[giveWochentag($start_day)],"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
							"offen_end" => date_format(date_create(date_format($week_dates[giveWochentag($start_day)],"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
							"id" => $row->id
						);
					}
				}
			}
		}
	}
		
	# 2 - REGELÖFFNUNGSZEITEN
	$result = mysql_query("SELECT * FROM oeffnungszeiten WHERE sid='".$_SESSION['uid']."'");
	while ($row = mysql_fetch_object($result)) {
		if (!array_key_exists($row->wochentag,$oeffnungszeiten_geoeffnet)) {
			$oeffnungszeiten_geoeffnet[$row->wochentag] = array(
				"title" => "Geschlossen", 
				"className" => "auto_oeffnungszeiten",
				"type" => "geoeffnet",
				"offen_start" => date_format(date_create(date_format($week_dates[$row->wochentag],"Y-m-d")." ".$row->von_std.":".$row->von_min.":00"),"Y-m-d H:i:s"),
				"offen_end" => date_format(date_create(date_format($week_dates[$row->wochentag],"Y-m-d")." ".$row->bis_std.":".$row->bis_min.":00"),"Y-m-d H:i:s")
			);
		}
	}
	
	# 3 ÖFFNUNGSZEITEN IN EVENTS[] EINFÜGEN
	foreach ($week_dates as $day => $date) {
		$next_day = clone $week_dates[$day];
		date_add($next_day,date_interval_create_from_date_string("1 day"));
			
		if (array_key_exists($day,$oeffnungszeiten_geoeffnet)) {
			if ($oeffnungszeiten_geoeffnet[$day]['type'] == "geschlossen") {
				$events[] = $oeffnungszeiten_geoeffnet[$day];
			} else {
				if (date_create($oeffnungszeiten_geoeffnet[$day]['offen_start']) > $week_dates[$day]) {
					$oeffnungszeiten_geoeffnet[$day]['start'] = date_format($week_dates[$day],"Y-m-d H:i:s");
					$oeffnungszeiten_geoeffnet[$day]['end'] = $oeffnungszeiten_geoeffnet[$day]['offen_start'];
					$events[] = $oeffnungszeiten_geoeffnet[$day];
				}
				if (date_create($oeffnungszeiten_geoeffnet[$day]['offen_end']) < $next_day) {
					$oeffnungszeiten_geoeffnet[$day]['start'] = $oeffnungszeiten_geoeffnet[$day]['offen_end'];
					$oeffnungszeiten_geoeffnet[$day]['end'] = date_format($next_day,"Y-m-d H:i:s");
					$events[] = $oeffnungszeiten_geoeffnet[$day];
				}
			}
		} else {
			$oeffnungszeiten_geoeffnet[$day] = array(
				"title" => "Geschlossen", 
				"className" => "auto_oeffnungszeiten",
				"type" => "geschlossen",
				"start" => date_format($date,"Y-m-d H:i:s"),
				"end" => date_format($next_day,"Y-m-d H:i:s")
			);
			$events[] = $oeffnungszeiten_geoeffnet[$day];
		}
	}
	
	# 3.1 - SPERRZEIT - NICHT WIEDERHOLT
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='geschlossen' && wiederholen='nie' && (start BETWEEN '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."' AND '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$events[] = array(
			"title" => "Geschlossen", 
			"className" => "blockierungszeit_einm",
			"editable" => true,
			"start" => $row->start,
			"end" => $row->end,
			"id" => $row->id
		);
	}
	
	# 3.2.1.1 - SPERRZEIT - TÄGLICH WIEDERHOLT - MIT ENDE
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='geschlossen' && wiederholen='taeglich' && wiederholen_ende_boolean='true' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') && (wiederholen_ende_datetime >= '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$d1 = date_create(date_format(date_create($row->start),"Y-m-d ")." 00:00:00");
		$d2 = date_create(date_format(date_create($row->wiederholen_ende_datetime),"Y-m-d ")." 00:00:00");
		if ($d1 < $week_dates['Mo']) {
			if ($d2 < $zeitspannen_ende) {
				for ($i = clone $week_dates['Mo']; $i <= $d2; date_add($i,date_interval_create_from_date_string("1 day"))) {
					$events[] = array(
						"title" => "Geschlossen", 
						"className" => "blockierungszeit_wied",
						"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
						"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
						"id" => $row->id
					);
				}
			} else {
				for ($i = clone $week_dates['Mo']; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
					$events[] = array(
						"title" => "Geschlossen", 
						"className" => "blockierungszeit_wied",
						"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
						"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
						"id" => $row->id
					);
				}
			}
		} else {
			if ($d2 < $zeitspannen_ende) {
				for ($i = clone $d1; $i <= $d2; date_add($i,date_interval_create_from_date_string("1 day"))) {
					$events[] = array(
						"title" => "Geschlossen", 
						"className" => "blockierungszeit_wied",
						"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
						"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
						"id" => $row->id
					);
				}
			} else {
				for ($i = clone $d1; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
					$events[] = array(
						"title" => "Geschlossen", 
						"className" => "blockierungszeit_wied",
						"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
						"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
						"id" => $row->id
					);
				}
			}
		}
	}
	
	# 3.2.1.2 - SPERRZEIT - TÄGLICH WIEDERHOLT - OHNE ENDE
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='geschlossen' && wiederholen='taeglich' && wiederholen_ende_boolean='false' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$d1 = date_create(date_format(date_create($row->start),"Y-m-d ")." 00:00:00");
		if ($d1 < $week_dates['Mo']) {
			for ($i = clone $week_dates['Mo']; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
				$events[] = array(
					"title" => "Geschlossen", 
					"className" => "blockierungszeit_wied",
					"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
					"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
					"id" => $row->id
				);
			}
		} else {
			for ($i = clone $d1; $i <= $week_dates['So']; date_add($i,date_interval_create_from_date_string("1 day"))) {
				$events[] = array(
					"title" => "Geschlossen", 
					"className" => "blockierungszeit_wied",
					"start" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
					"end" => date_format(date_create(date_format($i,"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
					"id" => $row->id
				);
			}
		}
	}
	
	# 3.2.2.1 - SPERRZEIT - WÖCHENTLICH WIEDERHOLT - MIT ENDE
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='geschlossen' && wiederholen='woechentlich' && wiederholen_ende_boolean='true' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') && (wiederholen_ende_datetime >= '".date_format($week_dates['Mo'], "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$start_day = date_create(date_format(date_create($row->start),"Y-m-d")." 00:00:00");
		$end_day = date_create(date_format(date_create($row->wiederholen_ende_datetime),"Y-m-d")." 00:00:00");
		
		if ($start_day >= $week_dates['Mo']) {
			$events[] = array(
				"title" => "Geschlossen", 
				"className" => "blockierungszeit_wied",
				"start" => $row->start,
				"end" => $row->end,
				"id" => $row->id
			);
		} else {
			while (giveWochentag($end_day) != giveWochentag($start_day)) {
				date_sub($end_day,date_interval_create_from_date_string("1 day"));
			}
			
			if ($end_day >= $week_dates['Mo']) {
				$events[] = array(
					"title" => "Geschlossen", 
					"className" => "blockierungszeit_wied",
					"start" => date_format(date_create(date_format($week_dates[giveWochentag($start_day)],"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
					"end" => date_format(date_create(date_format($week_dates[giveWochentag($start_day)],"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
					"id" => $row->id
				);
			}
		}
	}
	
	# 3.2.2.2 - SPERRZEIT - WÖCHENTLICH WIEDERHOLT - OHNE ENDE
	$result = mysql_query("SELECT * FROM oeffnungszeiten_ausnahmen WHERE sid='".$_SESSION['uid']."' && kategorie='geschlossen' && wiederholen='woechentlich' && wiederholen_ende_boolean='false' && (start < '".date_format($zeitspannen_ende, "Y-m-d H:i:s")."') ORDER BY id");
	while ($row = mysql_fetch_object($result)) {
		$wochentag = giveWochentag(date_create($row->start));
		$events[] = array(
			"title" => "Geschlossen", 
			"className" => "blockierungszeit_wied",
			"start" => date_format(date_create(date_format($week_dates[$wochentag],"Y-m-d ").date_format(date_create($row->start),"H:i:s")),"Y-m-d H:i:s"),
			"end" => date_format(date_create(date_format($week_dates[$wochentag],"Y-m-d ").date_format(date_create($row->end),"H:i:s")),"Y-m-d H:i:s"),
			"id" => $row->id
		);
	}
	
	# 5 EVENTS[] AUSGEBEN
	echo json_encode($events);
}
?>