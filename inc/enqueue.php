<?php
/*
@package skil3e
---------------------------
  Enqueue Functions Styles & Scripts
---------------------------
1. Admin
2. Public
*/

//------------------------------
//Enqueue Admin Styles & Scripts
//------------------------------
add_action('admin_enqueue_scripts', 'enqueuing_login_scripts');

function enqueuing_login_scripts()
{
}

//------------------------------
//Enqueue Public Styles & Scripts
//------------------------------
add_action('wp_enqueue_scripts', 'skil3e_script_enqueue');

function skil3e_script_enqueue()
{
    //Styles
    wp_enqueue_style('main', get_stylesheet_directory_uri() . '/style.css', array(), '1.0.0', 'all');

    //Scripts
    wp_enqueue_script('bundlleJs', get_stylesheet_directory_uri() . '/assets/js/bundle.js', array(), '1.0.0', true);
    wp_enqueue_script('recaptchav3', 'https://www.google.com/recaptcha/api.js?render=' . RECAPTCHA_SITE_KEY);

    if (is_page_template("contact.php")) {
        wp_enqueue_script('contact', get_stylesheet_directory_uri() . '/assets/js/dist/contact.js', ["bundlleJs"], '1.0.0', true);
    }
}

//function skil3e_add_font_attributes($html, $handle)
//{
//    if ($handle === 'font-regular' || $handle === 'font-bold' || $handle === "font-display-regular" || $handle === "font-display-bold") {
//        return str_replace("rel='stylesheet'", "rel='preload' as='font' type='font/ttf' crossorigin", $html);
//    }
//    return $html;
//}
//
//add_filter('style_loader_tag', 'skil3e_add_font_attributes', 10, 2);
