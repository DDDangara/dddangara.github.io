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
 

	//general settings

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}

	if (!strcmp($sub, "manager"))
	{
		
		
		if (isset($_POST['menedger_id']))
		{
		
		   
		   db_query("DELETE FROM ".MANAGER_TABLE_DENY." WHERE id_manager='".(int)$_POST['menedger_id']."'");
		   if (isset($_POST["file"]))
		   {
		   $mdeny['id_manager']=(int)$_POST['menedger_id']; 
		   foreach ($_POST["file"] as $value)
		   {
		      
 	              $mdeny['dpt']=$value;
		      add_field(MANAGER_TABLE_DENY,$mdeny);
		   }
		    $mdeny['dpt']='system_manager';
		    add_field(MANAGER_TABLE_DENY,$mdeny);
		    header("Location: " . CONF_ADMIN_FILE . "?dpt=system&sub=manager");
									exit;
		   }
		}
		
		
		if (isset($_POST["update_manager"])) //update managers
		{
		    $arr_manager = Array();
		    $arr_pass = Array();
		    $arr_access = Array();
		    $arr_email = Array();
		    $arr_online_type = Array();
		    $arr_online_num = Array();
		    $arr_online_name = Array();
		    $count = 0;
                    
                    if (isset($_POST['update_manager']))          
		    foreach ($_POST['update_manager'] as $key => $val)
			{
                          if (trim($val['manager']) and trim($val['password'])) update_field(MANAGER_TABLE, $val,'ID='.(int)$key);
			}
                     
		
		}
                
                
		if (isset($_POST['manager']['manager']) && trim($_POST['manager']['manager']) and trim($_POST['manager']['password'])) add_field(MANAGER_TABLE, $_POST['manager']);
		
			
                
		if ((isset($_GET["delete"])) && ($_GET["delete"]>0)) //delete manager
			{
			db_query("DELETE FROM ".MANAGER_TABLE." WHERE ID='".$_GET["delete"]."'");
			header("Location: " . CONF_ADMIN_FILE . "?dpt=system&sub=manager");
							exit;
			}
	
	//list of managers
		$managers = db_arAll("SELECT * FROM ".MANAGER_TABLE." ORDER BY ID");
		$smarty->assign("managers", $managers);

	//set sub-department template
	$smarty->assign("admin_sub_dpt", "system_manager.tpl.html");
	
	if (isset($_GET["deny"]))
	  {
             $mdeny=array(); 
             $i=1;
             
          
             $q = db_query("SELECT dpt FROM ".MANAGER_TABLE_DENY." where id_manager=".$_GET["deny"]);
             while ($row = db_fetch_row($q))
             {
               $mdeny[$i++]=$row[0];
             }
               
             $smarty->assign("mdeny",$mdeny); 
             $smarty->assign("admin_sub_dpt",'system_manager_deny.tpl.html'); 
          }   
      	
	}

?>