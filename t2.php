<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 6.11.2018.
 * Time: 13:50
 */

include_once('includes/init.php');

function getBetextplorerResulttest($code)
{
    $result = array();
    $url = "https://www.betexplorer.com/soccer/denmark/superliga/brondby-fc-copenhagen/$code/";
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
                $resultHT = str_replace("(", "", explode(",", $item->plaintext))[0];
                break;
        }

    }

//    echo "$time, $resultFT, $resultHT";

    $result['time'] = $time;
    $result['ht'] = $resultHT;
    $result['ft'] = $resultFT;
    return $result;
}

//var_dump(getBetextplorerResulttest('E59NjI37'));

print_r(getBetextplorerResulttest('K6OshSas'));