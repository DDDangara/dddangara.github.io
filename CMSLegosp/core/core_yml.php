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
	include("../cfg/tables.inc.php");
	include("../includes/database/mysql.php");
	include("../cfg/general.inc.php");
	include("../cfg/appearence.inc.php");
	include("../cfg/functions.php");
	include("../cfg/category_functions.php");
	#include("../cfg/language_list.php");
        include("../cfg/redirect.inc.php");
   
	function inttobolstr($n)
	{
	    $res = "false";
	    if($n>0) $res="true";
	    return $res;
	}
	function removeEmptyLines($string)
	{
	     return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
	}
	
	function formspecialchars($var)
        {
       $out='<![CDATA['.$var.']]>';
        return $out;
       } 
	
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	

 define('DEFAULT_CHARSET', 'utf-8');

	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"".DEFAULT_CHARSET."\"?>\n";
	echo "<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n";
	echo "<yml_catalog date=\"".date("Y-m-d H:i")."\">\n";
	echo "<shop>\n";
	$shop_name=CONF_SHOP_NAME;
	echo "\t<name>".$shop_name."</name>\n";
	echo "\t<company>".CONF_SHOP_NAME."</company>\n";
	echo "\t<url>".CONF_SHOP_URL."</url>\n";
	echo "\t<platform>LegoSP</platform>\n";
	echo "\t<version>6.1</version>\n";
	echo "\t<email>legoedition@gmail.com</email>\n";
	echo "\t<currencies>\n\t\t<currency id=\"RUR\" rate=\"1\"/>\n\t</currencies>\n";
	echo "\t<categories >\n";
	$catid='';

	$q = db_query("SELECT categoryID, name, parent, products_count,picture FROM ".CATEGORIES_TABLE." where categoryID<>0 and enabled=1 ORDER BY name") or die (db_error());
	while ($row = db_fetch_row($q))
	{
		echo  "\t\t<category id=\"".$row[0]."\" parentId=\"".$row[2]."\">";
     echo "\t\t\t<name>" . formspecialchars( $row[1] ) . "</name>\n";

     if( $row[4] && file_exists( "../products_pictures/" . $row[4] ) ){
         $pp = "http://" . CONF_SHOP_URL . "/products_pictures/" . $row[4];

     } else                $pp = "http://" . CONF_SHOP_URL . "/products_pictures/nophoto.jpg";
     #$pp = "http://" . CONF_SHOP_URL . "/products_pictures/nophoto.jpg";
     echo "\t\t\t<picture>$pp</picture>\n";
     echo   "\t\t</category>\n";
		$catid .=$row[0].','; 
	}

	$set=substr($catid, 0, strlen($catid)-1); 	
	echo "\t</categories>\n";
	echo "\t<offers>\n";
	$pt=PRODUCTS_TABLE;
	$ct=CATEGORIES_TABLE;
        $share=db_r('select count(*) from '.SHARE_TABLE);
        if ($share>0) $delivery='TRUE'; else $delivery='FALSE';
        currency();  
        $currency_iso_3 = (defined('CONF_CURRENCY_ISO3')) ? CONF_CURRENCY_ISO3 : "USD" ;
	$q = db_query("SELECT  pt.productID, ct.name as catname, pt.name as prodname, pt.description, pt.Price,pt.big_picture as picture, pt.in_stock, pt.categoryID, brand.name brand_name, pt.hurl FROM ".PRODUCTS_TABLE.' as pt INNER JOIN '.CATEGORIES_TABLE.' as ct ON pt.categoryID = ct.categoryID Left join '.BRAND_TABLE.' as brand ON pt.brandID = brand.brandID where pt.Price>0 and pt.enabled=1 and pt.yml=1 and ct.categoryID in ('.$set.')');
	while ($row = db_assoc_q($q))
	{
               
		echo "\t\t<offer id=\"".$row['productID']."\" available=\"".inttobolstr($row['in_stock'])."\">\n";
                if ($row['hurl'] && CONF_CHPU) 
                 echo "\t\t\t<url>http://".CONF_SHOP_URL.'/'.REDIRECT_PRODUCT.'/'.$row['hurl']."</url>\n";
                else
		 echo "\t\t\t<url>http://".CONF_SHOP_URL."/index.php?productID=".$row['productID']."</url>\n";
		echo "\t\t\t<price>".$row['Price']."</price>\n";
		echo "\t\t\t<currencyId>".$currency_iso_3."</currencyId>\n";
		echo "\t\t\t<categoryId>".$row['categoryID']."</categoryId>\n";
		if ($row['picture'] && file_exists("../products_pictures/".$row['picture'])) 
                {
                   $pp = "http://".CONF_SHOP_URL."/products_pictures/".$row['picture'];

                }
  else                $pp = "http://" . CONF_SHOP_URL . "/products_pictures/nophoto.jpg";
     echo "\t\t\t<picture>$pp</picture>\n";
		echo "\t\t\t<delivery>".$delivery."</delivery>\n";
		echo "\t\t\t<name>".formspecialchars($row['prodname'])."</name>\n";
                echo "\t\t\t<vendor>".$row['brand_name']."</vendor>\n";
		$zz=formspecialchars(strip_tags($row['description']));
		echo "\t\t\t<description>".removeEmptyLines($zz)."</description>\n";
		echo "\t\t</offer>\n";
	}
	
	echo "\t</offers>\n";
echo "</shop>\n";
echo "</yml_catalog>";
?>