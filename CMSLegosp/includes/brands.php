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
 
$brandres = Array();
$get_brand = 0;
$i = 0; 
$result = db_query("SELECT * FROM " . BRAND_TABLE) or die(db_error());
if (isset($_GET['brand'])) $get_brand = $_GET['brand'];
elseif (isset($_GET['brand_hurl'])) $get_brand = $_GET['brand_hurl'];
while ($row = db_fetch_row($result)) {
    $brandres[$i][0] = $row[0];
    $brandres[$i][1] = $row[1];
    $brandres[$i][3] = $row[3];
    $brandres[$i][4] = $row[5];
    if ($row[9] != "" && CONF_CHPU) {
        $brandres[$i][2] = REDIRECT_BRAND . "/" . $row[9];
    } else {
        $brandres[$i][2] = "index.php?brand=" . $row[0];
    }
    if (($row[9] == $get_brand) || ($row[0] == $get_brand)) {
        if ($row[9] != "" && CONF_CHPU) {
            $row[11] = REDIRECT_BRAND . "/" . $row[9] . "about/";
        } else {
            $row[11] = "index.php?about&amp;brands=" . $row[0];
        }
        if ($row[9] != "" && CONF_CHPU) {
            $row[9] = REDIRECT_BRAND . "/" . $row[9];
        } else {
            $row[9] = "index.php?brand=" . $row[0];
        }
        $selected_brand = $row;
    }
    $i++;
}
$smarty->assign("brand_list", $brandres);
//show all products of selected brand
if (isset($_GET[REDIRECT_BRAND . '_hurl']) && trim($_GET[REDIRECT_BRAND . '_hurl'])) $_GET['brand'] = $_GET[REDIRECT_BRAND . '_hurl'] . '/';
if (isset($_GET['brand']) && $_GET['brand']) {
    //get selected brand info
    if (!$selected_brand) {
        header("Location: http://" . CONF_SHOP_URL . "/404/");
        exit;
    } else {
        $smarty->assign("selected_brand", $selected_brand);
        $smarty->assign("meta_title", $selected_brand[6]);
        $smarty->assign("meta_keywords", $selected_brand[7]);
        $smarty->assign("meta_desc", $selected_brand[8]);
        $smarty->assign("rel_canonical", $selected_brand[10]);
        //sort options
        $sort_options['sort_values'] = Array($selected_brand[9] . "&amp;sort=name&amp;order=ASC", $selected_brand[9] . "&amp;sort=name&amp;order=DESC", $selected_brand[9] . "&amp;sort=Price&amp;order=ASC", $selected_brand[9] . "&amp;sort=Price&amp;order=DESC", $selected_brand[9] . "&amp;sort=customers_rating&amp;order=DESC", $selected_brand[9] . "&amp;sort=in_stock&amp;order=DESC", $selected_brand[9] . "&amp;sort=product_code&amp;order=ASC");
        $sort_options['sort_names'] = Array(ADMIN_SORT_BY_NAME_ASC, ADMIN_SORT_BY_NAME_DESC, ADMIN_SORT_BY_PRICE_ASC, ADMIN_SORT_BY_PRICE_DESC, ADMIN_SORT_BY_RATING, ADMIN_SORT_BY_IN_STOCK, ADMIN_SORT_BY_CODE);
        $sort_options['sort_selected'] = $selected_brand[9] . "&amp;sort=" . $_SESSION["sort"] . "&amp;order=" . $_SESSION["order"];
        $smarty->assign("sort_options", $sort_options);
    }
    //path to brand
    $path = Array();
    if ($selected_brand[9] != "" && CONF_CHPU) {
        $row[0] = $selected_brand[9];
    } else {
        $row[0] = "index.php?brand=" . $selected_brand[0];
    }
    $row[1] = $selected_brand[1];
    $path[] = $row;
    $smarty->assign("product_category_path", $path);
    $smarty->assign("main_content_template", "brands.tpl.html");
    $g_count = db_r("SELECT count(*) FROM " . PRODUCTS_TABLE . " WHERE " . $catw . " enabled=1 and brandID=".(int)$selected_brand[0]);
    $smarty->assign("catalog_navigator", NULL);
    $smarty->assign("products_to_show", NULL);
    $smarty->assign("products_to_show_count", NULL);
    if ($g_count) // there are products in the category
    {
        if ($offset > $g_count) $offset = 0;
        $sql = 'SELECT P.*,B.name brand_name, B.hurl brand_hurl, count(R.reviewID) count_review FROM `' . PRODUCTS_TABLE . '` AS P LEFT JOIN `' . REVIEW_TABLE . '` as R USING(productID) LEFT JOIN `' . BRAND_TABLE . '` as B USING (brandID)  INNER JOIN `' . CATEGORIES_TABLE . '` AS C ON (P.categoryID = C.categoryID) WHERE P.brandID=' .(int)$selected_brand[0] . ' AND P.enabled =1 AND C.enabled =1 GROUP BY `P`.`productID` ORDER BY P.' . $_SESSION['sort'] . ' ' . $_SESSION['order'];
        if (!isset($_GET['show_all'])) $sql.= ' LIMIT ' . $offset . ' , ' . CONF_PRODUCTS_PER_PAGE;
        $result=products_to_show($sql);
        $idp = '(' . substr($result['id_products'], 0, strlen($result['id_products']) - 1) . ')';
        $smarty->assign("products_to_show", $result['result']);
        $smarty->assign("products_to_show_count", count($result['result']));
        unset($result);
        $options_info = options_list($idp, CONF_SHOW_PRODUCT_VARIANTS_INSTOCK);
        $smarty->assign("options", $options_info['options']);
        $smarty->assign("p_default", $options_info['pic_default']);
        //number of products to show on this page
        if (!isset($_GET["show_all"])) {
            $min = CONF_PRODUCTS_PER_PAGE;
            if ($min > $g_count - $offset) $min = $g_count - $offset;
        } else {
            $min = $g_count;
            $offset = "show_all";
        }
        #$smarty->assign("products_to_show_count", $min);
        $navigator = ""; //navigation links
        showNavigator($g_count, $offset, CONF_PRODUCTS_PER_PAGE, $selected_brand[9] . "&amp;", $navigator);
        //showNavigator($g_count, $offset, CONF_PRODUCTS_PER_PAGE, $nav_path."&amp;",$navigator);
        $smarty->assign("catalog_navigator", $navigator);
    }
}
?>