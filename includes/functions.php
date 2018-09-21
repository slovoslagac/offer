<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 21.9.2018.
 * Time: 11:13
 */

function convetDate($date, $time)
{
    $tmp = explode("-", $date);
    $newDate = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0] . ' ' . $time;
    return $newDate;
}

function createCode($val)
{
    $replacelist = array('(', ')', '\'', '\\', '\/', '`', ' ', '-');
    $tmp = strtolower(str_replace($replacelist, '', str_replace(' ', '_', trim($val))));
    return $tmp;
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
                $tmpmatch = new xscoresMatch();
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