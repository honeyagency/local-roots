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
