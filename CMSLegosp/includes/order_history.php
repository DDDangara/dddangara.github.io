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
 
if (isset($_GET["order_history"]) && isset($_SESSION["cust_id"]))
{
if (isset($_GET["orderid"]))
           {
            
             $order_nambe=(int)$_GET["orderid"];
             $order_history=db_arAll("select *  from ".ORDERED_CARTS_TABLE.' as `order_car` left join '.ORDERS_TABLE.' as `order` on (`order`.orderID=`order_car`.orderID) where `order`.orderID='.$order_nambe.' and `order`.`custID`='.(int)$_SESSION["cust_id"]." and name not like '".ADMIN_DISCOUNT_STRING." %'");
             foreach($order_history as $val){
                $products_info[]=db_arAll('select * from `'.PRODUCTS_TABLE.'` where `productID`='.$val['productID']); 
             }
             $order_discont=db_assoc("select * from ".ORDERED_CARTS_TABLE." where orderID=".$order_nambe." and name like '".ADMIN_DISCOUNT_STRING." %'");
             $order_info=db_arAll('select * from `'.ORDERS_TABLE.'` where `orderID`='.$order_nambe.' and custID='.(int)$_SESSION["cust_id"]);
             $smarty->assign('order_info', $order_discont);
             $smarty->assign("order_discont", $order_discont);
             $smarty->assign("order_history", $order_history);
             $smarty->assign("products_info", $products_info);
             $smarty->assign("meta_title", ORDERS_HISTORY.' > '.$order_nambe);
             $smarty->assign("main_content_template", "order_history.tpl.html");
           }
           else
           { 
             $order_history=db_arAll("select `order`.`orderID` orderID, `Quantity` Quantity, `cust_firstname` cust_firstname, `cust_lastname` cust_lastname, `order`.`order_time` order_time, `name` name, '' status, sum(Price*Quantity) summ  from ".ORDERS_TABLE." as `order` LEFT JOIN ".ORDERED_CARTS_TABLE." as `order_car` USING ( `orderID` ) where `order`.`custID`=".(int)$_SESSION["cust_id"]." and name not like '".ADMIN_DISCOUNT_STRING." %' group by `order`.`orderID`");


             $historyall = db_arALL("select *  from ".ORDERED_CARTS_TABLE.' as `order_car` left join '.ORDERS_TABLE.' as `order` on (`order`.orderID=`order_car`.orderID) where `order`.orderID and `order`.`custID`='.(int)$_SESSION["cust_id"]." and name not like '".ADMIN_DISCOUNT_STRING." %'");
        

             foreach ($order_history as $key=>$value)
             {
               $d=db_r("select Price from ".ORDERED_CARTS_TABLE." where orderID=".$value['orderID']." and name like '".ADMIN_DISCOUNT_STRING." %'");
               $order_history[$key]["summ"]= show_price($value["summ"]- (integer)$d);
             } 


              foreach($historyall as $val){
                $products_info[]=db_arAll('select * from `'.PRODUCTS_TABLE.'` where `productID`='.$val['productID']); 
             }


$res = [];


             $smarty->assign("products_info", $products_info); 


             $smarty->assign("res", $res);
             $smarty->assign("historyall", $historyall);
             $smarty->assign("history", $order_history);
             $smarty->assign("meta_title", ORDERS_HISTORY);  
             $smarty->assign("main_content_template", "order_history.tpl.html");
           } 
           }
?>