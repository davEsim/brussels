<?
/*
Den a 3 dny před projekcí posílám mail o nadcházející projekci.
záměrně cron spouštím jen v určitých hodinách... třeba od 8 do 12 hodin (prostě dopoledne :-)) v zoneru je pak nastaveno spouštění každých 5 minut
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once("../../../data/private/2024/smtp_config.php");
require_once("../../../data/private/2024/phpMailer/phpmailer.class.php");
require_once("../../../data/private/2024/phpMailer/smtp.class.php");

require_once("../../2024/php/classes/dbPdo.class.php");
require_once("../../../data/private/2024/connection.php");
require_once("../../2024/php/funcs.php");

//if (intval(Date("H")) >= 8 && intval(Date("H")) < 12) {

// 1 den předem -- start

$rs = $db->queryAll("   SELECT v.id AS vid, v.fname, v.sname, s.date, s.time, f.titleEN AS film, v.mail, p.xBrusselPlaces, p.address 
    FROM `xBrusselViewers` AS v 
    INNER JOIN xBrusselScreenings AS s 
    ON v.id_xBrusselScreenings = s.id 
    INNER JOIN xBrusselFilms AS f
    ON s.id_xBrusselFilms = f.id
    INNER JOIN xBrusselPlaces AS p
    ON s.id_xBrusselPlaces = p.id
    WHERE s.date = CURDATE() + INTERVAL 1 DAY 
    AND v.reminder1DayBeforeSent LIKE 'ne'
    AND v.mail IS NOT NULL
    AND v.mail != ''
    AND v.state LIKE 'registered'
    ORDER BY vid ASC
    LIMIT 0,20
    ");

foreach ($rs as $r) {

    include("../included/partials/mails/brusselRegistrationReminder1DayBefore.php");
    $mail = new PHPMailer();
    $mail->From = "brussels@oneworld.cz";
    $mail->FromName = "One World in Brussels";
    $mail->AddAddress($r["mail"]);
    $mail->WordWrap = 50;                              // set word wrap
    $mail->IsHTML(true);                               // send as HTML
    $mail->CharSet = "utf-8";
    $mail->Subject  = "Your registration for One World Brussels";
    $mail->Body     =  $body;
    $mail->AltBody  =  formatTextMail($body);
    if ($mail->Send()) {
        $db->query("UPDATE xBrusselViewers SET reminder1DayBeforeSent = 'ano' WHERE id = ?", array($r["vid"]));

        $logString = "Mail to: " . $r["mail"] . ", registration ID: " . $r["vid"] . ", screening date: " . $r["date"];
        $db->query("INSERT into brusselReminderLog VALUES (NULL, NOW(), ?, ?, ?)", array("ano", $r["vid"], $logString));
    } else {
        $logString = "Mail to: " . $r["mail"] . ", registration ID: " . $r["vid"] . ", screening date: " . $r["date"];
        $db->query("INSERT into brusselReminderLog VALUES (NULL, NOW(), ?, ?, ?)", array("ne", $r["vid"], $logString));
    }
}

// 1 den předem -- end
// 3 dny předem -- start

$rs = $db->queryAll("   SELECT v.id AS vid, v.fname, v.sname, s.date, s.time, f.titleEN AS film, v.mail, p.xBrusselPlaces, p.address 
                            FROM `xBrusselViewers` AS v 
                            INNER JOIN xBrusselScreenings AS s 
                            ON v.id_xBrusselScreenings = s.id 
                            INNER JOIN xBrusselFilms AS f
                            ON s.id_xBrusselFilms = f.id
                            INNER JOIN xBrusselPlaces AS p
                            ON s.id_xBrusselPlaces = p.id
                            WHERE s.date = CURDATE() + INTERVAL 3 DAY 
                            AND v.reminder3DaysBeforeSent LIKE 'ne'
                            AND v.mail IS NOT NULL
                            AND v.mail != ''
                            AND v.state LIKE 'registered'
                            ORDER BY vid ASC
                            LIMIT 0,20
                            ");

foreach ($rs as $r) {

    include("../included/partials/mails/brusselRegistrationReminder3DaysBefore.php");
    $mail = new PHPMailer();
    $mail->From = "brussels@oneworld.cz";
    $mail->FromName = "One World in Brussels";
    $mail->AddAddress($r["mail"]);
    $mail->WordWrap = 50;                              // set word wrap
    $mail->IsHTML(true);                               // send as HTML
    $mail->CharSet = "utf-8";
    $mail->Subject  = "Your registration for One World Brussels";
    $mail->Body     =  $body;
    $mail->AltBody  =  formatTextMail($body);
    if ($mail->Send()) {
        $db->query("UPDATE xBrusselViewers SET reminder3DaysBeforeSent = 'ano' WHERE id = ?", array($r["vid"]));

        $logString = "Mail to: " . $r["mail"] . ", registration ID: " . $r["vid"] . ", screening date: " . $r["date"];
        $db->query("INSERT into brusselReminderLog VALUES (NULL, NOW(), ?, ?, ?)", array("ano", $r["vid"], $logString));
    } else {
        $logString = "Mail to: " . $r["mail"] . ", registration ID: " . $r["vid"] . ", screening date: " . $r["date"];
        $db->query("INSERT into brusselReminderLog VALUES (NULL, NOW(), ?, ?, ?)", array("ne", $r["vid"], $logString));
    }
}

// 3 dny předem -- end

//}

echo "o.k.";
