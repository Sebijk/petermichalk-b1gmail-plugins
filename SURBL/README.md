# SURBL Plugin

**Spam-Schutz durch URL-basierte Realtime-Blocklisten für b1gMail.**

Mit diesem Plugin können Sie E-Mails automatisch als Spam markieren, wenn sie URLs enthalten, die in SURBL (Spam URI Realtime Blocklists) gelistet sind. Das Plugin analysiert alle URLs in eingehenden E-Mails und prüft diese gegen verschiedene SURBL-Server.

- **Sprache:** Deutsch
- **b1gMail Version:** 7.3.0
- **PHP Version:** 8.3

## Features

- **Automatische URL-Erkennung:** Findet alle URLs in E-Mail-Inhalten (Text und HTML)
- **SURBL-Server-Abfrage:** Prüft URLs gegen konfigurierbare SURBL-Server
- **Scoring-System:** Gewichtete Bewertung basierend auf verschiedenen Faktoren
- **Whitelist/Blacklist:** Eigene Domain-Listen für individuelle Anpassungen
- **Statistiken:** Verfolgung der SURBL-Abfragen und Performance
- **Flexible Konfiguration:** Anpassbare Schwellenwerte und Server-Listen

## Installation

1. Plugin-Ordner `SURBL/` in das `plugins/` Verzeichnis von b1gMail kopieren
2. Im Adminbereich unter "Plugins" das Plugin aktivieren
3. Konfiguration über "Plugins" → "SURBL"