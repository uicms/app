$(document).ready(function() {
    initForms();
});

function initForms() {
    
    /* Anwsers */
    $('.answer_response_button').click(function() {
        $(this).parent().parent().find('.answer_child_form').toggleClass('expanded');
    });
    
    /* Prototype */
    $('.form_type_collection').each(function() {
    	var collection_element = this;
    	
    	$(collection_element).append('<button type="button" class="form_collection_entry_add">Ajouter</button>');
        $(collection_element).find('.form_collection_entry_add').unbind('click').click(function (e) {
            var counter = $(collection_element).find(' > .form_field_body > .form_collection_entry').length;
            var html = $(collection_element).data('prototype').replace(/__name__label__/g, counter).replace(/__name__/g, counter);
            $(collection_element).find(' > .form_field_body').append(html);

            initDelete();
            
            /* Common inits */
            initTranslationTabs();
            initAutocomplete();
        });

        initDelete();

        function initDelete() {
        	$(collection_element).find('.form_collection_entry_delete').remove();
        	$(collection_element).find('.form_collection_entry').append('<button type="button" class="form_collection_entry_delete">Supprimer</button>');
	        $(collection_element).find('.form_collection_entry_delete').unbind('click').click(function (e) {
	            $(this).closest('.form_collection_entry').remove();
	        });
        }
    });
    
    /* Common inits */
    initAutocomplete();
    initTranslationTabs();

    /* Agreement form */
    $('#agreement_checkbox').change(function() {
        if($('#agreement_checkbox').is(':checked')) {
            $('#submit_agreement').attr('disabled', false);
        } else {
            $('#submit_agreement').attr('disabled', 'disabled');
        }
    });
}

function initAutocomplete() {
    $( ".autocomplete" ).each(function() {
        var element = $(this);
        var source = '/' + $('body').data('locale') + '/' + $('body').data('slug') + '/autocomplete?entity=' + element.data('source') + '&field_name=' + element.data('field_name');
        
        element.autocomplete({
          url: source,
          minLength: 3,
          delay: 100,
          onSelect: function(value) {

            if (value.indexOf('<span') !== -1) {
                console.log('ok');
                var $temp = $('<div>').html(value);
                $temp.find('span').each(function() {
                    console.log('[data-field_name='+ $(this).attr('class') + ']');
                    console.log(element.parentsUntil('.form_group').find('[data-field_name='+ $(this).attr('class') + ']'));
                    element.parentsUntil('.form_group').find('[data-field_name='+ $(this).attr('class') + ']').val($(this).text());
                });
            } else {
                element.val(value);
            }
          },
          onFocus: function(value) {
          }
        });
    });
}

function initTranslationTabs(element) {
    selector = '.a2lix_translations';
    if(element) {
        selector = element + ' ' + selector;
    }
    $(selector).each(function() {
        var container = this;
        $(container).find('.nav-tabs .nav-item a').not('.initialized').each(function() {
            var pane_id = $(this).attr('href');
            $(this).addClass('initialized');
            $(this).attr('href', 'javascript:;');
            $(this).click(function() {
                $(container).find('.nav-item a.active, .tab-content .tab-pane.active').removeClass('active');
                $(container).find(pane_id).addClass('active');
                $(this).addClass('active');
            });
        });
    });
}