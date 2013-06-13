<?php

session_start(); //launch sessions

include_once('functions.php');

if (!check_login()) //Проверяем не пришло ли данных с формы авторизации
{
  show_login_page();
  show_reg_page();
}
else
{
  if(check_auth())
  {
    //Если авторизация в порядке, загружаем игровые функции
  }
  else
  {
    echo "Hacking attempt! Logs sent to admins!";
  }
}
