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
 

if (!isset($_SESSION['cust_id']) && isset($_COOKIE['cust_id'])) {
    $sql = "SELECT * FROM " . CUST_TABLE . " WHERE custID='" . (int) $_COOKIE['cust_id'] . "'";
    $r = db_assoc($sql);
    $_SESSION['cust_id'] = $r['custID'];
    $_SESSION['cust_login'] = "yes";
    unset($r['custID'], $r['cust_password']);
    if (!$r['openID'])
        unset($r['openID']);
    $_SESSION['userinf'] = $r;
}


//	LOGOUT
if (isset($_GET["cust_logout"])) {
    unset($_SESSION['cust_login']);
    $_SESSION['cust_login_openID'] = "no";
    unset($_SESSION['userinf']);
    unset($_SESSION['cust_id']);
    setcookie("cust_id", '', time() + 7889400, '/'); //3 monthes
    header('Location: ./');
    exit;
} elseif (isset($_POST["cust_login"]) && isset($_POST["cust_email"]) && isset($_POST["cust_password"])) {

    $sql = "SELECT * FROM " . CUST_TABLE . " WHERE (openID IS NULL or openID='') and  cust_email=" . int_text($_POST["cust_email"]);
    $r = db_assoc($sql);
    // and cust_password='".$_POST["cust_password"]."'
    if ($r) {       #if(get_magic_quotes_gpc()) $_POST["cust_password"]=stripslashes($_POST["cust_password"]);  
        if ($r['cust_email'] == $_POST["cust_email"] && $r['cust_password'] == md5($_POST["cust_password"])) {

            $_SESSION['cust_id'] = $r['custID'];
            $_SESSION['cust_login'] = "yes";
            unset($r['custID'], $r['openID'], $r['cust_password']);
            $_SESSION['userinf'] = $r;

            setcookie("cust_id", $_SESSION['cust_id'], time() + 7889400, '/'); //3 monthes
            header('Location: /index.php?cust_login=yes');
            exit;
        } else {

            $smarty->assign("cust_firstname", $r['cust_firstname']);
            $smarty->assign("cust_email", $r['cust_email']);
            $smarty->assign("cust_error_password", "yes");
        }
    } else {
        $smarty->assign("cust_no_such_email", "yes");
    }
} elseif (isset($custID) && ($custID > 0)) {

    $sql = 'SELECT * FROM ' . CUST_TABLE . ' WHERE openID=' . (int) $authData['uid'];
    $r = db_assoc($sql);
    $_SESSION['cust_id'] = $r['custID'];
    unset($r['custID'], $r['cust_password'], $r['openID'], $r['provider']);
    $_SESSION['userinf'] = $r;
    $_SESSION['userinf']['cust_lastname'] = $authData['last_name'];
    $_SESSION['userinf']['cust_firstname'] = $authData['first_name'];

    $_SESSION['cust_login_openID'] = "yes";
    setcookie("cust_id", $_SESSION['cust_id'], time() + 7889400, '/'); //3 monthes
    #setcookie("cust_firstname", $authData['first_name'], time()+7889400,'/'); //3 monthes
    header("Location: /index.php?cust_login=yes");
    exit;
}
?>