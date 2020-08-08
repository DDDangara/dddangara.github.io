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
//show subcategories
$category_root=FALSE;
if (isset($_GET['category_root'])) $category_root=TRUE;
if ($category_root || (isset($categoryID) && $categoryID>0 && !isset($productID)))
{
  
  if ($category_root) $categoryID=0;
  $q = db_query("SELECT categoryID, name, hurl, products_count,picture FROM ".CATEGORIES_TABLE." WHERE enabled=1 and parent='".$categoryID."' ORDER BY ".CONF_SORT_CATEGORY." ".CONF_SORT_CATEGORY_BY);
  $result = array();
  while ($row = db_assoc_q($q))
  {
      if ($row['hurl'] != "" && CONF_CHPU) {$row['hurl'] = REDIRECT_CATALOG."/".$row['hurl'];} else {$row['hurl'] = "index.php?categoryID=".$row['categoryID'];}
      if (!$row['picture'] || !file_exists('./products_pictures/'.$row['picture']) ) $row['picture']='';
      $result[] = $row;
  }
  $smarty->assign("subcategories_to_be_shown",$result);
  if ($categoryID==0) $smarty->assign("main_content_template", "subcateg.tpl.html"); 
}