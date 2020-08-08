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
 
if (!defined('WORKING_THROUGH_ADMIN_SCRIPT')) {
    die;
}
if (!strcmp($sub, "new_orders")) {
    SetCookie("legosp[new_orders]",strtotime(date("Y-m-d H:i:s")),time()+3600*3600,'./');
    if (isset($_GET["delete"]) && $_GET["delete"]) //cancel order without affecting products table
    {
        $q = db_query("select productID, Quantity, name FROM " . ORDERED_CARTS_TABLE . " where orderID=" . (int)$_GET["delete"] . " and name not LIKE '" . ADMIN_DISCOUNT_STRING . "%'");
        while ($row = db_fetch_row($q)) {
            $rezult = db_assoc("select `in_stock`,`items_sold` from `" . PRODUCTS_TABLE . "` where productID=" . $row[0]);
            if ($rezult !== FALSE) {
                $rezult['in_stock']+= $row[1];
                $rezult['items_sold']-= $row[1];
                update_field(PRODUCTS_TABLE, $rezult, "`productID` =" . $row[0]);
            }
            if (preg_match('/\((.*)\)/', $row[2], $params)) {
                $params = explode(',', $params[1]);
                foreach($params as $val) {
                    $val = explode(':', $val);
                    $uopt = array();
                    $sql = 'select OV.count,OV.variantID from ' . PRODUCT_OPTIONS_TABLE . ' as PNAME LEFT JOIN ' . PRODUCT_OPTIONS_VAL_TABLE . ' as PVariant on (PNAME.optionID=PVariant.optionID) left join ' . PRODUCT_OPTIONS_V_TABLE . ' as OV on (PVariant.variantID=OV.variantID) where OV.productID=' . $row[0] . ' and PVariant.name=\'' . $val[1] . '\' and PNAME.name=\'' . $val[0] . '\'';
                    $orez = db_assoc($sql);
                    $uopt['count'] = $orez['count'] + $row[1];
                    update_field(PRODUCT_OPTIONS_V_TABLE, $uopt, "`productID` =" . $row[0] . ' and variantID=' . $orez['variantID']);
                }
            }
        }
        db_query("DELETE FROM " . ORDERED_CARTS_TABLE . " WHERE orderID='" . (int)$_GET["delete"] . "'") or die(db_error());
        db_query("DELETE FROM " . ORDERS_TABLE . " WHERE orderID='" . (int)$_GET["delete"] . "'") or die(db_error());
        header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=new_orders");
    }
    if (isset($_GET["complite"]) && isset($_GET["orderid"]) && $_GET["orderid"])
    {
	db_query("UPDATE ".ORDERS_TABLE." SET status='1' WHERE orderID='".$_GET["orderid"]."'");
	header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=new_orders");
        exit;
    }
    //show all incomplete orders
    if (isset($_POST['show']) && $_POST['show'] > 0) $_SESSION['new_order_limit'] = (int)$_POST['show'];
    elseif (!isset($_SESSION['new_order_limit'])) $_SESSION['new_order_limit'] = 25;
    $diskont = array();
    $sql_new_order_count = 'SELECT count(*)  FROM ' . ORDERS_TABLE . ' where status=0';
    if (($_SESSION["access"] == 1) && ($_SESSION["manager_id"])) $sql_new_order_count.= ' and manager=' . $_SESSION["manager_id"];
    if ((isset($_POST['keyword']) && $_POST['keyword'] && $_POST['keyword']!=STRING_SEARCH))  $sql_new_order_count .=' and (O.orderID='.(int)$_POST['keyword'].' or O.cust_lastname like '.int_text($_POST['keyword']).')'; 
    $new_order_count = db_r($sql_new_order_count);
    $smarty->assign("new_order_count", $new_order_count); //new orders qunatity
    if (!isset($_POST['pages']) || $_POST['pages'] < 2) $pages = 0;
    else $pages = (int)$_POST['pages'] - 1;
    $sql_new_order = 'SELECT O.*, GROUP_CONCAT( CONCAT(OC.name,\'  x \',OC.Quantity,\': \',\''.CONF_CURRENCY_ID_LEFT.'\',OC.Price,\''.CONF_CURRENCY_ID_RIGHT.'\') SEPARATOR \'<br>\' ) order_products, cast(sum(OC.Price*OC.Quantity) as decimal(10,2)) price_summ FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) WHERE OC.name NOT LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status =0 ';
    if ((isset($_POST['keyword']) && $_POST['keyword'] && $_POST['keyword']!=STRING_SEARCH))  $sql_new_order .=' and (O.orderID='.(int)$_POST['keyword'].' or O.cust_lastname like '.int_text($_POST['keyword']).')'; 
    $sql_new_order.= 'GROUP BY O.orderID ORDER BY O.orderID DESC LIMIT ' . $pages * $_SESSION['new_order_limit'] . ' , ' . $_SESSION['new_order_limit'];
    $smarty->assign("new_orders", db_arAll($sql_new_order)); //new orders qunatity
    unset($sql_new_order);
    $q = db_query('SELECT OC.orderID, OC.Price, OC.name FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) WHERE OC.name LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status =0 GROUP BY O.orderID LIMIT ' . $pages * $_SESSION['new_order_limit'] . ' , ' . $_SESSION['new_order_limit']);
    while ($row = db_fetch_row($q)) {$diskont[$row[0]]['Price'] = $row[1]; $diskont[$row[0]]['name'] = '<p style="color:#194F86;">'.$row[2].'% x 1:'.$row[1].'</p>';}
    $smarty->assign("diskonts", $diskont);
    $smarty->assign("admin_sub_dpt", "custord_new_orders.tpl.html");
}
?> 