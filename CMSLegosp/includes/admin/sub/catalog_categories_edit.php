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
 

	//categories edit

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "categories_edit"))
	{

	    if (isset($_POST["categories_save"])) { //save changes in current category

		if (!isset($_POST["c_id"]) || $_POST["c_id"]=="") //add new category
			{
			if ($_POST["hurl"]=="") {$new_hurl=to_url($_POST["name"])."/";} else {$new_hurl=$_POST["hurl"];}
			if (!$_POST["title_h1"]) {$_POST["title_h1"] = rusDoubleQuotes($_POST["name"]);}
                        $category_add['name']=$_POST["name"];
                        $category_add['parent']=$_POST["parent"]; 
                        $category_add['products_count']=0; 
                        $category_add['description']=$_POST["desc"];  
                        $category_add['picture']='';  
                        $category_add['products_count_admin']=0;  
                        $category_add['about']=$_POST["about"]; 
                        $category_add['enabled']=1;
                        $category_add['meta_title']=$_POST["meta_title"];
                        $category_add['meta_keywords']=$_POST["meta_keywords"];
                        $category_add['meta_desc']=$_POST["meta_desc"]; 
                        $category_add['hurl']=$new_hurl;
                        $category_add['canonical']=$_POST["canonical"];  
                        $category_add['h1']=$_POST["title_h1"];
                        $category_add=$category_add+$_POST['catgegory_edit_array'];
                        add_field(CATEGORIES_TABLE,$category_add);  
			$pid = db_insert_id();
			}

		else //update existing category
			{
			    if ($_POST["hurl"]=="") {$new_hurl=to_url($_POST["name"])."/";} else {$new_hurl=$_POST["hurl"];}

			    if ($_POST["c_id"] != $_POST["parent"]) //if not moving category to itself
			    {

				//if category is being moved to any of it's subcategories - it's
				//neccessary to 'lift up' all it's subcategories
                                 $addcatalog["about"]=$_POST["about"];
                                 if(get_magic_quotes_gpc()) $addcatalog["about"]=stripslashes($addcatalog["about"]);
     	                         $addcatalog["about"]= rusDoubleQuotes($addcatalog["about"]);
				if (category_Moves_To_Its_SubDirectories($_POST["c_id"], $_POST["parent"]))
				{
					//lift up is required
                                       
					//get parent
					$q = db_query("SELECT parent FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID='".$_POST["c_id"]."'") or die (db_error());
					$r = db_fetch_row($q);

					//lift up
					db_query("UPDATE ".CATEGORIES_TABLE." SET parent='$r[0]' WHERE parent='".$_POST["c_id"]."'") or die (db_error());

					//move edited category
					if (!$_POST["title_h1"]) {$_POST["title_h1"] = rusDoubleQuotes($_POST["name"]);}
                                        
					db_query("UPDATE ".CATEGORIES_TABLE." SET name='".rusDoubleQuotes($_POST["name"])."', description='".$_POST["desc"]."', about='".$addcatalog["about"]."', parent='".$_POST["parent"]."', meta_title='".rusDoubleQuotes($_POST["meta_title"])."', meta_keywords='".rusDoubleQuotes($_POST["meta_keywords"])."', meta_desc='".rusDoubleQuotes($_POST["meta_desc"])."', hurl='".$new_hurl."', canonical='".$_POST["canonical"]."', h1='".$_POST["title_h1"]."' WHERE categoryID='".$_POST["c_id"]."'") or die (db_error());

				}
				else //just move category
				{	if (!$_POST["title_h1"]) {$_POST["title_h1"] = rusDoubleQuotes($_POST["name"]);}
                                        
                                        $updatec=array();
                                        $updatec['name']=$_POST["name"];
                                        $updatec['description']=$_POST["desc"];  
                                        $updatec['about']=$_POST["about"]; 
                                        $updatec['parent']=$_POST["parent"]; 
                                        $updatec['meta_title']=$_POST["meta_title"];  
                                        $updatec['meta_keywords']=$_POST["meta_keywords"];
                                        $updatec['meta_desc']=$_POST["meta_desc"];
                                        $updatec['hurl']=$new_hurl; 
                                        $updatec['canonical']=$_POST["canonical"]; 
                                        $updatec['h1']=$_POST["title_h1"]; 
                                        $updatec=$updatec+$_POST['catgegory_edit_array'];   
                                        /*echo "<pre>";
                                        print_r($updatec);
                                        echo "</pre>";  exit;*/
                                        update_field(CATEGORIES_TABLE, $updatec,'categoryID='.(int)$_POST["c_id"]);

					#db_query("UPDATE ".CATEGORIES_TABLE." SET , "',canonical='"h1='".$_POST["title_h1"]."' WHERE categoryID='".$_POST["c_id"]."'") or die (db_error());
                                }
			    }

			$pid = $_POST["c_id"];

			//manual change ID
			if (isset($_POST["manual_input"]) && ($_POST["manual_input"] != $_POST["c_id"]) && ($_POST["manual_input"] >0))
				{
				db_query("UPDATE ".CATEGORIES_TABLE." SET parent='".$_POST["manual_input"]."' WHERE parent='".$_POST["c_id"]."'") or die (db_error());
				db_query("UPDATE ".CATEGORIES_TABLE." SET categoryID='".$_POST["manual_input"]."' WHERE categoryID='".$_POST["c_id"]."'") or die (db_error());
				db_query("UPDATE ".PRODUCTS_TABLE." SET categoryID='".$_POST["manual_input"]."' WHERE categoryID='".$_POST["c_id"]."'") or die (db_error());

				$pid = $_POST["manual_input"];
				}
			//enable category
			if (isset($_POST["category_enabled"]))
				{db_query("UPDATE ".CATEGORIES_TABLE." SET enabled='1' WHERE categoryID='".$_POST["c_id"]."' || parent='".$_POST["c_id"]."'") or die (db_error());}
			else	{db_query("UPDATE ".CATEGORIES_TABLE." SET enabled='0' WHERE categoryID='".$_POST["c_id"]."' || parent='".$_POST["c_id"]."'") or die (db_error());}



			update_products_Count_Value_For_Categories(0);
			}

		if (isset($_FILES["picture"]) && $_FILES["picture"]["name"] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|png|bmp)$/i', $_FILES["picture"]["name"])) //upload category thumbnail
			{

			//old picture
			$q = db_query("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID='".$pid."' and categoryID<>0") or die (db_error());
			$row = db_fetch_row($q);

			//upload new photo
			$picture_name = file_url($_FILES["picture"]["name"]);
			if (!move_uploaded_file($_FILES["picture"]["tmp_name"], "./products_pictures/$picture_name")) //failed to upload
			{
			$smarty->assign("error", "loading error");
			}
			else //update db
			{
				db_query("UPDATE ".CATEGORIES_TABLE." SET picture='$picture_name' WHERE categoryID='".$pid."'") or die (db_error());
				SetRightsToUploadedFile( "./products_pictures/".$picture_name );
			}

			//remove old picture...
			if ($row[0] && strcmp($row[0], $picture_name) && file_exists("./products_pictures/$row[0]"))
				unlink("./products_pictures/$row[0]");
			}
                 unset($_FILES);       
		header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=categories");
		}

	    if (isset($_GET["picture_remove"])) //delete category thumbnail from server
		{
			$q = db_query("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0") or die (db_error());
			$r = db_fetch_row($q);
			if ($r[0] && file_exists("./products_pictures/$r[0]")) unlink("./products_pictures/$r[0]");
			db_query("UPDATE ".CATEGORIES_TABLE." SET picture='' WHERE categoryID='".$_GET["c_id"]."'") or die (db_error());
		}

	    if (isset($_GET["c_id"])) //edit existing category
		{
			$row = db_assoc("SELECT * FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0") or die (db_error());
			if (!file_exists("./products_pictures/".$row['picture'])) {$row['picture'] = "";}
			$smarty->assign("cat_edit", $row);
		}

		//fill the category combobox
		
			$cats = fillTheCList(0,0);
			$smarty->assign("cats", $cats);
			$smarty->assign("cats_count", count($cats));

		//set main template
		$smarty->assign("admin_sub_dpt", "catalog_categories_edit.tpl.html");
	}

?>