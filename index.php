<?php
$page = substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["REQUEST_URI"], "/")+1);
$tail = ltrim($_SERVER["REQUEST_URI"],"/");

include_once("../../data/private/brussels/config_prod.php"); //final
if($_ENV["page"] && !$metaTitle){
    //Header("Location: ".$_ENV["serverPath"].""); // kvuli FB a jeho přidávání ocásků :-)
}


?>
<!doctype html> 
<html class="no-js" lang="<?=($lang == "CZ")?"cs":"en";?>">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?=($metaTitle)?$metaTitle." &middot; ".$title:$title?></title>
    <meta name="description" content="<?=$metaDescr?>" />
    <meta property="fb:app_id" content="<?=FACEBOOK_APP_ID?>"/> 
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?=($metaTitle)?$metaTitle." &middot; ".$title:$title?>" />
    <meta property="og:site_name" content="<?=$title?>" />
    <meta property="og:description" content="<?=$metaDescr?>" /> 
	<meta property="og:image" content="<?=$metaImgPath?>" /> 
	<meta property="og:url" content="<?=$_ENV["host"].$_SERVER['REQUEST_URI']?>" />
    <meta name='robots' content='index,follow'/>
    <meta name='googlebot' content='index,follow,snippet,archive'/>
    <meta name="facebook-domain-verification" content="q7dqrc3ppuz63qdj45ezykezmkz27s" />
    <link rel="stylesheet" href="<?=$_ENV["prodHost"]?>/assets/css/app.css?v=<?=filemtime('assets/css/app.css') ?>" />
    <?php
    if($metaImgPath){
    ?>
        <style>
            .header--withImg{
                background-image: url(<?=$metaImgPath?>);
                background-size: cover;
            }
        </style>
    <?php
    }
    ?>
    <!-- Google Consent Mode -->
    <script data-cookieconsent="ignore">
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments)
        }
        gtag("consent", "default", {
            ad_storage: "denied",
            analytics_storage: "denied",
            functionality_storage: "denied",
            personalization_storage: "denied",
            security_storage: "granted",
            wait_for_update: 500
        });
        gtag("set", "ads_data_redaction", true);
    </script>
    <!-- Google Consent Mode END -->
    <!-- Google Tag Manager -->
    <script data-cookieconsent="ignore">
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KTJD45K');
    </script>
    <!-- End Google Tag Manager -->
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="aa99e4ef-188a-4803-aff2-5b6a762ac5b8" data-blockingmode="auto" type="text/javascript"></script>
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
    
    <header id="header"  role="banner" class="sticky">
        <a id="inflsGoToMain" class="sr-only" href="#main" tabindex="1"><?=__("Rovnou na obsah")?></a>
        <div class="row collapse header__row--<?=$_ENV["lang"]?> align-middle">
            <div class="medium-4 columns show-for-medium js-logo_<?=$_ENV["lang"]?>">
                <a title="<?=__("Festival Jeden svět")?>" href='<?=$_ENV["host"]?>' tabindex="1">
                    <h1><?=__("Jeden svět")?></h1>
                </a>
                <!--<div></div>-->
            </div>
            <div class="medium-8 columns text-right">
                <strong>Datum Bruselu </strong>   
            </div>                    
        </div>
        <?php
        if($secondNav = generateOneLevelMenu($rActivePage["id"], false, "nav--second", "Sekundární navigace")){
        ?>                
        <div class="header__nav--second show-for-medium">     
            <div class="row">
                <div class="small-12 columns"> 
                    <?php
                    $breadRootPage = "";
                    $parentPage = parentPageFull($rActivePage["id"]);
                    if($parentPage["id"] == 7) $breadRootPage = $rActivePage["rspagesFull$lang"];
                    else $breadRootPage = $parentPage["rspagesFull$lang"];
                    if($breadRootPage) echo "<strong>".$breadRootPage."</strong>";
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
    <main id="<?=($page)?"mainIn":"main"?>" role="main">
    <?
		if($template = $routing["template"]){
            include_once("included/templates/$template.php");
        }else{
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
        include_once("included/partials/foot.php");?>
        <hr>
        <? include_once("included/partials/sysFoot.php");?>
        
    </footer>
    <div class="large reveal" id="Modal11" aria-labelledby="ModalHeader11" data-reveal>
        <h1 id="ModalHeader11">O festivalu Jeden svět</h1>
        <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="responsive-embed widescreen">
            <iframe id="trailerPlayer"  src="https://player.vimeo.com/video/<?=($_ENV["lang"] == "CZ")?"669800959":"669803059"?>" width="760" height="431" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
        </div> 
    </div>
    <?php
    //include_once("included/partials/etr.php");
    ?>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="<?=$_ENV["prodHost"]?>/assets/js/app.js?v=3"></script>
    <script>var lang = "<?=$lang?>"</script>
    <script type="text/javascript" src="<?=$_ENV["prodHost"]?>/js/default.js?v=<?=filemtime("js/default.js")?>"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script>
    const video = document.getElementById('trailerPlayer');
    const player = new Vimeo.Player(video);

    $( document ).ready(function() {
        $('.close-button').click(function() {
            player.pause()
            //return false;
        });
    });   
</script>

    <? if($routing["extraJS"]){
        $extraJSfile = "/js/".$routing['extraJS'].".js";
        $extraRootJSfile = $_SERVER["DOCUMENT_ROOT"].$extraJSfile;
        ?>
        <script type="text/javascript" src="<?=$extraJSfile?>?v=7<?=filemtime($extraRootJSfile)?>"></script>
    <?}?>

    <? if($_ENV["page"] == "vstupenky" || $_ENV["page"] == "tickets"){
        ?>
        <script type="text/javascript" src="https://partners.goout.net/cz-prague/jedensvetcz.js?v=1"></script>
    <?}?>

  </body>
</html>