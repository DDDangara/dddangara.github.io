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
 

	// contact send mail
if (isset($_GET["contact"]) || isset($_GET["send_result"])) //show 'send result' page
  {	
        $f_c = file("./core/aux_pages/contact");
	$out_c = implode("", $f_c);
	$smarty->assign("contact_info", $out_c);
	//calculate a path
	$path = Array();
	if (CONF_CHPU) $row[0] = "contact/";
	else  $row[0] = 'index.php?contact';
	$row[1] = STRING_CONTACT_INFORMATION;
	$path[] = $row;

	$smarty->assign("product_category_path",$path);
	$smarty->assign("send_result", $_GET["send_result"]);
	$smarty->assign("main_content_template", "contact.tpl.html");
  }

if (isset($_POST["send_mail"]))
  {
	//calculate a path
	$path = Array();
	$row[0] = "contact/";
	$row[1] = STRING_CONTACT_INFORMATION;
	$path[] = $row;

	$smarty->assign("product_category_path",$path);

	$Name = $_POST['send_name'];
	$From = $_POST['send_email'];
	$Body = $_POST['send_text'];

		if (($Name=="") or ($From=="") or ($Body=="") or (!preg_match("/[-0-9a-z_]{2,6}/i",$From)) or ($_POST["captcha"]<>$_SESSION["captcha"])) 
			{$send_result=0;}
		   else	{
				$To = CONF_GENERAL_EMAIL;
				$Subj = STRING_MAIL_FROM_SITE;
                                if (DB_CHARSET!='cp1251') 
                                {  
                                    
                                     $Subj = win2utf($Subj);
                                } 
				$mail = "<b>От:</b> ".$Name."<br>\r\n";
				$mail .="<b>Адрес:</b> ".$From."<br>\r\n";
				$mail .="<b>Текст сообщения:</b><br>\r\n".$Body."\r\n";
				if (DB_CHARSET!='cp1251') $mail = win2utf($mail);
                                $from['mail']=CONF_ORDERS_EMAIL;
                                $from['name']='';   
 
                                phpmailer(CONF_GENERAL_EMAIL, $from, $Subj, '',$mail);     
                                $send_result=2;	
                                unset($_POST); 	
			}

	//show sent result
	unset($_SESSION["captcha"]);

	$f = file("./core/aux_pages/contact");
	$out = implode("", $f);
	$smarty->assign("contact_info", $out);
	$smarty->assign("send_result", $send_result);
        $smarty->assign("main_content_template", "contact.tpl.html"); 
	#header("Location: contact/&send_result=".$send_result);
  }

?>