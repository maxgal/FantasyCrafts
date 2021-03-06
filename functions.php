<?php

function show_login_page() 
{
    echo "<form action='./game.php' method='post'>";
    echo "<input type=text name=pers_name size=30/><br/>";
    echo "<input type=text name=pwd size=30/><br/>";
    echo "<input type=submit name=logon value='Enter'/>";
    echo "</form>";
}

function show_reg_page()
{
    echo "<form action='reg.php' method='post'>";
    echo "<input type=text name=pers_name size=30/><br/>";
    echo "<input type=text name=email size=30/><br/>";
    echo "<input type=submit name=reg value='Create Pers'/>";
    echo "</form>";
}

function check_auth($pers_name = '', $uniq = '')
{
    /*Каждый раз при авторизации, в базу данных в таблицу auth_users заносится имя перса и случайное число. Когда
    выполняются какие то действия, проверяется авторизирован ли пользователь
    */
    $pers_name = htmlspecialchars($pers_name);
    $uniq = (int) $uniq;
    $query = "select pers_name, uniq from auth_users where pers_name='".$pers_name."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0)
    {
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        if($row['uniq'] == $uniq) return true;
        else return false;
    }
    else return false;
}

function create_new_user($pers_name = '', $email = '', $pwd = '')
{
    /*
        Проверяется нет ли такого пользователя. Если нет, записываем в таблицу pers_users. В поле first_play
        ставим 1, что означает что игрок только создал персонажа
    */
    $pers_name = htmlspecialchars($pers_name);
    $query = "select pers_name from pers_users where pers_name='".$pers_name."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0) show_error("This name is already taken!");
    else
    {
        $email = htmlspecialchars($email);
        $pwd = htmlspecialchars($pwd);
        $pwd_md5 = md5(md5($pwd));
        $query = "insert into pers_users (pers_name, email, pwd, first_play) values ('".$pers_name."', '".$email."', '".$pwd_md5."', '1')";
        mysql_query($query);
        first_login($pers_name, $pwd);
    }
}

function first_login($pers_name = '')
{
    /*
        Проверяет есть ли в таблице auth_users запись с данным персонажем, если есть обновляем, если нет, вставляем
    */
    $pers_name = htmlspecialchars($pers_name);
    $query = "select pers_name from auth_users where pers_name='".$pers_name."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0)
    {
        $uniq = RAND(1111111, 99999999);
        $query = "update auth_users set uniq='".$uniq."' where pers_name='".$pers_name."'";
        mysql_query($query);    
    }
    else
    {
        $uniq = RAND(1111111, 99999999);
        $query = "insert into auth_users (pers_name, uniq) values ('".$pers_name."', '".$uniq."')";
    }
    
    $_SESSION['pers_name'] = $pers_name;
    $_SESSION['id_uniq'] = $uniq;
    return true;
}

function check_login()
{
    if(isset($_POST['logon']))
    {
        $pers_name = htmlspecialchars($_POST['pers_name']);
        $pwd = md5(md5($_POST['pwd']));
        $query = "select pers_name from pers_users where pers_name='".$pers_name."' and pwd='".$pwd."'";
        $result = mysql_query($query);
        if(mysql_num_rows($result) > 0) first_login($pers_name);
    }
}

function check_start()
{
    //Данная функция проверяет активно ли обучение и если да, выводит необходимый текст
    $pers_name = htmlspecialchars($_SESSION['pers_name']);
    $query = "select first_play from pers_users where pers_name='".$pers_name."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0)
    {
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        if($row['first_play'] > 1 && $row['first_play'] < 9)
        show_text($row['first_play']);
        return true;
    }
    return false;
}

function show_text($page = '')
{
    $page = (int) $page;
    $query = "select msg from game_texts where id='".$page."'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    echo $row['msg'];
    echo "<br/><a href='./newbie.php?start_next='>Продолжить</a>";
}
