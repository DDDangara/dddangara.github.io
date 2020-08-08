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
 

	//company pages

if(isset($_SESSION["id"]) || !defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	echo 'Acces denied to this module';
	die;
}

	if (!strcmp($sub, "company"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_POST["save_company"])) //save system settings
		{

			//general settings
			$f = fopen("./cfg/company.inc.php","w");
			fputs($f,"<?php\n\tdefine('COMPANY_NAME', '".__escape_string($_POST["company_name"])."');\n");
			fputs($f,"\tdefine('COMPANY_ADDRESS', '".__escape_string($_POST["company_adress"])."');\n");
			fputs($f,"\tdefine('COMPANY_PHONE', '".__escape_string($_POST["company_phone"])."');\n");
			fputs($f,"\tdefine('COMPANY_DIRECTOR', '".__escape_string($_POST["company_director"])."');\n");
			fputs($f,"\tdefine('COMPANY_BUH', '".__escape_string($_POST["company_buh"])."');\n");
			fputs($f,"\tdefine('COMPANY_RS', '".__escape_string($_POST["company_rs"])."');\n");
			fputs($f,"\tdefine('COMPANY_INN', '".__escape_string($_POST["company_inn"])."');\n");
			fputs($f,"\tdefine('COMPANY_KPP', '".__escape_string($_POST["company_kpp"])."');\n");
			fputs($f,"\tdefine('COMPANY_BANK', '".__escape_string($_POST["company_bank"])."');\n");
			fputs($f,"\tdefine('COMPANY_BANK_KOR', '".__escape_string($_POST["company_bank_kor"])."');\n");
			fputs($f,"\tdefine('COMPANY_BANK_BIK', '".__escape_string($_POST["company_bank_bik"])."');\n");
			fputs($f,"\tdefine('COMPANY_MAIL', '".__escape_string($_POST["company_mail"])."');\n");
			fputs($f,"\tdefine('COMPANY_WORK', '".__escape_string($_POST["company_work"])."');\n");
			fputs($f,"?>");
			fclose($f);

			header("Location: " . CONF_ADMIN_FILE . "?dpt=conf&sub=company&save_successful=yes");
			exit;
		}

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_company.tpl.html");
	}

?>