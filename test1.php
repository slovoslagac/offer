<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 25.9.2018.
 * Time: 14:28
 */
include_once('includes/init.php');
$url = 'http://www.scoresway.com/?sport=american_football&page=competition&id=1';
$competition_id = 1;
$country_id = 189;
$sport_id = 1;

$data = getTableScoresway($url,$competition_id, $country_id, $sport_id);

var_dump($data);

?>


