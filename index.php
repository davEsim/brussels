<?php
$page = substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/") + 1);
$tail = ltrim($_SERVER["REQUEST_URI"], "/");

include_once("../../data/private/brussels/config_prod.php"); //final
if ($_ENV["page"] && !$metaTitle) {
    //Header("Location: ".$_ENV["serverPath"].""); // kvuli FB a jeho přidávání ocásků :-)
}


?>
<!doctype html>
<html class="no-js" lang="<?= ($lang == "CZ") ? "cs" : "en"; ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= ($metaTitle) ? $metaTitle . " &middot; " . $title : $title ?></title>
    <meta name="description" content="<?= $metaDescr ?>" />
    <meta property="fb:app_id" content="<?= FACEBOOK_APP_ID ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= ($metaTitle) ? $metaTitle . " &middot; " . $title : $title ?>" />
    <meta property="og:site_name" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $metaDescr ?>" />
    <meta property="og:image" content="<?= $metaImgPath ?>" />
    <meta property="og:url" content="<?= $_ENV["host"] . $_SERVER['REQUEST_URI'] ?>" />
    <meta name='robots' content='index,follow' />
    <meta name='googlebot' content='index,follow,snippet,archive' />
    <meta name="facebook-domain-verification" content="q7dqrc3ppuz63qdj45ezykezmkz27s" />
    <link rel="stylesheet" href="<?= $_ENV["prodFullPath"] ?>assets/css/app.css?v=2<?= filemtime('assets/css/app.css') ?>" />
    <?php
    if ($metaImgPath) {
    ?>
        <style>
            .header--withImg {
                background-image: url(<?= $metaImgPath ?>);
                background-size: cover;
            }
        </style>
    <?php
    }
    ?>
    <style>
        @font-face {
            font-family: "saans_jsbold";
            src: url("/2024/assets/saansjs-bold-webfont.woff2") format("woff2"),
                url("/2024/assets/font/saansjs-bold-webfont.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: "saans_jsheavy";
            src: url("/2024/assets/font/saansjs-heavy-webfont.woff2") format("woff2"),
                url("/2024/assets/font/saansjs-heavy-webfont.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        }


        @font-face {
            font-family: "saans_jsmedium";
            src: url("/2024/assets/font/saansjs-medium-webfont.woff2") format("woff2"),
                url("/2024/assets/font/saansjs-medium-webfont.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: "saans_jsregular";
            src: url("/2024/assets/font/saansjs-regular-webfont.woff2") format("woff2"),
                url("/2024/assets/font/saansjs-regular-webfont.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: "saans_jssemibold";
            src: url("/2024/assets/font/saansjs-semibold-webfont.woff2") format("woff2"),
                url("/2024/assets/font/saansjs-semibold-webfont.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        }
    </style>

    <meta name="msapplication-config" content="/download/favicon/browserconfig.xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="/download/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/download/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/download/favicon/favicon-16x16.png">
    <link rel="manifest" href="/download/favicon/site.webmanifest">
    <link rel="mask-icon" href="/download/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>

    <div id="overlayBg">
        <div id="overlay" role='dialog'></div>
    </div>

    <header id="header" role="banner" class="sticky">
        <a id="inflsGoToMain" class="sr-only" href="#main" tabindex="1"><?= __("Rovnou na obsah") ?></a>
        <div class="row collapse header__row--<?= $_ENV["lang"] ?> align-middle">
            <div class="medium-4 columns show-for-medium js-logo_<?= $_ENV["lang"] ?>">
                <a title="<?= __("Festival Jeden svět") ?>" href='<?= $_ENV["host"] ?>' tabindex="1">
                    <h1><?= __("Jeden svět") ?></h1>
                </a>
                <!--<div></div>-->
            </div>
            <div class="medium-8 columns text-right">
                <strong>Datum Bruselu </strong>
            </div>
        </div>
        <?php
        if ($secondNav = generateOneLevelMenu($rActivePage["id"], false, "nav--second", "Sekundární navigace")) {
        ?>
            <div class="header__nav--second show-for-medium">
                <div class="row">
                    <div class="small-12 columns">
                        <?php
                        $breadRootPage = "";
                        $parentPage = parentPageFull($rActivePage["id"]);
                        if ($parentPage["id"] == 7) $breadRootPage = $rActivePage["rspagesFull$lang"];
                        else $breadRootPage = $parentPage["rspagesFull$lang"];
                        if ($breadRootPage) echo "<strong>" . $breadRootPage . "</strong>";
                        echo $secondNav;
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </header>
    <a name="main"></a>
    <main id="<?= ($page) ? "mainIn" : "main" ?>" role="main">
        <?
        if ($template = $routing["template"]) {
            include_once("included/templates/$template.php");
        } else {
            include_once("included/templates/xBrussels.php");
        }
        ?>
    </main>
    <footer id="footer" role="contentinfo">
        <?
        include_once("included/partials/brusselPartners.php");
        ?>
        <hr>
        <?
        include_once("included/partials/foot.php"); ?>
        <hr>
        <? include_once("included/partials/sysFoot.php"); ?>

    </footer>
    <div class="large reveal" id="Modal11" aria-labelledby="ModalHeader11" data-reveal>
        <h1 id="ModalHeader11">O festivalu Jeden svět</h1>
        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="responsive-embed widescreen">
            <iframe id="trailerPlayer" src="https://player.vimeo.com/video/<?= ($_ENV["lang"] == "CZ") ? "669800959" : "669803059" ?>" width="760" height="431" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
    <?php
    //include_once("included/partials/etr.php");
    ?>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="<?= $_ENV["prodFullPath"] ?>assets/js/app.js?v=3"></script>
    <script>
        var lang = "<?= $lang ?>"
    </script>
    <script type="text/javascript" src="<?= $_ENV["prodFullPath"] ?>js/default.js?v=7>"></script>


    <? if ($routing["extraJS"]) {
        $extraJSfile = "/js/" . $routing['extraJS'] . ".js";
        $extraRootJSfile = $_SERVER["DOCUMENT_ROOT"] . $extraJSfile;
    ?>
        <script type="text/javascript" src="<?= $extraJSfile ?>?v=7<?= filemtime($extraRootJSfile) ?>"></script>
    <? } ?>
</body>

</html>