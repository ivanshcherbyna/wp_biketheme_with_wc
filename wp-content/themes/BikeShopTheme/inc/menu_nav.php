<?php
function register_bike_menu()
{
    register_nav_menus(array(
        'header-menu' => __('Header Menu', THEME_OPT),
        'footer-menu' => __('Footer Menu', THEME_OPT),
    ));
}

function bike_nav(){
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu', //section
            'menu'            => 'header-menu', //(id,label,name)
            'container'       => 'nav',
            'container_class' => 'menu-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => 'ul-menu',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '', //text or html before  <a> links
            'after'           => '', //text or html after  <a> links
            'link_before'     => '',//link before
            'link_after'      => '',// link after
            'items_wrap'      => '<ul>%3$s</ul>',
            'depth'           => 3, //number of levels
            'walker'          => new My_Walker_Nav_Menu() //to use walker menu
        )


    );
   
}



//CREATE WALKER CLASS extentds
class My_Walker_Nav_Menu extends Walker_Nav_Menu
{
    /*
     * wp_nav_menu()
     * <div class="menu-container">
     *      <ul> //start_lvl()
     *          <li><a><span> //start_el()
     *          </a></span></li> //end_el()
     *          <li><a>Link</a></li>
     *          <li><a>Link</a></li>
     *          <li><a>Link</a></li>
     *
     *      </ul> // end_lvl()
     *      </div>
     *///ul

    function filter_function_to_image($item_output) {
        return $item_output;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {  // var_dump($item);

        global $biketheme;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        // Generate string with CSS-classes element of menu

        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // func join is convert array in string
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        // Generate ID element

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        //      Generate element of menu

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        // attributes elements, title="", rel="", target="" и href=""
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        if ($item->title == 'BASKET') {
            //$item->title = '<img src="' . esc_attr($biketheme['basket-item']['url']) . '">';
            $item->title = '<i class="fa fa-shopping-basket" aria-hidden="true"></i>';
        }

        // link to text
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;



        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);


        //add_filter('walker_nav_menu_start_el', array( $this, 'filter_function_to_image' ) );

    }

    //closing li a span
}
