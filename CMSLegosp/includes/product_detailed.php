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
 
    if (isset($_GET['productID']) && isset($_GET['variants']) && isset($_GET['var_change']))
	{
	   include("../cfg/ajax_connect.inc.php");
	   if (!is_numeric($_GET['productID'])) return false;
	   if (!is_numeric($_GET['var_change'])) return false;
	   $variants = explode(",", $_GET['variants']);
	   foreach ($variants as $key => $var) if (!is_numeric($var)) unset($variants[$key]);
	   $rezult=array();
           $r=db_assoc('select (select picture from `'.PRODUCT_OPTIONS_V_TABLE.'` where `productID`='.(int)$_GET['productID'].' and variantID='.(int)$_GET['var_change'].') Picture, P.Price+IFNULL((select sum(`price_surplus`) from `'.PRODUCT_OPTIONS_V_TABLE.'` where `productID`='.(int)$_GET['productID'].' and `variantID` in ('.add_in($variants).')),0) Price  from '.PRODUCTS_TABLE.' as P where productID='.(int)$_GET['productID']);
	   $r['Price'] = show_price(round($r['Price'] / CURRENCY_val, 2));
           echo json_safe_encode($r);
	   exit;
	}
	if (isset($_POST["vote"]) && isset($productID)) //vote for a product
	{
		if (!isset($_SESSION["vote_completed"][ $productID ]) && isset($_POST["mark"]) && $_POST["mark"])
		{
			$q = db_query("UPDATE ".PRODUCTS_TABLE." SET customers_rating=(customers_rating*customer_votes+'".(int)$_POST["mark"]."')/(customer_votes+1), customer_votes=customer_votes+1 WHERE productID=".(int)$productID) or die (db_error());
		}
		$_SESSION["vote_completed"][ $productID ] = 1;
	}
        
        if ($_POST['review'] && isset($productID))
        {
         if ((isset($_SESSION['cust_id']) && !isset($_POST["captcha"])) || ($_POST["captcha"] == $_SESSION['captcha']))  
         {
           unset($_SESSION['captcha']);
           if(isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["review"]))
           {
                      
                        if (!CONF_REVIEW_LINK && preg_match("#(https?|ftp)://\S+[^\s.,> )\];'\"!?]#", $_POST["review"])) 
                        {
                            $jstext=PRODUCT_REVIEW_ADD_NOT_LINK;
                            if (DB_CHARSET!='cp1251') $jstext=win2utf($jstext); 
                            echo "<script language=\"JavaScript\">alert('".$jstext."');</script>";  
                            return FALSE;
                               
                        }
                        
                        $new_review = str_replace("\n","<br />\n",$_POST["review"]);
                        $new_review = preg_replace("'<script[^>]*?>.*?</script>'si","",$new_review);
			$areview['productID']=(int)$productID;  
                        $areview['username']=preg_replace("'<script[^>]*?>.*?</script>'si","",$_POST["name"]);
                        $areview['email']=preg_replace("'<script[^>]*?>.*?</script>'si","",$_POST["email"]);
                        $areview['review']=$new_review;
                        add_field(REVIEW_TABLE, $areview); 
                        $smarty->assign("review_saved", "yes");
                        if (CONF_REVIEW_MODER) echo "<script language=\"JavaScript\">alert('".PRODUCT_REVIEW_ADD_OK_MODER."');</script>"; 
                        else echo  ""; 
                        

		        //notification for administrator
		        $adm .= "\n".CUSTOMER_FIRST_NAME.": ".$_POST["name"]."\n".CUSTOMER_EMAIL.": ".$_POST["email"]."\n".PRODUCT_REVIEW_MESSAGE.":\n".$_POST["review"];
                        $Subj=PRODUCT_REVIEW_NOTIFICATION_SUBJECT;
                        if (DB_CHARSET!='cp1251')
                        { 
                          $adm = win2utf($adm);  
                          $Subj= win2utf($Subj);   
                        }  
                        $from['mail']=CONF_GENERAL_EMAIL;
                        $from['name']=CONF_SHOP_NAME;   
                        phpmailer(CONF_GENERAL_EMAIL, $from, $Subj, $adm);   
 		        unset($_SESSION['securimage_code_value']['default']);
		//header("Location: ".$_SERVER['HTTP_REFERER']);
		        $smarty->assign("main_content_template", "product_detailed.tpl.html");

           }
         }  
         
        }  
	if (isset($productID) && $productID>0)
	{

			$smarty->assign("main_content_template", "product_detailed.tpl.html");
                        $sql = 'SELECT P.*,P.Price real_PRICE,P.Price+IFNULL((select sum(`price_surplus`) from `'.PRODUCT_OPTIONS_V_TABLE.'` where `productID`='.(int)$productID.' and `default`=1),0) Price,B.name brand_name, B.hurl brand_hurl, B.comment brand_comment, count(R.reviewID) count_review, C.hurl category_hurl FROM `' . PRODUCTS_TABLE . '` AS P INNER JOIN `' . CATEGORIES_TABLE . '` AS C USING (categoryID) LEFT JOIN `' . REVIEW_TABLE . '` as R USING(productID) LEFT JOIN `' . BRAND_TABLE . '` as B USING (brandID)  WHERE P.`enabled` =1 AND C.`enabled` =1 and productID='.(int)$productID;
                        $result=products_to_show($sql);
			$result=$result['result'][0];
                        $smarty->assign("product_info", $result);
			$smarty->assign("meta_title", $result['meta_title']);
			$smarty->assign("meta_keywords", $result['meta_keywords']);
			$smarty->assign("meta_desc", $result['meta_desc']);
			$smarty->assign("rel_canonical", $result['canonical']);
			$path_p=array();
			$path_p[0]=$result['hurl'];
			$path_p[1]=$result['name'];
			
			//searching accompanyID

            $sql='select P.*,Price real_PRICE,P.Price+IFNULL((select sum("price_surplus") from "'.PRODUCT_OPTIONS_V_TABLE.'" where "productID"='.(int)$productID.' and "default"=1),0) Price from '.PRODUCTS_TABLE.' as P WHERE enabled=1 and productID in ('.$result['accompanyID'].')';
			if (CONF_SHOW_PRODUCT_INSTOCK == 0) $sql .= ' and P.in_stock>0';
			$result=products_to_show($sql);
			$smarty->assign("accompany",$result['result']);
			unset($result);
			//searching reviews
                        $sql="SELECT date_time,username,review FROM ".REVIEW_TABLE." WHERE productID=".$productID;
                        if (CONF_REVIEW_MODER) $sql .=' and moder=1 '; 
                        $sql .=" ORDER BY date_time DESC";
			$smarty->assign("reviews", db_arAll($sql));
			unset($sql);
			//product_thumb
			$smarty->assign("product_thumb", db_arAll('SELECT picture, description FROM '.THUMB_TABLE.' WHERE productID='.$productID));
			//tags
			$smarty->assign("product_tags",db_arAll('select * from '.TAGS_TABLE.' where pid='.$productID));
			
			{
				
                                $variant=array(); 
								
								$options_info=options_list('('.$productID.')',CONF_SHOW_PRODUCT_VARIANTS_INSTOCK);
                                $smarty->assign("options",$options_info['options'][$productID]);
                                $smarty->assign("p_default", $options_info['pic_default']); 
                                                                 


			        //calculate a path to the category
				$path = array();
				$curr = $categoryID;
				do
				{
					$q = db_query("SELECT parent, name, hurl FROM ".CATEGORIES_TABLE." WHERE categoryID='".$curr."'") or die (db_error());
					$row = db_fetch_row($q);
					if ($row[2] != "") {$tmp = REDIRECT_CATALOG."/".$row[2];} else {$tmp = "index.php?categoryID=".$curr;}

					$curr = $row[0]; //get parent ID
					$row[0] = $tmp;
					$path[] = $row;
				} while ($curr);
				//now reverse $path
				$path = array_reverse($path);
				$path[] = $path_p;
				$smarty->assign("product_category_path",$path);
				unset($path_p,$row,$path);

			        
				

                	        
                    		
                       
			}
	}
#$smarty->debugging = true;

?>