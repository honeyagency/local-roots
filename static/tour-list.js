jQuery(document).ready(function($) {
    $('.tourSelect').on('change', function(event) {
        event.preventDefault();
        $item = $(this).val();
        changetour($item);
    });

    function changetour(value) {
        $item = value;
        $id = $item;
        $('.current').removeClass('current');
        $tour = $('main').find("[data-tour='" + $id + "']");
        $tour.addClass('current');
    }
});