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
k řešení tedy zůstávají situace kdy:

- nevypršela expirace projekce a nebyla naplněna kapacita - uložím s příznakem "registered"
- nevypršela expirace projekce a byla naplněna kapacita - upozorním uživatele, že byl registrován "pouze" na waitingList a uložím s příznakem "waiting"
- vypršela expirace, kapacitu už tedy řešit nemusím - uživateli se jen zobrazí zpráva, že registrace na projekci již byla uzavřena

zpráva, která se zobrazí po odeslání formuláře (souvisí s předchozím):

- pokud byla uložene registrace s příznakem "registered" - zobrazí se informace o úspěšné registraci včetně informací o odeslání potvrzovacího mailu
- pokud byla uložena registrace s příznakem "waiting" - zobrazí se informace o úspěšné registraci ale s výrazným upozorněním, že jde o registraci na waitingList - né plná registrace. a rovněž informace o odeslání potvrzovacího mailu

## maily

odesíláme až čtyři verze mailů:

- po registraci, kde nebyla naplněna kapacita - odesílám mailem informace o "plnohodnotné" registraci (šablona mailu /included/partials/mails/brusselRegistration.php). mail obsahuje odkaz na zrušení registrace na projekci
- po registraci, kde už před nebo po odeslání registrace byla naplněna kapacita projekce - odesílám mailem informace o registraci na waitingList (šablona /included/partials/mails/brusselRegistrationWatingList.php). mail neobsahuje odkaz na zrušení registrace na projekci, protože ta vlastně zatím není (otázka je jesti má cenu řešit zrušení z waitingListu)
- mail čekateli na waitingListu - pokud někdo jiný zruší svojí plnou registraci na vyprodanou projekci
- reminder 3 dny před projekcí, pokud se uživatel neregistruje méně než 3 dny před datumem projekce
- reminder 1 den před projekcí, pokud se uživatel neregistruje v den projekce
