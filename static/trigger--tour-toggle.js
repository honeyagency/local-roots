jQuery(document).ready(function($) {
    $('.trigger--tour-toggle').on('click touchstart',  '.border--4', function(event) {
        event.preventDefault();
        $(this).toggleClass('open');
    });
});