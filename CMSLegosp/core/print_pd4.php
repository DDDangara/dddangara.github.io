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
 
include("../cfg/company.inc.php");
include("../cfg/general.inc.php");

class formpd4{

function formpd4($param){
	if(isset($param)){
		$this->firm = $param['firm'];
		$this->innpol = $param['innpol'];
		$this->nsch = $param['nsch'];
		$this->bank = $param['bank'];
		$this->bik = $param['bik'];
		$this->korsch = $param['korsch'];
		$this->nazn = $param['nazn'];
		$this->kodplat = $param['kodplat'];
		$this->fio = $param['fio'];
		$this->address = $param['address'];
		$this->summ = $param['summ'];
		$this->uslug = $param['uslug'];
		$this->date = $param['date'];
		}
	}

function printform(){
 include("../cfg/connect.inc.php"); 
 include("../cfg/functions.php");
$text ="<html lang=ru>";
$text .="<head>";
$text .="<title>Форма ПД-4</title>";
$text .="<meta http-equiv='Content-Type' content='text/html; charset=windows-1251'>";
//#############################################STYLES
$text .="<style>";
$text .="body {font-family: sans-serif;font-size: 8pt;background-color: white;}";
$text .="table {	font-family: sans-serif;font-size: 8pt;}";
$text .=".ramka {border-top: black 1px dotted;border-bottom: black 1px dotted;border-left: black 1px dotted;border-right: black 1px dotted;}";
$text .=".linev {border-left: black 2px solid;}";
$text .=".lineh {border-bottom: black 2px solid;}";
$text .=".linevh {border-bottom: black 2px solid;border-left: black 2px solid;}";
$text .=".t10b {font-weight: bold;font-size: 10pt;font-family: 'Times New Roman', serif;}";
$text .=".h8b {font-weight: bold;font-size: 8pt;font-family: Arial, sans-serif;border-bottom: black 1px solid;text-align: center;}";
$text .=".line_b		{BORDER-BOTTOM: black 1px solid; FONT-SIZE: 7pt; FONT-WEIGHT: bold}";
$text .=".line_t		{BORDER-TOP: black 1px solid; FONT-SIZE: 7pt; FONT-WEIGHT: bold}";
$text .=".line_r {border-right: black 1px solid;font-weight: bold;}";
$text .=".line_l		{BORDER-LEFT: black 1px solid; FONT-SIZE: 7pt; FONT-WEIGHT: bold}";
$text .=".line_lbt {border-left: black 1px solid;border-bottom: black 1px solid;border-top: black 1px solid;font-weight: bold;}";
$text .=".line_lbtr {border-left: black 1px solid;border-bottom: black 1px solid;border-top: black 1px solid;border-right: black 1px solid;font-weight: bold;}";
$text .=".t6n		{FONT-SIZE: 6.5pt; FONT-FAMILY: 'Times New Roman', serif}";
$text .=".t7n		{FONT-SIZE: 7.5pt; FONT-FAMILY: 'Times New Roman', serif}";
$text .=".t8n		{FONT-SIZE: 8.5pt; FONT-FAMILY: 'Times New Roman', serif}";
$text .=".spc		{FONT-SIZE: 1pt}";
//#############################################END STYLES
$text .="</style>";

$text .="</head>";
$text .="<body onload=\"print(); return false;\">";

//#############################################FORM

$text .="<table border=0 cellspacing=0 cellpadding=0 align=center><tr><td>";
$text .="<table class=ramka border=0 cellspacing=0 cellpadding=0 align=center>";
$text .="<tr>";
$text .="<td class=lineh align=center width=188 height=245>";
$text .="<table border=0 cellpadding=0 cellspacing=0>";
$text .="<tr><td height=100 class=t10b valign=top align=center>И з в е щ е н и е</td></tr>";
$text .="<tr><td height=100 class=t10b valign=bottom align=center>Кассир</td></tr>";
$text .="</table>";
$text .="</td>";
$text .="<td width=16 class=linevh height=245>&nbsp;</td>";
$text .="<td class=lineh height=245>";
$text .="<table width=477 border=0 cellpadding=0 cellspacing=0 align=center style='height: 245px'>";
$text .="<tr>";
$text .="<td height=40>";
$text .="<table width='100%' align=center cellspacing=0 border=0 cellpadding=0>";
$text .="<tr>";
$text .="<td><img src='http://".CONF_SHOP_URL."/core/printforms/sblogo.gif' width=120 height=26 alt='Sberbank logo'></td>";
$text .="<td class=t6n align=right valign=middle><i>Форма № ПД-4</i></td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td class=h8b valign=bottom align=center>".$this->firm."</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td height=10 class=t6n valign=top align=center>(наименование получателя платежа)</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 width='100%' cellpadding=0>";
$text .="<tr>";
$text .="<td width='30%' valign=bottom><table class=line_r border=0 cellspacing=0 width='100%' cellpadding=0><tr>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[0]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[1]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[2]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[3]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[4]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[5]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[6]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[7]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[8]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[9]."</td>";
$text .="</tr></table></td><td width='10%' valign=bottom>&nbsp;</td>";
$text .="<td width='60%' valign=bottom>";

$text .="<table class=line_r border=0 cellspacing=0 cellpadding=0 width='100%'><tr>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[0]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[1]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[2]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[3]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[4]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[5]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[6]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[7]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[8]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[9]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[10]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[11]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[12]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[13]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[14]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[15]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[16]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[17]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[18]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[19]."</td></tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td valign=top class=t6n align=center>(ИНН получателя платежа)</td>";
$text .="<td valign=top class=t6n>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(номер счета получателя платежа)</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td width=10 class=t8n valign=bottom>в</td>";
$text .="<td class=h8b align=center valign=bottom>".$this->bank."</td>";
$text .="<td width=40 class=t8n align=right valign=bottom>БИК&nbsp;</td>";
$text .="<td width='27%' valign=bottom>";
$text .="<table class=line_r border=0 cellspacing=0 width='100%' cellpadding=0><tr>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[0]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[1]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[2]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[3]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[4]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[5]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[6]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[7]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[8]."</td>";
$text .="</tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(наименование банка получателя платежа)</td>";
$text .="<td>&nbsp;</td>";
$text .="<td>&nbsp;</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t7n valign=bottom>Номер&nbsp;кор./сч.&nbsp;банка&nbsp;получателя&nbsp;платежа</td>";
$text .="<td width='60%' valign=bottom>";
$text .="<table class=line_r border=0 cellspacing=0 cellpadding=0 width='100%'><tr>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[0]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[1]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[2]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[3]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[4]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[5]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[6]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[7]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[8]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[9]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[10]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[11]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[12]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[13]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[14]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[15]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[16]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[17]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[18]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[19]."</td></tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=h8b valign=bottom width='55%'>".$this->nazn."</td>";
$text .="<td width='5%'>&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->kodplat."</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td valign=top class=t6n align=center>(наименование платежа)</td>";
$text .="<td>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(номер лицевого счета (код) плательщика)</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Ф.И.О&nbsp;плательщика&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->fio."</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Адрес&nbsp;плательщика&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->address."</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Сумма&nbsp;платежа&nbsp;</td>";
 $sum = explode(".", $this->summ);
 $usl = explode(".", $this->uslug);
$text .="<td class=h8b valign=bottom width='8%'>".$sum[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$sum[1]."</td>";

$text .="<td class=t8n valign=bottom width='1%'>&nbsp;коп.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Сумма&nbsp;платы&nbsp;за&nbsp;услуги&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$usl[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$usl[1]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;коп.</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='5%'>Итого&nbsp;</td>";
 $sum = explode(".", $this->summ);
 $usl = explode(".", $this->uslug);
 $dat = explode(".", $this->date);
$r =(int)($sum[0]+$usl[0]);
$k =(int)($sum[1]+$usl[1]);
if($k>=100){$r++;$k = (int)($sum[1]+$usl[1])-100;}
$text .="<td class=h8b valign=bottom width='8%'>&nbsp;</td>"; //".$r."
$text .="<td class=t8n valign=bottom width='5%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>&nbsp;</td>"; //".$k."
$text .="<td class=t8n valign=bottom width='5%'>&nbsp;коп.&nbsp;</td>";
$text .="<td class=t8n valign=bottom width='20%' align=right>&nbsp;'&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$dat[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;'&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='20%'>&nbsp;".$this->formatdate()."&nbsp;</td>";
$text .="<td class=t8n valign=bottom width='4%'>&nbsp;20&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='3%'>".$dat[2]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;г.</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td class=t7n valign=bottom style='text-align: justify'>С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за&nbsp;услуги банка,&nbsp;ознакомлен&nbsp;и&nbsp;согласен.</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t6n valign=bottom width='50%'>&nbsp;</td>";
$text .="<td class=t7n valign=bottom width='1%'><b>Подпись&nbsp;плательщика&nbsp;</b></td>";
$text .="<td class=h8b valign=bottom width='40%'>&nbsp;</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr><td height=1 class=spc>&nbsp;</td></tr>";
$text .="</table>";
$text .="</td>";
$text .="<td width=16 class=lineh height=245>&nbsp;</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td height=285 class=t10b valign=bottom width=188 align=center>Квитанция<br><br>Кассир<br>&nbsp;</td>";
$text .="<td height=285 width=16 class=linev>&nbsp;</td>";
$text .="<td height=285 valign=top> ";
$text .="<table width=477 border=0 cellpadding=0 cellspacing=0 align=center style='height: 285px'>";
$text .="<tr>";
$text .="<td height=30 class=h8b valign=bottom align=center>".$this->firm."</td>";
$text .="</tr>";
$text .="<tr> ";
$text .="<td height=10 class=t6n valign=top align=center>(наименование получателя платежа)</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 width='100%' cellpadding=0>";
$text .="<tr>";
$text .="<td width='30%' valign=bottom><table class=line_r border=0 cellspacing=0 width='100%' cellpadding=0><tr>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[0]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[1]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[2]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[3]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[4]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[5]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[6]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[7]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[8]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->innpol[9]."</td>";
$text .="</tr></table></td><td width='10%' valign=bottom>&nbsp;</td>		<td width='60%' valign=bottom>";

$text .="<table class=line_r border=0 cellspacing=0 cellpadding=0 width='100%'><tr>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[0]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[1]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[2]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[3]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[4]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[5]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[6]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[7]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[8]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[9]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[10]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[11]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[12]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[13]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[14]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[15]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[16]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[17]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[18]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->nsch[19]."</td></tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td valign=top class=t6n align=center>(ИНН получателя платежа)</td>";
$text .="<td valign=top class=t6n>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(номер счета получателя платежа)</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td width=10 class=t8n valign=bottom>в</td>";
$text .="<td class=h8b align=center valign=bottom>".$this->bank."</td>";
$text .="<td width=40 class=t8n align=right valign=bottom>БИК&nbsp;</td>";
$text .="<td width='27%' valign=bottom>";
$text .="<table class=line_r border=0 cellspacing=0 width='100%' cellpadding=0><tr>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[0]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[1]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[2]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[3]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[4]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[5]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[6]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[7]."</td>";
$text .="<td height=10 class=line_lbt width='10%' align=center>".$this->bik[8]."</td>";
$text .="</tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(наименование банка получателя платежа)</td>";
$text .="<td>&nbsp;</td>";
$text .="<td>&nbsp;</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t7n valign=bottom>Номер&nbsp;кор./сч.&nbsp;банка&nbsp;получателя&nbsp;платежа</td>";
$text .="<td width='60%' valign=bottom>";
$text .="<table class=line_r border=0 cellspacing=0 cellpadding=0 width='100%'><tr>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[0]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[1]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[2]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[3]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[4]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[5]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[6]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[7]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[8]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[9]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[10]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[11]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[12]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[13]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[14]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[15]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[16]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[17]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[18]."</td>";
$text .="<td height=10 class=line_lbt width='5%' align=center>".$this->korsch[19]."</td></tr></table>";
$text .="</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=h8b valign=bottom width='55%'>".$this->nazn."</td>";
$text .="<td width='5%'>&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->kodplat."</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td valign=top class=t6n align=center>(наименование платежа)</td>";
$text .="<td>&nbsp;</td>";
$text .="<td valign=top class=t6n align=center>(номер лицевого счета (код) плательщика)</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Ф.И.О&nbsp;плательщика&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->fio."</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Адрес&nbsp;плательщика&nbsp;</td>";
$text .="<td class=h8b valign=bottom>".$this->address."</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='1%'>Сумма&nbsp;платежа&nbsp;</td>";
 $sum = explode(".", $this->summ);
 $usl = explode(".", $this->uslug);
$text .="<td class=h8b valign=bottom width='8%'>".$sum[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$sum[1]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;коп.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Сумма&nbsp;платы&nbsp;за&nbsp;услуги&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$usl[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$usl[1]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;коп.</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t8n valign=bottom width='5%'>Итого&nbsp;</td>";
 $sum = explode(".", $this->summ);
 $usl = explode(".", $this->uslug);
 $dat = explode(".", $this->date);
$r =(int)($sum[0]+$usl[0]);
$k =(int)($sum[1]+$usl[1]);
if($k>=100){$r++;$k = (int)($sum[1]+$usl[1])-100;}
$text .="<td class=h8b valign=bottom width='8%'>&nbsp;</td>"; // ".$r."
$text .="<td class=t8n valign=bottom width='5%'>&nbsp;руб.&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>&nbsp;</td>"; //".$k."
$text .="<td class=t8n valign=bottom width='5%'>&nbsp;коп.&nbsp;</td>";
$text .="<td class=t8n valign=bottom width='20%' align=right>&nbsp;'&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='8%'>".$dat[0]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;'&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='20%'>&nbsp;".$this->formatdate()."&nbsp;</td>";
$text .="<td class=t8n valign=bottom width='4%'>&nbsp;20&nbsp;</td>";
$text .="<td class=h8b valign=bottom width='3%'>".$dat[2]."</td>";
$text .="<td class=t8n valign=bottom width='1%'>&nbsp;г.</td>";
$text .="</tr>";
$text .="</table>";

$text .="</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td class=t7n valign=bottom style='text-align: justify'>С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за&nbsp;услуги банка,&nbsp;ознакомлен&nbsp;и&nbsp;согласен.</td>";
$text .="</tr>";
$text .="<tr>";
$text .="<td>";
$text .="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
$text .="<tr>";
$text .="<td class=t6n valign=bottom width='50%'>&nbsp;</td>";
$text .="<td class=t7n valign=bottom width='1%'><b>Подпись&nbsp;плательщика&nbsp;</b></td>";
$text .="<td class=h8b valign=bottom width='40%'>&nbsp;</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td>";
$text .="</tr>";
$text .="<tr><td height=1 class=spc>&nbsp;</td></tr>";
$text .="</table>";
$text .="</td>";
$text .="<td width=16>&nbsp;</td>";
$text .="</tr>";
$text .="</table>";
$text .="</td></tr>";
$text .="<tr><td>";
$text .="</td></tr></table>";

$text .="</body>";
$text .="</html>";

if (DB_CHARSET!='cp1251') $text=win2utf($text);  
echo $text;

} //print

function formatdate(){
	$d = explode(".",$this->date);
	if($d[1]=="01"){return "января";}
	if($d[1]=="02"){return "февраля";}
	if($d[1]=="03"){return "марта";}
	if($d[1]=="04"){return "апреля";}
	if($d[1]=="05"){return "мая";}
	if($d[1]=="06"){return "июня";}
	if($d[1]=="07"){return "июля";}
	if($d[1]=="08"){return "августа";}
	if($d[1]=="09"){return "сентября";}
	if($d[1]=="10"){return "октября";}
	if($d[1]=="11"){return "ноября";}
	if($d[1]=="12"){return "декабря";}
	}
};


$param = array(
	firm => COMPANY_NAME, 
	innpol => COMPANY_INN, 
	nsch => COMPANY_RS, 
	bank => COMPANY_BANK, 
	bik => COMPANY_BANK_BIK, 
	korsch => COMPANY_BANK_KOR, 
	nazn => $_POST['sbrf_comment'], 
	kodplat => '', 
	fio => $_POST['sbrf_fio'], 
	address => $_POST['sbrf_adress'],
	summ => $_POST['sbrf_summ'],
	uslug => '',
	date => '');


$pd4 = new formpd4($param);
$pd4 -> printform();

?>