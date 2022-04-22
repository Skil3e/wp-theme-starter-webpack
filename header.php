<?php
$args = $args ?? array()
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('title') | wp_title("|"); ?></title>
    <?php wp_head(); ?>
    <?php get_template_part("templates/marketing") ?>
</head>

<body class="preload">
<div id="site-header-observer-trigger"></div>
<header id="site-header" class="site-header">
    <a class="site-header__brand" href="<?= get_home_url() ?>">
        <?= GetIconMarkup("/assets/logo") ?? bloginfo('title') ?>
        <span class="sr-only"><?php bloginfo('title') ?></span>
    </a>
    <button id="mobile-menu__open" class="mobile-menu__open">
        <?= GetIconMarkup("/assets/icons/menu") ?>
    </button>
    <nav>
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'container' => 'ul',
                'menu_class' => 'menu site-header__menu',
                'menu_id' => 'site-header-menu',
                'depth' => '3'
            )
        );
        ?>
    </nav>
</header>
