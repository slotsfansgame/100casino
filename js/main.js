$(document).ready(function() {

    if( device.android() ) {
        setTimeout(function(){
            $('.popup').addClass('show');
        },600);
    }
    $('.popup_close_btn').click(function () {
        $('.popup').animate({'opacity': 0},100).removeClass('show');
    });

});