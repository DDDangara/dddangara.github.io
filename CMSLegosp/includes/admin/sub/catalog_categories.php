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
 

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}
	//show new orders page if selected
	if (!strcmp($sub, "categories"))
	{
           
		if (isset($_POST["categories_update"]))
                {               
		 $q = db_query('SELECT categoryID FROM '.CATEGORIES_TABLE) or die (db_error());
		 while ($row=db_fetch_row($q))
		 {
		    if (!isset($_POST["category_".$row[0]])) $category_enable = 0;
		    elseif (!strcmp($_POST["category_".$row[0]], "on")) $category_enable = 1;
		    else $category_enable = 0;
                    db_query('UPDATE '.CATEGORIES_TABLE.' SET enabled='.$category_enable.' WHERE categoryID='.$row[0]);
		 }
		header('Location: '.CONF_ADMIN_FILE.'?dpt=catalog&sub=categories');
		exit;
		}
		if (isset($_GET["del"]) && isset($_GET["c_id"])) //delete category
		{

			$_GET["c_id"]=(int)$_GET["c_id"];
			$picture = db_r("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0");
			if ($picture && file_exists('./products_pictures/'.$picture)) unlink('./products_pictures/'.$picture);
			//delete from db
			$q = db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0") or die (db_error());
			deleteSubCategories($_GET["c_id"]);
			update_products_Count_Value_For_Categories(0);
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=categories");
		}
		//calculate how many products are there in the root category
		$cnt = db_r("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=0");
		$smarty->assign("products_in_root_category",$cnt);
		//create a category tree
               	$smarty->assign("categories", All_Categories(0,0,0));
                unset($c);
		//set main template
		$smarty->assign("admin_sub_dpt", "catalog_categories.tpl.html");
	}

?>