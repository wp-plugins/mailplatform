$ = jQuery;

function orderPositions(){
    var list = $('.sortable-list');

    list.find('li').sort(function (a, b) {
        return +a.getAttribute('data-position') - +b.getAttribute('data-position');
    }).appendTo( list );

    list.sortable({
        handle: '.handle',
        axis: "y",
        update: function( event, ui ) {
            $('.sortable-list li').each(function(i){
                var pos = i;
                $(this).find('.mp_position').val(++pos);
            });
        }
    }).disableSelection();
}

$(document).ready(function(){
	$('#mailplatform-options-form').on('submit', function(e){
		var message = $(this).data('message');
		return confirm(message);
	});


	$('.mp_dropdown').on('click', 'a', function(e){
		e.preventDefault();
		var self = $(this);
		var ul = self.closest('.mp_dropdown');
		var url = self.data('url');
		var input = $('#' + ul.data('for'));

		input.val(url);

	});

    orderPositions();
});
