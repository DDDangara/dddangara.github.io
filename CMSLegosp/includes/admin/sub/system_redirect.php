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

	if (!strcmp($sub, "redirect"))
	{
		if (isset($_GET["save_successful"])) //show successful save confirmation message
			$smarty->assign("configuration_saved", 1);
		else
			$smarty->assign("configuration_saved", 0);

		if (isset($_POST["save_redirect"])) //save redirect settings
		{
			//editing .htaccess
			$line = Array();

			$f = fopen("./.htaccess", "r");
			while (!feof($f)) {
			    $tmp = fgets($f);
			    $tmp = str_replace(REDIRECT_CATALOG."/", $_POST["redir_catalog"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_PRODUCT."/", $_POST["redir_product"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_BRAND."/", $_POST["redir_brand"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_NEWS."/", $_POST["redir_news"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_PAGES."/", $_POST["redir_pages"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_TAGS."/", $_POST["redir_tags"]."/", $tmp);
			    $tmp = str_replace(REDIRECT_INFO."/", $_POST["redir_info"]."/", $tmp);
			    $line[] = $tmp;
			}
			fclose($f);

			//writing cfg
			$f = fopen("./cfg/redirect.inc.php","w");
			fputs($f,"<?php\n\t");
			fputs($f,"\tdefine('REDIRECT_CATALOG', '".__escape_string($_POST["redir_catalog"])."');\n");
			fputs($f,"\tdefine('REDIRECT_PRODUCT', '".__escape_string($_POST["redir_product"])."');\n");
			fputs($f,"\tdefine('REDIRECT_BRAND', '".__escape_string($_POST["redir_brand"])."');\n");
			fputs($f,"\tdefine('REDIRECT_NEWS', '".__escape_string($_POST["redir_news"])."');\n");
			fputs($f,"\tdefine('REDIRECT_PAGES', '".__escape_string($_POST["redir_pages"])."');\n");
			fputs($f,"\tdefine('REDIRECT_TAGS', '".__escape_string($_POST["redir_tags"])."');\n");
			fputs($f,"\tdefine('REDIRECT_INFO', '".__escape_string($_POST["redir_info"])."');\n");
			fputs($f,"?>");
			fclose($f);

			$i = 0;
			$f = fopen("./.htaccess", "w");
			while ($line[$i]) {
			    fputs($f, $line[$i]);
			    $i++;
			}
			fclose($f);

			header("Location: " . CONF_ADMIN_FILE . "?dpt=system&sub=redirect&save_successful=yes");
						exit;
		}

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "system_redirect.tpl.html");
	}

?>