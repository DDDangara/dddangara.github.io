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
	echo 'Acces denied to this module';
	die;
}

	if (!strcmp($sub, "votes"))
	{
                if (!isset($_GET["votes"]) && !isset($_GET["add"])) 
                  {
		    $q = db_query("SELECT votesID, title FROM ".VOTES_TABLE." WHERE enable=1") or die (db_error());
		    $p = db_fetch_row($q);
		    $_GET["votes"]=$p[0];
		  }
                
                
                 
		if (isset($_GET["archive"]) && ($_GET["archive"])) //add vote to archive
		{
		   if (isset($_GET["complite"]))
			{
			db_query("UPDATE ".VOTES_TABLE." SET enable='0' WHERE votesID='".$_GET["archive"]."'");
			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=votes");
							exit;
			}
		   else {
			db_query("UPDATE ".VOTES_TABLE." SET enable='0'");
			db_query("UPDATE ".VOTES_TABLE." SET enable='1' WHERE votesID='".$_GET["archive"]."'");
			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=votes");
									exit;
			}			
		}

		if (isset($_GET["save_successful"])) //show successful save confirmation message
		{
			$smarty->assign("configuration_saved", 1);
		}

		if (isset($_GET["del_votes"])) // delete vote
		{
			$q = db_query("DELETE ".VOTES_TABLE.", ".VOTES_CONTENT_TABLE." FROM ".VOTES_TABLE.", ".VOTES_CONTENT_TABLE." WHERE ".VOTES_TABLE.".votesID='".$_GET["del_votes"]."' AND ".VOTES_CONTENT_TABLE.".votesID='".$_GET["del_votes"]."'") or die (db_error());
			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=votes");
						exit;
		}

		if (isset($_GET["votes"])) // edit vote
		{
			
                        $questions=db_arAll("SELECT ".VOTES_TABLE.".votesID, title, ID, question, result FROM ".VOTES_TABLE." LEFT JOIN ".VOTES_CONTENT_TABLE." USING(votesID) WHERE ".VOTES_TABLE.".votesID='".$_GET["votes"]."'   ORDER BY ID ASC") or die (db_error());
                        
			$smarty->assign("votes", $questions[0]);
			$smarty->assign("votes_results", $questions);
			$smarty->assign("votes_results_count", count($questions));
		}

		if (isset($_POST["add_vote"]))
		{
			if ($_POST["add_vote"])
			{
				db_query("UPDATE ".VOTES_TABLE." SET enable=0");
                                db_query("UPDATE ".VOTES_TABLE." SET title='".$_POST["votes_title"]."', enable=1 WHERE votesID='".$_POST["add_vote"]."'");

				foreach ($_POST as $key => $val)
				{
				  if (strstr($key, "votes_quest_"))
					db_query("UPDATE ".VOTES_CONTENT_TABLE." SET question='".$val."' WHERE ID='".str_replace("votes_quest_","",$key)."'") or die (db_error());

				  if (strstr($key, "votes_res_"))
					db_query("UPDATE ".VOTES_CONTENT_TABLE." SET result='".$val."' WHERE ID='".str_replace("votes_res_","",$key)."'") or die (db_error());
				}

			}
			else //save new vote
			{
			   if ($_POST["votes_title"])
			   {
				db_query("INSERT INTO ".VOTES_TABLE." (title, enable) VALUES ('".$_POST["votes_title"]."', '1');") or die (db_error());
				$pid = db_insert_id();
				for ($i=0; $i<10; $i++)
				{db_query("INSERT INTO ".VOTES_CONTENT_TABLE." (votesID, question, result) VALUES ('".$pid."', '".$_POST["votes_quest_$i"]."', '0');") or die (db_error());}
			   }
			   $_POST["add_vote"]=$pid;

			}

			//save changes to cfg

			//modify checkbox vars
			if (!isset($_POST["votes_on"])) $_POST["votes_on"] = 0;
			if (!strcmp($_POST["votes_on"], "on")) $_POST["votes_on"] = 1;
			else $_POST["votes_on"] = 0;
			if (!isset($_POST["votes_tomail"])) $_POST["votes_tomail"] = 0;
			if (!strcmp($_POST["votes_tomail"], "on")) $_POST["votes_tomail"] = 1;
			else $_POST["votes_tomail"] = 0;

			//votes settings
			$f = fopen("./cfg/votes.inc.php","w");
			fputs($f,"<?php\n\tdefine('CONF_VOTES_ON', '".(int)$_POST["votes_on"]."');\n");
			fputs($f,"\tdefine('CONF_VOTES_TOMAIL', '".(int)$_POST["votes_tomail"]."');\n");
			fputs($f,"?>");
			fclose($f);

			header("Location: " . CONF_ADMIN_FILE . "?dpt=inform&sub=votes&votes=".$_POST["add_vote"]."&save_successful=yes");
			exit;
		}

		$votes_list=array();

		$q = db_query("SELECT votesID, title, enable FROM ".VOTES_TABLE." ") or die (db_error());
		$i=0;
		while ($nm=db_fetch_row($q))
		{ 
			$votes_list[0][] = $nm[0];
			$votes_list[2][] = $nm[2];

			if (isset($_GET["votes"]) && $nm[0] == $_GET["votes"])
				$votes_list[1][] = "<b>".$nm[1]."</b>";
			else
				$votes_list[1][] = $nm[1];
			$i++;
 		}

		$smarty->assign("votes_list", $votes_list);
		$smarty->assign("votes_list_count", $i);

		//set sub-department template
		$smarty->assign("admin_sub_dpt", "inform_votes.tpl.html");
	}

?>
