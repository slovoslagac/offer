<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 25.9.2018.
 * Time: 14:28
 */
include_once('includes/init.php');

$tables = getCurrTables();

$file = fopen('tabele.json', 'w');
fwrite($file, json_encode($tables));
fclose($file);


$alltables = getAllTables();
$file = fopen('tabeleAll.json', 'w');
fwrite($file, json_encode($alltables));
fclose($file);
?>


