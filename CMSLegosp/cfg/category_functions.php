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
 
function category_tree_list($cat=FALSE){
    $levels = array();
    $tree = array();
    $cur = array();
    $sql='SELECT categoryID,name,parent,products_count_admin,enabled,hurl FROM `'.CATEGORIES_TABLE.'` where enabled=1 ';
    if (isset($_SESSION["cust_id"]))
      $sql.= ' and hidden!=2';
    else $sql.= ' and hidden!=1';
    if ($cat !==FALSE)
    {
        $path = array($cat);
	$curr = $cat;
	do
	{
		$curr= db_r("SELECT parent FROM ".CATEGORIES_TABLE.' WHERE categoryID='.$curr);
                $curr = $curr ? $curr : 0; 
		$path[] = $curr;


	} while ($curr);
      $sql .= ' and parent in ('.add_in($path).')';
    }  
    
    $sql .=' ORDER BY ' . CONF_SORT_CATEGORY . ' ' . CONF_SORT_CATEGORY_BY ;
    $sqlresult =  db_query( $sql);
    while($rows = db_assoc_q($sqlresult)){
        if ($rows['hurl'] != "" && CONF_CHPU) {
            $rows['hurl'] = REDIRECT_CATALOG . "/" . $rows['hurl'];
        } else {
            $rows['hurl'] = "index.php?categoryID=" . $rows['categoryID'];
        }
        $cur = &$levels[$rows['categoryID']];
        if (is_array($cur))
        {
          $cur=array_merge($cur,$rows);
        }
        else $cur=$rows;
        if($rows['parent'] == 0){
            $tree[$rows['categoryID']] = &$cur;
        }
        else{
            $levels[$rows['parent']]['subitems'][$rows['categoryID']] = &$cur;
        }
       
    }
    return $tree;
 }
function processCategories($level, $path, $sel) {
    //returns an array of categories, that will be presented by the category_navigation.tpl.html template
    //$categories[] - categories array
    //$level - current level: 0 for main categories, 1 for it's subcategories, etc.
    //$path - path from root to the selected category (calculated by calculatePath())
    //$sel -- categoryID of a selected category
    //returns an array of (categoryID, name, level)
    //category tree is being rolled out "by the path", not fully
    $out = array();
    $cnt = 0;
    $sql = "select categoryID, name, products_count, products_count_admin, parent, hurl from " . CATEGORIES_TABLE . ' where parent=' . $path[$level] . ' and enabled=1 ';
    if (isset($_SESSION["cust_id"]))
    $sql .=' and hidden!=2';
    else $sql .=' and hidden!=1';  
    $sql.= ' ORDER BY ' . CONF_SORT_CATEGORY . " " . CONF_SORT_CATEGORY_BY;
    $q = db_query($sql);
    while ($row = db_assoc_q($q)) {
        if ($row['hurl'] != "" && CONF_CHPU) {
            $row['hurl'] = REDIRECT_CATALOG . "/" . $row['hurl'];
        } else {
            $row['hurl'] = "index.php?categoryID=" . $row['categoryID'];
        }
        $row['level'] = $level;
        $out[] = $row;
        //process subcategories?
        if ($level + 1 < count($path) && $row['categoryID'] == $path[$level + 1]) {
            //add $sub_out to the end of $out
            $sub_out = processCategories($level + 1, $path, $sel);
            foreach($sub_out as $rez) $out[] = $rez;
        }
    }
    return $out;
} //processCategories
function All_Categories($parent, $level, $enabled = 1) {
    $sql = 'SELECT categoryID, name, products_count, products_count_admin, parent, picture, hurl,enabled FROM ' . CATEGORIES_TABLE . ' WHERE categoryID>0 and parent=' . $parent;
    if ($enabled)
    {
      $sql.= ' and enabled=1';
      if (isset($_SESSION["cust_id"])) $sql.= ' and hidden!=2';
      else $sql.= ' and hidden!=1';
    }
    $sql.= ' ORDER BY ' . CONF_SORT_CATEGORY . " " . CONF_SORT_CATEGORY_BY;
    $q = db_query($sql); $categories=array();
    while ($row = db_assoc_q($q)) {
        if ($row['hurl'] != "" && CONF_CHPU) {
            $row['hurl'] = REDIRECT_CATALOG . "/" . $row['hurl'];
        } else {
            $row['hurl'] = "index.php?categoryID=" . $row['categoryID'];
        }
        $row['level'] = $level;
        $categories[] = $row;
        if ($sub_categories = All_Categories($row['categoryID'], $level + 1,$enabled)) foreach($sub_categories as $sub_categorie) $categories[] = $sub_categorie;
    }
    unset($sub_categories, $sql, $q, $row);
    return $categories;
}


