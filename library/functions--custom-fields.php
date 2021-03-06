<?php

function getSiteOptions()
{
    $social = array(
        'phone'     => get_field('field_57ee9be14ea9f', 'option'),
        'facebook'  => get_field('field_57ee9beb4eaa0', 'option'),
        'twitter'   => get_field('field_57ee9ce815723', 'option'),
        'instagram' => get_field('field_57ee9d51cf69c', 'option'),
        'pinterest' => get_field('field_57ee9da466cf3', 'option'),
        'linkedin'  => get_field('field_57ee9dd166cf4', 'option'),
        'email'     => get_field('field_58058e1e3c56d', 'option'),

    );
    $optionsImageId = get_field('field_580ffc8c1b8e5', 'option');
    $optionsImage   = new TimberImage($optionsImageId);
    $info           = array(
        'image' => $optionsImage,
    );
    $section = array(
        'social' => $social,
        'info'   => $info,
    );
    return $section;
}
function getHeaderFields()
{
    $imageId = get_field('field_57eeae178cf5d');
    $section = array(
        'image' => new TimberImage($imageId),
    );
    return $section;
}
function getContentSections()
{

    if (have_rows('field_58059a077e48e')) {
        while (have_rows('field_58059a077e48e')) {
            the_row();
            $content[] = array(
                'title'   => get_sub_field('field_58059a3b7e490'),
                'content' => get_sub_field('field_58059a447e491'),
            );
        }
    }
    $section = array(
        'content' => $content,
    );
    return $section;
}
function getAboutFields()
{

    if (have_rows('field_580fed38e6020')) {
        while (have_rows('field_580fed38e6020')) {
            the_row();
            $teamImageId = get_sub_field('field_580fed46e6021');
            $teamImage   = new TimberImage($teamImageId);
            $team[]      = array(
                'name'  => get_sub_field('field_580fee17e6022'),
                'title' => get_sub_field('field_580fee26e6023'),
                'image' => $teamImage,
            );
        }
    }
    $quote = array(
        'text'   => get_field('field_580fee55e6025'),
        'author' => get_field('field_580fee62e6026'),
    );
    $tourImageId = get_field('field_580fefd3ceaae');
    $tourImage   = new TimberImage($tourImageId);
    $tour        = array(
        'image' => $tourImage,
        'text'  => get_field('field_580fefeeceaaf'),
        'url'   => get_field('field_580feff6ceab0'),
    );

    $section = array(
        'team'  => $team,
        'quote' => $quote,
        'tour'  => $tour,
    );
    return $section;
}

function getFoodTours()
{
    if (have_rows('field_5805b688d65f6')) {
        while (have_rows('field_5805b688d65f6')) {
            the_row();
            $imageId = get_sub_field('field_5805b6e0d65fa');
            $image   = new TimberImage($imageId);
            if (have_rows('field_5805b9783a2d1')) {
                $content = array();
                while (have_rows('field_5805b9783a2d1')) {
                    the_row();
                    $content[] = array(
                        'title'   => get_sub_field('field_5805b9883a2d2'),
                        'content' => get_sub_field('field_5805b9953a2d3'),
                    );
                }
            }
            $section[] = array(
                'title'     => get_sub_field('field_5805b694d65f7'),
                'price'     => get_sub_field('field_5805b735d65fb'),
                'gift'      => get_sub_field('field_5805c923d7504'),
                'link'      => get_sub_field('field_5805b749d65fc'),
                'link_text' => get_sub_field('field_58100452b0a95'),
                'subtitle'  => get_sub_field('field_5805b6a2d65f8'),
                'paragraph' => get_sub_field('field_5805b6acd65f9'),
                'image'     => $image,
                'content'   => $content,

            );

        }
    }

    return $section;

}
function getTourBlocks()
{

    if (have_rows('field_5806355a78a27')) {
        while (have_rows('field_5806355a78a27')) {
            the_row();
            $imageId   = get_sub_field('field_5806358778a29');
            $image     = new TimberImage($imageId);
            $content[] = array(
                'title' => get_sub_field('field_5806357878a28'),
                'image' => $image,
                'link'  => get_sub_field('field_5806366800dad'),
            );
        }
    }
    $section = array(
        'content'      => $content,
        'text_section' => get_field('field_580fda8b71d7a'),
        'small_text'   => get_field('field_580fda9c71d7b'),
    );
    return $section;
}
function getHomepageFields()
{
    $sectionOneImageId   = get_field('field_580e6dbf5a957');
    $sectionTwoImageId   = get_field('field_580e6e235a95e');
    $sectionThreeImageId = get_field('field_580e6e5ddea93');
    $sectionFourImageId  = get_field('field_580e6f54f1b04');
    $section             = array(
        'intro_text'                    => get_field('field_580e6d965a954'),
        'section_one_text'              => get_field('field_580e6dae5a956'),
        'section_one_text_link'         => get_field('field_580e74df9cd2f'),
        'section_one_background_image'  => new TimberImage($sectionOneImageId),
        'section_one_copy'              => get_field('field_580e6dd45a958'),
        'section_one_link_text'         => get_field('field_580e6de65a959'),
        'section_one_link_url'          => get_field('field_580e6df05a95a'),
        'section_two_text'              => get_field('field_580e6e025a95c'),
        'section_two_author'            => get_field('field_580e6e1b5a95d'),
        'section_two_background_image'  => new TimberImage($sectionTwoImageId),
        'section_three_title_text'      => get_field('field_580e6e4ddea92'),
        'section_three_title_link'      => get_field('field_580e75049cd30'),
        'section_three_bg_image'        => new TimberImage($sectionThreeImageId),
        'section_three_text'            => get_field('field_580e6e77dea94'),
        'section_three_link_text'       => get_field('field_580e6e87dea95'),
        'section_three_link_url'        => get_field('field_580e6e9fdea96'),
        'section_four_small_text'       => get_field('field_580e6f37a2b51'),
        'section_four_yellow_text'      => get_field('field_580e6f47a2b52'),
        'section_four_yellow_link'      => get_field('field_580e752e9cd31'),
        'section_four_background_image' => new TimberImage($sectionFourImageId),
    );
    return $section;
}
