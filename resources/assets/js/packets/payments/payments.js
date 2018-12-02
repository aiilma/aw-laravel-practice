$(document).ready(function () {
    // require
    try {
        window.jQuery.lightSlider = window.$.lightSlider = require('lightslider');
    } catch (error) {}

    // setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    

    var awPaymentSlider = $("#awPaymentSlider").lightSlider({
        item:3,
        loop:true,
        controls: true,
        pager: false,
        slideMove:2,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed:200,
        pause: 5000,
        auto:true,
        pauseOnHover: true,
        responsive : [
            {
                breakpoint:800,
                settings: {
                    item:3,
                    slideMove:1,
                    slideMargin:6,
                  }
            },
            {
                breakpoint:480,
                settings: {
                    item:2,
                    slideMove:1
                  }
            }
        ],
        onBeforeSlide: function (el) {
            $('#current').text(el.getCurrentSlideCount());
        }
    });

    $('#total').text(awPaymentSlider.getTotalSlideCount());
    

});