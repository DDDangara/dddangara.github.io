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
 

	//ADMIN :: products and categories view

$sub_departments = array
		(
			array("id"=>"categories", "name"=>ADMIN_CATEGORIES),
			array("id"=>"products", "name"=>ADMIN_PRODUCTS),
                        array("id"=>'products_variants', "name"=>ADMIN_PRODUCTS_VARIANTS_GROUPS),
			array("id"=>"brands", "name"=>STRING_BRANDS),
			array("id"=>"review", "name"=>ADMIN_REVIEW),
			array("id"=>"special", "name"=>ADMIN_SPECIAL_OFFERS),
			array("id"=>"present", "name"=>ADMIN_PRESENTS),
			array("id"=>"import", "name"=>ADMIN_IMPORT)
		);
        $resualt=deny_menedger($sub_departments,"catalog");
        $sub_departments=$resualt[0];
if (count($sub_departments)>0)        
{
	//define admin department
	$admin_dpt = array(
		"id" => "catalog", //department ID
		"sort_order" => 10, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_CATALOG, //department name
		"sub_departments" => $sub_departments
	);
	add_department($admin_dpt);

	//show new orders page if selected
	if ($dpt == "catalog")
	{
		//set default sub department if required
		if (!isset($sub)) $sub = "categories";
                if (isset($_GET['excel'])) 
                {
                  require_once "./core/excel/Writer.php"; 
                  // создание книги 
                  $xls = new Spreadsheet_Excel_Writer();  
                  // создание листа 
                  $cart =& $xls->addWorksheet('phpPetstore');
                  $xls->send("phpPetstore.xls"); 
                  $xls->close();  
                   
                }  
		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		//include selected sub-department
		if (file_exists("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php") && (!array_search($admin_dpt["id"]."_".$sub, $resualt[1]))) //sub-department file exists
			include("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php");
		else //no sub department found
			$smarty->assign("admin_main_content_template", "notfound.tpl.html");

	}

	//safemode
	$smarty->assign("safemode", 0);

	$admin_sub_menus['catalog'] = $admin_dpt["sub_departments"];
	$smarty->assign("admin_sub_menus", $admin_sub_menus);
}
?>