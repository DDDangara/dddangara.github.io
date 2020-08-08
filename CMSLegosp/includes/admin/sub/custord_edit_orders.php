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

    if( !strcmp( $sub, "edit_orders" ) ){

        if( isset( $_GET['terminate'] ) ){
            $key = (int)$_GET['terminate'];
            $q = db_assoc( 'select Quantity,name,productID from ' . ORDERED_CARTS_TABLE . ' where id=' . $key );
            $needle = ADMIN_SHIPPING . ' ';
            $haystack = $q['name'];
            $Quantity = 0;
            if( substr( $haystack, 0, strlen( $needle ) ) != $needle && $q['name'] != ADMIN_FAST_ORDER ){
                $in_stock = db_r( 'select in_stock from ' . PRODUCTS_TABLE . ' where productID=' . $q['productID'] );
                $p = array();
                $p['in_stock'] = $in_stock + $q['Quantity'];
                update_field( PRODUCTS_TABLE, $p, 'productID=' . $q['productID'] );
            }
            db_query( 'DELETE FROM `' . ORDERED_CARTS_TABLE . '` WHERE `id` = ' . (int)$_GET['terminate'] );
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=edit_orders&orderID=" . (int)$_GET['orderID'] );
            exit;
        }
        if( isset( $_POST['save_order'] ) ){

            if( isset( $_POST['dicount'] ) ){
                $key = key( $_POST['dicount'] );
                $_POST['dicount'][$key]['name'] = ADMIN_DISCOUNT_STRING . ' ' . $_POST['order_discontpr'];
                $_POST['dicount'][$key]['orderID'] = (int)$_POST['save_order'];
                $_POST['dicount'][$key]['Quantity'] = 1;
                if( $key == 0 && $_POST['dicount'][$key]['Price'] > 0 )
                    add_field( ORDERED_CARTS_TABLE, $_POST['dicount'][$key] ); elseif( $key != 0 ){

                    update_field( ORDERED_CARTS_TABLE, $_POST['dicount'][$key], "id=" . $key );
                }
            }

            if (isset($_POST['send_ch_order'])){
                $to['mail'] = $_POST['shipping']["cust_email"];
                $to['name'] = $_POST['shipping']["cust_lastname"]. " " . $_POST['shipping']["cust_firstname"];
                $from['mail'] = CONF_GENERAL_EMAIL;
                $from['name'] = CONF_SHOP_NAME;
                phpmailer( $to, $from, 'Статуст заказа № '.$_POST['save_order'].' изменен', $_POST['text_ch_order'] );
            }


            foreach( $_POST['order_content'] as $key => $value ){

                $q = db_assoc( 'select Quantity,name,productID from ' . ORDERED_CARTS_TABLE . ' where id=' . $key );
                $needle = ADMIN_SHIPPING . ' ';
                $haystack = $q['name'];
                $Quantity = 0;
                if( substr( $haystack, 0, strlen( $needle ) ) != $needle && $q['name'] != ADMIN_FAST_ORDER ){
                    $Quantity = $value['Quantity'] - $q['Quantity'];
                    if( $Quantity != 0 ){
                        $in_stock = db_r( 'select in_stock from ' . PRODUCTS_TABLE . ' where productID=' . $q['productID'] );
                        $p = array();
                        $p['in_stock'] = $in_stock - $Quantity;
                        update_field( PRODUCTS_TABLE, $p, 'productID=' . $q['productID'] );
                    }
                } else  $value['Quantity'] = 1;
                update_field( ORDERED_CARTS_TABLE, $value, "id=" . $key );
            }
            if( isset( $_POST["addproduct"] ) && count( $_POST["addproduct"] ) > 0 )
                foreach( $_POST["addproduct"] as $key => $value ){
                    $p = array();
                    $value['productID'] = $key;
                    $value['orderID'] = $_POST['save_order'];
                    $p_inf = db_assoc( 'select name,in_stock from ' . PRODUCTS_TABLE . ' where productID=' . $key );
                    $value['name'] = $p_inf['name'];
                    $p['in_stock'] = $p_inf['in_stock'] - $value['Quantity'];

                    add_field( ORDERED_CARTS_TABLE, $value );

                    update_field( PRODUCTS_TABLE, $p, 'productID=' . $key );


                }
            update_field( ORDERS_TABLE, $_POST['shipping'], "orderID=" . (int)$_POST['save_order'] );
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=orders&status=".(int)$_POST['staus'] );
            exit;

        }

        $orderinf = db_assoc( "select * from " . ORDERS_TABLE . ' where orderID=' . (int)$_GET['orderID'] );
        $smarty->assign( 'orderinfo', $orderinf );
        # currency();


        $discont = db_assoc( "select id,name,Price from " . ORDERED_CARTS_TABLE . ' where name like  \'' . ADMIN_DISCOUNT_STRING . ' %\' and orderID=' . (int)$_GET['orderID'] );
        if( $discont ){
            preg_match( '/(.*) (\d+)/', $discont['name'], $m );

            $order_diskont['dp'] = $m[2];
            $order_diskont['Price'] = $discont['Price'];
            $order_diskont['id'] = $discont['id'];
        } else{
            $order_diskont['dp'] = 0;
            $order_diskont['Price'] = 0;
            $order_diskont['id'] = 0;
        }

        $smarty->assign( 'order_discont', $order_diskont );

        $orderprod = db_arAll( "select * from " . ORDERED_CARTS_TABLE . ' where  productID !=0 and name not like  \'' . ADMIN_DISCOUNT_STRING . ' %\' and orderID=' . (int)$_GET['orderID'] );
        $smarty->assign( 'order_products', $orderprod );
        $smarty->assign( "admin_sub_dpt", "custord_edit_orders.tpl.html" );
        $smarty->assign( 'order_statuses', db_arAll( 'select statusID, status_name from ' . ORDER_STATUS_TABLE . ' order by sort_order' ) );
    }
?>