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
 

	//appearence settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "product"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_POST["save_conf"])) //save system settings
		{
			//modify checkbox vars
			if (!isset($_POST["add2cart"])) $_POST["add2cart"] = 0;
			if (!strcmp($_POST["add2cart"], "on")) $_POST["add2cart"] = 1;
			else $_POST["add2cart"] = 0;

			if (!isset($_POST["add2cartInStock"])) $_POST["add2cartInStock"] = 0;
			if (!strcmp($_POST["add2cartInStock"], "on")) $_POST["add2cartInStock"] = 1;
			else $_POST["add2cartInStock"] = 0;

			if (!isset($_POST["float_quantity"])) $_POST["float_quantity"] = 0;
			if (!strcmp($_POST["float_quantity"], "on")) $_POST["float_quantity"] = 1;
			else $_POST["float_quantity"] = 0;

			if (!isset($_POST["productInStock"])) $_POST["productInStock"] = 0;
			if (!strcmp($_POST["productInStock"], "on")) $_POST["productInStock"] = 1;
			else $_POST["productInStock"] = 0;
                     
                        if (!isset($_POST["productvarInStock"])) $_POST["productvarInStock"] = 0;
			if (!strcmp($_POST["productvarInStock"], "on")) $_POST["productvarInStock"] = 1;
			else $_POST["productvarInStock"] = 0; 

			if (!isset($_POST["presentselect"])) $_POST["presentselect"] = 0;
			if (!strcmp($_POST["presentselect"], "on")) $_POST["presentselect"] = 1;
			else $_POST["presentselect"] = 0;
                       
                        if (isset($_POST['review_write']) && $_POST["review_write"]=='on') $_POST["review_write"]=1;
                        else $_POST["review_write"]=0; 
                       
                        if (isset($_POST['review_link']) && $_POST["review_link"]=='on') $_POST["review_link"]=1;
                        else $_POST["review_link"]=0; 
                         
                        if (isset($_POST['review_moder']) && $_POST["review_moder"]=='on') $_POST["review_moder"]=1;
                        else $_POST["review_moder"]=0; 

			if ($_POST["float_quantity"] == 1) {$q = db_query("ALTER TABLE `".ORDERED_CARTS_TABLE."` CHANGE `Quantity` `Quantity` FLOAT( 11 ) NULL DEFAULT NULL ") or die (db_error());} else {$q = db_query("ALTER TABLE `SS_orders_carts` CHANGE `Quantity` `Quantity` INT( 11 ) NULL DEFAULT NULL ") or die (db_error());}

			//appearence settings
			$f = fopen("./cfg/product.inc.php","w");
			fputs($f,"<?php\n");
			fputs($f,"\tdefine('CONF_SHOW_ADD2CART', '".$_POST["add2cart"]."');\n");
			fputs($f,"\tdefine('CONF_SHOW_ADD2CART_INSTOCK', '".$_POST["add2cartInStock"]."');\n");
			fputs($f,"\tdefine('CONF_SHOW_PRODUCT_INSTOCK', '".$_POST["productInStock"]."');\n");
                        fputs($f,"\tdefine('CONF_SHOW_PRODUCT_VARIANTS_INSTOCK', '".$_POST["productvarInStock"]."');\n"); 
                        fputs($f,"\tdefine('CONF_REVIEW_WRITE', '".$_POST["review_write"]."');\n"); 
                        fputs($f,"\tdefine('CONF_REVIEW_LINK', '".$_POST["review_link"]."');\n");    
                        fputs($f,"\tdefine('CONF_REVIEW_MODER', '".$_POST["review_moder"]."');\n");     
			fputs($f,"\tdefine('CONF_FLOAT_QUANTITY', '".(int)$_POST["float_quantity"]."');\n");
			fputs($f,"\tdefine('CONF_PRESENT_SELECT', '".(int)$_POST["presentselect"]."');\n");
			//fputs($f,"\tdefine('CONF_MINIMAL_COUNT', '".(int)$_POST["minimal_count"]."');\n");
			//fputs($f,"\tdefine('CONF_MINIMAL_SUMM', '".(int)$_POST["minimal_summ"]."');\n");
			//fputs($f,"\tdefine('CONF_MINIMAL_PRODUCT', '".__escape_string($_POST["minimal_product"])."');\n");
			//fputs($f,"\tdefine('CONF_MINIMAL_COST', '".(int)$_POST["minimal_cost"]."');\n");
			fputs($f,"\tdefine('CONF_SORT_CATEGORY', '".__escape_string($_POST["category_sort"])."');\n");
			fputs($f,"\tdefine('CONF_SORT_CATEGORY_BY', '".__escape_string($_POST["category_sort_by"])."');\n");
			fputs($f,"\tdefine('CONF_SORT_PRODUCT', '".__escape_string($_POST["product_sort"])."');\n");
			fputs($f,"\tdefine('CONF_SORT_PRODUCT_BY', '".__escape_string($_POST["product_sort_by"])."');\n?>");

			fclose($f);

			header("Location: " . CONF_ADMIN_FILE . "?dpt=conf&sub=product&save_successful=yes");
			exit;
		}


		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_product.tpl.html");
	}

?>