jQuery(document).ready(function($) {
    $('.content-list > a').on('click touchstart', function(event) {
        event.preventDefault();
        $item = $(this);
        changeContent($item);
    });
    $('.contentSelect').on('change', function(event) {
        event.preventDefault();
        $item = $(this).val();
        changeContent($item);
    });

    function changeContent(value) {
        $item = value;
        if ($item.jquery) {
            $id = $item.attr('id');
            if ($item.hasClass('current')) {} else {
                $('.current').removeClass('current');
                // $('.content-section .current').removeClass('current');
                $item.toggleClass('current');
                $content = $('article').find("[data-content='" + $id + "']");
                $content.toggleClass('current');
            }
        } else {
            $id = $item;
            $('.current').removeClass('current');
            // $('.content-section .current').removeClass('current');
            // $item.toggleClass('current');
            $content = $('article').find("[data-content='" + $id + "']");
            $content.toggleClass('current');
        }
    }
});