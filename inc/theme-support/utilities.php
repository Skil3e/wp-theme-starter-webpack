<?php
/*
@package skil3e
---------------------------
  Utilities
---------------------------
1. Add Class to tag link
2. Add Class to category link
3. Add markup to Next & Previous Links
4. Except Settings
*/

//---------------------------
// Add Class to tag link
//---------------------------
add_filter('the_tags', 'skil3e_add_class_to_tags', 10, 1);

function skil3e_add_class_to_tags($html)
{
    $postid = get_the_ID();
    $class_to_add = 'tag-link';
    $html = str_replace('<a', '<a class="' . $class_to_add . '"', $html);
    return $html;
}

//---------------------------
// Add Class to category link
//---------------------------
add_filter('the_category', 'skil3e_add_class_to_category', 10, 3);

function skil3e_add_class_to_category($thelist, $separator, $parents)
{
    $class_to_add = 'category-link';
    return str_replace('<a href="', '<a class="' . $class_to_add . '" href="', $thelist);
}

//---------------------------
// Next & Previous Links
//---------------------------
function posts_link_next_class($format)
{
    $format = str_replace('href=', 'class="ml--auto" href=', $format);
    return $format;
}

add_filter('next_post_link', 'posts_link_next_class');

function posts_link_prev_class($format)
{
    $format = str_replace('href=', 'class="mr--auto" href=', $format);
    return $format;
}

add_filter('previous_post_link', 'posts_link_prev_class');

//------------------------------
// Except Settings
//------------------------------
add_filter('excerpt_length', 'skil3e_excerpt_length', 999);
function skil3e_excerpt_length($length)
{
    return 25;
}

add_filter('excerpt_more', 'skil3e_excerpt_more');

function skil3e_excerpt_more($more)
{
    if (!is_single()) {
        $length = str_word_count(strip_tags(get_the_content()));
        $more = sprintf(
            '.. <a class="read-more" href="%1$s">%2$s</a>',
            get_permalink(get_the_ID()),
            __($length . ' more words', 'textdomain')
        );
    }
    return $more;
}
