<?php
require get_template_directory() . '/inc/acf/acf-fields.php';
//------------------------------
//Options Page
//------------------------------
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'     => 'Theme Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-settings',
        'capability'    => 'edit_posts',
        'redirect'        => false,
        'position' => 2
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Marketing',
        'menu_title'    => 'Marketing',
        'parent_slug'    => 'theme-settings',
    ));
}
