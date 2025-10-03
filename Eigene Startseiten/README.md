# Eigene Startseiten

**Erstellen Sie benutzerdefinierte Startseiten für Ihre Benutzer.**

Mit diesem Plugin können Administratoren benutzerdefinierte Startseiten erstellen, auf die Benutzer nach dem Login weitergeleitet werden. Dies ermöglicht es, individuelle Landing Pages oder spezielle Schnittstellen für verschiedene Benutzergruppen zu erstellen.

- **Sprache:** Deutsch
- **b1gMail Version:** 7.3.0

## ⚠️ Wichtiger Hinweis

**Template-Dateien fehlen!** Dieses Plugin benötigt noch die folgenden Template-Dateien im `templates/` Verzeichnis:

- `eigenestartseiten1.pref.tpl` - Hauptkonfigurationsseite
- `eigenestartseiten2.pref.tpl` - Erstellungsseite für neue Startseiten
- `eigenestartseiten_icon.png` - Plugin-Icon
- `eigenestartseiten_logo.png` - Plugin-Logo

## Installation

1. Plugin-Ordner in `plugins/` Verzeichnis kopieren
2. **Template-Dateien erstellen** (siehe Hinweis oben)
3. Im Adminbereich unter "Plugins" aktivieren

## Verwendung

1. Adminbereich → "Plugins" → "Eigene Startseiten"
2. Neue Startseite erstellen oder bestehende bearbeiten
3. Benutzer oder Gruppen zuweisen
4. Startseite aktivieren

## Fehlende Templates

Das Plugin erwartet folgende Template-Dateien:

```
Eigene Startseiten/
├── eigenestartseiten.php
├── README.md
└── templates/
    ├── eigenestartseiten1.pref.tpl  ← FEHLT
    ├── eigenestartseiten2.pref.tpl  ← FEHLT
    └── images/
        ├── eigenestartseiten_icon.png  ← FEHLT
        └── eigenestartseiten_logo.png  ← FEHLT
```

**Ohne diese Template-Dateien wird das Plugin nicht korrekt funktionieren.**
