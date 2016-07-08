jQuery( document ).ready( function ( $ ) {



    /*if ( $( '#cp-stop-details-holder' ).length ) {

     setInterval( function() {
     cp_submit_stop_data();
     }, 10000 );

     $( '.stop-page-holder .modules_accordion input, .stop-page-holder .modules_accordion select' ).live( 'change',
     function() {
     var par = $( this ).parent().closest( '.module-content' );
     var par_id = $( par ).attr( 'id' );
     $( '#' + par_id + ' input, #' + par_id + ' select, #' + par_id + ' textarea' ).each( function() {
     var $t = $( this );
     $t.attr( {
     name: $t.attr( 'noname' ),
     } )
     .removeAttr( 'noname' );
     } );
     }
     );

     }*/

    /*function cp_submit_stop_data() {
     var data = $( '#stop-add' ).serialize();
     $.post( fieldpress_stops.admin_ajax_url, data );

     $( '.stop-page-holder .modules_accordion input, .stop-page-holder .modules_accordion select' ).each( function() {
     var $t = $( this );
     $t.attr( {
     noname: $t.attr( 'name' ),
     } )
     .removeAttr( 'name' );
     } );

     }*/

    $( ".grade_spinner" ).spinner( {
        min: 1,
        max: 100,
        step: 1,
        start: 100,
        //numberFormat: "C"
    } );

    /*$( ".attempts_spinner" ).spinner({
     min: 1,
     max: 100,
     step: 1,
     start: 1,
     //numberFormat: "C"
     });*/

    $( document.body ).on( 'change', '.assessable_checkbox', function () {
        var checked = $( this ).prop( 'checked' );
        var second_group = $( this ).parent().parent().parent().find( '.second-group-check' );
        if ( checked ) {
            second_group.show();
        } else {
            second_group.hide();
        }
    } );


    jQuery( document.body ).on( 'click', '.modules_accordion .module-holder-title, #stop-pages .ui-tabs-nav li, .save-stop-button', function () {
        var current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        var active_element = jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "option", "active" );
        jQuery( '#active_element' ).val( active_element );
    } );

    jQuery( document.body ).on( 'input', '#add_student_class', function () {
        return false;
    } );

    jQuery( document.body ).on( 'input', '.element_title', function () {
        jQuery( this ).parent().parent().find( '.h3-label-left' ).html( jQuery( this ).val() );
    } );

    jQuery( document.body ).on( 'input', '#stop_name', function () {
        jQuery( '.mp-wrap .mp-tab.active a' ).html( jQuery( this ).val() );
    } );

    function submit_elements() {


        jQuery( "input[name*='radio_input_module_radio_check']:checked" ).each( function () {
            var vl = jQuery( this ).parent().find( '.radio_answer' ).val();
            jQuery( this ).closest( ".module-content" ).find( '.checked_index' ).val( vl );
        } );

        jQuery( "input[name*='text_input_module_answer_length']:checked" ).each( function () {
            jQuery( this ).closest( ".module-content" ).find( '.checked_index' ).val( jQuery( this ).val() );
        } );

        jQuery( "input[name*='audio_module_loop']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "audio_module_loop[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + ']' );
        } );

        jQuery( "input[name*='audio_module_autoplay']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "audio_module_autoplay[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + ']' );
        } );

        jQuery( "input[name*='radio_answers']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "radio_input_module_radio_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );


        jQuery( "input[name*='radio_check']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "radio_input_module_radio_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        jQuery( "#stop-add" ).submit();
    }

    jQuery( ".stop-control-buttons .save-stop-button" ).click( function () {
        $( '.wp-switch-editor.switch-tmce' ).click();

        var stop_page_num = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        jQuery( "#stop_page_num" ).val( stop_page_num );

        var stop_pages = jQuery( "#stop-pages .ui-tabs-nav li" ).size() - 2;

        var page_break_to_delete_id = jQuery( "#stop-page-1 .module-holder-page_break_module .element_id" ).val();
        //alert(page_break_to_delete_id);
        if ( !isNaN( parseFloat( page_break_to_delete_id ) ) && isFinite( page_break_to_delete_id ) ) {
            prepare_module_for_execution( page_break_to_delete_id );
        } else {
            jQuery( "#stop-page-1 .module-holder-page_break_module" ).remove();
        }
        //jQuery('#stop-add').attr('action', jQuery('#stop-add').attr('action') + "&stop_page_num=" + stop_page_num);

        submit_elements();
    } );

    jQuery( ".stop-control-buttons .button-publish" ).click( function () {
        //submit_elements();
    } );
} );

function delete_class_confirmed() {
    return confirm( fieldpress_stops.delete_class );
}

function deleteClass() {
    if ( delete_class_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function withdraw_all_from_class_confirmed() {
    return confirm( fieldpress_stops.withdraw_class_alert );
}

function withdrawAllFromClass() {
    if ( withdraw_all_from_class_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

jQuery( function () {

    jQuery( "#sortable-stops" ).sortable( {
        placeholder: "ui-state-highlight",
        items: "li:not(.static)",
        stop: function ( event, ui ) {
            update_sortable_indexes();
        }
    } );

    jQuery( "#sortable-stops" ).disableSelection();


    var current_stop_page = 0;//current selected stop page

    current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();

    jQuery( "#stop-page-" + current_stop_page + " .stop-module-list" ).change( function () {
        jQuery( "#stop-page-" + current_stop_page + " .module_description" ).html( jQuery( this ).find( ':selected' ).data( 'module-description' ) );
    } );

    jQuery( "#stop-page-" + current_stop_page + " .module_description" ).html( jQuery( this ).find( ':selected' ).data( 'module-description' ) );

} );

function update_sortable_indexes() {
    jQuery( '.numberCircle' ).each( function ( i, obj ) {
        jQuery( this ).html( i + 1 );
    } );

    jQuery( '.stop_order' ).each( function ( i, obj ) {
        jQuery( this ).val( i + 1 );
    } );

    var positions = new Array();

    jQuery( '.stop_id' ).each( function ( i, obj ) {
        positions[ i ] = jQuery( this ).val();
    } );

    var data = {
        action: 'update_stops_positions',
        positions: positions.toString()
    };

    jQuery.post( ajaxurl, data, function ( response ) {
        //alert(response);
    } );

}

/* Native WP media browser for audio module (stop module) */

jQuery( document ).ready( function () {

    jQuery( document.body ).on( 'click', '.remove_module_link', function () {
        var current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        var accordion_elements_count = ( jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion div.module-holder-title' ).length );//.modules_accordion').find('.modules_accordion div.module-holder-title').length);

        //alert('Current page: '+current_stop_page+', elements count: '+accordion_elements_count);

        if ( fieldpress_stops.stop_pagination == 0 ) {
            if ( ( current_stop_page == 1 && accordion_elements_count == 0 ) || ( current_stop_page >= 2 && accordion_elements_count == 1 ) ) {
                jQuery( '#stop-page-' + current_stop_page + ' .elements-holder .no-elements' ).show();
            } else {
                jQuery( '#stop-page-' + current_stop_page + ' .elements-holder .no-elements' ).hide();
            }
        } else {
            if ( accordion_elements_count == 0 ) {
                jQuery( '#stop-page-' + current_stop_page + ' .elements-holder .no-elements' ).show();
            } else {
                jQuery( '#stop-page-' + current_stop_page + ' .elements-holder .no-elements' ).hide();
            }
        }

    } );

    jQuery( document.body ).on( 'click', '.audio_url_button', function () {
        var target_url_field = jQuery( this ).prevAll( ".audio_url:first" );

        wp.media.editor.send.attachment = function ( props, attachment ) {
            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
            jQuery( target_url_field ).val( attachment.url );
        };
        wp.media.editor.open( this );
        return false;
    } );
} );

/* Native WP media browser for video module (stop module) */

jQuery( document ).ready( function ( $ ) {
    // Prevent 'Enter' key from submitting page on stop builder
    jQuery( document ).keypress( function ( event ) {

        if ( event.keyCode == 13 ) {
            var stopBuilder = jQuery( '.stop-detail-settings' );

            if ( typeof ( stopBuilder ) != 'undefined' ) {
                event.preventDefault();
            }
        }

    } );

    jQuery( document.body ).on( 'click', '.video_url_button', function () {
        var target_url_field = jQuery( this ).prevAll( ".video_url:first" );
        var target_id_field = jQuery( this ).prevAll( ".attachment_id:first" );
        var caption_field = jQuery( this ).parents( '.module-content' ).find( '.caption-source .element_title_description' );

        wp.media.string.props = function ( props, attachment ) {

            jQuery( target_url_field ).val( props.url );

            if ( attachment !== undefined ) {
                if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                    $( target_url_field ).removeClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
                } else {//extension is not allowed
                    $( target_url_field ).addClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
                }
            } else {
                jQuery( target_id_field ).val( 0 );
            }

            return props;
        };

        wp.media.editor.send.attachment = function ( props, attachment ) {
            if ( attachment !== undefined ) {
                if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                    $( target_url_field ).removeClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
                } else {//extension is not allowed
                    $( target_url_field ).addClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
                }

                jQuery( target_url_field ).val( attachment.url );
                jQuery( target_id_field ).val( attachment.id );
                jQuery( target_url_field ).parents( '.module-holder-title' ).find( '.media-caption-description' ).html( '"' + attachment.caption + '"' );
            }
        };

        wp.media.editor.open( this );
        return false;
    } );

    jQuery( document.body ).on( 'click', '.field_video_url_button', function () {
        var target_url_field = jQuery( this ).prevAll( ".field_video_url:first" );

        wp.media.string.props = function ( props, attachment ) {
            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
            jQuery( target_url_field ).val( props.url );
        }

        wp.media.editor.send.attachment = function ( props, attachment ) {
            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
            jQuery( target_url_field ).val( attachment.url );
        };

        wp.media.editor.open( this );
        return false;
    } );

} );

/* Native WP media browser for file module (for instructors) */

jQuery( document ).ready( function () {
    jQuery( document.body ).on( 'click', '.file_url_button', function () {

        var target_url_field = jQuery( this ).prevAll( ".file_url:first" );
        wp.media.editor.send.attachment = function ( props, attachment ) {
            jQuery( target_url_field ).val( attachment.url );
        };
        wp.media.editor.open( this );
        return false;
    } );

    jQuery( document.body ).on( 'click', '.image_url_button', function () {

        var target_url_field = jQuery( this ).prevAll( ".image_url:first" );
        var target_id_field = jQuery( this ).prevAll( ".attachment_id:first" );
        var caption_field = jQuery( this ).parents( '.module-content' ).find( '.caption-source .element_title_description' );

        wp.media.string.props = function ( props, attachment ) {

            jQuery( target_url_field ).val( props.url );


            if ( attachment !== undefined ) {
                if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                    $( target_url_field ).removeClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
                } else {//extension is not allowed
                    $( target_url_field ).addClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
                }
            } else {
                jQuery( target_id_field ).val( 0 );
            }

            return props;
        };

        wp.media.editor.send.attachment = function ( props, attachment ) {

            if ( attachment !== undefined ) {
                // console.log( attachment );
                if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                    $( target_url_field ).removeClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
                } else {//extension is not allowed
                    $( target_url_field ).addClass( 'invalid_extension_field' );
                    $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
                }
                jQuery( target_url_field ).val( attachment.url );
                jQuery( target_id_field ).val( attachment.id );
                jQuery( caption_field ).html( '"' + attachment.caption + '"' );
            }

        };

        wp.media.editor.open( this );
        return false;
    } );
} );


