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
    $section = array(
        'social' => $social,
    );
    return $section;
}
function getHeaderFields()
{
    // $imageId =
    $section = array(
        'image' => get_field('field_57eeae178cf5d'),
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

    return $content;
}
function getHomepageFields()
{
    $sectionOneImageId   = get_field('field_580e6dbf5a957');
    $sectionTwoImageId   = get_field('field_580e6e235a95e');
    $sectionThreeImageId = get_field('field_580e6e5ddea93');
    $sectionFourImageId  = get_field('field_580e6f54f1b04');
    $section             = array(
        'intro-text'                    => get_field('field_580e6d965a954'),
        'section-one-text'              => get_field('field_580e6dae5a956'),
        'section-one-text-link'         => get_field('field_580e74df9cd2f'),
        'section-one-background-image'  => new TimberImage($sectionOneImageId),
        'section-one-copy'              => get_field('field_580e6dd45a958'),
        'section-one-link-text'         => get_field('field_580e6de65a959'),
        'section-one-link-url'          => get_field('field_580e6df05a95a'),
        'section-two-text'              => get_field('field_580e6e025a95c'),
        'section-two-author'            => get_field('field_580e6e1b5a95d'),
        'section-two-background-image'  => new TimberImage($sectionTwoImageId),
        'section-three-title-text'      => get_field('field_580e6e4ddea92'),
        'section-three-title-link'      => get_field('field_580e75049cd30'),
        'section-three-bg-image'        => new TimberImage($sectionThreeImageId),
        'section-three-text'            => get_field('field_580e6e77dea94'),
        'section-three-link-text'       => get_field('field_580e6e87dea95'),
        'section-three-link-url'        => get_field('field_580e6e9fdea96'),
        'section-four-small-text'       => get_field('field_580e6f37a2b51'),
        'section-four-yellow-text'      => get_field('field_580e6f47a2b52'),
        'section-four-yellow-link'      => get_field('field_580e752e9cd31'),
        'section-four-background-image' => new TimberImage($sectionFourImageId),
    );
    return $section;
}
