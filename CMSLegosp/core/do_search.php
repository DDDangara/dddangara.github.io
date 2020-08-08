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
//получаем данные через $_POST
if (isset($_POST['search'])) {
    // подключаемся к базе
   
  
    // никогда не доверяйте входящим данным! Фильтруйте всё!
    
    $word = validate_search_string($_POST['search']);

    // Строим запрос

    $sql="SELECT productID,name,Price,in_stock FROM ".PRODUCTS_TABLE." where name LIKE '%" . $word . "%' or product_code='".$word."'";
    $row = db_arAll($sql);  $end_result='<table width="100%" cellspacing=0>';
    // Получаем результаты
    if(count($row)) {
        foreach($row as $r) {
            $end_result     .= "<tr style='background:#FFF;";
            if ((CONF_SHOW_ADD2CART_INSTOCK) || (!CONF_SHOW_ADD2CART_INSTOCK && $r['in_stock']>0)) $end_result     .= "cursor:pointer' onclick='addproduct(".$r['productID'].")' "; else $end_result .="'";
            $end_result     .= "class='grid'><td id='name_".$r['productID']."'>" .$r['name'].'</td><td>'.CONF_CURRENCY_ID_LEFT.'<font id="price_'.$r['productID'].'">'.$r['Price'].'</font>'.CONF_CURRENCY_ID_RIGHT.'</td><td>'.IN_STOCK.': '.$r['in_stock'].'</td></tr>';
        }
       $end_result .="</table>";
        echo $end_result;
    } else {
        echo '<h3 style="background:#FFF; color: red;">По вашему запросу ничего не найдено</h3>';
    }
}

if (isset($_POST['addcategory']))
{
    $html=''; 
   
    $q= db_query('select parent,name from '.CATEGORIES_TABLE.' where categoryID='.(int)$_POST['addcategory']);
    $row = db_fetch_row($q);
    $par= $row[0];
    $cname = $row[1];
    while($par > 0)
     {
        $scat=$par;
        $q= db_query('select parent,name from '.CATEGORIES_TABLE.' where categoryID='.$scat);
        $row = db_fetch_row($q);
        $par= $row[0];
        $cname =$row[1].'->'.$cname;  
     } 


    if ($cname) $html=$cname.'<input type=hidden name="appended_categories[]" value="'.$_POST['addcategory'].'">';  
    echo $html;
}
elseif (isset($_POST['delcat']))
{

   db_query('DELETE FROM `'.CATEGORIY_PRODUCT_TABLE.'` WHERE `id`='.(int)$_POST['delcat']);
} 

?>