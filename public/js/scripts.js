/*=============================================
=            Main js functionality            =
=============================================*/



jQuery(document).ready(function() {
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.go-top').fadeIn(duration);
        } else {
            jQuery('.go-top').fadeOut(duration);
        }
    });

    jQuery('.go-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});


