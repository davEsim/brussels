<?php
  //$covidInfoLink = "https://www.oneworld.cz/2022/one-world-in-brussels-covid-info";

  $body="<p>Dear ".$_POST["brusselViewerFName"].",</p>
  <p>Thank you for your registration for the screening of <strong>".$activeBrusselScreeningFilm["titleEN"]."</strong> at <strong>".invertDatumFromDB($activeBrusselScreening["date"],1)." ".$activeBrusselScreening["time"]."</strong>, <strong>".$activeBrusselScreeningPlace["xBrusselPlaces"]." ".$activeBrusselScreeningPlace["address"]."</strong> as part of the 16th edition of the One World International Human Rights Documentary Film Festival in Brussels.</p>";
  /*
  $body.="Please, follow <a title='the COVID-related information' href='".$covidInfoLink."'>the COVID-related information on our website</a> to help us create a safe environment during the festival.";
  */
  $body.="<br><p>We are looking forward to seeing you!</p>";
  $body.="<p>Warm regards,<br>";
  $body.="the One World in Brussels Team</p>";
?>
