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
 
if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}
if (!strcmp($sub, "users"))
{
   
   if (isset($_GET['complite']))
   { 
      db_query('UPDATE `'.CUST_TABLE."` SET `grup` = '1' WHERE `custID` =".(int)$_GET['user']);
      header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=users");
      exit;
   }
   if (isset($_GET['delete']))
   {

     db_query('DELETE FROM `'.CUST_TABLE.'` WHERE `custID` = '.(int)$_GET['delete']);
     header("Location: " . CONF_ADMIN_FILE . "?dpt=custord&sub=users");
     exit;
   } 
   if (isset($_POST['userinfo']))
   { 
      if (isset($_POST['chpass']))
      { 
        $newpassword=generate_password(8); 
        $_POST['userinfo']['cust_password']=md5($newpassword);
       
        $SHOP_NAME=CONF_SHOP_NAME;
        $NOTIFICATION_SUBJECT='Новый пароль';
        $html_body='Пароль для входа в магазин <a href=\'http://'.CONF_SHOP_URL.'/\'>'.$SHOP_NAME.'</a> изменен <br> Новый пароль:'.$newpassword;

        $last_name=$_POST['userinfo']["cust_lastname"]; 
        $first_name=$_POST['userinfo']["cust_firstname"]; 
        if (DEFAULT_CHARSET=='utf-8') {
            $last_name=win2utf($last_name); 
            $first_name=win2utf($first_name);   
            $SHOP_NAME = win2utf($SHOP_NAME); 
            $NOTIFICATION_SUBJECT=win2utf($NOTIFICATION_SUBJECT);
            $html_body = win2utf($html_body);     
        }  
        
        $to['mail']= $_POST['userinfo']['cust_email'];
        $to['name']=$first_name." ".$last_name;

        $from['mail']=CONF_GENERAL_EMAIL;
        $from['name']=$SHOP_NAME;   
        phpmailer ($to, $from, $NOTIFICATION_SUBJECT,'', $html_body); 
        
      } 
   
      update_field(CUST_TABLE,$_POST['userinfo'],'`custID`='.intval($_POST['custID']));
      
      header("Location: admin.php?dpt=custord&sub=users");
      exit;
   }


   if (isset($_GET['eu_id']))
   {
      $userinfo=db_assoc('select * from '.CUST_TABLE.' where `custID`='.(int)$_GET['eu_id']);
      $smarty->assign("userinfo", $userinfo);
      $smarty->assign("admin_sub_dpt", "custord_user_edit.tpl.html");
   }
   else
   {
     $users=db_arAll('select * from '.CUST_TABLE);
     $smarty->assign("users", $users);
     $smarty->assign("admin_sub_dpt", "custord_users.tpl.html");
   }

 
}
?>