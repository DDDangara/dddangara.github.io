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
if (!strcmp($sub, "complite_orders")) {
    if (isset($_GET["delete"]) && $_GET["delete"]) //cancel order without affecting products table
    {
        db_query("DELETE FROM " . ORDERED_CARTS_TABLE . " WHERE orderID='" . $_GET["delete"] . "'") or die(db_error());
        db_query("DELETE FROM " . ORDERS_TABLE . " WHERE orderID='" . $_GET["delete"] . "'") or die(db_error());
        header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=complite_orders");
    }
    if (isset($_GET["incomplite"]) && isset($_GET["orderid"]) && $_GET["orderid"]) {
        db_query("UPDATE " . ORDERS_TABLE . " SET status='0' WHERE orderID='" . $_GET["orderid"] . "'");
        header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=complite_orders");
    }
    if (isset($_POST['show']) && $_POST['show'] > 0) $_SESSION['complite_order_limit'] = (int)$_POST['show'];
    elseif (!isset($_SESSION['complite_order_limit'])) $_SESSION['complite_order_limit'] = 25;
    $diskont = array();
    $sql_complite_order_count = 'SELECT count(*)  FROM ' . ORDERS_TABLE .' AS O where status=1';
    if (($_SESSION["access"] == 1) && ($_SESSION["manager_id"])) $sql_complite_order_count.= ' and manager=' . $_SESSION["manager_id"];
    if ((isset($_POST['keyword']) && $_POST['keyword'] && $_POST['keyword']!=STRING_SEARCH))  $sql_complite_order_count .=' and (O.orderID='.(int)$_POST['keyword'].' or O.cust_lastname like '.int_text($_POST['keyword']).')';
    $complite_order_count = db_r($sql_complite_order_count);
    $smarty->assign("complite_order_count", $complite_order_count); //complite orders qunatity
    if (!isset($_POST['pages']) || $_POST['pages'] < 2) $pages = 0;
    else $pages = (int)$_POST['pages'] - 1;
    $sql_complite_order = 'SELECT O.*, GROUP_CONCAT( CONCAT(OC.name,\'  x \',OC.Quantity,\': \',\''.CONF_CURRENCY_ID_LEFT.'\',OC.Price,\''.CONF_CURRENCY_ID_RIGHT.'\') SEPARATOR \'<br>\' ) order_products, cast(sum(OC.Price*OC.Quantity) as decimal(10,2)) price_summ FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) WHERE OC.name NOT LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status =1';
    if ((isset($_POST['keyword']) && $_POST['keyword'] && $_POST['keyword']!=STRING_SEARCH))  $sql_complite_order .=' and (O.orderID='.(int)$_POST['keyword'].' or O.cust_lastname like '.int_text($_POST['keyword']).')'; 
    $sql_complite_order.= ' GROUP BY O.orderID ORDER BY O.orderID DESC LIMIT ' . $pages * $_SESSION['complite_order_limit'] . ' , ' . $_SESSION['complite_order_limit'];
    $smarty->assign("complite_orders", db_arAll($sql_complite_order)); //complite orders qunatity
    unset($sql_complite_order);
    $q = db_query('SELECT OC.orderID, OC.Price, OC.name FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) WHERE OC.name LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status =1 GROUP BY O.orderID LIMIT ' . $pages * $_SESSION['complite_order_limit'] . ' , ' . $_SESSION['complite_order_limit']);
    while ($row = db_fetch_row($q)) {$diskont[$row[0]]['Price'] = $row[1]; $diskont[$row[0]]['name'] = '<p style="color:#194F86;">'.$row[2].'% x 1:'.$row[1].'</p>';}
    $smarty->assign("diskonts", $diskont);
    $smarty->assign("admin_sub_dpt", "custord_complite_orders.tpl.html");
}
?> 