jQuery( document ).ready( function () {
    jQuery( document.body ).on( 'click', '.insert-media-cp', function () {

        var rand_id = jQuery( this ).attr( "data-editor" );

        wp.media.editor.send.attachment = function ( props, attachment ) {
            tinyMCE.execCommand( 'mceFocus', false, rand_id );
            var ed = tinyMCE.get( rand_id );
            var range = ed.selection.getRng();
            var image = ed.getDoc().createElement( "img" );

            var image_width = eval( 'attachment.sizes' + '.' + props.size + '.' + 'width' );
            var image_height = eval( 'attachment.sizes' + '.' + props.size + '.' + 'height' );

            image.setAttribute( 'class', 'align' + props.align + ' size-' + props.size + ' wp-image-' + rand_id );
            image.src = attachment.url;
            image.alt = attachment.alt;
            image.width = image_width;
            image.height = image_height;
            range.insertNode( image );
        };

        wp.media.editor.open( this );

        return false;
    } );

    //tinyMCE.activeEditor.selection.moveToBookmark(bm);
} );


jQuery.urlParam = function ( name ) {
    var results = new RegExp( '[\?&]' + name + '=([^&#]*)' ).exec( window.location.href );
    if ( results == null ) {
        return null;
    }
    else {
        return results[ 1 ] || 0;
    }
}

// Detect key changes in the wp_editor
var active_editor;
function cp_editor_key_down( ed, page, tab ) {
    $ = jQuery;

    var fieldDetailsPages = [
        'fieldpress_page_field_details',
        'fieldpress-pro_page_field_details'
    ];

    if ( $.inArray( page, fieldDetailsPages ) ) {
        if ( tab == '' || tab == 'overview' ) {

            // Mark as dirty when wp_editor content changes on 'Field Trip Setup' page.
            $( '#' + ed.id ).parents( '.field-section' ).addClass( 'dirty' );
            if ( $( $( '#' + ed.id ).parents( '.field-section.step' ).find( '.status.saved' )[ 0 ] ).hasClass( 'saved' ) ) {
                $( '#' + ed.id ).parents( '.field-section.step' ).find( 'input.button.update' ).css( 'display', 'inline-block' );
            }

            active_editor = ed.id;

        }
    }

}

// Detect mouse movement in the wp_editor
function cp_editor_mouse_move( ed, event ) {
}


function set_update_progress( step, value ) {

    $ = jQuery;
    $( 'input[name="meta_field_setup_progress[' + step + ']"]' ).val( value );

}

function get_meta_field_setup_progress() {
    var meta_field_setup_progress = {
        'step-1': $( 'input[name="meta_field_setup_progress[step-1]"]' ).val(),
        'step-2': $( 'input[name="meta_field_setup_progress[step-2]"]' ).val(),
        'step-3': $( 'input[name="meta_field_setup_progress[step-3]"]' ).val(),
        'step-4': $( 'input[name="meta_field_setup_progress[step-4]"]' ).val(),
        'step-5': $( 'input[name="meta_field_setup_progress[step-5]"]' ).val(),
        'step-6': $( 'input[name="meta_field_setup_progress[step-6]"]' ).val(),
    }

    return meta_field_setup_progress;
}

function autosave_field_setup_done( data, status, step, statusElement, nextAction ) {
    if ( typeof ( nextAction ) === 'undefined' )
        nextAction = false;
    if ( status == 'success' ) {

        var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
        // console.log(response);
        // Apply a new nonce when returning
        console.log( response );
        if ( response && response.success ) {
            $( '#field-ajax-check' ).data( 'nonce', response.nonce );
            $( '#field-ajax-check' ).data( 'id', response.field_id );
            $( '[name=field_id]' ).val( response.field_id );
            if ( response.mp_product_id ) {
                $( '[name=meta_mp_product_id]' ).val( response.mp_product_id );
            }

            var instructor_nonce = $( '#instructor-ajax-check' ).data( 'nonce' );
            var uid = $( '#instructor-ajax-check' ).data( 'uid' );

            // Add user as instructor
            if ( step == 'step-1' && response.instructor ) {
                $.post(
                    fieldpress_stops.admin_ajax_url, {
                        action: 'add_field_instructor',
                        instructor_id: response.instructor,
                        instructor_nonce: instructor_nonce,
                        field_id: response.field_id,
                        user_id: uid,
                    }
                ).done( function ( data, status ) {

                        var instructor_id = response.instructor;
                        var response2 = $.parseJSON( $( data ).find( 'response_data' ).text() );
                        var response_type = $( $.parseHTML( response2.content ) );

                        if ( $( "#instructor_holder_" + instructor_id ).length == 0 && response2.instructor_added ) {
                            $( '.instructor-avatar-holder.empty' ).hide();
                            $( '#instructors-info' ).append( '<div class="instructor-avatar-holder" id="instructor_holder_' + instructor_id + '"><div class="instructor-status"></div><div class="instructor-remove"><a href="javascript:removeInstructor( ' + instructor_id + ' );"><i class="fa fa-times-circle cp-move-icon remove-btn"></i></a></div>' + response2.instructor_gravatar + '<span class="instructor-name">' + response2.instructor_name + '</span></div><input type="hidden" id="instructor_' + instructor_id + '" name="instructor[]" value="' + instructor_id + '" />' );
                        }

                        //window.location = $('form#field-add').attr('action')  + '&field_id=' + response.field_id;

                    } );
                // return;
            }

            // Else, toggle back.	
        } else {
            $( statusElement ).removeClass( 'progress' );
            $( statusElement ).addClass( 'invalid' );
            set_update_progress( step, 'invalid' );
            return;
        }

        $( $( '.' + step + '.dirty' )[ 0 ] ).removeClass( 'dirty' )
        $( statusElement ).removeClass( 'progress' );

        var is_paid = $( '[name=meta_paid_field]' ).is( ':checked' ) ? true : false;
        var has_gateway = $( $( '.step-6 .field-enable-gateways' )[ 0 ] ).hasClass( 'gateway-active' );

        // Different logic required here for last step
        if ( step == 'step-6' ) {
            // Paid product
            if ( is_paid ) {
                // Gateway is setup and next action is set
                if ( has_gateway && 'stop_setup' == nextAction ) {
                    $field_id = $( '[name=field_id]' ).val();
                    $admin_url = $( '[name=admin_url]' ).val();
                    window.location = $admin_url + '&tab=stops&field_id=' + $field_id;
                    // Gateway is set, but we forgot to tell the 'done' button what to do
                } else if ( has_gateway ) {
                    $( statusElement ).addClass( 'saved' );
                    set_update_progress( step, 'saved' );
                    // Gateway is not set	
                } else {
                    // alert(fieldpress_stops.setup_gateway);
                    $( statusElement ).addClass( 'attention' );
                    set_update_progress( step, 'attention' );
                }
            } else {
                $( statusElement ).addClass( 'saved' );
                set_update_progress( step, 'saved' );
                $field_id = $( '[name=field_id]' ).val();
                $admin_url = $( '[name=admin_url]' ).val();

                if ( 'stop_setup' == nextAction ) {
                    window.location = $admin_url + '&tab=stops&field_id=' + $field_id;
                }

            }
            // Steps 1 - 5	
        } else {
            $( statusElement ).addClass( 'saved' );

            var buttons = $( statusElement ).parents( '.field-section' ).find( '.field-form .field-step-buttons' )[ 0 ];

            $( '#step-done-message' ).remove();
            $( buttons ).append( '<span id="step-done-message">&nbsp;<i class="fa fa-check"></i></span>' );
            // Popup Message
            $( '#step-done-message' ).show( function () {
                $( this ).fadeOut( 1000 );
            } );

        }

        $( '.field-section.step input.button.update' ).css( 'display', 'none' );
    } else {
        $( statusElement ).removeClass( 'progress' );
        $( statusElement ).addClass( 'invalid' );
        set_update_progress( step, 'invalid' );
    }
}

