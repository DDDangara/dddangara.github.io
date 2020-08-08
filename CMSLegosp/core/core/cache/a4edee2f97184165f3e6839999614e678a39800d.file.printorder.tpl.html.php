<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-02-27 07:17:24
         compiled from "../css/css_default/theme/printorder.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:12436698805c767fd4b736a2-66799324%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a4edee2f97184165f3e6839999614e678a39800d' => 
    array (
      0 => '../css/css_default/theme/printorder.tpl.html',
      1 => 1550154223,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12436698805c767fd4b736a2-66799324',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'result' => 0,
    'res' => 0,
    'rez' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c767fd4debe14_89878073',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c767fd4debe14_89878073')) {function content_5c767fd4debe14_89878073($_smarty_tpl) {?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr" >

<head>
<title><?php echo @constant('STRING_ORDER_ID');?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value[0];?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo @constant('DEFAULT_CHARSET');?>
" />

<style type="text/css">

html {
	overflow: -moz-scrollbars-vertical;
	margin: 0;
	padding: 0;
}
* {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;

}

</style>
</head>
<body onload="print(); return false;">
  <img src="../css/css_<?php echo @constant('CONF_COLOR_SCHEME');?>
/image/logo0000.png" alt=""/><br />
	<hr style="width: 800px; float: left;" />
	<br />
	<br />
<table cellspacing="0" cellpadding="0" width="800px">
	  <tr>
	    <td style="width: 150px"><b><?php echo @constant('ORDER_ORDERER');?>
:</b></td>
	    <td><?php echo $_smarty_tpl->tpl_vars['result']->value[1];?>
 <?php echo $_smarty_tpl->tpl_vars['result']->value[2];?>
</td>
	  </tr>
	  <tr>
	    <td style="width: 150px"><b><?php echo @constant('ORDER_PHONE');?>
:</b></td>
	    <td><?php echo $_smarty_tpl->tpl_vars['result']->value[6];?>
</td>
	  </tr>
	  <tr>
	    <td style="width: 150px"><b><?php echo @constant('ORDER_EMAIL');?>
:</b></td>
	    <td><?php echo $_smarty_tpl->tpl_vars['result']->value[3];?>
</td>
	  </tr>
	  <tr style="vertical-align: top;">
	    <td style="width: 150px"><b><?php echo @constant('ORDER_ADRESS');?>
:</b></td>
	    <td><?php echo $_smarty_tpl->tpl_vars['result']->value[4];?>
,<br /><?php echo $_smarty_tpl->tpl_vars['result']->value[5];?>
</td>
	  </tr>
	</table>
	<br />
	<br />
	<span style="font-weight: bold;" ><?php echo @constant('STRING_ORDER_ID');?>
: <?php echo $_smarty_tpl->tpl_vars['result']->value[0];?>
</span>
	<br />
	<br />
<?php if ($_smarty_tpl->tpl_vars['result']->value[8]) {?>
	<b><?php echo @constant('ADMIN_MANAGER_NAME_MAIL');?>
:</b> <?php echo $_smarty_tpl->tpl_vars['result']->value[8];?>

	<br />
	<br />
<?php }?>
	<table cellspacing="0" cellpadding="0" width="800px">
	  <tr style="font-weight: bold;">
	    <td></td>
	    <td style="text-align: center; width: 1px;"><?php echo @constant('TABLE_PRODUCT_QUANTITY');?>
</td>
	    <td style="text-align: center; width: 100px;"><?php echo @constant('ADMIN_PRODUCT_PRICE');?>
</td>
	    <td style="text-align: center; width: 100px;"><?php echo @constant('TABLE_PRODUCT_SUMM');?>
</td>
	  </tr>
         <?php  $_smarty_tpl->tpl_vars['rez'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rez']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['res']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rez']->key => $_smarty_tpl->tpl_vars['rez']->value) {
$_smarty_tpl->tpl_vars['rez']->_loop = true;
?>
	  <tr>
	    <td ><?php echo $_smarty_tpl->tpl_vars['rez']->value[0];?>
</td>
	    <td style="text-align: center; width: 1px;"><?php echo $_smarty_tpl->tpl_vars['rez']->value[2];?>
</td>
	    <td style="text-align: right; width: 100px;"><?php echo $_smarty_tpl->tpl_vars['rez']->value[1];?>
</td>
	    <td style="text-align: right; width: 100px;"><?php echo $_smarty_tpl->tpl_vars['rez']->value[4];?>
</td>
	  </tr>
	<?php } ?>
	  <tr style="font-weight: bold;">
	    <td>&nbsp;<br /><?php echo @constant('TABLE_TOTAL');?>
:</td>
	    <td></td>
	    <td></td>
	    <td style="text-align: right; width: 100px;">&nbsp;<br /><?php echo $_smarty_tpl->tpl_vars['result']->value[10];?>
</td>
	  </tr>
	</table>
	<br />
	<b><?php echo @constant('CUSTOMER_COMMENT');?>
</b>
	<br />
	<?php echo $_smarty_tpl->tpl_vars['result']->value[7];?>

	<br />
	<br />
	<hr style="width: 800px; float: left;" />
	<br />
	<br />
    <b><i><?php echo @constant('CONF_SHOP_NAME');?>
<br /><?php echo @constant('CONF_SHOP_URL');?>
</i></b>

</body>
</html><?php }} ?>
