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
 


$sub_departments = array
		(
			array("id"=>"general", "name"=>ADMIN_SETTINGS_GENERAL),
			array("id"=>"appearence", "name"=>ADMIN_SETTINGS_APPEARENCE),
			array("id"=>"company", "name"=>ADMIN_COMPANY_ABOUT),
			array("id"=>"product", "name"=>ADMIN_CATALOG_CONF),
			array("id"=>"shipping", "name"=>ADMIN_SHIPPING),
			array("id"=>"share", "name"=>ADMIN_SHARE),
                        array("id"=>"currency", "name"=>ADMIN_CURRENCY_TYPES),
			array("id"=>"pricing", "name"=>ADMIN_PRICING)
		);
		
$resualt=deny_menedger($sub_departments,"conf");	
$sub_departments=$resualt[0];

if (count($sub_departments)>0)
{
	$admin_dpt = array(
		"id" => "conf", //department ID
		"sort_order" => 40, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_SETTINGS, //department name
		"sub_departments" => $sub_departments
	);
        unset($sub_departments);
	add_department($admin_dpt);

	if ($dpt == "conf")
	{
		if (!isset($sub)) $sub = "general";
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		if (file_exists("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php") && (!array_search($admin_dpt["id"]."_".$sub, $resualt[1]))) //sub-department file exists
			include("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php");
		else //no sub department found
			$smarty->assign("admin_main_content_template", "notfound.tpl.html");
	}
	$smarty->assign("safemode", 0);
	$admin_sub_menus['conf'] = $admin_dpt["sub_departments"];    unset($admin_dpt);
	$smarty->assign("admin_sub_menus", $admin_sub_menus);
}
?>