/** Prepare AJAX post vars */
function step_1_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];
    var tmce = initialVars[ 'tmce' ];

    var content = '';

    var setting = typeof getUserSetting !== 'undefined' ? getUserSetting( 'editor' ) : 'html';
    if ( tinyMCE && tinyMCE.get( 'field_excerpt' ) && 'html' !== setting ) {
        content = tinyMCE.get( 'field_excerpt' ).getContent();
    } else {
        content = $( '[name=field_excerpt]' ).val();
    }

    var _thumbnail_id = '';
    if ( $( '[name=_thumbnail_id]' ) ) {
        _thumbnail_id = $( '[name=_thumbnail_id]' ).val()
    }

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        field_excerpt: content,
        meta_featured_url: $( '[name=meta_featured_url]' ).val(),
        _thumbnail_id: _thumbnail_id,
        //meta_field_category: $('[name=meta_field_category]').val(),
        meta_field_language: $( '[name=meta_field_language]' ).val(),
        field_category: $( '[name=meta_field_category]' ).val(),
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: 'step-2',
    }
}

function step_2_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];
    var tmce = initialVars[ 'tmce' ];

    var content = '';
    if ( tmce && tinyMCE.get( 'field_description' ) ) {
        if (jQuery("#wp-field_description-wrap").hasClass("tmce-active")){
            content = tinyMCE.get( 'field_description' ).getContent();
        }else{
            content = $( '[name=field_description]' ).val();
        }
    } else {
        content = $( '[name=field_description]' ).val();
    }

    //var show_boxes = {};
    //var preview_boxes = {};

    var show_stop_boxes = {};
    var preview_stop_boxes = {};

    var show_page_boxes = {};
    var preview_page_boxes = {};

    $( "input[name^=meta_show_stop]" ).each( function () {
        var stop_id = $( this ).attr( 'data-id' );

        show_stop_boxes[ stop_id ] = $( sanitize_checkbox( $( "input[name=meta_show_stop\\[" + stop_id + "\\]]" ) ) ).val();
        preview_stop_boxes[ stop_id ] = $( sanitize_checkbox( $( "input[name=meta_preview_stop\\[" + stop_id + "\\]]" ) ) ).val();

    } );

    $( "input[name^=meta_show_page]" ).each( function () {
        var page_id = $( this ).attr( 'data-id' );

        show_page_boxes[ page_id ] = $( sanitize_checkbox( $( "input[name=meta_show_page\\[" + page_id + "\\]]" ) ) ).val();
        preview_page_boxes[ page_id ] = $( sanitize_checkbox( $( "input[name=meta_preview_page\\[" + page_id + "\\]]" ) ) ).val();

    } );

    /*

     $("input[name^=module_element]").each(function() {
     var mod_id = $(this).val();

     show_boxes[ mod_id ] = $(sanitize_checkbox($("input[name=meta_show_module\\[" + mod_id + "\\]]"))).val();
     preview_boxes[ mod_id ] = $(sanitize_checkbox($("input[name=meta_preview_module\\[" + mod_id + "\\]]"))).val();

     });
     */

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        meta_field_video_url: $( '[name=meta_field_video_url]' ).val(),
        field_description: content,
        meta_field_stop_options: $( '[name=meta_field_stop_options]' ).is( ':checked' ) ? 'on' : 'off',
        meta_field_stop_time_display: $( '[name=meta_field_stop_time_display]' ).is( ':checked' ) ? 'on' : 'off',
        meta_show_stop_boxes: show_stop_boxes,
        meta_preview_stop_boxes: preview_stop_boxes,
        meta_show_page_boxes: show_page_boxes,
        meta_preview_page_boxes: preview_page_boxes,
        //meta_show_module: show_boxes,
        //meta_preview_module: preview_boxes,
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: 'step-3',
    }
}

function step_3_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];

    var instructors = $( "input[name^=instructor]" ).map( function () {
        return $( this ).val();
    } ).get();
    if ( $( instructors ).length == 0 ) {
        instructors = 0;
    }

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        instructor: instructors,
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: 'step-4',
    }
}

function step_4_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        meta_open_ended_field: $( '[name=meta_open_ended_field]' ).is( ':checked' ) ? 'on' : 'off',
        meta_field_start_date: $( '[name=meta_field_start_date]' ).val(),
        meta_field_end_date: $( '[name=meta_field_end_date]' ).val(),
        meta_field_start_time: $( '[name=meta_field_start_time]' ).val(),
        meta_field_end_time: $( '[name=meta_field_end_time]' ).val(),
        meta_open_ended_enrollment: $( '[name=meta_open_ended_enrollment]' ).is( ':checked' ) ? 'on' : 'off',
        meta_enrollment_start_date: $( '[name=meta_enrollment_start_date]' ).val(),
        meta_enrollment_end_date: $( '[name=meta_enrollment_end_date]' ).val(),
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: 'step-5',
    }
}

function step_5_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        meta_limit_class_size: $( '[name=meta_limit_class_size]' ).is( ':checked' ) ? 'on' : 'off',
        meta_class_size: $( '[name=meta_class_size]' ).val(),
        meta_allow_field_discussion: $( '[name=meta_allow_field_discussion]' ).is( ':checked' ) ? 'on' : 'off',
        meta_allow_workbook_page: $( '[name=meta_allow_workbook_page]' ).is( ':checked' ) ? 'on' : 'off',
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: 'step-6',
    }
}

function step_6_update( attr ) {
    var theStatus = attr[ 'status' ];
    var initialVars = attr[ 'initialVars' ];

    var passcode_val = false;
    var prerequisite_val = false;

    switch ( $( '[name=meta_enroll_type]' ).val() ) {
        case 'passcode':
            passcode_val = $( '[name=meta_passcode]' ).val();
            break;
        case 'prerequisite':
            prerequisite_val = $( '[name=meta_prerequisite]' ).val();
            break;
    }

    return {
        // Don't remove
        action: initialVars[ 'action' ],
        field_id: initialVars[ 'field_id' ],
        field_name: initialVars[ 'field_name' ],
        field_nonce: initialVars[ 'field_nonce' ],
        user_id: initialVars[ 'user_id' ],
        // Alter as required
        meta_enroll_type: $( '[name=meta_enroll_type]' ).val(),
        meta_prerequisite: prerequisite_val,
        meta_passcode: passcode_val,
        meta_paid_field: $( '[name=meta_paid_field]' ).is( ':checked' ) ? 'on' : 'off',
        meta_auto_sku: $( '[name=meta_auto_sku]' ).is( ':checked' ) ? 'on' : 'off',
        mp_sku: $( '[name=mp_sku]' ).val(),
        mp_is_sale: $( '[name=mp_is_sale]' ).is( ':checked' ) ? '1' : '0',
        mp_price: $( '[name=mp_price]' ).val(),
        mp_sale_price: $( '[name=mp_sale_price]' ).val(),
        meta_mp_product_id: $( '[name=meta_mp_product_id]' ).val(),
        //meta_allow_workbook_page: $('[name=meta_allow_workbook_page]').is(':checked') ? 'on' : 'off',
        // Don't remove
        meta_field_setup_progress: initialVars[ 'meta_field_setup_progress' ],
        meta_field_setup_marker: initialVars[ 'meta_field_setup_marker' ],
    }
}

