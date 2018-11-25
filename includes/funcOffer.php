<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 5.11.2018.
 * Time: 14:00
 */

function getHockey($league, $season)
{

    $sport = 'hockey';
    $url = "https://www.xscores.com/$sport/leagueresults/$league/$season/r//";
    $html = file_get_html($url);

    $links = array();
    $listofmatches = array();

    foreach ($html->find('[id="round"]') as $a) {
        foreach ($a->find('option') as $item) {
            array_push($links, array($item->value, $item->innertext));
        }
    }

    foreach ($links as $link) {
        $key = $link[0];
        $tmplist = getperioddata($key, $league, $sport, $season, $link[1]);
        foreach ($tmplist as $tmp) {
            array_push($listofmatches, $tmp);
        }
    }

    return $listofmatches;
}

function getperioddata($code, $league, $sport, $season, $round)
{
    $url = "https://www.xscores.com/$sport/leagueresults/$league/$season/r/$code/";
    $html = new simple_html_dom();
    $html->load_file($url);
    $data = $html->find('[id="scoretable"]');
    $current_date = null;
    $time = null;
    $home = null;
    $away = null;
    $matchid = null;
    $match = null;
    $leagueid = null;
    $league = null;
    $allmatches = array();

    foreach ($data as $a) {
        foreach ($a->find('div') as $v) {
            if ($v->class == 'score_row padded_date country_header') {
                $current_date = trim($v->innertext);
            } elseif ($v->class == 'score_row match_line e_true' or $v->class == 'score_row match_line o_true') {
                $matchid = $v->id;
                $leagueid = $v->attr['data-league-code'];
                $league = $v->attr['data-country-name'] . ' - ' . $v->attr['data-league-name'];
                foreach ($v->find('div') as $md) {
                    switch ($md->class) {
                        case 'score_ko score_cell':
                            $time = $md->innertext;
                            break;
                        case 'score_home_txt score_cell wrap':
                            $home = $md->innertext;
                            break;
                        case 'score_home_txt score_cell wrap winnerTeam':
                            $home = $md->innertext;
                            break;
                        case 'score_away_txt score_cell wrap':
                            $away = $md->innertext;
                            break;
                        case 'score_away_txt score_cell wrap winnerTeam':
                            $away = $md->innertext;
                            break;
                    }
                }
                $datetime = convetDate($current_date, $time);
                $match = createCode($league) . createCode($home) . createCode($away);
                $tmpmatch = new offerMatch();
                $tmpmatch->setAttr('date', $current_date);
                $tmpmatch->setAttr('time', $time);
                $tmpmatch->setAttr('hometeam', $home);
                $tmpmatch->setAttr('hometeamid', createCode($home));
                $tmpmatch->setAttr('awayteam', $away);
                $tmpmatch->setAttr('awayteamid', createCode($away));
                $tmpmatch->setAttr('datetime', $datetime);
                $tmpmatch->setAttr('season', $season);
                $tmpmatch->setAttr('match', $match);
                $tmpmatch->setAttr('matchid', $matchid);
                $tmpmatch->setAttr('league', $league);
                $tmpmatch->setAttr('leagueid', $leagueid);
                $tmpmatch->setAttr('round', $round);
                array_push($allmatches, $tmpmatch);
                unset($tmpmatch);
                $away = null;
                $home = null;
                $time = null;

            }
        }
    }
    return $allmatches;
}

function getBetextplorerResult($code, $codepref, $id)
{
    $result = array();
    $url = "https://www.betexplorer.com/$id/$codepref/$code/";
    $context = stream_context_create(array(
        'http' => array(
            'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
        ),
    ));
    $html = file_get_html($url, false, $context);
    foreach ($html->find('p, h2') as $item) {
        switch ($item->id) {
            case 'match-date':
                $dateDetails = explode(",", $item->attr['data-dt']);
                $time = "$dateDetails[3]:$dateDetails[4]";
                break;
            case'js-score':
                $resultFT = $item->plaintext;
                break;
            case'js-partial':
                $tmpres = str_replace("(", "", explode(",", $item->plaintext))->fields();
                $resultHT = $tmpres[0];
                break;
        }
    }
    $result['time'] = $time;
    $result['ht'] = $resultHT;
    $result['ft'] = $resultFT;
    return $result;
}

