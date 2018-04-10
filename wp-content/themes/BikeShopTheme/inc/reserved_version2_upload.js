/**
 * Created by devit on 25.12.17.
 */
jQuery(document).ready( function( $ ) {

    var myplugin_media_upload;

    $('.upload_image_button').click(function(e) {

        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if( myplugin_media_upload ) {
            myplugin_media_upload.open();
            return;
        }

        myplugin_media_upload = wp.media.frames.file_frame = wp.media({

            //button_text is seting by wp_localize_script() from function.php

            title: button_text.title,
            button:{ text: button_text.button },
            multiple: true //!!!!!!!!!allowing for multiple image selection
        });

        myplugin_media_upload.on( 'select', function(){

            var attachments = myplugin_media_upload.state().get('selection').map(
                function( attachment ) {
                    return attachment.toJSON();
                });

            //loop through the array and do things with attachment
            console.log(attachments);

            var i;
            for (i = 0; i < attachments.length; ++i)
            {
                // add image preview from jquery
                $('#myplugin-placeholder').append(
                    '<div class="myplugin-image-preview">'
                    + '<img src="' + attachments[i].url + '" >'
                    + '<input id="myplugin-image-input' + attachments[i].id+ '" type="hidden" name="uploader_image_custom[]"  value="' + attachments[i].id + '">'
                    + '</div>'
                );

            }

        });

        myplugin_media_upload.open();

    });





    // when to click on button
    $('.remove_image_button').click(function(){
        var result = confirm("Are you sure to remove ?");
        if (result == true) {
            var src = $(this).parent().prev().attr('data-src');
            $(this).parent().prev().attr('src', src);
            $(this).prev().prev().val('');
        }
        return false;
    });
});