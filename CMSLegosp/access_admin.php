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
 
	ini_set("display_errors", "1");
	session_start();
        define('ROOT_DIR', getcwd());  
	include("./cfg/connect.inc.php");
	include("./includes/database/mysql.php");
	include("./cfg/functions.php");
        include("./cfg/general.inc.php");

	//current language
	include("./cfg/language_list.php");
	if (!isset($_SESSION["current_language"]) ||
		$_SESSION["current_language"] < 0 || $_SESSION["current_language"] > count($lang_list))
			$_SESSION["current_language"] = 0; //set default language

	if (isset($lang_list[$_SESSION["current_language"]]) && file_exists("./languages/".$lang_list[$_SESSION["current_language"]]->filename))
		include("./languages/".$lang_list[$_SESSION["current_language"]]->filename); //include current language file
	else
	{
		die("<font color=red><b>ERROR: Couldn't find language file!</b></font>");
	}

	$errorStr = "";

	if (isset($_POST["authorize"]))
	{
		if (!strcmp(base64_encode($_POST["login"]), ADMIN_LOGIN) && !strcmp(md5($_POST["password"]), ADMIN_PASS))
		{ //login ok
			$_SESSION["log"] = ADMIN_LOGIN;
			$_SESSION["pass"] = ADMIN_PASS;
			$_SESSION["access"] = 3;
			$_SESSION["log_name"] = ACCESS_ADMIN;

			//redirect to the admin interface
			header("Location: admin.php");
		}
		else
		{ // not admin

			//connect to database
			db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
			db_select_db(DB_NAME) or die (db_error());

			$q = db_query("SELECT manager, password, access, online_name, ID FROM ".MANAGER_TABLE.' WHERE manager='.int_text($_POST["login"])) or die (db_error());
			$row = db_fetch_row($q);

			if (!strcmp($_POST["login"], $row[0]) && !strcmp($_POST["password"], $row[1]) && $row) 
				{
				$_SESSION["log"] = $row[0];
				$_SESSION["pass"] = $row[1];
				$_SESSION["access"] = $row[2];
				$_SESSION["log_name"] = $row[3];
				$_SESSION["manager_id"] = $row[4];

				//redirect to the admin interface
				header("Location: admin.php");
				}
		}
		$errorStr = ACCESS_ERROR;
               
	}
        
        require 'smarty/smarty.class.php';
        $smarty = new Smarty; //core smarty object
        $smarty->assign("errorStr",  $errorStr); 
        $smarty->display("./core/admin_tmpl/login.tpl.html");
?>