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
	die('Acces denied to this module');
}

	if (!strcmp($sub, "brands"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
			$smarty->assign("save_successful", ADMIN_UPDATE_SUCCESSFUL);
		else
			$smarty->assign("save_successful", "");

		if (isset($_GET["del_brand"])) // delete page
		{
			//old picture
			$q = db_query("SELECT Pict FROM ".BRAND_TABLE." WHERE brandID='".$_GET["del_brand"]."'") or die (db_error());
			$row = db_fetch_row($q);

			//remove old picture...
			if (($row[0]) && file_exists("./products_pictures/$row[0]"))
			unlink("./products_pictures/$row[0]");

			$q = db_query("DELETE FROM ".BRAND_TABLE." WHERE brandID='".$_GET["del_brand"]."'") or die (db_error());

			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=brands");
		}

		$p = Array("", "", "", "", "", "", "", "", "", "", "");
		if (isset($_GET["brands"])) // edit page
		{
			$p = db_assoc("SELECT * FROM ".BRAND_TABLE." WHERE brandID='".$_GET["brands"]."'") or die (db_error());


			$smarty->assign("new_brand", $_GET["brands"]);
		        $smarty->assign("brand_info", $p);
		}
		else
		{
			$smarty->assign("new_brand", "0");
			$smarty->assign("brand_info", $p);
		}

		if (isset($_POST["add_brand"]))
		{
			if ($_POST["brand"]['hurl']=="") {$_POST["brand"]['hurl']=to_url($_POST["brand"]['name'])."/";}
			if ($_POST["add_brand"])
			{	
                                
                                $pid=(int)$_POST["add_brand"];
                                update_field(BRAND_TABLE,$_POST['brand'],'brandID='.$_POST["add_brand"]);

				if (isset($_FILES['brand_pic']) && $_FILES['brand_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp|png)$/i', $_FILES['brand_pic']['name'])) //upload
                     		{

			                //remove old picture... 		
                                        $old_pic = db_r("SELECT Pict FROM ".BRAND_TABLE." WHERE brandID='".$pid."'");
                                        if ($old_pic && file_exists('./products_pictures/'.$old_pic)) unlink('./products_pictures/'.$old_pic);
                                       $brand_pic = file_url($_FILES['brand_pic']['name']);
					if (!move_uploaded_file($_FILES['brand_pic']['tmp_name'], './products_pictures/'.$brand_pic)) //failed to upload
						$smarty->assign("error", "loading error");
					else //update db
						{
                                                
						db_query("UPDATE ".BRAND_TABLE." SET Pict='".$brand_pic."' WHERE brandID='".$pid."'") or die (db_error());
						SetRightsToUploadedFile( "./products_pictures/".$brand_pic );
						}
                                }
			}
			else //save new page
			{
			   if ($_POST['brand']['name'])
			   {
                                add_field(BRAND_TABLE,$_POST['brand']);
				$pid = db_insert_id();
				if (isset($_FILES['brand_pic']) && $_FILES['brand_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp|png)$/i', $_FILES['brand_pic']['name'])) //upload
					{
					$brand_pic = file_url($_FILES['brand_pic']['name']);
					$r = move_uploaded_file($_FILES['brand_pic']['tmp_name'], './products_pictures/'.$brand_pic);

					SetRightsToUploadedFile('./products_pictures/'.$brand_pic);
					}
			   }
			}



			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=brands&brands=".$pid);
		}

		if (isset($_GET["picture_remove"]) && isset($_GET["brands"]) && ($_GET["brands"])) // remove picture
		{
			$q = db_query("SELECT Pict FROM ".BRAND_TABLE." WHERE brandID='".$_GET["brands"]."'") or die (db_error());
			$row = db_fetch_row($q);

			if (file_exists("./products_pictures/$row[0]"))	unlink("./products_pictures/$row[0]");
			
			db_query("UPDATE ".BRAND_TABLE." SET Pict='' WHERE brandID='".$_GET["brands"]."'") or die (db_error());

			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=brands&brands=".$_GET["brands"]);
		}

		$brand_list=array();

		$q = db_query("SELECT brandID, name FROM ".BRAND_TABLE." ") or die (db_error());
		$i=0;
		while ($nm=db_assoc_q($q))
		{
			$brand_list[0][] = $nm["brandID"];

			if ((isset($_GET["brands"])) && ($nm["brandID"] == $_GET["brands"]))
				$brand_list[1][] = "<b>".$nm["name"]."</b>";
			else
				$brand_list[1][] = $nm["name"];
			$i++;
 		}


		$smarty->assign("brand_list", $brand_list);
		$smarty->assign("brand_list_count", $i);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "catalog_brands.tpl.html");
	}

?>
