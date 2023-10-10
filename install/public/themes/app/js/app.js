var loading = false;

$(document).ready(function() {
    initNav();
    initAjax();
    initSlider();
    initInfiniteScroll();
    initScrollBars();
    initFilters();
    initAccordion();
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
    
    // Dropdown
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
    
    // Date picker
    var date_element = 'input[name="d"]';
    var date_format = 'YYYY-MM-DD';
    $(date_element).daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Aujourd\'hui': [moment(), moment()],
            'Demain': [moment().add(1, 'days'), moment().add(1, 'days')],
            'Les 7 prochains jours': [ moment(), moment().add(6, 'days')],
            'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
            'Le mois prochain': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
        },
        locale: {
            format: date_format,
            "separator": " - ",
            "applyLabel": "Valider",
            "cancelLabel": "Annuler",
            "fromLabel": "De",
            "toLabel": "à",
            "customRangeLabel": "Personnaliser",
            "daysOfWeek": [
                "Dim",
                "Lun",
                "Mar",
                "Mer",
                "Jeu",
                "Ven",
                "Sam"
            ],
            "monthNames": [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Août",
                "Septembre",
                "Octobre",
                "Novembre",
                "Décembre"
            ],
            "firstDay": 1
        }
    });
    
    $(date_element).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
        $('#filter_form_date').submit();
    });

    $(date_element).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
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

function initAccordion() {
    
    // Block case
    $('.block.accordion').each(function() {
        var block = this;
        $(this).find('.block_name').click(function() {
            is_unfolded = $(block).hasClass('unfolded') ? true : false;
            $('.block.accordion').removeClass('unfolded');
            if(!is_unfolded) $(block).addClass('unfolded');
       });
    });
    
    // Inline case
    $('.accordion_title').click(function() {
        var is_unfolded = $(this).next().hasClass('unfolded') ? true : false;
        $('.accordion_content,.accordion_title').removeClass('unfolded');
        if(!is_unfolded) {
            $(this).addClass('unfolded');
            $(this).next().addClass('unfolded');
        }
    });
}