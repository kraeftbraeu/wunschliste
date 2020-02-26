<?php

$link = false;
{
    /** docker **/
    $dbServername = "mysql";
    $dbUsername = "dummyu";
    $dbPassword = "dummyp";
    $dbName = "wunschliste";
    $pathToVendor = "";

    try {
    $link = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
    if (!$link)
        die('<p>keine SQL-Verbindung möglich!</p>');
    mysqli_set_charset($link,'utf8');
    } catch(Exception $e) { echo $e->getMessage(); }
}
if($link === false) {
    die('<p>keine SQL-Verbindung möglich!</p>');
}

$GLOBALS["logDb"] = false;
?>
