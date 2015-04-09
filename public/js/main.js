/**
 * Created by anton on 30/03/15.
 */

(function ($) {
    $('#close-terms').click(function () {
       $('#terms').hide();
    });

    $('a[href="#terms"]').click(function (){
       $('#terms').show();
    });
    $('.tabrow li a').click(function (e) {
        e.preventDefault();
        var t = $(this);
        t.parents('.tool').find('.active').removeClass('active');

        t.parent().addClass('active');
        $(t.attr('href')).addClass('active');
        checkScroll( $(t.attr('href')));
        onResize();
    })

    $('.team-name').click(function (event) {
        event.preventDefault();
        $('.game-table .active').removeClass('active');
        $(this).addClass('active');
        $('a[href="#teamform"]').click();

        renderStats($(this).parent());


    });
    $('#tips10').find('strong').click(function () {
        if($(this).is('.active')) {
            $('#tips10').find('.active').removeClass('active');
            onResize();
            return;
        }
        $('#tips10').find('.active').removeClass('active');
        $(this).addClass('active');
        onResize();
    });
    function renderStats (match) {
        var teams = match.find('.team-name');
        $('#teamform>h3').html('<span class="team1'+(+teams.eq(0).is('.active')?' t2':'')+'">'+teams.eq(0).text()+'</span> - <span class="team2'+(+teams.eq(1).is('.active')?' t2':'')+'">'+teams.eq(1).text()+'</span>');
        var $s = $('#statistics');
        var grids = $s.find('.grid');

        $s.find('.w,.l,.d').removeClass('w l d');
        grids.each(function () {
            if($(this).is('.t2')) {
                console.log(match.find('.active'));
                var stats = match.find('.active').attr('data-stats').split(',');
                $(this).find('ul').each(function (i,el) {
                    if(i<10) $(el).addClass(stats[i]);
                });
            } else {
                console.log(match.find(':not(.active)').get(0));
                var stats = match.find(':not(.active)').attr('data-stats').split(',');
                $(this).find('ul').each(function (i,el) {
                    if(i<10) $(el).addClass(stats[i]);
                });

            }


        });

    }
    $('.invite-friend').click(function (e) {
        e.preventDefault();
        onChallenge();
    })
    $(document).ready(function(){
        // Target your .container, .wrapper, .post, etc.
        $(".party-box").fitVids();
    });
    function checkScroll(item) {
        item.find('.userlist').each(function (){

            if(this.scrollHeight>300) {
                $(this).scrollTop(0,0);
                $(this).siblings('.scrolldown').addClass('active');
            }
        });


    }

    checkScroll($('#main'));
    $('.scrolldown').click(function (e) {
        e.preventDefault();
        var ul=$(this).siblings('.userlist');
        ul.scrollTop(ul.scrollTop()+300);
        console.log(ul.scrollTop(), ul.get(0).scrollHeight);
        if(ul.scrollTop()+300 >= ul.get(0).scrollHeight) ul.siblings('.scrolldown').removeClass('active');
        $(this).siblings('.scrollup').addClass('active');

    });
    $('.scrollup').click(function (e) {
        e.preventDefault();
        var ul=$(this).siblings('.userlist');
        ul.scrollTop(ul.scrollTop()-300);
        if(ul.scrollTop()<1) ul.siblings('.scrollup').removeClass('active');
        $(this).siblings('.scrolldown').addClass('active');
    });

    window.renderstats = renderStats;
    function onChallenge() {
        sendChallenge(null,'Var med och tävla och vinn din egen Champions League drömfinal', function(response) {
            console.log('sendChallenge',response);
            $.ajax('/saveInvite',{type: "post", dataType: "json", data: {facebook_friend_id:response.to}, success:function(response){
                $('.invite-friend .fine-print').text('('+response.count+'/5)')
            }})
        });
    }

    function sendChallenge(to, message, callback) {
        var options = {
            method: 'apprequests'
        };
        if(to) options.to = to;
        if(message) options.message = message;
        FB.ui(options, function(response) {
            if(callback) callback(response);
        });
    }
    $('h1').click(function () {
        FB.api('/me', {fields: 'apprequests'}, function(response) {
            console.log(response);
        });
    })
    $(window).on('resize',onResize());
    function onResize () {
        var cols = $('.full-height').removeAttr('style');
        if($(window).width()<800) {
            if($('#main').is('.login')) {
                cols = cols.eq(1);

            } else {
                cols = cols.eq(1);

            }
        }
        var maxH = 0;

        cols.each(function(){
            maxH = Math.max($(this).height(),maxH);
        });
        maxH = Math.max(maxH, ($('.tool-boxes').height()+ $('.party-box img').height() +60));
        if(cols.length > 1) maxH = Math.max(maxH,$(document).height())
        cols.height(maxH);

        return onResize;
    }
    $('.facebook-login').click(function (e) {
        e.preventDefault();
        FB.login(function(response) {
            if (response.authResponse) {
                console.log(response.authResponse);
                window.location.reload();

            } else {
                console.log('User cancelled login or did not fully authorize.');
            }
        },{scope: 'email,user_friends'});

    })
})(jQuery);