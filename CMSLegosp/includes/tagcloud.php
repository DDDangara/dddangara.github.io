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
 
	$max=1;
	$q = db_query("SELECT tag, Count(id) as Cnt, hurl FROM ".TAGS_TABLE." GROUP BY tag") or die (db_error());
        $num_rows=db_num_rows($q);
        if ($num_rows>0) $max=$num_rows;
	$maxfont=22;
	$minfont=10;
	$tagcloud = "<ul>";
	$tagsearch = "";
	$tc=0;
	while (($row = db_fetch_row($q)) && ($tc < CONF_TAG_COUNT))
	{
	     $font =round(($max/$row[1])+$minfont);
	     if(($max/$num_rows)==1) $font = $maxfont;
	     elseif($num_rows==1) $font = $minfont;
	     if($font<$minfont) $font=$minfont;
	     else $font=$maxfont;

		if ($row[2] && CONF_CHPU) {$href = REDIRECT_TAGS."/".$row[2];} else {$href = "index.php?tagID=".$row[0];}

	     $tagcloud .='<li><a href="./'.$href.'" style="font-weigth:bold; font-size:'.$font.'px;">'.$row[0].'</a></li> ';
	     $tagsearch .="'".$row[0]."',"."\n";
	     $tc++;
              
	}
	$tagsearch .= "''";
        $tagcloud.='</ul>';
	#if (CONF_TAG_VIEW_SW != 0) $tagcloud = "<div id=\"wpcumuluscontent\" style=\"position: relative; z-index: 0;\">".ERROR_FLASH_REQUARED."</div>";
        #if (DB_CHARSET=='utf8') $tagcloud=Utf8Win($tagcloud);
	$smarty->assign("tagcloud",$tagcloud);
	$smarty->assign("tagsearch",$tagsearch);


?>