/**
 * Created by Косаностра on 03.09.2016.
 */
$(document).ready(function () {
    var count = 0;
    var activeForm = false;
    $('.wrap').on('click', function (e) {
        if (e.target.nodeName == "IMG"){
            var position = {
                x: e.offsetX,
                y: e.offsetY
            };
            count++;

            if (!activeForm) {

                var mark = $('<div class="mark" data-numb="' + count + '"></div>');

                mark.css({top: position.y, left: position.x});

                var popup = _.template($('#new-mark').html());

                mark.append(popup({numb: count}));

                $(this).append(mark);
                $('.js_add_popup').data({x: position.x, y: position.y, numb: count});
                activeForm = true;
            }
        }

    });
    $(document).on('click','.js_add_popup', function (e) {
        var object = {
            title: $('.input[data-name="title"]').val(),
            text: $('.input[data-name="text"]').val(),
            x: $(this).data('x'),
            y: $(this).data('y'),
            numb: $(this).data('numb')
        };

        var mark = $('.mark[data-numb="' + object.numb + '"]');
        mark.html('');

        var popup = _.template($('#mark-item').html());

        mark.append(popup(object));

        activeForm = false;
    });

    $(document).on('click','.mark-icon',function(){

       if( $(this).hasClass('hider')){
           $(this).removeClass('hider').addClass('red');
       }else{
           $(this).removeClass('red').addClass('hider');
       }
    });
});