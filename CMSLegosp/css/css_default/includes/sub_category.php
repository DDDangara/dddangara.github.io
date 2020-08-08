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
if (!$categoryID) {$categoryID = 0;}
$sq = db_query("SELECT categoryID, name, hurl, products_count FROM ".CATEGORIES_TABLE." WHERE enabled=1 and parent='".$categoryID."' ORDER BY ".CONF_SORT_CATEGORY." ".CONF_SORT_CATEGORY_BY);
$sresult = array();
while ($srow = db_assoc_q($sq)) {
	if ($srow['hurl'] != "" && CONF_CHPU) {$srow['hurl'] = REDIRECT_CATALOG."/".$srow['hurl'];} else {$srow['hurl'] = "index.php?categoryID=".$row['categoryID'];}
	$sresult[] = $srow;
}
$smarty->assign("subcat", $sresult);
?>