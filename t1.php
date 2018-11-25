<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 5.11.2018.
 * Time: 14:02
 */

include_once('includes/init.php');
//$currentDate = new DateTime();
//$listofmatches = array();


$url = "https://www.soccerway.com/handball/russia/superleague/standings/?table=table&table_sub=overall&ts=UTJKrdXG&dcheck=0";

function checkLink($url)
{
    if (strpos($url, 'betexplorer') == true) {
        return 14;
    } else if (strpos($url, 'soccerway') == true) {
        return 15;
    }
}


echo checkLink($url);

