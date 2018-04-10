<?php

add_action( 'init', 'custom_post_type');
    function custom_post_type()
        {
        $labels = array(
            'name' => 'Product',
            'singular_name' => 'Products',
            'add_new' => 'Add Product',
            'add_new_items' => 'Add Products',
            'all_items' => 'Products',
            'edit_item' => 'Edit item',
            'new_item' => 'New item',
            'view_item' => 'View item',
            'search_items' => 'Search item',
            'featured_image' => true,
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in trash',
            'parent_item_colon' => 'Parent Singular Name:',
            'menu_name' => 'All Products',
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'description',
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => null,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var' => 'films',
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'supports' => array(
            'title',
            'editor',
            //'thumbnail',

            //'trackbacks',
            'custom-fields' => 'my_meta_block',

            'revisions' =>  true,

        ),
        );

    register_post_type('products', $args);

}




/*add_action( 'add_meta_boxes', 'listing_image_add_metabox' ); //test version for create metabox
    function listing_image_add_metabox () {
    add_meta_box( 'listingimagediv', __( 'Add Image', 'text-domain' ), 'listing_image_metabox_callback', 'products', 'side', 'low');
}
function listing_image_metabox_callback ($post){
    echo "<a href='#' name='image'>Select your image</a>";
}
*/