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
 
function my_error_handler($code, $msg, $file, $line) {
    if ($code == E_WARNING)
	{
	//write log file
	$f = fopen("./cfg/error.log","a");
	$time = date("d.m.y H:i");
	fputs($f, "[$time] Error $msg (code: $code) in $file (line: $line)\r\n");
	fclose($f);

include_once("./cfg/general.inc.php");
include_once("./cfg/connect.inc.php");
include_once("./cfg/functions.php");


$CHARSET='UTF-8';

	$error= '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$CHARSET.'" />
<title>Приносим наши извинения!</title>
<link rel="stylesheet" type="text/css" href="http://'.CONF_SHOP_URL.'/images/backend/install.css" />
</head>

<body>
<div id="container">
  <div id="header"><a href="http://'.CONF_SHOP_URL.'/"><img src="http://'.CONF_SHOP_URL.'/css/css_default/image/logo0000.png" alt="legosp" title="legosp" /></a></div>
  <div id="content">
    <div id="content_top"></div>
    <div id="content_middle">
      <h1>Приносим наши извинения!</h1>
      <div style="width: 100%; display: inline-block; padding: 40px;">
	В настоящее время ведуться технические работы на сервере.<br />
	Пожалуйста, зайдите позднее.<br /> 
      </div>
    </div>
    <div id="content_bottom"></div>
  </div>
  <div id="footer"><a onclick="window.open("http://legosp.net");">Project Homepage</a>|<a onclick="window.open("http://legosp.net/");">Documentation</a>|<a onclick="window.open("http://forum.webasyst.ru/viewforum.php?id=13");">Support Forums</a></div>
</div>
</body>
</html>';

echo $error;
	exit;
	}
}

set_error_handler('my_error_handler');

?>