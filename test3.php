<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 25.9.2018.
 * Time: 14:28
 */
include_once('includes/init.php');

//function getTable($league)
//{

$url = "https://int.soccerway.com/national/algeria/ligue-2/20182019/regular-season/r47781/tables/";
$competition_id = 1;
$country_id = 189;
$context = stream_context_create(array(
    'http' => array(
        'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
    ),
));
$html = file_get_html($url, false, $context);


$currentCubCmp = null;
$tmpArray = array();


foreach ($html->find('table.detailed-table') as $table) {
    foreach ($table->find('tr.team_rank') as $row) {
        $matchid = null;
        $position = null;
        $points = null;
        $matchPlayed = null;
        $matchWin = null;
        $matchDraw = null;
        $matchLose = null;
        $team = null;
        $code = explode("_", $row->id);
        $matchid = $code[3];
        $goalsfor = null;
        $goalsagainst = null;
        $position = ($row->find('td', 0)->innertext);
        foreach ($row->find('td') as $item) {
            switch ($item->class) {
                case 'text team large-link':
                    $team = $item->plaintext;
                    break;
                case 'number total mp':
                    $matchPlayed = $item->innertext;
                    break;
                case 'number total won total_won':
                    $matchWin = $item->innertext;
                    break;
                case 'number total drawn total_drawn':
                    $matchDraw = $item->innertext;
                    break;
                case 'number total lost total_lost':
                    $matchLose = $item->innertext;
                    break;
                case 'number total gf total_gf':
                    $goalsfor = $item->innertext;
                    break;
                case 'number total ga total_ga':
                    $goalsagainst = $item->innertext;
                    break;
                case 'number points':
                    $points = $item->innertext;
                    break;
            }

        }
        if ($team != '') {

            $tmpMatch = new tableMatch();
            $tmpMatch->setAttr('team', $team);
            $tmpMatch->setAttr('played', $matchPlayed);
            $tmpMatch->setAttr('won', $matchWin);
            $tmpMatch->setAttr('draw', $matchDraw);
            $tmpMatch->setAttr('goalDiff', "$goalsfor:$goalsagainst");
            $tmpMatch->setAttr('leagueId', $competition_id);
            $tmpMatch->setAttr('points', $points);
            $tmpMatch->setAttr('position', $position);
            $tmpMatch->setAttr('country_id', $country_id);
            $tmpMatch->setAttr('cmpType', "Team");
            $tmpMatch->setAttr('matchid', $matchid);
            $tmpMatch->setAttr('sportId', $sport_id);
            $tmpMatch->setAttr('source', 15);

            array_push($tmpArray, $tmpMatch);
            unset($tmpMatch);
        }


    }
}

var_dump($tmpArray);
?>


