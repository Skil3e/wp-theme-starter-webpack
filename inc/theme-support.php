<?php
/*
@package skil3e
---------------------------
  Theme Support
---------------------------
*/
require get_template_directory() . '/inc/theme-support/disable-comments.php';
require get_template_directory() . '/inc/theme-support/images-galleries.php';
require get_template_directory() . '/inc/theme-support/menus.php';
require get_template_directory() . '/inc/theme-support/menus-widgets.php';
require get_template_directory() . '/inc/theme-support/seo.php';
require get_template_directory() . '/inc/theme-support/utilities.php';

add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));

function GetIconMarkup($path)
{
    $filename = get_theme_file_path() . $path . ".svg";
    return file_exists($filename) ? file_get_contents($filename) : null;
}



