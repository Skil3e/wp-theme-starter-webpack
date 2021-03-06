<?php
class CustomNavWalker extends Walker_Nav_Menu
{
  //------------------------------
  // Start Level  
  //------------------------------
  function start_lvl(&$output, $depth = 0, $args = array())
  {
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth >= 0) ? 'menu__dropdown' : '';
    $output  .= "\n$indent<ul class=\"menu $submenu depth_$depth\">\n";
  }

  //------------------------------
  // Start Element  
  //------------------------------
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
  {
    $indent         = ($depth) ? str_repeat("\t", $depth) : '';
    $li_attributes  = '';
    $class_names    = $value = '';
    $hasMegaMenu    = is_active_sidebar('mega-item-' . $item->ID);
    $classes = array();

    // managing divider: add divider class to an element to get a divider before it.
    $divider_class_position = array_search('divider', $classes);

    if ($divider_class_position !== false) {
      $output .= "<li class=\"divider\"></li>\n";
      unset($classes[$divider_class_position]);
    }

    $classes[] = ($args->has_children || $hasMegaMenu) ? ' menu__item__dropdown' : '';
    $classes[] = ($hasMegaMenu) ? 'mega-menu' : '';
    $classes[] = ($item->current || $item->current_item_ancestor) ? ' menu__item--active' : '';
    // $classes[] = ($item->classes ) ? join(' ', $item->classes) : '';

    if ($depth && $args->has_children) {
      $classes[] = 'dropdown-submenu';
    }

    $class_names = join(' ', array_filter($classes));
    $class_names = ' class="menu__item' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';


    $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';


    $linkClass  = 'menu__link';
    // $linkClass .= ($depth == 0) ? ' px--md' : ' p--md';
    $linkClass .= ($args->has_children || $hasMegaMenu) ? ' menu__link__dropdown-parent ' : '';


    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
    // $attributes .= ($args->has_children || $hasMegaMenu) ? ' data-toggle="dropdown"' : '';


    $item_output = $args->before;
    $item_output .= '<a' . $attributes . ' class="' . $linkClass . '">';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

    if ($hasMegaMenu) {
      $output .= '<div class="menu menu__dropdown menu__dropdown__mega-wrapper depth_' . $depth . '">';
      $output .= '<ul class="menu menu__dropdown--mega" >';
      ob_start();
      dynamic_sidebar('mega-item-' . $item->ID);
      $dynamicSidebar = ob_get_contents();
      ob_end_clean();
      $output .=  $dynamicSidebar;
      $output .= "</ul>";
      $output .= "</div>";
    }
  }

  //------------------------------
  // Display Element  
  //------------------------------
  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
  {
    //v($element);
    if (!$element) {
      return;
    }

    $id_field = $this->db_fields['id'];
    $id       = $element->$id_field;

    //display this element
    if (is_array($args[0]))
      $args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
    else if (is_object($args[0]))
      $args[0]->has_children = !empty($children_elements[$element->$id_field]);
    $cb_args = array_merge(array(&$output, $element, $depth), $args);
    call_user_func_array(array(&$this, 'start_el'), $cb_args);

    $id = $element->$id_field;

    // descend only when the depth is right and there are childrens for this element
    if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {
      foreach ($children_elements[$id] as $child) {
        if (!isset($newlevel)) {
          $newlevel = true;
          //start the child delimiter
          $cb_args = array_merge(array(&$output, $depth), $args);
          call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
        }

        $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
      }

      unset($children_elements[$id]);
    }

    if (isset($newlevel) && $newlevel) {
      //end the child delimiter
      $cb_args = array_merge(array(&$output, $depth), $args);
      call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
    }

    //end this element
    $cb_args = array_merge(array(&$output, $element, $depth), $args);
    call_user_func_array(array(&$this, 'end_el'), $cb_args);
  }
}
