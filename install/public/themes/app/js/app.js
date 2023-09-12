var loading = false;

$(document).ready(function() {
    initNav();
    initAjax();
    initSlider();
    initInfiniteScroll();
    initScrollBars();
    initFilters();
});

function initAjax() {
    $('.ajax').each(function() {
        var url = $(this).data('url');
        $(this).attr('href', '#' + url);
    });

    if(location.hash && location.hash != '#top' && location.hash.indexOf("ui_form_translations") === -1) {
        var url = location.hash.substr(1);
        ajaxViewer(url);
    }

    $(window).bind('hashchange', function(e) {
        if(!location.hash) {
            $('#viewer').remove();
            $('body').removeClass('scroll-disabled');
        }
        if (location.hash && location.hash != '#top' && location.hash.indexOf("ui_form_translations") === -1) {
            var url = location.hash.substr(1);
            ajaxViewer(url);
        }
    });
}

function ajaxViewer(url) {
    if(!$('#viewer').length) {
        $('body,html').addClass('scroll-disabled');
        $('body').append('<div id="viewer" class="popup"><div class="close"></div><div class="content"></div></div>');
    }
    url = url.replace(' ', '+');
    $('#viewer .content').load(url, function() {
        ajaxViewerLoaded();
    });
    $('#viewer').show();
}

function ajaxViewerLoaded(element) {
    $('#viewer').scrollTop(0);

    $('#viewer .ajax').each(function() {
        var url = $(this).data('url');
        $(this).attr('href', '#' + url);
    });
    
    $('#viewer .close').unbind('click').click(function() {
        $('#viewer').remove();
        $('body,html').removeClass('scroll-disabled');
        history.pushState("", document.title, window.location.pathname + window.location.search);
    });
    
    initSlider('#viewer');
}

function initNav() {
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

    $('a.external').attr('target', '_blank');

    $('.cpnt_message').delay(3000).fadeOut();
}

function initFilters() {
    $('.filter_name').click(function(e) {
        e.stopPropagation();
        $('.filter').not('#' + $(this).parent().attr('id')).removeClass('expanded');
        $(this).parent().toggleClass('expanded');
    });
    $('.filter').click(function(e) {
        e.stopPropagation();
    });
    $('body').click(function() {
        $('.filter').removeClass('expanded');
    });
}

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

function initSlider(element) {
    var selector = element ? element + ' .slider' : '.slider';
    $(selector).each(function() {
        if($(this).find('li').length > 1) {
            $(this).slick({
                initialSlide: 0,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                dots: true,
                autoplay: false
            });
        }
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