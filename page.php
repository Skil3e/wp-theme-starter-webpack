<?php get_header(); ?>

<main class="container page-gutter">
    <article class="flow">
        <?php if (have_posts()) : while (have_posts()) : the_post();
            the_title('<h1 class="page-title">', '</h1>');
            the_post_thumbnail("full", ["class" => "page__image lightbox"]); ?>
            <div class="page__content flow">
                <?php the_content(); ?>
            </div>
        <?php
        endwhile;
        endif; ?>
    </article>
</main>

<?php get_footer(); ?>
