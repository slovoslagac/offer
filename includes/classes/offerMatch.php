<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 19.9.2018.
 * Time: 14:29
 */

class offerMatch
{
    public $date = null;
    public $time = null;
    public $hometeam = null;
    public $hometeamid = null;
    public $awayteam = null;
    public $awayteamid = null;
    public $datetime = null;
    public $sportid = null;
    public $source = null;
    public $season = null;
    public $matchid = null;
    public $match = null;
    public $leagueid = null;
    public $league = null;
    public $round = null;
    public $ht =null;
    public $ft = null;

    public function setAttr($attr, $val)
    {
        $this->$attr = $val;
    }

    public function getData()
    {
        var_dump(get_object_vars($this));
    }

    public function addResult($m, $v, $rt, $sr, $sp){
        global $conn;
        $ins = $conn->prepare("insert into ulaz_results (utk_id, value, result_type, source_id, sport_id) values (:m, :v, :rt, :sr, :sp)");
        $ins->bindParam(":m", $m);
        $ins->bindParam(":v", $v);
        $ins->bindParam(":rt", $rt);
        $ins->bindParam(":sr", $sr);
        $ins->bindParam(":sp", $sp);
        $ins->execute();
    }

    public function checkResult(){
        if($this->ht != null){
            $this->addResult($this->matchid, $this->ht, 'halfTimeScore', $this->source, $this->sportid);
        } elseif ($this->ft != null) {
            $this->addResult($this->matchid, $this->ft, 'fullTimeScore', $this->source, $this->sportid);
        }
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
        $this->checkResult();
    }
}