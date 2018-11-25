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

$url = "https://www.betexplorer.com/soccer/england/npl-premier-division/standings/?table=table&table_sub=overall&ts=pzmvc092&dcheck=0";
$competition_id = 1;
$country_id = 189;
$sport_id = 1;
$tmpArray = array();
$context = stream_context_create(array(
    'http' => array(
        'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
    ),
));

$html = file_get_html($url, false, $context);
if ($html) {
    $tmp = null;
    $currentCubCmp = null;

    foreach ($html->find('tr') as $row) {
        $team = null;
        $matchPlayed = 0;
        $matchWin = 0;
        $matchWinPen = 0;
        $matchDraw = 0;
        $matchLose = 0;
        $matchLosePen = 0;
        $goalDiff = null;
        $points = null;
        $position = null;
        if ($row->find('th', 1) != '') {
            $tmp = ($row->find('th', 1)->plaintext);
        }
        ($tmp != '') ? $currentCubCmp = $tmp : '';

        foreach ($row->find('td') as $item) {
            switch ($item->class) {
                case 'participant_name col_participant_name col_name':
                    $team = $item->plaintext;
                    break;
                case 'matches_played col_matches_played':
                    $matchPlayed = $item->innertext;
                    break;
                case 'wins_regular col_wins_regular':
                    $matchWin = $item->innertext;
                    break;
                case 'wins_pen col_wins_pen':
                    $matchWinPen = $item->innertext;
                    break;
                case 'draws col_draws':
                    $matchDraw = $item->innertext;
                    break;
                case 'losses_regular col_losses_regular':
                    $matchLose = $item->innertext;
                    break;
                case 'losses_pen col_losses_pen':
                    $matchLosePen = $item->innertext;
                    break;

                case 'goals col_goals':
                    if($goalDiff == null) {$goalDiff = $item->plaintext;}
                    $points = $item->plaintext;
                    break;
            }
            $position = ($row->find('td', 0)->plaintext);
        }

        if ($team != '') {

            $tmpMatch = new tableMatch();
            $tmpMatch->setAttr('team', $team);
            $tmpMatch->setAttr('played', $matchPlayed);
            $tmpMatch->setAttr('won', $matchWin + $matchWinPen);
            $tmpMatch->setAttr('draw', $matchDraw);
            $tmpMatch->setAttr('loses', $matchLose + $matchLosePen);
            $tmpMatch->setAttr('goalDiff', $goalDiff);
            $tmpMatch->setAttr('leagueId', $competition_id);
            $tmpMatch->setAttr('points', $points);
            $tmpMatch->setAttr('position', $position);
            $tmpMatch->setAttr('country_id', $country_id);
            $tmpMatch->setAttr('cmpType', $currentCubCmp);
            $tmpMatch->setAttr('sportId', $sport_id);
            $tmpMatch->setAttr('source', 14);

            array_push($tmpArray, $tmpMatch);
            unset($tmpMatch);
        }


    }

} else {
    $file = fopen('log.txt', 'a');
    fwrite($file, date('Y-n-d H:i') . ", $url, $competition_id \n");
}

//return $tmpArray;


var_dump($tmpArray);
?>


