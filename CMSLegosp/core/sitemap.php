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
 
        include("../cfg/connect.inc.php");
	include("../includes/database/mysql.php");
	include("../cfg/general.inc.php");
        include("../cfg/redirect.inc.php");  
	

	function removeEmptyLines($string)
	{
	     return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
	}
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	
	echo "<url>\n";
	echo "<loc>http://".CONF_SHOP_URL."</loc>\n";
	echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
	echo "<changefreq>monthly</changefreq>\n";
	echo "<priority>0.8</priority>\n";
	echo "</url>\n";
	
	echo "<url>\n";
	echo "<loc>http://".CONF_SHOP_URL."/about/</loc>\n";
	echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
	echo "<changefreq>monthly</changefreq>\n";
	echo "<priority>0.8</priority>\n";
	echo "</url>\n";

	echo "<url>\n";
	echo "<loc>http://".CONF_SHOP_URL."/contact/</loc>\n";
	echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
	echo "<changefreq>monthly</changefreq>\n";
	echo "<priority>0.8</priority>\n";
	echo "</url>\n";
	
	echo "<url>\n";
	echo "<loc>http://".CONF_SHOP_URL."/service/</loc>\n";
	echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
	echo "<changefreq>monthly</changefreq>\n";
	echo "<priority>0.8</priority>\n";
	echo "</url>\n";
	
	echo "<url>\n";
	echo "<loc>http://".CONF_SHOP_URL."/pricelist/</loc>\n";
	echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
	echo "<changefreq>monthly</changefreq>\n";
	echo "<priority>0.8</priority>\n";
	echo "</url>\n";	
	$catid='';
	$q = db_query("SELECT categoryID, hurl FROM ".CATEGORIES_TABLE." where categoryID>0 and enabled=1 ORDER BY name") or die (db_error());	
	while ($row = db_fetch_row($q))
	{
		if ($row[1] != "" && CONF_CHPU) {$row[1] = REDIRECT_CATALOG."/".$row[1];} else {$row[1] = "index.php?categoryID=".$row[0];}
		
		echo "<url>\n";
		echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
		echo "<changefreq>monthly</changefreq>\n";
		echo "<priority>0.5</priority>\n";
		echo "</url>\n";
		$catid .=$row[0].',';
	}
	$set=substr($catid, 0, strlen($catid)-1); 
	
		
	$q = db_query("SELECT P.productID, P.hurl FROM ".PRODUCTS_TABLE." as P LEFT JOIN ".CATEGORIES_TABLE." as C on P.categoryID=C.categoryID  where P.enabled='1' and C.categoryID in (".$set.") ORDER BY P.name") or die (db_error());	
	while ($row = db_fetch_row($q))
	{
		if ($row[1] != "" && CONF_CHPU) {$row[1] = REDIRECT_PRODUCT."/".$row[1];} else {$row[1] = "index.php?productID=".$row[0];}

		echo "<url>\n";
		echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
		echo "<changefreq>monthly</changefreq>\n";
		echo "<priority>0.5</priority>\n";
		echo "</url>\n";
		$prod_id .=$row[0].',';
	}
	$pset=substr($prod_id, 0, strlen($prod_id)-1); 	
	
	$q = db_query("SELECT brandID, hurl FROM ".BRAND_TABLE." ORDER BY name") or die (db_error());	
	while ($row = db_fetch_row($q))
	{
		if ($row[1] != "" && CONF_CHPU) {$row[1] = REDIRECT_BRAND."/".$row[1];} else {$row[1] = "index.php?brandID=".$row[0];}

		echo "<url>\n";
		echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
		echo "<changefreq>monthly</changefreq>\n";
		echo "<priority>0.5</priority>\n";
		echo "</url>\n";
	}	
	
	$q = db_query("SELECT id, hurl,date FROM ".NEWS_TABLE." WHERE enable=1 ORDER BY date DESC") or die (db_error());	
	while ($row = db_fetch_row($q))
	{
		if ($row[1] != "" && CONF_CHPU) {$row[1] = REDIRECT_NEWS."/".$row[1];} else {$row[1] = "index.php?news=".$row[0];}

		echo "<url>\n";
		echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		echo "<lastmod>".date("Y-m-d",$row[2])."</lastmod>\n";
		echo "<changefreq>monthly</changefreq>\n";
		echo "<priority>0.5</priority>\n";
		echo "</url>\n";
	}	
	
	$q = db_query("SELECT id, hurl FROM ".PAGES_TABLE." WHERE enable=1") or die (db_error());	
	while ($row = db_fetch_row($q))
	{
		if ($row[1] != "" && CONF_CHPU) {$row[1] = REDIRECT_PAGES."/".$row[1];} else {$row[1] = "index.php?pages=".$row[0];}

		echo "<url>\n";
		echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
		echo "<changefreq>monthly</changefreq>\n";
		echo "<priority>0.5</priority>\n";
		echo "</url>\n";
	}	
	
	$q = db_query("SELECT tag, hurl FROM ".TAGS_TABLE." where pid in(".$pset.")") or die (db_error());	
	while ($row = db_fetch_row($q))
	{        
                if ($row[1] != "" && CONF_CHPU)
                {  
	         $row[1]=substr($row[1], 0, strlen($row[1])-1); 	 
	         $row[1]=urlencode($row[1]);
		 $row[1] = REDIRECT_TAGS."/".$row[1].'/';
		 echo "<url>\n";
		 echo "<loc>http://".CONF_SHOP_URL."/".$row[1]."</loc>\n";
		 echo "<lastmod>".date("Y-m-d")."</lastmod>\n";
		 echo "<changefreq>monthly</changefreq>\n";
		 echo "<priority>0.5</priority>\n";
		 echo "</url>\n";
                } 
	}		

echo "</urlset>";
?>