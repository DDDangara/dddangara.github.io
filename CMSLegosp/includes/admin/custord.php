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
 

    /*db_query( 'REPLACE `' . ORDER_STATUS_TABLE . "` (`statusID`, `status_name`, `group_name`, `sort_order`) VALUES ('1', 'новый', 'Новые заказы', '-9999')" );
    db_query( 'REPLACE `' . ORDER_STATUS_TABLE . "` (`statusID`, `status_name`, `group_name`, `sort_order`) VALUES ('2', 'выполнен', 'Выполненные заказы', '9999')" );*/
    $sub_departments = array();

    $q=db_query( 'select statusID, group_name from ' . ORDER_STATUS_TABLE . ' order by sort_order' );

    while( $row = db_assoc_q( $q ) ){
        $sub_departments[] = array( "id" => "orders&status=" . $row['statusID'], "name" => $row['group_name']);
    }

    $sub_departments[] = array( "id" => "order_statuses", "name" => ORDER_STATUSES );
    $sub_departments[] = array( "id" => "users", "name" => ADMIN_USERS );
    $resualt = deny_menedger( $sub_departments, "custord" );
    $sub_departments = $resualt[0];

    if( count( $sub_departments ) > 0 ){


        //define a new admin department
        $admin_dpt = array( "id" => "custord", //department ID
        "sort_order" => 20, //sort order (less `sort_order`s appear first)
        "name" => ADMIN_CUSTOMERS_AND_ORDERS, //department name
        "sub_departments" => $sub_departments );
        add_department( $admin_dpt );

        //show department if it is being selected
        if( $dpt == "custord" ){
            //set default sub department if required
            if( !isset( $sub ) ){
                $sub = "orders";
                $_GET['status'] = 1;
            }

            //assign admin main department template
            $smarty->assign( "admin_main_content_template", $admin_dpt["id"] . ".tpl.html" );
            //assign subdepts
            $smarty->assign( "admin_sub_departments", $admin_dpt["sub_departments"] );
            //include selected sub-department
            if( file_exists( "./includes/admin/sub/" . $admin_dpt["id"] . "_$sub.php" ) && ( !array_search( $admin_dpt["id"] . "_" . $sub, $resualt[1] ) ) ) //sub-department file exists
                include( "./includes/admin/sub/" . $admin_dpt["id"] . "_$sub.php" ); else //no sub department found
                $smarty->assign( "admin_main_content_template", "notfound.tpl.html" );

        }

        //safemode
        $smarty->assign( "safemode", 0 );

        $admin_sub_menus['custord'] = $admin_dpt["sub_departments"];
        $smarty->assign( "admin_sub_menus", $admin_sub_menus );
    }
?>