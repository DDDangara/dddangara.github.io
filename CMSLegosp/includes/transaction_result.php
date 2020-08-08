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
 
	if(isset($_REQUEST["transaction_result"]) )
		$transaction_result = $_REQUEST["transaction_result"];
	else
		$transaction_result = null;

	$wmorderID = null;
	$wmorder   = null;

	if(isset($_REQUEST["LMI_PAYMENT_NO"]) )
		$wmorderID = (int)$_REQUEST["LMI_PAYMENT_NO"];

	if (isset($wmorderID) )
	{
		$q = db_query("SELECT orderID FROM ".ORDERS_TABLE." WHERE orderID='".$wmorderID."'") or die (db_error());
		$wmorder = db_fetch_row($q);
	}

	if ($wmorder != null && $wmorderID > 0)
	{
		switch ($transaction_result)
		{
			case 'success':
				$smarty->assign("orderID", $wmorderID);
				$smarty->assign("TransactionResult", $transaction_result);
				$smarty->assign( "main_content_template", "transaction_result.tpl.html");
			break;

			case 'failure':
				$smarty->assign("TransactionResult", $transaction_result);
				$smarty->assign( "main_content_template", "transaction_result.tpl.html");
			break;

			default:  break;
		}
	}

?>