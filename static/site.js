jQuery(document).ready(function($) {
	$('.trigger--menu').on('click touchstart', function(event) {
		event.preventDefault();
		$('body').toggleClass('open');
	});
});