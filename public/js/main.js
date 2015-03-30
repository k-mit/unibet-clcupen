/**
 * Created by anton on 30/03/15.
 */

(function ($) {
        $('.tabrow li a').click(function (e) {
            e.preventDefault();
            var t = $(this);
            t.parents('.tool').find('.active').removeClass('active');

            t.parent().addClass('active');
            $(t.attr('href')).addClass('active');
        })


})(jQuery);