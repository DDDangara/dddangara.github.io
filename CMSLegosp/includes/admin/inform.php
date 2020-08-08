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
 

	//ADMIN :: information managment
$sub_departments = array
		(
			array("id"=>"aux", "name"=>ADMIN_AUX_INFO),
			array("id"=>"aux_pages", "name"=>ADMIN_AUX_PAGES),
			array("id"=>"pages", "name"=>ADMIN_PAGES),
			array("id"=>"news", "name"=>ADMIN_NEWS),
			array("id"=>"votes", "name"=>ADMIN_VOTES)
		);
$resualt=deny_menedger($sub_departments,"inform");	
$sub_departments=$resualt[0];
	
	
if (count($sub_departments) > 0)
{
	//define a new admin department
	$admin_dpt = array(
		"id" => "inform", //department ID
		"sort_order" => 30, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_INFORMATION, //department name
		"sub_departments" => $sub_departments
	);
	add_department($admin_dpt);

	//show department if it is being selected
	if ($dpt == "inform")
	{
		//set default sub department if required
		if (!isset($sub)) $sub = "aux";

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

	$admin_sub_menus['inform'] = $admin_dpt["sub_departments"];
	$smarty->assign("admin_sub_menus", $admin_sub_menus);
}
?>