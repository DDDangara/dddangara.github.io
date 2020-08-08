jQuery(document).ready(function() {

	// nav-menu
	jQuery('ul.sf-menu').superfish();

	// nav-phone
	jQuery(".container").on("click","#menu-icon", function(){
		jQuery(".sf-menu-phone").slideToggle();
		jQuery(this).toggleClass("active");
	});
	// nav-phone icon
	jQuery('.sf-menu-phone').find('li.parent').append('<i class="icon-angle-down"></i>');
	jQuery('.sf-menu-phone li.parent i').on("click", function(){
		if (jQuery(this).hasClass('icon-angle-up')) { jQuery(this).removeClass('icon-angle-up').parent('li.parent').find('> ul').slideToggle(); }
		else {
			jQuery(this).addClass('icon-angle-up').parent('li.parent').find('> ul').slideToggle();
		}
	});

	// scrollup
	$(window).scroll(function(){
		if ($(this).scrollTop() > 300) {
	    	$('.scrollup').fadeIn();
	    } else {
			$('.scrollup').fadeOut();
		}
	});

	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});


	    $(".callback").fancybox({
	        'type': 'iframe',
	        'overlayShow': 'TRUE',
	        'hideOnOverlayClick': 'FALSE',
	        'padding'   : 6,
	        'width': 380
	    });

});

// Cart Analog
// $(document).ready(function(){
// 	$(".ajaxcart").click(function () {
// 		var curid = this.id.split('_')[1];
// 		poststr = "cart_analog=" + curid;
// 		$.ajax({
// 			type: "POST",
// 			url: "./index.php",
// 			data: poststr,
// 			success:
// 				function (response) {
// 					if (response != -1) {
// 						$(response).prependTo($(document.body)).fadeIn(1000);
// 					} else {
// 						alert("На складе больше нету этого товара!");
// 					}
// 				}
// 		});
// 	});
// });
