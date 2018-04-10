<?php



function custom_image_uploader_field( $name, $value = '', $weight = 90, $height = 90) {


    $default =  get_stylesheet_directory_uri(). '/img/No_Image_Available.png';

    echo '<div id="myplugin-placeholder">';

    if( ! empty($value) && is_array($value) ) {

        foreach( $value as $id ) {
            $image_attributes = wp_get_attachment_image_src( $id, array($weight, $height) );
            $src = $image_attributes[0];

            echo '<div class="myplugin-image-preview" >'
                    . '<img data-src="' . $default . '" src="' . $src . '" width="' . $weight . 'px" height="' . $height . 'px" />'
                    . '<input type="hidden" id="myplugin-image-input' . $id . '" name="' . $name . '[]"  value="' . $id . '" />'
                    .'<button type="submit" class="remove_image_button button" title="remove">X</button>'
                . '</div>';

        }

    } else {

        $src = $default;

        echo '<div class="myplugin-default-image-preview">'
            . '<img data-src="' . $default . '" src="' . $src . '" width="' . $weight . 'px" height="' . $height . 'px" />'
            . '</div>';
    }

    echo '</div>';


    echo '
<hr>
	<div>
		<div>
			<button type="submit" class="upload_image_button button">Upload media</button>
			
		</div>
	</div>
	';
}





//add to metabox

function image_meta_box() {
    add_meta_box('img_div', 'Metabox for image upload', 'show_box', 'products', 'normal', 'high');
}

add_action( 'admin_menu', 'image_meta_box' );





// input to metabox

function show_box($post) {

    custom_image_uploader_field( 'uploader_image_custom', get_post_meta($post->ID, 'uploader_image_custom',true) );

}




//save data

function save_box_data( $post_id ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;


    $uploader_image_custom = ( isset($_POST['uploader_image_custom']) ) ? $_POST['uploader_image_custom'] : "";

    update_post_meta( $post_id, 'uploader_image_custom', $uploader_image_custom);

    return $post_id;
}

add_action('save_post', 'save_box_data');