function fillTheCList($parent, $level) //completely expand category tree
{
    $q = db_query("SELECT categoryID, name, products_count, products_count_admin, parent, null, enabled, hurl FROM " . CATEGORIES_TABLE . " WHERE categoryID<>0 and parent=$parent ORDER BY " . CONF_SORT_CATEGORY . " " . CONF_SORT_CATEGORY_BY) or die(db_error());
    $a = array(); //parents
    while ($row = db_fetch_row($q)) {
        $row[5] = $level;
        if ($row[7] != "" && CONF_CHPU) {
            $row[7] = REDIRECT_CATALOG . "/" . $row[7];
        } else {
            $row[7] = "index.php?categoryID=" . $row[0];
        }
        $a[] = $row;
        //process subcategories
        $b = fillTheCList($row[0], $level + 1);
        //add $b[] to the end of $a[]
        for ($j = 0;$j < count($b);$j++) {
            $a[] = $b[$j];
        }
    }
    return $a;
 
} //fillTheCList
function update_products_Count_Value_For_Categories_new($par, $cout_p = 0, $cout_ap = 0) {
    if ($par > 0) {
        $scat = $par;
        $q = db_query('select parent,products_count,products_count_admin from ' . CATEGORIES_TABLE . ' where categoryID=' . $scat);
        $row = db_fetch_row($q);
        $par = $row[0];
        $u['products_count'] = $row[1] + $cout_p;
        $u['products_count_admin'] = $row[2] + $cout_ap;
        update_field(CATEGORIES_TABLE, $u, 'categoryID=' . $scat);
        unset($u);
        while ($par > 0) {
            $scat = $par;
            $q = db_query('select parent,products_count from ' . CATEGORIES_TABLE . ' where categoryID=' . $scat);
            $row = db_fetch_row($q);
            $par = $row[0];
            if ($cout_p != 0) {
                $u['products_count'] = $row[1] + $cout_p;
                update_field(CATEGORIES_TABLE, $u, 'categoryID=' . $scat);
            }
        }
    }
}
function update_products_Count_Value_For_Categories($parent) {
    //updates products_count and products_count_admin values for each category
    $q = db_query("SELECT categoryID FROM " . CATEGORIES_TABLE . " WHERE parent=$parent AND categoryID<>0") or die(db_error());
    $cnt = array();
    $cnt[0] = 0;
    $cnt[1] = 0;
    while ($row = db_fetch_row($q)) {
        //process subcategories
        //products_count of current category ($count[0]) surpluses it's subcategories' productsCounts
        $t = update_products_Count_Value_For_Categories($row[0]);
        $cnt[0]+= $t[0];
        $cnt[1] = $t[1];
    }
    $p = db_query("SELECT count(productID),sum(`enabled`) FROM " . PRODUCTS_TABLE . " WHERE categoryID=$parent") or die(db_error());
    $t = db_fetch_row($p);
    $cnt[0]+= $t[1];
    $cnt[1] = $t[0];
    //save calculations
    if ($parent) db_query("UPDATE " . CATEGORIES_TABLE . " SET products_count=$cnt[0], products_count_admin=$cnt[1] WHERE categoryID=" . $parent) or die(db_error());
    return $cnt;
} //update_products_Count_Value_For_Categories
function SetRightsToUploadedFile($file_name) {
    @chmod($file_name, 0666);
}
function deleteSubCategories($parent) //deletes all subcategories of category with categoryID=$parent
{
    $q = db_query("SELECT categoryID FROM " . CATEGORIES_TABLE . " WHERE parent=$parent and categoryID<>0") or die(db_error());
    while ($row = db_fetch_row($q)) {
        deleteSubCategories($row[0]); //recurrent call
        
    }
    $q = db_query("DELETE FROM " . CATEGORIES_TABLE . " WHERE parent=$parent and categoryID<>0") or die(db_error());
    $q = db_query("UPDATE " . PRODUCTS_TABLE . " SET categoryID=0 WHERE categoryID=$parent") or die(db_error());
}
function category_Moves_To_Its_SubDirectories($cid, $new_parent) {
    //return true/false
    $a = false;
    $q = db_query("SELECT categoryID FROM " . CATEGORIES_TABLE . " WHERE parent=$cid and categoryID<>0") or die(db_error());
    while ($row = db_fetch_row($q)) {
        if ($row[0] == $new_parent) $a = true;
        else $a = category_Moves_To_Its_SubDirectories($row[0], $new_parent);
    }
    return $a;
}
function get_Subs($cid) //get current category's subcategories IDs (of all levels!)
{
    $q = db_query("select categoryID from " . CATEGORIES_TABLE . " where categoryID<>0 and enabled=1 and parent='$cid'") or die(db_error());
    $r = array();
    while ($row = db_fetch_row($q)) {
        $a = get_Subs($row[0]);
        for ($i = 0;$i < count($a);$i++) $r[] = $a[$i];
        $r[] = $row[0];
    }
    return $r;
}
function products_to_show($sql)
{
  $q_show_active_products = db_query($sql);
            while ($row = db_assoc_q($q_show_active_products)) {
                if (!file_exists("./products_pictures/" . $row['big_picture'])) $row['big_picture'] = 0;
                if (!file_exists("./products_pictures/" . $row['thumbnail'])) $row['thumbnail'] = 0;
                if (!file_exists("./products_pictures/" . $row['picture'])) $row['picture'] = 0;
                if ($row['brand_hurl'] != "" && CONF_CHPU) {
                    $row['brand_about_hurl'] = REDIRECT_BRAND . "/" . $row['brand_hurl'] . "about/";
                } else {
                    $row['brand_about_hurl'] = "index.php?about&amp;brands=" . $row['brandID'];
                }
                if ($row['hurl'] != "" && CONF_CHPU) $row['hurl'] = REDIRECT_PRODUCT . "/" . $row['hurl'];
                else $row['hurl'] = "index.php?productID=" . $row['productID'];
                $row['real'] = $row['Price'];
                $row['Price'] = round($row['Price'] / CURRENCY_val, 2);
                $row['list_price'] = round($row['list_price'] / CURRENCY_val, 2);
                $row['you_save_val'] = show_price($row['list_price'] - $row['Price']); //you save (value)
                $row['Price_letters'] = show_price($row['Price']);
                $row['list_price_letters'] = show_price($row['list_price']);
                if ($row['list_price']) $row['you_save_val_p'] = ceil(100-100*$row['Price']/$row['list_price']); //you save (%)
                if (($row['in_stock'] > 0) && (CONF_SHOW_ADD2CART > 0)) {
                    $row[28] = 1;
                } elseif ((CONF_SHOW_ADD2CART_INSTOCK > 0) && (CONF_SHOW_ADD2CART > 0)) $row[28] = 1;
                else {
                    $row[28] = 0;
                }
                $result[] = $row;
                $idp.= $row['productID'] . ',';
            }
     unset($row);
     return array('result'=>$result,'id_products'=>$idp);
}

