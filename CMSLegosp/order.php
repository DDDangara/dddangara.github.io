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
 
include_once ("./cfg/connect.inc.php");
include_once ("./includes/database/mysql.php");
include_once ("./cfg/general.inc.php");
include_once ("./cfg/functions.php");
db_connect(DB_HOST, DB_USER, DB_PASS) or die(db_error());
db_select_db(DB_NAME) or die(db_error());
mysql_query('SET NAMES ' . DB_CHARSET);
currency();

  $fp = fopen ('post.txt', "w");
  foreach ($_POST as $key => $output)
  {
    fwrite($fp, $key.'=>'.$output."\r\n");
  }
  

if (isset($_POST['authorization']))
{
  $imei=$_POST['authorization'];
  unset($_POST['authorization']);
  $text="IMEI=".$imei."\r\n";
  foreach ($_POST as $login => $pass)
  {
    #$text.="Login=".$login."\r\n";
    #$text.="Pass=".$pass."\r\n";
  }
  $rezult='NOT';
  $sql='select count(*) from '.MANAGER_TABLE.' where manager=\''.$login.'\' and md5(password)=\''.$pass."' and  (`IMEI`=".$imei.' or `IMEI`=0)';
  $r=db_r($sql);
  
  
  if ((int)$r>0)
   $rezult='ok';
  
  fclose($fp); 
  die($rezult);
  
}
fclose($fp);

if (isset($_POST['login']) && trim($_POST['login']))
{
 if (!isset($_POST['ordnumber']))
 { 
  $name=db_r('select online_name from '.MANAGER_TABLE.' where manager='.int_text($_POST['login'])); 
  unset($_POST['login']);
  $order_info=array();
  $order_info['order_time']=get_current_time();
  $order_info['cust_firstname']=$name;
  /*$order_info['cust_lastname']=$_POST["last_name"];
  $order_info['cust_email']=$post_email;
  $order_info['cust_country']=$_POST["country"];
  $order_info['cust_zip']=$_POST["zip"];
  $order_info['cust_state']=$_POST["state"];
  $order_info['cust_city']=$_POST["city"];
  $order_info['cust_address']=$_POST["address"];
  $order_info['cust_phone']=$post_phone;
  $order_info['comment']=$_POST["comment"];
  $order_info['manager']=$man_arr[0];*/
  add_field(ORDERS_TABLE, $order_info); unset($order_info);
  echo $oid = db_insert_id();
 }
 else
 {
   echo $oid = (int)$_POST['ordnumber'];
   unset($_POST['ordnumber']);
 }
  
 #$fp = fopen ('post.txt', "w");
 
  foreach ($_POST as $key => $output)
  {
    $v=db_assoc("select name,Price from ".PRODUCTS_TABLE." where productID=".$key);
    $order_insert=array();  
    $order_insert['orderID']=$oid;
    $order_insert['productID']=$key; 
    $order_insert['name']=$v['name'];
    $order_insert['Price']=$v['Price']; 
    $order_insert['Quantity']=$output; 
    add_field(ORDERED_CARTS_TABLE, $order_insert);
    #fwrite($fp, $key.'=>'.$output."\r\n");
  }
}  
  #fwrite($fp, $text);
  #fclose($fp);
?>