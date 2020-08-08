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
			array("id"=>"sales", "name"=>ADMIN_REPORTS_SALES)
		);
$resualt=deny_menedger($sub_departments,"reports");	
$sub_departments=$resualt[0];

if (count($sub_departments) > 0)
{
	//define a new admin department
	$admin_dpt = array(
		"id" => "reports", //department ID
		"sort_order" => 50, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_REPORTS, //department name
		"sub_departments" => $sub_departments
	);
	add_department($admin_dpt);
        if ($dpt == "reports")
	{
           //set default sub department if required
		if (!isset($sub)) $sub = "sales";

		//assign admin main department template
               	$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);		
		//include selected sub-department
		if (file_exists("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php") && (!array_search($admin_dpt["id"]."_".$sub, $resualt[1]))) //sub-department file exists
			include("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php");
		else //no sub department found
                { 
			$smarty->assign("admin_main_content_template", "notfound.tpl.html");
                        
                } 
        }
        $smarty->assign("safemode", 0);

	$admin_sub_menus['reports'] = $admin_dpt["sub_departments"];
	$smarty->assign("admin_sub_menus", $admin_sub_menus);
}

?>