function clearFieldErrorMessages() {
    $( 'span.error' ).remove();
}

function validateFieldFields( step, ignore ) {
    var valid = true;

    if ( typeof ( ignore ) === 'undefined' ) {
        ignore = false;
    }

    var tmce = true;
    if ( typeof ( tinyMCE ) === 'undefined' ) {
        tmce = false;
    }

    $ = jQuery;

    clearFieldErrorMessages();

    switch ( step ) {

        case 1:
        case '1':
            if ( $( '[name=field_name]' ).val() == "" ) {
                $( '[for=field_name]' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_name + '</span>' );
                valid = false;
            }

            var content = '';
            if ( tmce && tinyMCE.get( 'field_excerpt' ) ) {
                content = tinyMCE.get( 'field_excerpt' ).getContent();
            } else {
                content = $( '[name=field_excerpt]' ).val();
            }

            break;

        case 2:
        case '2':
            var content = '';
            if ( tmce && tinyMCE.get( 'field_description' ) ) {
                if (jQuery("#wp-field_description-wrap").hasClass("tmce-active")){
                    content = tinyMCE.get( 'field_description' ).getContent();
                }else{
                    content = $( '[name=field_description]' ).val();
                }
            } else {
                content = $( '[name=field_description]' ).val();
            }

            if ( content == '' ) {
                $( '[for=field_description]' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_description + '</span>' );
                valid = false;
            }
            break;

        case 3:
        case '3':
            break;

        case 4:
        case '4':

            if ( $( '[name=meta_field_start_date]' ).val() == "" ) {
                $( '[name=meta_field_start_date]' ).parents( '.date-range' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_start + '<br /></span>' );
                valid = false;
            }

            if ( !$( '[name=meta_open_ended_field]' ).is( ':checked' ) ) {
                if ( $( '[name=meta_field_end_date]' ).val() == "" ) {
                    $( '[name=meta_field_end_date]' ).parents( '.date-range' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_end + '</span>' );
                    valid = false;
                }
            }

            if ( !$( '[name=meta_open_ended_enrollment]' ).is( ':checked' ) ) {
                if ( $( '[name=meta_enrollment_start_date]' ).val() == "" ) {
                    $( '[name=meta_enrollment_start_date]' ).parents( '.date-range' ).parent().append( '<span class="error">' + fieldpress_stops.required_enrollment_start + '<br /></span>' );
                    valid = false;
                }
                if ( $( '[name=meta_enrollment_end_date]' ).val() == "" ) {
                    $( '[name=meta_enrollment_end_date]' ).parents( '.date-range' ).parent().append( '<span class="error">' + fieldpress_stops.required_enrollment_end + '<br /></span>' );
                    valid = false;
                }
            }

            break;

        case 5:
        case '5':
            if ( $( '[name=meta_limit_class_size]' ).is( ':checked' ) ) {
                if ( $( '[name=meta_class_size]' ).val() == "" || $( '[name=meta_class_size]' ).val() == "0" || $( '[name=meta_class_size]' ).val() == 0 ) {
                    $( '[for=meta_class-size]' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_class_size + '</span>' );
                    valid = false;
                }
            }

            break;

        case 6:
        case '6':

            var has_gateway = $( $( '.step-6 .field-enable-gateways' )[ 0 ] ).hasClass( 'gateway-active' );

            if ( $( '[name=meta_enroll_type]' ).val() == 'passcode' ) {
                if ( $( '[name=meta_passcode]' ).val() == "" ) {
                    $( '[for=meta_enroll_type]' ).parent().append( '<span class="error">' + fieldpress_stops.required_field_passcode + '</span>' );
                    valid = false;
                }
            }

            if ( $( '[name=meta_paid_field]' ).is( ':checked' ) ) {

                if ( $( '[name=mp_price]' ).val() == "" ) {
                    $( '[name=mp_price]' ).parents( '.field-price' ).append( '<span class="error">' + fieldpress_stops.required_price + '</span>' );
                    valid = false;
                }

                if ( !has_gateway ) {
                    $( '.field-enable-gateways' ).append( '<div><span class="error">' + fieldpress_stops.required_gateway + '</span></div>' );
                    valid = false;
                }

                if ( $( '[name=mp_is_sale]' ).is( ':checked' ) ) {
                    if ( $( '[name=mp_sale_price]' ).val() == "" ) {
                        $( '.field-sale-price' ).append( '<span class="error">' + fieldpress_stops.required_sale_price + '</span>' );
                        valid = false;
                    }
                }
            }

            break;

    }


    if ( !valid && !ignore ) {
        alert( fieldpress_stops.section_error );
    }

    if ( ignore ) {
        return true;
    }

    return valid;
}

function fieldAutoUpdate( step, nextAction ) {
    if ( typeof ( nextAction ) === 'undefined' )
        nextAction = false
    $ = jQuery;

    var tmce = true;
    if ( typeof ( tinyMCE ) === 'undefined' ) {
        tmce = false;
    }

    clearFieldErrorMessages();

    var theStatus = $( $( '.field-section.step-' + step + ' .field-section-title h3' )[ 0 ] ).siblings( '.status' )[ 0 ];

    var statusNice = '';
    if ( $( theStatus ).hasClass( 'saved' ) ) {
        statusNice = 'saved';
    }
    if ( $( theStatus ).hasClass( 'invalid' ) ) {
        statusNice = 'invalid';
    }
    if ( $( theStatus ).hasClass( 'attention' ) ) {
        statusNice = 'attention';
    }
    $( theStatus ).removeClass( 'saved' );
    $( theStatus ).removeClass( 'invalid' );
    $( theStatus ).removeClass( 'attention' );

    var dirty = $( '.step-' + step + '.dirty' )[ 0 ];
    // Step 5 doesn't have anything that MUST be set, so override.
    if ( !dirty && step == 5 ) {
        dirty = true;
    }

    if ( dirty || nextAction == 'stop_setup' ) {
        $( theStatus ).addClass( 'progress' );

        // Field ID
        var field_id = $( '[name=field_id]' ).val();
        if ( !field_id ) {
            field_id = $.urlParam( 'field_id' );
            $( '[name=field_id]' ).val( field_id );
        }

        // Setup field progress markers and statuses
        set_update_progress( 'step-' + step, 'saved' );
        var meta_field_setup_progress = get_meta_field_setup_progress();

        var field_nonce = $( '#field-ajax-check' ).data( 'nonce' );
        var uid = $( '#field-ajax-check' ).data( 'uid' );

        var initial_vars = {
            action: 'autoupdate_field_settings',
            field_id: field_id,
            field_name: $( '[name=field_name]' ).val(),
            field_nonce: field_nonce,
            user_id: uid,
            meta_field_setup_progress: meta_field_setup_progress,
            meta_field_setup_marker: 'step-' + step,
            tmce: tmce
        }
        // console.log( initial_vars );
        var func = 'step_' + step + '_update';
        // Get the AJAX post vars from step_[x]_update();
        var post_vars = window[ func ]( { status: theStatus, initialVars: initial_vars } );

        // AJAX CALL
        $.post(
            fieldpress_stops.admin_ajax_url, post_vars
        ).done( function ( data, status ) {
                // Handle return
                autosave_field_setup_done( data, status, 'step-' + step, theStatus, nextAction );
            } ).fail( function ( data ) {
            } );

    } else {
        $( theStatus ).addClass( statusNice );
    }
}

function sanitize_checkbox( checkbox ) {
    $ = jQuery;

    if ( $( checkbox ).attr( 'type' ) == 'checkbox' ) {
        if ( $( checkbox ).attr( 'checked' ) ) {
            $( checkbox ).val( 'on' );
        } else {
            $( checkbox ).val( 'off' );
        }
    }

    return checkbox;
}

function mark_dirty( element ) {
    $ = jQuery;

    // Mark as dirty
    var parent_section = $( element ).parents( '.field-section.step' )[ 0 ];
    if ( parent_section ) {
        if ( !$( parent_section ).hasClass( 'dirty' ) ) {
            $( parent_section ).addClass( 'dirty' );
        }
    }

    if ( $( $( element ).parents( '.field-section.step' ).find( '.field-section-title .status' )[ 0 ] ).hasClass( 'saved' ) ) {
        $( element ).parents( '.field-section.step' ).find( 'input.button.update' ).css( 'display', 'inline-block' );
    }


}

