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
 
    //фаил перекодирован в utf-8

    class Dbconector{
        public $connectLink;
        private static $_instance = null;

        private function __construct(){
            // приватный конструктор ограничивает реализацию getInstance ()
        }

        protected function __clone(){
            // ограничивает клонирование объекта
        }

        public function getConnect(){
            return $this->connectLink;
        }

        public function setConnect( $connectLink ){
            $this->connectLink = $connectLink;
        }

        static public function getInstance(){
            if( is_null( self::$_instance ) ){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
    }

    //	database functions :: MySQL

    function add_in( $fields ){
        $set = '';
        foreach( $fields as $field => $value ){
            $set .= $value . ',';
        }
        $set = substr( $set, 0, strlen( $set ) - 1 );
        return $set;
    }

    function getDbCollation( $db ){
        return db_r( "SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '" . $db . "'" );
    }

    function db_connect( $host, $user, $pass, $db = DB_NAME ) //create connection
    {
        #echo $host."<br>".$user."<br>".$pass."<br>".$db;
        $hostData = array();
        $hostData = explode( ':', $host );
        $dbConnector = Dbconector::getInstance();
        if( count( $hostData ) > 1 ){
            /**
             * object db connector to store mysqli_connect link
             */
            $r = mysqli_connect( $hostData[0], $user, $pass, $db, 0, $hostData[1] );
        } else{

            $r = mysqli_connect( $host, $user, $pass, $db );
        }
        mysqli_query( $r, "SET NAMES UTF8");
        $dbConnector->setConnect( $r );

        if( preg_match( '/^5\./', mysqli_get_server_info( $r ) ) )
            db_query( 'SET SESSION sql_mode=0' );
        return $r;
    }



    function db_select_db( $name ) //select database
    {
        //pre(Dbconector::getInstance()->getConnect());
        $rez = mysqli_select_db( Dbconector::getInstance()->getConnect(), $name ) or die( 'ss' );

        db_query( "SET NAMES " . DB_CHARSET );

        return $rez;
    }


    function db_set_charset(){
        mysqli_query( Dbconector::getInstance()->getConnect(), 'SET NAMES ' . DB_CHARSET );
    }


    function db_query( $s ) //database query
    {
        GLOBAL $esql;
        if( $esql )
            echo "<p>" . $s . "</p>";
        return mysqli_query( Dbconector::getInstance()->getConnect(), $s );
    }

    function db_fetch_row( $q ) //row fetching
    {
        return mysqli_fetch_row( $q );
    }

    function db_insert_id(){
        return mysqli_insert_id( Dbconector::getInstance()->getConnect() );
    }

    function db_error() //database error message
    {
        return mysqli_error( Dbconector::getInstance()->getConnect() );
    }

    function db_assoc( $s ){
        $result = db_query( $s );
        if( $result )
            return mysqli_fetch_assoc( $result ); else return false;
    }

    function db_assoc_q( $q ){
        if( $q )
            return mysqli_fetch_assoc( $q ); else return false;
    }

    function db_affected_rows(){
        return mysqli_affected_rows( Dbconector::getInstance()->getConnect() );
    }

    function db_arAll( $s ){
        // выгребает все значения из выборки в один ассоциативный массив
        $result = db_query( $s );

        $all_data = array();
        if( $result )
            while( $rg = @mysqli_fetch_array( $result, MYSQLI_ASSOC ) ){
                array_push( $all_data, $rg );

            }
        #mysql_free_result($result);
        return $all_data;
    }

    function db_r( $s ){
        $result = db_query( $s );
        if( !$result || mysqli_num_rows( $result ) == 0 ){
            return '';
        } else{
            /**
             * set offset to first row
             */
            mysqli_data_seek( $result, 0 );
            /**
             * fetch first row
             */
            $row = mysqli_fetch_row( $result );
            return $row[0];
        }
    }

    function isTextValue( $field_type ){
        switch( $field_type ){
            case "tinytext":
            case "text":
            case "mediumtext":
            case "longtext":
            case "binary":
            case "varbinary":
            case "tinyblob":
            case "blob":
            case "mediumblob":
            case "longblob":
                return True;
                break;
            default:
                return False;
        }
    }


    function db_num_rows( $q ){
        if( $q )
            return mysqli_num_rows( $q ); else return 0;
    }

    function db_num_fields( $q ){
        if( $q )
            return mysqli_num_fields( $q ); else return 0;
    }

    function int_text( $val ){

        if( get_magic_quotes_gpc() )
            $val = stripslashes( $val );
        $val = "'" . mysqli_real_escape_string( Dbconector::getInstance()->getConnect(), $val ) . "'";
        return $val;
    }

    function add_set( $fields ){

        $set = '';
        foreach( $fields as $field => $value ){


            #echo $value; echo '=';
            if( get_magic_quotes_gpc() )
                $value = stripslashes( $value );
            if( $value !== 'NULL' )
                $value = "'" . mysqli_real_escape_string( Dbconector::getInstance()->getConnect(), $value ) . "'";
            #echo $value."<br>";
            if( DB_CHARSET != 'cp1251' && !is_utf8( $value ) ){
                $value = win2utf( $value );
            }
            $set .= "`" . $field . "`=" . $value . ',';
        }
        $set = substr( $set, 0, strlen( $set ) - 1 );
        #exit;
        return $set;

    }


    function add_field( $table, $fields ){

        return db_query( "INSERT INTO `" . $table . "` SET " . add_set( $fields ) );

    }

    function update_field( $table, $fields, $where = "1>0" ){

        return $q = db_query( "UPDATE `" . $table . "` SET " . add_set( $fields ) . " WHERE " . $where );

    }

    function validate_form_string( $inp ){
        if( is_array( $inp ) )
            return array_map( __METHOD__, $inp );

        if( !empty( $inp ) && is_string( $inp ) ){
            return str_replace( array( '\\', "\0", "\n", "\r", "'", '"', "\x1a" ), array( '\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z' ), $inp );
        }
    }

    function db_create( $db, $charset = 'UTF8' ){

        $general = $charset . '_general_ci';
        return db_query( 'CREATE DATABASE $db CHARACTER SET ' . $charset . ' COLLATE ' . $general );
    }

    //фаил перекодирован в utf-8
    function db_field_name( $records, $j ){

        return mysqli_fetch_field_direct( $records, $j )->name;
    }