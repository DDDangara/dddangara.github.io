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
 

	// shopping cart brief info

	//calculate shopping cart value
	$k=0;
	$cnt = 0;
	if (isset($_SESSION["gids"])) //...session vars
	{
		foreach ($_SESSION["gids"] as $key => $val)
		{
		   $proce = db_r('SELECT Price FROM '.PRODUCTS_TABLE.' WHERE productID='.(int)$val);
                   if ($_SESSION["opt"][$key])
                   {
                       $opt_array=explode(',',substr($_SESSION["opt"][$key],1));
                       $proce += db_r('SELECT sum(`price_surplus`) price_surplus FROM `'.PRODUCT_OPTIONS_TABLE.'` as O join `'.PRODUCT_OPTIONS_VAL_TABLE.'` as V on (O.optionID=V.optionID and V.variantID in ('.add_in($opt_array).')) join `'.PRODUCT_OPTIONS_V_TABLE.'` as OV on (V.variantID=OV.variantID and productID='.(int)$val.')');
                   }
                   $k += $proce*$_SESSION["counts"][$key];
		}
		$cnt=array_sum($_SESSION["counts"]);
		$k= round($k/CURRENCY_val,2);
	}

	//minimal
      if (isset($_SESSION["discount"]))
      { 
        $disc=$_SESSION["discount"][1];
        $disc=$k*$disc/100;
        $k -=$disc;
      } 
     	$smarty->assign("shopping_cart_value", $k);
	$smarty->assign("shopping_cart_value_shown", show_price($k));
	$smarty->assign("shopping_cart_items", $cnt);
?>