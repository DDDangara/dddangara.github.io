
/*********************************************************************************
*                                                                                *
*   shop-script Legosp - legosp.net                                              *
*   Skype: legoedition                                                           *
*   Email: legoedition@gmail.com                                                 *
*   Лицензионное соглашение: https://legosp.net/info/litsenzionnoe_soglashenie/  *
*   Copyright (c) 2010-2019  All rights reserved.                                *
*                                                                                *
*********************************************************************************/
 
$('.bootstraptable').bootstrapTable({

});

function operateactions(value, row, index) {

    spec='';
    if (row.picture){
        spec= '<a class="like" href="admin.php?dpt=catalog&sub=special&new_offer=' + row.productID + '" title="Добавить в список спец-предложений"><i class="glyphicon glyphicon-star fa-2x"></i></a>';

    }
    return [
        spec,
        '<a class="ml10" href="./admin.php?dpt=catalog&sub=products_edit&productID='+ row.productID+'" title="Edit">',
        '<i class="glyphicon glyphicon-edit fa-2x text-success"></i>',
        '</a>',
        '<a class="ml10" href="javascript:confirmDelete(\'%D0%A3%D0%B4%D0%B0%D0%BB%D0%B8%D1%82%D1%8C?\',\'admin.php?dpt=catalog&sub=products&terminate=' + row.productID + '\')" title="Remove">',
        '<i class="glyphicon glyphicon-remove fa-2x text-danger"></i>',
        '</a>',
        '<a class="ml10" href="admin.php?dpt=catalog&sub=products&categoryID=70&dublicate_product=' + row.productID + '" title="Создать копию">',
        '<i class="glyphicon glyphicon-file fa-2x"></i>',
        '</a>',
        '<a class="ml10" href="admin.php?dpt=catalog&sub=present&new_present=' + row.productID + '" title="В подарок">',
        '<i class="glyphicon glyphicon-gift fa-2x"></i>',
        '</a>'

    ].join('');

}

function orderactive(value, row, index) {
    if (row.status==1)
      complit='<a href="admin.php?dpt=custord&amp;sub=orders&amp;orderid=' + row.orderID + '&amp;complite" title="Выполнен" class="ml10"><i class="glyphicon glyphicon-ok fa-2x"></i></a>';
    else
      complit = '';
    return [
        '<a title="Редактировать" href="./admin.php?dpt=custord&amp;sub=edit_orders&amp;orderID='+row.orderID+'"><i class="glyphicon glyphicon-edit fa-2x"></i></a>',
        '<a title="Товарный чек" onclick="popupWin = window.open(this.href, \'Товарный чек\',\'location\'); popupWin.focus(); return false;" target="_blank" href="./core/print_tov.php?orderid=' + row.orderID + '" class="ml10"><i class="glyphicon glyphicon-print fa-2x text-muted"></i></a>',
        '<a title="Форма Торг-12" onclick="popupWin = window.open(this.href, \'Torg 12\', \'location\'); popupWin.focus(); return false;" target="_blank" href="./core/print_torg12.php?orderid=' + row.orderID + '" class="ml10"><img alt="Форма Торг-12" src="./images/backend/t12.png"></a>',
        complit,
        '<a href="javascript:confirmDelete(\'Удалить ?\',\'admin.php?dpt=custord&amp;sub=orders&amp;delete=' + row.orderID + '\');" class="ml10"><i class="glyphicon glyphicon-remove fa-2x text-danger"></i></a>'
    ].join('');
}

function ordercontact(value, row, index){
    str=row.cust_phone+'<br><a href="mailto:'+row.cust_email+'">'+ row.cust_email+'</a><br>';
    if (row.cust_address) str +='<hr>'+ row.cust_address + '<br>';
    str +=row.cust_city+' '+row.cust_state+' '+row.zip+'<br>'+ row.cust_country;
    return [str];
}

function orderproducts(value, row, index){
    //{if $order.comment}<br><br><span style="color: #f00; font-weight: bold">{$smarty.const.CUSTOMER_COMMENT}</span><br />{$order.comment}{/if}
    str=row.order_products+row.diskont_name;
    if (row.comment)
    str += "<blockquote>"+row.comment+'</blockquote>';
    return [str];
}


function columimg(value, row, index) {
    if (row.picture){
        return [
            '<a href="./products_pictures/' + row.picture + '" class="thickbox">да</a>'
        ].join('');
    }
    else{
        return [
            '<span class="text-danger">нет</span>'
        ].join('');
    }

}

    function operateenabled(value, row, index) {

    if (row.enabled==1) {
        return [
          '<input type="checkbox" value="1" checked="checked" name="p['+row.productID+'][enabled]">'
        ].join('');
    }
    else {
        return [
            '<input type="checkbox" value="1" name="p['+ row.productID+'][enabled]">'
        ].join('');
    }

}

function operatePrice(value, row, index) {
    return [
        '<input step=0.01 type="number" value="' + row.Price + '" checked="checked" name="p[' + row.productID + '][Price]">'
    ].join('');
}

function operateyml(value, row, index) {

    if (row.yml == 1) {
        return [
            '<input type="checkbox" value="1" checked="checked" name="p[' + row.productID + '][yml]">'
        ].join('');
    }
    else {
        return [
            '<input type="checkbox" value="1" name="p[' + row.productID + '][yml]">'
        ].join('');
    }

}



window.operateEvents = {
    'click .like': function (e, value, row, index) {
        alert('You click like icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    }
};