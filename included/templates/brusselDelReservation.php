<div class="row">
	<div class="medium-12 columns">
		<h3><?= __("Vaše rezervace") ?></h3>
		<?
		$lang = $_ENV["lang"];
		$brusselViewers = new xBrusselViewers($db, "xBrusselViewers");
		$brusselScreenings = new xBrusselScreenings($db, "xBrusselScreenings");

		if ($_POST) {
			if (strlen($_POST["uid"]) == 32) {
				$deletedReservations = $brusselViewers->delReservation($_POST["uid"]);
				if ($deletedReservations == 1) {
					$newViewer = $brusselViewers->firstOnWatingListSwitchToRegistered($_POST["screeningId"]);
					$activeBrusselScreening = $brusselScreenings->findById($_POST["screeningId"]);
					$activeBrusselScreeningFilm = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselFilms"], "xBrusselFilms");
					$activeBrusselScreeningPlace = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselPlaces"], "xBrusselPlaces");
					if ($newViewer["mail"]) {
						$uid = $newViewer["uid"];
						include_once("./included/partials/mails/brusselRegistrationSwitchedFromWaitingListgit.php");
						$mail = new PHPMailer();
						$mail->From = "brussels@oneworld.cz";
						$mail->FromName = "One World in Brussels";
						$mail->AddAddress($newViewer["mail"]);
						$mail->WordWrap = 50;                              // set word wrap
						$mail->IsHTML(true);                               // send as HTML
						$mail->CharSet = "utf-8";
						$mail->Subject  = "Your registration for One World Brussels";
						$mail->Body     =  $body;
						$mail->AltBody  =  formatTextMail($body);
						$mail->Send();
					}
					echo "<p>Reservation was canceled successfully.</p>";
				} else {
					echo "<p>Reservation was not canceled successfully.<br>Reservation was canceled sometimes earlier or didn't exists.</p>";
				}
			}
		} else {
			if ((strlen($itemId) == 32)) {
				$activeBrusselViewer = $brusselViewers->findByUid($itemId);
				if ($activeBrusselViewer["id"]) {
					$activeBrusselScreening = $brusselScreenings->findById($activeBrusselViewer["id_xBrusselScreenings"]);
					$activeBrusselScreeningFilm = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselFilms"], "xBrusselFilms");
					$activeBrusselScreeningPlace = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselPlaces"], "xBrusselPlaces");
		?>

					<p><strong><?= $activeBrusselScreeningFilm["title$lang"] ?></strong></p>
					<p><?= invertDatumFromDB($activeBrusselScreening["date"], 1) . " | " . $activeBrusselScreening["time"] ?></p>
					<p><?= $activeBrusselScreeningPlace["xBrusselPlaces"] ?><br /><?= $activeBrusselScreeningPlace["address"] ?></p>
					<form method="post">
						<input type="hidden" name="uid" value="<?= $itemId ?>" />
						<input type="hidden" name="screeningId" value="<?= $activeBrusselScreening["id"] ?>" />
						<div id="formSend" data-value="<?= __("Zrušit rezervaci") ?>"></div>
					</form>

		<?
				} else {
					echo "<p>Reservation was not canceled successfully.<br>Reservation was canceled sometimes earlier or didn't exists.</p>";
				}
			} else {
				echo "<p>Odkaz pro smazaní vaší registrace bohužel nemá správný formát. Přepošlete nám prosím email s potvrzením registrace na adresu info@oneworld.cz a my se pokusíme celou věc vyřešit.</p>";
			}
		}
		?>
	</div>
</div>