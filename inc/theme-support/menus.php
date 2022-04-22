<?php
add_filter('nav_menu_css_class', 'menu_item_css_class', 10, 2);
function menu_item_css_class($classes, $item)
{
    return ['menu__item'];
}

add_filter('nav_menu_link_attributes', 'menu_link_css_class', 10, 3);
function menu_link_css_class($attributes, $item)
{
    $attributes['class'] = 'menu__link';
    if (!empty($attributes['aria-current'])) {
        $attributes['class'] .= ' menu__link--active';
    }
    return $attributes;
}
