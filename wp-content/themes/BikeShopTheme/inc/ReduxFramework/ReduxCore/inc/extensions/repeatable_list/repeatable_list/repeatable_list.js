/*global redux_change, wp, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.repeatable_list = redux.field_objects.repeatable_list || {};

    $( document ).ready(
        function() {
            redux.field_objects.repeatable_list.init();
        }
    );

    redux.field_objects.repeatable_list.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.ll-repeateble-container' );
        }


        $( selector ).each(
            function() {
                var last_index;
                var required_fields;
                var indexes = [];
                var _this = $( this );
                var rows = _this.find('.repeatable-row');
                var tpl = $(_this.find('.repeatable-row-template').html());
                var fixed = _this.data('fixed');
                var max = (_this.data('max') != '')? _this.data('max') : false;
                var indexes = [];
                var fieldsToReInit = [
                    'media',
                    'gallery',
                    'select',
                    'slider',
                    'checkbox',
                    'spinner',
                    'image_select',
                    'ace_editor',
                    'color',
                    'background',
                    'color_gradient',
                    'link_color',
                    'dimensions',
                    'multi_text',
                    'button_set',
                    'border'
                ];

                last_index = (typeof _this.data('index') == 'undefined' )? tpl.data('index') : _this.data('index');

                var functions = {

                    add: function(button) {
                        if(!fixed && (max && rows.length < max) || (!fixed && !max)){
                            last_index++;
                            _this.data('index', last_index);
                            var toInsert = tpl.clone();
                            toInsert.appendTo(_this.find('.repeatable-content'));
                            tpl = functions.prepareTemplate();

                            functions.reinit( toInsert );
                            
                            rows = _this.find('.repeatable-row');

                            if(max && rows.length == max){
                                button.prop('disabled', true);
                            }

                            var name = _this.find('.repeatable-content').data('name');
                            var empty = _this.find('input[name="' + name + '"]');
                            if(empty.length){
                                empty.remove();
                            }
                        }
                    },

                    delete_row: function(event){
                        event.preventDefault();
                        var content = $(this).closest('.repeatable-row');
                        if(content.length){
                            var container = content.closest('.repeatable-content');

                            content.slideUp(300, function(){
                                content.remove();
                                if(!container.find('.repeatable-row').length){
                                    var name = container.data('name');
                                    $('<input type="hidden" name="' + name + '" value="">').appendTo(container);
                                }

                                rows = _this.find('.repeatable-row');

                                var add_button = _this.find('.repeatable-controls .add-button');
                                if(add_button.prop('disabled')){
                                    add_button.prop('disabled', false);
                                }
                            });

                        }
                    },

                    prepareTemplate: function() {
                        tpl.data('index', last_index);

                        var title = tpl.find('.row-title');
                        var fields = tpl.find('input, textarea, select');
                        var prev_index = last_index-1;

                        //Change title
                        title.html(title.html().replace(prev_index+1, last_index+1));
                        
                        //Change attr
                        fields.each(function(index, el) {
                            var name = $(el).prop('name');
                            var id = $(el).prop('id');
                            if(name != ''){
                                $(el).prop('name', name.replace('['+prev_index+']', '['+last_index+']'));
                                $(el).prop('id', id.replace('-'+prev_index+']', '-'+last_index));
                            }
                        });

                        var wrapper = $('<div></div>');
                        tpl.appendTo(wrapper)
                        _this.find('.repeatable-row-template').text(wrapper.html());

                        return tpl;
                    },

                    reinit: function(container) {
                        if(_this.hasClass('accordion'))
                            container.find('.row-content').show();
                        //Editor
                        if(container.find('textarea.wp-editor-area').length){
                            container.find('textarea.wp-editor-area').each(function(index, el) {
                                var id = $(el).prop('id'),
                                    stamp = 'editor-' + new Date().getTime(),
                                    fieldset = $(el).closest('fieldset').prop('data-id', stamp),
                                    prev = $(el).closest('.repeatable-row').prev().find('textarea.wp-editor-area'),
                                    text = fieldset.html(),
                                    qtinitConfig = {
                                        buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
                                    },
                                    mceinitConfig = {
                                        add_unload_trigger: false,
                                        browser_spellcheck: true,
                                        content_css: "/wp-includes/css/dashicons.min.css,/wp-includes/js/tinymce/skins/wordpress/wp-content.css",
                                        convert_urls: false,
                                        end_container_on_empty_block: true,
                                        entities: "38,amp,60,lt,62,gt",
                                        entity_encoding: "raw",
                                        fix_list_elements: true,
                                        formats: {
                                            aligncenter: [{
                                                selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                                                styles: {
                                                    textAlign: "center"
                                                }
                                            },
                                            {
                                                classes: "aligncenter",
                                                selector: "img,table,dl.wp-caption"
                                            }],
                                            alignleft: [{
                                                selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                                                styles: {
                                                    textAlign: "left"
                                                }
                                            },
                                            {
                                                classes: "alignleft",
                                                selector: "img,table,dl.wp-caption"
                                            }],
                                            alignright: [{
                                                selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                                                styles: {
                                                    textAlign: "right"
                                                }
                                            },
                                            {
                                                classes: "alignright",
                                                selector: "img,table,dl.wp-caption"
                                            }],
                                            strikethrough: {
                                                inline: "del"
                                            }
                                        },
                                        indent: false,
                                        keep_styles: false,
                                        menubar: false,
                                        plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wpview",
                                        preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
                                        relative_urls: false,
                                        remove_script_host: false,
                                        resize: false,
                                        skin: "lightgray",
                                        tabfocus_elements: "content-html,save-post",
                                        theme: "modern",
                                        toolbar1: "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_adv",
                                        toolbar2: "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo",
                                        toolbar3: "",
                                        toolbar4: "",
                                        wp_autoresize_on: true,
                                        wpautop: true,
                                        wpeditimage_disable_captions: false,
                                        wpeditimage_html5_captions: false
                                    },
                                    qtinit, mceinit;
                                    


                                text = text.replace(new RegExp(id,"g"), stamp),
                                fieldset.html(text);

                                qtinit = tinyMCEPreInit.qtInit[stamp] = tinymce.extend( qtinitConfig, false, tinyMCEPreInit.qtInit[stamp] );
                                mceinit = tinyMCEPreInit.mceInit[stamp] = tinymce.extend( mceinitConfig, false, tinyMCEPreInit.mceInit[stamp] );

                                if(typeof redux.fields.editor == 'undefined'){
                                    redux.fields.editor = {};
                                }

                                redux.fields.editor[stamp] = stamp;

                                tinyMCEPreInit.qtInit[stamp].id = stamp;
                                quicktags( qtinit );
                                QTags._buttonsInit();
                                tinyMCEPreInit.mceInit[stamp].selector = '#'+stamp;

                                window.switchEditors.go(stamp, 'tmce');
                            });
                        }
                        //Switch
                        if(container.find('.redux-container-switch').length){
                            redux.field_objects.switch.init();
                        }

                        for (var i = 0; i < fieldsToReInit.length; i++) {
                            var reInit = fieldsToReInit[i];
                            if(container.find('.redux-container-' + reInit ).length){
                                container.find('.redux-container-' + reInit ).each(function(index, el) {
                                    redux.field_objects[reInit].init();
                                });
                            }
                        };

                        functions.compareInit();
                        if(_this.hasClass('accordion'))
                            container.find('.row-content').hide();
                    },

                    init: function(container) {
                        if(_this.hasClass('accordion'))
                            container.find('.row-content').show();

                        //Editor
                        if(container.find('textarea.wp-editor-area').length){
                            if(typeof redux.fields.editor == 'undefined'){
                                redux.fields.editor = {};
                            }
                            container.find('textarea.wp-editor-area').each(function(index, el) {
                                tinymce.EditorManager.createEditor(true, $(el).prop('id'));

                                redux.fields.editor[$(el).prop('id')] = $(el).prop('id');
                            });
                        }

                        //Switch
                        if(container.find('.redux-container-switch').length){
                            redux.field_objects.switch.init();
                        }

                        for (var i = 0; i < fieldsToReInit.length; i++) {
                            var reInit = fieldsToReInit[i];
                            if(container.find('.redux-container-' + reInit ).length){
                                container.find('.redux-container-' + reInit ).each(function(index, el) {
                                    redux.field_objects[reInit].init();
                                });
                            }
                        };

                        functions.compareInit();
                        functions.compareRequired();

                        _this.find('.repeatable-content')
                        .sortable(
                            {
                                axis: "y",
                                handle: ".sortable-key",
                                items: '.repeatable-row',
                                connectWith: ".repeatable-row",
                                placeholder: "ui-state-highlight",
                                start: function( e, ui ) {
                                    ui.placeholder.height( ui.item.height() );
                                    ui.placeholder.width( ui.item.width() );
                                },
                                stop: function( event, ui ) {
                                    // IE doesn't register the blur when sorting
                                    // so trigger focusout handlers to remove .ui-state-focus
                                    ui.item.children( ".sortable-key" ).triggerHandler( "focusout" );
                                    var inputs = $( 'input.ll-row-sort' );
                                    inputs.each(
                                        function( idx ) {
                                            $( this ).val( idx );
                                        }
                                    );
                                }
                            }
                        );

                        if(_this.hasClass('accordion'))
                            container.find('.row-content').hide();
                    },

                    compareRequired: function() {
                        if(required_fields.length){
                            required_fields.each(function(index, field) {
                                field = $(field);
                                var compare = base64_decode( field.data('required'));
                                if(compare.isJSON()){
                                    compare = JSON.parse(compare);
                                    var parent = _this.find('#' + compare.required + '-' + compare.index);
                                    
                                    if(parent.length){
                                        if($.redux.check_dependencies_visibility(parent.val(), compare)){
                                            field.removeClass('hide');
                                        } else {
                                            field.addClass('hide');
                                            // parent.closest('.ll-field-container').find('select, radio, input, textarea').each(function(index, el) {
                                                // $(el).prop('disabled', true);
                                            // });
                                        }
                                    }
                                }
                            });
                        }
                    },

                    compareInit: function() {
                        required_fields = _this.find('.ll-field-container.required-field');
                    }

                };

                functions.init(_this);

                if(!fixed){
                    _this.find('.repeatable-controls .add-button').off('click');
                    _this.find('.repeatable-controls .add-button').on('click', function(event) {
                        event.preventDefault();
                        functions.add($(this));
                    });
                }

                $( 'body' ).off('check_dependencies', functions.compareRequired);
                $( 'body' ).on('check_dependencies', functions.compareRequired);

                $(document).off( 'click', '.repeatable-content .delete-button', functions.delete_row);
                $(document).on( 'click', '.repeatable-content .delete-button', functions.delete_row);
            }
        );


        $(document).off('click','.repeatable-row .row-title');
        $(document).on(
            'click',
            '.repeatable-row .row-title',
            function(event) {
                event.preventDefault();
                
                var content = $(this).find('.row-toggle').toggleClass('opened').closest('.repeatable-row').find('.row-content').toggle();
            }
        );
    };

    function base64_decode( data ) {    // Decodes data encoded with MIME base64

        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

        do {  // unpack four hexets into three octets using index points in b64
            h1 = b64.indexOf(data.charAt(i++));
            h2 = b64.indexOf(data.charAt(i++));
            h3 = b64.indexOf(data.charAt(i++));
            h4 = b64.indexOf(data.charAt(i++));

            bits = h1<<18 | h2<<12 | h3<<6 | h4;

            o1 = bits>>16 & 0xff;
            o2 = bits>>8 & 0xff;
            o3 = bits & 0xff;

            if (h3 == 64)     enc += String.fromCharCode(o1);
            else if (h4 == 64) enc += String.fromCharCode(o1, o2);
            else               enc += String.fromCharCode(o1, o2, o3);
        } while (i < data.length);

        return enc;
    }
    function base64_encode(data) {
        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = '',
        tmp_arr = [];

        if (!data) {
        return data;
        }

        do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);

        enc = tmp_arr.join('');

        var r = data.length % 3;

        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    }

    String.prototype.isJSON = function() {
        try {
            JSON.parse(this);
        } catch (e) {
            return false;
        }
        return true;
    };

})( jQuery );
