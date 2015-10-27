(function ($) {
    function orderPositions() {
        var list = $('.sortable-list');

        list.find('li').sort(function (a, b) {
            return +a.getAttribute('data-position') - +b.getAttribute('data-position');
        }).appendTo(list);

        list.sortable({
            handle: '.handle',
            axis: "y",
            update: function (event, ui) {
                $('.sortable-list li').each(function (i) {
                    var pos = i;
                    $(this).find('.mp_position').val(++pos);
                });
            }
        }).disableSelection();
    }

    $(document).ready(function () {
        $('#mailplatform-options-form').on('submit', function (e) {
            var message = $(this).data('message');
            return confirm(message);
        });


        $('.mp_dropdown').on('click', 'a', function (e) {
            e.preventDefault();
            var self = $(this);
            var ul = self.closest('.mp_dropdown');
            var url = self.data('url');
            var input = $('#' + ul.data('for'));

            input.val(url);

        });

        orderPositions();
    });

    var prefix = "mailplatform-link-";
    var selector = $('#' + prefix + 'selector');
    var amount = $('#' + prefix + 'amount');
    var category = $('#' + prefix + 'category');
    var output = $('#' + prefix + 'output');
    var clear = $('#' + prefix + 'clear');

    var tpl = {
        'posts': $('#' + prefix + 'posts-tpl').html(),
        'woocommerce': $('#' + prefix + 'woocommerce-tpl').html()
    };

    var url = {
        'site_url': $('#' + prefix + 'site_url').val(),
        'rss': '',
        'amount': 0,
        'category': 0
    };

    var builder = function () {

        var link = url.site_url + "/" + url.rss;

        if(url.amount > 0){
            link += '?amount='+url.amount;
        }

        if(url.category > 0){
            link += (url.amount > 0 ? '&' : '?') + 'cat='+url.category;
        }

        output.attr('href', link).text(link);
    };

    selector.on('change', 'select', function (e) {
        var val = $(this).find(":selected").val();

        switch (val) {
            case 'rss-posts':
                category.find('select').html(tpl.posts);
                amount.show();
                category.show();
                break;
            case 'rss-woocommerce':
                category.find('select').html(tpl.woocommerce);
                amount.show();
                category.show();
                break;
            case 'rss-pages':
                amount.show();
                category.hide();
                category.find('select').html('');
                break;
            default:
                amount.hide();
                category.hide();
                category.find('select').html('');
                break;
        }

        if(val != 0){
            url.rss = val;
            builder();
        }
    });


    amount.on('change', function (e) {
        var val = $(this).find('input').val();

        url.amount = val;

        builder();
    });


    category.on('change', 'select', function (e) {
        var val = $(this).find(":selected").val();

        url.category = val;

        builder();
    });

    clear.on('click', function (e) {
        e.preventDefault();

        url.rss = "";
        url.amount = 0;
        url.category = 0;

        output.attr('href', '').text('');

        selector.find('option[value="0"]').prop('selected', true);
        selector.trigger('change');
        amount.hide();
        category.hide();
        category.find('select').html('');
    });

})(jQuery);