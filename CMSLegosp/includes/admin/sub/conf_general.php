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
 

	//general settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "general"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		  $smarty->assign("configuration_saved", 1);
		if (isset($_GET["error"]) && $_GET["error"] == 1) $smarty->assign("error", 1);
		if (isset($_POST["save_general"])) //save system settings
		{
			//general settings
			if (!isset($_POST["currency_auto"])) $_POST["currency_auto"] = 0;
			if (!strcmp($_POST["currency_auto"], "on")) $_POST["currency_auto"] = 1;
			else $_POST["currency_auto"] = 0;			
                        if (!isset($_POST["chpu"])) $_POST["chpu"]=0; else  $_POST["chpu"]=1;
                        if (!isset($_POST["smtp"])) $_POST["smtp"]=0;  
                        if (!isset($_POST["currency_id"])) $_POST["currency_id"]=0;

		
			if (is_writable("./".CONF_ADMIN_FILE) && $_POST["admin_file"] != CONF_ADMIN_FILE) {
				rename("./".CONF_ADMIN_FILE, $_POST["admin_file"]);
			}
			if (is_writable("./".CONF_ADMIN_FILE_ACCESS) && $_POST["admin_file_access"] != CONF_ADMIN_FILE_ACCESS) {
				rename("./".CONF_ADMIN_FILE_ACCESS, $_POST["admin_file_access"]);
			}
			if (is_writable("./.htaccess") && $_POST["admin_file"] != CONF_ADMIN_FILE) {
				$f = file("./.htaccess");
				$cf = count($f);
					for ($i = 0; $i <= $cf; $i++) {
						$tmp .= $f[$i];
					}
						$chto = str_replace(".php", "", CONF_ADMIN_FILE);
						$nachto = str_replace(".php", "", $_POST["admin_file"]);
					$tmp = preg_replace("/ ".$chto."/", " ".$nachto, $tmp);
				$sh = fopen('./.htaccess',"w") or die("<p>Нет прав для записи</p>");
				flock($sh, 2);
				fputs($sh, $tmp);
				flock($sh, 3);
				fclose($sh);
			}
		
		
			
			if ($_POST["admin_file"] != CONF_ADMIN_FILE) {
					$admin_file = $_POST["admin_file"];
					$location = $_POST["admin_file"];
				} else {
					$location = CONF_ADMIN_FILE;
					$admin_file = CONF_ADMIN_FILE;
			}
		
		
			if ($_POST["admin_file_access"] != CONF_ADMIN_FILE_ACCESS) {
					$admin_file_access = $_POST["admin_file_access"];
				} else {
					$admin_file_access = CONF_ADMIN_FILE_ACCESS;
			}
		

			$f = fopen("./cfg/general.inc.php","w");
			fputs($f,"<?php\n\tdefine('CONF_SHOP_NAME', '".__escape_string($_POST["shop_name"])."');\n");
			fputs($f,"\tdefine('CONF_SHOP_DESCRIPTION', '".str_replace("'","\'",stripslashes($_POST["shop_description"]))."');\n");
			fputs($f,"\tdefine('CONF_SHOP_KEYWORDS', '".str_replace("'","\'",stripslashes($_POST["shop_keywords"]))."');\n");
			fputs($f,"\tdefine('CONF_SHOP_URL', '".__escape_string($_POST["shop_url"])."');\n");
			fputs($f,"\tdefine('CONF_GENERAL_EMAIL', '".__escape_string($_POST["general_email"])."');\n");
			fputs($f,"\tdefine('CONF_ORDERS_EMAIL', '".__escape_string($_POST["orders_email"])."');\n");
			fputs($f,"\tdefine('CONF_ADMIN_FILE', '".__escape_string($admin_file)."');\n"); // Админ файл
			fputs($f,"\tdefine('CONF_ADMIN_FILE_ACCESS', '".__escape_string($admin_file_access)."');\n"); // Админ файл 2
                        fputs($f,"\tdefine('CONF_SMTP', '".$_POST["smtp"]."');\n");
                        fputs($f,"\tdefine('CONF_SMTP_HOST', '".$_POST["smtp_host"]."');\n"); 
                        fputs($f,"\tdefine('CONF_SMTP_Port', '".$_POST["smtp_port"]."');\n");  
                        fputs($f,"\tdefine('CONF_SMTP_User', '".$_POST["smtp_user"]."');\n");
                        fputs($f,"\tdefine('CONF_SMTP_Pass', '".$_POST["smtp_pass"]."');\n");                              
         		fputs($f,"\tdefine('CONF_CURRENCY_AUTO', '".__escape_string($_POST["currency_auto"])."');\n");
                        fputs($f,"\tdefine('CONF_CURRENCY_ID',".$_POST["currency_id"].");\n"); 
                        fputs($f,"\tdefine('CONF_CHPU',".$_POST["chpu"].");\n");  
                          
			#fputs($f,"\tdefine('CONF_CURRENCY_ID_LEFT', '".__escape_string($_POST["currency_id_left"])."');\n");
			#fputs($f,"\tdefine('CONF_CURRENCY_ID_RIGHT', '".__escape_string($_POST["currency_id_right"])."');\n");
			#fputs($f,"\tdefine('CONF_CURRENCY_ISO3', '".__escape_string($_POST["currency_iso3"])."');\n");
			fputs($f,"?>");
			fclose($f);

			//header("Location: ./".$location."?dpt=conf&sub=general&save_successful=yes".$error);
		}

		//set sub-department template
                $CURRENCY=db_arAll('select * from '.CURRENCY_TABLE.' ORDER BY `sort_order` ASC');  
                $smarty->assign("CURRENCY", $CURRENCY);
		
		if (!is_writable("./".CONF_ADMIN_FILE)) {
			$smarty->assign("msg0", ADMIN_MSG0);
		}
		if (!is_writable("./".CONF_ADMIN_FILE_ACCESS)) {
			$smarty->assign("msg1", ADMIN_MSG1);
		}
		if (!is_writable("./.htaccess")) {
			$smarty->assign("msg2", ADMIN_MSG2);
		}

		$smarty->assign("admin_sub_dpt", "conf_general.tpl.html");
	}

?>