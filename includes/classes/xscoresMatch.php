<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 19.9.2018.
 * Time: 14:29
 */

class xscoresMatch
{
    public $date = null;
    public $time = null;
    public $hometeam = null;
    public $hometeamid = null;
    public $awayteam = null;
    public $awayteamid = null;
    public $datetime = null;
    public $sportid = 4;
    public $source = 11;
    public $season = null;
    public $matchid = null;
    public $match = null;
    public $leagueid = null;
    public $league = null;
    public $round = null;

    public function setAttr($attr, $val)
    {
        $this->$attr = $val;
    }

    public function getData()
    {
        var_dump(get_object_vars($this));
    }

    public function add_match()
    {
        global $conn;
        $hoTe = $this->hometeam;
        $hoTeId = $this->hometeamid;
        $viTe = $this->awayteam;
        $viTeId = $this->awayteamid;
        $st = $this->datetime;
        $sp = $this->sportid;
        $sr = $this->source;
        $ss = $this->season;
        $liid = $this->leagueid;
        $li = $this->league;
        $utid = $this->matchid;
        $ut = $this->match;
        $rn = $this->round;
        $insert_new_match = $conn->prepare("insert into ulaz_new (starttime, liga_id, liga, utk_id, utakmica, dom_id, dom, gost_id, gost, sport_id, source, season, round)
                                            values(:st, :liId, :li, :utId, :ut, :hoTeId, :hoTe, :viTeId, :viTe, :sp, :sr, :ss, :rn)");
        $insert_new_match->bindParam(':st', $st);
        $insert_new_match->bindParam(':hoTe', $hoTe);
        $insert_new_match->bindParam(':hoTeId', $hoTeId);
        $insert_new_match->bindParam(':viTe', $viTe);
        $insert_new_match->bindParam(':viTeId', $viTeId);
        $insert_new_match->bindParam(':sp', $sp);
        $insert_new_match->bindParam(':sr', $sr);
        $insert_new_match->bindParam(':ss', $ss);
        $insert_new_match->bindParam(':liId', $liid);
        $insert_new_match->bindParam(':li', $li);
        $insert_new_match->bindParam(':utId', $utid);
        $insert_new_match->bindParam(':ut', $ut);
        $insert_new_match->bindParam(':rn', $rn);
        $insert_new_match->execute();
    }
}