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
 

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

if (!strcmp($sub, "sales"))
{
$list=30;    

$shipping=ADMIN_SHIPPING;
$discont=ADMIN_DISCOUNT_STRING;
if (DB_CHARSET!='cp1251') 
{
 $shipping=win2utf($shipping);
 $discont=win2utf($discont);
} 

$sql='select DATE_FORMAT( o.order_time, \'%d-%m-%Y\') date,oc.name,sum(oc.Quantity) kol,oc.price,(sum(oc.Quantity)*oc.price) sprice,product_code from  '.ORDERS_TABLE.' as o left join '.ORDERED_CARTS_TABLE.' AS oc ON (o.orderID = oc.orderID) left join '.PRODUCTS_TABLE.' as pt ON (oc.productID=pt.productID) where oc.name not like "%'.$shipping.'%" and oc.name not like "'.$discont.' %" and oc.name !="" ';
$sql_all='select count(DISTINCT oc.productID) kol_p, sum(oc.Price*oc.Quantity) sum_p, sum(oc.Quantity) summ_p  from '.ORDERS_TABLE.' as o left join '.ORDERED_CARTS_TABLE.' AS oc ON (o.orderID = oc.orderID) where oc.name not like "%'.$shipping.'%" and oc.name not like "'.$discont.' %" and oc.name !="" ';
$URL='./".CONF_ADMIN_FILE."?dpt=reports&sub=sales'; 
if (isset($_GET['start_Year']) and isset($_GET['end_Year']))
          {   
            $sql .=  'and order_time BETWEEN STR_TO_DATE(\''.(int)$_GET['start_Year'].'-'.(int)$_GET['start_Month'].'-'.(int)$_GET['start_Day'].'\', \'%Y-%m-%d\') AND STR_TO_DATE(\''.(int)$_GET['end_Year'].'-'.(int)$_GET['end_Month'].'-'.(int)$_GET['end_Day'].' 23:59:59\', \'%Y-%m-%d %H:%i:%s\') ';
            $sql_all .= 'and order_time BETWEEN STR_TO_DATE(\''.(int)$_GET['start_Year'].'-'.(int)$_GET['start_Month'].'-'.(int)$_GET['start_Day'].'\', \'%Y-%m-%d\') AND STR_TO_DATE(\''.(int)$_GET['end_Year'].'-'.(int)$_GET['end_Month'].'-'.(int)$_GET['end_Day'].' 23:59:59\', \'%Y-%m-%d %H:%i:%s\') ';
            $URL .="&start_Day=".(int)$_GET['start_Day']."&start_Month=".(int)$_GET['start_Month']."&start_Year=".(int)$_GET['start_Year'];
            $URL .='&end_Day='.(int)$_GET['end_Day'].'&end_Month='.(int)$_GET['end_Month'].'&end_Year='.(int)$_GET['end_Year'];  
            $Data['start']=(int)$_GET['start_Year'].'-'.(int)$_GET['start_Month'].'-'.(int)$_GET['start_Day'];
            $Data['end']=(int)$_GET['end_Year'].'-'.(int)$_GET['end_Month'].'-'.(int)$_GET['end_Day'];
          }
else
{
  $Data=db_assoc('select DATE_FORMAT(min(order_time), \'%Y-%m-%d\') start,DATE_FORMAT(max(order_time), \'%Y-%m-%d\') end  from '.ORDERS_TABLE);
} 
if (isset($_GET['rproduct']))
 $_SESSION['rproduct']=(int)$_GET['rproduct'];
if (isset($_GET['rcustomer']))
 $_SESSION['rcustomer']=(int)$_GET['rcustomer'];
if (isset($_SESSION['rcustomer']) && $_SESSION['rcustomer']>0)
{
  $sql .=' and `custID`='.$_SESSION['rcustomer'].' ';
  $sql_all .=' and `custID`='.$_SESSION['rcustomer'].' ';
}

if (isset($_SESSION['rproduct']) && $_SESSION['rproduct']>0)
{
  $sql .=' and oc.`productID`='.$_SESSION['rproduct'].' ';
  $sql_all .=' and oc.`productID`='.$_SESSION['rproduct'].' ';
}
$smarty->assign("data", $Data);  
 $sql .=' GROUP BY oc.name, oc.`productID`, oc.`Price`, DATE_FORMAT( o.order_time, \'%d-%m-%Y\') ';
#$sql_all .=' GROUP BY oc.`productID`';
$rezult_all =db_assoc($sql_all);
$rezult_all['sum_p']=show_price($rezult_all['sum_p']);

$orders_count=count(db_arAll($sql));
if (isset($_GET['sort']))
{
   if (!isset($_GET['sort_dir'])) $_GET['sort_dir']='ASC';
    $sql .='order by '.$_GET['sort'].' '.$_GET['sort_dir']; 
}
else $sql .=' order by o.order_time'; 
$pages=ceil($orders_count/$list);
unset($orders_count);        
if (isset($_GET['page'])) $page=(int)$_GET['page']-1;
else $page=0;

$sql .=" LIMIT ".$page*$list." , ".$list;
$report_order=db_arAll($sql);


if (isset($_GET['export']))
{
  function get_max_array($a) {
	  if (count($a))
	  {
            arsort($a);
            $max = current($a);
            return current(array_filter($a, create_function('$x', 'return $x == '.$max.';')))+1;
            
          } else return -1; 
  }

  require_once ROOT_DIR.'/core/excel/Classes/PHPExcel.php';
  require_once ROOT_DIR.'/core/excel/Classes/PHPExcel/IOFactory.php';
  $text='Отчет продаж';
  if (isset($_GET['start_Year']) and isset($_GET['end_Year'])) 
  $text .=' за интервал дат c '.(int)$_GET['start_Year'].'-'.(int)$_GET['start_Month'].'-'.(int)$_GET['start_Day'].' по '.(int)$_GET['end_Year'].'-'.(int)$_GET['end_Month'].'-'.(int)$_GET['end_Day'];

   

  $objPHPExcel = new PHPExcel(); $i=2;
  $objPHPExcel->setActiveSheetIndex(0);
  $merge='A1:'.'Z1';
  $objPHPExcel->getActiveSheet()->mergeCells($merge);
   $objPHPExcel->getActiveSheet()->setCellValue('A1', win2utf($text));
  $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, win2utf('Дата'));
  $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, win2utf('Наименование'));
  $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, win2utf('Кол–во'));
  $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, win2utf('Цена'));
  $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, win2utf('Сумма'));
  $lang['A'][]=strlen(win2utf('Дата'));
    $lang['B'][]=strlen(win2utf('Наименование'));
    $lang['C'][]=strlen(win2utf('Кол–во')); 
    $lang['D'][]=strlen(win2utf('Цена'));
    $lang['E'][]=strlen(win2utf('Сумма'));  
  $kol_p=0;
  foreach ($report_order as $key => $value)
  {
    $i++;   
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['date']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, win2utf($value['name']));
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['kol']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['price']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['sprice']);
    $kol_p +=$value['kol'];
    $lang['A'][]=strlen($value['date']);
    $lang['B'][]=strlen($value['name']);
    $lang['C'][]=strlen($value['kol']); 
    $lang['D'][]=strlen($value['price']);
    $lang['E'][]=strlen($value['sprice']);
   
      
  } 
  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(get_max_array($lang['A']));
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(get_max_array($lang['B']));
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(get_max_array($lang['C']));
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(get_max_array($lang['D']));
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(get_max_array($lang['E'])); 
  if (isset($_SESSION['rproduct']) && $_SESSION['rproduct']>0)
  { 
    $i++; 
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, win2utf('Всего:').$kol_p);
  }     

  header("Content-type: application/vnd.ms-excel"); 
  header('Content-Disposition: attachment;filename="export_'.date("dmy").'.xls"');
  header('Cache-Control: max-age=0');  
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->setTempDir(TMP_DIR);
  $objWriter->save('php://output'); 
}



