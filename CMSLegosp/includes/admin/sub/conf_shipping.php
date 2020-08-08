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
 

	//shiping settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "shipping"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}


		if (isset($_GET["delete"]) && $_GET["delete"]) //delete
		{
		  db_query("DELETE FROM ".SHARE_TABLE." WHERE id='".$_GET["delete"]."'") or die (db_error());
		  header("Location: " . CONF_ADMIN_FILE . "?dpt=conf&sub=shipping");
		}

		if (isset($_POST["save_conf"])) //save settings
		{
			//settings

			if (!isset($_POST["fast_order_on"])) $_POST["fast_order_on"] = 0;
			if (!strcmp($_POST["fast_order_on"], "on")) $_POST["fast_order_on"] = 1;
			else $_POST["fast_order_on"] = 0;


			if (!isset($_POST["shipping_free_on"])) $_POST["shipping_free_on"] = 0;
			if (!strcmp($_POST["shipping_free_on"], "on")) $_POST["shipping_free_on"] = 1;
			else $_POST["shipping_free_on"] = 0;


			//appearence settings
			$f = fopen("./cfg/shipping.inc.php","w");
			fputs($f,"<?php\n\tdefine('CONF_FAST_ORDER_ON', '".(int)$_POST["fast_order_on"]."');\n");
			fputs($f,"\tdefine('CONF_FAST_ORDER_COST', '".(int)$_POST["fast_order_cost"]."');\n");
			fputs($f,"\tdefine('CONF_SHIPPING_FREE_VAL', '".(int)$_POST["shipping_free_val"]."');\n");
			fputs($f,"\tdefine('CONF_SHIPPING_FREE_ON', '".(int)$_POST["shipping_free_on"]."');\n");
			fputs($f,"?>");

			fclose($f);



			//shipping
			if (isset($_POST["new_shipping_val"]))
			  db_query("INSERT INTO ".SHARE_TABLE." (type, value, code) VALUES ('3','".$_POST["new_shipping_val"]."','".$_POST["new_shipping_where"]."');") or die (db_error());
                         
                        if (isset($_POST['shipping']) && count($_POST['shipping'])>0)
			foreach ($_POST['shipping'] as $key => $val)
			  {	
                            $val['default']=0;  
                            if (isset($_POST['default']) && $_POST['default']==$key) $val['default']=1;
                          
                            update_field(SHARE_TABLE, $val,"id=".$key);
			  }
                  
		  header("Location: " . CONF_ADMIN_FILE . "?dpt=conf&sub=shipping&save_successful=yes");
		}

		$shipping_list = array();

		$q = db_query("SELECT `id`, `value`, `code`,`default` FROM ".SHARE_TABLE." WHERE type=3 ORDER BY code ASC") or die (db_error());
		while ($row=db_fetch_row($q)) 
			$shipping_list[] = $row;

		$smarty->assign("shipping", $shipping_list);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_shipping.tpl.html");
	}

?>