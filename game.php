<?php

session_start(); //launch sessions

require_once('db.php');
include_once('functions.php');

  if(check_auth($_SESSION['pers_name']), $_SESSION['id_uniq'])
  {
    //Если авторизация в порядке, загружаем игровые функции
    if(check_start())
    {      
    }
    else
    {
      load_room(); //Загружаем местонахождение персонажа
      load_items(); //Загружаем игровые предметы в локации
      load_mobs(); //Загружаем неписей
      load_pers(); //Загружаем других игроков в локации
    }
  }
  else
  {
    echo "Hacking attempt! Logs sent to admins!";
  }

