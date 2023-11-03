## Brusel

- od 2023 běží samostatně v subdoméně brussels.oneworld.cz
- existuje jen v EN verzi
- [zadani](https://clovekvtisni-my.sharepoint.com/:w:/g/personal/nuspet01_pinf_cz/EUncSkjIlQtDtHGOh828JiMBoEq5toFwSAaNHYzMCLHJAw?e=91N0qq)

### Projekce

#### typy projekcí

| type      | zobrazit v programu | zobrazit reg form v detailu | neco extra                          |
| --------- | ------------------- | --------------------------- | ----------------------------------- |
| normal    | ano                 | ano                         |                                     |
| closed    | ano                 | ne                          |                                     |
| notPublic | ne                  | ano                         | vyřešit link (s hash) na projekci   |
| extern    | ano                 | ne                          | button s linkem na externí projekci |

#### logika projekcí

##### stránka projekcí (template xBrussels.php)

na stránce zobrazuji všechny projekce kromě:

- projekce má datum konání starší než je aktuální
- projekce je neveřejná (notPublic)

tlačítko:

- "Detail" - pokud nemá být aktivní registrace - closed projekce nebo datum expirace projekce je starší než aktuální datum
- "Detail a registrace" - ve všech ostatních případech. rušíme nemožnost se registrovat z důvodu naplnění, protože pak je možná registrace na waitingList

##### stránka detailu projekcí (template xBrusselReservation.php)

na stránce detailu projekce (template xBrusselReservation.php) zobrazuji vlastně všechny projekce (i detail notPulic projekce)

- registrační formulář
