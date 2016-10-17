jQuery(document).ready(function($) {
	$('.trigger--nav').on('click touchstart', function(event) {
		event.preventDefault();
		$('body').toggleClass('open');
	});
});