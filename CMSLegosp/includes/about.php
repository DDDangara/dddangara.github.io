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
 

	// auxiliary information page presentation

	if (isset($_GET["about"]))
	{
		if ($_GET["category"])
			{
			$q = db_query("SELECT categoryID, name, about, meta_title, meta_keywords, meta_desc, hurl FROM ".CATEGORIES_TABLE.' WHERE categoryID='.(int)($_GET["category"]).' OR hurl='.int_text($_GET['category'])) or die (db_error());
			$p = db_fetch_row($q);
			 
			if (empty($p[2])) include_once(ROOT_DIR.'/core/core_404.php');
			$path = Array();
			if ($p[6] != "" && CONF_CHPU) {$row[0] = REDIRECT_CATALOG."/".$p[6]."about/";} else {$row[0] = "index.php?about&amp;category=".$p[0];}
			$row[1] = $p[1];
			$path[] = $row;

			$smarty->assign("title", $p[1]);
			$smarty->assign("meta_title", $p[3]);
			$smarty->assign("meta_keywords", $p[4]);
			$smarty->assign("meta_desc", $p[5]);
			if ($p[2]<>"") {$smarty->assign("about", $p[2]);} else {$smarty->assign("about", NO_ABOUT);}
			}
		if ($_GET["brands"])
			{
			$q = db_query("SELECT brandID, name, description, meta_title, meta_keywords, meta_desc, hurl FROM ".BRAND_TABLE.' WHERE brandID='.(int)$_GET['brands'].' OR hurl='.int_text($_GET['brands'])) or die (db_error());
			$p = db_fetch_row($q);

			$path = Array();
			if ($p[6] != ""  && CONF_CHPU) {$row[0] = REDIRECT_BRAND."/".$p[6]."about/";} else {$row[0] = "index.php?about&amp;brands=".$p[0];}
			$row[1] = $p[1];
			$path[] = $row;

			$smarty->assign("title", $p[1]);
			$smarty->assign("meta_title", $p[3]);
			$smarty->assign("meta_keywords", $p[4]);
			$smarty->assign("meta_desc", $p[5]);
			if ($p[2]<>"") {$smarty->assign("about", $p[2]);} else {$smarty->assign("about", NO_ABOUT);}
			}

		$smarty->assign("product_category_path",$path);
		$smarty->assign("main_content_template", "about.tpl.html");
	}
?>
