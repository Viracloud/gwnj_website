(function($) {
    "use strict";
    $('.hero-slider').owlCarousel({
        rtl: true,
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        nav: false,
        dots: true,
        smartSpeed: 1000,
        animateOut: 'fadeOut', 
        animateIn: 'fadeIn',
        mouseDrag: true,
        touchDrag: true
    });

    $(document).ready(function(){
        var owl = $(".testimonial-slider-3");

        owl.owlCarousel({
            rtl: true,
            loop: true,
            nav: true,
            dots: false,
            autoplay: true,
            navText: ["<i class='fa-solid fa-arrow-left'></i>", "<i class='fa-solid fa-arrow-right'></i>"],
            responsive: {
                0: { items: 1 },
                1024: { items: 2 } 
            },
            
            onInitialized: highlightSecond,
            onTranslated: highlightSecond
        });

        function highlightSecond(event) {
            $('.testimonial-slider-3 .owl-item').removeClass('active-highlight');
            $('.testimonial-slider-3 .owl-item.active').eq(1).addClass('active-highlight');
        }
    });


    $(document).ready(function(){
        $(".brand-slider").owlCarousel({
            rtl: true,
            loop: true,
            margin: 30,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 2 
                },
                576: {
                    items: 3
                },
                768: {
                    items: 4
                },
                1024: {
                    items: 5
                }
            }
        });
    });


    $(document).ready(function(){
        $(".event-carousel").owlCarousel({
            rtl: true,
            loop: true,
            margin: 30,
            nav: false, 
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            navText: ["<i class='fa-solid fa-arrow-left'></i>", "<i class='fa-solid fa-arrow-right'></i>"],
            responsive:{
                0:{
                    items: 1
                },
                768:{
                    items: 2
                },
                1000:{
                    items: 3
                }
            }
        });
    });
})(jQuery);