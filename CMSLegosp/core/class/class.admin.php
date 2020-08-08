<?PHP
  
  class lego_function
  {

function Utf8Win($str,$type="w")
    {
 
        if (function_exists('mb_convert_encoding'))
        return  mb_convert_encoding($str,'windows-1251','utf-8');    
        static $conv='';
        if (!is_array($conv))
        {
            $conv = array();
            for($x=128;$x<=143;$x++)
            {
                $conv['u'][]=chr(209).chr($x);
                $conv['w'][]=chr($x+112);
            }
            for($x=144;$x<=191;$x++)
            {
                $conv['u'][]=chr(208).chr($x);
                $conv['w'][]=chr($x+48);
            }
            $conv['u'][]=chr(208).chr(129); // 
            $conv['w'][]=chr(168);
            $conv['u'][]=chr(209).chr(145); // 
            $conv['w'][]=chr(184);
            $conv['u'][]=chr(208).chr(135); // 
            $conv['w'][]=chr(175);
            $conv['u'][]=chr(209).chr(151); // 
            $conv['w'][]=chr(191);
            $conv['u'][]=chr(208).chr(134); // 
            $conv['w'][]=chr(178);
            $conv['u'][]=chr(209).chr(150); // 
            $conv['w'][]=chr(179);
            $conv['u'][]=chr(210).chr(144); // 
            $conv['w'][]=chr(165);
            $conv['u'][]=chr(210).chr(145); // 
            $conv['w'][]=chr(180);
            $conv['u'][]=chr(208).chr(132); // 
            $conv['w'][]=chr(170);
            $conv['u'][]=chr(209).chr(148); // 
            $conv['w'][]=chr(186);
            $conv['u'][]=chr(226).chr(132).chr(150); // 
            $conv['w'][]=chr(185);
        }
        if ($type == 'w') { return str_replace($conv['u'],$conv['w'],$str); }
        elseif ($type == 'u') { return str_replace($conv['w'], $conv['u'],$str); }
        else { return $str; }
    }


private function fract($num = 0) {
 if(floor($num)==0) return false;
   $out = explode('.', $num);
 if (!isset($out[1])) return false;
 return $out[1];
}

    public function show_price($price)
    {
	if ($this->fract($price))
		$price = number_format($price, 2, ',', ' ');
	else	$price = number_format($price, 0, '', ' ');
	$csign_left  = (defined("CONF_CURRENCY_ID_LEFT")) ? CONF_CURRENCY_ID_LEFT : "US $";
	$csign_right = (defined("CONF_CURRENCY_ID_RIGHT")) ? CONF_CURRENCY_ID_RIGHT : "";
	return $csign_left.$price.$csign_right;
    }
    public static function currency()
    {
      if (isset($_POST['current_currency'])) 
      {
         $CID=$_POST['current_currency'];
         $_SESSION['CURRENCY_ID']=$CID;  
      }
      elseif (isset($_SESSION['CURRENCY_ID'])) $CID=$_SESSION['CURRENCY_ID'];
      else  $CID=CONF_CURRENCY_ID;
      $c=db_assoc('select * from '.CURRENCY_TABLE.' where CID='.$CID);
      if ($c['where2show']) { @define("CONF_CURRENCY_ID_RIGHT",$c['code']); @define('CONF_CURRENCY_ID_LEFT','');}
      elseif ($c)  
      {  
        define('CONF_CURRENCY_ID_LEFT',$c['code']); define("CONF_CURRENCY_ID_RIGHT",''); 
      }
      else {@define('CONF_CURRENCY_ID_LEFT',''); @define("CONF_CURRENCY_ID_RIGHT",''); }
       
      @define('CONF_CURRENCY_ISO3',$c['currency_iso_3']);  
      if (!$c['currency_value']) $c['currency_value']=1;
      @define('CURRENCY_val', $c['currency_value']);
      @define('CURRENCY_ID', $CID);
    } 

  }


  class adminClass extends lego_function
  {
    public function category_list($cat=FALSE){
    $levels = array();
    $tree = array();
    $cur = array();
    
    $sql='SELECT categoryID,name,parent,products_count_admin,enabled,hurl FROM `'.CATEGORIES_TABLE.'` where enabled=1 ';
    if ($cat !==FALSE)
    {
        $path = array($cat);
	$curr = $cat;
	do
	{
		$curr= db_r("SELECT parent FROM ".CATEGORIES_TABLE.' WHERE categoryID='.$curr);
                $curr = $curr ? $curr : 0; 
		$path[] = $curr;


	} while ($curr);
      $sql .= ' and parent in ('.add_in($path).')';
    }  
    
    $sql .=' ORDER BY ' . CONF_SORT_CATEGORY . ' ' . CONF_SORT_CATEGORY_BY ;
    $sqlresult =  db_query( $sql);
    while($rows = db_assoc_q($sqlresult)){
        if ($rows['hurl'] != "" && CONF_CHPU) {
            $rows['hurl'] = REDIRECT_CATALOG . "/" . $rows['hurl'];
        } else {
            $rows['hurl'] = "index.php?categoryID=" . $rows['categoryID'];
        }
        $cur = &$levels[$rows['categoryID']];
        if (is_array($cur))
        {
          $cur=array_merge($cur,$rows);
        }
        else $cur=$rows;
        if($rows['parent'] == 0){
            $tree[$rows['categoryID']] = &$cur;
        }
        else{
            $levels[$rows['parent']]['subitems'][$rows['categoryID']] = &$cur;
        }
       
    }
    return $tree;
 }
    
    
public function to_url($text) {
    $text=trim($text); 
    if (DB_CHARSET!='cp1251') $text=$this->Utf8Win($text);  
    $text = preg_replace("/[^a-Ðªa-z0-9-s .]/i", "", $text);
    $tr = array(
        "ÑŽ"=>"a","Ð°"=>"b","Ð±"=>"v","Ñ†"=>"g",
        "Ð´"=>"d","Ðµ"=>"e","Ñ„"=>"j","Ð³"=>"z","Ñ…"=>"i",
        "Ð¸"=>"y","Ð¹"=>"k","Ðº"=>"l","Ð»"=>"m","Ð¼"=>"n",
        "Ð½"=>"o","Ð¾"=>"p","Ð¿"=>"r","Ñ"=>"s","Ñ€"=>"t",
        "Ñ"=>"u","Ñ‚"=>"f","Ñƒ"=>"h","Ð¶"=>"ts","Ð²"=>"ch",
        "ÑŒ"=>"sh","Ñ‹"=>"sch","Ð·"=>"","Ñˆ"=>"yi","Ñ"=>"",
        "Ñ‰"=>"e","Ñ‡"=>"yu","ÑŠ"=>"ya","Ð®"=>"a","Ð"=>"b",
        "Ð‘"=>"v","Ð¦"=>"g","Ð”"=>"d","Ð•"=>"e","Ð¤"=>"j",
        "Ð“"=>"z","Ð¥"=>"i","Ð?"=>"y","Ð™"=>"k","Ðš"=>"l",
        "Ð›"=>"m","Ðœ"=>"n","Ð"=>"o","Ðž"=>"p","ÐŸ"=>"r",
        "Ð¯"=>"s","Ð "=>"t","Ð¡"=>"u","Ð¢"=>"f","Ð£"=>"h",
        "Ð–"=>"ts","Ð’"=>"ch","Ð¬"=>"sh","Ð«"=>"sch","Ð—"=>"y",
        "Ð¨"=>"yi","Ð­"=>"","Ð©"=>"e","Ð§"=>"yu","Ðª"=>"ya", 
        " "=> "_", "."=> "", ":"=>"","/"=> "_"
    );
    $text=strtr($text,$tr);
    return $text;
}    

public function file_url($text) {
    $text=trim($text); 
    if (DB_CHARSET!='cp1251') $text=$this->Utf8Win($text);  
    $text = preg_replace("/[^a-Ðªa-z0-9-s .]/i", "", $text);
    $tr = array(
        "ÑŽ"=>"a","Ð°"=>"b","Ð±"=>"v","Ñ†"=>"g",
        "Ð´"=>"d","Ðµ"=>"e","Ñ„"=>"j","Ð³"=>"z","Ñ…"=>"i",
        "Ð¸"=>"y","Ð¹"=>"k","Ðº"=>"l","Ð»"=>"m","Ð¼"=>"n",
        "Ð½"=>"o","Ð¾"=>"p","Ð¿"=>"r","Ñ"=>"s","Ñ€"=>"t",
        "Ñ"=>"u","Ñ‚"=>"f","Ñƒ"=>"h","Ð¶"=>"ts","Ð²"=>"ch",
        "ÑŒ"=>"sh","Ñ‹"=>"sch","Ð·"=>"","Ñˆ"=>"yi","Ñ"=>"",
        "Ñ‰"=>"e","Ñ‡"=>"yu","ÑŠ"=>"ya","Ð®"=>"a","Ð"=>"b",
        "Ð‘"=>"v","Ð¦"=>"g","Ð”"=>"d","Ð•"=>"e","Ð¤"=>"j",
        "Ð“"=>"z","Ð¥"=>"i","Ð?"=>"y","Ð™"=>"k","Ðš"=>"l",
        "Ð›"=>"m","Ðœ"=>"n","Ð"=>"o","Ðž"=>"p","ÐŸ"=>"r",
        "Ð¯"=>"s","Ð "=>"t","Ð¡"=>"u","Ð¢"=>"f","Ð£"=>"h",
        "Ð–"=>"ts","Ð’"=>"ch","Ð¬"=>"sh","Ð«"=>"sch","Ð—"=>"y",
        "Ð¨"=>"yi","Ð­"=>"","Ð©"=>"e","Ð§"=>"yu","Ðª"=>"ya", 
        " "=> "_", "/"=> "_"
    );
    return strtr($text,$tr);
}


  }

  
?>