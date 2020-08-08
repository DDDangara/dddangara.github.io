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
if (isset($_GET["novinki"]))
    $limit = 20;
else
    $limit = 8;

if (isset($_GET["novinki"])) {
    $sql = 'select P.* from ' . PRODUCTS_TABLE . ' as P join ' . CATEGORIES_TABLE . ' as C USING (categoryID) where C.enabled=1 and P.enabled=1 ';
    if (CONF_SHOW_PRODUCT_INSTOCK == 0)
        $sql .= ' and P.in_stock>0';
    $sql .=' ORDER BY productID DESC LIMIT 1,' . $limit;
    $result = products_to_show($sql);
    $result = $result['result'];
    $smarty->assign("products_to_show", $result);

    $path = Array();
    if (CONF_CHPU) {
        $par[0] = "./new/";
    } else {
        $par[0] = 'index.php?novinki=yes';
    }
    $par[1] = HOT_NEW;
    $path[] = $par;
    $smarty->assign("product_category_path", $path);

    $smarty->assign("main_content_template", "new_product_list.tpl.html");
} else {
    $sql = 'select productID,P.name,Price,P.list_price,P.picture,P.hurl,in_stock,P.customers_rating from ' . PRODUCTS_TABLE . ' as P join ' . CATEGORIES_TABLE . ' as C USING (categoryID) where C.enabled=1 and P.enabled=1 ';
    if (CONF_SHOW_PRODUCT_INSTOCK == 0)
        $sql .= ' and P.in_stock>0';
    $sql .=' ORDER BY productID DESC LIMIT 1,' . $limit;
    $result = products_to_show($sql);
    $result = $result['result'];
    $smarty->assign("new_products", $result);
}
?>