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

	if (!strcmp($sub, "currency"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}
                
		if (isset($_POST["save_currency"])) //save system settings
		{
                     if ($_POST["curr_add"]['Name'])   
                       add_field(CURRENCY_TABLE,$_POST["curr_add"]);
                      
                     foreach ($_POST['curr'] as $key=>$value)
                     {
                        update_field(CURRENCY_TABLE,$value,"`CID`=$key"); 
                     }   

                        
		}
                 
                if (isset($_GET["del"])) 
                {
                   db_query("DELETE FROM `".CURRENCY_TABLE."` WHERE `SS_currency_types`.`CID` = ".$_GET["del"]);
                   header('Location: ./'.CONF_ADMIN_FILE.'?dpt=conf&sub=currency');
                }  
                /*if (trim(PAYEE_PURSE)) 
                {   
                  $smarty->assign('cach',substr(PAYEE_PURSE, 0, 1));    
                  $smarty->assign('cach_nambe',substr(PAYEE_PURSE, 1, strlen(PAYEE_PURSE)));    
                }*/
		//set sub-department template
                
                $sql="select * from ".CURRENCY_TABLE;
                $currency=db_arAll($sql);
                $smarty->assign("currency", $currency);    

		$smarty->assign("admin_sub_dpt", "conf_currency.tpl.html");
	}

?>