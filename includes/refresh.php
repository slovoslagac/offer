<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 8.11.2018.
 * Time: 15:11
 */

function refreshTable(){
    $tables = getCurrTables();

    $file = fopen('tabele.json', 'w');
    fwrite($file, json_encode($tables));
    fclose($file);


    $alltables = getAllTables();
    $file = fopen('tabeleAll.json', 'w');
    fwrite($file, json_encode($alltables));
    fclose($file);
}