<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 5.11.2018.
 * Time: 14:02
 */
include_once('includes/init.php');
$url = 'http://www.scoresway.com/?sport=american_football&page=competition&id=1';
$competition_id = 1;
$country_id = 189;
$sport_id = 1;

$tmpMatch = array();

$context = stream_context_create(array(
    'http' => array(
        'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
    ),
));
$html = file_get_html($url, false, $context);
if ($html) {
    $currentCubCmp = null;
    $competition = null;
    $tmpArray = array();
    foreach ($html->find('div.block_competition_table-wrapper') as $details) {

        $competition = $details->find('h2', 0)->find('a', 0)->plaintext;
        ($competition == 'Regular Season')? $competition = 'Team' : "";
        $team = null;
        foreach ($details->find('table.leaguetable') as $table) {
            foreach ($table->find('tr.odd, tr.even') as $row) {
                $teamid = $row->attr['data-team_id'];
                $position = strip_tags(($row->find('td', 0)->innertext));
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
                        case 'number total draw total_draw':
                            $matchDraw = $item->innertext;
                            break;
                        case 'number total lost total_lost':
                            $matchLose = $item->innertext;
                            break;
                        case 'number total gf total_gf sf total_sf':
                            $goalsfor = $item->innertext;
                            break;
                        case 'number total ga total_ga sa total_sa':
                            $goalsagainst = $item->innertext;
                            break;
                        case 'number points pct':
                            $points = $item->innertext;
                            break;
                    }

                }

                $tmpMatch = new tableMatch();
                $tmpMatch->setAttr('team', $team);
                $tmpMatch->setAttr('played', $matchPlayed);
                $tmpMatch->setAttr('won', $matchWin);
                $tmpMatch->setAttr('draw', $matchDraw);
                $tmpMatch->setAttr('loses', $matchLose);
                $tmpMatch->setAttr('goalDiff', "$goalsfor:$goalsagainst");
                $tmpMatch->setAttr('leagueId', $competition_id);
                $tmpMatch->setAttr('points', $points);
                $tmpMatch->setAttr('position', $position);
                $tmpMatch->setAttr('country_id', $country_id);
                $tmpMatch->setAttr('cmpType', $competition);
                $tmpMatch->setAttr('teamId', $teamid);
                $tmpMatch->setAttr('sportId', $sport_id);
                $tmpMatch->setAttr('source', 17);

                array_push($tmpArray, $tmpMatch);
                unset($tmpMatch);
            }
        }
    }
}

var_dump($tmpArray);


