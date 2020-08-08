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
 
if (isset($_GET['files']))
{
include_once("../cfg/general.inc.php");
include_once("../cfg/connect.inc.php");
include_once("../cfg/functions.php");
}
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
header("Status: 404 Not Found");

$CHARSET='windows-1251';
if (DB_CHARSET!='cp1251') $CHARSET='UTF-8';

$html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >';
$html.="<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"".$CHARSET."\" />
<title>404 Запрошенная Вами страница не найдена</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://".CONF_SHOP_URL."/images/backend/install.css\" />
</head>
<body>
<div id=\"container\">
  <div id=\"header\"><a href=\"http://".CONF_SHOP_URL."/\"><img src=\"http://".CONF_SHOP_URL."/images/logo0000.png\" alt='".CONF_SHOP_NAME."' title='".CONF_SHOP_NAME."'/></a></div>
  <div id='content'>
    <div id='content_top'></div>
    <div id='content_middle'>
      <h1>404 Страница не найдена</h1>
      <div style='width: 100%; display: inline-block; padding: 40px;'>
	Страница, которую вы запросили, отсутствует на нашем портале.<br /> 
	Возможно, вы ошиблись при наборе адреса или перешли по неверной ссылке.
	<ul>
	  <li>проверьте правильность написания</li>
	  <li>воспользуйтесь <a href='http://".CONF_SHOP_URL."/'>главной страницей</a></li>
	</ul>
      </div>
    </div>
    <div id='content_bottom'></div>
  </div>
  <div id='footer'><a onclick=\"window.open('http://legosp.net');\">Project Homepage</a>|<a onclick=\"window.open('http://legosp.net/info/doc/');\">Documentation</a>|<a onclick=\"window.open('http://forum.legosp.net');\">Support Forums</a></div>
</div>
</body>
</html>";

if (DB_CHARSET!='cp1251') $html=win2utf($html);
echo $html;
exit;
?>