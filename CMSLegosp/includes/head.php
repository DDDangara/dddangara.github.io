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
 

	// head variables definition: title, meta

	// title
	$r = array(); $r[0] = "";
	if (isset($categoryID) && !isset($productID) && $categoryID>0)
	{
		$q = db_query("SELECT name FROM ".CATEGORIES_TABLE.' WHERE categoryID<>0 and categoryID='.(int)$categoryID) or die (db_error());
		$r = db_fetch_row($q);
		$page_title = str_replace("\"","'",$r[0]." - ".CONF_SHOP_NAME);
	}
	else if (isset($productID) && $productID>0)
	{
		$q = db_query("SELECT name FROM ".PRODUCTS_TABLE.' WHERE productID='.(int)$productID) or die (db_error());
		$r = db_fetch_row($q);
		$page_title = str_replace("\"","'",$r[0]." - ".CONF_SHOP_NAME);
	}
	else $page_title = CONF_SHOP_NAME;


	// META
	$r = array(); $r[0] = "";
	if (isset($categoryID) && !isset($productID) && $categoryID>0)
	{
		$q = db_query("SELECT name, description FROM ".CATEGORIES_TABLE.' WHERE categoryID<>0 and categoryID='.(int)$categoryID) or die (db_error());
		$r = db_fetch_row($q);
		$page_meta = str_replace("\"","'",$r[0].", ".$r[1]);
	}
	else if (isset($productID) && $productID>0)
	{
		$q = db_query("SELECT name, brief_description FROM ".PRODUCTS_TABLE.' WHERE productID='.(int)$productID) or die (db_error());
		$r = db_fetch_row($q);
		$page_meta = str_replace("\"","'",$r[0].", ".$r[1]);
	}
	else $page_meta = CONF_SHOP_NAME.", powered by LegoSP";

	$page_description = CONF_SHOP_DESCRIPTION;
	$page_keywords = CONF_SHOP_KEYWORDS;

	$smarty->assign("user_identification",$_SESSION['cust_id']);

	$smarty->assign("page_description",$page_description);
	$smarty->assign("page_keywords",$page_keywords);

	$smarty->assign("page_meta",$page_meta);
	$smarty->assign("page_title",$page_title); 
?>