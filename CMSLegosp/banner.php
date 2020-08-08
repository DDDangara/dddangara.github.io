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
 

	//installation routine
	
	//ini_set("display_errors", "1");

	include("./cfg/connect.inc.php");
	include("./includes/database/mysql.php");
	include("./cfg/general.inc.php");
	include("./cfg/functions.php");

//подключение к базе

	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());
	db_query('SET NAMES '.DB_CHARSET);


function win2uni($s) 
  	{ 
    	$s = convert_cyr_string($s,'w','i'); // преобразование win1251 -> iso8859-5 
	    // преобразование iso8859-5 -> unicode: 
	for ($result='', $i=0; $i<strlen($s); $i++)
		{ 
		$charcode = ord($s[$i]); 
		$result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i]; 
		} 
	return $result; 
  	}

function split_rows($string, $length)
	{
	$row = explode(" ", $string);
	$res = array();

	$res[0] = $row[0];

	$j = 0;
	for ($i = 1; $i <= count($row); $i++)
		{
		if ((strlen($res[$j])+strlen($row[$i])) < $length) {$res[$j] .= " ".$row[$i];} else { $j++; $res[$j] .= "\n".$row[$i];}
		}

	$i = 0;
	while ($res[$i])
		{
		$result .= $res[$i];
		$i++;
		}
	return $result;
	}


