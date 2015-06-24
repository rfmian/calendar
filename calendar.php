<?php
$_SESSION['uid'] = 848;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='fullcalendar.css' rel='stylesheet' />
<link href='fullcalendar.print.css' rel='stylesheet' media='print' />
<script src="fullcalendar-2.3.1/lib/moment.min.js"></script>
<script src="fullcalendar-2.3.1/lib/jquery.min.js"></script>
<script src="fullcalendar-2.3.1/fullcalendar.js"></script>
<script src="fullcalendar-2.3.1/lang-all.js"></script>
<script src="jquery-popup-overlay-gh-pages/jquery.popupoverlay.js"></script>
<script>
	popup_open = false;
	function randomString() {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = 40;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
		return randomstring;
	}
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: ''
			},
			height: 1150,
			lang: 'de',
			defaultView: 'agendaWeek',
			selectable: true,
			eventClick: function (event, jsEvent, view) {
				if (!popup_open) {
					popup_open = true;
					if (event.className == 'auto_oeffnungszeiten') {
						var voreingestellt = '';
						var offen_start = '';
						var offen_end = '';
						if (event.type == "geoeffnet") {
							offen_start = $.fullCalendar.moment(event.offen_start).format();
							offen_end = $.fullCalendar.moment(event.offen_end).format();
							voreingestellt = $.fullCalendar.moment(event.offen_start).format('HH:mm') + ' - ' + $.fullCalendar.moment(event.offen_end).format('HH:mm') + ' Uhr';
						} else {
							voreingestellt = 'geschlossen';
						}
						var auto_oeffnungszeiten_bearbeiten = document.createElement('div');
						auto_oeffnungszeiten_bearbeiten.innerHTML = '<h1 style="margin-top:0;text-align:center;">Öffnungszeiten bearbeiten</h1><p>Hier können Sie die Öffnungszeiten für einzelne Tage ändern, zum Beispiel aufgrund von Feiertagen. Möchten Sie die generellen Öffnungszeiten ändern? Tun Sie dies bitte im <a href="/login">Loginbereich</a>.</p><p id="auto_oeffnungszeiten_bearbeiten_fehlermeldungsausgabe" style="color:red;"></p><div><strong>Tag: ' + event.start.format('DD.MM.YYYY') + '</strong><br /><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_voreingestellt"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen" id="auto_oeffnungszeiten_bearbeiten_optionen_voreingestellt" onclick="document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen\').style = \'display:none;\';document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen\').style = \'display:none;\';" checked="checked" />Voreingestellte verwenden: ' + voreingestellt + '</label><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen" id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen" onclick="document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen\').style = \'display:none;\';document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen\').style = \'display:block;\';" />Als geschlossen festlegen</label><br /><div id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen" style="display:none;">Wiederholen<br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_nie"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_nie" />nie</label><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich" />täglich bis zum&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_tag"><?php for ($i = 1; $i <= 31; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('j')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_monat"><?php for ($i = 1; $i <= 12; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('n')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_jahr"><?php for ($i = date("Y"); $i <= (date("Y")+10); $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('Y')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select></label><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich" />wöchentlich bis zum&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_tag"><?php for ($i = 1; $i <= 31; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('j')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_monat"><?php for ($i = 1; $i <= 12; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('n')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_jahr"><?php for ($i = date("Y"); $i <= (date("Y")+10); $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('Y')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select></label></div><label for="auto_oeffnungszeiten_bearbeiten_optionen_andere"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen" id="auto_oeffnungszeiten_bearbeiten_optionen_andere" onclick="document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen\').style = \'display:block;\';document.getElementById(\'auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen\').style = \'display:none;\';" />Spezielle festlegen:&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_von"><?php for ($i = 0; $i < 24; $i++) {echo '<option id="auto_oeffnungszeiten_bearbeiten_optionen_andere_von_'.(($i < 10) ? '0'.$i : $i).'00" value="'.(($i < 10) ? '0'.$i : $i).':00">'.(($i < 10) ? '0'.$i : $i).':00</option><option id="auto_oeffnungszeiten_bearbeiten_optionen_andere_von_'.(($i < 10) ? '0'.$i : $i).'30" value="'.(($i < 10) ? '0'.$i : $i).':30">'.(($i < 10) ? '0'.$i : $i).':30</option>';}?></select>&nbsp;-&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_bis"><?php for ($i = 0; $i < 24; $i++) {echo '<option id="auto_oeffnungszeiten_bearbeiten_optionen_andere_bis_'.(($i < 10) ? '0'.$i : $i).'00" value="'.(($i < 10) ? '0'.$i : $i).':00">'.(($i < 10) ? '0'.$i : $i).':00</option><option id="auto_oeffnungszeiten_bearbeiten_optionen_andere_bis_'.(($i < 10) ? '0'.$i : $i).'30" value="'.(($i < 10) ? '0'.$i : $i).':30">'.(($i < 10) ? '0'.$i : $i).':30</option>';}?></select>Uhr</label><br /><div id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen" style="display:none;">Wiederholen<br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_nie"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_nie" />nie</label><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich" />täglich bis zum&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_tag"><?php for ($i = 1; $i <= 31; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('j')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_monat"><?php for ($i = 1; $i <= 12; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('n')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_jahr"><?php for ($i = date("Y"); $i <= (date("Y")+10); $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('Y')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select></label><br /><label for="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich"><input type="radio" name="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen" id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich" />wöchentlich bis zum&nbsp;<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_tag"><?php for ($i = 1; $i <= 31; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('j')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_monat"><?php for ($i = 1; $i <= 12; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('n')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_jahr"><?php for ($i = date("Y"); $i <= (date("Y")+10); $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('Y')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select></label></div><button class="abbrechen" onclick="auto_oeffnungszeiten_bearbeiten_abbrechen()">Abbrechen</button><button class="speichern" onclick="auto_oeffnungszeiten_bearbeiten_speichern(\'' + event.start.format("YYYY-MM-DD") + '\', \'' + randomString() + '\',\'' + event.type + '\', \'' + offen_start + '\', \'' + offen_end + '\')">Speichern</button></div>';
						auto_oeffnungszeiten_bearbeiten.id = 'auto_oeffnungszeiten_bearbeiten';
						auto_oeffnungszeiten_bearbeiten.className = 'popup_box';
						document.body.appendChild(auto_oeffnungszeiten_bearbeiten);
						if (event.type != 'geschlossen') {
							document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von_' + $.fullCalendar.moment(event.offen_start).format('HHmm')).selected = true;
							document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis_' + $.fullCalendar.moment(event.offen_end).format('HHmm')).selected = true;
						} else {
							document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von_0800').selected = true;
							document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis_1600').selected = true;
						}
						$('#auto_oeffnungszeiten_bearbeiten').popup({
						  transition: 'all 0.3s',
						  escape: false,
						  blur: false,
						  autozindex: true
						});
						$('#auto_oeffnungszeiten_bearbeiten').popup('show');
					} else if (event.className == 'man_oeffnungszeiten_einm' || event.className == 'man_oeffnungszeiten_wied' || event.className == 'blockierungszeit_einm' || event.className == 'blockierungszeit_wied') {
						var event_loeschen = $(document.createElement('div')).attr("id","event_loeschen").attr("class","popup_box").html('lädt...').load('/wp-content/plugins/meinstylist-kernfunktionen/fullcalendar-2.3.1/ajax.php?type=output&id=' + event.id + '&sid=<?php echo $_SESSION['uid']; ?>').appendTo(document.body);
						$('#event_loeschen').popup({
						  transition: 'all 0.3s',
						  escape: false,
						  blur: false,
						  autozindex: true
						});
						$('#event_loeschen').popup('show');
					} else {
						// TO DO
						popup_open = false;
					}
				}
			},
			selectHelper: false,
			selectOverlap: false,
			eventOverlap: false,
			select: function(start, end) {
				if (!popup_open) {
					popup_open = true;
					var temp = $.fullCalendar.moment(start.format("YYYY-MM-DD") + " 00:00:00");
					temp.add(1,'d');
					
					if ((end.format("YYYY-MM-DD HH:mm:ss") == temp.format("YYYY-MM-DD HH:mm:ss")) || (start.format("YYYY-MM-DD") == end.format("YYYY-MM-DD"))) {
						var zeit_blockieren = document.createElement('div');
						zeit_blockieren.innerHTML = '<h1 style="margin-top:0;text-align:center;">Zeit blockieren</h1><p>Hier können Sie eine bestimmte Zeitspanne blockieren, sodass während dieser Zeit keine Buchungen vorgenommen werden können. Sie können diese Funktion benutzen, um zum Beispiel Mittagspausen festzulegen.</p><p id="zeit_blockieren_fehlermeldungsausgabe" style="color:red;"></p><p><table><tr><td><strong>Zeit</strong></td><td>am ' + start.format("DD.MM.YYYY") + ' von ' + start.format("HH:mm") + ' bis ' + end.format("HH:mm") + ' Uhr</td></tr><tr><td><strong>Wiederholen</strong></td><td><select id="zeit_blockieren_wiederholen"><option value="nie" onclick="document.getElementById(\'zeit_blockieren_ende\').style = \'display:none;\';" selected="selected">nie</option><option value="taeglich" onclick="document.getElementById(\'zeit_blockieren_ende\').style = \'display:table-row;\';">täglich</option><option value="woechentlich" onclick="document.getElementById(\'zeit_blockieren_ende\').style = \'display:table-row;\';">wöchentlich</option></select></td></tr><tr id="zeit_blockieren_ende" style="display:none;"><td><strong>Ende</strong></td><td><label for="zeit_blockieren_ende_nie"><input type="radio" name="zeit_blockieren_ende" id="zeit_blockieren_ende_nie" checked="checked" />nie</label><br /><label for="zeit_blockieren_ende_datum"><input type="radio" name="zeit_blockieren_ende" id="zeit_blockieren_ende_datum" />bis&nbsp;<select id="zeit_blockieren_ende_datum_tag"><?php for ($i = 1; $i <= 31; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('j')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="zeit_blockieren_ende_datum_monat"><?php for ($i = 1; $i <= 12; $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('n')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select>.<select id="zeit_blockieren_ende_datum_jahr"><?php for ($i = date("Y"); $i <= (date("Y")+10); $i++) {echo '<option value="'.(($i < 10) ? '0'.$i : $i).'"'.(($i == date('Y')) ? ' selected="selected"' : '').'>'.(($i < 10) ? '0'.$i : $i).'</option>';} ?></select></label></td></tr></table><button class="abbrechen" onclick="zeit_blockieren_abbrechen()">Abbrechen</button><button class="speichern" onclick="zeit_blockieren_speichern(\'' + start.format("YYYY-MM-DD HH:mm:ss") + '\', \'' + end.format("YYYY-MM-DD HH:mm:ss") + '\', \'' + randomString() + '\')">Speichern</button></p>';
						zeit_blockieren.id = 'zeit_blockieren';
						zeit_blockieren.className = 'popup_box';
						document.body.appendChild(zeit_blockieren);
						$('#zeit_blockieren').popup({
						  autoopen: true,
						  transition: 'all 0.3s',
						  escape: false,
						  blur: false,
						  autozindex: true
						});
					} else {
						$('#calendar').fullCalendar('unselect');
						popup_open = false;
					}
				}
			},
			editable: false,
			events: 'http://meinstylist.com/wp-content/plugins/meinstylist-kernfunktionen/fullcalendar-2.3.1/events.php'
		});
	});
	
	function zeit_blockieren_abbrechen() {
		$('#zeit_blockieren').popup('hide');
		$('#zeit_blockieren').remove();
		$('#calendar').fullCalendar('unselect');
		popup_open = false;
	}
	function zeit_blockieren_speichern(start_string, end_string, hash) {
		var start = $.fullCalendar.moment(start_string);
		var end = $.fullCalendar.moment(end_string);
		
		var fehlermeldung = '';
		var speichern = [];
		speichern['wiederholen_ende_boolean'] = '';
		speichern['wiederholen_ende_datetime'] = $.fullCalendar.moment("0000-00-00 00:00:00").format();
		
		if (document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value != 'nie' && document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value != 'taeglich' && document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value != 'woechentlich') {
			fehlermeldung = 'Es ist ein unbekannter Fehler aufgetreten.';
		} else if (document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value == 'taeglich' || document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value == 'woechentlich') {
			if (document.getElementById('zeit_blockieren_ende_nie').checked != true && document.getElementById('zeit_blockieren_ende_datum').checked != true) {
				fehlermeldung = 'Es ist ein unbekannter Fehler aufgetreten.';
			} else if (document.getElementById('zeit_blockieren_ende_nie').checked == true) {
				fehlermeldung = '';
				speichern['wiederholen_ende_boolean'] = 'false';
			} else if (document.getElementById('zeit_blockieren_ende_datum').checked == true) {
				if (document.getElementById('zeit_blockieren_ende_datum_tag').options[document.getElementById('zeit_blockieren_ende_datum_tag').selectedIndex].value == '' || document.getElementById('zeit_blockieren_ende_datum_monat').options[document.getElementById('zeit_blockieren_ende_datum_monat').selectedIndex].value == '' || document.getElementById('zeit_blockieren_ende_datum_jahr').options[document.getElementById('zeit_blockieren_ende_datum_jahr').selectedIndex].value == '') {
					fehlermeldung = 'Es ist ein unbekannter Fehler aufgetreten.';
				} else {
					var m = $.fullCalendar.moment(document.getElementById('zeit_blockieren_ende_datum_jahr').options[document.getElementById('zeit_blockieren_ende_datum_jahr').selectedIndex].value + '-' + document.getElementById('zeit_blockieren_ende_datum_monat').options[document.getElementById('zeit_blockieren_ende_datum_monat').selectedIndex].value + '-' + document.getElementById('zeit_blockieren_ende_datum_tag').options[document.getElementById('zeit_blockieren_ende_datum_tag').selectedIndex].value);
					if (!m.isValid()) {
						fehlermeldung = 'Das Datum ist ungültig.';
					} else if (!m.isAfter(end)) {
						fehlermeldung = 'Das Ende der Wiederholung muss in der Zukunft liegen.';
					} else {
						fehlermeldung = '';
						speichern['wiederholen_ende_boolean'] = 'true';
						speichern['wiederholen_ende_datetime'] = $.fullCalendar.moment(document.getElementById('zeit_blockieren_ende_datum_jahr').options[document.getElementById('zeit_blockieren_ende_datum_jahr').selectedIndex].value + '-' + document.getElementById('zeit_blockieren_ende_datum_monat').options[document.getElementById('zeit_blockieren_ende_datum_monat').selectedIndex].value + '-' + document.getElementById('zeit_blockieren_ende_datum_tag').options[document.getElementById('zeit_blockieren_ende_datum_tag').selectedIndex].value).format();
					}
				}
			}
		} else {
			fehlermeldung = '';
		}
		
		if (fehlermeldung == '') {
			$.post('/wp-content/plugins/meinstylist-kernfunktionen/fullcalendar-2.3.1/ajax.php?type=input',{
				sid: <?php echo $_SESSION['uid'] ?>,
				kategorie: 'geschlossen',
				start: start.format("YYYY-MM-DD HH:mm:ss"),
				end: end.format("YYYY-MM-DD HH:mm:ss"),
				wiederholen: document.getElementById('zeit_blockieren_wiederholen').options[document.getElementById('zeit_blockieren_wiederholen').selectedIndex].value,
				wiederholen_ende_boolean: speichern['wiederholen_ende_boolean'],
				wiederholen_ende_datetime: speichern['wiederholen_ende_datetime'],
				hash: hash
			},function(data) {
				if (data == "ok") {
					$('#zeit_blockieren').popup('hide');
					$('#zeit_blockieren').remove();
					$('#calendar').fullCalendar('unselect');
					$('#calendar').fullCalendar('refetchEvents')
					popup_open = false;
				} else if (data == "schon_gespeichert") {
					document.getElementById('zeit_blockieren_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Sie haben diesen Datensatz bereits gespeichert.';
				} else {
					document.getElementById('zeit_blockieren_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Es ist ein unvorhergesehener Fehler eingetreten. Bitte versuchen Sie es später wieder.';
				}
			}).fail(function () {
				document.getElementById('zeit_blockieren_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Es ist ein Problem mit Ihrer Internetverbindung eingetreten. Bitte versuchen Sie es später wieder.';
			});
		} else {
			document.getElementById('zeit_blockieren_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> ' + fehlermeldung;
		}
	}
	function auto_oeffnungszeiten_bearbeiten_abbrechen() {
		$('#auto_oeffnungszeiten_bearbeiten').popup('hide');
		$('#auto_oeffnungszeiten_bearbeiten').remove();
		popup_open = false;
	}
	function auto_oeffnungszeiten_bearbeiten_speichern(day_string, hash, type, offen_start_string, offen_end_string) {	
		var speichern = [];
		speichern['start'] = '';
		speichern['end'] = '';
		speichern['wiederholen'] = '';
		speichern['wiederholen_ende_boolean'] = '';
		speichern['wiederholen_ende_datetime'] = '0000-00-00 00:00:00';
		
		var fehlermeldung = '';
		var abbrechen = false;
		
		if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_voreingestellt').checked) {
			abbrechen = true;
		} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen').checked) {
			var d = $.fullCalendar.moment(day_string);
			var d1 = $.fullCalendar.moment(d.format('YYYY-MM-DD') + ' 00:00:00')
			var d2 = $.fullCalendar.moment(d1)
			//d2.add(1,'d');
			
			if (type == "geschlossen") {
				fehlermeldung = '';
				abbrechen = true;
			} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_nie').checked) {
				fehlermeldung = '';
				speichern['start'] = d1.format('YYYY-MM-DD HH:mm:ss');
				speichern['end'] = d2.format('YYYY-MM-DD HH:mm:ss');
				speichern['wiederholen'] = 'nie';
			} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich').checked) {
				if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_tag').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_monat').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_jahr').selectedIndex].value == '') {
					fehlermeldung = 'Bitte füllen Sie alle Felder aus.';
				} else {
					var m = $.fullCalendar.moment(document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_jahr').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_monat').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_taeglich_tag').selectedIndex].value);
					if (!m.isValid()) {
						fehlermeldung = 'Das Datum ist ungültig.';
					} else if (!m.isAfter(d)) {
						fehlermeldung = 'Das Ende der Wiederholung muss in der Zukunft liegen.';
					} else {
						fehlermeldung = '';
						speichern['start'] = d1.format('YYYY-MM-DD HH:mm:ss');
						speichern['end'] = d2.format('YYYY-MM-DD HH:mm:ss');
						speichern['wiederholen'] = 'taeglich';
						speichern['wiederholen_ende_boolean'] = 'true';
						speichern['wiederholen_ende_datetime'] = m.format('YYYY-MM-DD HH:mm:ss');
					}
				}
			} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich').checked) {
				if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_tag').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_monat').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_jahr').selectedIndex].value == '') {
					fehlermeldung = 'Bitte füllen Sie alle Felder aus.';
				} else {
					var m = $.fullCalendar.moment(document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_jahr').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_monat').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen_woechentlich_tag').selectedIndex].value);
					if (!m.isValid()) {
						fehlermeldung = 'Das Datum ist ungültig.';
					} else if (!m.isAfter(d)) {
						fehlermeldung = 'Das Ende der Wiederholung muss in der Zukunft liegen.';
					} else {
						fehlermeldung = '';
						speichern['start'] = d1.format('YYYY-MM-DD HH:mm:ss');
						speichern['end'] = d2.format('YYYY-MM-DD HH:mm:ss');
						speichern['wiederholen'] = 'woechentlich';
						speichern['wiederholen_ende_boolean'] = 'true';
						speichern['wiederholen_ende_datetime'] = m.format('YYYY-MM-DD HH:mm:ss');
					}
				}
			} else {
				fehlermeldung = 'Bitte füllen Sie die Felder aus.';
			}
		} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere').checked) {
			var e = $.fullCalendar.moment(day_string);
			var e1 = $.fullCalendar.moment(e.format('YYYY-MM-DD') + ' ' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von').selectedIndex].value + ':00');
			var e2 = $.fullCalendar.moment(e.format('YYYY-MM-DD') + ' ' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis').selectedIndex].value + ':00');
			
			if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_von').selectedIndex].value == "" || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_bis').selectedIndex].value == "") {
				fehlermeldung = 'Bitte füllen Sie alle Felder aus.';
			} else if (!e2.isAfter(e1)) {
				fehlermeldung = 'Bitte geben Sie eine gültige Zeitspanne ein.';
			} else if (e1.format() == $.fullCalendar.moment(offen_start_string).format() && e2.format() == $.fullCalendar.moment(offen_end_string).format()) {
				fehlermeldung = '';
				abbrechen = true;
			} else {
				if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_nie').checked) {
					fehlermeldung = '';					
					speichern['start'] = e1.format('YYYY-MM-DD HH:mm:ss');
					speichern['end'] = e2.format('YYYY-MM-DD HH:mm:ss');
					speichern['wiederholen'] = 'nie';
				} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich').checked) {
					if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_tag').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_monat').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_jahr').selectedIndex].value == '') {
						fehlermeldung = 'Bitte füllen Sie alle Felder aus.';
					} else {
						var m = $.fullCalendar.moment(document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_jahr').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_monat').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_taeglich_tag').selectedIndex].value);
						if (!m.isValid()) {
							fehlermeldung = 'Das Datum ist ungültig.';
						} else if (!m.isAfter(d)) {
							fehlermeldung = 'Das Ende der Wiederholung muss in der Zukunft liegen.';
						} else {
							fehlermeldung = '';
							speichern['start'] = e1.format('YYYY-MM-DD HH:mm:ss');
							speichern['end'] = e2.format('YYYY-MM-DD HH:mm:ss');
							speichern['wiederholen'] = 'taeglich';
							speichern['wiederholen_ende_boolean'] = 'true';
							speichern['wiederholen_ende_datetime'] = m.format('YYYY-MM-DD HH:mm:ss');
						}
					}
				} else if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich').checked) {
					if (document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_tag').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_monat').selectedIndex].value == '' || document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_jahr').selectedIndex].value == '') {
						fehlermeldung = 'Bitte füllen Sie alle Felder aus.';
					} else {
						var m = $.fullCalendar.moment(document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_jahr').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_jahr').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_monat').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_monat').selectedIndex].value + '-' + document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_tag').options[document.getElementById('auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen_woechentlich_tag').selectedIndex].value);
						if (!m.isValid()) {
							fehlermeldung = 'Das Datum ist ungültig.';
						} else if (!m.isAfter(d)) {
							fehlermeldung = 'Das Ende der Wiederholung muss in der Zukunft liegen.';
						} else {
							fehlermeldung = '';
							speichern['start'] = e1.format('YYYY-MM-DD HH:mm:ss');
							speichern['end'] = e2.format('YYYY-MM-DD HH:mm:ss');
							speichern['wiederholen'] = 'woechentlich';
							speichern['wiederholen_ende_boolean'] = 'true';
							speichern['wiederholen_ende_datetime'] = m.format('YYYY-MM-DD HH:mm:ss');
						}
					}
				} else {
					fehlermeldung = 'Bitte füllen Sie die Felder aus.';
				}
			}
		} else {
			fehlermeldung = 'Es ist ein unbekannter Fehler aufgetreten.';
		}
		
		if (!abbrechen) {
			if (fehlermeldung == '') {				
				$.post('/wp-content/plugins/meinstylist-kernfunktionen/fullcalendar-2.3.1/ajax.php?type=input',{
					sid: <?php echo $_SESSION['uid'] ?>,
					kategorie: 'oeffnungszeit',
					start: speichern['start'],
					end: speichern['end'],
					wiederholen: speichern['wiederholen'],
					wiederholen_ende_boolean: speichern['wiederholen_ende_boolean'],
					wiederholen_ende_datetime: speichern['wiederholen_ende_datetime'],
					hash: hash
				},function(data) {
					if (data == "ok") {
						$('#auto_oeffnungszeiten_bearbeiten').popup('hide');
						$('#auto_oeffnungszeiten_bearbeiten').remove();
						$('#calendar').fullCalendar('refetchEvents')
						popup_open = false;
					} else if (data == "schon_gespeichert") {
						document.getElementById('auto_oeffnungszeiten_bearbeiten_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Sie haben diesen Datensatz bereits gespeichert.';
					} else {
						document.getElementById('auto_oeffnungszeiten_bearbeiten_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Es ist ein unvorhergesehener Fehler eingetreten. Bitte versuchen Sie es später wieder.';
					}
				}).fail(function () {
					document.getElementById('auto_oeffnungszeiten_bearbeiten_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> Es ist ein Problem mit Ihrer Internetverbindung eingetreten. Bitte versuchen Sie es später wieder.';
				});
			} else {
				document.getElementById('auto_oeffnungszeiten_bearbeiten_fehlermeldungsausgabe').innerHTML = '<strong>Fehler:</strong> ' + fehlermeldung;
			}
		} else {
			$('#auto_oeffnungszeiten_bearbeiten').popup('hide');
			$('#auto_oeffnungszeiten_bearbeiten').remove();
			popup_open = false;
		}
	}
</script>
<style>
body {
	margin: 10px;
	padding: 0;
	font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
	font-size: 14px;
}
#calendar {
	width: 100%;
}
.geschlossen_oeffnungszeiten {
	border-radius: 0;
}
.geschlossen_oeffnungszeiten:hover .fc-bg {
	background-color: #ccc;
	cursor: pointer;
}
.fc-unthemed .fc-today {
	background: #eeeeee;
}
.fc-highlight { /* when user is selecting cells */
	background: #ccc;
	opacity: .5;
	filter: alpha(opacity=50); /* for IE */
}
.fc-day-grid, .fc-divider {
	display: none;
}
.popup_box {
	transform: scale(0.8);
	padding: 25px 30px 25px 30px;
	background-color: #F5F5F5;
	border-radius: 5px;
	box-shadow: 0px 0px 10px black;
	max-width: 500px;
	box-sizing: border-box;
}
.popup_box td {
	min-width: 100px;
}
.popup_box button {
	width: 49.5%;
	box-sizing: border-box;
	margin-top: 20px;
	padding: 8px;
	border: none;
	color: white;
	border-radius: 4px;
}
.popup_box button.abbrechen {
	margin-right: 1%;
	background-color: #b8b8b8;
}
.popup_box button.abbrechen:hover {
	cursor: pointer;
	background-color: #a0a0a0;
}
.popup_box button.speichern {
	background-color: #373737;
}
.popup_box button.speichern:hover {
	cursor: pointer;
	background-color: #2B2B2B;
}
.popup_visible .popup_box {
	transform: scale(1);
}
#auto_oeffnungszeiten_bearbeiten #auto_oeffnungszeiten_bearbeiten_optionen_andere_wiederholen, #auto_oeffnungszeiten_bearbeiten #auto_oeffnungszeiten_bearbeiten_optionen_geschlossen_wiederholen {
	padding: 20px;
	margin: 10px 10px 10px 20px;
	background: #ccc;
}
.fc-event .fc-bg {
	opacity: 0.14
}
.auto_oeffnungszeiten {
	border: 1px solid #000000;
	background: #000000;
	border-radius: 0;
}
.auto_oeffnungszeiten:hover {
	cursor: pointer;
	opacity: 0.85
}
.man_oeffnungszeiten_einm {
	border: 1px solid #000000;
	background: #111111;
	border-radius: 0;
}
.man_oeffnungszeiten_einm:hover {
	cursor: pointer;
	opacity: 0.85
}
.man_oeffnungszeiten_wied {
	border: 1px solid #000000;
	background: #111111;
	border-left: 6px solid #111111;
	padding: 2px;
	border-radius: 0;
}
.man_oeffnungszeiten_wied:hover {
	cursor: pointer;
	opacity: 0.85
}
.blockierungszeit_einm {
	border: none;
	background: #ff0000;
	box-shadow: 0 0 2px black;
}
.blockierungszeit_einm:hover {
	cursor: pointer;
	opacity: 0.85
}
.blockierungszeit_wied {
	border: none;
	background: #ff0000;
	border-left: 6px solid #800000;
	padding: 2px;
	border-radius: 0;
	box-shadow: 0 0 2px black;
}
.blockierungszeit_wied:hover {
	cursor: pointer;
	opacity: 0.85
}
</style>
</head>
<body>
<div id='calendar' class="calendar"></div>
</body>
</html>
