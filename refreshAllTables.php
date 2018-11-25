<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 25.9.2018.
 * Time: 14:28
 */
include_once('includes/init.php');
$file = fopen('CrolLog.txt', 'a');
fwrite($file, date('Y-n-d H:i') . " pocetak skidanja podataka \n");
$allLinks = getLinks();

$allTablesData = array();

foreach ($allLinks as $item) {
    $file = fopen('CrolLog.txt', 'a');
    fwrite($file, date('Y-n-d H:i') . " skidam $item->link, $item->competition_id, $item->country_id, $item->sport_id \n");
    sleep(1);
    try {
        if ($item->source_id == 14) {
            $tmpdata = getTableBetxplorer($item->link, $item->competition_id, $item->country_id, $item->sport_id);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        } elseif ($item->source_id == 15) {
            $tmpdata = getTableSoccerway($item->link, $item->competition_id, $item->country_id, 1);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        } elseif ($item->source_id == 16) {
            $tmpdata = getTableSofaScore($item->link, $item->competition_id, $item->country_id, $item->sport_id);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        } elseif ($item->source_id == 17) {
            $tmpdata = getTableScoresway($item->link, $item->competition_id, $item->country_id, $item->sport_id);

            $allTablesData = array_merge($allTablesData, $tmpdata);
        }
    } catch (Exception $e) {

    }
}

foreach ($allTablesData as $row) {
    echo $row->team, $row->position, $row->leagueId . '<br>';
}

try {
    $del = 'truncate ulaz_table';
    $prep = $conn->prepare($del);
    $prep->execute();
} catch (exception $e) {
    $file = fopen('log.txt', 'a');
    fwrite($file, date('Y-n-d H:i') . "frcnulo nesto kod brisanja tabele $e \n");
}
try {
    foreach ($allTablesData as $item) {
        $match = new tableMatch();
        $match = $item;
        $match->add_match();
    }
} catch (exception $e) {
    $file = fopen('log.txt', 'a');
    fwrite($file, date('Y-n-d H:i') . "frcnulo nesto kod upisa podatka $e \n");
}
$file = fopen('CrolLog.txt', 'a');
fwrite($file, date('Y-n-d H:i') . " zavrseno skidanje podataka \n");

addTableTeams();

?>


