function orderPositions(){
    var list = jQuery('.sortable-list');

    list.find('li').sort(function (a, b) {
        return +a.getAttribute('data-position') - +b.getAttribute('data-position');
    }).appendTo( list );

    list.sortable({
        handle: '.handle',
        axis: "y",
        update: function( event, ui ) {
            jQuery('.sortable-list li').each(function(i){
                var pos = i;
                jQuery(this).find('.mp_position').val(++pos);
            });
        }
    }).disableSelection();
}

jQuery(document).ready(function(){
	jQuery('#mailplatform-options-form').on('submit', function(e){
		var message = jQuery(this).data('message');
		return confirm(message);
	});


	jQuery('.mp_dropdown').on('click', 'a', function(e){
		e.preventDefault();
		var self = jQuery(this);
		var ul = self.closest('.mp_dropdown');
		var url = self.data('url');
		var input = jQuery('#' + ul.data('for'));

		input.val(url);

	});

    orderPositions();
});
