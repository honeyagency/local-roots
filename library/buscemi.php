<?php

// Adding documentation to the dash
function bc_dashboard_widget_function()
{
    $docs_template = get_template_directory() . '/library/docs.php';
    load_template($docs_template);
}
function bc_add_dashboard_widgets()
{
    wp_add_dashboard_widget('wp_dashboard_widget', 'Buscemi Docs', 'bc_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'bc_add_dashboard_widgets');

// add ie conditional html5 shim to header
function add_ie_html5_shim()
{
    echo '<!--[if lt IE 9]>';
    echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
    echo '<![endif]-->';
}
add_action('wp_head', 'add_ie_html5_shim');

function get_image_sizes()
{
    return array(
        'header' => array(
            'resize'     => array(1300),
            'srcset'     => array(3),
            'sizes'      => '100vw',
            'name'       => 'Width 1/4 fix',
            'post_types' => array('post', 'page'),
        ),
    );
}

// Remove WP 4.2 emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

// Getting rid of WP Default jquery and adding from google
if (!is_admin()) {
    add_action("wp_enqueue_scripts", "jquery_enqueue", 11);
}

function jquery_enqueue()
{
    wp_dequeue_script('jquery');
    wp_deregister_script('jquery');
    wp_register_script('jquery',  get_template_directory_uri() . '/app/jquery-1.9.1.min.js', false, null);
}


function localInstall()
{
    if ('127.0.0.1' == $_SERVER["REMOTE_ADDR"]) {
        $res = false;
    } else {

        $res = true;
    }
    return ($res);
}

// Enqueuing all of our scripts and styles
function buscemi_scripts()
{
    wp_enqueue_script('jquery');
    if (localInstall() == true) {
        // $reloadScript = 'http://localhost:35729/livereload.js';
        // wp_register_script('livereload', $reloadScript, null, false, true);
        // wp_enqueue_script('livereload');
    }
    wp_register_script('picturefill', get_template_directory_uri() . '/app/vendors/picturefill.min.js', null, false, true);
    wp_enqueue_script('picturefill');
    wp_enqueue_style('buscemi_style', get_template_directory_uri() . '/app/main.min.css', null, null, null);
    wp_enqueue_script('buscemi_script', get_template_directory_uri() . '/app/app.min.js', array('jquery'), null, null, true);
}
add_action('wp_enqueue_scripts', 'buscemi_scripts');

// Allowing SVG preveiw in WP Upload
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Setting up ACF options page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

// added 12/4/2019
function remove_media_attachments() {
    if ( is_attachment() ) {
        global $post;
        if ( $post && $post->post_parent ) {
            wp_redirect( esc_url( get_permalink( $post->post_parent ) ), 301 );
            exit;
        } else {
            wp_redirect( esc_url( home_url( '/' ) ), 301 );
            exit;
        }
    }
}
add_action( 'template_redirect', 'remove_media_attachments' );

require_once 'functions--custom-fields.php';
require_once 'functions--custom-posts.php';

