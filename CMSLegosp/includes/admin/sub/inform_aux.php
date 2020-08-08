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
 

	//aux pages

if((isset($_SESSION["id"]) && $_SESSION["id"]) || !defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	echo 'Acces denied to this module';
	die;
}

	if (!strcmp($sub, "aux"))
	{

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_POST["save_aux"])) //save system settings
		{
			//appearence settings
		    //main
			$f = fopen("./core/aux_pages/index","w");
			$str = stripslashes( str_replace("\r\n","\n",$_POST["index_page"]) );
			fputs($f,$str);
			fclose($f);
		    //contact
			$f = fopen("./core/aux_pages/contact","w");
			$str = stripslashes( str_replace("\r\n","\n",$_POST["contact_page"]) );
			fputs($f,$str);
			fclose($f);

		    //about
			$f = fopen("./core/aux_pages/aux1","w");

			fputs($f,"/title/".rusDoubleQuotes(str_replace('\"', '"', $_POST["title_1"]))."\r\n");
			fputs($f,"/meta-t/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_title_1"]))."\r\n");
			fputs($f,"/meta-k/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_keywords_1"]))."\r\n");
			fputs($f,"/meta-d/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_desc_1"]))."\r\n");

			$str = stripslashes( str_replace("\r\n","\n",$_POST["about_page"]) );
			fputs($f,$str);
			fclose($f);

		    //service
			$f = fopen("./core/aux_pages/aux2","w");

			fputs($f,"/title/".rusDoubleQuotes(str_replace('\"', '"', $_POST["title_2"]))."\r\n");
			fputs($f,"/meta-t/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_title_2"]))."\r\n");
			fputs($f,"/meta-k/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_keywords_2"]))."\r\n");
			fputs($f,"/meta-d/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_desc_2"]))."\r\n");

			$str = stripslashes( str_replace("\r\n","\n",$_POST["shipping_page"]) );
			fputs($f,$str);
			fclose($f);

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=aux&save_successful=yes");
			exit;
		}
//main
		$f = implode("",file("./core/aux_pages/index"));
		$smarty->assign("index_page", $f);

//contat
		$f = implode("",file("./core/aux_pages/contact"));
		$smarty->assign("contact_page", $f);
//about
		$f = file("./core/aux_pages/aux1");
		$i = 0; $out = "";
		while ($i<count($f))
			{
			$r = 0;
   			if (strpos($f[$i], 'title')) {$aux_h[1][0] = str_replace("/title/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-t')) {$aux_h[1][1] = str_replace("/meta-t/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-k')) {$aux_h[1][2] = str_replace("/meta-k/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-d')) {$aux_h[1][3] = str_replace("/meta-d/","",$f[$i]); $r = 1;}

			if ($r == 0){ 
				$out .= $f[$i]; 
			}
			
			$i++;
			}

		if ($out != null) $smarty->assign("about_page", explode( " 1 ", $out));

//service
		$f = file("./core/aux_pages/aux2");
		$i = 0; $out = "";
		while ($i<count($f))
			{
			$r = 0;
   			if (strpos($f[$i], 'title')) {$aux_h[2][0] = str_replace("/title/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-t')) {$aux_h[2][1] = str_replace("/meta-t/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-k')) {$aux_h[2][2] = str_replace("/meta-k/","",$f[$i]); $r = 1;}
   			if (strpos($f[$i], 'meta-d')) {$aux_h[2][3] = str_replace("/meta-d/","",$f[$i]); $r = 1;}

			if ($r == 0) {$out .= $f[$i];}
			$i++;
			}

		if ($out != null) $smarty->assign("shipping_page", explode(" 1 ", $out));

		$smarty->assign("aux_head", $aux_h);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "inform_aux.tpl.html");
	}

?>