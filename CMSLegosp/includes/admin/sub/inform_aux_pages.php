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

	if (!strcmp($sub, "aux_pages"))
	{

		if (isset($_GET["del_aux"])) // delete page
		{
			$q = db_query("DELETE FROM ".AUX_TABLE." WHERE id='".$_GET["del_aux"]."'") or die (db_error());

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=aux_pages");
			exit;
		}

		if (isset($_GET["aux"])) // edit aux
		{
			$q = db_query("SELECT id, title, text, meta_title, meta_keywords, meta_desc, hurl, canonical FROM ".AUX_TABLE." WHERE id='".$_GET["aux"]."'") or die (db_error());
			$row = db_fetch_row($q);

			$smarty->assign("new_aux", $_GET["aux"]);
			$smarty->assign("aux_page", $row);
		}
		else
		{
			$smarty->assign("new_aux", "0");
		}

		if (isset($_POST["add_aux"]))
		{
			if ($_POST["hurl"]=="") {$new_hurl=to_url($_POST["aux_title"])."/";} else {$new_hurl=$_POST["hurl"];}

			if ($_POST["add_aux"])
			{
				//save ubdate
				db_query("UPDATE ".AUX_TABLE." SET title='".rusDoubleQuotes($_POST["aux_title"])."', text='".$_POST["aux_text"]."', meta_title='".rusDoubleQuotes($_POST["meta_title"])."', meta_keywords='".rusDoubleQuotes($_POST["meta_keywords"])."', meta_desc='".rusDoubleQuotes($_POST["meta_desc"])."', hurl='".$new_hurl."', canonical='".$_POST["canonical"]."' WHERE id='".$_POST["add_aux"]."'");
			}
			else //save new page
			{
			   if ($_POST["aux_title"])
			   {
				db_query("INSERT INTO ".AUX_TABLE." (title, text, enable, meta_title, meta_keywords, meta_desc, hurl, canonical) VALUES ('".rusDoubleQuotes($_POST["aux_title"])."','".$_POST["aux_text"]."','1', '".rusDoubleQuotes($_POST["meta_title"])."', '".rusDoubleQuotes($_POST["meta_keywords"])."', '".rusDoubleQuotes($_POST["meta_desc"])."', '".$new_hurl."', '".$_POST["canonical"]."');") or die (db_error());
				$pid = db_insert_id();
				$_POST["add_aux"] = $pid;
			   }
			}

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=aux_pages&aux=".$_POST["add_aux"]);
			exit;
		}


		$aux_list=array();
		$q = db_query("SELECT id, title, enable FROM ".AUX_TABLE." ORDER BY id") or die (db_error());

		while ($row=db_fetch_row($q))
		{ 	
			if (isset($_GET["aux"]) && $row[0] == $_GET["aux"])
				$row[1] = "<b>".$row[1]."</b>";
			$aux_list[] = $row;
 		}

		$smarty->assign("aux_list", $aux_list);

/*
		$aux_list=array();
		$next_aux = 3;
		$dir = "./cfg/";
		$i = 0;
		if (is_dir($dir)) {
    		    if ($dh = opendir($dir))
		    {
        		while (($file = readdir($dh)) !== false)
			    {
				$res = explode('aux', $file);
				if ((!$res[1]=="") && ($res[1]>2)) 
				  {
				    $f = file("./cfg/aux".$res[1]);
				    if (strpos($f[0], "title")) {$aux_list[1][] = str_replace("/title/","",$f[0]);}
				    $aux_list[0][] = $res[1];
				    $next_aux = $res[1]+1;
				    $i++;
				  }
	       		    }
	        	closedir($dh);
	    	    }
		}
		sort($aux_list);
		$smarty->assign("aux_list", $aux_list);
		$smarty->assign("aux_list_count", $i);

		if (isset($_GET["del_aux"])) // delete page
		{
			if ($_GET["del_aux"] && file_exists("./cfg/aux".$_GET["del_aux"])) unlink("./cfg/aux".$_GET["del_aux"]);

			header("Location: admin.php?dpt=inform&sub=aux_pages");
		}

		if (isset($_GET["aux"]) && file_exists("./cfg/aux".$_GET["aux"])) // edit page
		{
			$f = file("./cfg/aux".$_GET["aux"]);

			$i = 0;
			while ($i<count($f))
				{
				$r = 0;
   				if (strpos($f[$i], 'title')) {$aux_h[0] = str_replace("/title/","",$f[$i]); $r = 1;}
   				if (strpos($f[$i], 'meta-t')) {$aux_h[1] = str_replace("/meta-t/","",$f[$i]); $r = 1;}
   				if (strpos($f[$i], 'meta-k')) {$aux_h[2] = str_replace("/meta-k/","",$f[$i]); $r = 1;}
   				if (strpos($f[$i], 'meta-d')) {$aux_h[3] = str_replace("/meta-d/","",$f[$i]); $r = 1;}

				if ($r == 0) {$out[] = $f[$i];}
				$i++;
				}

			if ($out != "") $aux_text = implode("", $out);

			$smarty->assign("new_aux", $_GET["aux"]);

			$smarty->assign("aux_head", $aux_h);
			$smarty->assign("aux_text", $aux_text);
		}
		else
		{
			$smarty->assign("new_aux", $next_aux);
		}

		if (isset($_POST["add_aux"]) && $_POST["add_aux"])
		{
		    if ($_POST["aux_title"])
			   {
				$f = fopen("./cfg/aux".$_POST["add_aux"],"w");				

				fputs($f,"/title/".rusDoubleQuotes(str_replace('\"', '"', $_POST["aux_title"]))."\r\n");
				fputs($f,"/meta-t/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_title"]))."\r\n");
				fputs($f,"/meta-k/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_keywords"]))."\r\n");
				fputs($f,"/meta-d/".rusDoubleQuotes(str_replace('\"', '"', $_POST["meta_desc"]))."\r\n");

				$str = stripslashes( str_replace("\r\n","\n",$_POST["aux_text"]) );
				fputs($f,$str);
				fclose($f);
			   }
		    header("Location: admin.php?dpt=inform&sub=aux_pages&aux=".$_POST["add_aux"]);
		}
*/


		//set sub-department template
		$smarty->assign("admin_sub_dpt", "inform_aux_pages.tpl.html");
	}

?>
