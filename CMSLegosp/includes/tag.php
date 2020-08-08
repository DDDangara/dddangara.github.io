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
 
//show all products of selected tag
//set $tagID
if (isset($_GET["tagID"]) || isset($_POST["tagID"])) $tagID = isset($_GET["tagID"]) ? $_GET["tagID"] : $_POST["tagID"];
else $tagID = '';
if (isset($tagID) && !isset($productID) && $tagID) {
    //get selected tag info
    $q = db_query("SELECT pid, tag, hurl, canonical FROM " . TAGS_TABLE . " WHERE tag=" .int_text($tagID)) or die(db_error());
    $row = db_fetch_row($q);
    if (!$row) {
        //header("HTTP/1.1 404 Not Found");
        header("Location: http://" . CONF_SHOP_URL . "/404/");
        exit;
    } else {
        if ($row[2] != "" && CONF_CHPU) {
            $row[2] = REDIRECT_TAGS . "/" . $row[2];
        } else {
            $row[2] = "index.php?tagID=" . $row[1];
        }
        $selected_tag = $row;
        $smarty->assign("selected_tag", $row);
        $smarty->assign("rel_canonical", $row[3]);
        //sort options
        $sort_options['sort_values'] = Array($row[2] . "&amp;sort=name&amp;order=ASC", $row[2] . "&amp;sort=name&amp;order=DESC", $row[2] . "&amp;sort=Price&amp;order=ASC", $row[2] . "&amp;sort=Price&amp;order=DESC", $row[2] . "&amp;sort=customers_rating&amp;order=DESC", $row[2] . "&amp;sort=in_stock&amp;order=DESC", $row[2] . "&amp;sort=product_code&amp;order=ASC");
        $sort_options['sort_names'] = Array(ADMIN_SORT_BY_NAME_ASC, ADMIN_SORT_BY_NAME_DESC, ADMIN_SORT_BY_PRICE_ASC, ADMIN_SORT_BY_PRICE_DESC, ADMIN_SORT_BY_RATING, ADMIN_SORT_BY_IN_STOCK, ADMIN_SORT_BY_CODE);
        $sort_options['sort_selected'] = $row[2] . "&amp;sort=" . $_SESSION["sort"] . "&amp;order=" . $_SESSION["order"];
        $smarty->assign("sort_options", $sort_options);
    }
    //path to tag
    $path = Array();
    if ($row[2] != "" && CONF_CHPU) {
        $rowt[0] = $row[2];
    } else {
        $rowt[0] = "index.php?tagID=" . $tagID;
    }
    $rowt[1] = $row[1];
    $path[] = $rowt;
    $smarty->assign("product_category_path", $path);
    $smarty->assign("main_content_template", "category.tpl.html");
    $g_count = db_r('SELECT count(P.productID) FROM `' . PRODUCTS_TABLE . '` AS P INNER JOIN `' . CATEGORIES_TABLE . '` AS C ON (P.categoryID = C.categoryID) INNER JOIN ' . TAGS_TABLE . ' ON (P.productID = ' . TAGS_TABLE . '.pid)  WHERE ' . TAGS_TABLE . '.tag=' . int_text($tagID) . ' AND P.enabled=1 AND C.enabled=1');
    $smarty->assign("catalog_navigator", NULL);
    $smarty->assign("products_to_show", NULL);
    $smarty->assign("products_to_show_count", NULL);
    if ($g_count) // there are products in the category
    {
        if ($offset > $g_count) $offset = 0;
        $sql = 'SELECT P.*,B.name brand_name, B.hurl brand_hurl, count(R.reviewID) count_review FROM `' . PRODUCTS_TABLE . '` AS P LEFT JOIN `' . REVIEW_TABLE . '` as R USING(productID) LEFT JOIN `' . BRAND_TABLE . '` as B USING (brandID)  INNER JOIN `' . CATEGORIES_TABLE . '` AS C ON (P.categoryID = C.categoryID) INNER JOIN ' . TAGS_TABLE . ' ON (P.productID = ' . TAGS_TABLE . '.pid) WHERE ' . TAGS_TABLE . '.tag=' . int_text($tagID) . ' AND P.enabled =1 AND C.enabled =1 GROUP BY `P`.`productID` ORDER BY P.' . $_SESSION['sort'] . ' ' . $_SESSION['order'];
        if (!isset($_GET['show_all'])) $sql.= ' LIMIT ' . $offset . ' , ' . CONF_PRODUCTS_PER_PAGE;
        $q = db_query($sql);
        //fetch all products
        $result = array();
        $i = 0;
        while ($row = db_assoc_q($q)) {
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
            if ($row['list_price']) $row['you_save_val_p'] = ceil(((($row['list_price'] - $row['Price']) / $row['list_price']) * 100)); //you save (%)
            if (($row['in_stock'] > 0) && (CONF_SHOW_ADD2CART > 0)) {
                $row[28] = 1;
            } elseif ((CONF_SHOW_ADD2CART_INSTOCK > 0) && (CONF_SHOW_ADD2CART > 0)) $row[28] = 1;
            else {
                $row[28] = 0;
            }
            if ($row['in_stock'] > 0 || CONF_SHOW_PRODUCT_INSTOCK > 0) $result[] = $row;
            $idp.= $row['productID'] . ',';
        }
        $idp = '(' . substr($idp, 0, strlen($idp) - 1) . ')';
        $options_info=options_list($idp,CONF_SHOW_PRODUCT_VARIANTS_INSTOCK);
        $smarty->assign("options",$options_info['options']);
        $smarty->assign("p_default", $options_info['pic_default']);
        unset($options_info, $idp, $row);
        //number of products to show on this page
        if (!isset($_GET["show_all"])) {
            $min = CONF_PRODUCTS_PER_PAGE;
            if ($min > $g_count - $offset) $min = $g_count - $offset;
        } else {
            $min = $g_count;
            $offset = "show_all";
        }
        $smarty->assign("products_to_show", $result);
        $smarty->assign("products_to_show_count", $min);
        $navigator = ""; //navigation links
        showNavigator($g_count, $offset, CONF_PRODUCTS_PER_PAGE, $selected_tag[2] . "&amp;", $navigator);
        $smarty->assign("catalog_navigator", $navigator);
    }
}
?>