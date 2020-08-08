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
 
    if( isset( $custID ) && ( $custID == 0 ) ){
        $smarty->assign( 'email', $authData['email'] );
        if( !isset( $authData['email'] ) )
            $authData['email'] = '';
        $smarty->assign( 'email', $authData['email'] );
        $smarty->assign( 'openID', $authData['uid'] );
        $smarty->assign( 'first_name', $authData['first_name'] );
        $smarty->assign( 'last_name', $authData['last_name'] );
        $smarty->assign( "provider", $authData['network'] );
        $smarty->assign( "city", $authData['city'] );
        $smarty->assign( "country", $authData['country'] );
        $smarty->assign( "main_content_template", "reguser.tpl.html" );

    }


    if( isset( $_POST["cust_register"] ) || isset( $_GET["cust_register"] ) ){
        $smarty->assign( "main_content_template", "reguser.tpl.html" );
    } elseif( isset( $_POST["customer_register"] ) ){

        if( ( !isset( $_POST["password"] ) or !isset( $_POST["password2"] ) or ( $_POST["password"] != $_POST["password2"] ) ) and !isset( $_POST["openID"] ) ){
            $smarty->assign( "main_content_template", "reguser.tpl.html" );
        } elseif( !isset( $_POST["captcha"] ) or ( $_POST["captcha"] != $_SESSION["captcha"] ) ){

            $smarty->assign( "openID", $_POST['openID'] );
            $smarty->assign( "error", "Невверно введен зашитный код" );
            $smarty->assign( "main_content_template", "reguser.tpl.html" );
        } else{
            //check if such email already exists
            $email = validate_form_string( $_POST["email"] );
            $row = db_r( "SELECT count(custID) FROM " . CUST_TABLE . " WHERE cust_email=" . int_text( $email ) );

            if( $row > 0 ) // customer email already exists
            {
                $smarty->assign( 'email', validate_form_string( $_POST["email"] ) );
                $smarty->assign( 'first_name', validate_form_string( $_POST["first_name"] ) );
                $smarty->assign( 'last_name', validate_form_string( $_POST["last_name"] ) );
                $smarty->assign( 'phone', validate_form_string( $_POST["phone"] ) );
                $smarty->assign( 'city', validate_form_string( $_POST["city"] ) );
                $smarty->assign( 'address', validate_form_string( $_POST["address"] ) );
                $smarty->assign( 'zip', validate_form_string( $_POST["zip"] ) );
                $smarty->assign( 'country', validate_form_string( $_POST["country"] ) );
                $smarty->assign( 'email_already_exists', 'yes' );
                $smarty->assign( "main_content_template", "reguser.tpl.html" );
            } else{
                //insert new customer into database



                $newuser['cust_phone'] = $_POST["phone"];
                $newuser['cust_email'] = $_POST["email"];
                $newuser['cust_firstname'] = $_POST["first_name"];
                $newuser['cust_lastname'] = $_POST["last_name"];
                $newuser['cust_city'] = $_POST["city"];
                $newuser['cust_address'] = $_POST["address"];
                $newuser['cust_zip'] = (int)$_POST["zip"];
                $newuser['cust_country'] = $_POST["country"];
                $newuser['cust_password'] = md5( $_POST["password"] );
                if( isset( $_POST["openID"] ) ){
                    $newuser['openID'] = ( $_POST["openID"] );
                    $newuser['provider'] = ( $_POST["provider"] );
                }


                add_field( CUST_TABLE, $newuser );
                $cid = db_insert_id(); //customer ID

                $_SESSION['cust_id'] = $cid;
                $_SESSION['cust_login'] = "yes";
                setcookie( "cust_id", $cid, time() + 7889400 ); //3 monthes
                $sql = "SELECT * FROM " . CUST_TABLE . " WHERE custID='" . $cid . "'";
                $r = db_assoc( $sql );
                unset( $r['custID'], $r['openID'], $r['cust_password'], $r['provider'] );
                $_SESSION['userinf'] = $r;

                //assign registration data to smarty
                $smarty_mail->assign( "email", $_POST["email"] );
                $smarty_mail->assign( "password", $_POST["password"] );
                $smarty_mail->assign( "phone", $_POST["phone"] );
                $smarty_mail->assign( "order_custname", $_POST["first_name"] . " " . $_POST["last_name"] );
                $smarty_mail->assign( "order_shipping_address", "г." . $city . "\n" . $address ); //."\nг.".." ".$_POST["state"]."  ".$_POST["zip"]."\n".$_POST["country"]

                //send message to customer
                $file_name = ROOT_DIR . "/css/css_" . CONF_COLOR_SCHEME . "/image/mail_logo.jpg";
                $SHOP_NAME = CONF_SHOP_NAME;
                $NOTIFICATION_SUBJECT = EMAIL_CUSTOMER_REGISTER_NOTIFICATION_SUBJECT;

                $To = "=?" . DEFAULT_CHARSET . "?B?" . base64_encode( $first_nam . " " . $last_name ) . "?=<" . $_POST["email"] . ">";
                $Subject = "=?" . DEFAULT_CHARSET . "?B?" . base64_encode( $NOTIFICATION_SUBJECT ) . "?=";
                $From = "=?" . DEFAULT_CHARSET . "?B?" . base64_encode( $SHOP_NAME ) . "?=<" . CONF_GENERAL_EMAIL . ">";

                $bound = "message-bound";
                $headers = "From: " . $From . "\n";
                $headers .= "Return-path: <" . CONF_GENERAL_EMAIL . ">" . "\n";
                $headers .= "Mime-Version: 1.0n" . "\n";
                $headers .= "Content-Type: multipart/related; boundary=" . '"' . $bound . '"' . "\n";

                $html_body = $smarty_mail->fetch( "register_notification.tpl.html" );
                if( DB_CHARSET != 'cp1251' )
                    $html_body = win2utf( $html_body );
                $body = "--$bound\n";
                $body .= "Content-type: text/html; charset=\"" . DEFAULT_CHARSET . "\"\n";
                $body .= "Content-Transfer-Encoding: 8bit\n\n";
                $body .= $html_body;
                $body .= "\n\n--$bound\n";


                $body .= "Content-Type: image/jpeg; name=\"" . basename( $file_name ) . "\"\n";
                $body .= "Content-Transfer-Encoding:base64\n";
                $body .= "Content-ID: <mail_img_1>\n\n";
                $f = fopen( $file_name, "rb" );
                $body .= base64_encode( fread( $f, filesize( $file_name ) ) ) . "\n";
                $body .= "--$bound--\n\n";
				
				
				
				
				$from['mail'] = CONF_GENERAL_EMAIL;
				$from['name'] = $SHOP_NAME;
				$file_name            = "./css/css_".CONF_COLOR_SCHEME."/image/mail_logo.jpg";
				$file_img['file'] = $file_name;
				$file_img['cid'] = 'mail_img_1';
				
				$to['mail'] = $_POST["email"];
				$to['name'] = $first_nam." ".$last_name;
				
				phpmailer ($to, $from,$NOTIFICATION_SUBJECT,'', $html_body,$file_img);
                //mail( $To, $Subject, $body, $headers );
                header( "Location: http://" . CONF_SHOP_URL . "/index.php?cust_login=yes" );
            }
        }
    }
?>