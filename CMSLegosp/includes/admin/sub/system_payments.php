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
 

//general settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT')){
	die;
}

if(!strcmp($sub, "payments")){
	if(isset($_POST["update_payments"])) //update managers
	{
		#db_query("UPDATE ".PAYMENT_TABLE." SET enabled = '0'") or die (db_error());
		db_query("UPDATE `".PAYMENT_TABLE."` SET `enabled`=0;") or die (db_error()) ;

		if(!isset($_POST["pay_qiwi_checkmobile"])) $_POST["pay_qiwi_checkmobile"] = 0;
		if(!isset($_POST["pay_robox_testmode"])) $_POST["pay_robox_testmode"] = 0;
		if(!isset($_POST["pay_webmoney_testmode"])) $_POST["pay_webmoney_testmode"] = 0;
		if(!strcmp($_POST["pay_qiwi_checkmobile"], "on")) $_POST["pay_qiwi_checkmobile"] = 1;
		else $_POST["pay_qiwi_checkmobile"] = 0;
		
		foreach($_POST as $key => $val){
			$tmp = explode("_",$key);
			if(strstr($key, "pay_")){
				if($tmp[2] == "enabled"){
					db_query("UPDATE ".PAYMENT_TABLE." SET enabled = 1 WHERE type='".$tmp[1]."'") or die (db_error());
				}
				$q = db_query("SELECT * FROM ".PAYMENT_TABLE." LEFT JOIN ".PAYOPTION_TABLE." USING (payID) WHERE type='".$tmp[1]."' AND payoption='".$tmp[2]."'") or die (db_error());
				$row = db_fetch_row($q);
				if($row)
				db_query("UPDATE ".PAYMENT_TABLE." LEFT JOIN ".PAYOPTION_TABLE." USING (payID) SET payvalue = '".$val."' WHERE type='".$tmp[1]."' AND payoption='".$tmp[2]."'") or die (db_error());
				else{
					$q = db_query("SELECT payID FROM ".PAYMENT_TABLE." WHERE type='".$tmp[1]."'") or die (db_error());
					$row1 = db_fetch_row($q);
					db_query("INSERT INTO ".PAYOPTION_TABLE." (payID, payoption, payvalue) VALUES ('".$row1[0]."','".$tmp[2]."','".$val."');") or die (db_error());
				}
			}
		}
	}
	//robox payments list
	$robox_pay['values'] = Array('PCR','WMRM','WMZM','WMEM','WMUM','WMBM','WMGM','MoneyMailR','RuPayR','MobileWalletR','W1R','EasyPayB','WebCredsR','MailRuR','VKontakteBANKR','ZPaymentR');
	$robox_pay['names'] = Array('Яндекс.Деньги','WMR','WMZ','WME','WMU','WMB','WMG','RUR MoneyMail','RUR RBK Money','RUR Личный кабинет Qiwi','RUR Единый Кошелек','EasyPay','RUR WebCreds','Деньги@Mail.Ru','RUR Банк.ВКонтакте','RUR Z-Payment');
	$smarty->assign("robox_pay", $robox_pay);

	$q = db_query("SELECT * FROM ".PAYMENT_TABLE." LEFT JOIN ".PAYOPTION_TABLE." USING (payID)") or die (db_error());
	while($row = db_assoc_q($q)){
		$smarty -> assign($row['type']."_".$row['payoption'], $row['payvalue']);
		$smarty -> assign($row['type']."_enabled", $row['enabled']);
	}

	//Статусы заказов для Webmoney
	$ord_status = db_arAll('select * from '.ORDER_STATUS_TABLE. ' order by sort_order');
	$smarty->assign("ord_status", $ord_status);

	//set sub - department template
	$smarty->assign("admin_sub_dpt", "system_payments.tpl.html");
}

?>