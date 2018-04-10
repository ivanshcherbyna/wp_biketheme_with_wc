<?php



function custom_image_uploader_field( $name, $value = '', $weight = 90, $height = 90) {

    $default =  get_stylesheet_directory_uri(). '/img/No_Image_Available.png';
    if( $value ) {
        $image_attributes = wp_get_attachment_image_src( $value, array($weight, $height) );
        $src = $image_attributes[0];
    } else {
        $src = $default;
    }
    echo '
	<div>
		<img data-src="' . $default . '" src="' . $src . '" width="' . $weight . 'px" height="' . $height . 'px" />
		<div>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Upload</button>
			<button type="submit" class="remove_image_button button" title="remove">X</button>
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

    update_post_meta( $post_id, 'uploader_image_custom', $_POST['uploader_image_custom']);
    return $post_id;
}

add_action('save_post', 'save_box_data');



///////dont must be here because using multiple select from another method

function add_options_page_u() {
    if ( isset( $_POST['page'] ) && $_POST['page'] == 'uplsettings' ) {
        if ( isset( $_POST['action'] ) && 'save' == $_POST['action'] ) {
            update_option('uploader_image_key', $_POST[ 'uploader_image_key' ]);

            die;
        }
    }
    add_submenu_page('options-general.php','Advanteg settings','Settings','edit_posts', 'upload_settings', 'submenu_func_show_options');
}


// submenu form for select
function submenu_func_show_options() {
    if ( isset( $_POST['saved'] ) ){
        echo '<div class="updated"><p>Saved</p></div>';
    }    ?>
    <div class="wrap">
        <form method="post">
            <p class="submit">
                <input name="save" type="submit" class="button-primary" value="Save changes" />
                <input type="hidden" name="action" value="save" />
            </p>
        </form>

    </div><?php
}

add_action('admin_menu', 'add_options_page_u');