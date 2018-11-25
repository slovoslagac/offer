<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 5.11.2018.
 * Time: 13:59
 */

function getTableBetxplorer($url, $competition_id, $country_id, $sport_id)
{

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
            $matchWinOt = 0;
            $matchDraw = 0;
            $matchLose = 0;
            $matchLoseOt = 0;
            $tmpPoints = null;
            $goalDiff = null;
            $points = null;
            $position = null;
            if ($row->find('th', 1) != '') {
                $tmp = ($row->find('th', 1)->plaintext);
            }
            ($tmp != '') ? $currentCubCmp = $tmp : '';
            if ($sport_id == 1) {
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
                        case 'wins col_wins':
                            $matchWin = $item->innertext;
                            break;
                        case 'wins_pen col_wins_pen':
                            $matchWinOt = $item->innertext;
                            break;
                        case 'draws col_draws':
                            $matchDraw = $item->innertext;
                            break;
                        case 'losses_regular col_losses_regular':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses col_losses':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses_pen col_losses_pen':
                            $matchLoseOt = $item->innertext;
                            break;
                        case 'goals col_goals':
                            if ($goalDiff == null) {
                                $goalDiff = $item->plaintext;
                            }
                            $points = $item->plaintext;
                            break;
                    }
                    $position = ($row->find('td', 0)->plaintext);
                }
            } elseif ($sport_id == 2 || $sport_id == 4) {
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
                        case 'wins col_wins':
                            $matchWin = $item->innertext;
                            break;
                        case 'wins_ot col_wins_ot':
                            $matchWinOt = $item->innertext;
                            break;
                        case 'losses_ot col_losses_ot':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses col_losses':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses_regular col_losses_regular':
                            $matchLoseOt = $item->innertext;
                            break;
                        case 'goals col_goals':
                            if ($goalDiff == null) {
                                $goalDiff = $item->plaintext;
                            }
                            $points = $item->plaintext;
                            break;
                        case 'winning_percentage col_winning_percentage';
                            $tmpPoints = $item->innertext;
                            break;
                    }
                    $position = ($row->find('td', 0)->plaintext);
                }
                if ($tmpPoints != null) {
                    $points = $tmpPoints * 100;
                } elseif ($points == $goalDiff) {
                    $points = ($matchPlayed > 0) ? round((($matchWin + $matchWinOt) / $matchPlayed), 3) * 100 : "";
                };
            } else if ($sport_id == 7) {
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
                        case 'wins col_wins':
                            $matchWin = $item->innertext;
                            break;
                        case 'wins_ot col_wins_ot':
                            $matchWinOt = $item->innertext;
                            break;
                        case 'draws col_draws':
                            $matchDraw = $item->innertext;
                            break;
                        case 'losses_regular col_losses_regular':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses col_losses':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses_ot col_losses_ot':
                            $matchLoseOt = $item->innertext;
                            break;
                        case 'goals col_goals':
                            if ($goalDiff == null) {
                                $goalDiff = $item->plaintext;
                            }
                            $points = $item->plaintext;
                            break;
                    }
                    $position = ($row->find('td', 0)->plaintext);
                }
            } else {
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
                        case 'draws col_draws':
                            $matchDraw = $item->innertext;
                            break;
                        case 'losses_regular col_losses_regular':
                            $matchLose = $item->innertext;
                            break;
                        case 'losses col_losses':
                            $matchLose = $item->innertext;
                            break;
                        case 'goals col_goals':
                            if ($goalDiff == null) {
                                $goalDiff = $item->plaintext;
                            }
                            $points = $item->plaintext;
                            break;
                    }
                    $position = ($row->find('td', 0)->plaintext);
                }
            }


            if ($team != '') {
                $tmpMatch = new tableMatch();
                $tmpMatch->setAttr('team', $team);
                $tmpMatch->setAttr('played', $matchPlayed);
                $tmpMatch->setAttr('won', $matchWin + $matchWinOt);
                $tmpMatch->setAttr('draw', $matchDraw);
                $tmpMatch->setAttr('loses', $matchLose + $matchLoseOt);
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
    return $tmpArray;
}


function getTableSoccerway($url, $competition_id, $country_id, $sport_id)
{

    $context = stream_context_create(array(
        'http' => array(
            'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
        ),
    ));
    $html = file_get_html($url, false, $context);

    if ($html) {
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
                    $tmpMatch->setAttr('loses', $matchLose);
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
    } else {
        $file = fopen('log.txt', 'a');
        fwrite($file, date('Y-n-d H:i') . ", $url, $competition_id \n");
    }

    return $tmpArray;
}


function getTableScoresway($url, $competition_id, $country_id, $sport_id)
{

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

    return $tmpArray;
}

function getTableSofaScore($url, $competition_id, $country_id, $sport_id)
{
    $tmpArray = array();

    $data = json_decode(file_get_contents($url));

    $totalTableData = $data->standingsTables;
    $number = count($totalTableData);

    foreach ($totalTableData as $newitem) {
        $cmpDetails = $newitem->tournament;

        if ($number > 1) {
            $cmpType = $newitem->name;
        } else {
            $cmpType = 'Team';
        }

        $tableData = $newitem->tableRows;

        foreach ($tableData as $item) {
            $team = $item->team;
            $teamid = $team->id;
            $teamname = $team->name;

            $position = $item->position;

            $totaldata = $item->totalFields;
            $matchPlayed = $totaldata->matchesTotal;
            $matchWin = $totaldata->winTotal;
            $matchDraw = $totaldata->drawTotal;
            $matchLose = $totaldata->lossTotal;
            $goalDiff = $totaldata->goalsTotal;
            $points = $totaldata->pointsTotal;

//            echo "$teamname -- $teamid -- $played -- ($win, $draw, $lost) -- $goalDiff - $points -- $cmpType<br>";

            $tmpMatch = new tableMatch();
            $tmpMatch->setAttr('team', $teamname);
            $tmpMatch->setAttr('teamId', $teamid);
            $tmpMatch->setAttr('played', $matchPlayed);
            $tmpMatch->setAttr('won', $matchWin);
            $tmpMatch->setAttr('draw', $matchDraw);
            $tmpMatch->setAttr('loses', $matchLose);
            $tmpMatch->setAttr('goalDiff', $goalDiff);
            $tmpMatch->setAttr('leagueId', $competition_id);
            $tmpMatch->setAttr('points', $points);
            $tmpMatch->setAttr('position', $position);
            $tmpMatch->setAttr('country_id', $country_id);
            $tmpMatch->setAttr('cmpType', $cmpType);
            $tmpMatch->setAttr('sportId', $sport_id);
            $tmpMatch->setAttr('source', 16);

            array_push($tmpArray, $tmpMatch);
            unset($tmpMatch);
        }

    }


    return $tmpArray;
}