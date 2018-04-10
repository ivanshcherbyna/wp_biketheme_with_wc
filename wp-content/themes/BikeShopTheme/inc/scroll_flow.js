$(document).ready(function () {
    //Flow scroll
    $('a[href^="#"]').click(function () {
        elemtClick = $(this).attr('href');
        destination =$(elemtClick).offset().top;
        if($.browser.safari) {
            $('body').animate({scrollTop: destination}, 1000);
        } else {
            $('html').animate({scrollTop: destination}, 1000);
        }
        return false;

    });

});