(function ($) {
    "use strict";

var windows = $(window);
var screenSize = windows.width();
    
$(window).on('load', function(){
    function productHover() {

        var productInner = $('.product-inner');
        var proImageHeight = productInner.find('img').outerHeight();

        productInner.hover(
            function() {
                var porContentHeight = $( this ).find('.content').innerHeight()-55;
                $( this ).find('.image-overlay').css({
                    "height": proImageHeight - porContentHeight,
                });
            }, function() {
                $( this ).find('.image-overlay').css({
                    "height": '100%',
                });
            }
        );

    }
    productHover();
    windows.resize(productHover);
});
    
var sticky = $('.header-sticky');

windows.on('scroll', function() {
    var scroll = windows.scrollTop();
    if (scroll < 300) {
        sticky.removeClass('is-sticky');
    }else{
        sticky.addClass('is-sticky');
    }
});

var mainMenuNav = $('.main-menu nav');
mainMenuNav.meanmenu({
    meanScreenWidth: '991',
    meanMenuContainer: '.mobile-menu',
    meanMenuClose: '<span class="menu-close"></span>',
    meanMenuOpen: '<span class="menu-bar"></span>',
    meanRevealPosition: 'right',
    meanMenuCloseSize: '0',
});

var searchToggle = $('.search-toggle');
var searchWrap = $('.header-search-wrap');

searchToggle.on('click', function(){
    
    if( !$(this).hasClass('active') ) {
        $(this).addClass('active');
        searchWrap.addClass('active');
    } else {
        $(this).removeClass('active');
        searchWrap.removeClass('active');
    }
    
});

var headerCart = $('.header-cart');
var closeCart = $('.close-cart, .cart-overlay');
var miniCartWrap = $('.mini-cart-wrap');

headerCart.on('click', function(e){
    e.preventDefault();
    $('.cart-overlay').addClass('visible');
    miniCartWrap.addClass('open');
});
closeCart.on('click', function(e){
    e.preventDefault();
    $('.cart-overlay').removeClass('visible');
    miniCartWrap.removeClass('open');
});
    
var heroSlider = $('.hero-slider');
heroSlider.slick({
    arrows: true,
    autoplay: true,
    autoplaySpeed: 5000,
    dots: true,
    pauseOnFocus: false,
    pauseOnHover: false,
    fade: true,
    infinite: true,
    slidesToShow: 1,
    prevArrow: '<button type="button" class="slick-prev"><i class="icofont icofont-long-arrow-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="icofont icofont-long-arrow-right"></i></button>',
});
    
$('.small-product-slider').slick({
    arrows: false,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 4,
    rows: 2,
    prevArrow: '<button type="button" class="slick-prev"><i class="icofont icofont-long-arrow-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="icofont icofont-long-arrow-right"></i></button>',
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 767,
            settings: {
                autoplay: true,
                slidesToShow: 2,
                arrows: false,
            }
        },
        {
            breakpoint: 479,
            settings: {
                autoplay: true,
                slidesToShow: 1,
                arrows: false,
            }
        }
    ]
});
    
$('.best-deal-slider, .deal-product-slider').slick({
    arrows: false,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 1,
    prevArrow: '<button type="button" class="slick-prev"><i class="icofont icofont-long-arrow-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="icofont icofont-long-arrow-right"></i></button>',
});
    
$('.testimonial-slider').slick({
    arrows: false,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 2,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    responsive: [
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
            }
        },
    ]
});

$('.brand-slider').slick({
    arrows: false,
    dots: false,
    autoplay: true,
    infinite: false,
    slidesToShow: 6,
    prevArrow: '<button type="button" class="slick-prev"><i class="icofont icofont-rounded-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="icofont icofont-rounded-right"></i></button>',
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 5,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 479,
            settings: {
                slidesToShow: 2,
            }
        }
    ]
});
    
