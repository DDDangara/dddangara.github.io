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


if (!strcmp($sub, "order_statuses"))
{
    if( isset( $_GET['delete'] ) && is_numeric( $_GET['delete'])){
        db_query('DELETE FROM `'.ORDER_STATUS_TABLE.'` WHERE statusID='.(int)$_GET['delete']);
        header( "Location: http://" . CONF_SHOP_URL . '/' . CONF_ADMIN_FILE . '?dpt=' . $dpt . '&sub=' . $sub ); //category not found
        exit;
    }
 if (isset($_POST['save_successfull']))
 {
    if (!empty($_POST['add_status']['status_name']))
    {
      add_field(ORDER_STATUS_TABLE, $_POST['add_status']);
    }
    foreach ($_POST['update_order_status'] as $key => $val)
    {
       if (!empty($val['status_name']))
         update_field(ORDER_STATUS_TABLE, $val,"statusID=".$key);
    }
    header("Location: http://" . CONF_SHOP_URL . '/'.CONF_ADMIN_FILE.'?dpt='.$dpt.'&sub='.$sub); //category not found
    exit;
 }

 $ord_status=db_arAll('select * from '.ORDER_STATUS_TABLE. ' where statusID>2 order by sort_order');
 $smarty->assign("order_statues", $ord_status);
 $smarty->assign("admin_sub_dpt", "custord_order_statuses.tpl.html");
  
}
?>