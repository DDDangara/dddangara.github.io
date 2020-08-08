
/*********************************************************************************
*                                                                                *
*   shop-script Legosp - legosp.net                                              *
*   Skype: legoedition                                                           *
*   Email: legoedition@gmail.com                                                 *
*   Лицензионное соглашение: https://legosp.net/info/litsenzionnoe_soglashenie/  *
*   Copyright (c) 2010-2019  All rights reserved.                                *
*                                                                                *
*********************************************************************************/
 
jQuery.fn.wizard = function(settings)
{
    settings = jQuery.extend({
         show: function(element) { return true; },
         prevnext: true,
         submitpage: null
      }, settings);

    // Hide all pages save the first.
    jQuery(this).children(".wizardpage").hide();
    jQuery(this).children(".wizardpage:first").show();
    settings.show(jQuery(this).children(".wizardpage:first"));
    
    // Also highlight the first nav item.
    jQuery(this).children(".wizard-nav").children("a:first").addClass("active");
    
    // Wire progress thingy
    jQuery(this).children(".wizard-nav").children("a").click(function(){
//        var target = jQuery(this).attr("href");
//        jQuery(this).parent().parent().children(".wizardpage").hide();
//        jQuery(target).fadeIn('slow');
//        settings.show(jQuery(target));
//        jQuery(this).parent().children('a').removeClass('active', 'slow');
//        jQuery(this).addClass('active', 'slow');
	      return false;
    });
    
    // Prevent form submission on a wizard page...
    jQuery(this).children(".wizardpage").each(function(i){
        // unless there is a submit button on this page
        if((settings.submitpage == null && jQuery(this).find('input[type="submit"]').length < 1) ||
           (settings.submitpage != null && !$(this).is(settings.submitpage)))
        {
            $(this).find('input,select').keypress(function(event){
                return event.keyCode != 13;
            });
        }
    });
    
    if(settings.prevnext)
    {
        // Add prev/next step buttons
        jQuery(this).children(".wizardpage")
        .append('<div class="row wizardcontrols"></div>')
        .children(".wizardcontrols")
        .append('<input type="button" class="wizardprev" value="&larr; Назад" /><input type="button" class="wizardnext" value="Далее &rarr;" />');
        jQuery('.wizardpage:first input[type="button"].wizardprev').hide(); // hide prev button on first page
        jQuery('.wizardpage:last input[type="button"].wizardnext').hide();  // hide next button on last page
		
        // Wire prev/next step buttons
        jQuery(this).children(".wizardpage")
        .children(".wizardcontrols")
        .children('input[type="button"].wizardprev').click(function(){
            var wizardpage = jQuery(this).parent().parent(); // wizardcontrols div, wizardpage div
            var wizardnav  = wizardpage.parent().children(".wizard-nav")
            
            wizardpage.hide();
            wizardpage.prev().fadeIn();
            settings.show(wizardpage.prev());
            
            try{ wizardpage.prev().find("input:first").focus(); } catch(err) {}
            wizardnav.children('a').removeClass('active', 'slow');
            wizardnav.children('a[href="#' + wizardpage.attr('id') + '"]').prev().addClass('active', 'slow');
        });
        jQuery(this).children(".wizardpage")
        .children(".wizardcontrols")
        .children('input[type="button"].wizardnext').click(function(){
            var wizardpage = jQuery(this).parent().parent(); // wizardcontrols div, wizardpage div
            var wizardnav  = wizardpage.parent().children(".wizard-nav")
            
            wizardpage.hide();
            wizardpage.next().fadeIn();
            settings.show(wizardpage.next());
            
            try{ wizardpage.prev().find("input:first").focus(); } catch(err) {}
            wizardpage.prev().find("input:first").focus();
            wizardnav.children('a').removeClass('active', 'slow');
            wizardnav.children('a[href="#' + wizardpage.attr('id') + '"]').next().addClass('active', 'slow');
			
			if ($('#error').text() == 'yes') {
				jQuery('#folders input[type="button"].wizardnext').hide();
			}
        });
    }
    
    return jQuery(this);
};

$(document).ready(function() {
//$('#folders.wizardnext').hide();
	//$('#license').hide();
	var d = new Date();
	$('#now_date').text(d.getFullYear());
	$("form.wizard").wizard({
			show: function(element) {

				if($(element).is("#install")){
					$('input[name=install]').remove();
					$('.wizardcontrols').append('<input class="wizardnext" type="submit" name="install" style="width:150px;" value="Установить">');
				} else {
					$('input[name=install]').remove();
				}

                if($(element).is("#start")){
                    setTimeout("checkAgree()", 100);
                }
			}
		});
});

function checkAgree() {
	var agree = $('#license_agree').attr('checked');
	if (agree) {
		$('.wizardnext').attr('disabled', '');
	} else {
		$('.wizardnext').attr('disabled', 'disabled');
	}
}