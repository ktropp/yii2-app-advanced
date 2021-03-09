$(document).ready(function(){

    //LAZYLOAD
    var lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy"
    });

    /*$('.site-index__categories, .site-index__articles, .carousel').on('init', function(event, slick){
        lazyLoadInstance.update();
    });*/


    //POPUPS
    $('.js-popup').click(function (e) {
        e.preventDefault();
        $($(this).attr("data-popup")).addClass('active');
    });

    $('.popup__close').click(function (e) {
        e.preventDefault();
        $(this).parents('.popup').removeClass('active');
    });

    $('.popup').click(function(data, handler){
        if (data.target == this){
            $(this).removeClass("active");
        }
    });
});
