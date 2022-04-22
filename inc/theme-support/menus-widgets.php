<?php
/*
@package skil3e
------------------------------
  Menus and Widgets
------------------------------
1. Register Custom Navigation Walker
2. Widgets Areas
3. Add Class to Widgets
*/
function skil3e_theme_setup()
{
    add_theme_support('menus');
    add_theme_support('editor-styles');
    register_nav_menu('primary', 'Primary Navigation');
    register_nav_menu('social', 'Social Menu');
    register_nav_menu('footer', 'Footer Navigation');
}
add_action('init', 'skil3e_theme_setup');
//------------------------------
// Register Custom Navigation Walker
//------------------------------
require_once get_template_directory() . '/inc/theme-support/wp_custom-mega-navwalker.php';

//------------------------------
// Add Dark Mode toggle to menu
//------------------------------
// add_filter('wp_nav_menu_items', 'skil3e_darkmode_toggle_in_header', 10, 2);
// function skil3e_darkmode_toggle_in_header($nav, $args)
// {
//     $checked = $_COOKIE['color-theme'] == "dark" ? "checked" : "";

//     $toggler = '<div class="switch theme-switch ml--sm flex--center py--md lg:p--0" title="Toggle dark mode">';
//     $toggler .= '<input id="themeChanger" class="switch__checkbox" type="checkbox" ' . $checked . ' />';
//     $toggler .= '<label for="themeChanger" class="switch__label sm"><span class="switch__button flex--center-middle"></span></label>';
//     $toggler .= '<label for="themeChanger" class="block ml--sm text--textDimmed md:none">Dark Mode</label>';
//     $toggler .= '</div>';

//     if ($args->theme_location == 'primary')
//         return $nav . $toggler;
//     return $nav;
// }

//------------------------------
// Widgets Areas
//------------------------------
add_action('widgets_init', 'skil3e_widget_setup');
function skil3e_widget_setup()
{
    register_sidebar(
        array(
            'name'          => __('Right Sidebar', 'skil3e'),
            'id'            => 'right_sidebar',
            'description'   => '',
            'class'         => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s py-3">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        )

    );
    //register MegaMenu widget if the Mega Menu is set as the menu location
    $location = 'primary';
    $css_class = 'mega-menu';
    $locations = get_nav_menu_locations();
    if (isset($locations[$location])) {
        $menu = get_term($locations[$location], 'nav_menu');
        if ($items = wp_get_nav_menu_items($menu->name)) {
            foreach ($items as $item) {
                if (in_array($css_class, $item->classes)) {
                    register_sidebar(array(
                        'id'   => 'mega-item-' . $item->ID,
                        'description' => 'Mega Menu items',
                        'name' => $item->title . ' - Mega Menu',
                        'before_widget' => '<li id="%1$s" class="mega-menu-item">',
                        'after_widget' => '</li>',
                        'before_title'  => '<h3 class="widget-title">',
                        'after_title'   => '</h3>'
                    ));
                }
            }
        }
    }
}

//------------------------------
// Add Class to Widgets
//------------------------------
function needbandwidget_form_extend($instance, $widget)
{
    if (!isset($instance['classes']))
        $instance['classes'] = null;
    $row = "Class:\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\n";
    $row .= "</p>\n";
    echo $row;
    return $instance;
}
add_filter('widget_form_callback', 'needbandwidget_form_extend', 10, 2);
function needbandwidget_update($instance, $new_instance)
{
    $instance['classes'] = $new_instance['classes'];
    return $instance;
}
add_filter('widget_update_callback', 'needbandwidget_update', 10, 2);
function needbanddynamic_sidebar_params($params)
{
    global $wp_registered_widgets;
    $widget_id    = $params[0]['widget_id'];
    $widget_obj    = $wp_registered_widgets[$widget_id];
    $widget_opt    = get_option($widget_obj['callback'][0]->option_name);
    $widget_num    = $widget_obj['params'][0]['number'];
    if (isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']))
        $params[0]['before_widget'] = preg_replace('/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1);
    return $params;
}
add_filter('dynamic_sidebar_params', 'needbanddynamic_sidebar_params');
