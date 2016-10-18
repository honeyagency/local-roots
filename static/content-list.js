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
                $content = $('article').find("[data-content='" + $id + "']");

                $('.content-section.current').removeClass('current');
                $('.content-toggle.current').removeClass('current');
                
                $item.toggleClass('current');
                $content.toggleClass('current');
            }
        } else {
            $id = $item;
            $('.content-section.current').removeClass('current');
            // $('.content-toggle .current').removeClass('current');
            // $itemtoggleClass.('current');
            $content = $('article').find("[data-content='" + $id + "']");
            $content.toggleClass('current');
        }
    }
});