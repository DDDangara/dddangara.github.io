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
 


if(isset($_POST["complete_order"]) || (isset($_GET["order_placement_result"]) && isset($_SESSION['order_reg']))) //place order
{
	if(!trim($_POST["first_name"]) || !preg_match('/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/',$_POST["email"]) || !preg_match('/^[\s0-9-()+]+$/',$_POST["phone"])){
		$smarty->assign("error", 1);
		$smarty->assign("main_content_template", "order_place.tpl.html");
	}
	//shopping cart items count
	$c = 0;

	if(isset($_SESSION["gids"]))
	for($j = 0; $j < count($_SESSION["gids"]); $j++)
	if($_SESSION["gids"][$j]) $c += $_SESSION["counts"][$j];

	//not empty?
	if(isset($_SESSION["gids"]) && $c){
		//insert order into database
		if(preg_match('/^[\s0-9-()+]+$/',$_POST["phone"])) $post_phone = $_POST["phone"];
		else $post_phone = validate_search_string($_POST["phone"]);
		if(preg_match('/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/',$_POST["email"])) $post_email = $_POST["email"];
		else $post_email = validate_search_string($_POST["email"]);

		//select manager
		$q_m        = db_query("SELECT ID, access, online_name, email FROM ".MANAGER_TABLE) or die (db_error());
		while($row = db_fetch_row($q_m))
		if($row[1] == 1)
		{
			$man_arr[] = $row[0]; $man_name[$row[0]] = $row[2]; $man_email[$row[0]] = $row[3];
		}
		if($man_arr){
			shuffle($man_arr);
			$smarty_mail->assign("manager", $man_name[$man_arr[0]]);
		}

		$order_info = array();
		$order_info['order_time'] = get_current_time();
		$order_info['cust_firstname'] = $_POST["first_name"];
		$order_info['cust_lastname'] = $_POST["last_name"];
		$order_info['cust_email'] = $post_email;
		$order_info['cust_country'] = $_POST["country"];
		$order_info['cust_zip'] = $_POST["zip"];
		$order_info['cust_state'] = $_POST["state"];
		$order_info['cust_city'] = $_POST["city"];
		$order_info['cust_address'] = $_POST["address"];
		$order_info['cust_phone'] = $post_phone;
		$order_info['comment'] = $_POST["comment"];
		$order_info['manager'] = $man_arr[0];
		if(isset($_SESSION['cust_id']))
		$order_info['custID'] = $_SESSION['cust_id'];
		add_field(ORDERS_TABLE, $order_info);
		$oid      = db_insert_id(); //order ID

		//now move shopping cart content to the database

		$k        = 0; //total cart value
		$products = array();
		$adm = ""; //order notification for administrator
		$opt = $_SESSION["opt"];
		for($i = 0; $i < count($_SESSION["gids"]); $i++)
		if($_SESSION["gids"][$i]){

			$q = db_query("SELECT P.name, Price, product_code, hurl, categoryID,email manager_email, online_name FROM ".PRODUCTS_TABLE.' as P LEFT JOIN '.MANAGER_TABLE." as M on (M.ID=P.managerID) WHERE productID=".(int)$_SESSION["gids"][$i]) or die (db_error());
			if($r = db_fetch_row($q)){

				if(trim($r[5]) && isValidEmail($r[5])){
					$managers_mail[$am]['mail'] = $r[5];
					$managers_mail[$am++]['name'] = $r[6];
				}

				$variants = '';
				if($opt[$i] != ''){
					$opt_array = explode(',',substr($_SESSION["opt"][$i],1));
					$options   = db_assoc('SELECT sum(`price_surplus`) price_surplus,  GROUP_CONCAT(CONCAT(O.`name`,\':\',V.`name`) order by O.sort_order, V.sort_order SEPARATOR \', \') variants FROM `'.PRODUCT_OPTIONS_TABLE.'` as O join `'.PRODUCT_OPTIONS_VAL_TABLE.'` as V on (O.optionID=V.optionID and V.variantID in ('.add_in($opt_array).')) join `'.PRODUCT_OPTIONS_V_TABLE.'` as OV on (V.variantID=OV.variantID and productID='.$_SESSION["gids"][$i].')');
					$variants  = '('.$options['variants'].')';
					$r[1] += $options['price_surplus'];
					db_query('UPDATE `'.PRODUCT_OPTIONS_V_TABLE.'` SET `count` = `count`-'.$_SESSION["counts"][$i].' WHERE `productID` = '.(int)$_SESSION["gids"][$i].' and variantID in ('.add_in($opt_array).')');
				}

				//product info
				show_price(round($_SESSION["counts"][$i] * $r[1] / CURRENCY_val, 2));
				$tmp = array(
					$_SESSION["gids"][$i],
					$r[0].$variants,
					$_SESSION["counts"][$i],
					show_price(round($_SESSION["counts"][$i] * $r[1] / CURRENCY_val, 2)),
					$r[2],
					show_price(round($r[1] / CURRENCY_val, 2))
				);

				//store ordered products info into database
				$articul      = trim($tmp[4]) ? "[".$tmp[4]."] " : "";

				//write to db
				$order_insert = array();
				$order_insert['orderID'] = $oid;
				$order_insert['productID'] = $tmp[0];
				$order_insert['name'] = $articul.$tmp[1];
				$order_insert['Price'] = $r[1];
				$order_insert['Quantity'] = $tmp[2];
				add_field(ORDERED_CARTS_TABLE, $order_insert);
				#$q1 = db_query("INSERT INTO ".ORDERED_CARTS_TABLE." (Quantity) values ( '".$r[1]."', '".$tmp[2]."');") or die (db_error());

				//update item sold
				db_query("UPDATE ".PRODUCTS_TABLE." SET items_sold=items_sold+1, in_stock=in_stock-".$tmp[2]." WHERE productID=".(int)$_SESSION["gids"][$i]) or die (db_error());

				$products[] = $tmp;

				//update order amount
				#round($_SESSION["counts"][$i] * $r[1] / CURRENCY_val, 2)
				$k += round($_SESSION["counts"][$i] * $r[1] / CURRENCY_val, 2);

				//order notification for administrator - update
				$adm .= $articul.$tmp[1]."; ".TABLE_PRODUCT_COST." - ".$tmp[5]."; ".TABLE_PRODUCT_QUANTITY." - ".$tmp[2]."; ".TABLE_PRODUCT_SUMM." - ".$tmp[3]."\n";
				$adm .= "\n";
			}
		}

		if(isset($_SESSION["present"])){
			$q1 = db_query("insert into ".ORDERED_CARTS_TABLE." (orderID, productID, name, Price, Quantity) values ('".$oid."', '".$_SESSION["present"][0]."', '".$_SESSION["present"][2]."', '".STRING_PRESENT."', '1');") or die (db_error());
		}

		if(isset($_SESSION["shipping"]) && $_SESSION["shipping"][1] > 0){
			$q1 = db_query("insert into ".ORDERED_CARTS_TABLE." (orderID, productID, name, Price, Quantity) values ('".$oid."', '".$_SESSION["shipping"][0]."', '".ADMIN_SHIPPING." ".$_SESSION["shipping"][2]."', '".$_SESSION["shipping"][1]."', '1');") or die (db_error());
			$k += $_SESSION["shipping"][1];
		}

		if(isset($_SESSION["get_fast_order"])){
			$q1 = db_query("insert into ".ORDERED_CARTS_TABLE." (orderID, productID, name, Price, Quantity) values ('$oid', '$oid', '".ADMIN_FAST_ORDER."', '".$_SESSION["get_fast_order"]."', '1');") or die (db_error());
			$k += $_SESSION["get_fast_order"];
		}

		if(isset($_SESSION["discount"])){
			#if (DEFAULT_CHARSET == 'utf - 8') ADMIN_DISCOUNT_STRING = win2utf(ADMIN_DISCOUNT_STRING);
			$q1 = db_query("insert into ".ORDERED_CARTS_TABLE." (orderID, productID, name, Price, Quantity) values ('$oid', '".$_SESSION["discount"][0]."', '".ADMIN_DISCOUNT_STRING." ".$_SESSION["discount"][1]."', '".$_SESSION["discount"][2]."', '1');") or die (db_error());
			$k -= $_SESSION["discount"][2];
		}

		//	if (isset($_SESSION["minimal"]))
		//	  {
		//	    $q1 = db_query("insert into ".ORDERED_CARTS_TABLE." (orderID, productID, name, Price, Quantity) values ('$oid', '".$tmp[0]."min01"."', '".$_SESSION["minimal"][0]."', '".$_SESSION["minimal"][1]."', '1');") or die (db_error());
		//	    $k += $_SESSION["minimal"][1];
		//	  }

		$sql='
				SELECT payvalue as name,type
					FROM '.PAYMENT_TABLE.' as T1 JOIN '.PAYOPTION_TABLE.' as T2
					ON(
						T1.enabled = 1 AND
						T1.payID = T2.payID AND
						T2.payoption = "name"
					)
			';
		$payment_type = db_arAll($sql);
		$smarty_mail->assign("payment_type", $payment_type);
		
		$mail_to = validate_search_string($_POST["email"]);
		$smarty_mail->assign("mail_to", $mail_to);
		
		//exit();
		//assign order content to smarty
		$smarty_mail->assign("order_content", $products);
		$smarty_mail->assign("order_total", show_price($k));
		$smarty_mail->assign("order_id", $oid);
		$smarty_mail->assign("order_custname", $_POST["first_name"]." ".$_POST["last_name"]);
		$smarty_mail->assign("order_shipping_address", "".$_POST["city"]."\n".$_POST["address"]); //."\n?.".." ".$_POST["state"]."  ".$_POST["zip"]."\n".$_POST["country"]

		if(isset($_SESSION["shipping"]))
		{
			$smarty_mail->assign("shipping", $_SESSION["shipping"]);
		}
		if(isset($_SESSION["get_fast_order"]))
		{
			$smarty_mail->assign("get_fast_order", $_SESSION["get_fast_order"]);
		}

		if(isset($_SESSION["present"]))
		{
			$smarty_mail->assign("present", $_SESSION["present"]);
		}
		//			if (isset($_SESSION["minimal"])) {$smarty_mail->assign("minimal", $_SESSION["minimal"]);}
		if(isset($_SESSION["discount"]))
		{
			$smarty_mail->assign("discount", $_SESSION["discount"]);
		}


		$_SESSION["order_id"] = $oid;
		//$_SESSION["order_amount"] = $k;

		//send message to customer
		$file_name            = "./css/css_".CONF_COLOR_SCHEME."/image/mail_logo.jpg";
		$SHOP_NAME            = CONF_SHOP_NAME;
		$NOTIFICATION_SUBJECT = EMAIL_CUSTOMER_ORDER_NOTIFICATION_SUBJECT;
		$last_name            = $_POST["last_name"];
		$first_name           = $_POST["first_name"];
		if(isset($managers_mail) && count($managers_mail) > 0){
				$managers_mail = array_unique($managers_mail);
				foreach($managers_mail as $key => $manager_mail) $manager .= $manager_mail['name'].", ";
				$manager = substr($manager, 0, strlen($manager) - 2);
				$smarty_mail->assign("manager", $manager);
				$adm .= "\n".ADMIN_MANAGER_MAIL.": ".$manager."\n";
		}
		elseif($man_arr){
			$adm .= "\n".ADMIN_MANAGER_MAIL.": ".$man_name[$man_arr[0]]."\n";
		}
		$html_body = $smarty_mail->fetch("order_notification.tpl.html");
		$to['mail'] = $_POST["email"];
		$to['name'] = $_POST["first_name"]." ".$_POST["last_name"];
		$from['mail'] = CONF_GENERAL_EMAIL;
		$from['name'] = $SHOP_NAME;
		
		$file_img['file'] = $file_name;
		$file_img['cid'] = 'mail_img_1';
		
		
		phpmailer ($to, $from,EMAIL_CUSTOMER_ORDER_NOTIFICATION_SUBJECT,'', $html_body,$file_img);
		
		
		$NOTIFICATION_SUBJECT = EMAIL_ADMIN_ORDER_NOTIFICATION_SUBJECT;
		$last_name            = $_POST["last_name"];
		$first_name           = $_POST["first_name"];
		//notification for administrator
		$od                   = STRING_ORDER_ID.": $oid\n\n";
		
		if(isset($_SESSION["shipping"])){
			$adm .= ADMIN_SHIPPING." ".$_SESSION["shipping"][2]." ".$_SESSION["shipping"][1].CONF_CURRENCY_ID_RIGHT."\n\n";
		}
		if(isset($_SESSION["get_fast_order"])){
			$adm .= ADMIN_FAST_ORDER." ".$_SESSION["get_fast_order"].CONF_CURRENCY_ID_RIGHT."\n\n";
		}
		
		if(isset($_SESSION["present"]) && $_SESSION["present"][1]){
			$adm .= $_SESSION["present"][2]." - ".STRING_PRESENT."\n\n";
		}
		if(isset($_SESSION["discount"])){
			$adm .= ADMIN_DISCOUNT_STRING." ".$_SESSION["discount"][1]."% - ".$_SESSION["discount"][3]."\n\n";
		}
		$adm .= EMAIL_CUSTOMER_COMMENT."\n".$_POST["comment"]."\n";
		$adm .= "\n".CUSTOMER_FIRST_NAME." ".$_POST["first_name"]."\n".CUSTOMER_LAST_NAME." ".$_POST["last_name"]."\n".CUSTOMER_ADDRESS.": ".$_POST["country"].", ".$_POST["zip"].", ".$_POST["state"].",  ".$_POST["city"].", ".$_POST["address"]."\n".CUSTOMER_PHONE_NUMBER.": ".$_POST["phone"]."\n".CUSTOMER_EMAIL.": ".$_POST["email"];
		phpmailer(CONF_ORDERS_EMAIL, $from, $NOTIFICATION_SUBJECT, $od.$adm);
		
		if(isset($managers_mail) && count($managers_mail) > 0){
			foreach($managers_mail as $key => $manager_mail){
				phpmailer($manager_mail, $from, $NOTIFICATION_SUBJECT, $od.$adm);
			}
		}
		
		unset($_SESSION["gids"]);
		unset($_SESSION["counts"]);
		if(isset($_SESSION["opt"])) unset($_SESSION["opt"]);
		if(isset($_SESSION["newprice"])) unset($_SESSION["newprice"]);
		if(isset($_SESSION['order_reg'])) unset($_SESSION['order_reg']);
		unset($_SESSION["shipping"]);
		unset($_SESSION["get_fast_order"]);

		unset($_SESSION["present"]);
		//			unset($_SESSION["minimal"]);
		unset($_SESSION["discount"]);


		//show order placement result
		if(CONF_CHPU)
			header("Location: http://".CONF_SHOP_URL."/cart/order_placed/");
		else
			header("Location: http://".CONF_SHOP_URL."/index.php?order_placement_result=1");
		exit;
	}
	else //empty shopping cart
	{
		if(CONF_CHPU)
			header("Location: http://".CONF_SHOP_URL."/cart/");
		else
			header("Location: http://".CONF_SHOP_URL."/index.php?shopping_cart=yes");
		exit;
	}
}
else
if(isset($_GET["order_placement_result"])) //show 'order successful' page
{
	$order_info = db_assoc("SELECT orderID, cust_firstname, cust_lastname, cust_email, cust_city, cust_address, cust_phone, comment, M.online_name manager FROM ".ORDERS_TABLE.' as O LEFT JOIN '.MANAGER_TABLE.' as M on O.manager=M.ID  WHERE orderID='.(int)$_SESSION["order_id"]) or die (db_error());

	$q = db_query("SELECT name, Price, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID='".$order_info['orderID']."' ORDER BY id ASC") or die(db_error());
	while($row = db_fetch_row($q)){

		if(substr_count($row[0],ADMIN_DISCOUNT_STRING) > 0){
			$total -= $row[1] * $row[2];
			$tmp = explode(" ",$row[0]);
			$row[4] = "<br /><b>".show_price($row[1] * $row[2])."</b>";
			$row[0] = "<br /><b>".$tmp[0]."</b>";
			$row[1] = "";
			$row[2] = "<br /><b>".$tmp[1]."%</b>";
			$res[] = Array();
			$res[] = $row;
		}
		else
		{
			$row[1] = $row[1] / CURRENCY_val;
			$total += $row[1] * $row[2];
			$row[4] = show_price($row[1] * $row[2]);
			$row[1] = show_price($row[1]);
			$res[] = $row;
		}
	}
	$order_info['total'] = show_price($total);
	$smarty->assign("orderer", $order_info);
	$smarty->assign("order", $res);
	unset($order_info,$res,$row);
	//select all payments
	$q = db_query("SELECT type, payvalue FROM ".PAYMENT_TABLE." LEFT JOIN ".PAYOPTION_TABLE." USING (payID) WHERE enabled='1' AND payoption = 'name'") or die (db_error());

	while($row = db_fetch_row($q)){
		$payment_list['values'][] = $row[0];
		$payment_list['names'][] = $row[1];
	}

	$smarty->assign("payment_list", $payment_list);


	$smarty->assign("main_content_template", "order_place.tpl.html");
}

?>