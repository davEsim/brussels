<?php
$delRegistrationLink = $_ENV["host"]."/brussel-cancel-reservation/".$uid;

$body = "<p>Dear " . $r["fname"] . ",</p>";
$body .= "<p>The screening of " . $r["film"] . " will take place in three days at " . $r["time"] . ", " . $r["xBrusselPlaces"] . ", " . $r["address"] . ".</p>";
$body.="<p><strong>Please, nebude moci, zruste svoji registraci kliknutim na tento odkaz<br>
<a title='cancel registration' href='".$delRegistrationLink."'>".$delRegistrationLink."</a></strong></p>";
$body .= "<p>We are looking forward to meeting you there!</p>";
$body .= "<p>The One World in Brussels Team</p>";
