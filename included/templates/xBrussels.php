<?php
$lang = $_ENV["lang"];
$brusselScreenings = new xBrusselScreenings($db, "xBrusselScreenings");
$juryMembers = new xBrusselJuryMembers($db, "xBrusselJuryMembers");

?>
<header class="header--withImg">
    <div class="row">
        <div class="xxlarge-4 xlarge-4 medium-6 columns">
            <h1><?= $rActivePage["rspagesFull$lang"] ?></h1>

        </div>
    </div>
</header>
<div class="row row--coloredPerex align-center">
    <div class="medium-8 columns">
        <?= $rActivePage["perex$lang"] ?>
    </div>
</div>

<style>
    td {
        vertical-align: top;
    }
</style>

<?
if ($lang == "CZ") {
    $resPath = "/brusel-registrace/";
} else {
    $resPath = "/brussels-registration/";
}
?>
<script>
    let toggler = document.getElementById("toggler");
    toggler.addEventListener("click", () => {
        toggler.style.display = "none"
    });
</script>
<div class="row">
    <div class="medium-12 columns">
        <div id="toggler" class="text-center">
            <h3 class="text-center marginTop button" data-toggle="panel">Zobrazit proběhlé projekce</h3>
        </div>
        <div class="" id="panel" data-toggler data-animate="fade-in fade-out" style="display:none">
            proběhlé projekce
        </div>
    </div>
</div>
<?
$days = $brusselScreenings->days();
foreach ($days as $day) {
    list($syear, $smonth, $sday) = explode("-", $day["date"]);
    $untilRegDay = $sday - 1; // registrace se uzavírají den před projekcí - pokud není zadáno jinak v RS v poli resvExtraInfo
?>
    <div class='row'>
        <smaller class="medium-12 columns">
            <h2 class="marginBottom2 marginTop"><?= invertDatumFromDB($day["date"], 1) ?></h2>
            <?
            $brusselScreeningsListing = $brusselScreenings->listingByDate($day["date"]);
            ?>
            <table class="stack table--brusselsScreenings">
                <?
                $i = 0;
                foreach ($brusselScreeningsListing as $brusselScreening) {
                    $i++;
                ?>
                    <tr>
                        <td width="250px" class="time">
                            <strong><?= $brusselScreening["time"] ?></strong><br>
                            <p style='margin-right: 1rem; cursor: pointer; cursor: hand' data-open="placeModal<?= $i ?>">
                                <?= $brusselScreening["xBrusselPlaces"] ?><br>
                                <small><?= $brusselScreening["address"] ?></small>
                            </p>
                        </td>
                        <div class="large reveal" id="placeModal<?= $i ?>" aria-labelledby="placeModalHeader<?= $i ?>" data-reveal>
                            <h1 id="placeModalHeader<?= $i ?>"><?= $brusselScreening["xBrusselPlaces"] ?></h1>
                            <p class="lead"><?= $brusselScreening["address"] ?></p>
                            <?= $brusselScreening["googleMap"] ?>
                            <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <td width="400px">
                            <?
                            echo "<a href=" . $resPath . $brusselScreening["sid"] . "><img src='" . modifyImgPathfromSB($brusselScreening["imageUrl"], 370, 1.8) . "' alt='" . $brusselScreening["title$lang"] . "'></a>";
                            ?>
                        </td>
                        <td class="fullFilmTitle">
                            <?
                            if ($brusselScreening["block$lang"]) {
                                echo "<div class='extraScreeningTitle'><strong>" . $brusselScreening["block$lang"] . "</strong></div>";
                            }
                            ?>
                            <a href="<?= $resPath . $brusselScreening["sid"] ?>"><strong><?= $brusselScreening["title$lang"] ?></strong></a>
                            <?
                            if ($brusselScreening["debate$lang"]) {
                                //echo "<p><small>".$brusselScreening["debate$lang"]."</small></p>";
                            }
                            ?>
                        </td>
                        <td width="100px">
                            <?php
                            if ($brusselScreening["type"] == "closed" || strtotime($brusselScreening["expiration"]) < time()) {
                            ?>
                                <a class="hollow button" href="<?= $resPath . $brusselScreening["sid"] ?>"><?= __("Detail") ?></a>
                            <?
                            } else {
                            ?>
                                <a class="hollow button" href="<?= $resPath . $brusselScreening["sid"] ?>">Detail and&nbsp;registration</a>
                                <small>Registrace je možná do<br><?= substr($brusselScreening["expiration"], 0, -3) ?></small>
                            <?
                            }
                            ?>
                        </td>
                    </tr>

                <?
                }
                ?>
            </table>
    </div>
    </div>
    <!--row-->
<?
}
?>
<section class="section section--neutral">
    <div class="row marginBottom2">
        <div class="medium-12 columns">
            <h3><?= __("Porota Jednoho světa v Bruselu") ?>
        </div>
    </div>
    <?php
    $results = $juryMembers->listing("", "sequence", "ASC", 0, 0);
    $i = 0;
    echo "<div class='row'>";
    foreach ($results as $result) {
    ?>
        <div class='large-4 medium-6 small-12 columns end'>
            <article>
                <? echo getFirstMedia("xBrusselJuryMembers", $result["id"], 1, "", "", "img", "", FALSE); ?>
                <div>
                    <h4><?= $result["name$lang"] ?></h4>
                    <p class="credits"><?= $result["country$lang"] ?></p>
                    <?= $result["descr$lang"] ?>
                </div>
            </article>
        </div>
    <?
    }
    echo "</div>";
    ?>
</section>
<div class="row  marginTop4 marginBottom">
    <div class="medium-12 columns">
        <h4><?= __("Hlavní kontakt") ?></h4>
        <div>
            <p><strong>Petr Nuska</strong><br />
                Festival Coordinator</p>
            <p><a href="mailto:petr.nuska@oneworld.cz" target="_blank">petr.nuska@oneworld.cz</a></p>
        </div>
    </div>
</div>