$(document).ready(function() {
	init();
	initVgrid();
	initForms();
	initSlideshow();
});

function init() {
    /* Misc */
	$('.external').attr('target', '_blank');
    
    /* Menu */
    $('#menu_button').click(function(e) {
        e.stopPropagation();
        $('body').toggleClass('menu_expanded');
    });
    $('#menu').click(function(e) {
        e.stopPropagation();
    });
    $('body').click(function() {
        $('body').removeClass('menu_expanded');
    });
}

function initSlideshow(index) {
	if ($('.slideshow').length && $('.slideshow').is(':visible')) {

		$('.slideshow').flexslider({
			animation: "slide",
			slideshow: true,                
			slideshowSpeed: 5000,           
			animationDuration: 3000,         
			directionNav: false,         
			controlNav: false, 
			keyboardNav: true,
			startAt: index ? index : 0,
			start: function(e) {
			
			},
			after: function() {
			}
		});
		$('.slideshow .nav #next').unbind('click').click(function(){$('.slideshow').flexslider("next");});
		$('.slideshow .nav #prev').unbind('click').click(function(){$('.slideshow').flexslider("prev");});
	}
}

function initVgrid() {
	vg = $(".vgrid").vgrid({
		easing: "easeOutQuint",
		useLoadImageEvent: true,
		time: 1000,
		delay: 100,
		fadeIn: {
			time: 500,
			delay: 50,
			wait: 500
		}
	});
	
	$(window).load(function(e){
		vg.vgrefresh();
	});
}

function initForms() {
	if ($('#contact_form').length) {
		$('#contact_form').validate({
		rules: {
			name: "required", 
			email: {required: true, email: true}, 
			subject: "required", 
			text: "required"
		}, 
		messages: {
			name: str_required, 
			email: str_required_email, 
			subject: str_required, 
			text: str_required
		}});
	}
}

/* Resize */
$(window).resize(function() {
});