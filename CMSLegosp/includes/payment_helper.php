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
  
	//Вспомагательные функции
	//До оплаты
	function _before_payment( $orderID, $WmSum, $lopatnik )
	{		
		$res = "";
		$order_amount = db_r("SELECT Price FROM ".ORDERED_CARTS_TABLE." WHERE orderID='".(int)$orderID."' ") or die (db_error());
		$exp_amount = explode(".", $order_amount);
		$kop = $exp_amount[1];
		if (!$kop) {$kop = "00";}
		$order_amount = $order_amount.".".$kop; unset($kop);
		$merch_lopatnik = db_r("SELECT UPPER(payvalue) FROM ".PAYOPTION_TABLE." WHERE payoption = 'merchant' ") or die (db_error());
		if ($order_amount != 0 && $merch_lopatnik == strtoupper($lopatnik) && $WmSum == $order_amount) $res = "YES";
                unset($orderID, $WmSum, $lopatnik,$merch_lopatnik,$order_amount);  
		return $res;
	}

	//После оплаты
	function _after_payment( $orderID, $params )
	{		
		$q = db_query("SELECT Price FROM ".ORDERED_CARTS_TABLE." WHERE orderID='".(int)$orderID."' ") or die (db_error());
		$order = db_fetch_row($q);
		if ( $order[0] != 0)
		{
			$q2 = db_query("SELECT payvalue FROM ".PAYOPTION_TABLE." WHERE payoption = 'secretkey' ") or die (db_error());
			$row2 = db_fetch_row($q2);
			$veryskey = $row2[0];
			$order_amount = $order[0];
			$exp_amount = explode(".", $order_amount);
			$kop = $exp_amount[1];
			if (!$kop) {$kop = "00";}
			$order_amount = $order_amount.".".$kop;
			$WmSum = $params["LMI_PAYMENT_AMOUNT"];
			$merch_lopatnik = db_r("SELECT UPPER(payvalue) FROM ".PAYOPTION_TABLE." WHERE payoption = 'merchant' ") or die (db_error());
			$crc = strtoupper(md5($merch_lopatnik.$params["LMI_PAYMENT_AMOUNT"].$orderID.$params["LMI_MODE"].$params["LMI_SYS_INVS_NO"].$params["LMI_SYS_TRANS_NO"].$params["LMI_SYS_TRANS_DATE"].$veryskey.$params["LMI_PAYER_PURSE"].$params["LMI_PAYER_WM"]));
			if ($order_amount != 0 && $merch_lopatnik == strtoupper($params["LMI_PAYEE_PURSE"]) && $WmSum == $order_amount && $crc == strtoupper($params["LMI_HASH"]))
	 		{
				$row4 = db_r("SELECT payvalue FROM ".PAYOPTION_TABLE." WHERE payoption = 'afterpay' ") or die (db_error());
				db_query("UPDATE ".ORDERS_TABLE." SET status='".$row4[0]."' WHERE orderID='".(int)$orderID."' ") or die (db_error());
	 		}
		}
	}

	// Передавать параметры в предварительном запросе
	// Не высылать Secret Key, если Result URL обеспечивает безопасность
	// Не позволять использовать URL, передаваемые в форме
	// Метод формирования контрольной подписи MD5

	if(isset($_REQUEST["webmoney"]) && isset($_REQUEST["LMI_PREREQUEST"]))
	{		
		$result = "";
		$orderID = (int)$_REQUEST["LMI_PAYMENT_NO"];
		if ($order = db_query("SELECT orderID FROM ".ORDERS_TABLE." WHERE orderID='".$orderID."' "))
		{
			$result = _before_payment( $orderID, $_REQUEST["LMI_PAYMENT_AMOUNT"], $_REQUEST["LMI_PAYEE_PURSE"] );
			if ($result != "")
			{
				header("Content-type: text/html; charset=windows-1251");
				die( $result );
			}
		}
	}

	// Передавать параметры в предварительном запросе
	// Не высылать Secret Key, если Result URL обеспечивает безопасность
	// Не позволять использовать URL, передаваемые в форме
	// Метод формирования контрольной подписи MD5

	if(isset($_REQUEST["webmoney"]) && !isset($_REQUEST["LMI_PREREQUEST"]))
	{
		$result = "";
		$orderID = (int)$_REQUEST["LMI_PAYMENT_NO"];
		if ($order=db_r("SELECT orderID FROM ".ORDERS_TABLE." WHERE orderID='".(int)$orderID."' ")) $result = _after_payment( $orderID, $_REQUEST );

	}
?>