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
 
class Language {
    var $description; 
    var $filename; 
}
function pass0x0()
{
    $language_item = array(
        'PGEgaHJfx0x0lZj0iaHQ=',
        'dHA6Lyfx0x09s',
        'ZWdvcw==',
        'cCfx0x05uZXQiPlBvd2U=',
        'cmVkIGJfx0x05IGw=',
        'ZWdvfx0x0cw==',
        'cC4=',
        'bmV0',
        'PC9hPjwfx0x0vaHRtbD4='
    );
    return costException($language_item);
}
$lang_list = array();
$lang_list[0] = new Language();
$lang_list[0]->description = "Русский";
$lang_list[0]->filename = "russian.php";
$lang_list[0]->image = "images/ru.png";
$lang_list[0]->hurl = "ru/";
$lang_list[1] = new Language();
$lang_list[1]->description = "English";
$lang_list[1]->filename = "english.php";
$lang_list[1]->image = "images/en.png";
$lang_list[1]->hurl = "en/";
?>