function options_list($id_products='',$count_opt=0)
{
  $options=array(); $p_default=FALSE;
  $sql_op = 'select DISTINCT P_variant.variantID, P_val.picture, P_val.productID, P_val.price_surplus,P_val.default, P_variant.name name_var, P_opt.optionID optionID, P_opt.name opt_name from ' . PRODUCT_OPTIONS_VAL_TABLE . ' as P_variant INNER JOIN ' . PRODUCT_OPTIONS_V_TABLE . ' as P_val USING(variantID) INNER JOIN ' . PRODUCT_OPTIONS_TABLE . ' as P_opt on (P_val.optionID=P_opt.optionID) where';
  if ($id_products) $sql_op .=' P_val.productID in '.$id_products;
  if ($id_products && !$count_opt) $sql_op .=' and ';
  if (!$count_opt) $sql_op .= ' P_val.count>0';
  $sql_op .=' order by P_opt.sort_order,P_variant.sort_order ';
  $q = db_query($sql_op);
  while ($frow = db_assoc_q($q)) {
          $options[$frow['productID']][$frow['opt_name']][] = $frow;
          if ($frow['default'] && $frow['picture']) $p_default[$frow['productID']] = $frow['picture'];
        }
  return array('options'=>$options,'pic_default'=>$p_default);
}

?>