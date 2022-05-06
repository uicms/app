(function($) {
	var params = {};
	
	var element;

    $.fn.infiniteScroll = function(options) {
		params = options;
		element = this;

		$(window).scroll(function() {
		   if($(window).scrollTop() + $(window).height() >= $(document).height()-1) {
			   if(!loading) {
			   	$(window).trigger('scrollBottom');
			   }
		   }
		});

		$(window).bind("scrollBottom", function() {
			
			var offset = parseInt($(element).data('offset'));
			var limit = parseInt($(element).data('limit'));
			var total = parseInt($(element).data('total'));

			var new_offset = offset + limit;

			if (new_offset < total && !loading) {

				if(params.before != undefined && params.before) {
					params.before.call();
				}
				
				var url = $(element).data('url') + '&of=' + new_offset;
				$.ajax({
					url: url,
					success: function(result, statut) {
						$(element).append(result);
						$(element).data('offset', new_offset);
						if(params.callback != undefined && params.callback) {
							params.callback.call(result);
						}
					}
				});
			}
		});

    	return this;
    };

    $.fn.infiniteScroll.example = function() {
	}
})(jQuery);
