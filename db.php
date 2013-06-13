<?php

$dbuser = "";
$dbpass = "";
$dbname = "";
$dbserver = "";
$dbport = "";

$link = mysql_connect($dbserver, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname, $link);

?>
