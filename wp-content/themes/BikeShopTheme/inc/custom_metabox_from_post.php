<?php
// Add block for custom post (products)
add_action('add_meta_boxes', 'my_custom_box');
function my_custom_box(){
    $screens = array( 'products', 'page' );
    add_meta_box( 'myplugin_sectionid', 'Name of metablock', 'my_meta_box_callback', $screens );
}

// HTML Code
function my_meta_box_callback( $post/*, $meta*/ ){
    //$screens = $meta['args'];
    $data = get_post_meta($post->ID, '_my_key', true);
    // VIEW
    echo '<label for="my_new_field">TITLE OF CUSTOM METABOX</label> ';
    echo '<input type="text" id= "my_new_field" name="my_new_field" value="'.esc_attr($data).'" size="20" />';
}

function clear_post_meta_text($value){
    return esc_html($value);
}




// Save metabox text if save post
add_action( 'save_post', 'my_save_postdata' );
add_action( 'edit_post', 'my_save_postdata' );
function my_save_postdata( $post_id ) {
    // When isset
    if ( ! isset( $_POST['my_new_field'] ) )
        return;



    // if autosave nothing doing
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return;



    //  CLEAR
    $my_data = clear_post_meta_text( $_POST['my_new_field'] );

    // SAVE OR UPDATE
    update_post_meta( $post_id, '_my_key', $my_data );
}

function get_meta_custom_post($post_id){
    $post_id = (int) $post_id;
    $key='_my_key';
    $args=false;

    $postMetabox = get_post_meta($post_id, $key, $args); //вызов метода Вордпресс поиска по ключу конкретного поста
    //var_dump($postMetabox); die;
    if( ! empty($postMetabox) ) {


        foreach( $postMetabox as $postmeta ) {
            echo $postmeta;
        }
    }
    

}

////test another func
function get_meta_custom_post_anothermethod($post_id){
    $post_id = (int) $post_id;


    $postMetabox = get_post_custom($post_id); //вызов метода другого Вордпресс поиска по ключу конкретного поста
    $my_customField = $postMetabox['_my_key'];

        foreach( $my_customField as $key => $value) {
            echo  $value;
        }


}