function section_touched( element ) {
    $ = jQuery;

    var parent_section = $( element ).parents( '.field-section.step' )[ 0 ];
    if ( parent_section ) {
        if ( !$( parent_section ).hasClass( 'touched' ) ) {
            $( parent_section ).addClass( 'touched' );
        }
    }
}

function is_section_touched( element ) {
    $ = jQuery;

    if ( $( element ).hasClass( 'field-section' ) ) {
        return $( element ).hasClass( 'touched' );
    } else {
        var parent_section = $( element ).parents( '.field-section.step' )[ 0 ];
        return $( parent_section ).hasClass( 'touched' );
    }
}


/** Handle Field Trip Setup Wizard */
jQuery( document ).ready( function ( $ ) {

    var self = this;

    $( window ).bind( 'tb_unload', function ( e ) {
        if ( $( e ).parents( '.step-6' ) ) {
            $( $( e ).parents( '.field-section.step' )[ 0 ] ).addClass( 'dirty' );

            var statusElement = $( $( '.field-section.step-6 .field-section-title h3' )[ 0 ] ).siblings( '.status' )[ 0 ];

            //Does field have an active gateway now?
            $( statusElement ).addClass( 'progress' );
            $.post(
                fieldpress_stops.admin_ajax_url, {
                    action: 'field_has_gateway',
                }
            ).done( function ( data, status ) {
                    if ( status == 'success' ) {
                        var step = 6;
                        var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                        if ( response.has_gateway ) {
                            $( $( '.step-6 .field-enable-gateways' )[ 0 ] ).addClass( 'gateway-active' );
                            $( '.step-6 .button-edit-gateways' ).css( 'display', 'inline-block' );
                            $( '.step-6 .button-incomplete-gateways' ).css( 'display', 'none' );
                            $( statusElement ).removeClass( 'progress' );
                            $( statusElement ).removeClass( 'attention' );
                            $( statusElement ).removeClass( 'invalid' );
                            $( statusElement ).addClass( 'saved' );
                            set_update_progress( step, 'saved' );
                        } else {
                            $( $( '.step-6 .field-enable-gateways' )[ 0 ] ).removeClass( 'gateway-active' );
                            $( '.step-6 .button-edit-gateways' ).css( 'display', 'none' );
                            $( '.step-6 .button-incomplete-gateways' ).css( 'display', 'inline-block' );
                            $( statusElement ).removeClass( 'progress' );
                            $( statusElement ).removeClass( 'saved' );
                            $( statusElement ).removeClass( 'invalid' );
                            $( statusElement ).addClass( 'attention' );
                            set_update_progress( step, 'attention' );
                        }
                        // Update step-6
                        fieldAutoUpdate( 6 );
                    }
                } );
        }
    } );


    /** If a section is not market as saved, automatically mark it as dirty. */
    $.each( $( '.field-section.step' ), function ( index, value ) {
        if ( !$( $( $( '.field-section.step' )[ index ] ).find( '.status' )[ 0 ] ).hasClass( 'saved' ) ) {
            $( $( '.field-section.step' )[ index ] ).addClass( 'dirty' )
        }
    } );

    /** Done Field Trip Setup. */
    $( '.field-section.step input.done' ).click( function ( e ) {
        var step = 6;
        if ( validateFieldFields( step ) ) {
            fieldAutoUpdate( step, 'stop_setup' );
        }
    } );

    /** Inline step update. */
    $( '.field-section.step input.update' ).click( function ( e ) {
        var field_section = $( this ).parents( '.field-section.step' )[ 0 ];
        var step = $( field_section ).attr( 'class' ).match( /step-\d+/ )[ 0 ].replace( /^\D+/g, '' );
        if ( validateFieldFields( step ) ) {
            fieldAutoUpdate( step );
        }
    } );

    /** Proceed to next step. */
    $( '.field-section.step input.next' ).click( function ( e ) {

        /**
         * Get the current step we're on.
         *
         * Looks for <div class="field-section step step-[x]"> and extracts the number.
         **/
        var field_section = $( this ).parents( '.field-section.step' )[ 0 ];
        var step = $( field_section ).attr( 'class' ).match( /step-\d+/ )[ 0 ].replace( /^\D+/g, '' );

        if ( validateFieldFields( step ) ) {

            // Next section
            var nextStep = parseInt( step ) + 1;

            // Attempt to get the next section.
            var nextSection = $( this ).parents( '.field-details .field-section' ).siblings( '.step-' + nextStep )[ 0 ];

            // If next section exists
            if ( nextSection ) {
                // There is a 'next section'. What do you want to do with it?
                var newTop = $( '.step-' + step ).position().top + 130;

                // Jump first, then animate		
                $( document ).scrollTop( newTop );

                $( nextSection ).children( '.field-form' ).slideDown( 500 );
                $( nextSection ).children( '.field-section-title' ).animate( { backgroundColor: '#0091cd' }, 500 );
                $( nextSection ).children( '.field-section-title' ).animate( { color: '#FFFFFF' }, 500 );
                $( this ).parents( '.field-form' ).slideUp( 500 );
                $( this ).parents( '.field-section' ).children( '.field-section-title' ).animate( { backgroundColor: '#F1F1F1' }, 500 );
                $( this ).parents( '.field-section' ).children( '.field-section-title' ).animate( { color: '#222' }, 500 );

                $( nextSection ).addClass( 'active' );
                $( this ).parents( '.field-section' ).removeClass( 'active' );

                /* Time to call some Ajax */
                fieldAutoUpdate( step );

            } else {
                // There is no 'next sections'. Now what?
            }
        }
    } );

    /** Return to previous step. */
    $( '.field-section.step input.prev' ).click( function ( e ) {

        /**
         * Get the current step we're on.
         *
         * Looks for <div class="field-section step step-[x]"> and extracts the number.
         **/
        var step = $( $( this ).parents( '.field-section.step' )[ 0 ] ).attr( 'class' ).match( /step-\d+/ )[ 0 ].replace( /^\D+/g, '' );

        if ( validateFieldFields( step, !is_section_touched( this ) ) ) {
            // Previous section
            var prevStep = parseInt( step ) - 1;

            // Attempt to get the previous section.
            var prevSection = $( this ).parents( '.field-details .field-section' ).siblings( '.step-' + prevStep )[ 0 ];

            // If previous section exists
            if ( prevSection ) {
                // There is a 'previous section'. What do you want to do with it?
                var newTop = $( '.step-' + prevStep ).offset().top - 50;
                $( prevSection ).children( '.field-form' ).slideDown( 500 );
                $( prevSection ).children( '.field-section-title' ).animate( { backgroundColor: '#0091cd' }, 500 );
                $( prevSection ).children( '.field-section-title' ).animate( { color: '#FFFFFF' }, 500 );
                $( this ).parents( '.field-form' ).slideUp( 500 );
                $( this ).parents( '.field-section' ).children( '.field-section-title' ).animate( { backgroundColor: '#F1F1F1' }, 500 );
                $( this ).parents( '.field-section' ).children( '.field-section-title' ).animate( { color: '#222' }, 500 );

                // Animate first then jump
                $( document ).scrollTop( newTop );
                $( prevSection ).addClass( 'active' );
                $( this ).parents( '.field-section' ).removeClass( 'active' );

                /* Time to call some Ajax */
                if ( is_section_touched( this ) ) {
                    fieldAutoUpdate( step );
                }

            } else {
                // There is no 'previous sections'. Now what?
            }
        } // Validate fields
    } );

    $( '.field-section.step .field-section-title h3' ).click( function ( e ) {

        // Get current "active" step
        var activeElement = $( '.field-section.step.active' )[ 0 ];
        var activeStep = 1;
        if ( typeof activeElement != 'undefined' ) {
            activeStep = $( activeElement ).attr( 'class' ).match( /step-\d+/ )[ 0 ].replace( /^\D+/g, '' );
        }

        var thisElement = $( this ).parents( '.field-section.step' )[ 0 ];
        var thisElementFormVisible = $( thisElement ).children( '.field-form' ).is( ':visible' );
        var thisStep = $( thisElement ).attr( 'class' ).match( /step-\d+/ )[ 0 ].replace( /^\D+/g, '' );

        var preceedingElement = $( '.field-section.step-' + ( thisStep - 1 ) )[ 0 ];
        var preceedingStatus = $( preceedingElement ).find( '.status' )[ 0 ];

        var thisStatus = $( this ).siblings( '.status' )[ 0 ];

        // Only move to a saved step or a previous step (asuming that it has to be saved)
        if ( $( thisStatus ).hasClass( 'saved' ) || $( thisStatus ).hasClass( 'attention' ) || thisStep < activeStep || ( thisStep > 1 && $( preceedingStatus ).hasClass( 'saved' ) ) ) {

            // There is a 'previous section'. What do you want to do with it?
            if ( thisStep < activeStep ) {
                var newTop = $( thisElement ).position().top + 130;
            } else if ( thisStep != 1 ) {
                var step = thisStep + 1;
                var newTop = $( thisElement ).prev( '.step' ).offset().top + 20;
            }

            if ( !thisElementFormVisible ) {
                $( thisElement ).children( '.field-form' ).slideDown( 500 );
                $( thisElement ).children( '.field-section-title' ).animate( { backgroundColor: '#0091cd' }, 500 );
                $( thisElement ).children( '.field-section-title' ).animate( { color: '#FFFFFF' }, 500 );
                if ( thisStep != activeStep ) {
                    $( activeElement ).children( '.field-form' ).slideUp( 500 );
                    $( activeElement ).children( '.field-section-title' ).animate( { backgroundColor: '#F1F1F1' }, 500 );
                    $( activeElement ).children( '.field-section-title' ).animate( { color: '#222' }, 500 );
                    // Animate first then jump
                    $( document ).scrollTop( newTop );
                }
            } else {
                $( activeElement ).children( '.field-form' ).slideUp( 500 );
                $( activeElement ).children( '.field-section-title' ).animate( { backgroundColor: '#0091cd' }, 500 );
                $( activeElement ).children( '.field-section-title' ).animate( { color: '#222' }, 500 );
            }

            $( activeElement ).removeClass( 'active' );
            $( thisElement ).addClass( 'active' );

        } else {
            $( $( this ).parent() ).effect( 'shake', { distance: 10 }, 100 );
        }
    } );

    $( '#invite-instructor-trigger' ).click( function () {

        // Field ID
        var field_id = $( '[name=field_id]' ).val();
        if ( !field_id ) {
            field_id = $.urlParam( 'field_id' );
            $( '[name=field_id]' ).val( field_id );
        }

        var instructor_nonce = $( '#instructor-ajax-check' ).data( 'nonce' );
        var uid = $( '#instructor-ajax-check' ).data( 'uid' );

        $.post(
            fieldpress_stops.admin_ajax_url, {
                action: 'send_instructor_invite',
                first_name: $( '[name=invite_instructor_first_name]' ).val(),
                last_name: $( '[name=invite_instructor_last_name]' ).val(),
                email: $( '[name=invite_instructor_email]' ).val(),
                field_id: field_id,
                user_id: uid,
                instructor_nonce: instructor_nonce,
            }
        ).done( function ( data, status ) {
                // Handle return
                if ( status == 'success' ) {
                    // console.log( data );
                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                    // console.log( response )
                    var response_type = $( $.parseHTML( response.content ) );

                    if ( $( response_type ).hasClass( 'status-success' ) ) {

                        var remove_button = '';
                        if ( response.capability ) {
                            remove_button = '<div class="instructor-remove"><a href="javascript:removePendingInstructor(\'' + response.data.code + '\', ' + field_id + ' );"><i class="fa fa-times-circle cp-move-icon remove-btn"></i></a></div>';
                        }

                        var content = '<div class="instructor-avatar-holder pending" id="' + response.data.code + '">' +
                            '<div class="instructor-status">PENDING</div>' +
                            remove_button +
                            '<img class="avatar avatar-80 photo" width="80" height="80" src="//www.gravatar.com/avatar/' + CryptoJS.MD5( response.data.email ) + '" alt="admin">' +
                            '<span class="instructor-name">' + response.data.first_name + ' ' + response.data.last_name + '</span>' +
                            '</div>';

                        $( '#instructors-info' ).append( content );

                        $( '[name=invite_instructor_first_name]' ).val( '' );
                        $( '[name=invite_instructor_last_name]' ).val( '' );
                        $( '[name=invite_instructor_email]' ).val( '' );
                    }

                    if ( $( '#invite-message' ) ) {
                        $( '#invite-message' ).remove()
                    }
                    ;
                    $( 'div.instructor-invite .submit-message' ).append( '<div id="invite-message" style="display:none;">' + response.content + '</div>' )
                    // Popup Message
                    $( '#invite-message' ).show( function () {
                        $( this ).fadeOut( 3000 );
                    } );
                    $( '[name=invite_instructor_first_name]' ).trigger( 'focus' );

                } else {
                }
            } ).fail( function ( data ) {
            } );

    } );


    // Submit Invite on 'Return/Enter' 
    $( '.instructor-invite input' ).keypress( function ( event ) {
        if ( event.which == 13 ) {
            switch ( $( this ).attr( 'name' ) ) {

                case "invite_instructor_first_name":
                    $( '[name=invite_instructor_last_name]' ).trigger( 'focus' );
                    break;
                case "invite_instructor_last_name":
                    $( '[name=invite_instructor_email]' ).trigger( 'focus' );
                    break;
                case "invite_instructor_email":
                case "invite_instructor_trigger":
                    $( '#invite-instructor-trigger' ).trigger( 'click' );
                    break;
            }
            event.preventDefault();
        }
    } );

    $( '.date' ).click( function ( event ) {
        if ( !$( this ).parents( 'div' ).hasClass( 'disabled' ) ) {
            $( this ).find( '.dateinput' ).datepicker( "show" );
        }
    } );

    //$( '.time' ).click( function ( event ) {
    //    if ( !$( this ).parents( 'div' ).hasClass( 'disabled' ) ) {
    //        $( this ).find( '.timeinput' ).timepicker( "show" );
    //    }
    //} );


    $( '.field-section .featured_url_button' ).click( function () {
        // Mark as dirty
        mark_dirty( this );
        section_touched( this );
    } );
    $( '.field-section .field_video_url_button' ).click( function () {
        // Mark as dirty
        mark_dirty( this );
        section_touched( this );
    } );
    $( '.field-section .field_video_url' ).keydown( function () {
        // Mark as dirty
        mark_dirty( this );
        section_touched( this );
    } );


    $( '.field-form textarea' ).change( function () {
        // Mark as dirty		
        mark_dirty( this );
        section_touched( this );
    } );

    FieldPress.Events.on( 'editor:keyup', function( el ) {

        // Is it the text editor or the textarea
        var target = undefined === el.container ? el : el.container;

        // Only on Field Trip Setup page...
        if( $( target ).parents('.field-section' ).length > 0 ) {
            mark_dirty( $( target ) );
            section_touched( $( target ) );
        }

    } );

    $( '.field-form select' ).change( function () {
        // Mark as dirty		
        mark_dirty( this );
        section_touched( this );
    } );


    $( '#add-instructor-trigger' ).click( function () {

        // Field ID
        var field_id = $( '[name=field_id]' ).val();
        if ( !field_id ) {
            field_id = $.urlParam( 'field_id' );
            $( '[name=field_id]' ).val( field_id );
        }

        var instructor_id = $( '#instructors option:selected' ).val();

        // Mark as dirty
        mark_dirty( this );

        var instructor_nonce = $( '#instructor-ajax-check' ).data( 'nonce' );
        var uid = $( '#instructor-ajax-check' ).data( 'uid' );

        $.post(
            fieldpress_stops.admin_ajax_url, {
                action: 'add_field_instructor',
                instructor_id: instructor_id,
                instructor_nonce: instructor_nonce,
                field_id: field_id,
                user_id: uid,
            }
        ).done( function ( data, status ) {
                // Handle return
                if ( status == 'success' ) {

                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                    var response_type = $( $.parseHTML( response.content ) );

                    if ( $( "#instructor_holder_" + instructor_id ).length == 0 && response.instructor_added ) {
                        $( '.instructor-avatar-holder.empty' ).hide();
                        $( '#instructors-info' ).append( '<div class="instructor-avatar-holder" id="instructor_holder_' + instructor_id + '"><div class="instructor-status"></div><div class="instructor-remove"><a href="javascript:removeInstructor( ' + instructor_id + ' );"><i class="fa fa-times-circle cp-move-icon remove-btn"></i></a></div>' + instructor_avatars[ instructor_id ] + '<span class="instructor-name">' + jQuery( '#instructors option:selected' ).text() + '</span></div><input type="hidden" id="instructor_' + instructor_id + '" name="instructor[]" value="' + instructor_id + '" />' );
                    } else {
                        alert( response.reason );
                    }

                } else {
                }
            } ).fail( function ( data ) {
            } );


    } );


    $( '.field-form input' ).keypress( function ( event ) {
        $( this ).change();
    } );
    $( '.field-form textarea' ).keypress( function ( event ) {
        $( this ).change();
    } );

    /** Mark "dirty" content */
    $( '.field-form input' ).change( function () {
        mark_dirty( this );
        section_touched( this );

        if ( $( this ).attr( 'type' ) == 'checkbox' ) {
            if ( $( this ).attr( 'checked' ) ) {
                $( this ).val( 'on' );
            } else {
                $( this ).val( 'off' );
            }
        }


    } );


} );

