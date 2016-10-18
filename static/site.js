jQuery(document).ready(function($) {
    $('.tourSelect').on('change', function(event) {
        event.preventDefault();
        $item = $(this).val();
        changetour($item);
    });

    function changetour(value) {
        $item = value;
        $id = $item;
        console.log($id);
        $('.current').removeClass('current');
        $tour = $('body').find("[data-tour='" + $id + "']");
        $tour.addClass('current');
    }
});