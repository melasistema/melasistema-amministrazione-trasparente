(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	/*************************
	 Predefined Variables
	 *************************/
	var MELASISTEMA = {},
		$window = $(window),
		$document = $(document),
		$body = $('body');

	//Check if function exists
	$.fn.exists = function () {
		return this.length > 0;
	};

	/*************************
	 Functions
	 *************************/
	MELASISTEMA.smoothScroll = function() {

		$('a[href^="#"]').click(function(event) {
			var target = this.hash;
			if (target == "") return false;

			event.preventDefault();
			$('html, body').animate({
				scrollTop: $(target).offset().top
			}, 600); // Adjust the speed (milliseconds) as needed
		});

	}

	/*************************
	 Let's have fun
	 *************************/
	// Init Functions
	function init() {
		// console.log( 'init' );
	} init();

	// Document Ready functions
	$document.ready( function () {
		/*console.log( 'document ready' );*/
		MELASISTEMA.smoothScroll();
	});

	// Window Load functions
	window.onload = function () {
		// console.log('onload');
	}

})( jQuery );