// Popup that shows upon 'Field Trip Setup' completed or when 0 stops are found.
jQuery( document ).ready( function ( $ ) {
    var stop_count = $( '[name="stop_count"]' ).val();

    if ( stop_count == 0 ) {
        var content = '<div class="update orange top-right">' + fieldpress_stops.stop_setup_prompt + '<i class="fa fa-times-circle" /></div>';

        $( '#wpbody-content' ).append( content );
        $( '.update.orange.top-right' ).css( {
            position: 'absolute',
            top: '25px',
            right: '35px',
            'background-color': '#fff',
            'border-left': '4px solid #ec8c35',
            'box-shadow': '0 1px 1px 0 rgba(0, 0, 0, 0.1)',
            padding: '15px 25px 15px 15px',
        } );
        $( '.update.orange.top-right .fa-times-circle' ).css( {
            'font-size': '20px',
            position: 'absolute',
            top: '10px',
            right: '10px',
            cursor: 'pointer',
        } ).click( function ( event ) {
            $( this ).parent().remove();
        } );
    }
} );

function toggle_payment_box( event, bool ) {
    $ = jQuery;
    event.stopPropagation();
    $( '#marketpressprompt-box' ).toggle();
    if ( $( '#paid_field' ).is( ':checked' ) ) {
        $( '#paid_field' ).prop( 'checked', !bool );
    } else {
        $( '#paid_field' ).prop( 'checked', bool );
    }
}

