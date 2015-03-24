/*=============================================
=            Main js functionality            =
=============================================*/



$(document).ready(function() {
    var offset = 220;
    var duration = 500;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.go-top').fadeIn(duration);
        } else {
            $('.go-top').fadeOut(duration);
        }
    });

    $('.go-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    });

    // $("#edit_form").on('submit', function() {
    //     var that = $(this),
    //         url  = that.attr('action'),
    //         type = that.attr('method'),
    //         data = [];

    //     that.find('[name]').each(function(index, value) {
    //         var that = $(this),
    //             name = that.attr('name'),
    //             value = that.val(),
    //         data[name] = value;
    //         });

    //     $.ajax({
    //         url: url,
    //         type: type,
    //         data: data,
    //         success: function(response){
    //             console.log(response);
    //         }

    //     });
    //     });

  
    $(function(){
        $('a.edit').click(function(){
            $('div.reveal_field').show();
            return false;
        });
    });

     $(function(){
        $('input.edit').click(function(){
            $('div.reveal_field').show();
        // $('input.mostra').click();
            return false;
        });
    });

    // document.getElementById('invisible').click();

});