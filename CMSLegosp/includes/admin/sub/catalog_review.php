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
 

	//catalog: products extra parameters list

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "review"))
	{
		if (isset($_GET["save_successful"])) //save extra product options
			$smarty->assign("save_successful", ADMIN_UPDATE_SUCCESSFUL);
		else
			$smarty->assign("save_successful", "");

		if (isset($_POST["save_review"])) //save extra product options
		{
                    
                    if (isset($_POST['reviews']) && $_POST['action']) 
                    {
                     
                     if ($_POST['action']=='deletes')
                     foreach ($_POST['reviews'] as $review_id)
                       db_query('delete FROM '.REVIEW_TABLE.' where reviewID='.(int)$review_id);
                     else
                     foreach ($_POST['reviews'] as $review_id)
                     {
                       $update=array(); 
                       $update['moder']=1;  
                       update_field(REVIEW_TABLE,$update,"reviewID=".(int)$review_id);
                     } 
                     header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=review"); 
                    }
                     else
			foreach ($_POST as $key => $val)
			{
			  if (strstr($key, "review_")) //update price
			  {
                                
				db_query("UPDATE ".REVIEW_TABLE." SET review='$val' WHERE reviewID=".str_replace("review_","",$key)) or die (db_error());
			  }
			}
		    header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=review&save_successful=yes&review=".$_POST["save_review"]);
		}

		if (isset($_GET["delete"]) && $_GET["delete"])
		{
			db_query("DELETE FROM ".REVIEW_TABLE." WHERE reviewID='".$_GET["delete"]."'");
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=review&review=".$_POST["save_review"]);
		}
                if (isset($_GET["approved"]) && $_GET["approved"])
                {
                    $update['moder']=1;
                    update_field(REVIEW_TABLE,$update,"reviewID=".(int)$_GET["approved"]);
                    header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=review");
                } 
		if (isset($_GET["review"]) && $_GET["review"])
		{
			$result = db_arAll("SELECT review.*,products.name products_name, review.*,products.thumbnail products_thumbnail, review.*,products.hurl products_hurl FROM ".REVIEW_TABLE." as review  LEFT JOIN ".PRODUCTS_TABLE." as products on (review.productID=products.productID) WHERE products.productID=".(int)$_GET["review"]." ORDER BY date_time DESC");
			$smarty->assign("reviews", $result);
		}
		else
		{
			//now select all product reviews
			$result = db_arAll("SELECT review.*,products.name products_name, products.hurl products_hurl FROM ".REVIEW_TABLE." as review  LEFT JOIN ".PRODUCTS_TABLE." as products on (review.productID=products.productID) ORDER BY date_time DESC");
			$smarty->assign("savedID", "");
			$smarty->assign("reviews", $result);
		}

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "catalog_review.tpl.html");
	}

?>