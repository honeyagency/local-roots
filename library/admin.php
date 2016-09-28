<?php

/*
This file handles the admin area and functions.

Originally Developed Eddie Machado
URL: http://themble.com/bones/

Torn Apart And Heavilly Opinionated By Josh Reeder-Esparza
Github: @joshre
*/

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
    
    // remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );    // Right Now Widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
    
    // Comments Widget
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
    
    // Incoming Links Widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'core');
    
    // Plugins Widget
    
    // remove_meta_box('dashboard_quick_press', 'dashboard', 'core' );  // Quick Press Widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
    
    // Recent Drafts Widget
    remove_meta_box('dashboard_primary', 'dashboard', 'core');
    
    //
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');
    
    //
    
    // removing plugin dashboard boxes
    remove_meta_box('yoast_db_widget', 'dashboard', 'normal');
    
    // Yoast's SEO Plugin Widget
    
    /*
    have more plugin widgets you'd like to remove?
    share them with us so we can get a list of
    the most commonly used. :D
    https://github.com/eddiemachado/bones/issues
    */
}

/*
Now let's talk about adding your own custom Dashboard widget.
Sometimes you want to show clients feeds relative to their
site's content. For example, the NBA.com feed for a sports
site. Here is an example Dashboard Widget that displays recent
entries from an RSS Feed.

For more information on creating Dashboard Widgets, view:
http://digwp.com/2010/10/customize-wordpress-dashboard/
*/

// removing the dashboard widgets
add_action('admin_menu', 'disable_default_dashboard_widgets');

// adding any custom widgets
add_action('wp_dashboard_setup', 'bones_custom_dashboard_widgets');

// Remove WP 4.2 emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

// changing the logo link from wordpress.org to your site
function bones_login_url() {
    return home_url();
}

// changing the alt text on the logo to show your site name
function bones_login_title() {
    return get_option('blogname');
}

// calling it only on the login page

add_filter('login_headerurl', 'bones_login_url');
add_filter('login_headertitle', 'bones_login_title');

/************* CUSTOMIZE ADMIN *******************/

// Custom CSS for the Admin Dashboard
function custom_css() {
    echo '<style type="text/css"></style>';
}
add_action('admin_head', 'custom_css');

// Custom Backend Footer
function bones_custom_admin_footer() {
    _e('<span id="footer-thankyou">Developed by <a href="http://honeyagency.com" target="_blank">The Honey Agency</a></span>.', 'bonestheme');
}

// adding it to the admin area
add_filter('admin_footer_text', 'bones_custom_admin_footer');

function custom_admin_head() {
    $css = '';
    
    $css = 'td.media-icon img[src$=".svg"] { width: 100% !important; height: auto !important; }';
    
    echo '<style type="text/css">' . $css . '</style>';
}
add_action('admin_head', 'custom_admin_head');

// Setting Up Custom Color Scheme
function buscemi_color_scheme() {
    
    $theme_dir = get_template_directory_uri();
    
    wp_admin_css_color('buscemi', __('Buscemi Theme'), $theme_dir . '/css/buscemi-color-scheme.css', array('#000000', '#000000', '#000000', '#000000'));
}

// add_action('admin_init', 'buscemi_color_scheme');
// UNCOMMENT TO ENABLE

// Making That ^ Color Scheme Default for every user
function set_default_admin_color($user_id) {
    $args = array('ID' => $user_id, 'admin_color' => 'buscemi');
    wp_update_user($args);
}

// add_action('user_register', 'set_default_admin_color');
// UNCOMMENT TO ENABLE

// Removing Some Admin Menus
function remove_menus() {
    
    // remove_menu_page( 'edit-comments.php' );          				//Comments
    
    
}
add_action('admin_menu', 'remove_menus');
?>