function toggle_checkbox_option( target, event ) {
    $ = jQuery;
    event.stopPropagation();
    if ( $( target ).is( ':checked' ) ) {
        $( target ).prop( 'checked', false );
    } else {
        $( target ).prop( 'checked', true );
    }
    $( target ).change();
}

function add_check_label_handler( target ) {
    $ = jQuery;
    $( target ).siblings( 'span' ).click( function ( event ) {
        toggle_checkbox_option( target, event );
    } );
}

jQuery( document ).ready( function ( $ ) {
    // allow <span> to trigger checkbox
    add_check_label_handler( '#limit_class_size' );
    add_check_label_handler( '#allow_field_discussion' );
    add_check_label_handler( '#allow_workbook_page' );

    $( '#paid_field' ).click( function ( event ) {
        toggle_payment_box( event, false );
    } );
    $( '#marketpressprompt' ).click( function ( event ) {
        toggle_payment_box( event, true );
    } );

    $( 'div.button.cp-activate-mp-lite' ).click( function ( event ) {
        event.stopPropagation();

        $.post(
            fieldpress_stops.admin_ajax_url, {
                action: 'cp_activate_mp_lite',
            }
        ).done( function ( data, status ) {
                if ( status == 'success' ) {

                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                    if ( response && response.mp_lite_activated ) {
                        $( '.cp-markertpress-not-active' ).addClass( 'hidden' );
                        $( '.cp-markertpress-is-active' ).removeClass( 'hidden' );

                        //
                        var content = '<div class="update green top-right">' + fieldpress_stops.mp_activated_prompt + '<i class="fa fa-times-circle" /></div>';
                        var the_top = $( '.field-section.step-6 .field-form' ).position().top + 200;
                        $( '#wpbody-content' ).append( content );
                        $( '.update.green.top-right' ).css( {
                            position: 'absolute',
                            top: the_top,
                            right: '60px',
                            'background-color': '#fff',
                            'border-left': '4px solid #6DC36C',
                            'box-shadow': '0 1px 1px 0 rgba(0, 0, 0, 0.1)',
                            padding: '15px 25px 15px 15px',
                        } );
                        $( '.update.green.top-right .fa-times-circle' ).css( {
                            'font-size': '20px',
                            position: 'absolute',
                            top: '10px',
                            right: '10px',
                            cursor: 'pointer',
                        } ).click( function ( event ) {
                            $( this ).parent().remove();
                        } );
                        setTimeout( function () {
                            $( '.update.green.top-right' ).fadeOut( '2000' );
                        }, 3000 );


                    } else {
                        $( '.cp-markertpress-is-active' ).addClass( 'hidden' );
                        $( '.cp-markertpress-not-active' ).removeClass( 'hidden' );
                    }
                }
            } );


    } );

    $( '[name="meta_field_stop_options"]' ).change( function ( event ) {

        if ( $( this ).prop( 'checked' ) ) {
            $( '.field-stop [name^="meta_show_stop"]' ).attr( 'checked', 'checked' );
            $( '.field-stop [name^="meta_show_stop"]' ).val( 'on' );

            $( '.field-stop [name^="meta_show_page"]' ).attr( 'checked', 'checked' );
            $( '.field-stop [name^="meta_show_page"]' ).val( 'on' );
        } else {
            $( '.field-stop [name^="meta_show_stop"]' ).removeAttr( 'checked' );
            $( '.field-stop [name^="meta_show_stop"]' ).val( 'off' );

            $( '.field-stop [name^="meta_show_page"]' ).removeAttr( 'checked' );
            $( '.field-stop [name^="meta_show_page"]' ).val( 'off' );
        }

    } );

    // If inheriting field show options then force save
    if ( $( '[name="section_dirty"]' ) ) {
        mark_dirty( $( '[name="section_dirty"]' ) );
    }


    $( '.stop-control-buttons .button-preview' ).click( function ( event ) {
        $( '.wp-switch-editor.switch-tmce' ).click();
        event.preventDefault();
        $( '#stop-add' ).append( '<input type="hidden" name="preview_redirect_url" id="preview_redirect_url" value="yes" />' );
        $( '#stop-add' ).attr( 'target', '_blank' );
        $( '.stop-control-buttons .save-stop-button' ).click();
        $( '#stop-add' ).removeAttr( 'target' );
        $( '#preview_redirect_url' ).remove();
    } );

    // Attempt at preview redirect
    // // Phase 1: Redirect if 'Preview' triggered a save.
    // if ( $('[name="preview_redirect"]') && $('[name="preview_redirect"]').val() == 'yes' ) {
    // 	alert('reload: yes');
    // 	// Now proceed with normal click.
    // 	$('.stop-control-buttons .button-preview').attr( 'href', $('.stop-control-buttons .button-preview').attr('data-href') );
    // 	$('.stop-control-buttons .button-preview').click();
    // 	$('[name="preview_redirect"]').val('no');
    // } else if ( $('[name="preview_redirect"]') && $('[name="preview_redirect"]').val() == 'no' ) {
    // 	$('.stop-control-buttons .button-preview').removeAttr( 'href' );
    // }
    //
    // // $('.stop-control-buttons .button-preview').off();
    // // Phase 2: Preview clicked, so save the stop first.
    // $('.stop-control-buttons .button-preview').click( function( e ) {
    // 	if( $('[name="preview_redirect"]') && $('[name="preview_redirect"]').val() == 'no' ) {
    // 		alert('click: no');
    // 		e.stopPropagation();
    // 		$('[name="preview_redirect"]').first().val('yes');
    // 		$('.stop-control-buttons .save-stop-button').first().click();
    // 	}
    // });


} );


