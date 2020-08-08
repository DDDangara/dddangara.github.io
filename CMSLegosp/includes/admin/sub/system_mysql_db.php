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
 

    //general settings

    if( !defined( 'WORKING_THROUGH_ADMIN_SCRIPT' ) ){
        die;
    }
    #echo "sub=".$sub; exit;
    if( !strcmp( $sub, "mysql_db" ) ){
        $smarty->assign( "mysql_result", "" );

        if( isset( $_FILES['mysql_db_import'] ) && $_FILES['mysql_db_import']['name'] ) //import db
        {
            $input_f = str_replace( " ", "_", $_FILES['mysql_db_import']['name'] );
            $r = move_uploaded_file( $_FILES['mysql_db_import']['tmp_name'], TMP_DIR . $input_f );

            $fp = fopen( TMP_DIR . $input_f, "r" ) or die ( "Не удалось открыть файл" );

            $first_line = fgets( $fp, 1024 );

            $a = strpos( $first_line, 'mihey' );
            //$b=strpos($first_line, '20-02-10');

            if( $a ) //"Mihey Edition"
            {
                //set time limit
                ini_set( "max_execution_time", "0" );

                //create tables

                db_query( "DROP TABLE IF EXISTS `ssme_orders`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_orders_carts`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_products`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_categories`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_special_offers`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_tags`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_feedbacks`" ) or die ( db_error() );

                db_query( "CREATE TABLE `ssme_orders` (orderID INT PRIMARY KEY AUTO_INCREMENT, order_time DATETIME, cust_firstname VARCHAR(30), cust_lastname VARCHAR(30), cust_email VARCHAR(30), cust_country VARCHAR(30), cust_zip VARCHAR(30), cust_state VARCHAR(30), cust_city VARCHAR(30), cust_address VARCHAR(30), cust_phone VARCHAR(30)) CHARACTER SET cp1251 COLLATE cp1251_general_ci;" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_orders_carts` (productID INT NOT NULL, orderID INT NOT NULL, name CHAR(255), Price FLOAT, Quantity INT, PRIMARY KEY (productID, orderID)) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_products` (productID INT PRIMARY KEY AUTO_INCREMENT, categoryID INT, name VARCHAR(255), description TEXT, customers_rating FLOAT NOT NULL, Price FLOAT, picture VARCHAR(30), in_stock INT, thumbnail VARCHAR(30), customer_votes INT NOT NULL, items_sold INT NOT NULL, big_picture VARCHAR(30), enabled INT NOT NULL, brief_description TEXT, list_price FLOAT, product_code CHAR(25), hurl VARCHAR (255)) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_categories` (categoryID INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), parent INT, products_count INT, description TEXT, picture VARCHAR(30), products_count_admin INT) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_special_offers` (offerID INT PRIMARY KEY AUTO_INCREMENT, productID INT, sort_order INT) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_tags` (id INT NOT NULL AUTO_INCREMENT, pid INT (11) DEFAULT NULL, tag VARCHAR (20) DEFAULT NULL, PRIMARY KEY (id)) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );
                db_query( "CREATE TABLE `ssme_feedbacks` (id INT (11) NOT NULL AUTO_INCREMENT, productID INT (11) NOT NULL, username VARCHAR (30) DEFAULT NULL, email VARCHAR (30) DEFAULT NULL, feedback TEXT DEFAULT NULL, date_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id)) CHARACTER SET cp1251 COLLATE cp1251_general_ci" ) or die ( db_error() );

                include( ROOT_DIR . "/includes/database/install/mysql.php" );


                //open mihey db

                //fill products and categories tables
                $helper = "[#%int!g%#]"; //helper

                $f = implode( "", file( TMP_DIR . $input_f ) );
                $f = explode( "INSERT INTO", $f );

                $sql_result = 0;
                $sql_str = '';

                for( $i = 1; $i < count( $f ); $i++ ){
                    db_query( trim( "INSERT INTO " . str_replace( $helper, "INSERT INTO", $f[$i] ) ) ) or $sql_result = $i . ")<br />" . trim( "INSERT INTO " . str_replace( $helper, "INSERT INTO", $f[$i] ) ) . "<br />&nbsp;";
                }

                //copying

                $q = db_query( "SELECT * FROM `ssme_categories` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . CATEGORIES_TABLE . " (categoryID, name, parent, products_count, description, picture, products_count_admin, about, enabled, meta_title, meta_keywords, meta_desc, hurl, canonical, h1) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', '" . $row[4] . "', '" . $row[5] . "', '" . $row[6] . "', '', '1', '" . $row[1] . "', '" . $row[1] . "', '" . $row[1] . "', '" . to_url( $row[1] ) . "/', '', '" . $row[1] . "');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_orders` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . ORDERS_TABLE . " (orderID, order_time, cust_firstname, cust_lastname, cust_email, cust_country, cust_zip, cust_state, cust_city, cust_address, cust_phone, status, comment, manager) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', '" . $row[4] . "', '" . $row[5] . "', '" . $row[6] . "', '" . $row[7] . "', '" . $row[8] . "', '" . $row[9] . "', '" . $row[10] . "', '0', '', '1');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_orders_carts` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . ORDERED_CARTS_TABLE . " (productID, orderID, name, Price, Quantity) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', '" . $row[4] . "');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_special_offers` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . SPECIAL_OFFERS_TABLE . " (offerID, productID, sort_order) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_tags` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . TAGS_TABLE . " (id, pid, tag, hurl, canonical) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . to_url( $row[2] ) . "/', '');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_feedbacks` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) )
                    db_query( "INSERT INTO " . REVIEW_TABLE . " (reviewID, productID, username, email, review, date_time) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', '" . $row[4] . "', '" . $row[5] . "');" ) or die ( db_error() );

                $q = db_query( "SELECT * FROM `ssme_products` WHERE 1" ) or die ( db_error() );
                while( $row = db_fetch_row( $q ) ){
                    if( !file_exists( "./products_pictures/" . $row[11] ) || ( $row[11] == '' ) )
                        if( file_exists( "./products_pictures/" . $row[6] ) ){
                            $row[11] = $row[6];
                        } else{
                            $row[11] = '';
                        }

                    if( $row[11] != '' ){
                        $new_pic_name = to_url( $row[2] ) . "-" . $row[0];
                        img_resize( "./products_pictures/" . $row[11], "./products_pictures/" . $new_pic_name . ".jpg", RESIZE_NORMAL_X, RESIZE_NORMAL_Y, CONF_IMAGE_COLOR );
                        //thumbnail
                        img_resize( "./products_pictures/" . $row[11], "./products_pictures/" . $new_pic_name . "-S.jpg", RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR );
                        //enlarged photo
                        img_resize( "./products_pictures/" . $row[11], "./products_pictures/" . $new_pic_name . "-B.jpg", RESIZE_BIG_X, RESIZE_BIG_Y, CONF_IMAGE_COLOR );
                        //hit photo
                        img_resize( "./products_pictures/" . $row[11], "./products_pictures/" . $new_pic_name . "-H.jpg", 130, 130, CONF_IMAGE_COLOR );
                        //shoping cart photo
                        img_resize( "./products_pictures/" . $row[11], "./products_pictures/" . $new_pic_name . "-SC.jpg", 50, 50, CONF_IMAGE_COLOR );

                        if( file_exists( "./products_pictures/" . $row[11] ) )
                            unlink( "./products_pictures/" . $row[11] );
                        $row[6] = $new_pic_name . ".jpg";
                        $row[8] = $new_pic_name . "-S.jpg";
                        $row[11] = $new_pic_name . "-B.jpg";
                    } else{
                        if( file_exists( "./products_pictures/" . $row[6] ) )
                            unlink( "./products_pictures/" . $row[6] );
                        if( file_exists( "./products_pictures/" . $row[8] ) )
                            unlink( "./products_pictures/" . $row[8] );
                        if( file_exists( "./products_pictures/" . $row[11] ) )
                            unlink( "./products_pictures/" . $row[11] );
                        $row[6] = '';
                        $row[8] = '';
                        $row[11] = '';
                    }


                    if( $row[16] == "" ){
                        $row[16] = to_url( $row[2] ) . "/";
                    }
                    db_query( "INSERT INTO " . PRODUCTS_TABLE . " (productID, categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, customer_votes, items_sold, big_picture, enabled, brief_description, list_price, product_code, hurl, accompanyID, brandID, meta_title, meta_keywords, meta_desc, canonical, h1) VALUES ('" . $row[0] . "', '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', " . $row[4] . ", '" . $row[5] . "', '" . $row[6] . "', '" . $row[7] . "', '" . $row[8] . "', '" . $row[9] . "',' " . $row[10] . "', '" . $row[11] . "', '" . $row[12] . "', '" . $row[13] . "', '" . $row[14] . "', '" . $row[15] . "', '" . $row[16] . "', '', '', '" . $row[2] . "', '" . $row[2] . "', '" . $row[2] . "', '', '" . $row[2] . "');" ) or die ( db_error() );
                }

                //delete mihey tables
                db_query( "DROP TABLE IF EXISTS `ssme_orders`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_orders_carts`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_products`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_categories`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_special_offers`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_tags`" ) or die ( db_error() );
                db_query( "DROP TABLE IF EXISTS `ssme_feedbacks`" ) or die ( db_error() );

                //result
                if( $sql_result == 0 ){
                    $result = '
				      <table style="width: 100%; background: #F0FFF0; padding: 2px; border: #00FF00 solid 1px; text-align: center;">
			        	<tr>
				          <td><br />"Mihey Edition": ' . ADMIN_MYSQL_DB_IMPORT_COMPLITE . ' ' . ( $i - 1 ) . '<br />&nbsp;</td>
				        </tr>
				      </table>';
                } else{
                    $result = '
				      <table style="width: 100%; background: #FFFFCC; padding: 2px; border: #CC0000 solid 1px; text-align: center;">
				        <tr>
				          <td style="color: #CC0000;"><br />"Mihey Edition" ' . ADMIN_MYSQL_DB_IMPORT_ERROR . $sql_result . '</td>
				        </tr>
				      </table>';
                }
                //set time limit back
                ini_set( "max_execution_time", "30" );
            } else  //standart import
            {

                //fill products and categories tables
                $helper = "[#%int!g%#]"; //helper

                $f = implode( "", file( TMP_DIR . $input_f ) );
                $f = explode( "INSERT INTO", $f );

                include( ROOT_DIR . "/includes/database/install/mysql.php" );

                $sql_result = 0;

                for( $i = 1; $i < count( $f ); $i++ ){
                    if( DB_CHARSET != 'cp1251' )
                        $f[$i] = win2utf( $f[$i] );
                    db_query( trim( "INSERT INTO " . str_replace( $helper, "INSERT INTO", $f[$i] ) ) ) or $sql_result = $i . ")<br />" . trim( "INSERT INTO " . str_replace( $helper, "INSERT INTO", $f[$i] ) ) . "<br />&nbsp;" . mysql_error() . "<br>";
                }

                if( $sql_result == 0 ){
                    $result = '
				      <table style="width: 100%; background: #F0FFF0; padding: 2px; border: #00FF00 solid 1px; text-align: center;">
			        	<tr>
				          <td><br />' . ADMIN_MYSQL_DB_IMPORT_COMPLITE . ' ' . ( $i - 1 ) . '<br />&nbsp;</td>
				        </tr>
				      </table>';
                } else{
                    $result = '
				      <table style="width: 100%; background: #FFFFCC; padding: 2px; border: #CC0000 solid 1px; text-align: center;">
				        <tr>
				          <td style="color: #CC0000;"><br />' . ADMIN_MYSQL_DB_IMPORT_ERROR . ' ' . $sql_result . '</td>
				        </tr>
				      </table>';
                }
            }

            $smarty->assign( "mysql_result", $result );

            $smarty->assign( "admin_sub_dpt", "system_mysql_db.tpl.html" );
        }

        if( isset( $_GET["optimize"] ) ) //show successful save confirmation message
        {
            $q = db_query( "OPTIMIZE TABLE " . PRODUCTS_TABLE . ", " . ORDERS_TABLE . ", " . ORDERED_CARTS_TABLE . ", " . CATEGORIES_TABLE . ", " . SPECIAL_OFFERS_TABLE . ", " . TAGS_TABLE . ", " . PAGES_TABLE . ", " . NEWS_TABLE . ", " . THUMB_TABLE . ", " . REVIEW_TABLE . ", " . BRAND_TABLE . ", " . VOTES_TABLE . ", " . VOTES_CONTENT_TABLE . ", " . SHARE_TABLE . ", " . MANAGER_TABLE . ", " . AUX_TABLE . ", " . PAYMENT_TABLE . ", " . PAYOPTION_TABLE ) or die ( db_error() );

            $result = '
			<table style="width: 100%; padding: 2px; border: #CCC solid 1px">
			  <tr style="background: #CCC; border: #CCC solid 1px; text-align: center;">
			    <td><b>Table</b></td>
			    <td><b>Op</b></td>
			    <td><b>Msg_type</b></td>
			    <td><b>Msg_text</b></td>
			  </tr>';

            while( $row = db_fetch_row( $q ) )
                $result .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";

            $result .= '</table>';

            $smarty->assign( "mysql_result", $result );
        }

        if( isset( $_GET["test"] ) ) //show successful save confirmation message
        {
            $q = db_query( "CHECK TABLE " . PRODUCTS_TABLE . ", " . ORDERS_TABLE . ", " . ORDERED_CARTS_TABLE . ", " . CATEGORIES_TABLE . ", " . SPECIAL_OFFERS_TABLE . ", " . TAGS_TABLE . ", " . PAGES_TABLE . ", " . NEWS_TABLE . ", " . THUMB_TABLE . ", " . REVIEW_TABLE . ", " . BRAND_TABLE . ", " . VOTES_TABLE . ", " . VOTES_CONTENT_TABLE . ", " . SHARE_TABLE . ", " . MANAGER_TABLE . ", " . AUX_TABLE . ", " . PAYMENT_TABLE . ", " . PAYOPTION_TABLE ) or die ( db_error() );

            $result = '
			<table style="width: 100%; padding: 2px; border: #CCC solid 1px">
			  <tr style="background: #CCC; border: #CCC solid 1px; text-align: center;">
			    <td><b>Table</b></td>
			    <td><b>Op</b></td>
			    <td><b>Msg_type</b></td>
			    <td><b>Msg_text</b></td>
			  </tr>';

            while( $row = db_fetch_row( $q ) )
                $result .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";

            $result .= '</table>';

            $smarty->assign( "mysql_result", $result );
        }

        if( isset( $_GET["export"] ) ) //show successful save confirmation message
        {
            //export db file

            $filename = "mysql_dump.txt";
            $filepath = TMP_DIR . $filename;

            $qt = db_query( "SHOW TABLES FROM `" . DB_NAME.'`');

            $f = fopen( $filepath, "w" );

            fputs( $f, "/30-05-10/" . "\r\n" );

            while( $all_tables = db_fetch_row( $qt ) ){

                $records = db_query( 'SHOW FIELDS FROM `' . $all_tables[0] . '`' );
                $num_fields = db_num_rows( $records );
                if( $num_fields == 0 )
                    return false;
                $insertStatement = 'INSERT INTO `' . $all_tables[0] . '` (';
                $selectStatement = "SELECT ";
                $hexField = array();
                for( $x = 0; $x < $num_fields; $x++ ){
                    $record = db_assoc_q( $records );

                    $selectStatement .= '`' . $record['Field'] . '`';
                    $insertStatement .= '`' . $record['Field'] . '`';
                    $insertStatement .= ", ";
                    $selectStatement .= ", ";

                }
                $insertStatement = @substr( $insertStatement, 0, -2 ) . ') VALUES';
                $selectStatement = @substr( $selectStatement, 0, -2 ) . ' FROM `' . $all_tables[0] . '`';

                $records = db_query( $selectStatement );
                $num_rows = db_num_rows( $records );
                $num_fields = db_num_fields( $records );
                $data = '';
                if( $num_rows > 0 ){
                    $data .= $insertStatement;
                    for( $i = 0; $i < $num_rows; $i++ ){
                        $record = db_assoc_q( $records );
                        $data .= ' (';
                        for( $j = 0; $j < $num_fields; $j++ ){
                            $field_name = db_field_name( $records, $j );
                            $data .= int_text( $record[$field_name] );
                            $data .= ',';
                        }
                        $data = @substr( $data, 0, -1 ) . ")";
                        $data .= ( $i < ( $num_rows - 1 ) ) ? ',' : ';';
                        $data .= "\n";
                    }
                    fputs( $f, $data );
                }


            }

            fclose( $f );
            $mm_type = "application/octet-stream";
            header( "Cache-Control: public, must-revalidate" );
            header( "Pragma: hack" );
            header( "Content-Type: " . $mm_type );
            header( "Content-Length: " . (string)( filesize( $filepath ) ) );
            header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
            header( "Content-Transfer-Encoding: binary\n" );

            readfile( $filepath );

            if( file_exists( $filepath ) ){
                unlink( $filepath );
            }

            $smarty->assign( "mysql_result", $result );
        }


        if( isset( $_POST["save_mysql"] ) ) //save system settings
        {

            //save cfg file
            $f = fopen( "./cfg/connect.inc.php", "w" );
            fputs( $f, "<?php\n\t//database connection settings\n\n" );
            fputs( $f, "\tdefine('DB_HOST', '" . str_replace( "'", "\'", stripslashes( $_POST["mysql_db_host"] ) ) . "'); // database host\n" );
            fputs( $f, "\tdefine('DB_USER', '" . str_replace( "'", "\'", stripslashes( $_POST["mysql_db_user"] ) ) . "'); // username\n" );
            fputs( $f, "\tdefine('DB_PASS', '" . str_replace( "'", "\'", stripslashes( $_POST["mysql_db_pass"] ) ) . "'); // password\n" );
            fputs( $f, "\tdefine('DB_NAME', '" . str_replace( "'", "\'", stripslashes( $_POST["mysql_db_name"] ) ) . "'); // database name\n" );
            fputs( $f, "\tdefine('DB_CHARSET', '" . str_replace( "'", "\'", stripslashes( $_POST["mysql_charset"] ) ) . "'); // database name\n" );
            fputs( $f, "\tdefine('ADMIN_LOGIN', '" . ADMIN_LOGIN . "'); //administrator's login\n" );
            fputs( $f, "\tdefine('ADMIN_PASS', '" . ADMIN_PASS . "'); //administrator's login\n\n" );
            fputs( $f, "\t//database tables\n" );
            fputs( $f, "\tinclude(" . 'dirname ( __FILE__ ).' . "\"/tables.inc.php\");\n\n" );
            fputs( $f, "?>" );
            fclose( $f );

            $smarty->assign( "configuration_saved", 1 );
        } else
            $smarty->assign( "configuration_saved", 0 );


        //set sub-department template
        $smarty->assign( "admin_sub_dpt", "system_mysql_db.tpl.html" );
    }

?>