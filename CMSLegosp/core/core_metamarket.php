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
include("../cfg/redirect.inc.php"); 
  
db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
db_select_db(DB_NAME) or die (db_error());


header("Content-type: text/xml");
  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
  echo '<!DOCTYPE e-shop SYSTEM "http://market.meta.ua/market.dtd">'."\n";
  echo '<e-shop name="'.CONF_SHOP_NAME.'">'."\n";
  echo "\t<categories>\n"; $cid='';
  $q = db_query("SELECT categoryID, name, parent, products_count FROM ".CATEGORIES_TABLE." where categoryID>1 and enabled=1 ORDER BY name") or die (db_error());	
  while ($row = db_fetch_row($q))
  {
      echo  "\t\t<category id=\"".$row[0]."\"";
      if ($row[2]) echo " parentId=\"".$row[2]."\"";
      echo ">".$row[1]."</category>\n";
      $cid .=$row[0].',';
      
  }	
  $cid=substr($cid, 0, strlen($cid)-1); 
  echo "\t</categories>\n";
  echo "\t<currencies>\n";
  echo "\t<currency  id=\"UAH\" rate=\"1\"/>\n";
  echo "\t</currencies>\n"; 
  echo "\t<itemlist>\n"; 
  $pt=PRODUCTS_TABLE;
  $ct=CATEGORIES_TABLE;
  $q = db_query("SELECT  $pt.productID, $pt.categoryID,$pt.name, $ct.name as catname,  $pt.description, $pt.Price, $pt.thumbnail, $pt.in_stock, $pt.hurl FROM $pt INNER JOIN $ct ON $pt.categoryID = $ct.categoryID where $pt.enabled=1 and $pt.Price>0 and $pt.categoryID in (".$cid.")" );
  while ($row = db_fetch_row($q))
  {
     $url='http://'.CONF_SHOP_URL.'/';
     if ($row[8]!= "" && CONF_CHPU) $url.=REDIRECT_PRODUCT.'/'.$row[8]; else $url.='index.php?productID='.$row[0];
     
     echo '<item id="'.$row[0].'" category="'.$row[1].'" priority="20">'."\n"; 
     echo '<link  img="http://'.CONF_SHOP_URL.'/products_pictures/'.$row[6].'"'."\n".'click="'.$url.'" />'."\n";
     echo '<name><![CDATA['.$row[2].']]></name>'."\n";
     echo '<price cid="UAH">'.$row[5].'</price>'."\n";
     echo '<description><![CDATA['.$row[4].']]></description>'."\n";
     
     echo '</item>';
  }
   echo "\t</itemlist>\n"; 
  echo '</e-shop>';

?>