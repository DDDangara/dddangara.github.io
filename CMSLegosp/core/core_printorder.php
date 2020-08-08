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
 
       include("../cfg/ajax_connect.inc.php");
       if (isset($_SESSION["order_id"]))
       {
        $q = db_query("SELECT orderID, cust_firstname, cust_lastname, cust_email, cust_city, cust_address, cust_phone, comment, manager FROM ".ORDERS_TABLE." WHERE orderID=".(int)$_SESSION["order_id"]) or die (db_error());
	$result = db_fetch_row($q);
	$q = db_query("SELECT online_name FROM ".MANAGER_TABLE." WHERE ID='".$result[8]."'") or die (db_error());
		$m_row = db_fetch_row($q);
		$total=0;
		$q = db_query("SELECT name, Price, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".$result[0]."") or die(db_error());
		while ($row = db_fetch_row($q))
		{	
                        $row[1]=round($row[1]/CURRENCY_val,2);  	
			if (substr_count($row[0],ADMIN_DISCOUNT_STRING) > 0)
			{
			    $total -= $row[1]*$row[2];
			    $tmp = explode(" ",$row[0]);
			    $row[4] = "<br /><b>".show_price($row[1]*$row[2])."</b>";
			    $row[0] = "<br /><b>".$tmp[0]."</b>";
			    $row[1] = "";
			    $row[2] = "<br /><b>".$tmp[1]."%</b>";
			    $res[] = Array();
			    $res[] = $row;
			}
			else
			{
			    $total += $row[1]*$row[2];

			    $row[4] = show_price($row[1]*$row[2]);
			    $row[1] = show_price($row[1]);
			    $res[] = $row;
			}	
		}

		$result[8] = $m_row[0];
		$result[9] = show_price($total); //order value
                require '../smarty/smarty.class.php'; 
                $smarty = new Smarty;
                $smarty->assign("result", $result);
                $smarty->assign("res", $res);
                $smarty->display("../css/css_".CONF_COLOR_SCHEME."/theme/printorder.tpl.html"); 
           }     

?>
