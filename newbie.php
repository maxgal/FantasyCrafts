<?php

session_start();

require_once('db.php');
include_once('functions.php');

if(isset($_GET['start_next']))
{
    $pers_name = $_SESSION['pers_name'];
    $pers_name = htmlspecialchars($pers_name);
    $query = "select first_play from pers_users where pers_name='".$pers_name."'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $page = $row['first_play'] + 1;
    if($page == 9) $page = 9;
    show_text($page);
}
else
{
    echo "Hacking attempt! Logs sent to admins!";
}
