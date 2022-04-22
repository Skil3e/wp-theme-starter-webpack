<?php
/*
@package skil3e
---------------------------
  Cleanup HTML & Meta
---------------------------
1. Remove Version of WordPress from Head
2. Remove  Unnecessary Tags from Head and footer
3. Remove wp-embed
4. Remove Recent Comments Style
5. Remove archive title prefixes.
6. Disable gutenberg style in Front
*/

//------------------------------
// Remove Version of WordPress from Head
//------------------------------
// CSS & JS
function skil3e_remove_wp_version_stings($src)
{
  global $wp_version;
  parse_str(parse_url($src, PHP_URL_QUERY), $query);
  if (!empty($query['ver']) && $query['ver'] === $wp_version) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('script_loader_src', 'skil3e_remove_wp_version_stings');
add_filter('style_loader_src', 'skil3e_remove_wp_version_stings');
// Meta
function skil3e_remove_meta_version()
{
  return '';
}
add_filter('the_generator', 'skil3e_remove_meta_version');


//------------------------------
// Remove Unnecessary Tags
//------------------------------
add_action('after_setup_theme', 'prefix_remove_unnecessary_tags');

function prefix_remove_unnecessary_tags()
{
  // REMOVE WP EMOJI
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');

  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');
  // remove all tags from header
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'index_rel_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'start_post_rel_link', 10, 0);
  remove_action('wp_head', 'parent_post_rel_link', 10, 0);
  remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action('wp_head', 'rest_output_link_wp_head');
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('template_redirect', 'rest_output_link_header', 11);
  remove_action('wp_head', 'wp_resource_hints', 2);
  // language
  add_filter('multilingualpress.hreflang_type', '__return_false');
}
//------------------------------
// Remove wp-embed
//------------------------------
add_action('wp_footer', 'my_deregister_scripts');
function my_deregister_scripts()
{
  wp_deregister_script('wp-embed');
}
//------------------------------
// Remove Recent Comments Style
//------------------------------
add_action('widgets_init', 'remove_recent_comments_style');
function remove_recent_comments_style()
{
  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

//------------------------------
// Remove archive title prefixes.
//------------------------------
function grd_custom_archive_title($title)
{
  return preg_replace('#^[\w\d\s]+:\s*#', '', strip_tags($title));
}
add_filter('get_the_archive_title', 'grd_custom_archive_title');

//------------------------------
// Disable gutenberg style in Front
//------------------------------
function wps_deregister_styles()
{
  wp_dequeue_style('wp-block-library');
}
add_action('wp_print_styles', 'wps_deregister_styles', 100);

//------------------------------
// Remove Default Jquery
//------------------------------
//add_action('wp_enqueue_scripts', 'no_more_jquery');
//function no_more_jquery(){
//   wp_deregister_script('jquery');
//}
