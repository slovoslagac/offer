<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 19.9.2018.
 * Time: 14:29
 */

class tableMatch
{
    public $team = null;
    public $leagueId = null;
    public $played = 0;
    public $won = 0;
    public $draw = 0;
    public $loses = 0;
    public $goalDiff = null;
    public $points = 0;
    public $position = null;
    public $country_id = null;
    public $source = null;
    public $teamId = null;
    public $cmpType = null;
    public $sportId = null;
    public $findArray = array(" ", ";", ",", "&nbsp","&nbsp;");


    public function setAttr($attr, $val)
    {
        $this->$attr = trim(str_replace($this->findArray, '', $val));
    }

    public function getData()
    {
        var_dump(get_object_vars($this));
    }

    public function createTeamCode()
    {

        $tmpcode = strtolower($this->country_id . trim(str_replace($this->findArray, '', $this->team)));
        $this->teamId = $tmpcode;
        unset($tmpcode);
    }

    public function add_match()
    {
        if( $this->teamId == null){$this->createTeamCode();}
        global $conn;
        $team = $this->team;
        $played = $this->played;
        $leagueId = $this->leagueId;
        $won = $this->won;
        $draw = $this->draw;
        $loses = $this->loses;
        $source = $this->source;
        $position = $this->position;
        $countryId = $this->country_id;
        $goalDiff = $this->goalDiff;
        $points = $this->points;
        $teamId = $this->teamId;
        $cmpType = $this->cmpType;
        $sport = $this->sportId;
        $insert_new_match = $conn->prepare("insert into ulaz_table (liga_id, team, played, won, draw, loses, goal_diff, points, position, country_id, source, teamid, cmpType, sport_id)
                                            values(:leagueId,:team,:played,:won,:draw,:loses,:gdiff,:pt,:pos,:countryId,:source, :teamId, :cmpTp, :spid)");
        $insert_new_match->bindParam(':draw', $draw);
        $insert_new_match->bindParam(':team', $team);
        $insert_new_match->bindParam(':played', $played);
        $insert_new_match->bindParam(':leagueId', $leagueId);
        $insert_new_match->bindParam(':won', $won);
        $insert_new_match->bindParam(':loses', $loses);
        $insert_new_match->bindParam(':source', $source);
        $insert_new_match->bindParam(':pos', $position);
        $insert_new_match->bindParam(':countryId', $countryId);
        $insert_new_match->bindParam(':gdiff', $goalDiff);
        $insert_new_match->bindParam(':pt', $points);
        $insert_new_match->bindParam(':teamId', $teamId);
        $insert_new_match->bindParam(':cmpTp', $cmpType);
        $insert_new_match->bindParam(':spid', $sport);
        $insert_new_match->execute();
    }
}