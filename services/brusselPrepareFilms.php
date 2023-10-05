
<?
/*
nastavit YEAR a YEAR_ID
skript pak: 
zkopíruje xFilms do xBrusselFilms
a smaže pole co nejsou v xBrusselFilms potřeba
*/

ini_set("display_errors",1);
define("YEAR", 2023);
define("YEAR_ID",43); // 42 - 2022

include("../php/classes/dbPdo.class.php");
include("../../../data/private/".YEAR."/connection.php");
include("../php/funcs.php");

$db->query("DROP TABLE xBrusselFilms");

$db->query("   CREATE TABLE xBrusselFilms AS
                SELECT *
                FROM xFilms;
            ");

$db->query("DELETE FROM xBrusselFilms WHERE type NOT LIKE 'Film'");           

$colsForDelete = array("imageAltCZ" ,"TITLE_ORIGINAL", "xFilms", "titleCZ", "premiereCZ", "countryCZ", "timeCZ", "languageCZ", "subtitlesCZ", "shortSynopsisCZ", "synopsysCZ", "impactCZ", "impactEN", "package", "packageen", "type", "linkToOnlineCZ");

foreach($colsForDelete AS $colForDelete){
    $db->query("ALTER TABLE xBrusselFilms DROP COLUMN ".$colForDelete);
}


echo "o.k.";
?>