function getBetexplorer($id){

    $listofmatches = array();
    $currentDate = new DateTime();

    //$sport = 'hockey';
    $url = "https://www.betexplorer.com/$id/fixtures/";

    $context = stream_context_create(array(
        'http' => array(
            'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
        ),
    ));
    $html = file_get_html($url, false, $context);
    $tmpseason = $html->find('option[selected]')->fields();
    $season = trim($tmpseason[0]->plaintext);


    foreach ($html->find('table') as $table) {
        $round = null;
        $date = null;
        foreach ($table->find('tr') as $row) {
            $team = null;
            $hometeam = null;
            $awayteam = null;
            $code = null;
            foreach ($row->find('th') as $item) {
                switch ($item->class) {
                    case 'h-text-left':
                        $tmpround =explode('.', $item->plaintext)->fields();
                        $round = $tmpround[0];
                        break;
                }
            }

            foreach ($row->find('td') as $item) {
                switch ($item->class) {
                    case 'h-text-left':
                        $tmpitem = explode("/",$item->find('a', 0))->fields();
                        $code =  $tmpitem[5]; $codepref =$tmpitem[4];
                        $country = ucwords(str_replace("-", " ", $tmpitem[2]));
                        $league = ucwords(str_replace("-", " ", $country . " " .( $tmpitem[3])));
                        $team = $item->plaintext;
                        break;
                    case 'table-main__datetime':
                        ($item->plaintext != "&nbsp;") ? $date = $item->plaintext : "";
                        break;

                }
            }
            if ($team != "") {
                $dateDetails = explode(" ", $date);
                $day = $dateDetails[0];
                $time = $dateDetails[1];
                $dayDetails = explode(".", $day);
                if ($dayDetails[0] == "Today") {
                    $d = $currentDate->format("d");
                    $m = $currentDate->format("m");
                } elseif ($dayDetails[0] == "Tomorrow") {
                    $currentDate->modify('+1 day');
                    $d = $currentDate->format("d");
                    $m = $currentDate->format("m");
                }elseif ($dayDetails[0] == "Yesterday") {
                    $currentDate->modify('-1 day');
                    $d = $currentDate->format("d");
                    $m = $currentDate->format("m");
                } else {
                    $d = $dayDetails[0];
                    $m = $dayDetails[1];
                }
                if (isset($dayDetails[2])) {
                    if($dayDetails[2] != "") {
                        $y = $dayDetails[2];
                    } else {
                        $y = $currentDate->format("Y");
                    }
                } else {
                    $y = $currentDate->format("Y");
                }
                $teamDetails = explode(" - ", $team);
                $hometeam = $teamDetails[0];
                $awayteam = $teamDetails[1];
                $leagueId = str_replace(" ", "", $country.$league);
                $tmpmatch = new offerMatch();
                $tmpmatch->setAttr('date', "$d.$m.$y");
                $tmpmatch->setAttr('time', $time);
                $tmpmatch->setAttr('hometeam', $hometeam);
                $tmpmatch->setAttr('hometeamid', createCode($hometeam));
                $tmpmatch->setAttr('awayteam', $awayteam);
                $tmpmatch->setAttr('awayteamid', createCode($awayteam));
                $tmpmatch->setAttr('datetime', "$y-$m-$d $time");
                $tmpmatch->setAttr('season', $season);
                $tmpmatch->setAttr('match', "$hometeam - $awayteam");
                $tmpmatch->setAttr('matchid', $code);
                $tmpmatch->setAttr('league', $league);
                $tmpmatch->setAttr('leagueid', $leagueId);
                $tmpmatch->setAttr('round', $round);
                array_push($listofmatches, $tmpmatch);
                unset($tmpmatch);
            }
        }
    }
    return $listofmatches;
}

