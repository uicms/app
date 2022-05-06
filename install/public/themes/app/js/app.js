var loading = false;

$(document).ready(function() {
    initSlider();
    initInfiniteScroll();
    initScrollBars();
    
    /* Menu */
    $('#menu_button').click(function(e) {
        e.stopPropagation();
        $('body').toggleClass('menu_expanded');
    });
    $('body').click(function(e) {
         $('body').removeClass('menu_expanded');
    });
    $('#menu').click(function(e) {
        e.stopPropagation();
    });
    
    /* Misc */
    $('a.external').attr('target', '_blank');
});

function initInfiniteScroll() {
	$('.infiniteScroll').infiniteScroll({
		before: function() {
			loading = 1;
			$('#loading').show();
		},
		callback:
		function(data) {
			loading = 0;
			$('#loading').hide();
		}
	});
}

function initSlider() {
    $(".slider").slick({
        initialSlide: 0,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: false
    });
}

function initScrollBars() {
    $(".scrollbar").mCustomScrollbar({
        theme:"dark-thin",
        callbacks:{
            onInit:function(){
            }
        }
    });
}