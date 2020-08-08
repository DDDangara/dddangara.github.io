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
	include("../cfg/appearence.inc.php");
	include("../cfg/functions.php");
	include("../cfg/category_functions.php");
	include("../cfg/language_list.php");
        include("../cfg/redirect.inc.php");
        include("../languages/russian.php");  
        require '../smarty/smarty.class.php'; 
	$smarty = new Smarty; //core smarty object
        #$smarty->register_modifier("filesize","filesize"); 
        
        define('ROOT_DIR',realpath('../'));
        $smarty->compile_dir = ROOT_DIR."/core/cache"; 
	function removeEmptyLines($string) { return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string); }
	
        db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());
        /*echo "SELECT name, SUBSTRING(description, 1, 600) AS descr, thumbnail, productID, hurl  FROM  ".PRODUCTS_TABLE."where enabled=1 ORDER BY productID DESC LIMIT 10";
        exit; */
	$products = db_arAll("SELECT name, SUBSTRING(description, 1, 600) AS descr, thumbnail, productID, hurl  FROM  ".PRODUCTS_TABLE." where enabled=1 and categoryID>0 ORDER BY productID DESC LIMIT 10");
        $smarty->assign("products", $products);  
        header("Content-type: text/xml");  
   	$smarty->display("../css/css_".CONF_COLOR_SCHEME."/theme/rss.tpl.html"); 
?>