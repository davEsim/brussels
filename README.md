# Brusel

- od 2023 běží samostatně na subdoméně brussels.oneworld.cz
- existuje jen v EN verzi
- [zadani](https://clovekvtisni-my.sharepoint.com/:w:/g/personal/nuspet01_pinf_cz/EUncSkjIlQtDtHGOh828JiMBoEq5toFwSAaNHYzMCLHJAw?e=91N0qq)

# Projekce

## typy projekcí

oproti zadání je zde změna. domnívám se, že je zbytečné na každý typ projekce vytvářet přepínač ano/ne. protože se (alespon podle mého :-)) typy vzájemně vylučují, zahrnul jsem všechny typy do jednoho radioButtonu a v CMS tak přepínám přímo mezi typy.

| type      | zobrazit v programu | zobrazit reg form v detailu | neco extra                          |
| --------- | ------------------- | --------------------------- | ----------------------------------- |
| normal    | ano                 | ano                         |                                     |
| closed    | ano                 | ne                          |                                     |
| notPublic | ne                  | ano                         | vyřešit link (s hash) na projekci   |
| extern    | ano                 | ne                          | button s linkem na externí projekci |

## logika projekcí

### stránka projekcí (template xBrussels.php)

na stránce zobrazuji všechny projekce kromě:

- projekce má datum konání starší než je aktuální
- projekce je neveřejná (notPublic)

tlačítko:

- "Detail" - pokud nemá být aktivní registrace - closed projekce nebo datum expirace projekce je starší než aktuální datum
- "Detail a registrace" - ve všech ostatních případech. rušíme nemožnost se registrovat z důvodu naplnění, protože pak je možná registrace na waitingList

### stránka detailu projekce s registračním formulářem (template xBrusselReservation.php)

na stránce detailu projekce (template xBrusselReservation.php) zobrazuji vlastně všechny projekce (i detail notPublic projekce)
rozdíly jsou ale v sekci pro registrační formulář (pro přehlednost v DEV verzi šedý background), který je součástí detailu projekce:

- pokud je projekce typu "normal" nebo "notPublic", současně není projekce expirovaná a současně není projekce vyprodaná zobrazí se registrační formulář
- pokud je projekce typu "normal" nebo "notPublic", současně není projekce expirovaná a současně je projekce vyprodaná zobrazí Alert, že projekce je vyprodaná, ale že je možné se zapsat na waiting list, plus registrační formulář (ten je stejný jako formulář na plnou registraci, jen posílá skrytá data, že data mají být uložena do waitinglistu a né do registrací)
- pokud je projekce typu "normal" nebo "notPublic" a současně je projekce expirovaná zobrazí se informace, že registrace byla již ukočena včetně data expirace
- pokud je projekce typu "extern" místo registračního formuláře se zobrazí tlačítko s odkazem (editovatelný v rámci CMS) na stránky pořadetele, kde je možné provést registraci
- pokud je projekce typu "closed" místo registračního formuláře se zobrazí text o tom, že projekce je uzavřená

### submit registračního formuláře (template xBrusselReservation.php)

znovu vše kontroluji, protože uživatel mohl dlouho vyplňovat formulář nebo měl stránku s registrací dlouho otevřenou a mezitím se mohl stav (expirace, naplnění) projekce změnit
nemusím už řešit typ projekce, ten se řeší u zobrazení formuláře. takže když už se odesílá \$\_POST mám odfiltrované.
opravdu uložit novou registraci uživatele můžu jen pokud:

- nevypršela expirace projekce - uložím s příznakem "registered"
- nebyla naplněna kapacita - uložím s příznakem "waiting"
