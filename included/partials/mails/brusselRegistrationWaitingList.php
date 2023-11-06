<?php
$delRegistrationLink = $_ENV["host"] . "/brussel-cancel-reservation/" . $uid;

$body = "<p>Dear " . $_POST["brusselViewerFName"] . ",</p>";
$body .= "<p><strong>Pozor, nejde o plnohnodnotnou registrace, ale zatím jen o zápis na waiting list</strong></p><br>";
$body .= "
  <p>Thank you for your registration for the screening of <strong>" . $activeBrusselScreeningFilm["titleEN"] . "</strong> at <strong>" . invertDatumFromDB($activeBrusselScreening["date"], 1) . " " . $activeBrusselScreening["time"] . "</strong>, <strong>" . $activeBrusselScreeningPlace["xBrusselPlaces"] . " " . $activeBrusselScreeningPlace["address"] . "</strong> as part of the 17th edition of the One World International Human Rights Documentary Film Festival in Brussels.</p>";

/*
 $body .= "<p><strong>Please, pokud nechcete být na waiting listu, kliněte prosím na následující odkaz.<br>
   <a title='cancel registration' href='" . $delRegistrationLink . "'>" . $delRegistrationLink . "</a></strong></p>";
 */

$body .= "<br><p>We are looking forward to seeing you!</p>";
$body .= "<p>Warm regards,<br>";
$body .= "the One World in Brussels Team</p>";
