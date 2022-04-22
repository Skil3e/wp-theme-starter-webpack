<article class="post flow">
    <?php
    the_title('<h1 class="post__title">', '</h1>'); ?>
    <p class="post__date"><?= get_the_time('F j, Y'); ?></p>
    <?php the_post_thumbnail("full", ["class" => "post__image lightbox"]); ?>
    <div class="post__categories">
        <?php the_category(' | '); ?>
    </div>
    <?php
    the_content();
    ?>
</article>
