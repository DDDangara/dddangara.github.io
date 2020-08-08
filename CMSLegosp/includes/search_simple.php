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
 
function subcateg($categoryID,$out_c)
{
   
   global $categ_id;
   $sql="select categoryID from ".CATEGORIES_TABLE." where enabled=1 and parent=".(int)$categoryID." and parent>0";
   $q=db_query($sql) or die (db_error()); $out=$out_c;
   
   while ($row = db_fetch_row($q))
   {
     
     $categ_id[]=$row[0]; 
     subcateg($row[0],$categ_id);
     
   }
  
   return $categ_id;

}
	// simple search
        if (isset($_GET["search_expanded_ajax"]))
        {
           include("../cfg/ajax_connect.inc.php");
           $word = validate_search_string($_POST['search']);
           $q=db_query("select optionID,name from ".PRODUCT_OPTIONS_TABLE." ORDER BY `sort_order` ASC");
           if (db_num_rows($q)>0)
           {
             $option=array();
             while ($row = db_fetch_row($q))
             {
               $option[$row[0]]['name']=$row[1];
             } 
           $out_a=array(); 
           $out_a[]=$word; $categ_id=array();
           if ($_POST['sub']) 
           {
           $sub_cat=subcateg($word,$out_a);
           
           if (count($sub_cat)>0)
           {
              foreach ($sub_cat as $sub) 
                        $categ_in .=$sub.',';
                        $categ_in=substr($categ_in, 0, strlen($categ_in)-1);  
           } 
           else $categ_in='-1';
           $categ_in=$word.','.$categ_in; 
           }
           else $categ_in=$word; 

           $sql="SELECT POV.variantID,POV.optionID,POV.name FROM ".PRODUCTS_TABLE." as P LEFT JOIN ".PRODUCT_OPTIONS_V_TABLE." as OP USING(productID) LEFT JOIN ".PRODUCT_OPTIONS_VAL_TABLE." AS POV USING(variantID) where P.productID=OP.productID";
           if ($categ_in!=0) $sql .=" and P.categoryID in (".$categ_in.")";
           $q=db_query($sql);
           if (db_num_rows($q)>0)
           {  
             while ($row = db_fetch_row($q))
             {
                $option[$row[1]]['opt'][$row[0]]=$row[2];
             }
             foreach ($option as $key => $value)
              if (!isset($value['opt'])) unset($option[$key]);
              
            
           }
           else $option=array();
           $html=''; 
           if (count($option))
           foreach ($option as $key => $value)
           {
             $html.= '<p><b>'.$value['name'].'</b>';
             if (isset($value['opt'])) 
             {
              $html .='<select name="variant['.$key.']">'; 
              $html .='<option value="0">'.STRING_UNIMPORTANT.'</option>';
               foreach ($value['opt'] as $key2 => $variant)
                $html .='<option value="'.$key2.'">'.$variant.'</option>';  
              $html .='</select>'; 
             }
             $html .='</p>';    
                
           } 
            
           

           echo $html;
           
           }   
           
 
        } 
        else
        {
	if (isset($_GET["inside"])) $smarty->assign("search_in_results", $_GET["inside"]);
        if (isset($_GET["search_expanded"]))
        {
           $category=db_arAll("select categoryID, name from `".CATEGORIES_TABLE."` where `products_count`>0 and `enabled`=1"); 
           $sql="select optionID,name from ".PRODUCT_OPTIONS_TABLE." order by sort_order";
           $options = array(); 
           $q=db_query($sql); 
           while ($row = db_fetch_row($q))
           {
              $options[$row[0]]['name'] = $row[1];   
              $options[$row[0]]['variant'] = db_arAll("select * from ".PRODUCT_OPTIONS_VAL_TABLE." where optionID=".$row[0]." order by sort_order");   
           }  
         
           $smarty->assign("options_search", $options);  
           if  (isset($_POST['search']))  
           {
              
             
             $url="./index.php?search=yes&search_expanded=yes";
             $sql='SELECT P.*,B.name brand_name, B.hurl brand_hurl, count(R.reviewID) count_review FROM `' . PRODUCTS_TABLE . '` AS P LEFT JOIN `' . REVIEW_TABLE . '` as R USING(productID) LEFT JOIN `' . BRAND_TABLE . '` as B USING (brandID)  INNER JOIN `' . CATEGORIES_TABLE . '` AS C ON (P.categoryID = C.categoryID) LEFT JOIN '.PRODUCT_OPTIONS_V_TABLE.' AS POV ON (P.productID = POV.productID) WHERE P.enabled =1 AND C.enabled =1 ' ;
             
             $sql_count .='select count(*) FROM `' . PRODUCTS_TABLE . '` AS P INNER JOIN `' . CATEGORIES_TABLE . '` AS C USING(categoryID) WHERE P.enabled =1 AND C.enabled =1';
              if ($_POST['categorySelect']>0 && is_numeric($_POST['categorySelect'])) 
              {    
                                  
                   $categ=db_r("select categoryID from `".CATEGORIES_TABLE."` where `products_count`>0 and `enabled`=1 and categoryID=".$_POST['categorySelect']);
                   $categ_in=$categ; $out_a=array();
                   if (isset($_POST["search_in_subcategory"]))
                   { 
                     $out_a[]=$categ; $categ_in='';
                     $sub_cat=subcateg($categ,$out_a);
                     if (count($sub_cat)>0)
                     {
                       foreach ($sub_cat as $sub) 
                         $categ_in .=$sub.',';
                        $categ_in=substr($categ_in, 0, strlen($categ_in)-1);  
                     } 
                     else $categ_in='-1';
                   }  
                   $categ_in=$categ.','.$categ_in;
                   $sql .=' and C.categoryID in ('.$categ_in.')';
                   $sql_count .= ' and C.categoryID in ('.$categ_in.')';
              }
               
	      $search = explode(" ", $_POST["keyword"]);
              $where  = 'and (';
	       foreach ($search as $value) {
	          $value=validate_search_string($value);
		  $where .= '(P.name like \'%' . $value . '%\' or P.description like \'%' . $value . '%\' or P.product_code like \'%' . $value . '%\' or P.brief_description like \'%' . $value . '%\') and ';
	       }
	       $sql .= substr($where, 0, strlen($where) - 5) . ')';
	       $sql_count .= substr($where, 0, strlen($where) - 5) . ')';
        
            if (isset($_POST['variant']))
            {   
              $_POST['variant']=array_unique($_POST['variant']);
              $_POST['variant']=array_values($_POST['variant']);
              if (count($_POST['variant'])>0 && $_POST['variant'] != array(0=>'0'))
             {
               $sql .=' and POV.variantID in ('.add_in($_POST['variant']).')';
               $sql_count .=' and POV.variantID in ('.add_in($_POST['variant']).')';
             }  
            }
            $price_from=str_replace(',','.',$_POST['search_price_from']);
            if (is_numeric($price_from)) $sql .=" and P.Price>=".$price_from;
            $price_to=str_replace(',','.',$_POST['search_price_to']);
            if (is_numeric($price_to)) $sql .=" and P.Price<=".$price_to;
            if (!isset($_POST['page'])) $page=1;
            else  $page=$_POST['page'];
            $sql .=' GROUP BY `P`.`productID` ';
            
            $q=db_query($sql);
            $sql.=' ORDER BY P.' . $_SESSION['sort'] . ' ' . $_SESSION['order'].' LIMIT '.(CONF_PRODUCTS_PER_PAGE*($page-1)).','.CONF_PRODUCTS_PER_PAGE;
            $result=products_to_show($sql);
            $smarty->assign("products_to_show", $result['result']);
            $smarty->assign("products_to_show_count", count($result['result']));
	    $smarty->assign("products_found", $g_search_count);
            $idp = '(' . substr($result['id_products'], 0, strlen($result['id_products']) - 1) . ')';
            $options_info=options_list($idp,CONF_SHOW_PRODUCT_VARIANTS_INSTOCK);
            $smarty->assign("options",$options_info['options']);
            $smarty->assign("p_default", $options_info['pic_default']);
            $smarty->assign("post", $_POST);
            $navigator = ""; 
           }
           $smarty->assign("search_categories", All_Categories(0,0));
           $smarty->assign("category", $category);
           $smarty->assign("main_content_template", "search_expanded.tpl.html");
           
        } 
	elseif (isset($_GET["searchstring"])) //make a simple search
	{
                 
		//prepare search string
		$_GET["searchstring"] = trim($_GET["searchstring"]);
		$products_search = array();
		$cats_search = array();
		$g_search_count = 0;

		//explode string to a set separate of words
		$search = explode(" ",$_GET["searchstring"]);

		$result=array();
		$r = array();
		$i = 0;
		$k = 0;

		if ($_GET["searchstring"])
		{
			//sort options

			$sort_options['sort_values'] = Array(
						"index.php?searchstring=".$_GET["searchstring"]."  &amp;sort=name&amp;order=asc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=name&amp;order=desc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=Price&amp;order=asc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=Price&amp;order=desc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=customers_rating&amp;order=desc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=in_stock&amp;order=desc",
						"index.php?searchstring=".$_GET["searchstring"]."&amp;sort=product_code&amp;order=asc"
					    );
			$sort_options['sort_names'] = Array(ADMIN_SORT_BY_NAME_ASC, ADMIN_SORT_BY_NAME_DESC, ADMIN_SORT_BY_PRICE_ASC, ADMIN_SORT_BY_PRICE_DESC, ADMIN_SORT_BY_RATING, ADMIN_SORT_BY_IN_STOCK, ADMIN_SORT_BY_CODE);
			$sort_options['sort_selected'] = "index.php?searchstring=".$_GET["searchstring"]."&amp;sort=".$_SESSION["sort"]."&amp;order=".$_SESSION["order"];

			$smarty->assign("sort_options", $sort_options);
                        $search = explode(" ", $_GET["searchstring"]);
			$where  = '(';
			foreach ($search as $value) {
			    $value=validate_search_string($value);
			    $where .= '(P.name like \'%' . $value . '%\' or P.description like \'%' . $value . '%\' or P.product_code like \'%' . $value . '%\' or P.brief_description like \'%' . $value . '%\') and ';
			}
			$where = substr($where, 0, strlen($where) - 5) . ')';
			if (CONF_SHOW_PRODUCT_INSTOCK == 0) 
			$where .= ' and in_stock>0';
			$g_search_count = db_r('select count(*) FROM `' . PRODUCTS_TABLE . '` AS P INNER JOIN `' . CATEGORIES_TABLE . '` AS C USING(categoryID) WHERE P.enabled =1 AND C.enabled =1 and ' . $where);
			$sql   = 'SELECT P.*,B.name brand_name, B.hurl brand_hurl, count(R.reviewID) count_review FROM `' . PRODUCTS_TABLE . '` AS P LEFT JOIN `' . REVIEW_TABLE . '` as R USING(productID) LEFT JOIN `' . BRAND_TABLE . '` as B USING (brandID)  INNER JOIN `' . CATEGORIES_TABLE . '` AS C ON (P.categoryID = C.categoryID) WHERE P.enabled =1 AND C.enabled =1 and ' . $where . ' GROUP BY `P`.`productID` ORDER BY P.' . $_SESSION['sort'] . ' ' . $_SESSION['order'];
                        if ($offset>$g_search_count) $offset = 0;
			
                        if (!isset($_GET['show_all'])) $sql.= ' LIMIT ' . $offset . ' , ' . CONF_PRODUCTS_PER_PAGE;
                        else $offset = "show_all";
                        $result=products_to_show($sql);
        $smarty->assign("products_to_show", $result['result']);
        $smarty->assign("products_to_show_count", count($result['result']));
	$smarty->assign("products_found", $g_search_count);
        $idp = '(' . substr($result['id_products'], 0, strlen($result['id_products']) - 1) . ')';
        unset($result); 
        $options_info=options_list($idp,CONF_SHOW_PRODUCT_VARIANTS_INSTOCK);
        $smarty->assign("options",$options_info['options']);
        $smarty->assign("p_default", $options_info['pic_default']);
        unset($options_info, $idp);

			

			//navigation
			$search_navigator = "";
			showNavigator($g_search_count, $offset, CONF_PRODUCTS_PER_PAGE, "index.php?searchstring=".$_GET["searchstring"]."&",$search_navigator);
			$smarty->assign("search_navigator",$search_navigator);

			
		}
         if (isset($_GET['offset']) && $offset !=$_GET['offset'])  include_once(ROOT_DIR.'/core/core_404.php');  
 		
		$smarty->assign("searchstring", $_GET["searchstring"]);
		$smarty->assign("main_content_template", "search_simple.tpl.html");
	}
	else
	{
	$smarty->assign("searchstring", "");
	}
        }  
      
?>
