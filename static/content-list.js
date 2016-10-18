jQuery(document).ready(function($) {
    $('.content-list > a').on('click touchstart', function(event) {
        event.preventDefault();
        // console.log($(this));
        $id = $(this).attr('id');
        if ($(this).hasClass('current')) {
        	console.log('fish');
        } else {
            $('.current').removeClass('current');
            // $('.content-section .current').removeClass('current');
            $(this).toggleClass('current');
            $content = $('article').find("[data-content='" + $id + "']");
            $content.toggleClass('current');
        }
    });
});