<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 30.10.2018.
 * Time: 09:56
 */


include_once('includes/init.php');


$url = "https://www.betexplorer.com/soccer/england/vanarama-national-league/standings/?table=table&table_sub=overall&ts=C4s2JXFd&dcheck=0";
$competition_id = 1;
$country_id = 189;
$context = stream_context_create(array(
    'http' => array(
        'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
    ),
));



$html = file_get_html($url);
if(!$html)



?>