jQuery( document ).ready( function ( $ ) {
    var stop_state_toggle = {
        init: function () {
            this.attachHandlers( '.stop-state .control' );
        },
        controls: {
            $radio_slide_init: function ( selector ) {
                //console.log('requested');
                $( selector ).click( function () {
                    if ( $( this ).hasClass( 'disabled' ) ) {
                        return;
                    }

                    if ( $( selector ).hasClass( 'on' ) ) {
                        $( selector ).removeClass( 'on' );
                        $( selector ).parent().find( '.live' ).removeClass( 'on' );
                        $( selector ).parent().find( '.draft' ).addClass( 'on' );
                        $( '#stop_state' ).val( 'draft' );
                        $( '.mp-tab.active .stop-state-circle' ).removeClass( 'active' );
                        var stop_state = 'draft';
                    } else {
                        $( selector ).addClass( 'on' );
                        $( selector ).parent().find( '.draft' ).removeClass( 'on' );
                        $( selector ).parent().find( '.live' ).addClass( 'on' );
                        $( '#stop_state' ).val( 'publish' );
                        $( '.mp-tab.active .stop-state-circle' ).addClass( 'active' );
                        var stop_state = 'publish';
                    }

                    // Field ID
                    var field_id = $( '[name=field_id]' ).val();
                    if ( !field_id ) {
                        field_id = $.urlParam( 'field_id' );
                        $( '[name=field_id]' ).val( field_id );
                    }

                    var stop_id = $( this ).parent().find( '.stop_state_id' ).attr( 'data-id' );
                    var stop_nonce = $( this ).parent().find( '.stop_state_id' ).attr( 'data-nonce' );
                    var uid = $( '#field-ajax-check' ).data( 'uid' );

                    if ( stop_id !== '' ) {//if it's empty it means that's not saved yet so we won't save it via ajax
                        $.post(
                            fieldpress_stops.admin_ajax_url, {
                                action: 'change_stop_state',
                                stop_state: stop_state,
                                stop_id: stop_id,
                                stop_nonce: stop_nonce,
                                field_id: field_id,
                                user_id: uid,
                            }
                        ).done( function ( data, status ) {
                                if ( status == 'success' ) {

                                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                                    // console.log(response);
                                    // Apply a new nonce when returning
                                    if ( response && response.toggle ) {
                                        $( $( selector ).parents( 'form' )[ 0 ] ).find( '.stop_state_id' ).attr( 'data-nonce', response.nonce );
                                        // Else, toggle back.
                                    } else {
                                        if ( $( selector ).hasClass( 'on' ) ) {
                                            $( selector ).removeClass( 'on' );
                                            $( selector ).parent().find( '.live' ).removeClass( 'on' );
                                            $( selector ).parent().find( '.draft' ).addClass( 'on' );
                                            $( '#stop_state' ).val( 'draft' );
                                            $( '.mp-tab.active .stop-state-circle' ).removeClass( 'active' );
                                        } else {
                                            $( selector ).addClass( 'on' );
                                            $( selector ).parent().find( '.draft' ).removeClass( 'on' );
                                            $( selector ).parent().find( '.live' ).addClass( 'on' );
                                            $( '#stop_state' ).val( 'publish' );
                                            $( '.mp-tab.active .stop-state-circle' ).addClass( 'active' );
                                        }
                                    }
                                }
                            } );

                    }
                } );
            }
        },
        attachHandlers: function ( selector ) {
            //console.log('handlers attached');
            this.controls.$radio_slide_init( selector );
        }
    };

    var field_state_toggle = {
        init: function () {
            this.attachHandlers( '.field-state .control' );
        },
        controls: {
            $radio_slide_init: function ( selector ) {
                //console.log('requested');
                $( selector ).click( function () {
                    if ( $( this ).hasClass( 'disabled' ) ) {
                        return;
                    }

                    if ( $( selector ).hasClass( 'on' ) ) {
                        $( selector ).removeClass( 'on' );
                        $( selector ).parent().find( '.live' ).removeClass( 'on' );
                        $( selector ).parent().find( '.draft' ).addClass( 'on' );
                        $( '#field_state' ).val( 'draft' );
                        var field_state = 'draft';
                    } else {
                        $( selector ).addClass( 'on' );
                        $( selector ).parent().find( '.draft' ).removeClass( 'on' );
                        $( selector ).parent().find( '.live' ).addClass( 'on' );
                        var field_state = 'publish';
                    }

                    var field_id = $( '#field_state_id' ).attr( 'data-id' );
                    var field_nonce = $( '#field_state_id' ).attr( 'data-nonce' );
                    var uid = $( '#field-ajax-check' ).data( 'uid' );

                    $.post(
                        fieldpress_stops.admin_ajax_url, {
                            action: 'change_field_state',
                            field_state: field_state,
                            field_id: field_id,
                            field_nonce: field_nonce,
                            user_id: uid,
                        }
                    ).done( function ( data, status ) {
                            if ( status == 'success' ) {

                                var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                                // console.log(response);
                                // Apply a new nonce when returning
                                if ( response && response.toggle ) {
                                    $( '#field_state_id' ).attr( 'data-nonce', response.nonce );
                                    // Else, toggle back.
                                } else {
                                    if ( $( selector ).hasClass( 'on' ) ) {
                                        $( selector ).removeClass( 'on' );
                                        $( selector ).parent().find( '.live' ).removeClass( 'on' );
                                        $( selector ).parent().find( '.draft' ).addClass( 'on' );
                                        $( '#field_state' ).val( 'draft' );
                                    } else {
                                        $( selector ).addClass( 'on' );
                                        $( selector ).parent().find( '.draft' ).removeClass( 'on' );
                                        $( selector ).parent().find( '.live' ).addClass( 'on' );
                                    }
                                }
                            }
                        } );

                } );
            }
        },
        attachHandlers: function ( selector ) {
            //console.log('handlers attached');
            this.controls.$radio_slide_init( selector );
        }
    };

    field_state_toggle.init();//single field in admin
    stop_state_toggle.init();
} );

/* iFrame fix for MP Gateway popup. */
jQuery( document ).ready( function ( $ ) {
    $( '.button-edit-gateways' ).click( function () {
        fix_tinymce_in_iframe();
    } );
    $( '.button-incomplete-gateways' ).click( function () {
        fix_tinymce_in_iframe();
    } );
} );

function fix_tinymce_in_iframe() {
    if ( typeof ( tinyMCE ) === 'undefined' ) {
        return false;
    }
    var the_box = '.fieldpress_page_field_details #TB_iframeContent';
    var delay = 1000;//1 seconds
    setTimeout( function () {
        $( the_box ).on( 'load', function () {
            tinyMCE.execCommand( 'mceRepaint' );
            jQuery( the_box ).contents().find( '[id*="tmce"]' ).parents( '.wp-editor-wrap' ).find( '.mce-panel' ).show();
            jQuery( the_box ).contents().find( '#mp-need-help' ).hide();
        } );
    }, delay );
}


jQuery( document ).ready( function ( $ ) {


    /* Show Media Caption Toggle */
    $( document.body ).on( 'change', '[name*="show_media_caption"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).parents( '.caption-settings' ).find( '.caption-source' ).removeClass( 'hidden' );
            $( this ).siblings( '[name*="show_caption_field"]' ).val( 'yes' );
        } else {
            $( this ).parents( '.caption-settings' ).find( '.caption-source' ).addClass( 'hidden' );
            $( this ).siblings( '[name*="show_caption_field"]' ).val( 'no' );
        }
    } );
    $( document.body ).on( 'change', '[name*="_caption_source"]', function ( event ) {
        if ( $( this ).val() == 'media' ) {
            $( this ).siblings( '[name*="caption_field"]' ).val( 'media' );
        } else {
            $( this ).siblings( '[name*="caption_field"]' ).val( 'custom' );
        }
    } );

    /* Hide related media */
    $( document.body ).on( 'change', '[name*="hide_related_media"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '[name*="hide_related_media_field"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '[name*="hide_related_media_field"]' ).val( 'no' );
        }
    } );

    /* Set hidden title field. Resolves issue with $_POST arrays. */
    $( document.body ).on( 'change', '[name*="show_title_on_front"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '[name*="show_title_field"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '[name*="show_title_field"]' ).val( 'no' );
        }
    } );

    /* Set hidden mandatory field. Resolves issue with $_POST arrays. */
    $( document.body ).on( 'change', '[name*="module_mandatory_answer"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '[name*="module_mandatory_answer"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '[name*="module_mandatory_answer"]' ).val( 'no' );
        }
    } );

    /* Set hidden Assessable field. Resolves issue with $_POST arrays. */
    $( document.body ).on( 'change', '[name*="module_gradable_answer"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '[name*="module_gradable_answer"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '[name*="module_gradable_answer"]' ).val( 'no' );
        }
    } );

    /* Set hidden Limit Attempts field. Resolves issue with $_POST arrays. */
    $( document.body ).on( 'change', '[name*="module_limit_attempts"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '[name*="module_limit_attempts_field"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '[name*="module_limit_attempts_field"]' ).val( 'no' );
        }
    } );

    /* Set Stop Page Titles. */
    $( document.body ).on( 'change', '.show_page_title [name*="show_page_title"]', function ( event ) {
        if ( $( this ).attr( 'checked' ) ) {
            $( this ).siblings( '.show_page_title [name*="show_page_title_field"]' ).val( 'yes' );
        } else {
            $( this ).siblings( '.show_page_title [name*="show_page_title_field"]' ).val( 'no' );
        }
    } );

    function slim_scroll_load() {

        var scroll_height = 750;

        //if($(window).height() <= 750){
        scroll_height = $( window ).height() - 120;
        //}

        $( '#sortable-stops' ).slimScroll( {
            width: '200px',
            height: scroll_height + 'px',
            size: '5px',
            position: 'right',
            color: '#ec8c35',
            alwaysVisible: false,
            distance: '0px',
            railVisible: true,
            railColor: '#00B0D6',
            railOpacity: 0,
            wheelStep: 20,
            allowPageScroll: false,
            disableFadeOut: true
        } );
    }

    $( window ).resize( function () {
        slim_scroll_load();
    } );

    $( window ).load( function () {
        slim_scroll_load();
    } );


} );

