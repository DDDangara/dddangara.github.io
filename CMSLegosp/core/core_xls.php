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
 
    include( "../cfg/connect.inc.php" );
    include( "../includes/database/mysql.php" );
    include( "../cfg/general.inc.php" );
    include( "../cfg/company.inc.php" );
    include( "../cfg/appearence.inc.php" );
    include( "../cfg/functions.php" );
    include( "../cfg/redirect.inc.php" );
    include( "../cfg/product.inc.php" );

    require_once './excel/Classes/PHPExcel.php';
    require_once './excel/Classes/PHPExcel/RichText.php';
    require_once './excel/Classes/PHPExcel/IOFactory.php';


    $objPHPExcel = new PHPExcel();


    db_connect( DB_HOST, DB_USER, DB_PASS ) or die ( db_error() );
    db_select_db( DB_NAME ) or die ( db_error() );

    currency();
    $pricelist_elements = pricessCategories( 0, 0 );
    $objPHPExcel->setActiveSheetIndex( 0 );
    $objPHPExcel->getActiveSheet()->mergeCells( 'A1:B4' );
    $logo = '../css/css_' . CONF_COLOR_SCHEME . '/image/logo0000.png';
    if( is_readable( $logo ) ){
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName( 'Logo' );
        $objDrawing->setDescription( 'Logo' );
        $objDrawing->setPath( '../css/css_' . CONF_COLOR_SCHEME . '/image/logo0000.png' );
        $objDrawing->setCoordinates( 'A1' );
        $objDrawing->setOffsetX( 110 );
        $objDrawing->setRotation( 25 );
        $objDrawing->setWorksheet( $objPHPExcel->getActiveSheet() );
    }

    $head = array( 'font' => array( 'bold' => true, ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, ), 'borders' => array( 'top' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array( 'rgb' => '9A9A9A' ) ), 'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array( 'rgb' => '9A9A9A' ) ) )

    );


    $styleArray = array( 'font' => array( 'name' => 'Arial', 'size' => '10', 'color' => array( 'rgb' => 'FFFFFF' ), 'bold' => true ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP )

    );

    $subcategory = array( 'font' => array( 'name' => 'Arial', 'size' => '10', 'color' => array( 'rgb' => '3f27b3' ), 'bold' => true )

    );


    $category = array( 'font' => array( 'name' => 'Arial', 'size' => '12', 'color' => array( 'rgb' => '2d2842' ), 'bold' => true )

    );

    $text = array( 'font' => array( 'name' => 'Arial', 'size' => '8', 'bold' => true ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP )

    );
    $textr = array( 'font' => array( 'name' => 'Arial', 'size' => '8', 'bold' => true ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
    $heading = array( 'font' => array( 'name' => 'Arial', 'size' => '12', 'bold' => true ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );

    $heading2 = array( 'font' => array( 'name' => 'Arial', 'size' => '10', 'bold' => true ), 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );


    $objPHPExcel->getActiveSheet()->setCellValue( 'D1', win2utf( CONF_SHOP_NAME ) );
    $objPHPExcel->getActiveSheet()->getStyle( 'D1' )->applyFromArray( $heading );

    $objPHPExcel->getActiveSheet()->setCellValue( 'D3', win2utf( COMPANY_ADDRESS ) );
    $objPHPExcel->getActiveSheet()->getStyle( 'D3' )->applyFromArray( $heading2 );

    $objPHPExcel->getActiveSheet()->setCellValue( 'C4', win2utf( "Тел./факс: " . COMPANY_PHONE ) );
    $objPHPExcel->getActiveSheet()->getStyle( 'D4' )->applyFromArray( $heading2 );

    $objPHPExcel->getActiveSheet()->setCellValue( 'D5', win2utf( "E-Mail: " . CONF_GENERAL_EMAIL ) );
    $objPHPExcel->getActiveSheet()->getStyle( 'D5' )->applyFromArray( $heading2 );


    $objPHPExcel->getActiveSheet()->getStyle( 'A1:D5' )->getFill()->getStartColor()->setRGB( 'FFFFFF' );
    #$objPHPExcel->getActiveSheet()->mergeCells('A1:D5');


    $objPHPExcel->getActiveSheet()->getStyle( 'A6:D6' )->applyFromArray( $styleArray );
    $objPHPExcel->getActiveSheet()->getStyle( 'A6:D6' )->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID );
    $objPHPExcel->getActiveSheet()->getStyle( 'A6:D6' )->getFill()->getStartColor()->setRGB( '56' );

    $objPHPExcel->getActiveSheet()->setCellValue( 'A6', win2utf( "Код продукта" ) );
    $objPHPExcel->getActiveSheet()->setCellValue( 'B6', win2utf( "Наименование" ) );
    $objPHPExcel->getActiveSheet()->setCellValue( 'C6', win2utf( "Наличие" ) );
    $objPHPExcel->getActiveSheet()->setCellValue( 'D6', win2utf( "Цена" ) );
    /*$worksheet->setColumn(0, 0, 13);
$worksheet->setColumn(1, 1, 60);
$worksheet->setColumn(2, 2, 9);
$worksheet->setColumn(3, 3, 11);
$worksheet->setColumn(4, 4, 11);*/


    $objPHPExcel->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 13 );
    $objPHPExcel->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 60 );
    $objPHPExcel->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 9 );
    $objPHPExcel->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 11 );

    $objPHPExcel->getActiveSheet()->freezePane( 'A7' );

    foreach( $pricelist_elements as $key => $pricelist_element ){

        $line = 'A' . ( $key + 7 ) . ':D' . ( $key + 7 );
        $pricelist_element[1] = win2utf( "$pricelist_element[1]" );
        if( ( $pricelist_element[2] == 0 ) && ( $pricelist_element[4] == 0 ) ){
            $objPHPExcel->getActiveSheet()->getStyle( $line )->applyFromArray( $category );
            $objPHPExcel->getActiveSheet()->getStyle( $line )->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID );
            $objPHPExcel->getActiveSheet()->getStyle( $line )->getFill()->getStartColor()->setRGB( '969696' );
        } elseif( ( $pricelist_element[2] > 0 ) && ( $pricelist_element[4] == 0 ) ){
            $objPHPExcel->getActiveSheet()->getStyle( $line )->applyFromArray( $subcategory );
            $objPHPExcel->getActiveSheet()->getStyle( $line )->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID );
            $objPHPExcel->getActiveSheet()->getStyle( $line )->getFill()->getStartColor()->setRGB( 'DDDDDD' );
        } else{
            $objPHPExcel->getActiveSheet()->getStyle( 'C' . ( $key + 7 ) )->applyFromArray( $text );
            $objPHPExcel->getActiveSheet()->getStyle( 'D' . ( $key + 7 ) )->applyFromArray( $textr );
            $objPHPExcel->getActiveSheet()->setCellValue( 'A' . ( $key + 7 ), win2utf( $pricelist_element[8] ) );
            $objPHPExcel->getActiveSheet()->setCellValue( 'C' . ( $key + 7 ), $pricelist_element[7] );
            $objPHPExcel->getActiveSheet()->setCellValue( 'D' . ( $key + 7 ), win2utf( $pricelist_element[5] ) );
        }

        $objPHPExcel->getActiveSheet()->setCellValue( 'B' . ( $key + 7 ), $pricelist_element[1] );


    }

    header( "Content-type: application/vnd.ms-excel" );
    header( 'Content-Disposition: attachment;filename="price_' . date( "dmy" ) . '.xls"' );
    header( 'Cache-Control: max-age=0' );
    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
    $objWriter->setTempDir( dirname( __FILE__ ) . '/cache' );
    $objWriter->save( 'php://output' );




?>