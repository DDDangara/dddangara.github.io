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
 
    //connect to the database
    include( "connect.inc.php" );
    include( "../includes/database/mysql.php" );
    include( "general.inc.php" );
    include( "appearence.inc.php" );
    include( "functions.php" );
    include( "language_list.php" );
    include( "product.inc.php" );
    include( "shipping.inc.php" );
    include( "votes.inc.php" );
    include( "company.inc.php" );
    include( "redirect.inc.php" );

    db_connect( DB_HOST, DB_USER, DB_PASS ) or die( db_error() );
    


    session_start();
    currency();
    //current language session variable
    if( !isset( $_SESSION["current_language"] ) || $_SESSION["current_language"] < 0 || $_SESSION["current_language"] > count( $lang_list ) )
        $_SESSION["current_language"] = 0; //set default language
    //include a language file
    if( isset( $lang_list[$_SESSION["current_language"]] ) && file_exists( "../languages/" . $lang_list[$_SESSION["current_language"]]->filename ) )
        include( "../languages/" . $lang_list[$_SESSION["current_language"]]->filename ); //include current language file
    else{
        die( "<font color=red><b>ERROR: Couldn't find language file!</b></font>" );
    }

?>