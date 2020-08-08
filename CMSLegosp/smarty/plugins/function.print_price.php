<?php
/**
 * Created by PhpStorm.
 * User: legosp.net
 * Date: 21.01.15
 * Time: 18:18
 */
    function smarty_function_print_price( $price, &$smarty ){
        if( fract( $price ) )
            $price = number_format( $price, 2, ',', ' ' ); else $price = number_format( $price, 0, '', ' ' );
        return $price;

    }
