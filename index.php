<?php
$archive_title = get_the_archive_title();
get_header(); ?>
<main class="container page-gutter flow">
    <h1 class="page-title"> <?= $archive_title ?></h1>
    <div class="post-summary__wrapper">
        <?php if (have_posts()) :
        while (have_posts()) : the_post();
            $postType = get_post_type();
            get_template_part("templates/post/summary");
        endwhile; ?>
    </div>
    <?php
    if (get_next_posts_link()) {
        next_posts_link();
    }
    ?>
    <?php
    if (get_previous_posts_link()) {
        previous_posts_link();
    }
    ?>

    <?php else: ?>

        <p>No posts found. :(</p>

    <?php endif; ?>

</main>
<?php get_footer(); ?>
