<article class="post-summary">
    <a class="post-summary__link" href="<?= get_permalink() ?>">
        <?php the_post_thumbnail("full", ["class" => "post-summary__image"]); ?>
        <div class="post-summary__content">
            <p class="post-summary__date"><?= get_the_time('F j, Y'); ?></p>
            <h2 class="post-summary__title"><?php the_title(); ?></h2>
        </div>
    </a>
</article>
