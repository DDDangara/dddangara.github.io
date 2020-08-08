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
    //show new orders page if selected
    if( !strcmp( $sub, "products" ) ){
        if( isset( $_GET['load_productlist'] ) && isset( $_GET['categoryID'] ) && is_numeric( $_GET['categoryID'] ) ){
            $sql_p = "SELECT P.productID, name, customers_rating, Price, in_stock, picture, big_picture, thumbnail, items_sold, enabled, product_code, yml FROM " . PRODUCTS_TABLE . " as P LEFT JOIN " . REVIEW_TABLE . " as R on P.productID=R.productID WHERE categoryID=" . $_GET['categoryID'];

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

            if( isset( $_GET["search"] ) ){
                $search = ' and name like \'%' . validate_search_string( $_GET["search"] ) . '%\' ';
            } else{
                $search = "";
            }

            $sql_p .= $search;
            if( $_SESSION['access'] == 1 && isset( $_SESSION['manager_id'] ) )
                $sql_p .= ' and (P.managerID=\'-1\' or  P.managerID is NULL or P.managerID=' . (int)$_SESSION['manager_id'] . ')';
            $sql_p .= ' GROUP BY P.productID  ORDER BY P.' . $_GET['sort'] . ' ' . $_GET['order'] . ' limit ' . $offset . ',' . $limit;
            $rp = array();
            $rp['total'] = db_r( 'select count(*) from ' . PRODUCTS_TABLE . ' where categoryID=' . $_GET['categoryID']. $search );
            $rp['rows'] = db_arAll( $sql_p );
            header( 'Content-Type: application/json' );
            echo json_encode( $rp );
            exit;
        }
        if( isset( $_POST["products_update"] ) ){ //save changes in current category

            //disable all products


            foreach( $_POST['p'] as $key => $val ){
                if( !isset( $val['enabled'] ) )
                    $val['enabled'] = 0;
                if( !isset( $val['yml'] ) )
                    $val['yml'] = 0;
                $val['Price'] = round( $val['Price'] * 100 ) / 100;
                update_field( PRODUCTS_TABLE, $val, "productID=" . (int)$key );

            }
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=catalog&sub=products&categoryID=" . (int)$_POST["categoryID"] );
            exit;
        } else if( isset( $_GET["terminate"] ) ) //delete product
        {

            if( $_SESSION['access'] == 1 && isset( $_SESSION['manager_id'] ) )
                $access_del = db_assoc( 'select * FROM ' . PRODUCTS_TABLE . ' WHERE productID=' . (int)$_GET["terminate"] . ' and (managerID=\'-1\' or  managerID is NULL or managerID=' . (int)$_SESSION['manager_id'] . ')' );
            else $access_del = db_assoc( 'select * FROM ' . PRODUCTS_TABLE . ' WHERE productID=' . (int)$_GET["terminate"]);
            if( $access_del ){
                $q = db_query( "SELECT picture, thumbnail, big_picture, enabled 	FROM " . PRODUCTS_TABLE . " WHERE productID='" . (int)$_GET["terminate"] . "'" ) or die ( db_error() );
                $row = db_fetch_row( $q );
                $old_pic_name = substr( $access_del['picture'], 0, strlen( $access_del['picture'] ) - 4 );
                //at first photos...
                if( $row[0] != "none" && $row[0] != "" && file_exists( "./products_pictures/" . $access_del['picture'] ) )
                    unlink( "./products_pictures/" . $access_del['picture'] );
                if( $row[0] != "none" && $row[0] != "" && file_exists( "./products_pictures/" . $old_pic_name . "-H.jpg" ) )
                    unlink( "./products_pictures/" . $old_pic_name . "-H.jpg" );
                if( $row[0] != "none" && $row[0] != "" && file_exists( "./products_pictures/" . $old_pic_name . "-SC.jpg" ) )
                    unlink( "./products_pictures/" . $old_pic_name . "-SC.jpg" );
                if( $row[1] != "none" && $row[1] != "" && file_exists( "./products_pictures/" . $row[1] ) )
                    unlink( "./products_pictures/" . $row[1] );
                if( $row[2] != "none" && $row[2] != "" && file_exists( "./products_pictures/" . $row[2] ) )
                    unlink( "./products_pictures/" . $row[2] );

                db_query( "DELETE FROM " . PRODUCTS_TABLE . " WHERE productID='" . $_GET["terminate"] . "'" );
                db_query( "DELETE FROM " . THUMB_TABLE . " WHERE productID='" . $_GET["terminate"] . "'" );
                db_query( "DELETE FROM " . TAGS_TABLE . " WHERE pid='" . $_GET["terminate"] . "'" );
                //$par = (int)$_GET["categoryID"];
                $cout_p = $row[3] * ( -1 );
                //update_products_Count_Value_For_Categories_new( $par, $cout_p, -1 );
            }
            header( "Location: " . CONF_ADMIN_FILE . "?dpt=catalog&sub=products&categoryID=" . $access_del['categoryID'] );
            exit;
        }

        if( isset( $_GET["dublicate_product"] ) && $_GET["dublicate_product"] = (int)$_GET["dublicate_product"] ){

            $access_dubl = 1;
            if( $_SESSION['access'] == 1 && isset( $_SESSION['manager_id'] ) )
                $access_dubl = db_assoc( 'select categoryID FROM ' . PRODUCTS_TABLE . ' WHERE productID=' . (int)$_GET["dublicate_product"] . ' and (managerID=\'-1\' or  managerID is NULL or managerID=' . (int)$_SESSION['manager_id'] . ')' );
            else {
                $access_dubl = db_assoc( 'select categoryID FROM ' . PRODUCTS_TABLE . ' WHERE productID=' . (int)$_GET["dublicate_product"]);
            }
            if( $access_dubl ){
                $par = $access_dubl['categoryID'];
                $_GET["dublicate_product"] = (int)$_GET["dublicate_product"];
                $ar = db_assoc( "SELECT name,description,Price,enabled,brief_description,list_price,accompanyID,brandID,meta_title,meta_keywords,meta_desc,h1,yml,min_qunatity,managerID,categoryID FROM " . PRODUCTS_TABLE . " WHERE productID='" . $_GET["dublicate_product"] . "'" ) or die ( db_error() );

                add_field( PRODUCTS_TABLE, $ar ) or die ( db_error() );
                $pid = db_insert_id();
                if( $ar['enabled'] )
                    $cout_p = 1; else $cout_p = 0;
                $new_hurl = $lego_admin->to_url( $ar['name'] ) . "-" . $pid . "/";
                db_query( "UPDATE " . PRODUCTS_TABLE . " SET hurl='" . $new_hurl . "' WHERE productID='" . $pid . "'" ) or die ( db_error() );


                //tags
                $ar = array();
                $ar = db_assoc( "SELECT tag, hurl FROM " . TAGS_TABLE . " WHERE pid='" . $_GET["dublicate_product"] . "'" );
                if( $ar ){
                    $ar['pid'] = $pid;
                    add_field( TAGS_TABLE, $ar ) or die ( db_error() );
                }

                update_products_Count_Value_For_Categories_new( $par, $cout_p, 1 );

                header( "Location: " . CONF_ADMIN_FILE . "?dpt=catalog&sub=products&categoryID=" . $access_dubl['categoryID'] );
                exit;
            }
        }


        //create a category tree
        $admin_category = new adminClass();
        $c = $admin_category->category_list();
        $smarty->assign( "categories", $c );

        //show category name as a title
        $row = array();
        if( !isset( $_GET["categoryID"] ) && !isset( $_POST["categoryID"] ) ){
            $categoryID = 0;
            $row[0] = ADMIN_CATEGORY_ROOT;
        } else //go to the root if category doesn't exist
        {
            $categoryID = isset( $_GET["categoryID"] ) ? (int)$_GET["categoryID"] : (int)$_POST["categoryID"];
            $q = db_query( "SELECT name FROM " . CATEGORIES_TABLE . " WHERE categoryID<>0 and categoryID='$categoryID'" ) or die ( db_error() );
            $row = db_fetch_row( $q );
            if( !$row ){
                $categoryID = 0;
                $row[0] = ADMIN_CATEGORY_ROOT;
            }
        }

        $smarty->assign( "categoryID", $categoryID );
        $smarty->assign( "category_name", $row[0] );

        If( isset( $_GET['p_sort'] ) )
            $_SESSION['product_sort'] = $_GET['p_sort']; elseif( !isset( $_SESSION['product_sort'] ) )
            $_SESSION['product_sort'] = 'productID';
        If( isset( $_GET['p_order'] ) )
            $_SESSION['product_order'] = $_GET['p_order']; elseif( !isset( $_SESSION['product_order'] ) )
            $_SESSION['product_order'] = 'DESC';


        //calculate how many products are there in the root category
        $count_root = db_r( "SELECT count(*) FROM " . PRODUCTS_TABLE . " WHERE categoryID=0" );
        $smarty->assign( "products_in_root_category", $count_root );

        if( $categoryID )
            $smarty->assign( "products_count", db_r( 'select products_count_admin from ' . CATEGORIES_TABLE . ' where categoryID=' . $categoryID ) ); else $smarty->assign( "products_count", $count_root );
        unset( $count_root );

        if( isset( $_POST['show'] ) && $_POST['show'] > 0 )
            $_SESSION['products_limit'] = (int)$_POST['show']; elseif( !isset( $_SESSION['products_limit'] ) )
            $_SESSION['products_limit'] = 25;
        if( !isset( $_POST['pages'] ) || $_POST['pages'] < 2 )
            $pages = 0; else $pages = (int)$_POST['pages'] - 1;

        $par = $categoryID;
        $patch_category = array();
        while( $par > 0 ){
            $category_info = db_assoc( 'select parent,name from ' . CATEGORIES_TABLE . ' where categoryID=' . $par );
            array_unshift( $patch_category, array( 'name' => $category_info['name'], 'categoryID' => $par ) );
            $par = $category_info['parent'];
        }
        $smarty->assign( "patch_category", $patch_category );


        //set main template
        $smarty->assign( "admin_sub_dpt", "catalog_products.tpl.html" );
    }

?>