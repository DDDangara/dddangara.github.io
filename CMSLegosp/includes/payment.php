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
 

	// payments page

if (isset($_GET["payment"]))
{
	if( isset($_POST["payment_type"]) ){ $payment_type = $_POST["payment_type"]; }
	else{
		if( isset($_GET["payment_type"]) ){//посылки из письма
			$payment_type = validate_search_string($_GET["payment_type"]);
		}
		else{ $payment_type = 0; }
	}

	//payments
	$q = db_query("SELECT * FROM ".PAYMENT_TABLE." LEFT JOIN ".PAYOPTION_TABLE." USING (payID)") or die (db_error());
	$tmp_arr = Array();
	while ($row = db_assoc_q($q)){
		$tmp_arr[$row['type']."_".$row['payoption']] = $row['payvalue'];
		$smarty -> assign($row['type']."_".$row['payoption'], $row['payvalue']);
	}


	//price
	if( isset($_GET["order_id"]) && isset($_GET["mail_to"]) ){//посылка из пиьма
		$payment['id'] = (int)$_GET["order_id"];
		$mail_to = validate_search_string($_GET["mail_to"]);
		$sql = "
			SELECT COUNT(*)
				FROM ".ORDERS_TABLE."
				WHERE `orderID` = ".$payment['id']." AND `cust_email` = '$mail_to'
		";
		$result = db_r($sql);
		if($result == 0){ die('Access denied'); }
	}
	else{
		$payment['id'] = $_SESSION["order_id"];
	}
	$total = db_r("SELECT SUM(Price*Quantity) FROM ".ORDERED_CARTS_TABLE.' WHERE name NOT LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and orderID='.(int)$_SESSION["order_id"]) or die(db_error());
	$disk=db_r("SELECT Price FROM ".ORDERED_CARTS_TABLE.' WHERE name LIKE \'' . ADMIN_DISCOUNT_STRING . '%\' and orderID='.(int)$_SESSION["order_id"]);
	$total -= (integer)$disk;

	$addprice = round($total/100*$tmp_arr[$payment_type."_addprice"], 2);

	$payment['addprice'] = $tmp_arr[$payment_type."_addprice"];

	$tmp_price = explode(".", $addprice+$total);
	$payment['price'] = show_price($total);
	$payment['price_rub'] = $tmp_price[0];
	$payment['price_kop'] = $tmp_price[1];
	if (!$payment['price_kop']) {$payment['price_kop'] = 0;}

	$tmp_price_us = explode(".", round(($addprice+$total)/CURR_USD, 2));
	$payment['price_usd'] = $tmp_price_us[0];
	$payment['price_usc'] = $tmp_price_us[1];
	if (!$payment['price_usc']) {$payment['price_usc'] = 0;}

	$smarty->assign("payment", $payment);

	$tmp_arr['all_description'] = str_replace("%orderid%", $_SESSION["order_id"], $tmp_arr['all_description']);
	$tmp_arr['all_description'] = str_replace("%ordertotal%", $payment['price'], $tmp_arr['all_description']);
	$smarty -> assign("payment_description", $tmp_arr['all_description']);
	if (DB_CHARSET =='cp1251') $tmp_arr['all_description']=win2utf($tmp_arr['all_description']);


	$smarty->assign("payment_type", $payment_type);
	$smarty->assign("payment_name", $tmp_arr[$payment_type.'_name']);

	$robox_crc = md5($tmp_arr['robox_login'].":".$payment['price_rub'].".".$payment['price_kop'].":".$_SESSION["order_id"].":".$tmp_arr['robox_pass'].":"."Shp_item=");
	$smarty -> assign("robox_crc", $robox_crc);
	
	//////////////interkassa_2//////////////////
	if($payment['price_usd']){
		if(!$payment['price_usc']) $payment['price_usc'] = 0;
		$ik_am = $payment['price_usd'].'.'.$payment['price_usc'];
		if($tmp_arr['inter_addprice'] && $tmp_arr['inter_addprice']!=0){
			$addprice = ($tmp_arr['inter_addprice'])/100;
			$addprice = ($ik_am*$addprice) + $ik_am;
			$ik_am = round($addprice,2);
		}
		$smarty->assign("payment_price", $ik_am);
	}
	//массив формирующий подпись
	$dataSet = array(
						'ik_co_id'=>$tmp_arr['inter_shopid'],
						'ik_pm_no'=>$payment['id'],
						'ik_am'=>$ik_am,
						'ik_cur'=>CONF_CURRENCY_ISO3,
						'ik_desc'=>$tmp_arr['all_description']
							);
	$key = $tmp_arr['inter_key']; //тестовый или секретный ключ
	
	
	//unset($dataSet['ik_sign']); //удаляем из данных строку подписи
	ksort($dataSet, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
	array_push($dataSet, $key); // добавляем в конец массива "секретный ключ"
	$signString = implode(':', $dataSet); // конкатенируем значения через символ ":"
	$ik_sign = base64_encode(md5($signString, true));  // берем MD5 хэш в бинарном виде по сформированной строке и кодируем в BASE64
	$smarty->assign("ik_sign", $ik_sign);
	//////////////END interkassa_2//////////////
	
	
	
	$smarty->assign("main_content_template", "payment.tpl.html");
}
?>