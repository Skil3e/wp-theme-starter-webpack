<?php
/*
@package skil3e
---------------------------
  SEO
---------------------------
1. Add meta tags to head
*/
//---------------------------
// Add Social Media meta tags
//---------------------------
add_action('wp_head', 'skil3e_add_link_in_head', 3);
function skil3e_add_link_in_head()
{
    global $post;
    if (!empty($post)) {
        $postID = $post->ID;
        $post = get_post($postID);
        $title = $post->post_title;
        $thumb = get_the_post_thumbnail_url($postID, "facebook");
        $url = get_permalink($postID);
        $postExerpt = $post->post_excerpt;

        //Edit Exerpt
        if ($postExerpt) {
            $excerpt = $postExerpt;
        } else {
            $striped = strip_tags($post->post_content);
            $noSpace = preg_replace('/\s\s+/', ' ', $striped);
            $noReturn = str_replace("\n", '', $noSpace);
            $excerpt = preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $noReturn);
        };

        if (strlen($excerpt) > 199) {
            $excerpt = substr($excerpt, 0, 199);
            $excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
            $excerpt .= '...';
        } ?>
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

        <?php
        if (empty($excerpt)) { ?>
            <meta name="description" content="<?php bloginfo('description'); ?>">
        <?php };

        if (!empty($excerpt)) { ?>
            <meta name="description" content="<?php echo $excerpt; ?>">
        <?php };

        if (!empty($title)) { ?>
            <meta property="og:title" content="<?php echo $title; ?>">
        <?php };

        if (!empty($excerpt)) { ?>
            <meta property="og:description" content="<?php echo $excerpt; ?>">
        <?php };

        if ($thumb) { ?>
            <meta property="og:image" content="<?php echo $thumb ?>">
        <?php }

        if (!empty($url)) { ?>
            <meta property="og:url" content="<?php echo $url ?>">
        <?php } ?>
        <meta property="og:site_name" content="<?php bloginfo('title'); ?>">
        <?php

        if (!empty($title)) { ?>
            <meta name="twitter:title" content="<?php echo $title; ?>">
        <?php };

        if (!empty($excerpt)) { ?>
            <meta name="twitter:description" content="<?php echo $excerpt; ?>">
        <?php };

        if ($thumb) { ?>
            <meta name="twitter:image" content="<?php echo $thumb ?>">
            <meta name="twitter:card" content="summary_large_image">
        <?php }
    }
}
