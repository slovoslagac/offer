<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 14.11.2018.
 * Time: 11:20
 */

class competitionLink
{
    public $competition_id = null;
    public $link = null;
    public $source_id = null;
    public $status = 1;


    public function setAttr($attr, $val)
    {
        $this->$attr = $val;
        if($attr == 'link'){
            $this->checkLink();
        }
    }

    public function checkLink()
    {
        $url = $this->link;
        if (strpos($url, 'www.betexplorer.com') == true) {
            $this->source_id = 14;
        } else if (strpos($url, 'soccerway.com') == true) {
            $this->source_id = 15;
        } else if (strpos($url, 'scoresway.com') == true) {
            $this->source_id = 17;
        } else {
            $this->source_id = null;
        }
    }

    public function addLink(){
        if($this->competition_id != null && $this->source_id != null) {
            global $conn;
            $sql = $conn->prepare('insert into competition_links (competition_id,link,source_id, status) VALUES (:cl, :l, :so, :st)');
            $sql->bindParam(":cl", $this->competition_id);
            $sql->bindParam(":l", $this->link);
            $sql->bindParam(":so", $this->source_id);
            $sql->bindParam(":st", $this->status);
            $sql->execute();
        }
}

    public function deleteLink(){

            global $conn;
            $sql = $conn->prepare('delete from competition_links where competition_id = :id');
            $sql->bindParam(":id", $this->competition_id);
            $sql->execute();

    }





}