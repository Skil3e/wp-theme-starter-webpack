<?php
$postType = get_post_type();
get_header(); ?>

<main class="container page-gutter main__<?= $postType ?>">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        get_template_part("templates/post/single");
    endwhile;
    endif; ?>
</main>

<?php get_footer(); ?>
