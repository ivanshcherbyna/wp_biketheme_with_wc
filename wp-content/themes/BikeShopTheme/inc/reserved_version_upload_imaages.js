/*jQuery(function($){
 //When onclik to button upload SINGLE SELECT
 $('.upload_image_button').click(function(){
 var send_attachment = wp.media.editor.send.attachment;

 var button = $(this);

 wp.media.editor.send.attachment = function(props, attachment) {
 $(button).parent().prev().attr('src', attachment.url);
 $(button).prev().val(attachment.id);
 wp.media.editor.send.attachment = send_attachment;
 }
 wp.media.editor.open(button);

 return false;
 });
 */

//OTHER method for multiple select
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
                    attachment.toJSON();
                    return attachment;
                });

            //loop through the array and do things with attachment

            var i;
            for (i = 0; i < attachments.length; ++i)
            {
                //sample function 1: add image preview
                $('#myplugin-placeholder').after(
                    '<div class="myplugin-image-preview"><img src="' +
                    attachments[i].attributes.url + '" ></div>'
                );

                //sample function 2: add hidden input for each image
                $('#myplugin-placeholder').after
                (
                    '<input id="myplugin-image-input' + attachments[i].id+ '" type="hidden" name="myplugin_attachment_id_array[]"  value="' + attachments[i].id + '">'
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