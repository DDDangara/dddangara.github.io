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
 
    if( !defined( 'WORKING_THROUGH_ADMIN_SCRIPT' ) ){
        die;
    }
    if( !strcmp( $sub, "orders" ) ){
        $status = (int)$_GET['status'];
        if( isset( $_GET["delete"] ) && is_numeric($_GET["delete"]) ) //cancel order without affecting products table
        {
            $status = db_r( 'select  `status` from ' . ORDERS_TABLE . ' WHERE orderID= ' . (int)$_GET["delete"] );
            $q = db_query( "select productID, Quantity, name FROM " . ORDERED_CARTS_TABLE . " where orderID=" . (int)$_GET["delete"] . " and name not LIKE '" . ADMIN_DISCOUNT_STRING . "%'" );
            while( $row = db_fetch_row( $q ) ){
                $rezult = db_assoc( "select `in_stock`,`items_sold` from `" . PRODUCTS_TABLE . "` where productID=" . $row[0] );
                if( $rezult !== FALSE ){
                    $rezult['in_stock'] += $row[1];
                    $rezult['items_sold'] -= $row[1];
                    update_field( PRODUCTS_TABLE, $rezult, "`productID` =" . $row[0] );
                }
                if( preg_match( '/\((.*)\)/', $row[2], $params ) ){
                    $params = explode( ',', $params[1] );
                    foreach( $params as $val ){
                        $val = explode( ':', $val );
                        $uopt = array();
                        $sql = 'select OV.count,OV.variantID from ' . PRODUCT_OPTIONS_TABLE . ' as PNAME LEFT JOIN ' . PRODUCT_OPTIONS_VAL_TABLE . ' as PVariant on (PNAME.optionID=PVariant.optionID) left join ' . PRODUCT_OPTIONS_V_TABLE . ' as OV on (PVariant.variantID=OV.variantID) where OV.productID=' . $row[0] . ' and PVariant.name=\'' . $val[1] . '\' and PNAME.name=\'' . $val[0] . '\'';
                        $orez = db_assoc( $sql );
                        $uopt['count'] = $orez['count'] + $row[1];
                        update_field( PRODUCT_OPTIONS_V_TABLE, $uopt, "`productID` =" . $row[0] . ' and variantID=' . $orez['variantID'] );
                    }
                }
            }
            db_query( "DELETE FROM " . ORDERED_CARTS_TABLE . " WHERE orderID='" . (int)$_GET["delete"] . "'" ) or die( db_error() );
            db_query( "DELETE FROM " . ORDERS_TABLE . " WHERE orderID='" . (int)$_GET["delete"] . "'" ) or die( db_error() );
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=orders&status=" . $status );
            exit;
        }
        if( isset( $_GET["complite"] ) && isset( $_GET["orderid"] ) && $_GET["orderid"] ){
            $status=db_r('select  `status` from '. ORDERS_TABLE.' WHERE orderID= '. $_GET["orderid"]);
            db_query( "UPDATE " . ORDERS_TABLE . " SET status='2' WHERE orderID='" . $_GET["orderid"] . "'" );
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=orders&status=".$status );
            exit;
        }
        //show all incomplete orders
        if( isset( $_GET["limit"] ) ){
            $limit = $_GET["limit"];
        } else{
            $limit = 10;
        }

        if( isset( $_GET["offset"] ) ){
            $offset = $_GET["offset"];
        } else{
            $offset = 0;
        }

        if( !isset( $_GET['sort'] ) )
            $_GET['sort'] = 'orderID';
        if( !isset( $_GET['order'] ) )
            $_GET['order'] = 'desc';

        if( isset( $_POST['show'] ) && $_POST['show'] > 0 )
            $_SESSION['new_order_limit'] = (int)$_POST['show']; elseif( !isset( $_SESSION['new_order_limit'] ) )
            $_SESSION['new_order_limit'] = 25;
        $diskont = array();
        $sql_order_count = 'SELECT count(*)  FROM ' . ORDERS_TABLE . ' where status=' . $status;
        
        $order_count = db_r( $sql_order_count );
        $smarty->assign( "new_order_count", $order_count ); //new orders qunatity
        db_query( 'SET group_concat_max_len = 5000' );

        $sql_order = 'SELECT O.*,CONCAT(O.cust_firstname,\' \',O.cust_lastname) as cust_user ,CONCAT(cust_phone,\'<br />\',cust_email) as contact, GROUP_CONCAT( CONCAT(OC.name,\'  x \',OC.Quantity,\': \',\'' . CONF_CURRENCY_ID_LEFT . '\',OC.Price,\'' . CONF_CURRENCY_ID_RIGHT . '\') SEPARATOR \'<br>\' ) order_products, sum(OC.Price*OC.Quantity) price_summ,OS.status_name FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) JOIN ' . ORDER_STATUS_TABLE . ' as OS on (O.status=OS.statusID) WHERE OC.name NOT LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status=' . $status;
        if( ( isset( $_POST['keyword'] ) && $_POST['keyword'] && $_POST['keyword'] != STRING_SEARCH ) )
            $sql_order .= ' and (O.orderID=' . (int)$_POST['keyword'] . ' or O.cust_lastname like ' . int_text( $_POST['keyword'] ) . ')';
        $sql_order .= ' GROUP BY O.orderID ORDER BY ' . $_GET['sort'] . ' ' . $_GET['order'] . ' LIMIT ' . $offset . ' , ' . $limit;
        # $smarty->assign("new_orders", db_arAll($sql_order)); //new orders qunatity

        $q = db_query( 'SELECT OC.orderID, OC.Price, OC.name FROM ' . ORDERS_TABLE . ' AS O INNER JOIN ' . ORDERED_CARTS_TABLE . ' AS OC USING ( orderID ) WHERE OC.name LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and O.status =' . $status . ' GROUP BY O.orderID LIMIT ' . $offset . ', ' . $limit ) or die(db_error());
        while( $row = db_fetch_row( $q ) ){
            $diskont[$row[0]]['Price'] = $row[1];
            $diskont[$row[0]]['name'] = '<p style="color:#194F86;">' . $row[2] . '% x 1:' . $row[1] . '</p>';
        }
        $smarty->assign( "diskonts", $diskont );
        if( isset( $_GET['json'] ) ){
            $json = array();
            $json['total'] = $order_count;
            # print_r( $sql_order);
            $q = db_query( $sql_order );
            $rows = array();
            while( $row = db_assoc_q( $q ) ){
                if( isset( $diskont[$row['orderID']] ) ){

                    $row['diskont_name'] = $diskont[$row['orderID']]['name'];
                    $row['price_summ'] = show_price($row['price_summ'] - $diskont[$row['orderID']]['Price']);
                }
                $rows[] = $row;
            }
            $json['rows'] = $rows;
            header( 'Content-Type: application/json' );
            die( json_encode( $json ) );
        }
        unset( $sql_order );
        $smarty->assign( "h1", db_r('select group_name from '.ORDER_STATUS_TABLE.' where statusID='.$status) );
        $smarty->assign( "admin_sub_dpt", "custord_orders.tpl.html" );
    }
?> 