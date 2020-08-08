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
 
if (CONF_NEWS_ONHOME == 1)
    {
        
        $count_news=CONF_NEWS_ONHOME_COUNT; 
        $q = db_query("SELECT id, title, date, brief, Pict, enable, hurl FROM ".NEWS_TABLE." WHERE enable=1 ORDER BY date DESC  LIMIT 0,".$count_news) or die (db_error());
	$news=array();
        $i = 0;
        while ($p = db_fetch_row($q))
            {
		if ($p[6] != "" && CONF_CHPU) {$p[6] = REDIRECT_NEWS."/".$p[6];} else {$p[6]="index.php?news=".$p[0];}
                $news[] = $p;
		$i++;
            }
        $smarty->assign("home_news_list", $news);
    }

if (isset($_GET["news"]) && $_GET["news"])   
    {
	//calculate a path
	$path = Array();
        if (CONF_CHPU) $row[0] = REDIRECT_NEWS."/";
        else  $row[0] = 'index.php?news';
	$row[1] = ADMIN_NEWS;
	$path[] = $row;

        $q = db_query('SELECT title, text, date, brief, Pict, enable, meta_title, meta_keywords, meta_desc, hurl, canonical FROM '.NEWS_TABLE.' WHERE hurl='.int_text($_GET["news"]).' OR id='.(int)($_GET['news'])) or die (db_error());
        $p = db_fetch_row($q);

	if (!$p) {
		//not found
		 include_once(ROOT_DIR.'/core/core_404.php');  
		}
	if ($p[9] != "" && CONF_CHPU)
		$row[0] = REDIRECT_NEWS."/".$p[9];
	else	$row[0] = "index.php?news=".$_GET["news"];

	$row[1] = $p[0];
	$path[] = $row;

	$smarty->assign("product_category_path",$path);

	$smarty->assign("meta_title", $p[6]);
	$smarty->assign("meta_keywords", $p[7]);
	$smarty->assign("meta_desc", $p[8]);
	$smarty->assign("rel_canonical", $p[10]);
        $smarty->assign("newstext", $p);
        $smarty->assign("main_content_template", "news.tpl.html");
    }
if (isset($_GET["news_list"]))   
     {
	
	//calculate a path
	$path = Array();
	if (CONF_CHPU) $row[0] = REDIRECT_NEWS."/";
        else  $row[0] = 'index.php?news';
	$row[1] = ADMIN_NEWS;
	$path[] = $row;

	$smarty->assign("product_category_path",$path);
        if (!isset($_GET['offset'])) $offset=0; else $offset=$_GET['offset'];
        $sl=$offset; $sql="SELECT id, title, date, brief, Pict, enable, hurl FROM ".NEWS_TABLE." where enable=1  ORDER BY date DESC";
        if (!isset($_GET['show_all'])) $sql .=" LIMIT ".$sl.", ".CONF_NEWS_PER_PAGE;
        $q = db_query($sql) or die (db_error()); 

        $g_count=db_r("SELECT count(*) FROM ".NEWS_TABLE.' where enable=1 ');
        $news=array();
        while ($p=db_fetch_row($q))
	     {
		if ($p[6] != "" && CONF_CHPU) {$p[6] = REDIRECT_NEWS."/".$p[6];} else {$p[6]="index.php?news=".$p[0];}
                $news[] = $p;
             }
        showNavigator($g_count, $offset, CONF_NEWS_PER_PAGE, $row[0]."&amp;",$navigator);

        
        $smarty->assign("catalog_navigator", $navigator);
	$smarty->assign("meta_title", CONF_SHOP_NAME." | ".ADMIN_NEWS);
	$smarty->assign("meta_keywords", CONF_SHOP_NAME." | ".ADMIN_NEWS);
	$smarty->assign("meta_desc", CONF_SHOP_NAME." | ".ADMIN_NEWS);
        $smarty->assign("newslist", $news);
        $smarty->assign("main_content_template", "news.tpl.html");
    }
?>