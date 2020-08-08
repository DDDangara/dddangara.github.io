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

	if (!strcmp($sub, "appearence"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_POST["save_appearence"])) //save system settings
		{

			//zip color scheme ?

			if (isset($_FILES["color_filename"]) && $_FILES["color_filename"]["name"] && preg_match('/\.(zip)$/i', $_FILES["color_filename"]["name"])) //upload
			  {
			    $css_name = $_FILES["color_filename"]["name"];
			    $r = move_uploaded_file($_FILES["color_filename"]["tmp_name"], "./css/".$_FILES["color_filename"]["name"]);
			    SetRightsToUploadedFile( "./css/".$_FILES["color_filename"]["name"] );

			    //unziping
			    include("./core/core_zip.php");

			    $zip = new PclZip("./css/".$css_name);
			    $zip -> extract("./css/");

			    //delete zip
			    if (file_exists("./css/".$_FILES["color_filename"]["name"])) {unlink("./css/".$_FILES["color_filename"]["name"]);}
			  }


			//modify checkbox vars
			if (!isset($_POST["bestchoice"])) $_POST["bestchoice"] = 0;
			if (!strcmp($_POST["bestchoice"], "on")) $_POST["bestchoice"] = 1;
			else $_POST["bestchoice"] = 0;
			if (!isset($_POST["showmenu"])) $_POST["showmenu"] = 0;
			if (!strcmp($_POST["showmenu"], "on")) $_POST["showmenu"] = 1;
			else $_POST["showmenu"] = 0;
			if (!isset($_POST["tagsview"])) $_POST["tagsview"] = 0;
			if (!strcmp($_POST["tagsview"], "on")) $_POST["tagsview"] = 1;
			else $_POST["tagsview"] = 0;
			if (!isset($_POST["online_on"])) $_POST["online_on"] = 0;
			if (!strcmp($_POST["online_on"], "on")) $_POST["online_on"] = 1;
			else $_POST["online_on"] = 0;
			if (!isset($_POST["newsonhome"])) $_POST["newsonhome"] = 0;
			if (!strcmp($_POST["newsonhome"], "on")) $_POST["newsonhome"] = 1;
			else $_POST["newsonhome"] = 0;
			if (!isset($_POST["pagesonhome"])) $_POST["pagesonhome"] = 0;
			if (!strcmp($_POST["pagesonhome"], "on")) $_POST["pagesonhome"] = 1;
			else $_POST["pagesonhome"] = 0;
			if (!isset($_POST["google_translate"])) $_POST["google_translate"] = 0;
			if (!strcmp($_POST["google_translate"], "on")) $_POST["google_translate"] = 1;
			else $_POST["google_translate"] = 0;
                        

			//appearence settings
			$f = fopen("./cfg/appearence.inc.php","w");
			fputs($f,"<?php\n\tdefine('CONF_PRODUCTS_PER_PAGE', '".(int)$_POST["productscount"]."');\n");
                        fputs($f,"\tdefine('CONF_NEWS_PER_PAGE', '".(int)$_POST["newscount"]."');\n");
                        fputs($f,"\tdefine('CONF_PAGES_PER_PAGE', '".(int)$_POST["pagescount"]."');\n");   
			fputs($f,"\tdefine('CONF_COLUMNS_PER_PAGE', '".(int)$_POST["colscount"]."');\n");
			fputs($f,"\tdefine('CONF_MAX_HITS', '".(int)$_POST["hitscount"]."');\n");
			fputs($f,"\tdefine('CONF_SCROLL_HITS', '".(int)$_POST["scrollhits"]."');\n");
			fputs($f,"\tdefine('CONF_HITS_FRIQ', '".(int)$_POST["hitsfriq"]."');\n");
			fputs($f,"\tdefine('CONF_HITS_SPEED', '".(int)$_POST["hitsspeed"]."');\n");
			fputs($f,"\tdefine('CONF_HITS_TYPE', '".(int)$_POST["hitstype"]."');\n");
			fputs($f,"\tdefine('CONF_TAG_COUNT', '".(int)$_POST["tagscount"]."');\n");
			fputs($f,"\tdefine('CONF_TAG_VIEW_SW', '".(int)$_POST["tagsview"]."');\n");
			fputs($f,"\tdefine('CONF_PRICE_SHOW_COUNT', '".(int)$_POST["price_show_count"]."');\n");
			fputs($f,"\tdefine('CONF_SHOW_BEST_CHOICE', '".$_POST["bestchoice"]."');\n");
			fputs($f,"\tdefine('CONF_SHOW_MENU', '".$_POST["showmenu"]."');\n");
			fputs($f,"\tdefine('CONF_COLOR_SCHEME', '".__escape_string($_POST["color_scheme"])."');\n");
			fputs($f,"\tdefine('CONF_DARK_COLOR', '".__escape_string($_POST["darkcolor"])."');\n");
			fputs($f,"\tdefine('CONF_MIDDLE_COLOR', '".__escape_string($_POST["middlecolor"])."');\n");
			fputs($f,"\tdefine('CONF_LIGHT_COLOR', '".__escape_string($_POST["lightcolor"])."');\n");
			fputs($f,"\tdefine('CONF_VOTE_COLOR', '".__escape_string($_POST["votecolor"])."');\n");
                        fputs($f,"\tdefine('CONF_BODY_COLOR', '".__escape_string($_POST["bodycolor"])."');\n");    
			fputs($f,"\tdefine('CONF_IMAGE_COLOR', '".__escape_string($_POST["imagecolor"])."');\n");
			fputs($f,"\tdefine('CONF_NEWS_ONHOME', '".__escape_string($_POST["newsonhome"])."');\n");
                        fputs($f,"\tdefine('CONF_NEWS_ONHOME_COUNT', '".(int)($_POST["newsonhomecount"])."');\n");
			fputs($f,"\tdefine('CONF_PAGES_ONHOME', '".__escape_string($_POST["pagesonhome"])."');\n");
                        fputs($f,"\tdefine('CONF_PAGES_ONHOME_COUNT', '".(int)($_POST["pagesonhomecount"])."');\n");
			fputs($f,"\tdefine('RESIZE_SMALL_X', '".(int)$_POST["resize_small_x"]."');\n");
			fputs($f,"\tdefine('RESIZE_SMALL_Y', '".(int)$_POST["resize_small_y"]."');\n");
			fputs($f,"\tdefine('RESIZE_NORMAL_X', '".(int)$_POST["resize_normal_x"]."');\n");
			fputs($f,"\tdefine('RESIZE_NORMAL_Y', '".(int)$_POST["resize_normal_y"]."');\n");
			fputs($f,"\tdefine('RESIZE_BIG_X', '".(int)$_POST["resize_big_x"]."');\n");
			fputs($f,"\tdefine('RESIZE_BIG_Y', '".(int)$_POST["resize_big_y"]."');\n");
			fputs($f,"\tdefine('CONF_ONLINE_ON', '".(int)$_POST["online_on"]."');\n");
			fputs($f,"\tdefine('CONF_GOOGLE_TR', '".(int)$_POST["google_translate"]."');\n");
			fputs($f,"?>");
			fclose($f);

			header("Location: ".CONF_ADMIN_FILE."?dpt=conf&sub=appearence&save_successful=yes");
			exit;
		}

		// Open a css directory, and proceed to read its schemes

		$dir = "./css/";
		$scheme = Array();
		
		if (is_dir($dir)) {
    			if ($dh = opendir($dir)) {
        			while (($file = readdir($dh)) !== false) {
                                  if (trim(strstr($file,"_")))
                                  {
				    $res = explode('_', $file);
				    if (!$res[1]=="") {$scheme[] = $res[1];}
                                  } 
	       		 	}
	        		closedir($dh);
	    		}
		}
		$smarty->assign("scheme_list", $scheme);


		//set sub-department template
		$smarty->assign("admin_sub_dpt", "conf_appearence.tpl.html");
	}

?>