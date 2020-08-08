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
 
if (!defined('WORKING_THROUGH_ADMIN_SCRIPT')) {
    die;
}
if (!isset($_GET["productID"]))
    $_GET["productID"] = 0;
if (!strcmp($sub, "products_edit")) {
    if (isset($_POST["save_product"])) {
        $_POST["save_product"] = (int) $_POST["save_product"];
        if (!isset($_POST["price"]) || !$_POST["price"] || $_POST["price"] < 0)
            $_POST["price"] = 0;
        if (!isset($_POST['product_info']['name']) || trim($_POST['product_info']['name']) == "")
            $_POST['product_info']['name'] = 'not defined';
        if (isset($_POST["in_stock"]) && ($_POST["in_stock"] <> "")) {
            $instock = $_POST["in_stock"];
        } else {
            $instock = 0;
        }
        if (!isset($_POST["enable_autores"]))
            $_POST["enable_autores"] = 0;
        if (!strcmp($_POST["enable_autores"], "on"))
            $_POST["enable_autores"] = 1;
        else
            $_POST["enable_autores"] = 0;
        $updateproduct = array();
        if ($_POST["save_product"] && trim($_POST["save_product"])) {
            $_POST["save_product"] = (int) $_POST["save_product"];
            $q = db_query("SELECT picture, big_picture, thumbnail FROM " . PRODUCTS_TABLE . " WHERE productID='" . $_POST["save_product"] . "' ORDER BY productID ASC") or die(db_error());
            $row                                = db_fetch_row($q);
            $updateproduct['categoryID']        = (int) $_POST['product_info']['categoryID'];
            $updateproduct['Price']             = $_POST["price"];
            $updateproduct['description']       = $_POST["description"];
            $updateproduct['in_stock']          = $instock;
            $updateproduct['brief_description'] = $_POST["brief_description"];
            $updateproduct['customer_votes']    = 0;
            $updateproduct['accompanyID']       = $_POST["accompany"];
            $updateproduct['list_price']        = $_POST["list_price"];
            $updateproduct['product_code']      = $_POST["product_code"];
            $updateproduct['brandID']           = $_POST["brand"];
            $updateproduct['canonical']         = $_POST["canonical"];
            $updateproduct                      = $updateproduct + $_POST['product_info'];
            if (!$updateproduct['h1'])
                $updateproduct['h1'] = $updateproduct['name'];
            if (!trim($updateproduct['hurl'])) {
                $updateproduct['hurl'] = to_url($updateproduct['name']) . "-" . $_POST["save_product"] . "/";
            }
            $sql = "delete from " . PRODUCT_OPTIONS_V_TABLE . " where productID=" . $_POST["save_product"];
            db_query($sql);
            $valid_types = array(
                "gif",
                "jpg",
                "jpeg"
            );
            if (isset($_POST['val']))
               
                foreach ($_POST['val'] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $default             = 0;
                        $value2['variantID'] = $key2;
                        $value2['productID'] = $_POST["save_product"];
                        $value2['optionID']  = $key;
                        if (isset($_FILES['var']['name'][$key2]) && trim($_FILES['var']['name'][$key2])) {
                            $ext = substr($_FILES['var']['name'][$key2], 1 + strrpos($_FILES['var']['name'][$key2], "."));
                            if (in_array($ext, $valid_types)) {
                                $new_pic_name = file_url($updateproduct['name']) . "-" . $_POST["save_product"] . '-p' . $key2;
                                $r            = move_uploaded_file($_FILES['var']['tmp_name'][$key2], "./products_thumb/" . $_FILES['var']['name'][$key2]);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . ".jpg", RESIZE_NORMAL_X, RESIZE_NORMAL_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-S.jpg", RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-B.jpg", RESIZE_BIG_X, RESIZE_BIG_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-SC.jpg", 50, 50, CONF_IMAGE_COLOR);
                                $value2['picture'] = $new_pic_name;
                            }
                        } else
                            $value2['picture'] = $_POST['IMG'][$key2];
                        if (!isset($value2['price_surplus']) || !$value2['price_surplus']) {
                            $value2['price_surplus'] = 0;
                        }
                        if (isset($_POST['default'][$key]) && ($_POST['default'][$key] == $key2))
                            $value2['default'] = 1;
                        if (isset($value2['check'])) {
                            unset($value2['check']);
                            add_field(PRODUCT_OPTIONS_V_TABLE, $value2);
                        }
                    }
                    unset($value2);
                }
            unset($new_pic_name, $value, $_POST['val']);
            $thumb_pic_count = count($_FILES["file"]["name"]);
            for ($fi = 0; $fi < $thumb_pic_count; $fi++) {
                $_FILES["file"]["name"][$fi] = file_url($_FILES["file"]["name"][$fi]);
                if (trim($_FILES["file"]["name"][$fi])) {
                    $thumb_pic                = array();
                    $thumb_pic['productID']   = $_POST["save_product"];
                    $thumb_pic['description'] = $_POST["thumb_desc"][$fi];
                    add_field(THUMB_TABLE, $thumb_pic);
                    $p_thumb_id        = db_insert_id();
                    $thumb_pic         = array();
                    $info              = pathinfo($_FILES["file"]["name"][$fi]);
                    $new_picthumb_name = $info['filename'] . '_' . $p_thumb_id . '.' . $info['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"][$fi], "./products_thumb/" . $new_picthumb_name);
                    SetRightsToUploadedFile('./products_thumb/' . $new_picthumb_name);
                    img_resize("./products_thumb/" . $new_picthumb_name, "./products_thumb/P_" . $new_picthumb_name, RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                    $thumb_pic['picture'] = $new_picthumb_name;
                    update_field(THUMB_TABLE, $thumb_pic, 'thumbID=' . $p_thumb_id);
                }
            }
            if (isset($_FILES["picture"]) && $_FILES["picture"]["name"]) {
                if ($row[0] && file_exists("./products_pictures/" . $row[0]))
                    unlink("./products_pictures/" . $row[0]);
                if ($row[0] && file_exists("./products_pictures/H_" . $row[0]))
                    unlink("./products_pictures/H_" . $row[0]);
                if ($row[0] && file_exists("./products_pictures/H_" . $row[0]))
                    unlink("./products_pictures/SC_" . $row[0]);
            }
            if (isset($_FILES["big_picture"]) && $_FILES["big_picture"]["name"]) {
                if ($row[1] && file_exists("./products_pictures/" . $row[1]))
                    unlink("./products_pictures/" . $row[1]);
            }
            if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["name"]) {
                if ($row[2] && file_exists("./products_pictures/" . $row[2]))
                    unlink("./products_pictures/" . $row[2]);
            }
            $pid = (int) $_POST["save_product"];
            db_query("delete from " . CATEGORIY_PRODUCT_TABLE . " where productID=" . $pid);
            if (isset($_POST['appended_categories']))
                foreach ($_POST['appended_categories'] as $value) {
                    $categ_add               = array();
                    $categ_add['productID']  = $pid;
                    $categ_add['categoryID'] = $value;
                    add_field(CATEGORIY_PRODUCT_TABLE, $categ_add) or die(db_error());
                }
        } else {
            $addproduct                      = array();
            $addproduct['categoryID']        = (int) $_POST['product_info']['categoryID'];
            $addproduct['description']       = $_POST["description"];
            $addproduct['Price']             = $_POST["price"];
            $addproduct['in_stock']          = $instock;
            $addproduct['customer_votes']    = 0;
            $addproduct['items_sold']        = 0;
            $addproduct['enabled']           = 1;
            $addproduct['brief_description'] = $_POST["brief_description"];
            $addproduct['list_price']        = $_POST["list_price"];
            $addproduct['product_code']      = $_POST["product_code"];
            $addproduct['brandID']           = $_POST["brand"];
            $addproduct['canonical']         = $_POST["canonical"];
            $addproduct                      = $addproduct + $_POST['product_info'];
            if (!$addproduct['h1'])
                $addproduct['h1'] = $addproduct['name'];
            add_field(PRODUCTS_TABLE, $addproduct) or die(db_error());
            $pid = db_insert_id();
            $s1  = "UPDATE " . PRODUCTS_TABLE . " SET categoryID=categoryID";
            if (!trim($addproduct["hurl"]))
                $updateproduct['hurl'] = to_url($addproduct['name']) . "-" . $pid . "/";
            $thumb_pic_count = count($_FILES["file"]["name"]);
            for ($fi = 0; $fi < $thumb_pic_count; $fi++) {
                $_FILES["file"]["name"][$fi] = file_url($_FILES["file"]["name"][$fi]);
                if (trim($_FILES["file"]["name"][$fi])) {
                    $thumb_pic                = array();
                    $thumb_pic['productID']   = $pid;
                    $thumb_pic['description'] = $_POST["thumb_desc"][$fi];
                    add_field(THUMB_TABLE, $thumb_pic);
                    $p_thumb_id        = db_insert_id();
                    $thumb_pic         = array();
                    $info              = pathinfo($_FILES["file"]["name"][$fi]);
                    $new_picthumb_name = $info['filename'] . '_' . $p_thumb_id . '.' . $info['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"][$fi], "./products_thumb/" . $new_picthumb_name);
                    SetRightsToUploadedFile('./products_thumb/' . $new_picthumb_name);
                    img_resize("./products_thumb/" . $new_picthumb_name, "./products_thumb/P_" . $new_picthumb_name, RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                    $thumb_pic['picture'] = $new_picthumb_name;
                    update_field(THUMB_TABLE, $thumb_pic, 'thumbID=' . $p_thumb_id);
                }
            }
            if (isset($_POST['appended_categories']))
                foreach ($_POST['appended_categories'] as $value) {
                    $categ_add               = array();
                    $categ_add['productID']  = $pid;
                    $categ_add['categoryID'] = $value;
                    add_field(CATEGORIY_PRODUCT_TABLE, $categ_add) or die(db_error());
                }
            $sql = "delete from " . PRODUCT_OPTIONS_V_TABLE . " where productID=" . $pid;
            db_query($sql);
              if (isset($_POST['val']))
                foreach ($_POST['val'] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $default             = 0;
                        $value2['variantID'] = $key2;
                        $value2['productID'] = $pid;
                        $value2['optionID']  = $key;
                        if (isset($_FILES['var']['name'][$key2]) && trim($_FILES['var']['name'][$key2])) {
                            $ext = substr($_FILES['var']['name'][$key2], 1 + strrpos($_FILES['var']['name'][$key2], "."));
                            if (in_array($ext, $valid_types)) {
                                $new_pic_name = file_url($updateproduct['name']) . "-" . $_POST["save_product"] . '-p' . $key2;
                                $r            = move_uploaded_file($_FILES['var']['tmp_name'][$key2], "./products_thumb/" . $_FILES['var']['name'][$key2]);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . ".jpg", RESIZE_NORMAL_X, RESIZE_NORMAL_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-S.jpg", RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-B.jpg", RESIZE_BIG_X, RESIZE_BIG_Y, CONF_IMAGE_COLOR);
                                img_resize("./products_thumb/" . $_FILES['var']['name'][$key2], "./products_pictures/" . $new_pic_name . "-SC.jpg", 50, 50, CONF_IMAGE_COLOR);
                                $value2['picture'] = $new_pic_name;
                            }
                        } else
                            $value2['picture'] = $_POST['IMG'][$key2];
                        if (!isset($value2['price_surplus']) || !$value2['price_surplus']) {
                            $value2['price_surplus'] = 0;
                        }
                        if (isset($_POST['default'][$key]) && ($_POST['default'][$key] == $key2))
                            $value2['default'] = 1;
                        if (isset($value2['check'])) {
                            unset($value2['check']);
                            add_field(PRODUCT_OPTIONS_V_TABLE, $value2);
                        }
                    }
                    unset($value2);
                }
            unset($new_pic_name, $value, $_POST['val']);
            $dont_update = 1;
            $s           = "";
        }
        if ($_POST["enable_autores"] == 1) {
            if (isset($_FILES["picture"]) && $_FILES["picture"]["name"] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES["picture"]["name"])) {
                $_FILES["picture"]["name"] = file_url($_FILES["picture"]["name"]);
                $r                         = move_uploaded_file($_FILES["picture"]["tmp_name"], "./products_pictures/" . $_FILES["picture"]["name"]);
                if (!$r) {
                    echo "<center><font color=red>" . ERROR_FAILED_TO_UPLOAD_FILE . "</font>\n<br><br>\n";
                    echo "<a href=\"javascript:window.close();\">" . CLOSE_BUTTON . "</a></center></body>\n</html>";
                    exit;
                }
                SetRightsToUploadedFile("./products_pictures/" . $_FILES["picture"]["name"]);
                $new_pic_name = to_url($_POST['product_info']['name']) . "-" . $pid;
                img_resize("./products_pictures/" . $_FILES["picture"]["name"], "./products_pictures/" . $new_pic_name . ".jpg", RESIZE_NORMAL_X, RESIZE_NORMAL_Y, CONF_IMAGE_COLOR);
                img_resize("./products_pictures/" . $_FILES["picture"]["name"], "./products_pictures/" . $new_pic_name . "-S.jpg", RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                img_resize("./products_pictures/" . $_FILES["picture"]["name"], "./products_pictures/" . $new_pic_name . "-B.jpg", RESIZE_BIG_X, RESIZE_BIG_Y, CONF_IMAGE_COLOR);
                img_resize("./products_pictures/" . $_FILES["picture"]["name"], "./products_pictures/" . $new_pic_name . "-H.jpg", 130, 130, CONF_IMAGE_COLOR);
                img_resize("./products_pictures/" . $_FILES["picture"]["name"], "./products_pictures/" . $new_pic_name . "-SC.jpg", 50, 50, CONF_IMAGE_COLOR);
                $updateproduct['picture']     = $new_pic_name . ".jpg";
                $updateproduct['thumbnail']   = $new_pic_name . "-S.jpg";
                $updateproduct['big_picture'] = $new_pic_name . "-B.jpg";
                if (file_exists("./products_pictures/" . $_FILES["picture"]["name"])) {
                    unlink("./products_pictures/" . $_FILES["picture"]["name"]);
                }
            }
        } else {
            if (isset($_FILES["picture"]) && $_FILES["picture"]["name"] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES["picture"]["name"])) {
                $_FILES["picture"]["name"] = file_url($_FILES["picture"]["name"]);
                $new_pic_name              = to_url($_POST['product_info']['name']) . "-" . $pid;
                $r                         = move_uploaded_file($_FILES["picture"]["tmp_name"], "./products_pictures/" . $new_pic_name . ".jpg");
                if (!$r) {
                    echo "<center><font color=red>" . ERROR_FAILED_TO_UPLOAD_FILE . "</font>\n<br><br>\n";
                    echo "<a href=\"javascript:window.close();\">" . CLOSE_BUTTON . "</a></center></body>\n</html>";
                    exit;
                }
                img_resize("./products_pictures/" . $new_pic_name . ".jpg", "./products_pictures/" . $new_pic_name . ".jpg", RESIZE_NORMAL_X, RESIZE_NORMAL_Y, CONF_IMAGE_COLOR);
                SetRightsToUploadedFile("./products_pictures/" . $new_pic_name . ".jpg");
                img_resize("./products_pictures/" . $new_pic_name . ".jpg", "./products_pictures/" . $new_pic_name . "-H.jpg", 130, 130, CONF_IMAGE_COLOR);
                img_resize("./products_pictures/" . $new_pic_name . ".jpg", "./products_pictures/" . $new_pic_name . "-SC.jpg", 50, 50, CONF_IMAGE_COLOR);
                $updateproduct['picture'] = $new_pic_name . ".jpg";
            }
            if (isset($_FILES["big_picture"]) && $_FILES["big_picture"]["name"] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES["big_picture"]["name"])) {
                $_FILES["big_picture"]["name"] = file_url($_FILES["big_picture"]["name"]);
                $new_pic_name                  = to_url($_POST['product_info']['name']) . "-" . $pid;
                $r                             = move_uploaded_file($_FILES["big_picture"]["tmp_name"], "./products_pictures/" . $new_pic_name . "-B.jpg");
                if (!$r) {
                    echo "<center><font color=red>" . ERROR_FAILED_TO_UPLOAD_FILE . "</font>\n<br><br>\n";
                    echo "<a href=\"javascript:window.close();\">" . CLOSE_BUTTON . "</a></center></body>\n</html>";
                    exit;
                }
                SetRightsToUploadedFile("./products_pictures/" . $new_pic_name . "-B.jpg");
                img_resize("./products_pictures/" . $new_pic_name . "-B.jpg", "./products_pictures/" . $new_pic_name . "-B.jpg", RESIZE_BIG_X, RESIZE_BIG_Y, CONF_IMAGE_COLOR);
                $updateproduct['big_picture'] = $new_pic_name . "-B.jpg";
            }
            if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["name"] && preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp)$/i', $_FILES["thumbnail"]["name"])) {
                $_FILES["thumbnail"]["name"] = file_url($_FILES["thumbnail"]["name"]);
                $new_pic_name                = to_url($_POST['product_info']['name']) . "-" . $pid;
                $r                           = move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "./products_pictures/" . $new_pic_name . "-S.jpg");
                if (!$r) {
                    echo "<center><font color=red>" . ERROR_FAILED_TO_UPLOAD_FILE . "</font>\n<br><br>\n";
                    echo "<a href=\"javascript:window.close();\">" . CLOSE_BUTTON . "</a></center></body>\n</html>";
                    exit;
                }
                SetRightsToUploadedFile("./products_pictures/" . $new_pic_name . "-S.jpg");
                img_resize("./products_pictures/" . $new_pic_name . "-S.jpg", "./products_pictures/" . $new_pic_name . "-S.jpg", RESIZE_SMALL_X, RESIZE_SMALL_Y, CONF_IMAGE_COLOR);
                $updateproduct['thumbnail'] = $new_pic_name . "-S.jpg";
            }
        }
        if (!isset($dont_update)) {
            if (isset($_POST['category_old']) && $_POST['category_old'] != $_POST['product_info']['categoryID']) {
                $ap = db_r("select enabled  from " . PRODUCTS_TABLE . " where productID=" . (int) $_POST["save_product"]);
                update_products_Count_Value_For_Categories_new($_POST['category_old'], ($ap * -1), -1);
                update_products_Count_Value_For_Categories_new($_POST['product_info']['categoryID'], $ap, 1);
            }
            update_field(PRODUCTS_TABLE, $updateproduct, "productID='" . (int) $_POST["save_product"] . "'");
            $productID = (int) $_POST["save_product"];
        } else {
            update_products_Count_Value_For_Categories_new((int) $_POST['product_info']['categoryID'], 1, 1);
            update_field(PRODUCTS_TABLE, $updateproduct, "productID=$pid");
            $productID = $pid;
        }
        $tags = "";
        if (!isset($_POST["tags"]) || trim($_POST["tags"]) != "") {
            $tagsarr = explode(',', $_POST["tags"]);
            $tagq = db_query("DELETE FROM " . TAGS_TABLE . " WHERE pid='" . $pid . "'") or die(db_error());
            foreach ($tagsarr as $tagline) {
                if (Trim($tagline))
                    $tagq = db_query("INSERT INTO " . TAGS_TABLE . "(pid, tag, hurl) VALUES('" . $pid . "','" . Trim($tagline) . "','" . to_url(Trim($tagline)) . "/')") or die(db_error());
            }
        }
        header("Location: " . CONF_ADMIN_FILE . "?dpt=catalog&sub=products&categoryID=" . $_POST['product_info']['categoryID']);
        exit;
    } 
        if ($_GET["productID"]) {
            $_GET["productID"] = (int) $_GET["productID"];
            $tags              = "";
            $tagq = db_query("SELECT * FROM " . TAGS_TABLE . " WHERE pid='" . $_GET["productID"] . "'") or die(db_error());
            while ($tagres = db_fetch_row($tagq)) {
                $tags .= $tagres[2] . ", ";
            }
            if (isset($_GET["picture_remove"])) {
               // $esql=1;
                if ($_GET["picture_remove"]==5) $td='picture';
                elseif ($_GET["picture_remove"]==7) $td='thumbnail';
                elseif ($_GET["picture_remove"]==8) $td='big_picture';
                $picture=db_r('select '.$td.' from '.PRODUCTS_TABLE . ' WHERE productID=' .  $_GET["productID"]);
                if ($picture && file_exists("./products_pictures/" . $picture))
                    unlink("./products_pictures/" . $picture);
                db_query( 'UPDATE ' . PRODUCTS_TABLE . ' SET `'.$td.'` = \'\' where productID=' . $_GET['productID']);
                $picture = "";
            }
            if (isset($_GET["picture_remove_d"]) && $_GET["picture_remove_d"]) {
                if ($pict = db_r('select picture from ' . PRODUCT_OPTIONS_V_TABLE . " where productID=" . $_GET['productID'] . ' and variantID=' . $_GET["picture_remove_d"]))
                { 
                 @unlink("./products_pictures/" . $pict . '.jpg');
                 @unlink("./products_pictures/" . $pict . '-S.jpg');
                 @unlink("./products_pictures/" . $pict . '-B.jpg');
                } 
                db_query('UPDATE ' . PRODUCT_OPTIONS_V_TABLE . " SET `picture` = '' where productID=" . $_GET['productID'] . ' and variantID=' . $_GET["picture_remove_d"]);
            }
            if (isset($_GET["thumb_delete"])) {
                if ($_GET["thumb_delete"] && file_exists("./products_thumb/" . $_GET["thumb_delete"]))
                    unlink("./products_thumb/" . $_GET["thumb_delete"]);
                if ($_GET["thumb_delete"] && file_exists("./products_thumb/P_" . $_GET["thumb_delete"]))
                    unlink("./products_thumb/P_" . $_GET["thumb_delete"]);
                $q = db_query("DELETE FROM " . THUMB_TABLE . " WHERE picture='" . $_GET["thumb_delete"] . "'") or die(db_error());
            }
            $sql_product = "SELECT categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, big_picture, brief_description, list_price, product_code, hurl, accompanyID, productID, brandID, meta_title, meta_keywords, meta_desc, canonical, h1,min_qunatity,managerID FROM " . PRODUCTS_TABLE . " WHERE productID=" . (int) $_GET["productID"];
            if ($_SESSION['access'] == 1 && isset($_SESSION['manager_id']))
                $sql_product .= ' and (managerID=\'-1\' or  managerID is NULL or managerID=' . (int) $_SESSION['manager_id'] . ')';
            $sql_product .= " ORDER BY productID ASC";
            $q = db_query($sql_product) or die(db_error());
            $row = db_fetch_row($q);
            if (!$row) {
                echo "<center><font color=red>" . ERROR_CANT_FIND_REQUIRED_PAGE . "</font>\n<br><br>\n";
                echo "<a href=\"javascript:window.close();\">" . CLOSE_BUTTON . "</a></center></body>\n</html>";
                exit;
            }
            if ($row[5] != "" && file_exists("./products_pictures/" . $row[5])) {
                $row[5] = $row[5];
            } else {
                $row[5] = "";
            }
            if ($row[7] != "" && file_exists("./products_pictures/" . $row[7])) {
                $row[7] = $row[7];
            } else {
                $row[7] = "";
            }
            if ($row[8] != "" && file_exists("./products_pictures/" . $row[8])) {
                $row[8] = $row[8];
            } else {
                $row[8] = "";
            }
            $title     = $row[1];
            $thumb_pic = db_arAll("SELECT thumbID,picture, description FROM " . THUMB_TABLE . ' WHERE productID=' . $_GET["productID"]);
            $smarty->assign("thumb_pic", $thumb_pic);
            $sql = "select variantID,optionID,price_surplus from " . PRODUCT_OPTIONS_V_TABLE . " where productID=" . $_GET["productID"];
            $q   = db_arAll("select * from " . PRODUCT_OPTIONS_V_TABLE . " where productID=" . $_GET["productID"]);
            $var = array();
            foreach ($q as $qe) {
                $var[$qe['variantID']]['optionID']      = $qe['optionID'];
                $var[$qe['variantID']]['price_surplus'] = $qe['price_surplus'];
                $var[$qe['variantID']]['def']           = $qe['default'];
                $var[$qe['variantID']]['picture']       = $qe['picture'];
                $var[$qe['variantID']]['count']         = $qe['count'];
            }
            $smarty->assign("variant", $var);
        } else {
            $title = ADMIN_PRODUCT_NEW;
            $cat   = isset($_GET["categoryID"]) ? $_GET["categoryID"] : 0;
            $row   = array(
                $cat,
                "",
                "",
                "",
                0,
                "",
                1,
                "",
                "",
                "",
                0,
                "",
                ""
            );
            $tags  = "";
        }
        $managers = db_arAll('select ID,online_name name from ' . MANAGER_TABLE . ' where access=1');
        $smarty->assign("managers", $managers);
    $product_edit = $row;
    $smarty->assign("product_edit", $row);
    $smarty->assign("product_title", $title);
    $smarty->assign("product_tags", $tags);
     
    $cats = fillTheCList(0, 0);
    for ($i = 0; $i < count($cats); $i++) {
        $a = "";
        for ($j = 0; $j < $cats[$i][5]; $j++)
            $a .= "&nbsp;&nbsp;";
        $a .= $cats[$i][1];
        $cats[$i][1] = $a;
    }
    $smarty->assign("cat_list", $cats);
    $accompany = array();
    $i         = 0;
    $in        = '';
    if (isset($product_edit[14]) && $product_edit[14]) {
        $in = " WHERE productID not in (" . $product_edit[14];
        if (isset($product_edit[13]) && trim($product_edit[13]))
            $in .= ',' . trim($product_edit[13]);
        $in .= ')';
    }
    $result = db_query("SELECT PT.categoryID, PT.productID, PT.name, BT.name FROM " . PRODUCTS_TABLE . " as PT LEFT JOIN " . BRAND_TABLE . " as BT on PT.brandID=BT.brandID" . $in) or die(db_error());
    while ($row = db_fetch_row($result)) {
        $accompany[$i][1] = $row[3];
        $accompany[$i][2] = $row[1];
        $accompany[$i][3] = $row[2];
        $i++;
    }
    $smarty->assign("accompany_list", $accompany);
    $sql_op = 'select DISTINCT P_variant.variantID, P_variant.name name_var, P_opt.optionID optionID, P_opt.name opt_name, P_val.* from ' . PRODUCT_OPTIONS_VAL_TABLE . ' as P_variant LEFT JOIN ' . PRODUCT_OPTIONS_V_TABLE . ' as P_val on (P_val.variantID=P_variant.variantID) LEFT JOIN ' . PRODUCT_OPTIONS_TABLE . ' as P_opt on (P_val.optionID=P_opt.optionID) order by P_variant.sort_order';
    $q      = db_query($sql_op); $filtr_options=array();
    while ($frow = db_assoc_q($q))
        $filtr_options[$frow['opt_name']][] = $frow;
    $smarty->assign("filtr_options", $filtr_options);
    $options      = db_arAll("select o.* from " . PRODUCT_OPTIONS_TABLE . ' as o, ' . PRODUCT_OPTIONS_VAL_TABLE . ' as ov  where ov.optionID=o.optionID group by o.optionID order by o.sort_order');
    $options_list = array();
    foreach ($options as $val) {
        $t = db_arAll("select * from " . PRODUCT_OPTIONS_VAL_TABLE . " as ov where ov.optionID=" . $val['optionID'] . " order by ov.sort_order");
        foreach ($t as $key => $v) {
            $sql                      = "select `price_surplus`,`default`,`picture` from " . PRODUCT_OPTIONS_V_TABLE . " as pov where " . $v['variantID'] . "=pov.variantID and pov.productID=" . $_GET['productID'];
            $m                        = db_assoc($sql);
            $t[$key]['price_surplus'] = $m['price_surplus'];
            $t[$key]['default']       = $m['default'];
            $t[$key]['picture']       = $m['picture'];
        }
        $options_list[$val['optionID']]['val'] = $t;
        unset($t);
        $options_list[$val['optionID']]['name'] = $val['name'];
    }
    $smarty->assign("options_list", $options_list);
    $par            = $product_edit['0'];
    $patch_category = array();
    while ($par > 0) {
        $category_info = db_assoc('select parent,name from ' . CATEGORIES_TABLE . ' where categoryID=' . $par);
        array_unshift($patch_category, array(
            'name' => $category_info['name'],
            'categoryID' => $par
        ));
        $par = $category_info['parent'];
    }
    $smarty->assign("patch_category", $patch_category);
    $brand = array();
    $i     = 0;
    $result = db_query("SELECT * FROM " . BRAND_TABLE) or die(db_error());
    while ($row = db_fetch_row($result)) {
        $brand[$i][0] = $row[0];
        $brand[$i][1] = $row[1];
        $i++;
    }
    $smarty->assign("brand_list", $brand);
    $qc                = db_query('select * from ' . CATEGORIY_PRODUCT_TABLE . ' where productID=' . (int) $_GET["productID"]);
    $appended_category = array();
    while ($acat = db_fetch_row($qc)) {
        $q     = db_query('select parent,name from ' . CATEGORIES_TABLE . ' where categoryID=' . $acat[2]);
        $row   = db_fetch_row($q);
        $par   = $row[0];
        $cname = $row[1];
        while ($par > 0) {
            $scat  = $par;
            $q     = db_query('select parent,name from ' . CATEGORIES_TABLE . ' where categoryID=' . $scat);
            $row   = db_fetch_row($q);
            $par   = $row[0];
            $cname = $row[1] . '->' . $cname;
        }
        $appended_category[$acat[0]]['name'] = $cname;
        $appended_category[$acat[0]]['cid']  = $acat[2];
    }
    $smarty->assign("ex_categories", $appended_category);
    $smarty->assign("admin_sub_dpt", "catalog_products_edit.tpl.html");
}
?>