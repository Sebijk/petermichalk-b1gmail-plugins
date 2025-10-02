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

## Konfiguration

### Admin-Einstellungen

1. **Adminbereich** → **"Plugins"** → **"smsTAN"**
2. **Allgemeine Einstellungen:**
   - Absender-Nummer für SMS
   - SMS-Typ auswählen
   - Kostenübernahme konfigurieren (Benutzer oder System)

### Benutzergruppen

- Plugin kann für bestimmte Benutzergruppen aktiviert werden
- Über Gruppenoptionen "smsTAN?" aktivieren/deaktivieren

### Benutzer-Einstellungen

- Benutzer können das SMS-TAN-Verfahren individuell aktivieren/deaktivieren
- Einsehbar: Verbrauchte Credits der letzten 30 Tage
- SMS-Log der letzten 10 Anfragen

## Verwendung

### Für Benutzer

1. **SMS-TAN-Anmeldung aufrufen:** Login-Seite → "smsTAN" Link
2. **E-Mail-Adresse eingeben** und "Anfordern" klicken
3. **SMS-Code empfangen** auf dem hinterlegten Mobiltelefon
4. **TAN-Code eingeben** und anmelden

### Für Administratoren

1. **Logs einsehen:** Admin → "Plugins" → "smsTAN" → "Letzte Anmeldungen"
2. **Einstellungen verwalten:** SMS-Typ, Kostenübernahme, Absender-Nummer
3. **IP-Sperren überwachen:** Anzahl gesperrter IP-Adressen anzeigen

## Sicherheitsfeatures

- **TAN-Code-Gültigkeit:** Codes sind nur 15 Minuten gültig
- **Einmalige Verwendung:** Jeder TAN-Code kann nur einmal verwendet werden
- **IP-Rate-Limiting:** Nach 5 fehlgeschlagenen Versuchen wird die IP für 15 Minuten gesperrt
- **Gruppenbasierte Berechtigung:** Nur autorisierte Benutzer können das Verfahren nutzen
- **Audit-Trail:** Vollständige Protokollierung aller Aktivitäten

## Technische Details

- **Datenbank-Tabellen:**
  - `mod_smstan_keys`: Speichert aktive TAN-Codes
  - `mod_smstan_log`: Protokolliert SMS-Anfragen und Kosten
  - `mod_smstan_banip`: Verwaltet IP-Sperren

- **Automatische Bereinigung:** Cron-Job löscht abgelaufene Daten
- **Template-System:** Anpassbare SMS-Texte mit Platzhaltern
- **Credit-Integration:** Nahtlose Integration in das b1gMail-Credit-System

## Fehlerbehebung

### Häufige Probleme

1. **"Keine Handynummer hinterlegt":**
   - Benutzer muss Handynummer in den Einstellungen hinterlegen

2. **"Nicht genügend Credits":**
   - Benutzer-Credits aufladen oder Kostenübernahme auf System umstellen

3. **"SMS-Versand fehlgeschlagen":**
   - SMS-Service-Konfiguration prüfen
   - Absender-Nummer validieren

4. **"IP gesperrt":**
   - 15 Minuten warten oder IP-Sperre im Admin-Bereich entfernen

## Support

Bei Problemen oder Fragen wenden Sie sich an den Plugin-Entwickler oder konsultieren Sie die b1gMail-Dokumentation für SMS-Service-Konfiguration.
