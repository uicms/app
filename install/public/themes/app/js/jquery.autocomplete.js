(function ($) {
    $.fn.autocomplete = function (options) {
        // Default options
        var settings = $.extend({
            url: '',
            minLength: 2,
            onSelect: function (text) {},
            onFocus: function (text) {}
        }, options);
    
        // Iterate on each autocomplete
        return this.each(function () {
            var input = $(this);
            var popup = $('<div class="autocomplete_popup"></div>');
            var selected = null;
      
            // Input event
            input.on('input', function () {
                var text = input.val();
                if (text.length >= settings.minLength) {
                    updatePopup(text);
                } else {
                    popup.hide();
                }
            });
      
            // Select with keyboard
            input.on('keydown', function (e) {
                var options = popup.find('.autocomplete_option');
                selected = options.filter('.selected');
                
                // Up
                if (e.keyCode == 38) {
                    if (selected.length > 0) {
                        selected.removeClass('selected');
                        selected.prev().addClass('selected');
                        settings.onFocus(selected.prev().text());
                    } else {
                        options.last().addClass('selected');
                        settings.onFocus(options.last().text());
                    }
                    if(popup.is(':visible')) {
                        e.preventDefault();
                    }
                } 
                // Down
                else if (e.keyCode == 40) {
                    if (selected.length > 0) {
                        selected.removeClass('selected');
                        selected.next().addClass('selected');
                        settings.onFocus(selected.next().text());
                    } else {
                        options.first().addClass('selected');
                        settings.onFocus(options.first().text());
                    }
                }
                // Return
                else if (e.keyCode == 13) {
                    if (selected.length > 0) {
                        if(popup.is(':visible')) {
                            e.preventDefault();
                        }
                        input.val(selected.text());
                        settings.onSelect(selected.text());
                        popup.hide();
                    }
                }
            });
      
            // Click
            popup.on('click', '.autocomplete_option', function () {
                input.val($(this).text());
                settings.onSelect($(this).text());
                popup.hide();
            });
      
            // Close popup
            $(document).on('click', function (e) {
                if (!$(e.target).closest(popup).length && !$(e.target).is(input)) {
                    popup.hide();
                }
            });
      
            // Update popup
            function updatePopup(text) {
                $.ajax({
                    url: settings.url,
                    data: {term: text},
                    dataType: 'json',
                    success: function (data) {
                        if (data.length > 0) {
                            var html = '';
                            for (var i = 0; i < data.length; i++) {
                                html += '<div class="autocomplete_option">' + data[i].value + '</div>';
                            }
                            popup.html(html);
                            popup.show();
                        } else {
                            popup.hide();
                        }
                    }
                });
            }
      
            // Init popup
            popup.insertAfter(input).hide();
        });
    };
}(jQuery));