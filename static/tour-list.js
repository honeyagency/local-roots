jQuery(document).ready(function($) {
    $('.tourSelect').on('change', function(event) {
        event.preventDefault();
        $item = $(this).val();
        $text = $(this).children('option[value="' + $item + '"]').text();
        changetour($item, $text);
    });
    $('.trigger--tour-toggle > .tour').on('click touchstart', function(event) {
        event.preventDefault();
        $item = $(this).attr('data-value');
        $text = $(this).text();
        changetour($item, $text);
    });

    function changetour(value, text) {
        $item = value;
        $text = text;
        $id = $item;
        if ($id == 'choose') {} else {
            $('.tours.current').removeClass('current');
            $tour = $('main').find("[data-tour='" + $id + "']");
            $tour.addClass('current');
            $('.choose > span').text($text);
            $('.trigger--tour-toggle').removeClass('open');
            $('.tourSelect').val($id);
        }
    }
});