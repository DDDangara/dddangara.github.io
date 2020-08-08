<?php
/*********************************************************************************
*                                                                                *
*   shop-script Legosp - legosp.net                                              *
*   Skype: legoedition                                                           *
*   Email: legoedition@gmail.com                                                 *
*   Лицензионное соглашение: https://legosp.net/info/litsenzionnoe_soglashenie/  *
*   Copyright (c) 2010-2019  All rights reserved.                                *
*                                                                                *
*********************************************************************************/
if (isset($_GET['cust_login']))
{
  $smarty->assign("main_content_template", "cust_login_page.tpl.html");
}
elseif (isset($_POST["cust_login"]) && !isset($_SESSION['cust_id']))
{
  $smarty->assign("main_content_template", "cust_login_page.tpl.html");
}
?>