function getBetexplorerMatchResult($id)
{
    $listofmatches = array();
    $url = "https://www.betexplorer.com/$id/results/";
    $currentDate = new DateTime();
    $context = stream_context_create(array(
        'http' => array(
            'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
        ),
    ));
    $html = file_get_html($url, false, $context);
    $tmphtml = $html->find('option[selected]')->fields();
    $season = trim($tmphtml[0]->plaintext);
    foreach ($html->find('table') as $table) {
        $round = null;
        $date = null;
        foreach ($table->find('tr') as $row) {
            $team = null;
            $hometeam = null;
            $awayteam = null;
            $code = null;
            foreach ($row->find('th') as $item) {
                switch ($item->class) {
                    case 'h-text-left':
                        $tmpround =explode('.', $item->plaintext)->fields();
                        $round = $tmpround[0];
                        break;
                }
            }
            foreach ($row->find('td') as $item) {
                switch ($item->class) {
                    case 'h-text-left':
                        $tmpcode = explode("/", $item->find('a', 0))->fields();
                        $code = $tmpcode[5];
                        $codepref = $tmpcode[4];
                        $country = ucwords(str_replace("-", " ", $tmpcode[2]));
                        $league = ucwords(str_replace("-", " ", $country . " " .($tmpcode[3])));
                        $team = $item->plaintext;
                        break;
                    case 'h-text-right h-text-no-wrap':
                        ($item->plaintext != "&nbsp;") ? $date = $item->plaintext : "";
                        break;
                    case 'h-text-center':
                        $ft = strip_tags($item->plaintext);
                        break;
                }
            }
            if ($team != null) {
                $time = "16:00";
                $dayDetails = explode(".", $date);
                if ($dayDetails[0] == "Today") {
                    $d = $currentDate->format("d");
                    $m = $currentDate->format("m");
                } elseif ($dayDetails[0] == "Yesterday") {
                    $currentDate->modify('-1 day');
                    $d = $currentDate->format("d");
                    $m = $currentDate->format("m");
                } else {
                    $d = $dayDetails[0];
                    $m = $dayDetails[1];
                }
                if (isset($dayDetails[2])) {
                    if ($dayDetails[2] != "") {
                        $y = $dayDetails[2];
                    } else {
                        $y = $currentDate->format("Y");
                    }
                } else {
                    $y = $currentDate->format("Y");
                }
                $teamDetails = explode(" - ", $team);
                $hometeam = $teamDetails[0];
                $awayteam = $teamDetails[1];
                $leagueId = str_replace(" ", "", $country . $league);
//                sleep(0.4);
                $tmpmatch = new offerMatch();
//                $resultDetails = getBetextplorerResult($code, $codepref, $id);
//                if (isset($resultDetails->time)) {
//                    $time = $resultDetails['time'];
//                }
//                if (isset($resultDetails)) {
//                    $tmpmatch->setAttr('ht', $resultDetails['ht']);
//                    $tmpmatch->setAttr('ft', $resultDetails['ft']);
//                }

                $tmpmatch->setAttr('date', "$d.$m.$y");
                $tmpmatch->setAttr('time', $time);
                $tmpmatch->setAttr('hometeam', $hometeam);
                $tmpmatch->setAttr('hometeamid', createCode($hometeam));
                $tmpmatch->setAttr('awayteam', $awayteam);
                $tmpmatch->setAttr('awayteamid', createCode($awayteam));
                $tmpmatch->setAttr('datetime', "$y-$m-$d $time");
                $tmpmatch->setAttr('season', $season);
                $tmpmatch->setAttr('match', "$hometeam - $awayteam");
                $tmpmatch->setAttr('matchid', $code);
                $tmpmatch->setAttr('league', $league);
                $tmpmatch->setAttr('leagueid', $leagueId);
                $tmpmatch->setAttr('round', $round);
                $tmpmatch->setAttr('ft', $ft);
                array_push($listofmatches, $tmpmatch);
                unset($tmpmatch);
//                echo "$round, $team, $date, $season, $d.$m.$y $time, $hometeam, $awayteam, $leagueId, $league, $country, $code, $resultDetails[ht], $resultDetails[ft] <br>";
            }
        }
    }
    return $listofmatches;
}