$('.pro-thumb-img').slick({
    arrows: true,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 479,
            settings: {
                slidesToShow: 3,
            }
        }
    ]
});
$('.product-slider, .related-product-slider-1').slick({
    arrows: true,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 4,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 767,
            settings: {
                autoplay: true,
                slidesToShow: 1,
                arrows: false,
            }
        }
    ]
});
$('.related-product-slider-2').slick({
    arrows: true,
    dots: false,
    autoplay: true,
    infinite: true,
    slidesToShow: 3,
    prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 767,
            settings: {
                autoplay: true,
                slidesToShow: 1,
                arrows: false,
            }
        }
    ]
});

var $easyzoom = $('.easyzoom').easyZoom();

var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.pro-thumb-img').on('click', 'a', function(e) {
    var $this = $(this);

    e.preventDefault();

    // Use EasyZoom's `swap` method
    api1.swap($this.data('standard'), $this.attr('href'));
});

$('[data-countdown]').each(function() {
	var $this = $(this), finalDate = $(this).data('countdown');
	$this.countdown(finalDate, function(event) {
		$this.html(event.strftime('<span class="cdown day"><span class="time-count">%-D</span> <p>Days</p></span> <span class="cdown hour"><span class="time-count">%-H</span> <p>Hours</p></span> <span class="cdown minutes"><span class="time-count">%M</span> <p>Mint</p></span> <span class="cdown second"><span class="time-count">%S</span> <p>Secs</p></span>'));
	});
});
    
$(function () {
    var form = $('#contact-form');
    var formMessages = $('.form-message');
    $(form).submit(function (e) {
        e.preventDefault();
        var formData = $(form).serialize();
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData,
        })
        .done(function (response) {
        $(formMessages).removeClass('error');
        $(formMessages).addClass('success');
        $(formMessages).text(response);
        $('#contact-form input,#contact-form textarea').val('');
        })
        .fail(function (data) {
        $(formMessages).removeClass('success');
        $(formMessages).addClass('error');
        if (data.responseText !== '') {
            $(formMessages).text(data.responseText);
        } else {
            $(formMessages).text(
                'Oops! An error occured and your message could not be sent.'
            );
        }
        });
    });
});

$.scrollUp({
    easingType: 'linear',
    scrollSpeed: 900,
    animation: 'fade',
    scrollText: '<i class="icofont icofont-swoosh-up"></i>',
});
    
$('.nice-select').niceSelect()
$('#price-range').slider({
   range: true,
   min: 0,
   max: 2000,
   values: [ 250, 1670 ],
   slide: function( event, ui ) {
	$('#price-amount').val( '$' + ui.values[ 0 ] + '  -  $' + ui.values[ 1 ] );
   }
  });
$('#price-amount').val( '$' + $('#price-range').slider( 'values', 0 ) +
   '  -  $' + $('#price-range').slider('values', 1 ) ); 
$('.pro-qty').prepend('<span class="dec qtybtn"><i class="ti-minus"></i></span>');
$('.pro-qty').append('<span class="inc qtybtn"><i class="ti-plus"></i></span>');
$('.qtybtn').on('click', function() {
	var $button = $(this);
	var oldValue = $button.parent().find('input').val();
	if ($button.hasClass('inc')) {
	  var newVal = parseFloat(oldValue) + 1;
	} else {
	  if (oldValue > 0) {
		var newVal = parseFloat(oldValue) - 1;
		} else {
		newVal = 0;
	  }
	  }
	$button.parent().find('input').val(newVal);
});  
    
$('[data-shipping]').on('click', function(){
    if( $('[data-shipping]:checked').length > 0 ) {
        $('#shipping-form').slideDown();
    } else {
        $('#shipping-form').slideUp();
    }
})

$('[name="payment-method"]').on('click', function(){
    
    var $value = $(this).attr('value');

    $('.single-method p').slideUp();
    $('[data-method="'+$value+'"]').slideDown();
    
})
    
   
})(jQuery);	
