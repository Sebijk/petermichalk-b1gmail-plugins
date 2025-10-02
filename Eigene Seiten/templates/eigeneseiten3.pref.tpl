	<fieldset>
		<legend>Was ist der unterschied zwischen Link Titel und Titel?</legend>
		
		<table>
			<tr>
				<td class="td2">Der Link Title ist der Text von dem Link im Men&uuml; und der Titel den Text der im Browser als Titel ausgegeben wird.<br>
				Zum Beispiel soll der Link nur "FAQ" hei&szlig;en aber der Titel "Frequently Asked Questions".</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Was f&uuml;r Typen gibt es?</legend>
		
		<table>
			<tr>
				<td class="td2">Es gibt drei Typen, "Eingeloggt", "Nicht eingeloggt" und "Beide". Bei "Eingeloggt" wird der Link nach dem einloggen oben im Men&uuml; angezeigt , auch steht die Seite nur eingeloggten Benutzern zur verf&uuml;gung.
				Bei "Nicht eingeloggt" wird der Link unten im Men&uuml; angezeigt und die Seite steht allen Leuten zur Verf&uuml;gung.<br>
				Bei "Beide" ist die Kombination von beiden Typen.</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Warum klappt das mit dem Icon nicht?</legend>
		
		<table>
			<tr>
				<td class="td2">Das Icon wird nur im Men&uuml; des eingeloggten Bereichs angezeigt.<br>
				Die Datei muss im folgendem Ordner liegen: /templates/&lt;ihr Template&gt;/images/li/<br>
				Dar&uuml;ber hinaus muss die Datei tab_ico_&lt;Icon&gt;.png als Namen tragen.</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Was ist die Sidebar?</legend>
		
		<table>
			<tr>
				<td class="td2">Die Sidebar wird nur im eingeloggten Bereich angezeigt und ist das rechte Men&uuml;.<br>
				Es stehen alle standard Sidebars zur Verf&uuml;gung:<br><br>
				<b>email</b> - enth&auml;lt das Men&uuml; des E-Mail-Bereichs<br>
				<b>organizer</b> - enth&auml;lt das Men&uuml; des Organizer-Bereichs<br>
				<b>prefs</b> - enth&auml;lt das Men&uuml; des Einstellungs-Bereichs<br>
				<b>start</b> - dieses enth&auml;lt das Men&uuml; der Startseite vom eingeloggten Bereich<br>
				<b>sms</b> - enth&auml;lt das Men&uuml; des SMS-Bereichs<br>
				<b>webdisk</b> - enth&auml;lt das Men&uuml; des Webdisk-Bereichs<br><br>
				Nat&uuml;rlich k&ouml;nnen Sie in dem Ordner /templates/&lt;ihr Template&gt;/li auch eigene Sidebars (&lt;name&gt;.sidebar.tpl) anlegen und im Script eintragen.</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Was ist die Toolbar?</legend>
		
		<table>
			<tr>
				<td class="td2">Die Toolbar wird nur im eingeloggten Bereich angezeigt und ist die obere Anzeige zwischen dem Men&uuml; und der Seite.<br>
				Es stehen alle standard Toolbars zur Verf&uuml;gung:<br><br>
				<b>email</b> - enth&auml;lt die Anzeige des verf&uuml;gbaren Speicherplatzes.<br>
				<b>sms</b> - enth&auml;lt die Anzeige der verf&uuml;gbaren Credits.<br>
				<b>webdisk</b> - enth&auml;lt die Anzeige des verf&uuml;gbaren Speicherplatzes und des verf&uuml;gbaren Traffics<br><br>
				Nat&uuml;rlich k&ouml;nnen Sie in dem Ordner /templates/&lt;ihr Template&gt;/li auch eigene Toolbars (&lt;name&gt;.toolbar.tpl) anlegen und im Script eintragen.</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Eine Seite einer Gruppe zuordnen?</legend>
		
		<table>
			<tr>
				<td class="td2">Sie k&ouml;nnen auch sagen, es soll nur einer Gruppe einen bestimmte Seite sehen.<br>
				W&auml;hlen Sie dazu die entsprechende Gruppe aus. Nur dieser Gruppe wird der Link und die Seite angezeigt.</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Tab Reihenfolge</legend>
		
		<table>
			<tr>
				<td class="td2">Mit der Tab Reihenfolge k&ouml;nnen Sie w&auml;hlen wo im Men&uuml; des eingeloggten Bereichs angezeigt, das Tab angezeigt werden soll.<br>
				hinter <b>start</b> - 100+<br>
				hinter <b>email</b> - 200+<br>
				hinter <b>sms</b> - 300+<br>
				hinter <b>organizer</b> - 400+<br>
				hinter <b>webdisk</b> - 500+<br>
				hinter <b>prefs</b> - 600+</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Variablen</legend>
		
		<table>
			<tr>
				<td class="td2">Sie wollen einen Benutzer mit Namen auf der Seite ansprechen? Dann f&uuml;gen Sie an die jeweilige Stelle folgen Ausdruck:<br>
				Vorname - [vorname]<br>
				Nachname - [nachname]<br>
				E-Mailadresse - [email]<br>
				Gruppenname - [gruppenname]</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Ver&ouml;ffentlichen</legend>
		
		<table>
			<tr>
				<td class="td2">Hiermit k&ouml;nnen Sie bestimmen ob eine Seite angezeigt werden soll oder nicht.<br>
				Wollen Sie die Seite erst einmal erstellen und sp&auml;ter verfeinern? Kein Problem, ver&ouml;ffentlichen Sie die Seite einfach sp&auml;ter.</td>
			</tr>
		</table>
	</fieldset>