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
 
	include_once("../cfg/ajax_connect.inc.php");
	$json_data = Array();
    //json var constants
        $json_data['CONF_COLOR_SCHEME'] = CONF_COLOR_SCHEME;
	$json_data['CONF_SCROLL_HITS'] = (int)CONF_SCROLL_HITS;
	$json_data['CONF_HITS_FRIQ'] = (int)CONF_HITS_FRIQ;
	$json_data['CONF_HITS_SPEED'] = (int)CONF_HITS_SPEED;
	$json_data['REVIEW_SAVED'] = isset($_POST["review"]) ? 1 : 0;
	$json_data['ERROR_INPUT_NAME'] = ERROR_INPUT_NAME;
	$json_data['ERROR_INPUT_CITY'] = ERROR_INPUT_CITY;
	$json_data['ERROR_INPUT_EMAIL'] = ERROR_INPUT_EMAIL;
	$json_data['ERROR_INPUT_PHONE'] = ERROR_INPUT_PHONE;
        $json_data['ERROR_INPUT_LETTERSONLY'] = 'hhhhh';
	$json_data['QUESTION_UNSUBSCRIBE'] = QUESTION_UNSUBSCRIBE;
	$json_data['PAGE_LANG'] = "ru";
        $json_data['CURRENCY_val'] = CURRENCY_val;

	#print_r($json_data);

	header("Content-type: application/x-javascript");
	echo "var ConstJS = {\"constants\":".json_encode_win($json_data)."};";

?>