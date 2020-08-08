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
 

	//catalog: products extra parameters list

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "present"))
	{

		if (isset($_GET["save_successful"])) //update was successful
			$smarty->assign("save_successful", ADMIN_UPDATE_SUCCESSFUL);
		else
			$smarty->assign("save_successful", "");

		if (isset($_POST["save_present"])) //save extra product options
		{
			//save existing
			db_query("delete from ".SHARE_TABLE." WHERE type=5") or die (db_error());

			$present = array();

			foreach ($_POST as $key => $val)
			{
			  if(strstr($key, "present_productID_") != false)
			  {
				$a = str_replace("present_productID_","",$key);
				$present[$a]["productID"] = $val;
			  }

			  if(strstr($key, "present_value_") != false)
			  {
				$a = str_replace("present_value_","",$key);
				$present[$a]["value"] = $val;
			  }
			}

			foreach ($present as $key => $value)
			{
				db_query("insert into ".SHARE_TABLE." (type, type_val, value) values ('5', '".$value["productID"]."', '".$value["value"]."')") or die (db_error());
			}
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=present&save_successful=yes");
		}

		if (isset($_GET["new_present"])) //add new present
		{
			db_query("INSERT INTO ".SHARE_TABLE." (type, type_val, value) VALUES ('5', '".$_GET["new_present"]."', '0');") or die (db_error());
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=present");
		}

		if (isset($_GET["delete"])) //delete special offer
		{
			db_query("DELETE FROM ".SHARE_TABLE." WHERE id='".$_GET["delete"]."'") or die (db_error());
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=present");
		}

		//now select all available product options
		$q = db_query("select id, type_val, value from ".SHARE_TABLE." WHERE type=5 ORDER BY value") or die (db_error());
		$result = array();
		while ($row = db_fetch_row($q))
		{
			//get product name
			$q1 = db_query("select name, categoryID from ".PRODUCTS_TABLE." where productID=$row[1]") or die (db_error());
			if ($row1 = db_fetch_row($q1))
			{
				$row[3] = $row1[0];
				$row[4] = $row1[1];
				$result[] = $row;
			}
		}
		$smarty->assign("present", $result);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "catalog_present.tpl.html");
	}

?>