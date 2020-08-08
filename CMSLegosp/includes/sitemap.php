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
 

if (isset($_GET["sitemap"]))    
    {

        $sitemap_categorys=All_Categories(0,0);
        $smarty->assign("sitemap_categorys", $sitemap_categorys);  
        $smarty->assign("main_content_template", "sitemap.tpl.html");
    }
?>