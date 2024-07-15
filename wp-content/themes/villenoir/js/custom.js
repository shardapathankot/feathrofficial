(function ($) {
"use strict";

function gg_isotope_init() {

	if($('.el-grid:not(.gg-slick-carousel)').length > 0){
	    var layout_modes = {
	        fitrows: 'fitRows',
	        masonry: 'masonry'
	    }
	    jQuery('.gg_products_grid').each(function(){
	        var $container = jQuery(this);
	        var $thumbs = $container.find('.el-grid:not(.gg-slick-carousel):not([data-layout-mode="list"])');
	        var layout_mode = $thumbs.attr('data-layout-mode');
	        
	        $thumbs.isotope({
	            // options
	            itemSelector : '.isotope-item',
	            layoutMode : (layout_modes[layout_mode]==undefined ? 'fitRows' : layout_modes[layout_mode]),
	        });


			//Isotope filter
	        if($container.find('.gg_filter:not(.gg-slick-carousel)').length > 0){
		        $container.find('.gg_filter:not(.gg-slick-carousel) a').data('isotope', $thumbs).on('click', function(e) {
		            e.preventDefault();
		            var $thumbs = jQuery(this).data('isotope');
		            jQuery(this).parent().parent().find('.active').removeClass('active');
		            jQuery(this).parent().addClass('active');
		            $thumbs.isotope({filter: jQuery(this).attr('data-filter')});
		        });
	    	}

	        jQuery(window).on('load resize', function() {
				$thumbs.imagesLoaded( function() {
				 	$thumbs.isotope('layout');
				});
	        });

	    });
	}
}

/* Magnific */
function gg_magnific_init() {
	if($('.el-grid:not(.no_magnific), .gg-slick-carousel.has_magnific, .wpb_image_grid.has_magnific, .wpb_single_image.has_magnific, .post-thumbnail.has_magnific, .size-guide-wrapper.has_magnific, .gg-contact-template').length > 0){
		$( '.el-grid:not(.no_magnific), .gg-slick-carousel.has_magnific, .wpb_image_grid.has_magnific, .wpb_single_image.has_magnific, .post-thumbnail.has_magnific, .size-guide-wrapper.has_magnific, .gg-contact-template' ).each(function(){
			$(this).magnificPopup({
				delegate: 'a.lightbox-el',
				type: 'image',
				gallery: {
		            enabled: true
		        },
				callbacks: {
				    elementParse: function(item) {
				    	console.log(item);
				    	if(item.el[0].className == 'lightbox-el link-wrapper lightbox-video') {
				        	item.type = 'iframe';
				    	} else if(item.el[0].className == 'lightbox-el gg-popup') {
				        	item.type = 'inline';
				        } else {
				        	item.type = 'image';
				      	}
				    }
				}
			});
		});
	}
}

/* SlickCarousel */
function gg_slickcarousel_init() {
	if($('.gg-slick-carousel:not(.gg_filter)').length > 0){
		$( '.gg-slick-carousel:not(.gg_filter)' ).each(function(){

			var $this = $(this);
			var filtered = false;

			if ( $('.gg_filter.gg-slick-carousel').length > 0 ) {
				$('.gg_filter.gg-slick-carousel a').on('click', function(e){
					e.preventDefault();
					$(this).parent().parent().find('.active').removeClass('active');
					$(this).parent().addClass('active');

					var gg_filter = $(this).parent().parent().parent().parent().find('.el-grid.gg-slick-carousel');

					if ($(this).attr('data-filter') == '*') {
						gg_filter.slick('slickUnfilter');
						gg_filter.slick('slickGoTo',0);
						filtered = false;
					} else {
						gg_filter.slick('slickFilter',$(this).attr('data-filter'));
						gg_filter.slick('slickGoTo',0);
						filtered = true;
					} 
				});
			}

		});

	}
}

/* Counter */
function gg_counter_init(){
	if($('.counter').length > 0){
		jQuery('.counter-holder').waypoint(function() {
			$('.counter').each(function() {
				if(!$(this).hasClass('initialized')){
					$(this).addClass('initialized');
					var $this = $(this),
					countToNumber = $this.attr('data-number'),
					refreshInt = $this.attr('data-interval'),
					speedInt = $this.attr('data-speed');

					$(this).countTo({
						from: 0,
						to: countToNumber,
						speed: speedInt,
						refreshInterval: refreshInt
					});
				}
			});
		}, { offset: '85%' });
	}
}

/* VC is RTL */
function gg_vc_is_rtl() {
	if(jQuery('body.rtl').length > 0){
		jQuery( '.vc_row[data-vc-full-width="true"]' ).each(function(){
		  //VC Row RTL
		  var jQuerythis = jQuery(this);
		  var vc_row = jQuerythis.offset().left;
		  jQuerythis.css('right', - vc_row)
		});
 	}
}


/* Infinite scroll */
function gg_infinite_scroll() {
	if(jQuery('.el-grid[data-pagination="ajax_load"]').length > 0) {

		var container = jQuery('ul.el-grid');
		var infinite_scroll = {
		  loading: {
			selector: '.load-more-anim',
			img: villenoir_custom_object.infinite_scroll_img,
			msgText: villenoir_custom_object.infinite_scroll_msg_text,
			finishedMsg: villenoir_custom_object.infinite_scroll_finished_msg_text
		  },
		  bufferPx     : 140,
		  behavior: "twitter",
		  nextSelector:".pagination-span a",
		  navSelector:".pagination-load-more",
		  itemSelector:"ul.el-grid li",
		  contentSelector:"ul.el-grid",
		  animate: false,
		  debug: false,

		};

		jQuery( infinite_scroll.contentSelector ).infinitescroll( 
		  	infinite_scroll,
		  	// Infinite Scroll Callback
		  	function( newElements ) {
				var newElems = jQuery( newElements ).hide();
				newElems.imagesLoaded(function(){
			  		newElems.fadeIn();

			 		if(jQuery('.el-grid[data-layout-mode="masonry"], .el-grid[data-layout-mode="fitRows"]').length > 0) {
						container.isotope( 'appended', newElems );
					}  
				});
		  	}
		);
  	}
}

//Sticky menu
function gg_sticky_menu() {
	if($('body.gg-has-stiky-menu').length > 0) {
		var main_menu = $('header.site-header .navbar');
		var main_menu_height = main_menu.outerHeight();
		var admin_bar = 0;
		var header_margin = main_menu.parent();

		if($('body.admin-bar').length > 0) {
			admin_bar = 31;
		}

   		$(window).on('scroll', function () {

   			if ($(this).scrollTop() > main_menu_height) {
   				main_menu.addClass('navbar-fixed-top');
   				setTimeout(function() {
				    $('header.site-header .navbar.navbar-fixed-top').css('top', admin_bar +'px');
				}, 500);
				if ( $('body').hasClass('gg-slider-is-beneath_header') || $('body').hasClass('gg-page-has-transparent-header') ) {
					header_margin.css('marginTop', '0px' );
				} else {
					header_margin.css('marginTop', main_menu_height + 'px' );
				}
   			} else {
   				main_menu.removeClass('navbar-fixed-top');
   				main_menu.css('top', '');
				if ( $('body').hasClass('gg-slider-is-beneath_header') == false || $('body').hasClass('gg-page-has-transparent-header') == false ) {
					header_margin.css('marginTop', '0px' )
				}
   			}

   		});
	}
}

function scrollEvent() {

    if (!is_touch_device()) {
        var viewportTop = jQuery(window).scrollTop();

        if (jQuery(window).width())

            jQuery('.parallax-overlay').each(function() {
            var elementOffset = jQuery(this).offset().top;
            var size = jQuery(this).attr('data-vc-kd-parallax');
            var distance = (elementOffset - viewportTop) * ( 1 - size );
            jQuery(this).css('transform', 'translate3d(0, ' + distance + 'px,0)');
        	});

        	jQuery('.kd-parallax-image .vc_figure img').each(function() {
                elementOffset = jQuery(this).offset().top;
                distance = (elementOffset - viewportTop) * -0.5;
        		jQuery(this).css('transform', 'translateY(' + distance + 'px)');
        	});

    }
}

function is_touch_device() {
    return 'ontouchstart' in window || 'onmsgesturechange' in window;
}


$(document).ready(function () {
	gg_sticky_menu();
	gg_slickcarousel_init();
    gg_magnific_init();
    gg_counter_init();
    gg_isotope_init();
    gg_vc_is_rtl();
    gg_infinite_scroll();

	function draw() {
		requestAnimationFrame(draw);
		scrollEvent();
	}
	draw();

	//Animations
	$(".kd-animated").inViewport(function(px) {
		if (px) $(this).addClass("kd-animate");
	});


    // Initialize Locomotive Scroll (horizontal direction)
    if($('[data-scroll-container]').length > 0){
		const lscroll_new = new LocomotiveScroll({
		    el: document.querySelector('[data-scroll-container]'),
		    smooth: true,
		    direction: 'horizontal'
		});
	}
       
	if($('body.gg-theme-is-mobile').length > 0) {
		$("a.product-image-overlay").on('click', function(event) {
		    event.preventDefault();
		});
	}

	//Fullscreen search form
	if($('li.gg-header-search').length > 0) {
	    $('a[href="#fullscreen-searchform"]').on('click', function(event) {
	        event.preventDefault();
	        $('#fullscreen-searchform').addClass('open');
	        $('#fullscreen-searchform > form > input[type="search"]').focus();
	    });

	    
	    $('#fullscreen-searchform button.close').on('click', function(event) {
	        $(this).parent().removeClass('open');
	    });

	    $('#fullscreen-searchform').on('keyup', function(event) {
	    	console.log(event.keyCode);
	        if (event.keyCode == 27) {
	            $(this).removeClass('open');
	        }
	    });
    }

    //Back to top
    $("a[href='#site-top']").on('click', function(e){
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});

	 //Load, resize, added to cart
	$(window).bind("load resize",function(e){
	 	gg_sticky_menu();
	});

});

	
})(jQuery);