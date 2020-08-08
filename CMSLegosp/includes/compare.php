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
 
if (isset($_GET["check"]) && isset($_GET["uncheck"])) {
	session_start();
	$check = preg_replace('/[^0-9]/', '', $_GET['check']);
	$uncheck = preg_replace('/[^0-9]/', '', $_GET['uncheck']);
	if ($check != 0) {
		$_SESSION["comp"][] = $check;
	} else if ($uncheck != 0) {
		$j = 0;
		for ($i = 0; $i < count($_SESSION["comp"]); $i++) {
			if ($_SESSION["comp"][$i] == $uncheck) {
				$_SESSION["comp"][$i] = 0;
			}
			if ($_SESSION["comp"][$i] != 0) {
				$j++;
			}
		}
	}
	if ($uncheck != 0 && $j == 0) {
		unset($_SESSION["comp"]);
	}
	exit;
}

if (isset($_GET["compare"])) {
	$comp = Array();
	$j = 0;
	$num = count($_SESSION["comp"]);
	for ($i = 0; $i < $num; $i++) {
		if ($_SESSION["comp"][$i] != 0) {
			$q = db_query("SELECT productID, name, customers_rating, Price, picture, big_picture, hurl FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$_SESSION["comp"][$i]) or die (db_error());
			$row = db_fetch_row($q);
			$row[2] = round($row[2]+0.49);
			$row[3] = show_price($row[3]);
			$photo = str_replace(".jpg", "-SC.jpg", $row[4]);
			if (file_exists("./products_pictures/".$row[4])) {
				$row[4] = '<img src="./products_pictures/'.$photo.'" id="dp'.$row[0].'" alt="" />';
			} else {
				$row[4] = '<img src="./products_pictures/nophoto.jpg" height="50" id="dp'.$row[0].'" alt="" />';
			}
			if (CONF_CHPU) {$row[55] = REDIRECT_PRODUCT."/".$row[6];} else {$row[55] = "index.php?productID=".$row[0];}
			
			$sql2 = "SELECT po.* FROM ".PRODUCT_OPTIONS_TABLE." as po,".PRODUCT_OPTIONS_V_TABLE." as pov where po.`optionID`=pov.`optionID` and pov.productID=".$row[0]." group by po.`optionID` order by po.name";
				$p2 = db_arAll($sql2);
				$variant = array();
				foreach ($p2 as $opions) {
					$sql_v = "SELECT ovv.name,pov.* FROM ".PRODUCT_OPTIONS_VAL_TABLE." AS ovv,".PRODUCT_OPTIONS_V_TABLE." AS pov where ovv.variantID = pov.`variantID` AND ovv.optionID =".$opions['optionID']." AND pov.productID =".$row[0]." GROUP BY ovv.`variantID` ORDER BY ovv.name";
					$vn = db_fetch_row(db_query($sql_v));
					$variant[$opions['optionID']]['varname'] = $vn[0];
					$variant[$opions['optionID']]['name'] = $opions["name"];
				}
			
				if ($variant) {
					$rez[$_SESSION["comp"][$i]] = $variant;
				}
			unset($variant, $sql_v);
			$comp[] = $row;
			$j++;
		}
	}

	//print_r("<pre>");print_r($rez);print_r("</pre>");
	$smarty->assign("options", $rez);
	$smarty->assign("comp", $comp);
	$smarty->assign("num_comp", $j);
	$smarty->assign("main_content_template", "compare.tpl.html");
}

if (isset($_SESSION["comp"])) {
	$comp = Array();
	$j = 0;
	$num = count($_SESSION["comp"]);
	for ($i = 0; $i < $num; $i++) {
		if ($_SESSION["comp"][$i] != 0) {
			$q = db_query("SELECT productID, name, customers_rating, Price, picture, big_picture, hurl FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$_SESSION["comp"][$i]) or die (db_error());
			$row = db_fetch_row($q);
			$row[2] = round($row[2]+0.49);
			$row[3] = show_price($row[3]);
			$photo = str_replace(".jpg", "-SC.jpg", $row[4]);
			if (file_exists("./products_pictures/".$row[4])) {
				$row[4] = '<img src="./products_pictures/'.$photo.'" class="c_img" height="50" alt="" />';
			} else {
				$row[4] = '<img src="./products_pictures/nophoto.jpg" class="c_img" height="50" />';
			}
			if (CONF_CHPU) {$row[55] = REDIRECT_PRODUCT."/".$row[6];} else {$row[55] = "index.php?productID=".$row[0];}

			$comp[] = $row;
			$j++;
		}
	}

	//print_r("<pre>");print_r($rez);print_r("</pre>");
	$smarty->assign("options_", $rez);
	$smarty->assign("comp_", $comp);
	$smarty->assign("num_comp_", $j);
}

if (isset($_GET["clear_compare"])) {
	unset($_SESSION["comp"]);
	header("Location: ./index.php?compare");
	exit;
}
?>