function create_banner($width, $height, $rgb_bg, $rgb_f, $price, $rgb_pr, $product)
	{

	$text = win2uni(split_rows($product[1], 16));					//название продукта
	$price = win2uni(show_price($product[3]));					//цена
	//$priduct_pic = "http://".CONF_SHOP_URL."/products_pictures/".$product[2];	//картинка

	$priduct_pic = "./products_pictures/".$product[2];				//картинка
	$size = getimagesize($priduct_pic);

	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	$icfunc = "imagecreatefrom" . $format;
	$isrc = $icfunc($priduct_pic);

	$rgb_bg = hex2rgb("#".$rgb_bg);							//цвет фона
	$rgb_f = hex2rgb("#".$rgb_f);							//цвет шрифта
	$rgb_pr = hex2rgb("#".$rgb_pr);							//цвет цены

	$idest = imagecreatetruecolor($width, $height);					//создаём ресурс с пустым изображением

	$rgb_bg = imagecolorallocate($idest, $rgb_bg[0], $rgb_bg[1], $rgb_bg[2]); 	//регистрируем цвет фона
	$rgb_f = imagecolorallocate($idest, $rgb_f[0], $rgb_f[1], $rgb_f[2]); 		//регистрируем цвет шрифта
	$rgb_pr = imagecolorallocate($idest, $rgb_pr[0], $rgb_pr[1], $rgb_pr[2]); 	//регистрируем цвет шрифта цены

	imagefill($idest, 0, 0, $rgb_bg);						//заливаем фон

	$font = "./images/banner.ttf";							//загружаем шрифт
	$text_size = imagettftext($idest, 10, 0, 0, 0, $rgb_bg, $font, $text); 		//рисуем текст c цветом фона, чтобы получить размер
	$price_size = imagettftext($idest, 10, 0, 0, 0, $rgb_bg, $font, $price);	//рисуем цену c цветом фона, чтобы получить размер

	imagettftext($idest, 10, 0, 10, $height-$text_size[1]-30-$price_size[1], $rgb_f, $font, $text); 	//теперь пишем текст по центру

	imagettftext($idest, 10, 0, ceil(($width/2)-($price_size[2]/2)), $height-10-$price_size[1], $rgb_pr, $font, $price); 	//теперь пишем текст по центру


	imagecopyresampled($idest, $isrc, 10, 10, 0, 0, $width-20, $height-$text_size[1]-30-$price_size[1]-30, $size[0], $size[1]);

//	imagettftext($idest, 10, 0, ceil(($width/2)-($text_size[2]/2)), ceil(($height/2)-($text_size[1]/2)), $rgb_f, $font, $text); //теперь пишем текст по центру

// $img_size[0]." ".$img_size[1]." ".$img_size[2]." ".$img_size[3]." ".$img_size[4]." ".$img_size[5]." ".$img_size[6]." ".$img_size[7];

	header("Content-type: image/png");
	imagepng($idest);

	imagedestroy($idest);
	}

    if (isset($_POST["banner_x"])) 	{$banner_x = $_POST["banner_x"];} 	else {$banner_x = 150;}
    if (isset($_POST["banner_y"])) 	{$banner_y = $_POST["banner_y"];} 	else {$banner_y = 200;}
    if (isset($_POST["banner_bg"])) 	{$banner_bg = $_POST["banner_bg"];}  	else {$banner_bg = "FFFFFF";}
    if (isset($_POST["banner_f"])) 	{$banner_f = $_POST["banner_f"];}	else {$banner_f = "000000";}
    if (isset($_POST["banner_pr_f"])) 	{$banner_pr_f = $_POST["banner_pr_f"];}	else {$banner_pr_f = "FF0000";}

    if (!isset($_POST["banner_pr"])) $_POST["banner_pr"] = 1;
    if (!strcmp($_POST["banner_pr"], "on")) $_POST["banner_pr"] = 1;
	else $_POST["banner_pr"] = 0;

    if (isset($_GET["banner"])) //формируем рисунок если пришли по ссылке
	{
	if (isset($_GET["x"])) 	  {$banner_x = $_GET["x"];} 	  else {$banner_x = 150;}
	if (isset($_GET["y"])) 	  {$banner_y = $_GET["y"];} 	  else {$banner_y = 200;}
	if (isset($_GET["bg"]))   {$banner_bg = $_GET["bg"];}  	  else {$banner_bg = "FFFFFF";}
	if (isset($_GET["f"]))	  {$banner_f = $_GET["f"];}	  else {$banner_f = "000000";}
	if (isset($_GET["pr_f"])) {$banner_pr_f = $_GET["pr_f"];} else {$banner_pr_f = "FF0000";}

	$q = db_query("SELECT ".PRODUCTS_TABLE.".productID, name, picture, Price, hurl FROM ".PRODUCTS_TABLE." JOIN ".SPECIAL_OFFERS_TABLE." USING (productID) where ".PRODUCTS_TABLE.".enabled = '1'") or die (db_error());	
	while ($row = db_fetch_row($q))	$arr[] = $row;
	shuffle($arr);

	create_banner($banner_x, $banner_y, $banner_bg, $banner_f, $banner_pr, $banner_pr_f, $arr[0]);
	}

   if (isset($_POST["get_banner"]) && $_POST["get_banner"]) 
	{
	  $banner_img = 1; //показываем результат на странице настроек
	  $banner_code = "<!-- ".CONF_SHOP_NAME." banner -->
<a href=\"http://".CONF_SHOP_URL."\"><img src=\"http://".CONF_SHOP_URL."/banner.php?banner&amp;x=$banner_x&amp;y=$banner_y&amp;bg=$banner_bg&amp;f=$banner_f&amp;pr=$banner_pr&amp;pr_f=$banner_pr_f\" alt=\"".CONF_SHOP_NAME."\" /></a>
<!-- /".CONF_SHOP_NAME." banner -->";

	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Установите баннер LEGOSP</title>
<link rel="stylesheet" type="text/css" href="images/backend/install.css" />
</head>

<body>
<div id="container">
  <div id="header"><a href="./"><img src="./css/css_default/image/logo0000.png" alt="LEGOSP" title="LEGOSP" /></a></div>
  <div id="content">
    <div id="content_top"></div>
    <div id="content_middle">
      <h1>Установите наш баннер</h1>
      <div style="width: 100%; display: inline-block;">
	  <form action="banner.php" name="form" method="post" enctype="multipart/form-data" id="form" >

	    <p>1. Параметры</p>
	    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
	      <table>
		<tr>
		  <td style="width: 185px;">Размер:</td>
		  <td><input type="text" name="banner_x" value="<?php echo $banner_x; ?>" style="width: 50px" /> x <input type="text" name="banner_y" value="<?php echo $banner_y; ?>" style="width: 50px" /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Цвет фона: <b>#</b></td>
		  <td><input type="text" name="banner_bg" value="<?php echo $banner_bg; ?>" style="width: 119px" /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Цвет текста: <b>#</b></td>
		  <td><input type="text" name="banner_f" value="<?php echo $banner_f; ?>" style="width: 119px" /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Цвет цены: <b>#</b></td>
		  <td><input type="text" name="banner_pr_f" value="<?php echo $banner_pr_f; ?>" style="width: 119px" /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
<?php
/*
		  <td style="width: 185px; text-align: right;">Показывать цену: </td>
		  <td><input type="checkbox" name="banner_pr" <?php if ($banner_pr == 1) {echo "checked=\"checked\"";} ?> /></td>
*/
?>
		</tr>
	      </table>
	    </div>
	    <p>2. Код баннера</p>
	    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
	      <table style="width: 100%">
		<tr>
		  <td>
		    <textarea name="banner_code" cols="40" rows="10" style="height: <?php if ($banner_y > 200) {echo $banner_y."px";} else {echo "200px";}?>; width: 100%">
<?php
echo $banner_code;
?>
		    </textarea>
		  </td>
		  <td width="1px"><?php if ($banner_img) { ?><div style="padding-left: 10px"><a href="<?php echo "http://".CONF_SHOP_URL; ?>"><img src="<?php echo "banner.php?banner&amp;x=$banner_x&amp;y=$banner_y&amp;bg=$banner_bg&amp;f=$banner_f&amp;pr=$banner_pr&amp;pr_f=$banner_pr_f"; ?>" alt="" /></a></div><?php } ?></td>
		</tr>
	      </table>
	    </div>
	    <input type="hidden" name="get_banner" value="true" />
	    <div style="text-align: right;"><a onclick="javascript: document.getElementById('form').submit()" class="button"><span class="button_left button_continue"></span><span class="button_middle">Получить код</span><span class="button_right"></span></a></div>
	  </form>
      </div>
    </div>
    <div id="content_bottom"></div>
  </div>
  <div id="footer"><a onclick="window.open('http://legosp.net');">Project Homepage</a>|<a onclick="window.open('http://legosp.net/overview/');">Documentation</a>|<a onclick="window.open('http://forum.legosp.net/');">Support Forums</a></div>
</div>
</body>
</html>

