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
 
    if( !count( $_GET ) && !count( $_POST ) ){

        $total = db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue, count(DISTINCT O.orderID) orders FROM `' . ORDERS_TABLE . '` AS O JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID )' );
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_today, count(DISTINCT(O.orderID)) orders_today FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= CURDATE( ) ' ) );
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_yesterday, count(DISTINCT(O.orderID)) orders_yesterday FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= (CURDATE()-1)  and O.order_time<CURDATE()' ) );
        #$total["revenue_yesterday"] = show_price(db_r('SELECT sum( OC.Price * OC.Quantity ) FROM `'.ORDERS_TABLE.'` AS O INNER JOIN `'.ORDERED_CARTS_TABLE.'` AS OC USING ( orderID ) WHERE O.order_time >= (CURDATE()-1)  and O.order_time<CURDATE()'));
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_thismonth, count(DISTINCT(O.orderID)) orders_thismonth  FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= DATE_SUB(CURRENT_DATE, INTERVAL (DAYOFMONTH(NOW())-1) DAY)' ) );
        $total["revenue"] = show_price( db_r( 'SELECT sum( OC.Price * OC.Quantity ) FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID )' ) );
        // --- PRODUCTS ---
        $total = array_merge( $total, db_assoc( "select count(*) products,sum(Enabled) products_enabled from " . PRODUCTS_TABLE ) );
        // --- CATEGORIES ---
        $total = array_merge( $total, db_assoc( "select count(*) categories from " . CATEGORIES_TABLE ) );
        //safemode
        $smarty->assign( "safemode", 0 );
        $smarty->assign( "totals", $total );
        unset( $total );
    }
