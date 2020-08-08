$(document).ready(function(){
   $("input[name$='insert_type']").click(function(){
   var radio_value = $(this).val();
   if(radio_value=='2') 
    $("#pamans_update").show(1000);
   else  
    $("#pamans_update").hide(500);
   });
   $("input[name$='navig_next']").click(function(){  
     $('option:selected', '#pages_list').removeAttr('selected').next('option').attr('selected', 'selected');
     $('#pages_list').submit();
   });
   $("input[name$='navig_prev']").click(function(){  
     $('option:selected', '#pages_list').removeAttr('selected').prev('option').attr('selected', 'selected');
     $('#pages_list').submit();
   });
    $("input[name$='navig_first']").click(function(){  
     $('#pages_list option:first').attr("selected", "selected");
     $('#pages_list').submit();
   });
    $("input[name$='navig_last']").click(function(){  
     $('#pages_list option:last').attr("selected", "selected");
     $('#pages_list').submit();
   });  

});

function show_hide(id)
{
  $('#'+id).toggle('slow');
}

function checkautores() 
{
   if ($('#enable_autores').is(":checked"))
	{$('#thumbnail').attr('disabled', 'disabled'); $('#big_picture').attr('disabled', 'disabled');} 
   else 
	{$('#thumbnail').removeAttr('disabled'); $('#big_picture').removeAttr('disabled');}
}

function confirmDelete(id, ask, url) //confirm order delete
	{
		temp = window.confirm(ask);
		if (temp) window.location=url+id;
	}
