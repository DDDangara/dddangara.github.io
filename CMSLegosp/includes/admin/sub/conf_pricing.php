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
 

//pricing editing

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "pricing"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_POST["save_pricing"]) && $_POST["pricing_value"]) //editing prices
		{
                       $where='';
                       if (isset($_POST["categorySelect"]) && $_POST["categorySelect"]>0) $where=' and categoryID='.(int)$_POST["categorySelect"];
			$q = db_query("SELECT productID, Price, list_price FROM ".PRODUCTS_TABLE) or die (db_error());

			while ($row = db_fetch_row($q))
			{
			  switch($_POST["pricing_process"]*$_POST["pricing_type"])
			    {
			     case 3:
				$list_price = $row[2] + $_POST["pricing_value"];
				$new_price = $row[1] + $_POST["pricing_value"];
			     break;
			     case 4:
				$list_price = $row[2] + ($row[2]*$_POST["pricing_value"]/100);
				$new_price = $row[1] + ($row[1]*$_POST["pricing_value"]/100);
			     break;
			     case 6:
				$list_price = $row[2] - $_POST["pricing_value"];
				$new_price = $row[1] - $_POST["pricing_value"];
			     break;
			     case 8:
				$list_price = $row[2] - ($row[2]*$_POST["pricing_value"]/100);
				$new_price = $row[1] - ($row[1]*$_POST["pricing_value"]/100);
			     break;
			    }
			  switch($_POST["pricing_option"])
			    {
			     case 5:
				db_query("UPDATE ".PRODUCTS_TABLE." SET Price = '".$new_price."' WHERE productID='".$row[0]."'".$where) or die (db_error());
			     break;
			     case 6:
				db_query("UPDATE ".PRODUCTS_TABLE." SET Price = '".$new_price."', list_price = '".$list_price."' WHERE productID='".$row[0]."'".$where) or die (db_error());
			     break;
			     case 7:
				db_query("UPDATE ".PRODUCTS_TABLE." SET Price = '".$new_price."', list_price = '".$row[1]."' WHERE productID='".$row[0]."'".$where) or die (db_error());
			     break;
			    }
			}

			header("Location: " . CONF_ADMIN_FILE . "?dpt=conf&sub=pricing&save_successful=yes");
			exit;
		}
                $category=fillTheCList(0,0); 
                $smarty->assign("category_list", $category);  
		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_pricing.tpl.html");
	}

?>