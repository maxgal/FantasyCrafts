<?php

session_start(); //launch sessions

if (!check_login()) //Проверяем не пришло ли данных с формы авторизации
{
  show_login_page();
  show_reg_page();
}
