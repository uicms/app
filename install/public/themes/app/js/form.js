$(document).ready(function() {
    initForms();
});

function initForms() {
    /* Prototype */
    $('.form_type_collection').each(function() {
    	var collection_element = this;
    	
    	$(collection_element).append('<button type="button" class="add_collection_entry button2">Ajouter</button>');
        $(collection_element).find('.add_collection_entry').unbind('click').click(function (e) {
            var counter = $(collection_element).find(' > .form_field_body > .collection_entry').length;
            var html = $(collection_element).data('prototype').replace(/__name__label__/g, counter).replace(/__name__/g, counter);
            $(collection_element).find(' > .form_field_body').append(html);

            initDelete();
        });

        initDelete();

        function initDelete() {
        	$(collection_element).find('.delete_collection_entry').remove();
        	$(collection_element).find('.collection_entry').append('<button type="button" class="delete_collection_entry button2">Supprimer</button>');
	        $(collection_element).find('.delete_collection_entry').unbind('click').click(function (e) {
	            $(this).closest('.collection_entry').remove();
	        });
        }
    });
    

    /* Agreement form */
    $('#agreement_checkbox').change(function() {
        if($('#agreement_checkbox').is(':checked')) {
            $('#submit_agreement').attr('disabled', false);
        } else {
            $('#submit_agreement').attr('disabled', 'disabled');
        }
    });

    /* Translations tabs */
    tabs = document.querySelectorAll('.nav-tabs .nav-item a');
    tabs.forEach(function(tab) {
        var pane_id = tab.getAttribute('href');
        tab.setAttribute('href', 'javascript:;');
        tab.addEventListener('click', function(e) { 
            document.querySelector('.nav-item a.active').classList.remove('active');
            document.querySelector('.tab-content .tab-pane.active').classList.remove('active');
            document.querySelector(pane_id).classList.add('active');
            this.classList.add('active');
        });
    });
}