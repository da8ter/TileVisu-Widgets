# TileVisu Widgets (Widget-Kachel)

Symcon-Kachel (HTML-SDK) mit bis zu zehn Schaltern (`Schalter1` bis `Schalter10`) auf einem optionalen Hintergrundbild (`bgImage`). Öffentliches Repo `da8ter/TileVisu-Widgets`. Bedienung: `Widget/README.md`.

Nicht verwechseln mit dem Repo `TileVisu-Button-Kachel` (Ordner `Widgets/`, Präfix `WDT`): dieselbe Kachel-Familie, dort mit Bild-Hook, Nachrichtenfilter und Module Strict überarbeitet.

Betriebsdaten dieses Rechners (Zweige, Testsystem) stehen in `CLAUDE.local.md` (nicht eingecheckt).

## Aufbau

- **`Widget/`**: einziges Modul, Klasse `TileVisuWidgetTile`, Präfix `TWT`, noch `IPSModule` ohne `strict_types`, ab Symcon 7.2.
- Abos (`VM_UPDATE`) und Referenzen nur für zugeordnete Variablen (ID > 0): Absender 0 heißt bei `RegisterMessage` „jedes Objekt“, `MessageSink` liefe sonst bei jeder Variablenaktualisierung im System.
- `module.html` lädt Symcons `/icons.js` selbst; der geteilte Icon-Baustein `symcon-icons-shared` der anderen TileVisu-Kacheln ist hier nicht eingebaut. Icons nur `fa-light`.
- Startzustand als `handleMessage(...)` im Kacheldokument.

## Prüfen

```bash
php -l Widget/module.php
php tests/module_test.php     # braucht die SDK-Attrappe aus dem Nachbar-Repo TileVisu-Raum-Titel-Kachel (tests/stubs), Pfad per SYMCON_STUBS
```

`WIDGET_MODULE=<pfad/module.php>` lässt den Test eine andere Fassung laden (Gegenprobe).

## Regeln

- **Commits:** deutsche Botschaft, ein Thema je Commit, **ohne** Co-Authored-By-Zeile; Prüfungen vorher.
- **Nie** `git checkout`/`git restore` auf Dateien: die Arbeitskopie kann nicht committete Arbeit enthalten.
- **Push und Release nur auf Zuruf.** Release: `version`, `build` und `date` in `library.json` hochsetzen (`date` ist ein Unix-Zeitstempel).
- **Öffentliches Repo:** keine IP-Adressen, Ports, Instanz-IDs, Token, Pfade unter `/Users/`, keine Personendaten – auch nicht in Tests und Kommentaren. FontAwesome Pro ist lizenziert: keine Font-Dateien, Kit-Kennungen oder Lizenzdaten.
- **Symcon-Standards** für neuen Code: Darstellungen statt Variablenprofilen, Texte über `locale.json`, Nutzertexte sagen „Symcon“. Solange die Klasse `IPSModule` erweitert, Overrides ohne Parametertypen lassen; eine Umstellung auf `IPSModuleStrict` (Mindestversion 8.1) folgt dem Muster der Button-Kachel.
- Eigenschaften, Idents und Payload-Schlüssel nicht umbenennen: bestehende Instanzen und Kacheln hängen daran.

## Wissen

Gemeinsames Symcon-Plattformwissen (Lebenszyklus, Kacheln, Icons): https://github.com/da8ter/SymDo-Family-Organizer/tree/SymDo-Beta/docs/plattform – lokal `../List/docs/plattform/`. Symcon-Fragen am offiziellen Handbuch prüfen.
