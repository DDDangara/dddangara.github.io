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
 


    if( isset( $_GET['orderid'] ) ){
        include_once( "../cfg/ajax_connect.inc.php" );
        if( !isset( $_SESSION["log"] ) && !isset( $_SESSION["pass"] ) ){
            header( "location:index.php" );
            exit;
        }
        $_GET['orderid'] = (int)$_GET['orderid'];
        $cust_name = "";
        $cust_adress = "";
        $ord_num = 0;
        $ord_date = date( "dd.mm.Y" );
        $q = db_query( "SELECT * FROM " . ORDERS_TABLE . " where orderID=" . $_GET['orderid'] ) or die ( db_error() );
        $row = db_fetch_row( $q );
        if( $row ){
            $cust_name = $row[2] . " " . $row[3];
            $cust_adress = $row[5] . ", " . $row[6] . ", " . $row[7] . ", " . $row[8] . ", " . $row[9] . ", " . $row[6];
            $ord_num = $row[0];

        }
        require '../smarty/smarty.class.php';
        $smarty = new Smarty;
        $smarty->compile_dir = "./cache";
        $smarty->assign( "cust_name", $cust_name );
        $smarty->assign( "cust_adress", $cust_adress );
        $smarty->assign( "ord_date", $row[1] );
        $smarty->assign( "ord_num", $row[0] );
        $dq = db_query( "SELECT * FROM " . ORDERED_CARTS_TABLE . " where name!='' and orderID='" . $_GET['orderid'] . "' AND name LIKE '" . ADMIN_DISCOUNT_STRING . "%'" ) or die ( db_error() );
        $row_d = db_fetch_row( $dq );
        if( str_replace( ADMIN_DISCOUNT_STRING . " ", "", $row_d[3] ) ){
            $disc['val'] = str_replace( ADMIN_DISCOUNT_STRING . " ", "", $row_d[3] );
            $disc['id'] = $row_d[0];
        } else{
            $disc['val'] = 0;
            $disc['id'] = 0;
        }

        $mmm = db_arAll( "SELECT * FROM " . ORDERED_CARTS_TABLE . " where id !=" . $disc['id'] . " and Price>0 and orderID=" . $_GET['orderid'] ) or die ( db_error() );
        $smarty->assign( "mmm", $mmm );
        $smarty->assign( "disc", $disc );
        $smarty->display( "./admin_tmpl/print_torg12.tpl.html" );
    }
?>
