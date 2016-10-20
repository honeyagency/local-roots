jQuery(document).ready(function($) {
    $('.trigger--tour-toggle').on('click touchstart',  '.choose', function(event) {
        event.preventDefault();
        $(this).parent('.trigger--tour-toggle').toggleClass('open');
    });
});