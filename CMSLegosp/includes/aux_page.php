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
 

	// auxiliary information page presentation from files

	if (isset($_GET["aux_page"]))
	{
		if (strstr($_GET["aux_page"],"aux") && file_exists("./core/aux_pages/".$_GET["aux_page"]))
		{	
			$out = Array();
			$f = file("./core/aux_pages/".$_GET["aux_page"]);
			$i = 0;
			while ($i<count($f))
				{
				$r = 0;
				if (strpos($f[$i], 'title'))
				{
					$smarty->assign("aux_title", str_replace("/title/","",$f[$i]));
					//calculate a path
					$path = Array();

					if ($_GET["aux_page"] == "aux1" && CONF_CHPU) $row[0] = "./about/";
					elseif ($_GET["aux_page"] == "aux2" && CONF_CHPU) $row[0] = "./service/";
				        else {$row[0] = "index.php?aux_page=".$_GET["aux_page"];}
					$row[1] = str_replace("/title/","",$f[$i]);
					$path[] = $row;

					$smarty->assign("product_category_path",$path);

					$r = 1;
				}
   				if (strpos($f[$i], 'meta-t')) {$smarty->assign("meta_title", str_replace("/meta-t/","",$f[$i]));$r = 1;}
   				if (strpos($f[$i], 'meta-k')) {$smarty->assign("meta_keywords", str_replace("/meta-k/","",$f[$i]));$r = 1;}
   				if (strpos($f[$i], 'meta-d')) {$smarty->assign("meta_desc", str_replace("/meta-d/","",$f[$i]));$r = 1;}
				if ($r == 0) {$out[] = $f[$i];}
				$i++;
				}

			if ($out != "") $aux_text = implode("", $out);

			$smarty->assign("auxiliary_page", $aux_text);
		}
		$smarty->assign("main_content_template", "aux_page.tpl.html");
	}

	//from db

	if (isset($_GET["aux_pages"]))
	{
		$q = db_query("SELECT id, title, text, meta_title, meta_keywords, meta_desc, hurl, canonical FROM ".AUX_TABLE." WHERE hurl=".int_text($_GET['aux_pages']).' OR id='.(int)($_GET["aux_pages"])) or die (db_error());
		$p = db_fetch_row($q);

		if (!$p) {
			//not found
			//header("HTTP/1.1 404 Not Found");
			 include_once(ROOT_DIR.'/core/core_404.php');  
			}
		$path = Array();
		$row = Array();
		//calculate a path
		if ($p[6] != "" && CONF_CHPU)
			$row[0] = REDIRECT_INFO."/".$p[6];
		else	$row[0] = "index.php?aux_pages=".(int)$_GET["aux_pages"];

		$row[1] = $p[1];
		$path[] = $row;

		$smarty->assign("product_category_path",$path);

		$smarty->assign("aux_title", $p[1]);

		$smarty->assign("meta_title", $p[3]);
		$smarty->assign("meta_keywords", $p[4]);
		$smarty->assign("meta_desc", $p[5]);
		$smarty->assign("rel_canonical", $p[7]);
		$smarty->assign("auxiliary_page", $p[2]);

		$smarty->assign("main_content_template", "aux_page.tpl.html");
	}
?>
