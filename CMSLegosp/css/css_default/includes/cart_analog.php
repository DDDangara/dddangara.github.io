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
if (isset($_POST["cart_analog"]) && !empty($_POST["cart_analog"])) {
	//session_start();
	//include("../cfg/ajax_connect.inc.php");
	//include '../smarty/smarty.class.php';
	//$smarty = new Smarty;
	//$smarty->template_dir = "../css/css_" . CONF_COLOR_SCHEME . "/theme";
	
	$q = db_query('SELECT name, Price, picture, in_stock FROM '.PRODUCTS_TABLE.' WHERE productID='.mysqli_real_escape_string(Dbconector::getInstance()->getConnect(), $_POST["cart_analog"])) or die(db_error());
	$row = db_assoc_q($q);
	
	if (!isset($_SESSION["gids"])) {
		$_SESSION["gids"] = array();
		$_SESSION["counts"] = array();
		$_SESSION["opt"] = array();
	}
	
	$cak = 0;
	if (isset($_SESSION["gids"])) {
		foreach ($_SESSION["gids"] as $key => $val)
		{
			$proce = db_r('SELECT Price FROM '.PRODUCTS_TABLE.' WHERE productID='.(int)$val);
			if ($_SESSION["opt"][$key]){
				$opt_array=explode(',',substr($_SESSION["opt"][$key],1));
				$price_surplus = db_r('SELECT sum(`price_surplus`) price_surplus FROM `'.PRODUCT_OPTIONS_TABLE.'` as O join `'.PRODUCT_OPTIONS_VAL_TABLE.'` as V on (O.optionID=V.optionID and V.variantID in ('.add_in($opt_array).')) join `'.PRODUCT_OPTIONS_V_TABLE.'` as OV on (V.variantID=OV.variantID and productID='.(int)$val.')');
				$proce += $price_surplus;
				
				if($val==$_POST["cart_analog"] && $_SESSION["opt"][$key]==$_POST["opt"])
					$row["Price"] += $price_surplus;
			
			}
			$cak += $proce*$_SESSION["counts"][$key];
		}
	}
	
	$cacnt = array_sum($_SESSION["counts"]);
	

	$picture = $row["picture"];
	if (!file_exists("./products_pictures/".$picture)) {
		$picture = "nophoto.jpg";
	}
	$smarty->assign("tov_name", $row["name"]);
	$smarty->assign("tov_pic", $picture);
	$smarty->assign("tov_price", show_price($row["Price"]));

	$smarty->assign("cart_analog_cart_value", show_price($cak));
	$smarty->assign("cart_analog_cart_items", $cacnt);

    for ($i = 0; $i < count($_SESSION["gids"]); $i++) {  
        if ($_SESSION["gids"][$i] == $_POST["cart_analog"]) {
			$num = $_SESSION["counts"][$i];
            break;
        }
    }

	if (($row["in_stock"] - $num - 1) < 0) {
		echo "-1"; return 0;
	} else {
		echo $smarty->fetch("new_shopping_cart_info.tpl.html");	
	}

	exit;
}
?>