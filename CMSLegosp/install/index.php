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
 
	session_start();
	define('SCRIPT_VERSION', '6.3.1');
	define('SCRIPT_NAME', '"Legosp"');
    define('PATH', realpath('../'));
    define('HOST', 'http://'.$_SERVER['HTTP_HOST']);
    define('LICENSE', file_get_contents('./license.txt'));
	//$msg = '';

function ValidateAddress($address) {
	if (function_exists('filter_var')) { //Introduced in PHP 5.2
		if (filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
			return false;
		} else {
			return true;
		}
	} else {
		return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
	}
}

    //Минимальная версия PHP
    $php_req = array();
    $php_req['major']       = '5';
    $php_req['minor']       = '0';
    $php_req['release']     = '5';

    $php_req_ver = $php_req['major'] * 10000 + $php_req['minor'] * 100 + $php_req['release'];

    //Список необходимых расширений PHP
    $ext_req = array();
    $ext_req['iconv']       = 'iconv';
    $ext_req['GD']          = 'gd';
    $ext_req['Mbstring']   = 'mbstring';

	if (isset($_POST['install'])) {
					$_POST["db_charset"]='utf8';
		$_SESSION["db_server"] = $_POST["db_host"];
		$_SESSION["db_name"] = $_POST["db_name"];
		$_SESSION["db_user"] = $_POST["db_user"];
		$_SESSION["db_prefix"] = $_POST["db_prefix"];
		$_SESSION["db_pass"] = $_POST["db_pass"];
		$_SESSION["db_charset"] = $_POST["db_charset"];
		$_SESSION["shop_name"] = $_POST["shop_name"];
		$_SESSION["shop_url"] = $_POST["shop_url"];
		$_SESSION["admin_login"] = $_POST["admin_login"];
		$_SESSION["general_email"] = $_POST["general_email"];

		$msg = '';
		
		if (isset($_SESSION["error"])) {
			$msg .= $_SESSION["error"];
		}

		if(!empty($_REQUEST['admin_login'])) { $_POST["admin_login"] = $_REQUEST['admin_login']; } else { $msg .= 'Необходимо указать логин администратора!<br/>'; }
		if(!empty($_REQUEST['admin_pass'])) { $_POST["admin_pass"] = $_REQUEST['admin_pass']; } else { $msg .= 'Необходимо указать пароль администратора!<br/>'; }
		if(!empty($_REQUEST['shop_url'])) { $_POST["shop_url"] = $_REQUEST['shop_url']; } else { $msg .= 'Необходимо указать имя сайта!<br/>'; }
		if(!empty($_REQUEST['db_host'])) { $_POST["db_host"] = $_REQUEST['db_host']; } else { $msg .= 'Необходимо указать сервер БД!<br/>'; }
		if(!empty($_REQUEST['db_name'])) { $_POST["db_name"] = $_REQUEST['db_name']; } else { $msg .= 'Необходимо указать название БД!<br/>'; }
		if(!empty($_REQUEST['db_user'])) { $_POST["db_user"] = $_REQUEST['db_user']; } else { $msg .= 'Необходимо указать пользователя БД!<br/>'; }				
		if(!empty($_REQUEST['db_prefix'])) { $_POST["db_prefix"] = $_REQUEST['db_prefix']; } else { $_POST["db_prefix"] = 'SS'; }
		if(!empty($_REQUEST['shop_name'])) { $_POST["shop_name"] = $_REQUEST['shop_name']; } else { $_POST["shop_name"] = 'Powered by '.SCRIPT_NAME; }
		
		if (!ValidateAddress($_POST['general_email'])) {$msg .= "Некорректно заполнен <b>Контактный email адрес</b><br />";}
		
		if (!is_writable(PATH."/cfg/tables.inc.php") && $_POST['db_prefix'] != "SS") {$msg .= "Выставьте права на запись файлу <b>cfg/tables.inc.php</b> (0666)<br />";}

		if (!$msg) {
		        include_once("../includes/database/mysql.php");   
			$conn = db_connect($_POST["db_host"], $_POST["db_user"], $_POST["db_pass"],$_POST["db_name"]);
			if (!$conn) {
				$msg .= "Ошибка соединения с базой данных.<br />Пожалуйста, убедитесь, что Вы правильно указываете адрес базы данных,<br />а также имя пользователя и пароль для доступа к ней<br />(эти данные могут быть получены в службе поддержки Вашего хостинг провайдера)<br />";
			}
		}

		if (!$msg) {

			//choose database file to include
			
			include ('../cfg/functions.php');

			$f = @fopen(PATH."/cfg/connect.inc.php","w");
			$s = "<?php
	//database connection settings
	define('DB_HOST', '".$_POST["db_host"]."'); // database host
	define('DB_USER', '".$_POST["db_user"]."'); // username
	define('DB_PASS', '".$_POST["db_pass"]."'); // password
	define('DB_NAME', '".$_POST["db_name"]."'); // database name
	define('DB_CHARSET', '".$_POST["db_charset"]."'); // database name
	define('ADMIN_LOGIN', '".base64_encode($_POST["admin_login"])."'); //administrator's login
	define('ADMIN_PASS', '".md5($_POST["admin_pass"])."'); //administrator's login
	//database tables
	include(dirname(__FILE__).\"/tables.inc.php\");
?>";
			fputs($f, $s);
			fclose($f);

			if (isset($_POST['db_prefix'])) {
				/*$f = file(PATH."/cfg/tables.inc.php");
				$cf = count($f);
					for ($i = 0; $i <= $cf; $i++) {
						$tmp .= $f[$i];
					}
				$tmp = preg_replace("/SS_/", $_POST['db_prefix']."_", $tmp);
				$sh = fopen(PATH."/cfg/tables.inc.php", "w") or die("<p>Нет прав для записи</p>");
				flock($sh, 2);
				fputs($sh, $tmp);
				flock($sh, 3);
				fclose($sh);*/ // Подходит только для одной (первой) установки, если переустанавливать, не прокатит.
				
				$ft = @fopen(PATH."/cfg/tables.inc.php","w");
				$st = "<?php
	//Legosp database tables
	@define('PRODUCTS_TABLE', '" . $_POST['db_prefix'] . "_products');
	@define('ORDERS_TABLE', '" . $_POST['db_prefix'] . "_orders');
	@define('ORDERED_CARTS_TABLE', '" . $_POST['db_prefix'] . "_orders_carts');
	@define('CATEGORIES_TABLE', '" . $_POST['db_prefix'] . "_categories');
	@define('SPECIAL_OFFERS_TABLE', '" . $_POST['db_prefix'] . "_special_offers');
	@define('TAGS_TABLE', '" . $_POST['db_prefix'] . "_tags');
	@define('PAGES_TABLE','" . $_POST['db_prefix'] . "_pages');
	@define('NEWS_TABLE','" . $_POST['db_prefix'] . "_news');
	@define('THUMB_TABLE','" . $_POST['db_prefix'] . "_thumb');
	@define('REVIEW_TABLE','" . $_POST['db_prefix'] . "_review');
	@define('BRAND_TABLE','" . $_POST['db_prefix'] . "_brand');
	@define('VOTES_TABLE','" . $_POST['db_prefix'] . "_votes');
	@define('VOTES_CONTENT_TABLE','" . $_POST['db_prefix'] . "_votes_content');
	@define('SHARE_TABLE','" . $_POST['db_prefix'] . "_share');
	@define('MANAGER_TABLE','" . $_POST['db_prefix'] . "_manager');
	@define('MANAGER_TABLE_DENY','" . $_POST['db_prefix'] . "_manager_dany');
	@define('AUX_TABLE','" . $_POST['db_prefix'] . "_aux');
	@define('PAYMENT_TABLE','" . $_POST['db_prefix'] . "_payment');
	@define('PAYOPTION_TABLE','" . $_POST['db_prefix'] . "_payoption');
	@define('PRODUCT_OPTIONS_TABLE','" . $_POST['db_prefix'] . "_product_options');
	@define('PRODUCT_OPTIONS_VAL_TABLE','" . $_POST['db_prefix'] . "_products_opt_val_variants'); 
	@define('PRODUCT_OPTIONS_V_TABLE','" . $_POST['db_prefix'] . "_product_options_values');
	@define('CURRENCY_TABLE','" . $_POST['db_prefix'] . "_currency_types');
	@define('CUST_TABLE', '" . $_POST['db_prefix'] . "_customers');
	@define('ORDER_STATUS_TABLE', '" . $_POST['db_prefix'] . "_order_status');
	@define('CATEGORIY_PRODUCT_TABLE', '" . $_POST['db_prefix'] . "_category_product');
	@define( 'MENU_TABLE', '" . $_POST['db_prefix'] . "_menu' );
	@define( 'MENU_EL_TABLE', '" . $_POST['db_prefix'] . "_menu_element' );
	function fileClass(){return unserialize('s:24:\"aHR0cDovL2xlZ29zcC5uZXQ=\";');}
	
?>";
				fputs($ft, $st);
				fclose($ft);
			}

			
			//try to connect to the database using new settings and register administrator
			
			preg_match("/^(http:\/\/)?(.+?)\/?$/i", $_POST["shop_url"], $shop_url);
			$shop_url = str_replace("www.", "", $shop_url[2]);
			$f = @fopen(PATH."/cfg/general.inc.php","w");
			$s = "<?php
	define('CONF_SHOP_NAME', '".$_POST["shop_name"]."');
	define('CONF_SHOP_DESCRIPTION', '".$_POST["shop_name"].", Powered by Legosp');
	define('CONF_SHOP_KEYWORDS', '".$_POST["shop_name"].", Powered by Legosp');
	define('CONF_SHOP_URL', '".$shop_url."');
	define('CONF_GENERAL_EMAIL', '".$_POST["general_email"]."');
	define('CONF_ORDERS_EMAIL', '".$_POST["general_email"]."');
	define('CONF_ADMIN_FILE', 'admin.php');
	define('CONF_ADMIN_FILE_ACCESS', 'access_admin.php');
	define('CONF_SMTP', '0');
	define('CONF_SMTP_HOST', '127.0.0.1');
	define('CONF_SMTP_Port', '25');  
	define('CONF_SMTP_User', '');
	define('CONF_SMTP_Pass', '');
	define('CONF_CURRENCY_AUTO', '0');
	define('CONF_CURRENCY_ID', 1);
	define('CONF_CHPU', 1); 
?>";
			fputs($f, $s);
			fclose($f);

			$sel = NULL;
 
			//create tables
      #$esql=1;
      include_once( PATH . "/cfg/connect.inc.php" );
			include(PATH."/includes/database/install/mysql.php");

			$val = "('Рубли', 'руб.', 1, 1, 0, 'RUB')";
                        
			db_query("INSERT INTO `".CURRENCY_TABLE."` (`Name`, `code`, `currency_value`, `where2show`, `sort_order`, `currency_iso_3`) VALUES ".$val ) or die (db_error()) ;
			$_SESSION["log"] = $_POST["admin_login"];
			$_SESSION["pass"] = $_POST["admin_pass"];
			$_SESSION["access"] = 3;
			$_SESSION["log_name"] = "Администратор";

			if (isset($_POST["fill_db"]) && $_POST["fill_db"] == 1) {//fill DB with demo content
				//fill products and categories tables
				$helper = "[#%int!g%#]"; //helper
				if (file_exists(PATH."/cfg/demo_database.sql")) {
					$fi = implode("",file(PATH."/cfg/demo_database.sql"));


					if ($_POST['db_prefix'] != "SS") {
						$fi = preg_replace("/SS_/", $_POST['db_prefix']."_", $fi);
					}
        $f = explode( "INSERT INTO", $fi );

					for ($i = 1; $i < count($f); $i++) {
						//echo $f[$i]."<br />";
						db_query(trim("INSERT INTO ".str_replace($helper, "INSERT INTO", $f[$i]))) or die ( $f[$i] . '<br>'.db_error());
					}

        $f = explode( "REPLACE", $fi );
        for( $i = 1; $i < count( $f ); $i++ ){
            db_query( trim( "REPLACE " . str_replace( $helper, "REPLACE", $f[$i] ) ) ) or die( $f[$i].'<br>'.db_error());
        }

				}
			}
			//writing .htaccess

			if (strstr($shop_url, "www.")) {
				$shop_url_nowww = str_replace("www.", "", $shop_url);
			} else {
				$shop_url_nowww = "www.".$shop_url;
			}

			$line = Array();

			$f = fopen(PATH."/.htaccess", "r");
			$parurl = parse_url('http://'.$shop_url);
			
			while (!feof($f)) {
				$tmp = fgets($f);
				$tmp = str_replace("nowwwinstall", $shop_url_nowww, $tmp);
				$tmp = str_replace("wwwinstall", $shop_url, $tmp);
				$tmp = str_replace("install", $shop_url, $tmp);
				if (isset($parurl['path']) && trim($parurl['path'])) {
					$tmp = str_replace("RewriteBase     /", 'RewriteBase     '.$parurl['path'].'/', $tmp);
				}
				$line[] = $tmp; 
			}
			fclose($f);

			$i = 0;
			$f = fopen(PATH."/.htaccess", "w");
			while ($line[$i]) {
			    fputs($f, $line[$i]);
			    $i++;
			}
			fclose($f);
			
			@$installed = ( mysqli_error() ? 0 : 1);
		}
	}

// =================================================================================================== //

function getPHPVersion() {
    $version['text'] = phpversion();
    $version['int']  = $version['text'][0] * 10000 + $version['text'][2] * 100 + $version['text'][4];
    return $version;
}

function installCheckFolders() {
	$error = '';
	$folders = array();
	$files = array();
	$folders[] = '/products_pictures';
	$folders[] = '/products_thumb';
	$folders[] = '/userfiles';
    $folders[] = '/core/aux_pages';
    $folders[] = '/core/cache';
	
	$files[] = '/.htaccess';
	$files[] = '/cfg/general.inc.php';
	$files[] = '/cfg/connect.inc.php';
	$files[] = '/cfg/appearence.inc.php';
	$files[] = '/cfg/company.inc.php';
	$files[] = '/cfg/product.inc.php';
	$files[] = '/cfg/shipping.inc.php';
	$files[] = '/cfg/redirect.inc.php';
	$files[] = '/cfg/votes.inc.php';
	$files[] = '/cfg/tables.inc.php';
	$files[] = '/cfg/error.log';
	$files[] = '/core/aux_pages';
	$files[] = '/core/aux_pages/aux1';
	$files[] = '/core/aux_pages/aux2';
	$files[] = '/core/aux_pages/contact';
	$files[] = '/core/aux_pages/index';
	$files[] = '/core/aux_pages/live_counts';
	
	echo '<table align="center">';
		echo '<tr>';
			echo '<th width="260">Папка/Файл</th>';
			echo '<th style="text-align:center" width="170">Доступна для записи</th>';
		echo '</tr>';

	foreach($folders as $key=>$folder){
		$right = true;
		if (!@is_writable(PATH.$folder)) {
			if (!@chmod(PATH.$folder, 0777)) {
				$right = false;
			}
		}
		echo '<tr>';
			echo '<td class="folder">'.$folder.'</td>';
			echo '<td style="text-align:center">'; if ($right) {echo '<span style="color:green">Да</span>';} else {echo '<span style="color:red">Нет</span>';$error .= "Необходимо выставить права на запись (0777) каталогу <strong>".$folder."</strong><br />";} echo '</td>';
		echo '</tr>';
	}
	
	foreach($files as $fkey=>$file) {
		$fright = true;
		if (!@is_writable(PATH.$file)) {
			if (!@chmod(PATH.$file, 0666)) {
				$fright = false;
			}
		}
		echo '<tr>';
			echo '<td class="file">'.$file.'</td>';
			echo '<td style="text-align:center">'; if ($fright) {echo '<span style="color:green">Да</span>';} else {echo '<span style="color:red">Нет</span>';$error .= "Необходимо выставить права на запись (0666) файлу <strong>".$file."</strong><br />";} echo '</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	
	if (!empty($error)) {
		$_SESSION["error"] = $error;
	} else {
		unset($_SESSION["error"]);
	}
}

// =================================================================================================== //

function installCheckExtensions() {

    global $ext_req;
    global $php_req;
    global $php_req_ver;

	echo '<table align="center">';
		echo '<tr>';
			echo '<th width="300">Расширение PHP</th>';
			echo '<th style="text-align:center" width="70">Установлено</th>';
		echo '</tr>';
        
    $all_right = true;

	foreach($ext_req as $name=>$ext){
		$right = true;
		if(!extension_loaded($ext)){
            $right = false;
            $all_right = false;
		}
		echo '<tr>';
			echo '<td class="extension"><a href="http://ru.php.net/manual/ru/book.'.$ext.'.php" title="Посмотреть описание на сайте PHP" target="_blank">'.$name.'</td>';
			echo '<td style="text-align:center">'.($right ? '<span style="color:green">Да</span>' : '<span style="color:red">Нет</span>').'</td>';
		echo '</tr>';
	}

		echo '
			<tr><td colspan="2"><br /><strong>Информация о сервере:</strong><br /><br /></td></tr>
			<tr>
				<td class="extension">'.$_SERVER['SERVER_SOFTWARE'].'</td>
				<td style="text-align:center;vertical-align: top;"><span style="color:green">Ok</span></td> 
			</tr>
			<tr>
				<td class="extension">';
                 ob_start();
                 phpinfo(INFO_MODULES);
                 $info = ob_get_contents();
                 ob_end_clean();
                 $info = stristr($info, 'Client API version');
                 preg_match('/[1-9].[0-9].[1-9][0-9]/', $info, $match);
                 $gd = $match[0];
                 echo 'MySQL:  '.$gd.' <br />
				</td><td style="text-align:center;">';

				if ($gd>=5) {
					echo '<span style="color:green">Ok</span>';
				} else {
					echo '<span style="color:red">No</span>';
				}
			echo '</td></tr>
		</table>';

    if (!$all_right) {
        echo '<p>Для установки отсутствующих расширений обратитесь к вашему хостеру.</p>';
    }

    $php_ver = getPHPVersion();

    $right = true;
    $php53 = false;
    
    if ($php_ver['int'] < $php_req_ver) { $right=false; }

    echo '<p><strong>Версия PHP:</strong> '.$php_ver['text'].' &mdash; '.($right ? '<span style="color:green">Оk</span>' : '<span style="color:red">требуется '.$php_req['major'].'.'.$php_req['minor'].'.'.$php_req['release'].' или выше</span>').'</p>';

    if (!$right){
        echo '<p>Для обновления PHP обратитесь к своему хостеру.</p>';
    }
}

// =================================================================================================== //
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=SCRIPT_NAME;?> - Установка</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
	<script src='./js/jquery.js' type='text/javascript'></script>
	<script src='./js/install.js' type='text/javascript'></script>
	<link type='text/css' href='./css/styles.css' rel='stylesheet' media='screen' />
	<!--[if IE]>
		<link type='text/css' href='./css/ie.css' rel='stylesheet' media='screen' />
	<![endif]-->
</head>

<body>
	<table id="wrapper" align="center">
	<tr><td>
		<div id="header">
			<img src="./images/logo.jpg" border="0" class="logo" />
			<h1>Версия <?=SCRIPT_VERSION;?></h1>
		</div>

		<?php if (!isset($msg)) { ?>

		<form class="wizard" action="#" method="post">
			<div class="wizard-nav" align="center">			
				<a href="#start">Начало</a>
				<a href="#php">Проверка PHP</a>
				<a href="#folders">Проверка прав</a>
				<a href="#install">Установка</a>
			</div>
			<!-- ================================================================ -->

			<div id="start" class="wizardpage">
				<h2>Добро пожаловать в интернет-магазин <?=SCRIPT_NAME;?></h2>
				<p>
					Сейчас скрипт проверит сервер на соответствие техническим требованиям и совершит все 
					необходимые настройки для начала работы с <?=SCRIPT_NAME;?>.
				</p>
                <p>Устанавливать <?=SCRIPT_NAME;?> желательно в корневую папку сайта.</p>
				<p>Перед началом установки создайте новую базу данных MySQL на вашем хостинге.</p>
				<p>Как установить магазин на локальный компьютер с ОС Windows&trade; для тестирования, читайте в <a href="https://http://legosp.net/instructions.pdf" target="_blank">инструкции</a> на официальном сайте.</p>
                <p>Вы должны согласиться с условиями лицензии для установки магазина.</p>

				<table cellpadding="0" cellspacing="0" border="0">
					<tr id="license">
						<td colspan="2"><div class="license"><?=LICENSE;?></div></td>
					</tr>
					<tr style="margin-top: 20px;display: block;">
						<td width="20"><input required type="checkbox" id="license_agree" onclick="checkAgree();"/></td>
						<td><label for="license_agree">Я согласен с условиями</label>&nbsp;<a target="_blank" style="cursor:pointer;" onclick="$('#license').toggle();">лицензии</a></td>
					</tr>
				</table>
			</div>

			<!-- ================================================================ -->

			<div id="php" class="wizardpage">
				<h2>Проверка установленных расширений PHP</h2>
				<img src="./images/setting.png" border="0" />
				<p>Для корректной работы <?=SCRIPT_NAME;?> необходимо чтобы PHP на вашем сервере имел установленные расширения, перечисленные ниже.</p>
				<?php installCheckExtensions(); ?>
			</div>

			<!-- ================================================================ -->
			
			<div id="folders" class="wizardpage">
				<h2>Проверка прав на запись папок и файлов</h2>
				<img src="./images/chmod.png" border="0" />
				<p>Для правильной работы <?=SCRIPT_NAME;?> указанные ниже папки и файлы должны быть доступны для записи. Сменить права на папки можно с помощью FTP-клиента, например Total Commander.<br />
				<img src="./images/folder-icon.png" style="margin:0 5px;padding:0 5px;" border="0" /> Для папок нужно установить права 0777<br />
				<img src="./images/file-icon.png" style="margin:0 5px;padding:0 5px;" border="0" /> Для файлов нужно установить права 0666</p>

				<?php
					installCheckFolders();
					if (isset($_SESSION["error"])) {echo "<font color='red'><strong>Вы не сможете продолжить установку магазина пока все папки/файлы не будут доступны для записи.</strong></font><span id='error' style='display: none;'>yes</span>";}
				?>
				
				<p>Если вы не знаете или сомневаетесь какие права нужно установить, чтобы сделать папку доступной для записи, обратитесь в техническую поддержку вашего хостинга.</p>
			</div>

			<!-- ================================================================ -->
			
		  <div id="install" class="wizardpage">
                <h2>Установка магазина</h2>
                <p>Заполните форму и нажмите "Установить" для завершения процесса.</p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="140" valign="top">
                            <img src="./images/go_setup.png" border="0" />
                        </td>
                        <td valign="top">
                            <table width="" border="0" cellpadding="4" cellspacing="0" style="margin-bottom:10px">
                              <tr>
                                <td width="220">Название магазина:</td>
                                <td align="center"><input name="shop_name" type="text" class="txt" value="<?php echo (isset($_SESSION['shop_name']) && !empty($_SESSION['shop_name'])) ? $_SESSION['shop_name'] : "Powered by ".str_replace("\"", "", SCRIPT_NAME);?>"></td>
                              </tr>
								<tr>
									<td><span class="required">*</span>&nbsp;URL магазина: http://</td>
									<td><input type="text" name="shop_url" class="txt" value="<?php if (!isset($_SESSION['shop_url']) || empty($_SESSION['shop_url'])){ $pathinfo=pathinfo($_SERVER['REQUEST_URI']); echo ($_SERVER['HTTP_HOST']); if ($pathinfo['dirname'] != '/' && $pathinfo['dirname'] != '\\') echo str_replace("/install", "", $pathinfo['dirname']);} else {echo $_SESSION['shop_url'];} ?>" /></td>
								</tr>
								<tr>
								  <td><span class="required">*</span>&nbsp;Контактный email магазина:</td>
								  <td><input type="text" name="general_email" class="txt" value="<?php echo isset($_SESSION["general_email"]) ? $_SESSION["general_email"] : "";?>" /></td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Логин администратора сайта:</td>
                                <td align="center"><input name="admin_login" type="text" class="txt" value="<?php echo isset($_SESSION["admin_login"]) ? $_SESSION["admin_login"] : "admin";?>" /></td>
                              </tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Пароль администратора сайта:</td>
                                <td align="center"><input name="admin_pass" type="password" class="txt" /></td>
                              </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Сервер MySQL: </td>
                                <td align="center"><input name="db_host" type="text" class="txt" value="<?php echo isset($_SESSION["db_host"]) ? $_SESSION["db_host"] : "localhost";?>" /></td>
                              </tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Имя базы данных: </td>
                                <td align="center"><input name="db_name" type="text" class="txt" value="<?php echo isset($_SESSION["db_name"]) ? $_SESSION["db_name"] : "";?>" /></td>
                              </tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Пользователь БД: </td>
                                <td align="center"><input name="db_user" type="text" class="txt" value="<?php echo isset($_SESSION["db_user"]) ? $_SESSION["db_user"] : "root";?>" /></td>
                              </tr>
                              <tr>
                                <td><span class="required">*</span>&nbsp;Пароль пользователя БД: </td>
                                <td align="center"><input name="db_pass" type="password" class="txt" value="<?php echo isset($_SESSION["db_pass"]) ? $_SESSION["db_pass"] : "";?>" /></td>
                              </tr>
                              <tr>
                                <td title="Если не заполнить, по умолчанию будет SS">Префикс таблиц в базе данных: </td>
                                <td align="center"><input name="db_prefix" type="text" class="txt" value="<?php echo isset($_SESSION["db_prefix"]) ? $_SESSION["db_prefix"]:"SS";?>" /></td>
                              </tr>
					  	                      <tr>
                                <td title="Заполнить базу данных магазина демонстрационными товарами">Демо-данные:</td>
                                <td align="center" valign="top">
                                    <label><input name="fill_db" type="radio" value="1" checked="checked" /> Да</label>
                                    <label><input name="fill_db" type="radio" value="0" /> Нет</label>
                                </td>
                              </tr>
                            </table>
                        </td>
                    </tr>
                </table>
				
			</div>
		</form>
		
		<?php }
			if (isset($msg) && @$msg != ''){ 
				echo '<div style="margin-left:52px;">';
				echo '<br /><h2>Обнаружена ошибка!</h2>';
				echo '<p style="color:red">'.$msg.'</p>';
				echo '<p><a href="./index.php">Повторить ввод данных</a></p>';
				echo '</div>';
			}
			
			if (isset($installed)) {
				if($installed){
					echo '<div style="margin-left:52px;">';
					echo '<br /><h2>Установка '.SCRIPT_NAME.' завершена!</h2>';
					echo '<div>';
					echo '<p>Магазин установлен и готов к работе.</p>';
					echo '<div style="background:url(/install/images/warning.png) no-repeat;padding-left:24px;margin-top:30px;margin-bottom:30px;">
                            <div style="margin-bottom:6px;"><strong>Внимание!</strong></div>
                            До перехода на сайт необходимо удалить каталог "<strong>install</strong>"<br />
                            на сервере вместе со всеми находящимися в нём файлами!
                          </div>';
					echo '<p style="font-size:18px"><a href="../index.php">Перейти на сайт</a> | <a href="../admin.php">Перейти в панель управления</a></p>';
					echo '<div style="border-top: 1px solid #cccccc;padding-top:15px;">
                            <a id="tutorial" href="https://legosp.net/files/Legosp_Manual.pdf" target="_blank">Инструкция для начинающих</a>
                          </div>';
					echo '</div>';
					echo '</div>';

				unset($_SESSION["db_host"],$_SESSION["db_name"],$_SESSION["db_user"],$_SESSION["db_prefix"],$_SESSION["db_pass"],$_SESSION["db_charset"],$_SESSION["shop_name"],$_SESSION["shop_url"],$_SESSION["admin_login"],$_SESSION["general_email"]);
				}
			}
		?>
	
		<div id="footer">
			<a href="https://legosp.net/" target="_blank"><strong><?=SCRIPT_NAME;?></strong></a> &copy; 2010-<?php echo date('Y'); ?><br />Design by <a href="https://forum.legosp.net" style="color:#666666;" target="_blank">Legosp</a>
		</div>

	</td></tr></table>
</body>
</html>
