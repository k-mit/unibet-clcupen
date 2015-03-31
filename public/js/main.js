/**
 * Created by anton on 30/03/15.
 */

(function ($) {
    $('.tabrow li a').click(function (e) {
        e.preventDefault();
        var t = $(this);
        t.parents('.tool').find('.active').removeClass('active');

        t.parent().addClass('active');
        $(t.attr('href')).addClass('active');
    })

    $('.team-name').click(function (event) {
        event.preventDefault();
        $('.game-table .active').removeClass('active');
        $(this).addClass('active');

        renderStats($(this).parent());

    });
    function renderStats (match) {
        var teams = match.find('.team-name');
        $('#teamform>h3').html('<span class="team1'+(+teams.eq(0).is('.active')?' t2':'')+'">'+teams.eq(0).text()+'</span> - <span class="team2'+(+teams.eq(1).is('.active')?' t2':'')+'">'+teams.eq(1).text()+'</span>');
        var $s = $('#statistics');
        var grids = $s.find('.grid');

        $s.find('.w,.l,.d').removeClass('w l d');
        grids.each(function () {
            if($(this).is('.t2')) {
                console.log();
                var stats = match.find('.active').attr('data-stats').split(',');
                $(this).find('ul').each(function (i,el) {
                    if(i<10) $(el).addClass(stats[i]);
                });
            } else {
                var stats = match.find(':not(.active)').attr('data-stats').split(',');
                $(this).find('ul').each(function (i,el) {
                    if(i<10) $(el).addClass(stats[i]);
                });

            }


        });

    }
    window.renderstats = renderStats;
})(jQuery);