$sql='select oc.name,p.categoryID,oc.productID from '.ORDERED_CARTS_TABLE.' AS oc LEFT JOIN '.PRODUCTS_TABLE.' as p USING (productID) where oc.name not like "%'.$shipping.'%" and oc.name not like "'.$discont.' %" and oc.name !="" GROUP BY oc.name, oc.`productID`';
$products_arr=array(); $i=0;
$qc=db_query($sql); 
while ($products =db_fetch_row($qc))
{
  $name='';  
  $par=$products[1];
  $name=''; 
  if ($par>0)
  {
     while($par > 0)
     {
        $scat=$par;
        $q= db_query('select parent,products_count,name from '.CATEGORIES_TABLE.' where categoryID='.$scat);
        $row = db_fetch_row($q);
        $par= $row[0];
        $name =$row[2].'->'.$name;
     }
    $name=substr($name, 0, strlen($name)-2); 
  }
  
  
  $products_arr[$i]['category']=$name.':';
  $products_arr[$i]['productID']=$products[2];
  $products_arr[$i++]['name']=$products[0];
  
}
$CUSTOMERS=db_arAll('select custID,cust_email,cust_firstname,cust_lastname from '.CUST_TABLE);
$smarty->assign("customers", $CUSTOMERS); 
$smarty->assign("products", $products_arr); 
$smarty->assign("rezult", $rezult_all); 
$smarty->assign("path", $URL); 
$smarty->assign("page", $page); 
$smarty->assign("slist", $page*$list);  
$smarty->assign("count_pages", $pages);
$smarty->assign("orders", $report_order);
$smarty->assign("admin_sub_dpt", "reports_sales.tpl.html");
}

?>