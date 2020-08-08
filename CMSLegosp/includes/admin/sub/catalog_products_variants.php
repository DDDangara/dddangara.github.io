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
 
	//products and categories tree view

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}
if (!strcmp($sub, "products_variants"))
{
   
   
    if (isset($_GET['del']))
    { 
      
      db_query("delete from ".PRODUCT_OPTIONS_TABLE." where  optionID=".$_GET['del']);
      header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=products_variants");
    } 
    elseif (isset($_POST['variant_edit']) || isset($_POST['variant_var_edit']))
    {
      
      if (isset($_POST['variant_edit'])) 
      foreach ($_POST['variant'] as $key =>$value)
      { 
         db_query("update ".PRODUCT_OPTIONS_TABLE."  SET `name`= '".$value['name']."',`sort_order` = '".$value['sort_order']."' WHERE `optionID`=".$key );
      }
      else 
      foreach ($_POST['variant'] as $key =>$value)
      { 
         db_query("update ".PRODUCT_OPTIONS_VAL_TABLE."  SET `name`= '".$value['name']."',`sort_order` = '".$value['sort_order']."' WHERE `variantID`=".$key );
      }  
      unset($_POST['variant_edit'],$_POST['variant'],$_POST['variant_var_edit']);
    } 
   
    elseif (isset($_POST['add_variant']) && isset($_POST['edit']))
    {
      $_POST['variant']['optionID']=(int)$_POST['edit'];  
      add_field(PRODUCT_OPTIONS_VAL_TABLE, $_POST['variant']);
      header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=products_variants&edit=".$_POST['variant']['optionID']);  
    }
    elseif (isset($_POST['add_variant']))
    {
      add_field(PRODUCT_OPTIONS_TABLE, $_POST['variant']);
      unset($_POST); 
      header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=products_variants&edit=".db_insert_id());            
 
    }  
    elseif (isset($_GET['del_val'])) 
    {  
       db_query("delete from ".PRODUCT_OPTIONS_VAL_TABLE." where  variantID=".$_GET['del_val']);
       header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=products_variants&edit=".$_GET['edit']);
    } 

#return 0;

//Templayte
    
    if (isset($_GET['edit']) || isset($_POST['edit']))
    { 
     
      $edit=isset($_GET["edit"]) ? (int)$_GET["edit"] : (int)$_POST["edit"];
      
      $options_val=db_arAll("select * from ".PRODUCT_OPTIONS_VAL_TABLE." where optionID=".$edit." order by sort_order");   
      $options_name=db_r('select name from '.PRODUCT_OPTIONS_TABLE.' where optionID='.$edit);
      $smarty->assign("options_name", $options_name);  
      $smarty->assign("products_variants_val", $options_val);
      $smarty->assign("edit", $edit);  
      $smarty->assign("admin_sub_dpt", "products_variants_val.tpl.html");
    }
    //Добовление опций и их значений
    elseif (isset($_GET['add']) || isset($_GET['valadd']))
    {
      if (isset($_GET['valadd'])) $smarty->assign("val", $_GET['valadd']);
      $smarty->assign("admin_sub_dpt", "products_variants_add.tpl.html");
    }
    else
    {
     
     $options=db_arAll("SELECT po.*, count(pov.optionID) kol FROM `".PRODUCT_OPTIONS_TABLE."` as po left join `".PRODUCT_OPTIONS_VAL_TABLE."` as pov on po.optionID=pov.optionID  group by  po.optionID order by po.sort_order");  
     $smarty->assign("products_variants", $options);
     $smarty->assign("admin_sub_dpt", "products_variants.tpl.html");
   }
}