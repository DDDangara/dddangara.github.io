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
 
define('ROOT_DIR',realpath('../'));
$water_mark_dir=ROOT_DIR.'/water_mark';

$products_pictures=ROOT_DIR.'/'.$_GET['dir'].'/';
if (!preg_match("/^[a-zA-Z0-9_\.\-]+$/",$_GET['img_path']))
die('no corect name');
$imgpath = $products_pictures.$_GET['img_path'];
if (!file_exists($imgpath))
 die('not image');
list($width_orig, $height_orig, $type) = getimagesize($imgpath);
//list($width_water, $height_water, $type_water) = getimagesize($wp);


 if ($width_orig < $height_orig)
 {
  $width_water_approx = ceil($width_orig / 3);
  //$height_water_approx = ceil($height_water * ($width_water_approx / $width_water));
  $flag = 'width';
 }
 else
 {
  $height_water_approx = ceil($height_orig / 3);
  //$height_water_n = ceil($height_water * ($width_water_n / $width_water));
  $flag = 'height';
 }
 $mark_list = array();

 
 $files = array();
 $dh  = opendir($water_mark_dir.'/');
 while (false !== ($filename = readdir($dh)))
 {
  if(is_file($water_mark_dir.'/'.$filename))
   $files[] = $filename;
  
 }
 
 #print_r($files); exit;   

 foreach($files as $f)
 {
  $wptemp = $water_mark_dir.'/'.$f;
  list($mark_list[$f]['width'], $mark_list[$f]['height'], $mark_list[$f]['type']) = getimagesize($wptemp);
 }

 $diff = array();

 $iter = 0;
 if($flag == 'width')
 {
  //echo '<br /><br />'.$flag.'<br /><br />';
  foreach($mark_list as $key=>$mark)
  {
   $diff[$key] = abs($width_water_approx - $mark['width']);
   if($iter > 0)
   {
   	if($diff[$key] < $d)
   	{
   	 $d = $diff[$key];
   	 $name = $key;
   	}
   }
   else
   {
    $name = $key;
    $d = $mark['width'];
   }
   $iter ++;
  }
  
  $wp = $water_mark_dir.'/'.$name;
  list($width_water, $height_water, $type_water) = getimagesize($wp);
 }
 else//if($flag == 'height')
 {
  //echo '<br /><br />'.$flag.'<br /><br />';
 
  foreach($mark_list as $key=>$mark)
  {
   $diff[$key] = abs($height_water_approx - $mark['height']);
   if($iter > 0)
   {
   	if($diff[$key] < $d)
   	{
   	 $d = $diff[$key];
   	 $name = $key;
   	}
   }
   else
   {
    $name = $key;
    $d = $mark['height'];
   }
   $iter ++;
  }
  $wp = $water_mark_dir.'/'.$name;
  list($width_water, $height_water, $type_water) = getimagesize($wp);
  #echo $name; exit;
 }
if ($type == 2)
 @$image = imagecreatefromjpeg($imgpath);
elseif ($type == 1)
 @$image = imagecreatefromgif($imgpath);
elseif ($type == 3)
 @$image = imagecreatefrompng($imgpath);
else
 die;

 @$wm = imagecreatefrompng($wp);

@imagesavealpha($wm, true);
/*
@ $wm_n = imagecreatetruecolor($width_water, $height_water);

@$i = ImageCreateFromPng($products_pictures.'transparentimage.png');
@ imagesavealpha($i, true);


@ imagecopyresampled($wm_n, $i, 0, 0, 0, 0, $width_water, $height_water, 1, 1);
@ imagesavealpha($wm_n, true);
@ imagecopyresampled($wm_n, $wm, 0, 0, 0, 0, $width_water, $height_water, $width_water, $height_water);
*/
imagecopy($image, $wm, (ceil($width_orig/2)-ceil($width_water/2)), (ceil($height_orig/2)-ceil($height_water/2)), 0, 0, $width_water, $height_water);
@ imagesavealpha($image, true);
Header('Content-type: image/png');
ImagePng($image);
ImageDestroy($image);
?>