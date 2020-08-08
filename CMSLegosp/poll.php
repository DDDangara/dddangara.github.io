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
 
include("./cfg/connect.inc.php");
include("./includes/database/mysql.php");
include_once("./cfg/general.inc.php"); 
db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
db_select_db(DB_NAME) or die (db_error());

// Poll option definitions
$options[1] = 'jQuery';
$options[2] = 'Ext JS';
$options[3] = 'Dojo';
$options[4] = 'Prototype';
$options[5] = 'YUI';
$options[6] = 'mootools';

// Column definitions
define('OPT_ID', 0);
define('OPT_TITLE', 1);
define('OPT_VOTES', 2);

#define('HTML_FILE', 'index.html');

// Initialize the DB
define('VOTE_DB', 'votes.txt');
define('IP_DB', 'ips.txt');

if (isset($_GET['poll']) || isset($_POST['poll'])) {
  poll_submit();
}
elseif (isset($_GET['vote']) || isset($_POST['vote'])) {
  poll_ajax();
}
else {
  #poll_default();
}


function poll_ajax() {

  $id = $_GET['vote'];
  $idvote = $_GET['idvote']; 
  if ($id != 'none') {
      db_query("UPDATE ".VOTES_CONTENT_TABLE." SET `result`=`result`+'1' WHERE votesID='".$idvote."' AND ID='".$id."'") or die (db_error());
  }
  
  $q = db_query("SELECT ID, question, result FROM ".VOTES_TABLE." as vt LEFT JOIN ".VOTES_CONTENT_TABLE." USING(votesID) WHERE enable='1' and question !='' ORDER BY ID ASC") or die (db_error()); 
  $mmm = Array();
 
    while ($p= db_fetch_row($q))
	   {
            #if (!$p[2]) $p[2]=0;
	    $mmm[] = $p;
	    
	   }  
  print json_encode_univ($mmm);
}


function poll_submit() {
  global $db;
  global $options;
  
  $id = $_GET['poll'] || $_POST['poll'];
  $id = str_replace("opt", '', $id);
  
  $ip_result = $db->selectUnique(IP_DB, 0, $_SERVER['REMOTE_ADDR']);
  
  if (!isset($_COOKIE['vote_id']) && empty($ip_result)) {
    $row = $db->selectUnique(VOTE_DB, OPT_ID, $id);
    if (!empty($row)) {
      $ip[0] = $_SERVER['REMOTE_ADDR'];
      $db->insert(IP_DB, $ip);
      
      setcookie("vote_id", $id, time()+31556926,"/"); // cookie expires in one year
      
      $new_votes = $row[OPT_VOTES]+1;
      $db->updateSetWhere(VOTE_DB, array(OPT_VOTES => $new_votes), new SimpleWhereClause(OPT_ID, '=', $id));
      
      poll_return_results($id);
    }
    else if ($options[$id]) {
      $ip[0] = $_SERVER['REMOTE_ADDR'];
      $db->insert(IP_DB, $ip);
      
      setcookie("vote_id", $id, time()+31556926,"/"); // cookie expires in one year
      
      $new_row[OPT_ID] = $id;
      $new_row[OPT_TITLE] = $options[$id];
      $new_row[OPT_VOTES] = 1;
      $db->insert(VOTE_DB, $new_row);
      
      poll_return_results($id);
    }
  }
  else {
    poll_return_results($id);
  }
}


function poll_default() {
  global $db;
  
  $ip_result = $db->selectUnique(IP_DB, 0, $_SERVER['REMOTE_ADDR']);
  
  if (!isset($_COOKIE['vote_id']) && empty($ip_result)) {
    print file_get_contents(HTML_FILE);
  }  
  else {
    poll_return_results($_COOKIE['vote_id']);
  }
}


function poll_return_results($id = NULL) {
    global $db;
  
    $html = file_get_contents(HTML_FILE);
    $results_html = "<div id='poll-container'><div id='poll-results'><h3>Poll Results</h3>\n<dl class='graph'>\n";
    
    $rows = $db->selectWhere(VOTE_DB,
      new SimpleWhereClause(OPT_ID, "!=", 0), -1,
      new OrderBy(OPT_VOTES, DESCENDING, INTEGER_COMPARISON));

    foreach ($rows as $row) {
      $total_votes = $row[OPT_VOTES]+$total_votes;
    }
    
    foreach ($rows as $row) {
      $percent = round(($row[OPT_VOTES]/$total_votes)*100);
      if (!$row[OPT_ID] == $id) {
        $results_html .= "<dt class='bar-title'>". $row[OPT_TITLE] ."</dt><dd class='bar-container'><div id='bar". $row[OPT_ID] ."'style='width:$percent%;'>&nbsp;</div><strong>$percent%</strong></dd>\n";
      }
      else {
        $results_html .= "<dt class='bar-title'>". $row[OPT_TITLE] ."</dt><dd class='bar-container'><div id='bar". $row[OPT_ID] ."' style='width:$percent%;background-color:#0066cc;'>&nbsp;</div><strong>$percent%</strong></dd>\n";
      }
    }
    
    $results_html .= "</dl><p>Total Votes: ". $total_votes ."</p></div></div>\n";
    
    $results_regex = '/<div id="poll-container">(.*?)<\/div>/s';
    $return_html = preg_replace($results_regex, $results_html, $html);
    print $return_html;
}

function json_encode_univ($value) 
    {
        if (is_int($value)) {
            return (string)$value;   
        } elseif (is_string($value)) {
            $value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"), 
                                 array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
            $convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
            $result = "";
            for ($i = mb_strlen($value) - 1; $i >= 0; $i--) {
                $mb_char = mb_substr($value, $i, 1);
                if (mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match)) {
                    $result = sprintf("\\u%04x", $match[1]) . $result;
                } else {
                    $result = $mb_char . $result;
                }
            }
            return '"' . $result . '"';                
        } elseif (is_float($value)) {
            return str_replace(",", ".", $value);         
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $with_keys = false;
            $n = count($value);
            for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
                        if (key($value) !== $i) {
                  $with_keys = true;
                  break;
                        }
            }
        } elseif (is_object($value)) {
            $with_keys = true;
        } else {
            return '';
        }
        $result = array();
        if ($with_keys) {
            foreach ($value as $key => $v) {
                $result[] = json_encode_univ((string)$key) . ':' . json_encode($v);    
            }
            return '{' . implode(',', $result) . '}';                
        } else {
            foreach ($value as $key => $v) {
                $result[] = json_encode_univ($v);    
            }
            return '[' . implode(',', $result) . ']';
        }
    } 