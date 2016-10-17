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
