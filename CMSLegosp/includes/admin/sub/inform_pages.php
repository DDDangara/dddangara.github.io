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
	echo 'Acces denied to this module';
	die;
}

	if (!strcmp($sub, "pages"))
	{

		if (isset($_GET["del_page"])) // delete page
		{
			//old picture
			$q = db_query("SELECT Pict FROM ".PAGES_TABLE." WHERE id='".$_GET["del_page"]."'") or die (db_error());
			$row = db_fetch_row($q);

			//remove old picture...
			if (($row[0]) && file_exists("./products_pictures/$row[0]"))
			unlink("./products_pictures/$row[0]");

			$q = db_query("DELETE FROM ".PAGES_TABLE." WHERE id='".$_GET["del_page"]."'") or die (db_error());

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=pages");
						exit;
		}

		if (isset($_GET["pages"])) // edit page
		{
			$q = db_query("SELECT id, title, text, date, brief, Pict, meta_title, meta_keywords, meta_desc, hurl, canonical FROM ".PAGES_TABLE." WHERE id='".$_GET["pages"]."'") or die (db_error());
			$p = db_fetch_row($q);

			$smarty->assign("new_page", $_GET["pages"]);
		        $smarty->assign("pagetext", $p);
			$smarty->assign("page_date_y", substr($p[3], 0, 4));
			$smarty->assign("page_date_m", substr($p[3], 5, 2));
			$smarty->assign("page_date_d", substr($p[3], 8, 2));
		}
		else
		{
			$smarty->assign("new_page", "0");
			$smarty->assign("page_date_d", date("j")); $smarty->assign("page_date_m", date("n")); $smarty->assign("page_date_y", date("Y"));
		}

		if (isset($_POST["add_page"]))
		{
			if ($_POST["hurl"]=="") {$new_hurl=to_url($_POST["page_title"])."/";} else {$new_hurl=$_POST["hurl"];}

			$date = $_POST["page_date_y"]."-".$_POST["page_date_m"]."-".$_POST["page_date_d"];
			$page_pic = str_replace(" ","_",$_FILES['page_pic']['name']);

			if ($_POST["add_page"])
			{

				//old picture
				$q = db_query("SELECT Pict FROM ".PAGES_TABLE." WHERE id='".$_POST["add_page"]."'") or die (db_error());
				$row = db_fetch_row($q);
				//save changes
				db_query("UPDATE ".PAGES_TABLE." SET date='".$date."', title='".rusDoubleQuotes($_POST["page_title"])."', text='".$_POST["page_text"]."', brief='".$_POST["page_brief"]."', meta_title='".rusDoubleQuotes($_POST["meta_title"])."', meta_keywords='".rusDoubleQuotes($_POST["meta_keywords"])."', meta_desc='".rusDoubleQuotes($_POST["meta_desc"])."', hurl='".$new_hurl."', canonical='".$_POST["canonical"]."' WHERE id='".$_POST["add_page"]."'");

				if (isset($_FILES['page_pic']) && $_FILES['page_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp|png)$/i', $_FILES['page_pic']['name'])) //upload
					{
					$page_pic = str_replace(" ","_",$_FILES['page_pic']['name']);

					if (!move_uploaded_file($_FILES['page_pic']['tmp_name'], './products_pictures/'.$page_pic)) //failed to upload
						{
						$smarty->assign("error", "loading error");
						}		
					else //update db
						{
						db_query("UPDATE ".PAGES_TABLE." SET Pict='".$page_pic."' WHERE id='".$_POST["add_page"]."'") or die (db_error());
						SetRightsToUploadedFile( "./products_pictures/".$page_pic );
						}

					//remove old picture...
					if ($row[0] && strcmp($row[0], $page_pic) && file_exists("./products_pictures/$row[0]"))
					unlink("./products_pictures/$row[0]");
					}
			}
			else //save new page
			{
			   if ($_POST["page_title"])
			   {
				db_query("INSERT INTO ".PAGES_TABLE." (date, title, text, brief, Pict, enable, meta_title, meta_keywords, meta_desc, hurl, canonical) VALUES ('".$date."','".rusDoubleQuotes($_POST["page_title"])."','".$_POST["page_text"]."','".$_POST["page_brief"]."','".$page_pic."','1', '".rusDoubleQuotes($_POST["meta_title"])."', '".rusDoubleQuotes($_POST["meta_keywords"])."', '".rusDoubleQuotes($_POST["meta_desc"])."', '".$new_hurl."', '".$_POST["canonical"]."');") or die (db_error());
				$pid = db_insert_id();
				if (isset($_FILES['page_pic']) && $_FILES['page_pic']['name'] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES['page_pic']['name'])) //upload
					{
					$page_pic = str_replace(" ","_",$_FILES['page_pic']['name']);
					$r = move_uploaded_file($_FILES['page_pic']['tmp_name'], './products_pictures/'.$page_pic);

					SetRightsToUploadedFile('./products_pictures/'.$page_pic);
					}
			   $_POST["add_page"] = $pid;
			   }
			}

			// on/off

			$q = db_query("SELECT id FROM ".PAGES_TABLE." ") or die (db_error());
			$i=0;
			while ($row=db_fetch_row($q))
			  {
				if (!isset($_POST["page_".$row[0]])) $page_enable = 0;
				if (!strcmp($_POST["page_".$row[0]], "on")) $page_enable = 1;
				else $page_enable = 0;

				if ($row[0] <> $pid) db_query("UPDATE ".PAGES_TABLE." SET enable='".$page_enable."' WHERE id='".$row[0]."'");
				$i++;
			  }

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=pages&pages=".$_POST["add_page"]);
						exit;
		}

		if (isset($_GET["picture_remove"]) && isset($_GET["pages"]) && ($_GET["pages"])) // remove picture
		{
			$q = db_query("SELECT Pict FROM ".PAGES_TABLE." WHERE id='".$_GET["pages"]."'") or die (db_error());
			$row = db_fetch_row($q);

			if (file_exists("./products_pictures/$row[0]"))	unlink("./products_pictures/$row[0]");
			
			db_query("UPDATE ".PAGES_TABLE." SET Pict='' WHERE id='".$_GET["pages"]."'") or die (db_error());

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=pages&pages=".$_GET["pages"]);
						exit;
		}

		$page_list=array();

		$q = db_query("SELECT id, title, enable FROM ".PAGES_TABLE." ") or die (db_error());
		$i=0;
		while ($nm=db_assoc_q($q))
		{ 
			$page_list[0][] = $nm["id"];
			$page_list[2][] = $nm["enable"];

			if (isset($_GET["pages"]) && $nm["id"] == $_GET["pages"])
				$page_list[1][] = "<b>".$nm["title"]."</b>";
			else
				$page_list[1][] = $nm["title"];
			$i++;
 		}

		$smarty->assign("page_list", $page_list);
		$smarty->assign("page_list_count", $i);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "inform_pages.tpl.html");
	}

?>
