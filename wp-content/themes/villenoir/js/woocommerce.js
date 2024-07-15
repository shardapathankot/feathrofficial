jQuery( function( $ ) {

	// Quantity buttons
	if ( ! String.prototype.getDecimals ) {
		String.prototype.getDecimals = function() {
			var num = this,
				match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
			if ( ! match ) {
				return 0;
			}
			return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
		}
	}

	function villenoir_refresh_quantity_increments(){
		$( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );
	}

	$( document ).on( 'updated_wc_div', function() {
		villenoir_refresh_quantity_increments();
	} );

	$( document ).on( 'click', '.plus, .minus', function() {
		// Get values
		var $qty		= $( this ).closest( '.quantity' ).find( '.qty'),
			currentVal	= parseFloat( $qty.val() ),
			max			= parseFloat( $qty.attr( 'max' ) ),
			min			= parseFloat( $qty.attr( 'min' ) ),
			step		= $qty.attr( 'step' ),
			$quickCartUpdate = $( this ).closest( '.quantity' ).find( '.quick-cart-update');

		if ($quickCartUpdate.length == 0) {
			if ($('body').hasClass('woocommerce-cart')) {
				var update_cart_string = $('.wc-update-cart').find('button').text();
				$( this ).closest( '.quantity' ).append( '<span class="quick-cart-update">'+update_cart_string+'</span>' );
			}
			
		}

		// Format values
		if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
		if ( max === '' || max === 'NaN' ) max = '';
		if ( min === '' || min === 'NaN' ) min = 0;
		if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

		// Change the value
		if ( $( this ).is( '.plus' ) ) {
			if ( max && ( currentVal >= max ) ) {
				$qty.val( max );
			} else {
				$qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
			}
		} else {
			if ( min && ( currentVal <= min ) ) {
				$qty.val( min );
			} else if ( currentVal > 0 ) {
				$qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
			}
		}

		// Trigger change event
		$qty.trigger( 'change' );
	});

	villenoir_refresh_quantity_increments();

	$('.woocommerce').on('click', '.quick-cart-update', function(){
		$("[name='update_cart']").trigger("click");
	});

	//Custom Scroll
	function gg_custom_scroll() {

		if ( $( '#side-cart .widget_shopping_cart_content > ul' ).length ) {
			var ps = new PerfectScrollbar('#side-cart .widget_shopping_cart_content > ul', {
				wheelSpeed: 1,
				wheelPropagation: false,
				minScrollbarLength: 20,
				suppressScrollX: true
			});

			$(window).on('resize.customscroll',function() {
				ps.update();
			});
		}
	}
	//gg_custom_scroll();
	
	//Sidecart
	var $doc = $(document),
			win = $(window),
			body = $('body'),
			header = $('.header'),
			wrapper = $('#wrapper'),
			cc = $('.click-capture'),
			cc_close = $('.thb-close'),
			adminbar = $('#wpadminbar'),
			container = $('.site-wrapper'),
			thb_ease = new BezierEasing(0.25,0.46,0.45,0.94);

	gsap.config({
		nullTargetWarn: false
	});

	tlCartNav = gsap.timeline({
		paused: true,
		onStart: function() { container.addClass('open-cart'); },
		onReverseComplete: function() {container.removeClass('open-cart'); },
		onComplete: function() { win.trigger('resize.customscroll'); }
	});

	tlCartNav.from($('#side-cart').find('.mini_cart_item'), {
		duration: 0.15, delay: 0.15, y: "30", opacity:0, ease: thb_ease, stagger: 0.05
	});


	$('.site-header').on('click', '#quick_cart', function() {
		if ( villenoir_wc_settings.is_cart || villenoir_wc_settings.is_checkout || villenoir_wc_settings.header_quick_cart === 'off') {
			return true;
		} else {
			tlCartNav.play();
			gg_custom_scroll();
			return false;
		}
	});


	$doc.keyup(function(e) {
	  if (e.keyCode === 27) {
			tlCartNav.reverse();
	  }
	});

	cc.add(cc_close).on('click', function() {
		tlCartNav.reverse();
		return false;
	});
	body.on('wc_fragments_refreshed added_to_cart removed_from_cart', function() {
		gg_custom_scroll();
		$('.thb-close').on('click', function() {
			tlCartNav.reverse();
			return false;
		});
	});


});