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
 
if (isset($_POST['captcha']))
{
 include_once("../cfg/ajax_connect.inc.php"); 
 if ($_POST['captcha']!=$_SESSION["captcha"] && !isset($_SESSION['cust_id']))
 {
     $text_error="<label class='error'>Неверно введен Защитный код.</label>";
     if (DB_CHARSET!='cp1251') $text_error=win2utf($text_error);    
     echo $text_error;
 }
 else
 {
    echo '1';
 }
 exit;
}
else
{
session_start();


function hex2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

$count=5;		/* количество символов */
$width=120; 		/* ширина картинки */
$height=40; 		/* высота картинки */
$font_size_min=19; 	/* минимальная высота символа */
$font_size_max=29; 	/* максимальная высота символа */
$font_file="../images/Comic_Sans_MS.ttf"; /* путь к файлу относительно w3captcha.php */
$char_angle_min=-25; 	/* максимальный наклон символа влево */
$char_angle_max=23;	/* максимальный наклон символа вправо */
$char_angle_shadow=6;	/* размер тени */
$char_align=30;		/* выравнивание символа по-вертикали */
$start=3;		/* позиция первого символа по-горизонтали */
$interval=20;		/* интервал между началами символов */
$chars="0123456789abcdefgiwspvmxnozqrtyulj"; 	/* набор символов */
$noise=7; 		/* уровень шума */
$colors = array("90","110","130","150","170","190","210"); 

if(!function_exists('imagetypes')) {echo 'GD is not loaded'; exit;}
if(!file_exists($font_file)) {echo 'font not faund'; exit;}
 


$image=imagecreatetruecolor($width, $height);

if (isset($_GET["bg"]))  	/* rbg-цвет фона */
	{
	$bgclr = hex2rgb($_GET["bg"]);
	$background_color=imagecolorallocate($image, $bgclr[0], $bgclr[1], $bgclr[2]);
	}
else
	{
	$background_color=imagecolorallocate($image, 255, 255, 255);
	}

$font_color=imagecolorallocate($image, 0, 0, 0); 		/* rbg-цвет тени */

imagefill($image, 0, 0, $background_color);

$str="";

$num_chars=strlen($chars);
for ($i=0; $i<$count; $i++)
{
	$char=$chars[rand(0, $num_chars-1)];
	$font_size=rand($font_size_min, $font_size_max);
	$char_angle=rand($char_angle_min, $char_angle_max);
        $fcolor = imagecolorallocatealpha($image,rand(0,255),rand(0,255),rand(0,255),rand(20,40));
	imagettftext($image, $font_size, $char_angle, $start, $char_align, $fcolor, $font_file, $char);
        $fbcolor = imagecolorallocatealpha($image,rand(0,255),rand(0,255),rand(0,255),rand(1,80));
	imagettftext($image, $font_size, $char_angle+$char_angle_shadow*(rand(0, 1)*2-1), $start, $char_align, $fbcolor, $font_file, $char);
	$start+=$interval;
	$str.=$char;
}

if ($noise)
{
	for ($i=0; $i<$width; $i++)
	{
		for ($j=0; $j<$height; $j++)
		{
			$rgb=imagecolorat($image, $i, $j);
			$r=($rgb>>16) & 0xFF;
			$g=($rgb>>8) & 0xFF;
			$b=$rgb & 0xFF;
			$k=rand(-$noise, $noise);
			$rn=$r+255*$k/100;
			$gn=$g+255*$k/100;		
			$bn=$b+255*$k/100;
			if ($rn<0) $rn=0;
			if ($gn<0) $gn=0;
			if ($bn<0) $bn=0;
			if ($rn>255) $rn=255;
			if ($gn>255) $gn=255;
			if ($bn>255) $bn=255;
			$color=imagecolorallocate($image, $rn, $gn, $bn);
			imagesetpixel($image, $i, $j , $color);					
		}
	}
}

$_SESSION["captcha"]=$str;
if (function_exists("imagepng"))
{
	header("Content-type: image/png");
	imagepng($image);
}
elseif (function_exists("imagegif"))
{
	header("Content-type: image/gif");
	imagegif($image);
}
elseif (function_exists("imagejpeg"))
{
	header("Content-type: image/jpeg");
	imagejpeg($image);
}
imagedestroy($image);
}
?>
