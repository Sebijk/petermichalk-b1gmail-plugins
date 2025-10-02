# smsTAN Plugin

**SMS-basierte Zwei-Faktor-Authentifizierung für b1gMail.**

Mit diesem Plugin können Benutzer sich sicher über SMS-TAN-Codes anmelden. Das System sendet einen Bestätigungscode per SMS an das hinterlegte Mobiltelefon des Benutzers, der zur Anmeldung eingegeben werden muss.

- **Sprache:** Deutsch
- **b1gMail Version:** 7.3.0
- **PHP Version:** 8.3

## Features

- **SMS-TAN-Authentifizierung:** Sichere Anmeldung mit SMS-Bestätigungscodes
- **Gruppenbasierte Aktivierung:** Plugin kann für bestimmte Benutzergruppen aktiviert werden
- **Kostenverwaltung:** Automatische Abrechnung der SMS-Kosten über das Credit-System
- **IP-Rate-Limiting:** Schutz vor Brute-Force-Angriffen durch IP-Sperrung
- **Audit-Log:** Vollständige Protokollierung aller SMS-Anfragen und Anmeldungen
- **Admin-Interface:** Umfassende Verwaltung und Konfiguration
- **Benutzer-Einstellungen:** Individuelle Aktivierung/Deaktivierung pro Benutzer

## Voraussetzungen

- **SMS-Service:** Konfigurierter SMS-Service in b1gMail
- **Mobiltelefon:** Benutzer müssen eine Handynummer hinterlegt haben
- **Credits:** Ausreichend Credits für SMS-Versand (falls aktiviert)

## Installation

1. Plugin-Ordner `smsTAN/` in das `plugins/` Verzeichnis von b1gMail kopieren
2. Im Adminbereich unter "Plugins" das Plugin aktivieren
3. SMS-Service konfigurieren (falls noch nicht geschehen)
4. Plugin-Einstellungen über "Plugins" → "smsTAN" anpassen