<?php
/*
@package skil3e
------------------------------
  Images
------------------------------
1. Post Thumbnails
2. Default image link settings
3. Add class to image
4. Add class to image anchor
5. Galleries
*/

//------------------------------
// Post Thumbnails
//------------------------------
add_theme_support('post-thumbnails');
add_image_size('facebook', 1200, 628, true);

//------------------------------
// Default image link settings
//------------------------------
add_action('after_setup_theme', 'skil3e_default_image_settings');

function skil3e_default_image_settings()
{
    update_option('image_default_link_type', 'media');
}

//------------------------------
// Add class to image
//------------------------------
add_filter('the_content', 'add_custom_class_to_all_images');

function add_custom_class_to_all_images($content)
{
    $my_custom_class = "lightbox"; // your custom class
    $add_class = str_replace('<img class="', '<img class="' . $my_custom_class . ' ', $content); // add class
    return $add_class; // display class to image
}

//------------------------------
// Add class to image anchor
//------------------------------
add_filter('the_content', 'add_classes_to_image_anchor', 100, 1);

function add_classes_to_image_anchor($html)
{
    $classes = 'lightbox'; // can do multiple classes, separate with space

    $patterns = array();
    $replacements = array();

    $patterns[0] = '/<a(?![^>]*class)([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor tag has no existing classes
    $replacements[0] = '<a\1 class="' . $classes . '"><img\2></a>';

    $patterns[1] = '/<a([^>]*)class="([^"]*)"([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor has existing classes contained in double quotes
    $replacements[1] = '<a\1class="' . $classes . ' \2"\3><img\4></a>';

    $patterns[2] = '/<a([^>]*)class=\'([^\']*)\'([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor has existing classes contained in single quotes
    $replacements[2] = '<a\1class="' . $classes . ' \2"\3><img\4></a>';

    $html = preg_replace($patterns, $replacements, $html);

    return $html;
}

//------------------------------
// Galleries
//------------------------------
add_filter('post_gallery', 'customFormatGallery', 10, 2);

function customFormatGallery($string, $attr)
{
    $posts_order_string = $attr['ids'];
    $posts_order = explode(',', $posts_order_string);

    $output = "<div class=\"post-gallery\">";
    $posts = get_posts(array(
        'include' => $posts_order,
        'post_type' => 'attachment',
        'orderby' => 'post__in'
    ));

    if ($attr['orderby'] == 'rand') {
        shuffle($posts);
    }

    foreach ($posts as $imagePost) {
        $output .= '<a class="post-gallery__link" data-gallery="gallery" href="' . wp_get_attachment_image_src($imagePost->ID, 'full')[0] . '">';
        $output .= wp_get_attachment_image($imagePost->ID, null, false, ['class' => 'post-gallery__image attachment-' . $imagePost->ID]);
        $output .= '</a>';
    }

    $output .= "</div>";
    return $output;
}




