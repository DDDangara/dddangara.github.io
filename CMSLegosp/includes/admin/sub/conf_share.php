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
 

	//share settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "share"))
	{

		if (isset($_GET["save_successful"])) $smarty->assign("configuration_saved", 1);
		if (isset($_GET["delete"]) && $_GET["delete"]) //delete
		{
		  db_query('DELETE FROM '.SHARE_TABLE.' WHERE id='.(int)$_GET["delete"]) or die (db_error());
		  header("Location: ".CONF_ADMIN_FILE."?dpt=conf&sub=share");
		}

		if (isset($_POST["save_conf"])) //save settings
		{
			//discount
			if (isset($_POST["new_discount_val"]) && $_POST["new_discount_val"])
			  db_query("INSERT INTO ".SHARE_TABLE." (type, type_val, value) VALUES ('1','".$_POST["new_discount_pr"]."','".$_POST["new_discount_val"]."');") or die (db_error());

			foreach ($_POST as $key => $val)
			{
			  if (strstr($key, "discount_val_")) //update values
				db_query("UPDATE ".SHARE_TABLE." SET value='".$val."' WHERE id=".str_replace("discount_val_","",$key)) or die (db_error());

			  if (strstr($key, "discount_pr_")) //update values
				db_query("UPDATE ".SHARE_TABLE." SET type_val='".$val."' WHERE id=".str_replace("discount_pr_","",$key)) or die (db_error());
			}


			//cupons
			//random num

			mt_srand(time()+(double)microtime()*1000000); 
 			for($i = 0; $i < 10; $i++)
			  { 
 				$x = mt_rand(48,90); 
				if ($x > 57 && $x < 65) $x += 10;
				$cupon_code .= chr($x);
			  }

			if (isset($_POST["new_cupon_val"]) && $_POST["new_cupon_val"])
			  db_query("INSERT INTO ".SHARE_TABLE." (type, type_val, value, code) VALUES ('2','".$_POST["new_cupon_type"]."','".$_POST["new_cupon_val"]."', '".$cupon_code."');") or die (db_error());


			foreach ($_POST as $key => $val)	
			  if (strstr($key, "cupon_val_")) //update values
				db_query("UPDATE ".SHARE_TABLE." SET value='".$val."' WHERE id=".str_replace("cupon_val_","",$key)) or die (db_error());


			header("Location: ".CONF_ADMIN_FILE."?dpt=conf&sub=share&save_successful=yes");
		}


		//list discounts

		$discounts = array();

		$q = db_query("SELECT id, type_val, value FROM ".SHARE_TABLE." WHERE type=1") or die (db_error());
		while ($row=db_fetch_row($q)) 
			$discounts[] = $row;

		$smarty->assign("discounts", $discounts);		


		$cupons = array();

		$q = db_query("SELECT id, type_val, value, code FROM ".SHARE_TABLE." WHERE type=2") or die (db_error());
		while ($row=db_fetch_row($q)) 
			$cupons[] = $row;

		$smarty->assign("cupons", $cupons);


		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_share.tpl.html");
	}

?>