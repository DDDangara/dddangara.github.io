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
	if (!strcmp($sub, "import"))
	{   
          @set_time_limit(528); 
	  function get_max_array($a) {
	  if (count($a))
	  {
            arsort($a);
            $max = current($a);
            return current(array_filter($a, create_function('$x', 'return $x == '.$max.';')))+1;
            
          } else return -1; 
        }
        $letters = range('A', 'Z');

            if (isset($_POST["export"])) 
            {  
                require_once ROOT_DIR.'/core/excel/Classes/PHPExcel.php';
                require_once ROOT_DIR.'/core/excel/Classes/PHPExcel/IOFactory.php';
                $objPHPExcel = new PHPExcel();
                
                if (isset($_POST["type"]) && $_POST["type"]=='csv')  
                {
                  $exportfile='export_'.date("dmy").'.csv';
                 
                  $row=1;
                  $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'productID')
                              ->setCellValue('B'.$row, 'product_code')
                              ->setCellValue('C'.$row, 'name')
                              ->setCellValue('D'.$row, 'Price')
                              ->setCellValue('E'.$row, 'in_stock')
                              ->setCellValue('F'.$row, 'picture')
                              ->setCellValue('G'.$row, 'thumbnail')
                              ->setCellValue('H'.$row, 'big_picture')
                              ->setCellValue('I'.$row, 'brand name')
                              ->setCellValue('J'.$row, 'categoryID')  
                              ->setCellValue('K'.$row, 'brief_description')  
                              ->setCellValue('L'.$row, 'description')
                              ->setCellValue('M'.$row, 'hurl') 
                              ->setCellValue('N'.$row, 'META title')
                              ->setCellValue('O'.$row, 'META keywords')
                              ->setCellValue('P'.$row, 'META description');         


                   $PRODUCTS=db_arAll("select p.*,b.name bname from ".PRODUCTS_TABLE.' as p left join '.BRAND_TABLE.' as b on p.brandID=b.brandID');  
                   $i=2;
                   foreach ($PRODUCTS as $key => $value)
                   {
                      $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['productID']);  
                      $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['product_code']);          
                      $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, win2utf($value['name']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['Price']);       
                      $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['in_stock']);       
                      $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['picture']);       
                      $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, win2utf($value['thumbnail']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['big_picture']);       
                      $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, win2utf($value['bname']));
                      $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['categoryID']); 
                      $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, win2utf($value['brief_description']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, win2utf($value['description']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, win2utf($value['hurl']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, win2utf($value['meta_title']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, win2utf($value['meta_keywords']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, win2utf($value['meta_desc']));            
                      $i++;
                   } 
              
                  header("Content-type: application/vnd.ms-excel; charset=cp1251"); 
                  header('Content-Disposition: attachment;filename="'.$exportfile);
                  header('Cache-Control: max-age=0');  

                  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')
                    ->setDelimiter(';')
                    ->setEnclosure('"')
                    ->setLineEnding("\r\n")
                    ->setSheetIndex(0)
                    ->save('php://output');
                   exit;
                }
                elseif (isset($_POST["type"]) && $_POST["type"]=='xls')  
                { 
                   $objPHPExcel->setActiveSheetIndex(0);
                   $catalogs=fillTheCList(0,0);
                   $i=1;  
                   $category = array(
                      'font'    => array(
                                'name'=>'Arial',   
                                'size'=>'12',
                                'color' => array('rgb' => '2d2842'),  
				'bold'      => true
			       )
			
                            ); 
                   $center = array(
	                      'alignment'=>array(
     		              'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
	                  )        );
	
                $lang['productID']=array();                                                   
                $lang['product_code']=array();
                $lang['name']=array();    
                $lang['Price']=array();
                $lang['picture']=array();
                $lang['thumbnail']=array();
                $lang['big_picture']=array();
                
                $objPHPExcel->getActiveSheet()->getStyle('A:I')->applyFromArray($center);
                foreach ($catalogs as $key => $val)
                {
                  
                   #$sheet->setColumn($val[5],$i++,); 
                   $PRODUCTS=db_arAll("select p.*,b.name bname from ".PRODUCTS_TABLE.' as p left join '.BRAND_TABLE.' as b on p.brandID=b.brandID where categoryID='.$val[0]);  
 
                   
                   $row=$letters[$val[5]].$i;
                   $merge=$row.':'.'Z'.$i;
                   $objPHPExcel->getActiveSheet()->mergeCells($merge);
                   $objPHPExcel->getActiveSheet()->getStyle($row)->applyFromArray($category);
                   $objPHPExcel->getActiveSheet()->setCellValue($row, win2utf($val[1]));  
                   $i++;
                   
                   foreach ($PRODUCTS as $key => $value)
                   {
                    
                      
                      $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, win2utf($value['productID']));  
                      $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, win2utf($value['product_code']));          
                      $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, win2utf($value['name']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, win2utf($value['Price']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, win2utf($value['in_stock']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, win2utf($value['picture']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, win2utf($value['thumbnail']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, win2utf($value['big_picture']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, win2utf($value['bname']));
                      $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, win2utf($value['categoryID'])); 
                      $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, win2utf($value['brief_description']));       
                      $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, win2utf($value['description']));       
                      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setWrapText(true);
                      $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getAlignment()->setWrapText(true);
                      
                      $lang['productID'][]=strlen($value['productID']);
                      $lang['product_code'][]=strlen(win2utf($value['product_code']));
                      $lang['name'][]=strlen(win2utf($value['name']));
                      $lang['picture'][]=strlen(win2utf($value['picture']));
                      $lang['thumbnail'][]=strlen(win2utf($value['thumbnail']));
                      $lang['big_picture'][]=strlen(win2utf($value['big_picture']));
                      $lang['bname'][]=strlen(win2utf($value['bname']));
                      $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
                      

                      $i++;
                   } 



                }
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(get_max_array($lang['productID']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(get_max_array($lang['product_code']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(get_max_array($lang['name']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(get_max_array($lang['picture']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(get_max_array($lang['thumbnail']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(get_max_array($lang['big_picture']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(2+get_max_array($lang['bname']));
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(100);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(150);
                
                header("Content-type: application/vnd.ms-excel"); 
                header('Content-Disposition: attachment;filename="export_'.date("dmy").'.xls"');
                header('Cache-Control: max-age=0');  
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->setTempDir(TMP_DIR);
                $objWriter->save('php://output'); 
                }  
             
            }  
function l_name($ncateg,$nl,$ar_par)
{
 
  $sql='select c2.name,c1.parent,c1.name name_c,c1.categoryID,c2.categoryID upcatid from '.CATEGORIES_TABLE.' as c1 LEFT JOIN '.CATEGORIES_TABLE.' as c2 ON ( c2.`categoryID` = c1.`parent` )  where c1.categoryID=\''.$ncateg.'\'';
  if ($ica = db_assoc($sql))
  {
     array_unshift($nl,$ica['name_c']); 
     array_unshift($ar_par,$ica['categoryID']); 
    if ((int)$ica['parent']===0) return (array($nl,$ar_par));
    else return l_name($ica['upcatid'],$nl,$ar_par);
  }
  else return false;
}

       
	    if (isset($_POST["save_import"])) //start import
	    {
		$csv_file_row = Array();
                $csv_row= Array(); $result=0;
                
		if ((isset($_FILES['csv_file']) && $_FILES['csv_file']['name']) || (isset($_POST['file_import']) && $_POST['file_import'])  ) //import file
                 {
                  if (isset($_POST['file_import']) && $_POST['file_import']) $input_f=ROOT_DIR.'/import/'.$_POST['file_import'];
                  else { 
                      $input_f = ROOT_DIR.'/core/cache/'.str_replace(" ","_",$_FILES['csv_file']['name']);
                      $r = move_uploaded_file($_FILES['csv_file']['tmp_name'],$input_f);
                    }  
                  if (substr(strrchr($input_f, '.'), 1)=='csv')
                  {
                    
                    $handle = fopen($input_f, 'r'); 
                    #if(!setlocale(LC_ALL, 'ru_RU.utf8')) setlocale(LC_ALL, 'en_US.utf8');
                    setlocale(LC_ALL, 'ru_RU.CP1251');  
                    if(setlocale(LC_ALL, 0) == 'C') die('Не поддерживается ни одна из перечисленных локалей (ru_RU.CP1251)');
                    $lines_fest=fgetcsv($handle, 0, ';');  $op_sort=0; $op_id=array(); $coun=count($lines_fest)-1;
                    for ($param_i = 21; $param_i <= $coun; $param_i++) 
                    {
                       $line=$lines_fest[$param_i]; $addopt=array(); 
                       if(get_magic_quotes_gpc()) $line=stripslashes($line);
    	                   $line="'".mysql_real_escape_string($line)."'";  
                       
                       if ($id=db_r('select optionID from '.PRODUCT_OPTIONS_TABLE.' where name='.$line))
                         $op_id[$param_i]=$id;
                       else 
                       {
                         $addopt['sort_order']=$op_sort++;
                         $addopt['name']=$lines_fest[$param_i]; 
                         add_field(PRODUCT_OPTIONS_TABLE, $addopt);
                         $op_id[$param_i]=db_insert_id();
                       } 
                               
                    } 
                    $categorys=array(); $cid=0; $categorys[0]=0; $esql=0; $count_insert=0; $categorys_name=array();
                    $i=0;  $idcategory=0;
                    switch ($_POST["insert_type"])
		    {
		      case 0:
                      while (($data = fgetcsv($handle, 0, ';')) !== false)
                      { 
                        $category=array(); $doparam=array(); 
                        if (trim($data[5]) === '')
                        {    
                             $idcategory=0;
                             $category['name']=$data[2];
                             preg_match("/(!+)?(.+)/", $category['name'],$pieces);  
                             $sl=strlen($pieces[1]);  
                             $category['name']=$pieces[2];
                             $category['about']=($data[3]);
                             $category['description']=($data[4]);
                             $category['meta_title']=($data[8]);
                             $category['meta_keywords']=($data[9]);
                             $category['meta_desc']=($data[9]);
                             $category['picture']=$data[19];
                             $category['enabled']=1; 
                             if ($sl>0) { 
                               $categorys_name[$sl]=$category['name'];
                               $category['parent']=$categorys[$sl-1];  
                             } 
                             else 
                             {
                               $categorys_name=array();
                               $categorys_name[0]=$category['name'];   
                               $category['parent']=0;  
                             }
                        }    
                        else
                        {
                            if (DB_CHARSET=='utf8') 
                            {
                               $data[2]=win2utf($data[2]);
                               $data[3]=win2utf($data[3]);
                               $data[4]=win2utf($data[4]); 
                               $data[8]=win2utf($data[8]);
                               $data[9]=win2utf($data[9]);  
                               $data[10]=win2utf($data[10]);  
                            }  
                            if (trim($data[0]))
                             $product['productID']=$data[0];  
                            $product['product_code']=$data[1];  
                            $product['name']=$data[2];
                            $product['description']=($data[3]);
                            $product['brief_description']=($data[4]);
                            $product['Price']=$data[5];
                            $product['list_price']=$data[6]; 
                            $product['in_stock']=$data[7];  
                            $product['meta_title']=$data[8];   
                            $product['meta_keywords']=$data[9];
                            $product['meta_desc']=$data[10];
                            $product['categoryID']=$cid;
                            $product['enabled']=1; 
                            $product['min_qunatity']=$data[14]; 
                            $pictures_ar=explode(",", $data[19]);  
                            if (isset($pictures_ar[0]))       
                              $product['big_picture']=$pictures_ar[0];
                            if (isset($pictures_ar[1]))        
                              $product['picture']=$pictures_ar[1];
                            if (isset($pictures_ar[2]))         
                              $product['thumbnail']=$pictures_ar[2];
                           
                            #update_products_Count_Value_For_Categories_new($cid,1,1);  
                        }
                        
                        if (count($category)>0)
                        {
                           $cname=$category['name'];
                           
                           $nl=array();
                           $i=0;
                          
                           if(get_magic_quotes_gpc()) $cname=stripslashes($cname);
       	                   $cname="'".mysql_real_escape_string($cname)."'";  $rez=0;
                           
                           if ($categorysID=db_arAll('select categoryID from '.CATEGORIES_TABLE.' where name='.$cname))
                           foreach ($categorysID as $categoryID)
                           {
                              
                              $am=l_name($categoryID['categoryID'],$nl,$nl);
                              if ($am[0]==$categorys_name) {$rez=1;  break;} 
                           } 
                           
                          

                          
                           if ($rez==0 && add_field(CATEGORIES_TABLE, $category))
                           {
                             $cid=db_insert_id();
                             $categorys[$sl]=$cid;   
                             $count_insert++;
                           }
                           else $cid=$categoryID['categoryID'];
                          
                       }
                       else 
                       {
                         if(!db_r('select productID from '.PRODUCTS_TABLE.' where categoryID='.$cid.' and productID=\''.$product['productID'].'\''))
                         if (add_field(PRODUCTS_TABLE, $product)) 
                         {    
                          update_products_Count_Value_For_Categories_new($cid,1,1);  
                          $variant_sort=0;  
                          foreach ($op_id as $key => $value) 
                          {
                            $variant=array();     
                            $sel_text=$data[$key];
                            if(get_magic_quotes_gpc()) $sel_text=stripslashes($sel_text);
    	                    $sel_text="'".mysql_real_escape_string($sel_text)."'"; 
                            if (trim($data[$key]))    
                            if ($variantID=db_r('select variantID from '.PRODUCT_OPTIONS_VAL_TABLE.' WHERE name='.$sel_text.' and optionID='.$value))
                             {}
                            else 
                             {
                               $variant['name']=$data[$key];
                               $variant['optionID']=$value;
                               add_field(PRODUCT_OPTIONS_VAL_TABLE, $variant);  
                                
                             }
 
                          }
                          $count_insert++; 
                          
                         }  
                       }
                       
                       
                     } 
                     $smarty->assign("sql_result", $count_insert); 
                     break;
            	     case 1:
                                     
                     while (($data = fgetcsv($handle, 0, ';')) !== false)
                      { 
                        $category=array(); 
                        if (trim($data[5]) === '')
                        {    
                             $idcategory=0;
                             $category['name']=$data[2];
                             preg_match("/(!+)?(.+)/", $category['name'],$pieces);  
                             $sl=strlen($pieces[1]);  
                             $category['name']=$pieces[2];
                             $category['about']=($data[3]);
                             $category['description']=($data[4]);
                             $category['meta_title']=($data[8]);
                             $category['meta_keywords']=($data[9]);
                             $category['meta_desc']=($data[9]);
                             $category['picture']=$data[19];
                             $category['enabled']=1; 
                             if ($sl>0) { 
                               $categorys_name[$sl]=$category['name'];
                               $category['parent']=$categorys[$sl-1];  
                             } 
                             else 
                             {
                               $categorys_name=array();
                               $categorys_name[0]=$category['name'];   
                               $category['parent']=0;  
                             }
                        }    
                        else
                        {
                            if (DB_CHARSET=='utf8') 
                            {
                               $data[2]=win2utf($data[2]);
                               $data[3]=win2utf($data[3]);
                               $data[4]=win2utf($data[4]); 
                               $data[8]=win2utf($data[8]); 
                               $data[9]=win2utf($data[9]);  
                               $data[10]=win2utf($data[10]);  
                            }  
                             if (trim($data[0]))
                             $product['productID']=$data[0];  
                            $product['product_code']=$data[1];  
                            $product['name']=$data[2];
                            $product['description']=($data[3]);
                            $product['brief_description']=($data[4]);
                            $product['Price']=$data[5];
                            $product['list_price']=$data[6]; 
                            $product['in_stock']=$data[7];
                            $product['meta_title']=$data[8];     
                            $product['meta_keywords']=$data[9];
                            $product['meta_desc']=$data[10];
                            $product['categoryID']=$cid;
                            $product['enabled']=1; 
                            $product['min_qunatity']=$data[14]; 
                            $pictures_ar=explode(",", $data[19]);  
                            if (isset($pictures_ar[0]))       
                              $product['big_picture']=$pictures_ar[0];
                            if (isset($pictures_ar[1]))        
                              $product['picture']=$pictures_ar[1];
                            if (isset($pictures_ar[2]))         
                              $product['thumbnail']=$pictures_ar[2];
                           
                             
                        }
                        
                        if (count($category)>0)
                        {
                           $cname=$category['name'];
                           
                           $nl=array();
                           $i=0;
                          
                           if(get_magic_quotes_gpc()) $cname=stripslashes($cname);
       	                   $cname="'".mysql_real_escape_string($cname)."'";  $rez=0;
                           
                           if ($categorysID=db_arAll('select categoryID from '.CATEGORIES_TABLE.' where name='.$cname))
                           foreach ($categorysID as $categoryID)
                           {
                              
                              $am=l_name($categoryID['categoryID'],$nl,$nl);
                              if ($am[0]==$categorys_name) {$rez=1;  break;} 
                           } 
                           
                          

                          
                           if ($rez==1 && update_field(CATEGORIES_TABLE, $category, 'categoryID='.$categoryID['categoryID']))
                           {
                             $cid=$categoryID['categoryID'];
                             $categorys[$sl]=$cid;   
                             $count_insert++;
 
                           }
                           else $cid=0;
                          
                       }
                       elseif(db_r('select productID from '.PRODUCTS_TABLE.' where categoryID='.$cid.' and productID=\''.$product['productID'].'\''))
                       if (update_field(PRODUCTS_TABLE, $product,'product_code=\''.$product['product_code'].'\'')) $count_insert++; 
                     } 
                     #update_products_Count_Value_For_Categories(0);    
                     $smarty->assign("sql_result", $count_insert); 
                    
                     
                  } 
                }    
                  else   
		    {
                        if ((isset($_FILES['csv_file']) && $_FILES['csv_file']['name']))       
                        {
                           if ($_FILES['csv_file']['error']) 
                           { 
                             /*echo " <script type=\"text/javascript\"> alert(\"Пожалуйста, введите адрес базы данных\") </script>"; 
                              header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=import");*/
                              print_r($_FILES);
                           }
                           else
                           { 
			      $input_f = ROOT_DIR.'/core/cache/'.str_replace(" ","_",$_FILES['csv_file']['name']);
			      $r = move_uploaded_file($_FILES['csv_file']['tmp_name'],$input_f);
			   }   
			}   
			else 
			{
			   $input_f = ROOT_DIR.'/import/'.$_POST['file_import'];
			}
                        require_once("core/excel/reader.php");
                        $data = new Spreadsheet_Excel_Reader();
                        $data->setOutputEncoding('utf8');
                        $data->setUTFEncoder('mb'); $data->read($input_f); 
                        $m=1;  $c=1;         
                        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)  
                        {
                           $date_c=array(); $error=true;
                           for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
                            {
                             if (!isset($data->sheets[0]['cells'][$i][$j])) $data->sheets[0]['cells'][$i][$j]='';
                                $date_c[]=$data->sheets[0]['cells'][$i][$j];   

                            } 
                            if (trim($date_c[3]) && trim($date_c[2])!=='') $csv_row[$m]['prod'][]=$date_c; 
                            elseif (count(array_filter($date_c))>0) {$csv_row[$i]=$date_c; $m=$i;}
                        }  
			
		     
		    
                    $iu=0;
                    
                    $i=0; $parent=0; $lc[0]=0; 
		    switch ($_POST["insert_type"])
		    {
			case 0:
				//INSERT IGNORE	
                                 
                                

				foreach ($csv_row as $value)
				  {
                                    foreach ($value as $key => $val) 
                                    {  
                                      if (DB_CHARSET!='cp1251') $val=win2utf($val);
                                      
                                      if ((trim($val) !=''))  
                                      {   
                                        $catalog=array();
                                        if ($key==0)  {unset($lc); $lc[0]=0;} 
                                        elseif ($key<count($lc)-1)
                                        { 
                                           $tmplc=array();
                                           foreach ($lc as $tk=>$lval) 
                                           {
                                             $tmplc[$tk]=$lval;
                                             if ($key==$tk) break;
                                           }
                                           $lc=$tmplc;
                                        }
                                        
                                        if ($key>0)	
                                          $parent=$lc[$key-1];
                                        else $parent=$lc[0];
                                        
                                        $con=0;
                                        if (isset($value['prod'])) $con=count($value['prod']);
                                        $catalog['name']=$val;
                                        $catalog['parent']=$parent;   
                                        $catalog['products_count_admin']=$con; 
                                        $catalog['hurl']=to_url($val)."/";       
                                        $catalog['enabled']='1'; 
                                        $catalog['products_count']=$con;   
                                        add_field(CATEGORIES_TABLE, $catalog);
                                        $cid=db_insert_id(); $i++;
                                        $lc[$key]=$cid; 
                                        
                                        if (isset($value['prod'])) 
                                        { 
                                         
                                          foreach ($value['prod'] as $pkey => $pval) 
                                          {
                                            if (DB_CHARSET!='cp1251') $pval[2]=win2utf($pval[2]);
                                              
                                            $product['categoryID']=$cid;
                                            if (isset($pval[9]) && $pval[9]) $product['categoryID']=(int)$pval[9]; 
                                            if (trim($pval[1]))  $product['product_code']=$pval[1];
                                            if (trim($pval[0]))  $product['productID']=(int)$pval[0]; 
                                            $product['name']=$pval[2]; 
                                            $product['Price']=(float)$pval[3];
                                            if (isset($pval[4]))  
                                            $product['in_stock']=(float)$pval[4];  
                                            if (isset($pval[5]))  
                                            $product['picture']=$pval[5]; 
                                            if (isset($pval[6]))  
                                            $product['thumbnail']=$pval[6]; 
                                            if (isset($pval[7]))  
                                            $product['big_picture']=$pval[7];
                                          
                                            if (isset($pval[8]) && trim($pval[8])) 
                                            {  
                                              $bradid=db_r("select brandID from ".BRAND_TABLE." where `name`='".trim($pval[8])."'");  
                                              if ($bradid) $product['brandID']=$bradid;
                                              else 
                                              {
                                                $brand['name']=$pval[8];
                                                add_field(BRAND_TABLE,$brand);
                                                $product['brandID']=db_insert_id(); 
                                              }    
                                              unset($brand);   
                                            } 
                                            #$product['bname']=$pval[8];
                                            if (isset($pval[10]))        
                                            $product['brief_description']=$pval[10]; 
                                            if (isset($pval[11]))        
                                            $product['description']=$pval[11];      
                                            $product['enabled']='1'; 
                                             
                                             
                                            #if (isset($_POST['enabled'])) 
                                            
                                            if (add_field(PRODUCTS_TABLE, $product)) 
                                            {
                                             update_products_Count_Value_For_Categories_new($product['categoryID'],1,1);   
                                             $i++;
                                            }
                                          }  
                                          unset($value['prod']); 
                                        }
                                        break;
                                      } 
                                      
                                    }  
				    
				  }
				$result = $i;  //Вставлено строк:
                                
			break;
			case 1:

                               foreach ($csv_row as $value)
				  {
                                    foreach ($value as $key => $val) 
                                    {  
//                                      if (DB_CHARSET!='cp1251') $val=win2utf($val);
                                      
                                      if ((trim($val) !=''))  
                                      {   
                                        
                                        if (isset($value['prod'])) 
                                        { 
                                                
                                          foreach ($value['prod'] as $pkey => $pval) 
                                          {
                                            if (DB_CHARSET!='cp1251') $pval[2]=win2utf($pval[2]);
                                              $where=FALSE;
                                            
                                            $where=FALSE; $idp=FALSE;
                                            if (trim($pval[1])) $where="product_code='".$pval[1]."'";
                                            elseif (trim($pval[0])) $where="productID=".(int)$pval[0];   
                                            if ($where)
                                            {
                                              #echo "select productID from ".PRODUCTS_TABLE." where ".$where;
                                              #echo "<br>";
                                              $idp=db_r("select productID from ".PRODUCTS_TABLE." where ".$where);  
                                              if ($idp)
                                              { 
                                                if (DB_CHARSET!='cp1251') $pval[2]=win2utf($pval[2]);
                                                #$product['categoryID']=$cid;
                                                if (trim($pval[1])) $product['product_code']=$pval[1];
                                                if (trim($pval[0])) $product['productID']=(int)$pval[0]; 
                                                $product['name']=$pval[2]; 
                                                $product['Price']=(float)$pval[3];
                                                if (isset($pval[4]))  
                                                $product['in_stock']=(float)$pval[4];  
                                                if (isset($pval[5]))  
                                                $product['picture']=$pval[5]; 
                                                if (isset($pval[6]))  
                                                $product['thumbnail']=$pval[6]; 
                                                if (isset($pval[7]))  
                                                 $product['big_picture']=$pval[7];     
                                            #$product['bname']=$pval[8];
                                            
                                            if (isset($pval[9]))        
                                            $product['categoryID']=(int)$pval[9];
                                            if (isset($pval[10]))        
                                            $product['brief_description']=$pval[10];
                                            if (isset($pval[11]))        
                                            $product['description']=$pval[11];
                                            
                                                  
                                            $product['enabled']='1'; 
                                               if (update_field(PRODUCTS_TABLE, $product,'productID='.$idp)) $iu++; 
                                                
                                              } 
                                              
                                            }    
                                          }  
                                        }
                                        break;
                                      } 
                                      
                                    }  
				  }  
				$result = $iu;  //Вставлено строк:
			break;
			case 2:
				//UPDATE SELECTED 
//                                 $esql=1;
                                    foreach ($csv_row as $key => $val) 
                                    { 
                                       
                                      if (DB_CHARSET!='cp1251') $val=win2utf($val);
                                      
                                      if ((is_array($val)) && isset($val['prod']))  
                                      {   
                                          foreach ($val['prod'] as $pkey => $pval) 
                                          { 
                                             	
                                             		
				             $product=array();
				             if (isset($_POST["Price"])) 	         $product['Price']=(float)$pval[3];
				             if (isset($_POST["in_stock"]))		 $product['in_stock']=(int)$pval[4]; 
				             #if (isset($_POST["list_price"]))		{ $product['list_price']= $csv_row[$i][14];}
				             if (isset($_POST["enabled"]))		{ $product['enabled']='1';}
				             if (isset($_POST["name"]))			{ $product['name']=$pval[2];}
				             if (isset($_POST["description"]))		{ $product['description']=$pval[10];}
				             if (isset($_POST["brief_description"])) 	{ $product['brief_description']=$pval[9];}
				             if (isset($_POST["product_code"]))		{ $product['product_code']=$pval[1];}
				             if (isset($_POST["picture"]))		{ $product['picture']=$pval[5];}
				             if (isset($_POST["thumbnail"]))		{ $product['thumbnail']=$pval[6];}
				             if (isset($_POST["big_picture"]))		{ $product['big_picture']=$pval[7];}
				             if (isset($_POST["categoryID"]))		{ $product['categoryID']=(int)$pval[9];}
				             
                                                                                          

                                             if (count($product)>0)
                                             if (update_field(PRODUCTS_TABLE, $product,'productID='.$pval[0])) $iu++;
				          }
                                      } 
                                   }
				$result = $iu;  //Вставлено строк:
                                $esql=0;
			break;
		    }
                    $smarty->assign("sql_result", $result);
                    }  
		    
		}
               }
		chdir(ROOT_DIR.'/import');
                $ar_xls=glob('*.xls');
                $ar_csv=glob('*.csv'); 
               
                $ar=array_merge($ar_xls,$ar_csv);
                chdir(ROOT_DIR);
                $smarty->assign("import_files", $ar); 
		//set sub-department template
		$smarty->assign("admin_sub_dpt", "catalog_import.tpl.html");
	}