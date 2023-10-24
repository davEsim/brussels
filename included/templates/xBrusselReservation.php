<?php
$lang = $_ENV["lang"];
$itemSeo = intval($_ENV["itemSeo"]);

$films = new xFilms($db, "xBrusselFilms");
$filmParams = new xFilmParams($db, "xFilmParams");
$filmDirectors = new xFilmDirectors($db, "xFilmDirectors");

$brusselScreenings = new xBrusselScreenings($db, "xBrusselScreenings");
$activeBrusselScreening = $brusselScreenings->findById($itemId);
$activeBrusselScreeningCountOfViewers = $brusselScreenings->countOfViewers($itemId);
$activeBrusselScreeningFilm = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselFilms"], "xBrusselFilms");
$activeBrusselScreeningPlace = $brusselScreenings->getRelRow($activeBrusselScreening["id_xBrusselPlaces"], "xBrusselPlaces");

include_once("./included/partials/modals/personalData.php");


if($_POST){?>
	<div class="row align-center">
		<div class="medium-6 small-12 columns">
			<h3 class="marginBottom2"><?=__("Vaše rezervace na projekci")?></h3>
			<?
			if($activeBrusselScreeningCountOfViewers >= $activeBrusselScreening["countOfViewers"] && $activeBrusselScreening["activeForReservation"] == "ano"){?>
					<p>Není bohužel možné provést rezervaci. Projekce je již vyprodána.</p>
			<?}else{        
				$message = "";
				if(!empty($_POST["email"])){
					$message = "Detekováno jako spam.";
				}elseif(filter_var(filter_var($_POST["brusselViewerM"], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
					$message = "E-mail nemá bohužel správný formát.";
				}elseif(empty($_POST["brusselViewerSName"]) || empty($_POST["brusselViewerM"])){
					$message = "Vyplňte prosím všechna povinná pole.";
				}else{
					$uid = md5(uniqid(rand(), true));
					if($_POST["agreeData"] == "ne" || !isset($_POST["agreeData"])) $agreeData = "ne"; else $agreeData = "ano";
					if($_POST["agreeInfo"] == "ne" || !isset($_POST["agreeInfo"])) $agreeInfo = "ne"; else $agreeInfo = "ano";
					if($_POST["agreeNewsletter"] == "ne" || !isset($_POST["agreeNewsletter"])) $agreeNewsletter = "ne"; else $agreeNewsletter = "ano";

					$params = array(
										":screenId" 		=> $_POST["sid"],
										":fname"			=> $_POST["brusselViewerFName"],
										":sname"			=> $_POST["brusselViewerSName"],
										":dateOfBirth"	=> $_POST["dateOfBirth"],
										":idType"			=> $_POST["idType"],
										":idN"			=> $_POST["idN"],
										":nationality"	=> $_POST["nationality"],
										":organisation"	=> $_POST["organisation"],
										":badge"	        => $_POST["badge"],
										":mail"			=> $_POST["brusselViewerM"],
										":agreeData"		=> $agreeData,
										":agreeNewsletter"=> $agreeNewsletter,
										":uid"			=> $uid,
										":reminderSent"	=> "ne"
									);
					$db->query("INSERT INTO xBrusselViewers VALUES (NULL, :screenId, :fname, :sname, :dateOfBirth, :idType, :idN, :nationality, :organisation, :badge, :mail, :agreeData, :agreeNewsletter, :uid, NOW(), :reminderSent)", $params);
					
					echo "	<p>Your registration has been successful.</p>
							<p>An email message confirming registration has been sent to your email address ".$_POST["brusselViewerM"].".</p>
							<p class='warning'><strong>If you can not find our mail, please,  check your spam folder too.</strong></p>
							<p>Thank you</p>
							<a class='hollow button marginBottom' href='../brussels'><b class='fi-arrow-left'></b> Back to Programme</a>
						";
					
					include_once("./included/partials/mails/brusselRegistration.php");
					$mail = new PHPMailer();                                			
					$mail->From="brussels@oneworld.cz";
					$mail->FromName="One World in Brussels";
					$mail->AddAddress($_POST["brusselViewerM"]); 			
					$mail->WordWrap = 50;                              // set word wrap
					$mail->IsHTML(true);                               // send as HTML
					$mail->CharSet="utf-8"; 
					$mail->Subject  =(($lang=="CZ")?"Jeden svět v Bruselu – registrace na projekci":"Your registration for One World Brussels");
					$mail->Body     =  $body;
					$mail->AltBody  =  formatTextMail($body);			
					$mail->Send();
			
				}
			}
	?>
		</div>
	</div>
<?php
}
if(!$_POST || $message){
	if($itemId == 13){?>
		<img src='/assets/img/static/brusselsNoImageW.jpg' alt='".$brusselScreening["title$lang"]."'>
	<?}else{
    ?>
    	<img src="<?=modifyImgPathfromSB($activeBrusselScreeningFilm["imageUrl"], 1920, 2.5);?>">
	<?php
	}
	?>
    <div class="row marginTop2 marginBottom2 align-center">
        <div class="medium-8 small-12 columns">          
            <h1 id="<?=$itemId?>"><?=$activeBrusselScreeningFilm["title$lang"]?></h1>
            <?=$activeBrusselScreening["debate$lang"]?>
        </div>
        <div class="medium-4 small-12 columns medium-text-right align-self-middle">
			<?
			$trailerPath = "/2023/download/trailers/".$activeBrusselScreeningFilm["film_number"].".mp4";
			$trailerRootPath = substr($_SERVER["DOCUMENT_ROOT"], 0, strrpos($_SERVER["DOCUMENT_ROOT"], "/")).$trailerPath;  
            if(file_exists($trailerRootPath)){  
            ?>
                <button class="medium hollow button button--play button--310" data-open="trailer"><?=__("Přehrát trailer")?></button> 
            <?
            }
            if($activeBrusselScreeningFilm["linkToOnline$lang"]){
            ?>  
                <a href="<?=$activeBrusselScreeningFilm["linkToOnline$lang"]?>" target="_blank" class="medium hollow button button--rightArrow button--310"><?=__("Zhlédnout online")?></a>
            <?php
            }  
            ?>
        </div>
    </div>
	<section class="section--neutral">
		<div class="row">
			<div class="medium-12 columns">
				<h3 class="marginBottom2"><?=__("Vaše rezervace na projekci")?></h3>
				<? if($message){?><p class="warning"><?=$message?></p><?}?>
			</div>
		</div>
		<?php
		if($activeBrusselScreeningCountOfViewers >= $activeBrusselScreening["countOfViewers"]){
		?>
			<div class="row row--textDecoration align-justify">
				<div class="medium-6 columns">
				<? if($lang == "CZ"){?>
					<p>Promítání je již plně obsazeno. <br>Pokud chcete být zařazeni na čekací listinu, kontaktujte <a href="mailto:brussels@oneworld.cz">brussels@oneworld.cz</a>.</p>
				<?}else{?>
					<p>The screening is now fully booked. <br>Contact <a href="mailto:brussels@oneworld.cz">brussels@oneworld.cz</a> if you want to be put on a&nbsp;waiting list.</p>
				<?}?>  
				</div>
			</div>
		<?
		}else{
		?>
			<div class="row align-justify" data-abide>
				<div class="medium-6 columns">
					<form method="post">  
						<table class="brusselForm">
							<tr><td><?=__("Jméno")?> <sup>&ast;</sup></td><td><input type="text" name="brusselViewerFName" size="20" required /></td></tr>
							<tr><td><?=__("Přijmení")?> <sup>&ast;</sup></td><td><input type="text" name="brusselViewerSName" size="20" required /></td></tr>
							<tr><td>E-mail <sup>&ast;</sup></td><td><input type="email" name="brusselViewerM" required  /></td></tr>
							<tr><td><?=__("Organizace")?> <sup>&ast;</sup></td><td><input type="text" name="organisation" required  /></td></tr>
							<tr class="nS"><td>E-mail</td><td><input type="email" name="email" /></td></tr>
							<tr class="nS"><td>E-mail</td><td><input type="email" name="mail" /></td></tr>
							<table class="brusselForm">
							<tr>
								<td><input type="checkbox" name="agreeData" value="<?=__("ano")?>" required> <sup>&ast;</sup></td>
								<td>
									<p><a href="#" data-open="modal-personalData"><?=__("Souhlasím s tím, že mé kontaktní údaje budou po dobu konání festivalu uchovávány za účelem zasílání připomínek o konání projekcí.")?></a></p>
								</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="agreeNewsletter" value="<?=__("ano")?>"></td>
								<td>
									<p><?=__("Chci být informován o příštím ročníku festivalu Jeden svět v Bruselu (2024).")?></p>
								</td>
							</tr>
						</table>
						<div id="formSend" data-value="<?=__("Odeslat")?>"></div>
						<input type="hidden" name="sid" value="<?=$activeBrusselScreening["id"]?>" />
						<input type="hidden" name="pid" value="<?=$activeBrusselScreening["id_xBrusselPlaces"]?>" />
					</form>
				</div>
				
				<div class="medium-5 columns">
					<div style="border-left: 1px solid #cacaca; padding-left: 1rem">
						<p class="marginBottom1"><strong><?=__("Projekce")?></strong></p>
						<h4><?=$activeBrusselScreeningFilm["title$lang"]?></h4>
						<p><strong><?=invertDatumFromDB($activeBrusselScreening["date"],1)." | ".$activeBrusselScreening["time"]?></strong></p>
						<p><a href="<?=$activeBrusselScreeningPlace["url"]?>" target="_blank"><?=$activeBrusselScreeningPlace["xBrusselPlaces"]?></a><br /><?=$activeBrusselScreeningPlace["address"]?></p> 
					</div> 
				</div>
			</div>
		<?  
		}
		?>	
	</section>
	<?
	if($activeBrusselScreeningFilm["id"]){		
	?>
	<div class='row marginTop4 align-center'>
		<div class='medium-6 small-12 columns'>
			<h2><?=__("O filmu")?></h2>
			<p><strong><?=$activeBrusselScreeningFilm["shortSynopsis$lang"]?></strong></p>
			<p><?=$activeBrusselScreeningFilm["synopsys$lang"]?></p>
		</div>
	</div>
	<div class="row marginTop credits--filmDetail"> 
		<div class='medium-4 small-12 columns smaller'>
			<h6><?=__("Název")?></h6>
			<p><?=$activeBrusselScreeningFilm["title$lang"]?></p>

			<h6 class="marginTop2"><?=__("Originální název")?></h6>
			<p><?=$activeBrusselScreeningFilm["TITLE_ORIGINAL"]?></p>
			<h6><?=__("Originální znění")?></h6>
			<p>
				<?
				if($clickedScreen["language$lang"]){ //bud zobrazuji jazyk z projekce, pokud není vyplněn, zobrazuji jazyk vyplněný u filmu
					echo $clickedScreen["language$lang"];
				}else{
					echo $activeBrusselScreeningFilm["language$lang"];
				}
				?>
			</p>
			<h6><?=__("Titulky")?></h6>
			<p>
				<?php 
				if($clickedScreen["subtitles$lang"]){
					echo $clickedScreen["subtitles$lang"];
				}else{
					echo $activeBrusselScreeningFilm["subtitles$lang"];
				}
				?>
			</p>
			
			<?if($activeBrusselScreeningFilm["premiere$lang"]){?>
				<h6><?=__("Premiéra")?></h6>
				<p><?=$activeBrusselScreeningFilm["premiere$lang"]?></p>
			<?}?>
		
		</div>
		<div class="medium-4 small-12 columns smaller">
			<h6><?=__("Rok výroby")?></h6>
			<p><?=$activeBrusselScreeningFilm["YEAR"]?></p>
			<h6><?=__("Země původu")?></h6>
			<p><?=$activeBrusselScreeningFilm["country$lang"]?></p>
			<h6><?=__("Stopáž")?></h6>
			<p><?=$activeBrusselScreeningFilm["time$lang"]?> min.</p>
			
			<!--
			<h6><?=__("Tagy")?></h6>
			<p>
				<?php
					/*
					$tags = $films->findItemsInRelations($activeBrusselScreeningFilm["id"], "xFilmTags");
					$i = 0;
					foreach($tags AS $tag){
						if($i++) echo ", ";
						echo $tag["tag$lang"];
						
					}
					*/
				?>
			</p>
			-->	
			
		</div>
		<div class="medium-3 small-12 columns smaller">
			
				<?php
				$filmDirectors = $filmDirectors->listingWhereLike("id_xFilms", $activeBrusselScreeningFilm["id"], "id", "ASC", 0, 3);
				foreach($filmDirectors AS $filmDirector){?>
					<img src="<?=$filmDirector["imageUrl"]?>" alt="<?=$filmDirector["fName"]?> <?=$filmDirector["sName"]?>">
					<h6><?=__("Režie")?></h6>
					<h5><?=$filmDirector["fName"]?> <?=$filmDirector["sName"]?></h5>
					<p><?=$filmDirector["fgraphy$lang"]?></p>
				<?}
				?>
		</div>
	</div>   
<?			
	}
}
?>

<!-- modal -->

<div class="large reveal" id="trailer" data-reveal data-animation-in="scale-in-up" data-close-on-click="false">
    <h5><?=$activeBrusselScreeningFilm["title$lang"]?></h5>   
  <div class="responsive-embed widescreen">
    <video id="trailerPlayer"  controls preload poster="<?=modifyImgPathfromSB($activeBrusselScreeningFilm["imageUrl"], 1920, 1.78);?>">
        <source src="<?=$_ENV["prodHost"].$trailerPath?>" type="video/mp4">
    </video>
  </div>
  <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<script>
    document.querySelector(".close-button").addEventListener("click", function(){
        let trailerPlayer = document.querySelector("#trailerPlayer");
        trailerPlayer.pause();
    })    
</script>

