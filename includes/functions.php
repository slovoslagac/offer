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


function getLinks()
{
    global $conn;
    $allLinks = $conn->prepare('select link, cl.competition_id, ic.country_id, ic.sport_id, source_id
from init_competition ic, competition_links cl
where ic.id = cl.competition_id
and cl.competition_id < 100000');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

function getConnLinks()
{
    global $conn;
    $allLinks = $conn->prepare('select id, mozzart, sport_id
  from init_competition
  where id not in (select competition_id from competition_links)
  and sport_id in (1,2,4,6,7,58,59)
  and id != 9999999
  order by 3,2');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

function getConnectedLinks()
{
    global $conn;
    $allLinks = $conn->prepare('select id, mozzart, sport_id
from init_competition
where id  in (select competition_id from competition_links)
and sport_id in (1,2,4,6,7,58,59)');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

function getCurrLinks()
{
    global $conn;
    $allLinks = $conn->prepare('select link, cl.competition_id, ic.country_id, ic.sport_id, source_id
from init_competition ic, competition_links cl
where ic.id = cl.competition_id
and ic.id in (select distinct im.competition_id 
from init_match im
where im.start_time > timestamp(current_date(), "9:57")
and im.start_time < timestamp(CURRENT_DATE()+ interval 1 day, "10:00"))');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}


function getSpecLinks()
{
    global $conn;
    $allLinks = $conn->prepare('select link, cl.competition_id, ic.country_id, ic.sport_id, source_id
from init_competition ic, competition_links cl
where ic.id = cl.competition_id
and ic.id in (10261)
and cl.competition_id < 100000');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

function getCmpLink($id)
{
    global $conn;
    $allLinks = $conn->prepare("select link, cl.competition_id, ic.country_id, ic.sport_id, source_id
from init_competition ic, competition_links cl
where ic.id = cl.competition_id
and ic.id = $id
and cl.competition_id < 100000");

    $allLinks->execute();
    $result = $allLinks->fetch(PDO::FETCH_OBJ);
    return $result;
}

function getSportLinks($id)
{
    global $conn;
    $allLinks = $conn->prepare('select link, cl.competition_id, ic.country_id, ic.sport_id, source_id
from init_competition ic, competition_links cl
where ic.id = cl.competition_id
and ic.sport_id in (:id)
and cl.competition_id < 100000');
$allLinks->bindParam(":id", $id);
    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);
    return $result;
}


function getMozzartTeams($id)
{
    global $conn;
    $allLinks = $conn->prepare("select distinct c.country_id, t.name, t.id
from init_team_competition itc, init_competition c, init_team t
where c.id = itc.competition_id
and c.country_id != 199
and c.sport_id = $id
and season_id > 22
and itc.team_id = t.id
and c.name not like '%SLOBOD%'
union all
select distinct 199, t.name, home_team_id
from init_match it, init_team t
where it.season_id > 22
and it.home_team_id = t.id
and it.competition_id in (select id from init_competition where country_id = 199)
and it.init_sport_id = $id 
order by 1,2");

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);

    $tmpcmp = null;
    $tmparray = array();
    $allTeams = array();

    foreach ($result as $item) {
        if ($item->country_id != $tmpcmp) {
            $tmpcmp = $item->country_id;
            $tmparray = array();
        }
        array_push($tmparray, $item);
        $allTeams[$item->country_id] = $tmparray;
    }

    return $allTeams;
}

function getConnTeams($id)
{
    global $conn;
    $allLinks = $conn->prepare("select distinct t.id, t.name, t.country_id, c.country
from table_teams t, init_country c
where t.id not in (select table_conn_team.table_team_id  FROM table_conn_team )
and t.country_id = c.id
and t.sport_id = $id
order by c.country, t.name
limit 100");

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);

    return $result;
}


function getCurrentOffer()
{
    global $conn;
    $offer = $conn->prepare('select *
from
(
select distinct ic.mozzart, ic.id, ic.sport_id
from init_match im, init_competition ic
where im.competition_id = ic.id
and im.start_time > timestamp(current_date(), "9:57")
and im.start_time < timestamp(CURRENT_DATE()+ interval 1 day, "10:00")
and ic.sport_id in (1,2,4,6,7,9,10,58,69)) c
left join 
(select distinct liga_id, `TIMESTAMP` timerefresh
from ulaz_table
) t
on c.id = t.liga_id
order by c.sport_id, 1;');
    $offer->execute();
    $result = $offer->fetchAll(PDO::FETCH_OBJ);
    return $result;
}


function getAllTables()
{
    global $conn;
    $allLinks = $conn->prepare('select ut.position, ut.team, ut.played, ut.won, ut.draw, ut.loses, ut.goal_diff, ut.points, ut.name, ut.teamid, ut.cmpid, ut.sport_id, ut.cmpType, team.mozzart
from
(
select t.position, team, t.played, t.won, t.draw, t.loses, t.goal_diff, t.points, ic.name, teamid, ic.id cmpid, t.cmpType, ic.sport_id, ic.country_id
from ulaz_table t, init_competition ic
where t.liga_id = ic.id
) ut
left join
(select tt.teamid, it.mozzart, tt.sport_id, tt.country_id
from table_conn_team tct, init_team it, table_teams tt
where tct.init_team_id = it.id
and tct.table_team_id = tt.id) team
on ut.teamid = team.teamid and ut.sport_id = team.sport_id and team.country_id = ut.country_id
order by ut.sport_id, name, cmpType, ut.position');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);

    return $result;
}

function getCurrTables()
{
    global $conn;
    $allLinks = $conn->prepare('select ut.position, ut.team, ut.played, ut.won, ut.draw, ut.loses, ut.goal_diff, ut.points, ut.name, ut.teamid, ut.cmpid, ut.sport_id, ut.cmpType, team.mozzart
from
(
select t.position, team, t.played, t.won, t.draw, t.loses, t.goal_diff, t.points, ic.name, teamid, ic.id cmpid, t.cmpType, ic.sport_id, ic.country_id
from ulaz_table t, init_competition ic
where t.liga_id = ic.id
and ic.id in (select distinct im.competition_id 
from init_match im
where im.start_time > timestamp(current_date(), "9:57")
and im.start_time < timestamp(CURRENT_DATE()+ interval 1 day, "10:03"))
) ut
left join
(select tt.teamid, it.mozzart, tt.sport_id, tt.country_id
from table_conn_team tct, init_team it, table_teams tt
where tct.init_team_id = it.id
and tct.table_team_id = tt.id) team
on ut.teamid = team.teamid and ut.sport_id = team.sport_id and team.country_id = ut.country_id
order by ut.sport_id, name, cmpType, ut.position');

    $allLinks->execute();
    $result = $allLinks->fetchAll(PDO::FETCH_OBJ);

    return $result;
}


function calculatePoints($id, $p, $n, $i, $pn)
{
    $tmpres = 0;
    if ($id == 1 || $id == 9) {
        $tmpres = $p * 3 + $n;
    } else if ($id == 2) {
        $tmpres = $p * 2 + $i;
    } else if ($id == 7) {
        $tmpres = $p * 2 + $n;
    } else if ($id == 4) {
        $tmpres = $pn;
    }


    return $tmpres - $pn;


}


function dellLeagueTable($id)
{
    global $conn;
    $sql = $conn->prepare("delete from ulaz_table where liga_id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();
}


function addLeagueTable($id)
{

    $item = getCmpLink($id);

    $allTablesData = array();


    sleep(1);
    try {
        if ($item->source_id == 14) {
            $tmpdata = getTableBetxplorer($item->link, $item->competition_id, $item->country_id, $item->sport_id);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        } elseif ($item->source_id == 15 && $item->sport_id == 1) {
            $tmpdata = getTableSoccerway($item->link, $item->competition_id, $item->country_id, 1);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        } elseif ($item->source_id == 16) {
            $tmpdata = getTableSofaScore($item->link, $item->competition_id, $item->country_id, $item->sport_id);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        }
    } catch (Exception $e) {

    }


    foreach ($allTablesData as $row) {
        echo $row->team, $row->position, $row->leagueId . '<br>';
    }


    dellLeagueTable($id);

    foreach ($allTablesData as $item) {
        $match = new tableMatch();
        $match = $item;
        $match->add_match();
    }


}

function addTableTeams()
{
    global $conn;
    $sql = $conn->prepare('insert into table_teams (
   name
  ,teamid
  ,country_id
  ,source_id
  ,sport_id
) select distinct team, teamid, country_id, source, sport_id
from ulaz_table
where (teamid, sport_id) not in (select teamid, sport_id from table_teams)
;');
    $sql->execute();
}

function addTableTeamsBySport($id)
{
    global $conn;
    $sql = $conn->prepare('insert into table_teams (
   name
  ,teamid
  ,country_id
  ,source_id
  ,sport_id
) select distinct team, teamid, country_id, source, sport_id
from ulaz_table
where (teamid, sport_id) not in (select teamid, sport_id from table_teams)
and sport_id = :id
;');
    $sql->bindParam(":id", $id);
    $sql->execute();
}

function getLeagues($lim)
{
    global $conn;
    $sql = $conn->prepare("select c.mozzart, cl.timestamp, cl.source_id, cl.link
from competition_links cl, init_competition c
where cl.competition_id = c.id 
order by timestamp desc limit $lim");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

function getLastConnTeams($lim = 20){
    global $conn;
    $sql=$conn->prepare("select t.mozzart, tt.name, tct.id, s.name sport, tt.source_id 
from table_conn_team tct, table_teams tt, init_team t, init_sport s
where tct.table_team_id = tt.id
and tct.init_team_id = t.id
and t.sport_id = s.id
order by s.id ,1
limit $lim");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

