var global_iframe_content = '';
var FieldPress = FieldPress || {}

jQuery( document ).ready( function ( $ ) {

    // If we're on the Field Trip Overview / Setup page, init the editors
    if ( $( '#field .field-section.step' ).length > 0 ) {
        FieldPress.editor.init();
    }

    if( undefined != FieldPress.Events){
        FieldPress.Events.on( 'editor:created', function( el ) {
            $( '.fieldpress-media-button-message' ).off('click');
            $( '.fieldpress-media-button-message' ).on('click', function(e){
                console.log('MOOO');
                $( '.fieldpress-media-button-message span' ).toggle();
            });

        } );
    }

    if( undefined != FieldPress.Events){
        FieldPress.Events.on( 'editor:created', function( el ) {
            $( '.fieldpress-media-button-add-map' ).off('click');
            $( '.fieldpress-media-button-add-map' ).on('click', function(e){
                console.log('MOOO');
                $( '.fieldpress-media-button-add-map span' ).toggle();
            });

        } );
    }

    $( '#doaction_bulk_fields' ).click( function ( e ) {
        //$( '#bulk_fields_values' ).val();

        var searchIDs = $( "#fields_table .check-column input:checkbox:checked" ).map( function () {
            return $( this ).val();
        } ).get();

        $( '#bulk_fields_values' ).val( searchIDs );

    } );

    /*$('.stop-control-buttons .button.button-preview, .stop-control-buttons .submit-stop').click(function(){
     $('.wp-switch-editor.switch-tmce').click();
     });*/

    $( document.body ).on( 'input propertychange paste change', 'input.audio_url, input.video_url, input.image_url, input.featured_url, input.field_video_url', function () {
        if ( cp_is_extension_allowed( $( this ).val(), $( this ) ) ) {//extension is allowed
            $( this ).removeClass( 'invalid_extension_field' );
            $( this ).parent().find( '.invalid_extension_message' ).hide();
        } else {//extension is not allowed
            $( this ).addClass( 'invalid_extension_field' );
            $( this ).parent().find( '.invalid_extension_message' ).show();
        }
    } );

    var fields_state_toggle = {
        init: function () {
            this.attachHandlers( '.fields-state .control' );
        },
        controls: {
            $radio_slide_init: function ( selector ) {
                //console.log('requested');
                $( selector ).click( function () {

                    var the_toggle = this;
                    var field_id = $( this ).parent().find( '.field_state_id' ).attr( 'data-id' );
                    var field_nonce = $( this ).parent().find( '.field_state_id' ).attr( 'data-nonce' );
                    var uid = $( '#field-ajax-check' ).data( 'uid' );

                    if ( $( this ).hasClass( 'disabled' ) ) {
                        return;
                    }
                    if ( $( this ).hasClass( 'on' ) ) {
                        $( the_toggle ).removeClass( 'on' );
                        $( the_toggle ).parent().find( '.live' ).removeClass( 'on' );
                        $( the_toggle ).parent().find( '.draft' ).addClass( 'on' );
                        var field_state = 'draft';
                    } else {
                        $( the_toggle ).addClass( 'on' );
                        $( the_toggle ).parent().find( '.draft' ).removeClass( 'on' );
                        $( the_toggle ).parent().find( '.live' ).addClass( 'on' );
                        var field_state = 'publish';
                    }

                    $.post(
                        fieldpress.admin_ajax_url, {
                            action: 'change_field_state',
                            field_state: field_state,
                            field_id: field_id,
                            field_nonce: field_nonce,
                            user_id: uid,
                        }
                    ).done( function ( data, status ) {
                            if ( status == 'success' ) {

                                var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
                                // Apply a new nonce when returning
                                if ( response && response.toggle ) {
                                    $( the_toggle ).parent().find( '.field_state_id' ).attr( 'data-nonce', response.nonce );
                                    // Else, toggle back.
                                } else {
                                    if ( $( the_toggle ).hasClass( 'on' ) ) {
                                        $( the_toggle ).removeClass( 'on' );
                                        $( the_toggle ).parent().find( '.live' ).removeClass( 'on' );
                                        $( the_toggle ).parent().find( '.draft' ).addClass( 'on' );
                                    } else {
                                        $( the_toggle ).addClass( 'on' );
                                        $( the_toggle ).parent().find( '.draft' ).removeClass( 'on' );
                                        $( the_toggle ).parent().find( '.live' ).addClass( 'on' );
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

    fields_state_toggle.init();//field admin archive page

    jQuery( '#stop-pages' ).tabs();//{active:(fieldpress.stop_page_num - 1)}

    jQuery( document.body ).on( 'click', '#add_new_stop_page', function ( event ) {
        event.preventDefault();
        add_new_stop_page();
    } );

    jQuery( document.body ).on( 'click', '.ui-tabs-anchor', function ( event ) {

        var current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        var elements_count = jQuery( '#stop-page-' + current_page + ' .modules_accordion .module-holder-title' ).length;

        if ( fieldpress.stop_pagination == 0 ) {
            if ( ( current_page == 1 && elements_count == 0 ) || ( current_page >= 2 && elements_count == 1 ) ) {
                jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
            } else {
                jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
            }
        } else {
            if ( elements_count == 0 ) {
                jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
            } else {
                jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
            }
        }
    } );

    jQuery( document.body ).on( 'click', '.delete_stop_page .button-delete-stop', function () {

        var current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        var current_page_id = $( '#stop-pages .ui-tabs-nav .ui-state-active a' ).attr( 'href' );

        if ( delete_stop_page_and_elements_confirmed() ) {
            jQuery( '#stop-pages' ).css( 'display', 'none' );
            jQuery( '.stop_pages_delete' ).css( 'display', 'block' );

            jQuery( current_page_id + ' .element_id' ).each( function ( i, obj ) {
                prepare_element_for_execution( jQuery( this ).val() );
                jQuery( this ).closest( '.module-holder-title' ).remove();
            } );

//jQuery('#stop-page-' + current_page + ' .removable').each(function(i, obj) {
            jQuery( '.removable' ).each( function ( i, obj ) {
                jQuery( this ).closest( '.module-holder-title' ).remove();
            } );

            jQuery( '#stop-pages .ui-tabs-nav .ui-state-active' ).remove();

            //reenumarate_stop_pages();
            jQuery( current_page_id ).remove();
            //cp_repaint_all_editors();
            reenumarate_stop_pages();


            /*if (current_page == 1) {
             active_num = 1;
             } else {
             active_num = 0;
             }*/

            var stop_pages = jQuery( "#stop-pages .ui-tabs-nav li" ).size() - 2;

            //var elements_count = jQuery('#stop-page-' + current_page + ' .modules_accordion .module-holder-title').length;

            if ( stop_pages == 1 ) {
                jQuery( ".delete_stop_page" ).hide();
            } else {
                jQuery( ".delete_stop_page" ).show();
            }

            jQuery( "#stop-pages" ).tabs( { active: 0 } );

            current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
            current_page_id = $( '#stop-pages .ui-tabs-nav .ui-state-active a' ).attr( 'href' );

            var elements_count = jQuery( current_page_id + ' .modules_accordion .module-holder-title' ).length;

            if ( fieldpress.stop_pagination == 0 ) {
                if ( ( current_page == 1 && elements_count == 0 ) || ( current_page >= 2 && elements_count == 1 ) ) {
                    jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
                } else {
                    jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
                }
            } else {
                if ( elements_count == 0 ) {
                    jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
                } else {
                    jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
                }
            }


            if ( typeof current_page === "undefined" ) {
                jQuery( "#stop-pages" ).tabs( { active: 1 } );
            }

            update_module_page_number();
            update_stop_page_order_and_numbers();
            //cp_repaint_all_editors();

            jQuery( '.stop-pages-navigation' ).css( 'opacity', '1' );


            jQuery( '.stop-control-buttons .save-stop-button' ).click();
        }

        function reenumarate_stop_pages() {
            var i = 1;
            jQuery( ".stop-pages-navigation li.ui-state-default" ).each( function ( index ) {
                if ( jQuery( this ).find( 'a' ).html() !== '+' ) {
                    jQuery( this ).find( 'a' ).html( i );
                    jQuery( this ).attr( 'aria-controls', 'stop-page-' + i );
                    jQuery( this ).attr( 'aria-labelledby', 'ui-id-' + i );
                    jQuery( this ).find( 'a' ).attr( 'href', '#stop-page-' + i );
                    jQuery( this ).find( 'a' ).attr( 'id', 'ui-id-' + i );
                    i++;
                }
            } );

            i = 1;

            jQuery( "#stop-pages .ui-tabs-panel" ).each( function ( index ) {
                jQuery( this ).attr( 'id', 'stop-page-' + i );
                jQuery( this ).attr( 'aria-controls', 'stop-page-' + i );
                jQuery( this ).attr( 'aria-labelledby', 'ui-id-' + i );
                i++;
            } );

        }

        function delete_stop_page_and_elements_confirmed() {
            return confirm( fieldpress.delete_stop_page_and_elements_alert );
        }

        function prepare_element_for_execution( module_to_execute_id ) {
            jQuery( '<input>' ).attr( {
                type: 'hidden',
                name: 'modules_to_execute[]',
                value: module_to_execute_id
            } ).appendTo( '#stop-add' );
        }

    } );


    jQuery( document.body ).on( 'click', '.ui-tabs-anchor', function ( event ) {
        var current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();

        var form_action = jQuery( "#stop-add" ).attr( "action" );

        //var match = form_action.match( /stop-page-\[( \d+ )\]/ );
        //alert( match[1] );

        if ( jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion div' ).first().attr( 'class' ) == 'module-holder-page_break_module module-holder-title' ) {
            jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "option", "active", 1 );
        } else {
            jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "option", "active", 0 );
        }

    } );


    function add_new_stop_page() {
        var tabs = jQuery( "#stop-pages" ).tabs();
        var stop_pages = jQuery( "#stop-pages .ui-tabs-nav li" ).size() - 2;
        var next_page = ( stop_pages + 1 );
        var id = "stop-page-" + next_page;
        var li = '<li><a href="#' + id + '">' + next_page + '</a><span class="arrow-down"></span></li>';
        var tabs_html = jQuery( '.ui-tabs-nav' ).html();
        var add_page_plus = '<li class="ui-state-default ui-corner-top add_new_stop_page"><a id="add_new_stop_page" class="ui-tabs-anchor">+</a></li>';

        tabs_html = tabs_html.replace( add_page_plus, '' );

        jQuery( '.ui-tabs-nav' ).html( tabs_html + li + add_page_plus );

        jQuery( '#stop-pages' ).append( '<div id="stop-page-' + next_page + '"><div class="field-details elements-holder">' + jQuery( '.elements-holder' ).html() + '</div><div class="modules_accordion"></div></div>' );
        //jQuery('#stop-page-'+next_page).append('<a class="delete_module_link" onclick="delete_stop_page_and_elements_confirmed()"><i class="fa fa-trash-o"></i> '+fieldpress.delete_stop_page_label+'</a>');
        tabs.tabs( "refresh" );

        jQuery( '#stop-page-' + next_page + ' .page_title' ).val( '' );
        jQuery( '#stop-page-' + next_page + ' .page_title' ).attr( 'name', 'page_title[page_' + next_page + ']' );
        jQuery( '#stop-page-' + next_page + ' .page_title' ).attr( 'id', 'page_title[page_' + next_page + ']' );

        /*jQuery( '#stop-page-' + next_page + ' .modules_accordion' ).accordion( {
         heightStyle: "content",
         header: "> div > h3",
         collapsible: true,
         } );*/


        if ( $( '#stop-page-' + next_page + ' .modules_accordion' ).hasClass( 'ui-accordion' ) ) {
            $( '#stop-page-' + next_page + ' .modules_accordion' ).accordion( 'destroy' );
        }
        jQuery( '#stop-page-' + next_page + ' .modules_accordion' ).accordion( {
            heightStyle: "content",
            header: "> div > h3",
            collapsible: true
        } ).sortable( {
            handle: "h3",
            axis: "y",
            stop: function ( event, ui ) {

                update_sortable_module_indexes();
                //ui.draggable.attr( 'id' ) or ui.draggable.get( 0 ).id or ui.draggable[0].id

                /* Dynamic WP Editor */

                var nth_child_num = ui.item.index() + 1;
                var module_selector = "#stop-page-" + next_page + " .module-holder-title:nth-child( " + nth_child_num + " )";
                var editor_id = $( module_selector + " .wp-editor-wrap" ).attr( 'id' );

                var initial_editor_id = editor_id;

                editor_id = editor_id.replace( "-wrap", "" );
                editor_id = editor_id.replace( "wp-", "" );

                var content = $( '#' + editor_id ).html();
                var name = $( '#' + editor_id ).attr( 'name' );

                $( '#' + initial_editor_id ).detach();
                try {
                    delete tinyMCEPreInit.mceInit[ editor_id ];
                    delete tinyMCEPreInit.qtInit[ editor_id ];
                    delete tinyMCE.EditorManager.editors[ editor_id ];

                    // Get rid of other redundancy
                    $.each( tinyMCE.EditorManager.editors, function ( idx ) {
                        try {
                            var eid = tinyMCE.EditorManager.editors[ idx ].id;
                            if ( editor_id === eid ) {
                                delete tinyMCE.EditorManager.editors[ idx ];
                            }
                            ;
                        } catch ( ei ) {
                        }
                    } );
                } catch ( e ) {
                }

                var id = editor_id;
                var text_editor_whole = '<textarea name="' + name + '"  class="fieldpress-editor" id="' + id + '"></textarea>';
                var editor = module_selector + ' .editor_in_place';

                editor = $( editor );

                var height = 300;

                $( editor ).html( text_editor_whole );

                editor = $( module_selector + ' [name="' + name + '"]' );

                FieldPress.editor.create( editor, id, name, content, false, height );

                $( editor ).on('keyup', function( object ) {
                    // Fix Enter/Return key
                    if( 13 === object.keyCode ) {
                        $( this ).val( $( this ).val() + "\n" );
                    }
                    FieldPress.Events.trigger( 'editor:keyup', this );
                });


            }
        }, function () {
            jQuery( 'a' ).click( function ( e ) {
//e.stopPropagation();
            } )
        } ).on( 'click', 'a', function ( e ) {
//e.stopPropagation();
        } );

        var rand_id = 'rand_id' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 );
        var cloned = jQuery( '.draggable-module-holder-page_break_module' ).html();
        cloned = '<div class="module-holder-page_break_module module-holder-title" id="' + rand_id + '_temp">' + cloned + '</div>';

        //jQuery( '#stop-page-' + next_page + ' .modules_accordion' ).append( cloned );

        jQuery( '#stop-page-' + next_page + ' .modules_accordion' ).accordion( "refresh" );

        jQuery( "#stop-pages li" ).each( function ( index ) {
            jQuery( this ).removeClass( 'ui-tabs-active ui-state-active' ); //fix for active stop page state
        } );

        jQuery( '#stop-pages' ).tabs( { active: stop_pages } ); //set last added page active

        jQuery.post(
            fieldpress.admin_ajax_url, {
                action: 'create_stop_element_draft',
                stop_id: jQuery( '#stop_id' ).val(),
                temp_stop_id: rand_id,
            }
        ).done( function ( data, status ) {
                jQuery( '#' + rand_id + '_temp' ).find( '.stop_element_id' ).val( data );
                jQuery( '#' + rand_id + '_temp' ).find( '.element_id' ).val( data );
            } );

        var current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
        var accordion_elements_count = jQuery( '#stop-pages-' + current_stop_page + ' .modules_accordion' ).find( 'div.module-holder-title' ).length;

        jQuery( '#stop-page-' + current_stop_page + ' .elements-holder .no-elements' ).show();

        if ( stop_pages == 0 ) {
            jQuery( ".delete_stop_page" ).hide();
        } else {
            jQuery( ".delete_stop_page" ).show();
        }

    }
} );

jQuery( document ).ready( function () {

    jQuery( '#add_student_class' ).click( function () {

        var class_input_errors = 0;
        if ( jQuery( '.field_classes_input' ).val() == '' ) {
            jQuery( '.add_class_message' ).html( fieldpress.empty_class_name );
            class_input_errors++;
        }

        jQuery( ".ui-accordion-header h3" ).each( function ( index ) {
            if ( jQuery( this ).attr( 'data-title' ) == jQuery( '.field_classes_input' ).val() ) {
                jQuery( '.add_class_message' ).html( fieldpress.duplicated_class_name );
                class_input_errors++;
            }
        } );
        if ( class_input_errors == 0 ) {
            return true;
        } else {
            return false;
        }

    } );
} );

jQuery( document ).ready( function () {
    jQuery( document.body ).on( 'input', '.checkbox_answer', function () {
        jQuery( this ).closest( 'td' ).find( ".checkbox_answer_check" ).val( jQuery( this ).val() );
    } );
} );

jQuery( document ).ready( function () {
    if ( fieldpress.field_taxonomy_screen ) {
//jQuery( '#adminmenu .wp-submenu li.current' ).removeClass( "current" );
        jQuery( 'a[href="edit-tags.php?taxonomy=field_category&post_type=field"]' ).parent().addClass( "current" );
    }
} );

/* STOP MODULES */
jQuery( document ).ready( function () {
    jQuery( document.body ).on( 'click', '.action .action-top .action-button', function () {
        if ( jQuery( this ).parent().hasClass( 'open' ) ) {
            jQuery( this ).parent().removeClass( 'open' ).addClass( 'closed' );
            jQuery( this ).parents( '.action' ).find( '.action-body' ).removeClass( 'open' ).addClass( 'closed' );
        } else {
            jQuery( this ).parent().removeClass( 'closed' ).addClass( 'open' );
            jQuery( this ).parents( '.action' ).find( '.action-body' ).removeClass( 'closed' ).addClass( 'open' );
        }
    } );
} );

function fieldpress_module_click_action_toggle() {
    if ( jQuery( this ).parent().hasClass( 'open' ) ) {
        jQuery( this ).parent().removeClass( 'open' ).addClass( 'closed' );
        jQuery( this ).parents( '.action' ).find( '.action-body' ).removeClass( 'open' ).addClass( 'closed' );
    } else {
        jQuery( this ).parent().removeClass( 'closed' ).addClass( 'open' );
        jQuery( this ).parents( '.action' ).find( '.action-body' ).removeClass( 'closed' ).addClass( 'open' );
    }
}

function fieldpress_no_elements( elements_number ) {

}


function update_sortable_module_indexes_page_sort( page_id, page_num ) {
    // alert(page_num);
    jQuery( '#' + page_id + ' .module_order' ).each( function ( i, obj ) {
        jQuery( this ).val( page_num * ( i + 1 ) );
    } );
    /*
     *  jQuery( '#' + page_id + ' .module_page' ).each( function( i, obj ) {
     jQuery( this ).val( page_num );
     } );
     * 
     */


    jQuery( "input[name*='audio_module_loop']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "audio_module_loop[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    jQuery( "input[name*='audio_module_autoplay']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "audio_module_autoplay[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    jQuery( "input[name*='radio_answers']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "radio_input_module_radio_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='radio_check']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "radio_input_module_radio_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='checkbox_answers']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "checkbox_input_module_checkbox_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='checkbox_check']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "checkbox_input_module_checkbox_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    var current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
    var elements_count = jQuery( '#stop-page-' + current_page + ' .modules_accordion .module-holder-title' ).length;

    if ( fieldpress.stop_pagination == 0 ) {
        if ( ( current_page == 1 && elements_count == 0 ) || ( current_page >= 2 && elements_count == 1 ) ) {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
        } else {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
        }
    } else {
        if ( elements_count == 0 ) {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
        } else {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
        }
    }
}

function update_module_page_number() {
    var curr_page = 1;
    jQuery( '#stop-pages li.ui-state-default' ).each( function ( i, obj ) {
        if ( $( this ).find( 'a.ui-tabs-anchor' ).attr( 'id' ) !== 'add_new_stop_page' ) {
            //$( '#stop-pages #stop-page-' + $( this ).find( 'a.ui-tabs-anchor' ).text() ).attr( 'data-weight', i + 1 );
            //$( this ).find( 'a.ui-tabs-anchor' ).text( i + 1 );

            var holder_id = $( this ).find( 'a.ui-tabs-anchor' ).attr( 'href' );
            var res = holder_id.replace( "stop-page-", "" );

            $( holder_id ).find( '.module_page' ).val( curr_page );
            curr_page++;
        }
    } );
}


function update_stop_page_order_and_numbers() {
    jQuery( '.stop-pages-navigation' ).css( 'opacity', '0.5' );
    var curr_page = 1;

    jQuery( '#stop-pages li.ui-state-default' ).each( function ( i, obj ) {
        if ( $( this ).find( 'a.ui-tabs-anchor' ).attr( 'id' ) !== 'add_new_stop_page' ) {
            $( '#stop-pages #stop-page-' + $( this ).find( 'a.ui-tabs-anchor' ).text() ).attr( 'data-weight', i + 1 );
            $( this ).find( 'a.ui-tabs-anchor' ).text( i + 1 );

            var holder_id = $( this ).find( 'a.ui-tabs-anchor' ).attr( 'href' );
            var res = holder_id.replace( "stop-page-", "" );

            $( holder_id ).find( '.module_page' ).val( curr_page );
            $( holder_id ).find( '.page_title' ).attr( 'name', 'page_title[page_' + curr_page + ']' );
            $( holder_id ).find( '.page_title' ).attr( 'id', 'page_title[page_' + curr_page + ']' );
            curr_page++;
        }
    } );

    var wrapper = jQuery( '#stop-pages' );
    stop_pages = wrapper.find( '.stop-page-holder' );
    /*$stop_pages, $wrapper*/
    [].sort.call( stop_pages, function ( a, b ) {
        return +jQuery( a ).attr( 'data-weight' ) - +jQuery( b ).attr( 'data-weight' );
    } );

    stop_pages.each( function () {
        wrapper.append( this );
    } );

}

function cp_repaint_current_page_editors() {
    /* Dynamic WP Editor */

    var current_page = jQuery( ".stop-page-holder[aria-expanded='true']" );
    var current_page_id = current_page.attr( 'id' );

    jQuery( '#' + current_page_id + ' .wp-editor-wrap' ).each( function ( i, obj ) {

        var nth_child_num = i + 1;
        var editor_id = $( this ).attr( 'id' );
        var initial_editor_id = editor_id;

        editor_id = editor_id.replace( "-wrap", "" );
        editor_id = editor_id.replace( "wp-", "" );
        //editor_content = get_tinymce_content_new( editor_id );

        tinyMCE.init( {
// General options
            mode: "specific_textareas",
            editor_selector: "mceEditor",
        } );

        /*var iframe_id = $( this ).find( 'iframe' ).attr( 'id' );
         var iframe_content = document.getElementById( iframe_id ).contentWindow.document.body.innerHTML;
         //console.log('iframe id: '+iframe_id);
         //console.log( iframe_content );

         editor_content = iframe_content;//tinyMCE.get( editor_id ).getContent();*/

        editor_content = tinyMCE.get( editor_id ).getContent();

        var textarea_name = ( jQuery( '#' + initial_editor_id + ' textarea' ).attr( 'name' ) );
        var rand_id = 'rand_id' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 );
        var text_editor = '<textarea name="' + textarea_name + '" id="' + rand_id + '">' + editor_content + '</textarea>';

        var switches = '<a id="' + rand_id + '-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);">Visual</a>';
        switches += '<a id="' + rand_id + '-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);">Text</a>';


        var text_editor_whole =
            '<div id="wp-' + rand_id + '-wrap" class="wp-core-ui wp-editor-wrap tmce-active">' +
            '<div id="wp-' + rand_id + '-editor-tools" class="wp-editor-tools hide-if-no-js">' +
            '<div id="wp-' + rand_id + '-media-buttons" class="wp-media-buttons"><a href="#" class="button insert-media-cp add_media" data-editor="' + rand_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a></div>';
        text_editor_whole += fieldpress_editor.quicktags ? '<div class="wp-editor-tabs">' + switches + '</div>' : '';
        text_editor_whole += '<div id="wp-' + rand_id + '-editor-container" class="wp-editor-container">' +
            text_editor +
            '</div></div></div>';
        jQuery( '#' + initial_editor_id ).parent().html( text_editor_whole );

        tinyMCE.init( {
            mode: "exact",
            elements: rand_id,
            plugins: fieldpress_editor.plugins.join( ',' ),
            toolbar: fieldpress_editor.toolbar.join( ',' ),
            theme: fieldpress_editor.theme,
            skin: fieldpress_editor.skin,
            menubar: false,
        } );

        // Init Quicktags
        if ( fieldpress_editor.quicktags ) {
            new QTags( rand_id );
            QTags._buttonsInit();
            // force the editor to start at its defined mode.
            switchEditors.go( rand_id, tinyMCE.editors[ rand_id ] );
        }
    } );

    tinyMCE.execCommand( 'mceRepaint' );
}

function cp_repaint_all_editors() {

    /* REPAINT ALL EDITORS AFTER RESORTING PAGES */
    //update_sortable_module_indexes();
    //ui.draggable.attr( 'id' ) or ui.draggable.get( 0 ).id or ui.draggable[0].id

    /* Dynamic WP Editor */
    jQuery( '.stop-page-holder .wp-editor-wrap' ).each( function ( i, obj ) {

        var nth_child_num = i + 1;
        var editor_id = $( this ).attr( 'id' );
        var initial_editor_id = editor_id;

        editor_id = editor_id.replace( "-wrap", "" );
        editor_id = editor_id.replace( "wp-", "" );
        //editor_content = get_tinymce_content_new( editor_id );

        tinyMCE.init( {
// General options
            mode: "specific_textareas",
            editor_selector: "mceEditor",
        } );

        editor_content = tinyMCE.get( editor_id ).getContent();

        var textarea_name = ( jQuery( '#' + initial_editor_id + ' textarea' ).attr( 'name' ) );
        var rand_id = 'rand_id' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 );
        var text_editor = '<textarea name="' + textarea_name + '" id="' + rand_id + '">' + editor_content + '</textarea>';

        var switches = '<a id="' + rand_id + '-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);">Visual</a>';
        switches += '<a id="' + rand_id + '-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);">Text</a>';


        var text_editor_whole =
            '<div id="wp-' + rand_id + '-wrap" class="wp-core-ui wp-editor-wrap tmce-active">' +
            '<div id="wp-' + rand_id + '-editor-tools" class="wp-editor-tools hide-if-no-js">' +
            '<div id="wp-' + rand_id + '-media-buttons" class="wp-media-buttons"><a href="#" class="button insert-media-cp add_media" data-editor="' + rand_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a></div>';
        text_editor_whole += fieldpress_editor.quicktags ? '<div class="wp-editor-tabs">' + switches + '</div>' : '';
        text_editor_whole += '<div id="wp-' + rand_id + '-editor-container" class="wp-editor-container">' +
            text_editor +
            '</div></div></div>';
        jQuery( '#' + initial_editor_id ).parent().html( text_editor_whole );

        tinyMCE.init( {
            mode: "exact",
            elements: rand_id,
            plugins: fieldpress_editor.plugins.join( ',' ),
            toolbar: fieldpress_editor.toolbar.join( ',' ),
            theme: fieldpress_editor.theme,
            skin: fieldpress_editor.skin,
            menubar: false,
        } );

        // Init Quicktags
        if ( fieldpress_editor.quicktags ) {
            new QTags( rand_id );
            QTags._buttonsInit();
            // force the editor to start at its defined mode.
            switchEditors.go( rand_id, tinyMCE.editors[ rand_id ] );
        }
    } );

    tinyMCE.execCommand( 'mceRepaint' );
}

function fieldpress_modules_ready() {

    jQuery( '.draggable-module' ).draggable( {
        opacity: 0.7,
        helper: 'clone',
        start: function ( event, ui ) {
            jQuery( 'input#beingdragged' ).val( jQuery( this ).attr( 'id' ) );
        },
        stop: function ( event, ui ) {

        }
    } );

    jQuery( document.body ).on( 'click', '.elements-holder div.output-element, .elements-holder div.input-element', function () {//.stop-module-add,

        var current_stop_page = 0;//current selected stop page

        current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();

        var stamp = new Date().getTime();
        var module_count = 0;

        jQuery( 'input#beingdragged' ).val( jQuery( this ).find( '.add-element' ).attr( 'id' ) );//jQuery( "#stop-page-" + current_stop_page + " .stop-module-list option:selected" ).val()

        var cloned = jQuery( '.draggable-module-holder-' + jQuery( 'input#beingdragged' ).val() ).html();

        var rand_id = 'rand_id' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 ) + '_' + Math.floor( ( Math.random() * 99999 ) + 100 );

        cloned = '<div class="module-holder-' + jQuery( 'input#beingdragged' ).val() + ' module-holder-title" id="' + rand_id + '_temp">' + cloned + '</div>';

        jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).append( cloned );

        var data = '';

        jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion();
        jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "refresh" );
        jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).accordion( "option", "active", -1 );

        moving = jQuery( 'input#beingdragged' ).val();

        if ( moving != '' ) {

        }

        jQuery( '.module_order' ).each( function ( i, obj ) {
            jQuery( this ).val( i + 1 );
            module_count = i;
        } );

        module_count = module_count - jQuery( ".stop-module-list option" ).size();

        jQuery( "input[name*='audio_module_loop']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "audio_module_loop[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + ']' );
        } );

        jQuery( "input[name*='audio_module_autoplay']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "audio_module_autoplay[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + ']' );
        } );

        jQuery( "input[name*='radio_answers']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "radio_input_module_radio_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        jQuery( "input[name*='radio_check']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "radio_input_module_radio_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        jQuery( "input[name*='checkbox_answers']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "checkbox_input_module_checkbox_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        jQuery( "input[name*='checkbox_check']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "checkbox_input_module_checkbox_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        jQuery( "input[name*='answer_length']" ).each( function( i, obj ) {
            jQuery( this ).attr( "name", "text_input_module_answer_length[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );

        /* Dynamic WP Editor */
        moving = jQuery( 'input#beingdragged' ).val();
        //var text_editor_whole = '<textarea name="' + moving + '_content[]" id="' + rand_id + '"></textarea>';
        var name = moving + '_content[]';
        var id = rand_id;
        var text_editor_whole = '<textarea name="' + name + '"  class="fieldpress-editor" id="' + id + '"></textarea>';
        var content = $( '#' + id ).val() || '';
        var editor = '#stop-page-' + current_stop_page + ' .modules_accordion .editor_in_place';
        editor = $( editor ).last();
        var height = 400;

        $( editor ).html( text_editor_whole );
        editor = $( editor ).find( '[name="' + name + '"]' );
        FieldPress.editor.create( editor, id, name, content, false, height );

        $( editor ).on( 'keyup', function ( object ) {

            // Fix Enter/Return key
            if ( 13 === object.keyCode ) {
                $( this ).val( $( this ).val() + "\n" );
            }

            FieldPress.Events.trigger( 'editor:keyup', this );
        } );

        //
        //var text_editor = '<textarea name="' + moving + '_content[]" id="' + rand_id + '"></textarea>';
        //
        //var switches = '<a id="' + rand_id + '-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);">Visual</a>';
        //switches += '<a id="' + rand_id + '-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);">Text</a>';
        //
        //
        //
        //var text_editor_whole =
        //    '<div id="wp-' + rand_id + '-wrap" class="wp-core-ui wp-editor-wrap tmce-active">' +
        //    '<div id="wp-' + rand_id + '-editor-tools" class="wp-editor-tools hide-if-no-js">' +
        //    '<div id="wp-' + rand_id + '-media-buttons" class="wp-media-buttons"><a href="#" class="button insert-media-cp add_media" data-editor="' + rand_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a></div>';
        //text_editor_whole += fieldpress_editor.quicktags ? '<div class="wp-editor-tabs">' + switches + '</div>' : '';
        //text_editor_whole += '<div id="wp-' + rand_id + '-editor-container" class="wp-editor-container">' +
        //    text_editor +
        //    '</div></div></div>';
        //
        //jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion .editor_in_place' ).last().html( text_editor_whole );
        //
        //// Init tinyMCE
        //tinyMCE.init( {
        //    mode: "specific_textareas",
        //    elements: rand_id,
        //    plugins: fieldpress_editor.plugins.join( ',' ),
        //    toolbar: fieldpress_editor.toolbar.join( ',' ),
        //    theme: fieldpress_editor.theme,
        //    skin: fieldpress_editor.skin,
        //    menubar: false,
        //    height: '360px',
        //    content_css: fieldpress.cp_editor_style,
        //} );
        //
        //// Init Quicktags
        //if ( fieldpress_editor.quicktags ) {
        //    new QTags( rand_id );
        //    QTags._buttonsInit();
        //    // force the editor to start at its defined mode.
        //    switchEditors.go( rand_id, tinyMCE.editors[rand_id] );
        //}
        //
        //tinyMCE.execCommand( 'mceRepaint' );


        // PAY ATTENTION BELOW

        var accordion_elements_count = ( jQuery( this ).parents( '.elements-holder' ).siblings( '.modules_accordion' ).find( 'div.module-holder-title' ).length );//find('.modules_accordion').length

        jQuery( this ).parent().parent().find( '.modules_accordion div.module-holder-title' ).last().find( '.module-title' ).attr( 'data-panel', accordion_elements_count );
        jQuery( this ).parent().parent().find( '.modules_accordion div.module-holder-title' ).last().find( '.module-title' ).attr( 'data-id', -1 );

        if ( fieldpress.stop_pagination == 0 ) {
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

        jQuery.post(
            fieldpress.admin_ajax_url, {
                action: 'create_stop_element_draft',
                stop_id: jQuery( '#stop_id' ).val(),
                temp_stop_id: rand_id,
            }
        ).done( function ( data, status ) {
                jQuery( '#' + rand_id + '_temp' ).find( '.stop_element_id' ).val( data );
                jQuery( '#' + rand_id + '_temp' ).find( '.element_id' ).val( data );
            } );

        //update_stop_page_order_and_numbers();
        update_module_page_number();
    } );
}

jQuery( document ).ready( fieldpress_modules_ready );
/* END-STOP MODULES*/

jQuery( function () {
    jQuery( ".spinners" ).spinner( {
        min: 0,
        stop: function ( event, ui ) {
            // Trigger change event.
            jQuery( this ).change();
        },
    } );
    jQuery( '.dateinput' ).datepicker( {
        dateFormat: 'yy-mm-dd',
        firstDay: fieldpress.start_of_week
    } );
    //jQuery( '.timeinput' ).timepicker( {
    //    timeFormat: 'H:i:s'
    //} );
} );

function update_sortable_module_indexes() {

    jQuery( '.module_order' ).each( function ( i, obj ) {
        jQuery( this ).val( i + 1 );
    } );

    jQuery( "input[name*='audio_module_loop']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "audio_module_loop[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    jQuery( "input[name*='audio_module_autoplay']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "audio_module_autoplay[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    jQuery( "input[name*='radio_answers']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "radio_input_module_radio_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='radio_check']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "radio_input_module_radio_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='checkbox_answers']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "checkbox_input_module_checkbox_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );
    jQuery( "input[name*='checkbox_check']" ).each( function ( i, obj ) {
        jQuery( this ).attr( "name", "checkbox_input_module_checkbox_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
    } );

    var current_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
    var elements_count = jQuery( '#stop-page-' + current_page + ' .modules_accordion .module-holder-title' ).length;

    if ( fieldpress.stop_pagination == 0 ) {
        if ( ( current_page == 1 && elements_count == 0 ) || ( current_page >= 2 && elements_count == 1 ) ) {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
        } else {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
        }
    } else {
        if ( elements_count == 0 ) {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).show();
        } else {
            jQuery( '#stop-page-' + current_page + ' .elements-holder .no-elements' ).hide();
        }
    }
}

function withdraw_student_confirmed() {
    return confirm( fieldpress.withdraw_student_alert );
}

function withdrawStudent() {
    if ( withdraw_student_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function remove_module_confirmed() {
    return confirm( fieldpress.remove_module_alert );
}

function removeModule() {
    if ( remove_module_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function delete_module_confirmed() {
    return confirm( fieldpress.delete_module_alert );
}

function prepare_module_for_execution( module_to_execute_id ) {
    jQuery( '<input>' ).attr( {
        type: 'hidden',
        name: 'modules_to_execute[]',
        value: module_to_execute_id
    } ).appendTo( '#stop-add' );
}


function deleteModule( module_to_execute_id ) {
    if ( delete_module_confirmed() ) {
        prepare_module_for_execution( module_to_execute_id );
        return true;
    } else {
        return false;
    }
}

function delete_field_confirmed() {
    return confirm( fieldpress.delete_field_alert );
}

function removeField() {
    if ( delete_field_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function delete_student_response_confirmed() {
    return confirm( fieldpress.delete_student_response_alert );
}

function removeStudentResponse() {
    if ( delete_student_response_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function delete_notification_confirmed() {
    return confirm( fieldpress.delete_notification_alert );
}

function removeNotification() {
    if ( delete_notification_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function delete_discussion_confirmed() {
    return confirm( fieldpress.delete_discussion_alert );
}

function removeDiscussion() {
    if ( delete_discussion_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function removeStop() {
    if ( delete_stop_confirmed() ) {
        return true;
    } else {
        return false;
    }
}

function delete_stop_confirmed() {
    return confirm( fieldpress.delete_stop_alert );
}

function delete_instructor_confirmed() {
    return confirm( fieldpress.delete_instructor_alert );
}

function removeInstructor( instructor_id ) {
    $ = jQuery;
    if ( delete_instructor_confirmed() ) {

        // Field ID
        var field_id = $( '[name=field_id]' ).val();
        if ( !field_id ) {
            field_id = $.urlParam( 'field_id' );
            $( '[name=field_id]' ).val( field_id );
        }

        // Mark as dirty
        var parent_section = $( '#instructor_holder_' + instructor_id ).parents( '.field-section.step' )[ 0 ];
        if ( parent_section ) {
            if ( !$( parent_section ).hasClass( 'dirty' ) ) {
                $( parent_section ).addClass( 'dirty' );
            }
        }

        var instructor_nonce = $( '#instructor-ajax-check' ).data( 'nonce' );
        var uid = $( '#instructor-ajax-check' ).data( 'uid' );

        $.post(
            fieldpress.admin_ajax_url, {
                action: 'remove_field_instructor',
                instructor_id: instructor_id,
                field_id: field_id,
                instructor_nonce: instructor_nonce,
                user_id: uid,
            }
        ).done( function ( data, status ) {
                // Handle return
                if ( status == 'success' ) {

                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );

                    var response_type = $( $.parseHTML( response.content ) );

                    if ( response.instructor_removed ) {
                        $( "#instructor_holder_" + instructor_id ).remove();
                        $( "#instructor_" + instructor_id ).remove();
                        if ( 1 == $( '.instructor-avatar-holder' ).length ) {
                            $( '.instructor-avatar-holder.empty' ).show();
                        }
                    }

                } else {
                }
            } ).fail( function ( data ) {
            } );

    }
}

function removePendingInstructor( invite_code, field_id ) {
    $ = jQuery;
    if ( confirm( fieldpress.delete_pending_instructor_alert ) ) {

        var instructor_nonce = $( '#instructor-ajax-check' ).data( 'nonce' );
        var uid = $( '#instructor-ajax-check' ).data( 'uid' );

        $.post(
            fieldpress.admin_ajax_url, {
                action: 'remove_instructor_invite',
                invite_code: invite_code,
                field_id: field_id,
                instructor_nonce: instructor_nonce,
                user_id: uid,
            }
        ).done( function ( data, status ) {
                if ( status == 'success' ) {
                    var response = $.parseJSON( $( data ).find( 'response_data' ).text() );

                    if ( response.invite_removed ) {
                        $( '#' + invite_code ).remove();
                    }
                }
            } ).fail( function ( data ) {
            } );
    }
}

jQuery( document ).ready( function ( $ ) {

    // Enable spellcheck on textboxes/textareas
    jQuery.each( jQuery( '[type="text"]' ), function ( index, val ) {
        jQuery( jQuery( '[type="text"]' )[ index ] ).attr( 'spellcheck', true );
    } );
    jQuery.each( jQuery( 'textarea' ), function ( index, val ) {
        jQuery( jQuery( 'textarea' )[ index ] ).attr( 'spellcheck', true );
    } );

    // Enable tinyMCE browser spellcheck
    if ( typeof tinyMCE != "undefined" ) {
        tinyMCE.init( {
            browser_spellcheck: true
        } );
    }

    function get_tinymce_content( id ) {

        tinyMCE.init( {
// General options
            mode: "specific_textareas",
            editor_selector: "mceEditor"
        } );

        return tinyMCE.get( id ).getContent();
    }

    function set_tinymce_content( id, content ) {

        tinyMCE.init( {
// General options
//mode: "specific_textareas",
//editor_selector: id
        } );
        tinyMCE.EditorManager.execCommand( 'mceFocus', false, id );
        tinyMCE.activeEditor.selection.setContent( content );
    }

    function set_tinymce_active_editor( id ) {
        tinyMCE.init( {
// General options
            mode: "specific_textareas",
            editor_selector: "mceEditor",
        } );
        //tinyMCE.setActive( id, true );
    }

    jQuery( '#enroll_type' ).change( function () {
        var enroll_type = jQuery( "#enroll_type" ).val();
        if ( enroll_type == 'passcode' ) {
            jQuery( "#enroll_type_holder" ).css( {
                'display': 'block'
            } );
        } else {
            jQuery( "#enroll_type_holder" ).css( {
                'display': 'none'
            } );
        }
    } );
    jQuery( '#enroll_type' ).change( function () {
        var enroll_type = jQuery( "#enroll_type" ).val();
        if ( enroll_type == 'prerequisite' ) {
            jQuery( "#enroll_type_prerequisite_holder" ).css( {
                'display': 'block'
            } );
        } else {
            jQuery( "#enroll_type_prerequisite_holder" ).css( {
                'display': 'none'
            } );
        }

        if ( enroll_type == 'manually' ) {
            jQuery( "#manually_added_holder" ).css( {
                'display': 'block'
            } );
        } else {
            jQuery( "#manually_added_holder" ).css( {
                'display': 'none'
            } );
        }
    } );

    var ct = 2;

    jQuery( document.body ).on( 'click', 'a.radio_new_link', function () {

        var unique_group_id = jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val();

        var r = '<tr><td><input class="radio_answer_check" type="radio" name="radio_input_module_radio_check_' + unique_group_id + '[]"><input class="radio_answer" type="text" name="radio_input_module_radio_answers_' + unique_group_id + '[]"></td><td><a class="radio_remove" onclick="jQuery( this ).parent().parent().remove();"><i class="fa fa-trash-o"></i></a></td></tr>';

        jQuery( this ).parent().find( ".ri_items" ).append( r );
        //jQuery( this ).parent().parent().parent().append( r );

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
    } );
    jQuery( document.body ).on( 'click', 'a.checkbox_new_link', function () {
        var unique_group_id = jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val();
        var r = '<tr><td><input class="checkbox_answer_check" type="checkbox" name="checkbox_input_module_checkbox_check_' + unique_group_id + '[]"><input class="checkbox_answer" type="text" name="checkbox_input_module_checkbox_answers_' + unique_group_id + '[]"></td><td><a class="checkbox_remove" onclick="jQuery( this ).parent().parent().remove();"><i class="fa fa-trash-o"></i></a></td></tr>';
        //jQuery( this ).parent().parent().parent().append( r );

        jQuery( this ).parent().find( ".ci_items" ).append( r );
        jQuery( "input[name*='checkbox_answers']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "checkbox_input_module_checkbox_answers[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );
        jQuery( "input[name*='checkbox_check']" ).each( function ( i, obj ) {
            jQuery( this ).attr( "name", "checkbox_input_module_checkbox_check[" + jQuery( this ).closest( ".module-content" ).find( '.module_order' ).val() + '][]' );
        } );
    } );

    jQuery( "#students_accordion" ).accordion( {
        heightStyle: "content",
        active: parseInt( fieldpress.active_student_tab )
    } );

    var current_stop_page = 0;
    current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();

    jQuery( '#stop-page-' + current_stop_page + ' .modules_accordion' ).show();
    jQuery( ".loading_elements" ).hide();
    jQuery( ".stop-pages-navigation" ).show();

    var editor_content = '';

//#stop-page-' + current_stop_page + ' .modules_accordion'

    jQuery( document.body ).on( 'mousedown', '.module-title', function () {
        var holder = jQuery( this ).parent();
        var iframe_id = jQuery( holder ).find( 'iframe' ).attr( 'id' );
        var iframe_content = document.getElementById( iframe_id ).contentWindow.document.body.innerHTML;
        global_iframe_content = iframe_content;
        //console.log( global_iframe_content );
    } );

    // Fix Accordion
    if ( $( '.modules_accordion' ).hasClass( 'ui-accordion' ) ) {
        $( '.modules_accordion' ).accordion( 'destroy' );
    }
    jQuery( '.modules_accordion' ).accordion( {
        heightStyle: "content",
        header: "> div >h3",
        collapsible: true,
        //active: ".remove_module_link"
    } ).sortable( {
        //items: "div:not(.notmovable)",
        handle: "h3",
        axis: "y",
        stop: function ( event, ui ) {
            //alert('test');

            current_stop_page = jQuery( '#stop-pages .ui-tabs-nav .ui-state-active a' ).html();
            update_sortable_module_indexes();
            //ui.draggable.attr( 'id' ) or ui.draggable.get( 0 ).id or ui.draggable[0].id
            //cp_repaint_current_page_editors();

            /* Dynamic WP Editor */
            var nth_child_num = ui.item.index() + 1;
            var editor_id = jQuery( "#stop-page-" + current_stop_page + " .module-holder-title:nth-child( " + nth_child_num + " ) .wp-editor-wrap" ).attr( 'id' );

            var initial_editor_id = editor_id;

            editor_id = editor_id.replace( "-wrap", "" );
            editor_id = editor_id.replace( "wp-", "" );

            var content = jQuery( '#' + editor_id ).html();
            var name = jQuery( '#' + editor_id ).attr( 'name' );

            $( '#' + initial_editor_id ).detach();
            try {
                delete tinyMCEPreInit.mceInit[ editor_id ];
                delete tinyMCEPreInit.qtInit[ editor_id ];
                delete tinyMCE.EditorManager.editors[ editor_id ];

                // Get rid of other redundancy
                $.each( tinyMCE.EditorManager.editors, function ( idx ) {
                    try {
                        var eid = tinyMCE.EditorManager.editors[ idx ].id;
                        if ( editor_id === eid ) {
                            delete tinyMCE.EditorManager.editors[ idx ];
                        }
                        ;
                    } catch ( ei ) {
                    }
                } );
            } catch ( e ) {
            }

            var id = editor_id;
            var text_editor_whole = '<textarea name="' + name + '"  class="fieldpress-editor" id="' + id + '"></textarea>';
            var editor = '#stop-page-' + current_stop_page + ' .modules_accordion .editor_in_place';
            editor = $( editor )[ ( nth_child_num - 1 ) ];
            var height = 300;

            $( editor ).html( text_editor_whole );

            FieldPress.editor.create( '#' + id, id, name, content, false, height );

            $( '[name="' + name + '"]' ).on( 'keyup', function ( object ) {
                // Fix Enter/Return key
                if ( 13 === object.keyCode ) {
                    $( this ).val( $( this ).val() + "\n" );
                }
                FieldPress.Events.trigger( 'editor:keyup', this );
            } );


        }
    }, function () {
        jQuery( 'a' ).click( function ( e ) {
            //e.stopPropagation();
        } )
    } ).on( 'click', 'a', function ( e ) {
        //e.stopPropagation();
    } )
    /*} );*/


    jQuery( '#open_ended_enrollment' ).change( function () {
        if ( this.checked ) {
            //jQuery( '#all_field_dates' ).hide( 500 );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date label' ).removeClass( 'required' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date label' ).removeClass( 'required' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date' ).addClass( 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date input' ).attr( 'disabled', 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date' ).addClass( 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date input' ).attr( 'disabled', 'disabled' );
        } else {
            //jQuery( '#all_field_dates' ).show( 500 );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date label' ).addClass( 'required' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date label' ).addClass( 'required' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date' ).removeClass( 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.start-date input' ).removeAttr( 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date' ).removeClass( 'disabled' );
            jQuery( this ).parents( '.enrollment-dates' ).find( '.end-date input' ).removeAttr( 'disabled' );
        }
    } );

    jQuery( '#open_ended_field' ).change( function () {
        if ( this.checked ) {
            jQuery( this ).parents( '.field-dates' ).find( '.end-date label' ).removeClass( 'required' );
            jQuery( this ).parents( '.field-dates' ).find( '.end-date' ).addClass( 'disabled' );
            jQuery( this ).parents( '.field-dates' ).find( '.end-date input' ).attr( 'disabled', 'disabled' );
        } else {
            jQuery( this ).parents( '.field-dates' ).find( '.end-date label' ).addClass( 'required' );
            jQuery( this ).parents( '.field-dates' ).find( '.end-date' ).removeClass( 'disabled' );
            jQuery( this ).parents( '.field-dates' ).find( '.end-date input' ).removeAttr( 'disabled' );
        }
    } );

    jQuery( '#limit_class_size' ).change( function () {
        if ( this.checked ) {
            jQuery( this ).parents( '.wide' ).find( '.limit-class-size-required' ).addClass( 'required' );
            jQuery( 'input.class_size' ).removeClass( 'disabled' );
            jQuery( 'input.class_size' ).removeAttr( 'disabled' );
        } else {
            jQuery( this ).parents( '.wide' ).find( '.limit-class-size-required' ).removeClass( 'required' );
            jQuery( 'input.class_size' ).addClass( 'disabled' );
            jQuery( 'input.class_size' ).attr( 'disabled', 'disabled' );
        }
    } );

    jQuery( '#paid_field' ).change( function () {
        toggle_payment_fields( jQuery( this ), jQuery( this ).is( ':checked' ) );
    } );

    jQuery( '#paid_field' ).siblings( 'span' ).click( function () {
        toggle_payment_fields( jQuery( '#paid_field' ), !jQuery( '#paid_field' ).is( ':checked' ) );
    } );

    jQuery( '.field-section #mp_is_sale' ).change( function () {
        if ( this.checked ) {
            jQuery( this ).parents( '.product' ).find( '.field-sale-price .price-label' ).addClass( 'required' );
        } else {
            jQuery( this ).parents( '.product' ).find( '.field-sale-price .price-label' ).removeClass( 'required' );
        }
    } );


} );

function toggle_payment_fields( element, bool ) {

    if ( bool ) {
        jQuery( element ).parents( '.product' ).find( '.field-sku input' ).removeClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price input' ).removeClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sale-price input' ).removeClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sku input' ).removeAttr( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price input' ).removeAttr( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sale-price input' ).removeAttr( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price .price-label' ).addClass( 'required' );
        jQuery( element ).parents( '.product' ).find( '.payment-gateway-required' ).addClass( 'required' );
        jQuery( element ).parents( '.product' ).find( '.field-paid-field-details' ).removeClass( 'hidden' );

        // jQuery('input.class_size').removeClass('disabled');
        // jQuery('input.class_size').removeAttr('disabled');
    } else {
        jQuery( element ).parents( '.product' ).find( '.field-sku input' ).addClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price input' ).addClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sale-price input' ).addClass( 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sku input' ).attr( 'disabled', 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price input' ).attr( 'disabled', 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-sale-price input' ).attr( 'disabled', 'disabled' );
        jQuery( element ).parents( '.product' ).find( '.field-price .price-label' ).removeClass( 'required' );
        jQuery( element ).parents( '.product' ).find( '.payment-gateway-required' ).removeClass( 'required' );
        jQuery( element ).parents( '.product' ).find( '.field-paid-field-details' ).addClass( 'hidden' );
        // jQuery( this ).parents('.wide').find('.limit-class-size-required').removeClass('required');
        //             jQuery('input.class_size').addClass('disabled');
        //             jQuery('input.class_size').attr('disabled', 'disabled');
    }

}

jQuery( document ).ready( function () {

    jQuery( '.featured_url_button' ).on( 'click', function () {
        var target_url_field = jQuery( this ).prevAll( ".featured_url:first" );

        wp.media.string.props = function ( props, attachment ) {
            //console.log(props);
            jQuery( target_url_field ).val( props.url );
            jQuery( '#thumbnail_id' ).val( '' );
            jQuery( '#featured_url_size' ).val( '' );

            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
        }

        wp.media.editor.send.attachment = function ( props, attachment ) {
            jQuery( target_url_field ).val( attachment.url );
            jQuery( '#thumbnail_id' ).val( attachment.id );
            jQuery( '#featured_url_size' ).val( props.size );

            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
        };


        wp.media.editor.open( this );
        return false;
    } );
} );

function radio_new_link( identifier ) {
    //( identifier );
    jQuery( '#r' + ct + 'td1' ).html( '<input class="radio_answer" type="text" name="radio_input_module_radio_answers[' + identifier + '][]" /><input class="radio_answer_check" type="radio" name="radio_input_module_radio_answers_check[' + identifier + '][]" />' );
    if ( ct >= 3 ) {
        jQuery( '#r' + ct + 'td4' ).html( '<a class="radio_remove" >' + fieldpress.remove_row + '</a>' ); //href="javascript:radio_removeElement( \'items\',\'r' + ct + '\' );"
    } else {
        jQuery( '#r' + ct + 'td4' ).html( '' );
    }
}

function radio_removeElement( parentDiv, childDiv ) {
    if ( childDiv == parentDiv ) {
    }
    else if ( document.getElementById( childDiv ) ) {
        var child = document.getElementById( childDiv );
        var parent = document.getElementById( parentDiv );
        parent.removeChild( child );
    }
    else {
    }
}


function radio_addRow( identifier ) {
    ct++;
    var r = document.createElement( 'tr' );
    r.setAttribute( 'id', 'r' + ct );
    var ca = document.createElement( 'td' );
    ca.setAttribute( 'id', 'r' + ct + 'td1' );
    var cd = document.createElement( 'td' );
    cd.setAttribute( 'id', 'r' + ct + 'td4' );
    //var t = document.getElementById( 'items' );

    r.appendChild( ca );
    r.appendChild( cd );
    //t.appendChild();
    //jQuery( "input[name='radio_input_module_radio_answers_"+identifier+"']" ).closest( ".ri-items" ).append( r );
    //alert( jQuery( "input[name='radio_input_module_radio_answers_" + identifier + "']" ).val() );
}

jQuery( 'a' ).on( 'click', function ( e ) {
    e.stopPropagation();
} );

jQuery( function () {
    if ( jQuery( window ).width() < 783 ) {
        jQuery( '.wp-editor-wrap .switch-tmce' ).click( function () {
            jQuery( this ).parents( '.wp-editor-wrap' ).find( '.mce-toolbar-grp' ).toggle();
            jQuery( this ).parents( '.wp-editor-wrap' ).find( '.quicktags-toolbar' ).hide();
        } );
        jQuery( '.wp-editor-wrap .switch-html' ).click( function () {
            jQuery( this ).parents( '.wp-editor-wrap' ).find( '.quicktags-toolbar' ).toggle();
            jQuery( this ).parents( '.wp-editor-wrap' ).find( '.mce-toolbar-grp' ).hide();
        } );
    }

    if ( jQuery( window ).width() < 783 ) {
        jQuery( '.sticky-slider' ).click( function () {
            if ( jQuery( this ).hasClass( 'slider-open' ) ) {
                jQuery( this ).parent().animate( { left: "-235px" }, 500 );
                jQuery( this ).parent().siblings( '.mp-settings' ).animate( { left: "32px" }, 500 );
                jQuery( this ).removeClass( 'slider-open' );
            } else {
                jQuery( this ).parent().animate( { left: "-11px" }, 500 );
                jQuery( this ).parent().siblings( '.mp-settings' ).animate( { left: "258px" }, 500 );
                jQuery( this ).addClass( 'slider-open' );
            }
        } );
    }

    if ( jQuery( window ).width() < 556 ) {
        jQuery( '.fieldpress_page_instructors div.field-liquid-right' ).after( jQuery( '.fieldpress_page_instructors div.field-liquid-left' ) );
    }

    if ( jQuery( window ).width() >= 556 ) {
        jQuery( '.fieldpress_page_instructors div.field-liquid-left' ).after( jQuery( '.fieldpress_page_instructors div.field-liquid-right' ) );
    }

} );

function cp_is_extension_allowed( filename, type ) {
    type = jQuery( type ).attr( 'class' ).split( ' ' )[ 0 ];
    var extension = filename.split( '.' ).pop();
    var audio_extensions = fieldpress.allowed_audio_extensions;
    var video_extensions = fieldpress.allowed_video_extensions;
    var image_extensions = fieldpress.allowed_image_extensions;

    if ( type == 'featured_url' ) {
        type = 'image_url';
    }

    if ( type == 'field_video_url' ) {
        type = 'video_url';
    }

    if ( type == 'audio_url' ) {
        if ( cp_is_value_in_array( extension, audio_extensions ) ) {
            return true;
        } else {
            if ( cp_is_valid_url( filename ) && extension.length > 5 ) {
                return true;
            } else {
                if ( filename.length == 0 ) {
                    return true;
                }
                return false;
            }
        }
    }

    if ( type == 'video_url' ) {
        if ( cp_is_value_in_array( extension, video_extensions ) ) {
            return true;
        } else {
            if ( cp_is_valid_url( filename ) && extension.length > 5 ) {
                return true;
            } else {
                if ( filename.length == 0 ) {
                    return true;
                }
                return false;
            }
        }
    }

    if ( type == 'image_url' ) {
        if ( cp_is_value_in_array( extension, image_extensions ) ) {
            return true;
        } else {
            if ( cp_is_valid_url( filename ) && extension.length > 5 ) {
                return true;
            } else {
                if ( filename.length == 0 ) {
                    return true;
                }
                return false;
            }
        }
    }
}


function cp_is_valid_url( str ) {
    if ( str.indexOf( "http://" ) > -1 || str.indexOf( "https://" ) > -1 ) {
        return true;
    } else {
        return false;
    }
}

function cp_is_value_in_array( value, array ) {
    return array.indexOf( value ) > -1;
}

jQuery( function ( $ ) {
    $( 'input.module_preview' ).on( 'change', function () {
        if ( $( this ).attr( 'checked' ) ) {
            $( "input[name*='meta_preview_page[" + $( this ).data( 'id' ) + "_']" ).each( function ( i, obj ) {
                $( obj ).attr( 'checked', true );
                $( obj ).attr( 'disabled', true );
            } );
        } else {
            $( "input[name*='meta_preview_page[" + $( this ).data( 'id' ) + "_']" ).each( function ( i, obj ) {
                $( obj ).attr( 'checked', false );
                $( obj ).attr( 'disabled', false );
            } );
        }
    } );
} );


jQuery( document ).ready( function ( $ ) {

    function update_field_sortable_indexes() {

        jQuery( '.field_order' ).each( function ( i, obj ) {
            jQuery( this ).val( i + 1 );
        } );

        var positions = new Array();

        jQuery( '.field_id' ).each( function ( i, obj ) {
            positions[ i ] = jQuery( this ).val();
        } );

        var data = {
            action: 'update_field_positions',
            positions: positions.toString(),
            field_page_number: jQuery( '#field_page_number' ).val()
        };

        jQuery.post( ajaxurl, data, function ( response ) {
            //alert(response);
        } );

    }

    jQuery( ".field-rows" ).sortable( {
        placeholder: "field-row-ui-state-highlight",
        items: "tr.field-row",
        stop: function ( event, ui ) {
            update_field_sortable_indexes();
        }
    } );

    function prepare_element_to_delete( module_to_execute_id ) {
        jQuery( '<input>' ).attr( {
            type: 'hidden',
            name: 'modules_to_execute[]',
            value: module_to_execute_id
        } ).appendTo( '#stop-add' );
    }

    update_stop_page_order_and_numbers();
    /*
     jQuery( "#stop-pages ul" ).sortable( {
     placeholder: "stop-page-placeholder",
     //items: "",
     items: "li:not( .add_new_stop_page, .stop-pages-title )",
     activate: function( event, ui ) {
     //alert( 'received!' );
     jQuery( '.stop-pages-navigation' ).css( 'opacity', '0.7' );
     },
     update: function( event, ui ) {
     update_stop_page_order_and_numbers();
     cp_repaint_all_editors();
     jQuery( '.stop-pages-navigation' ).css( 'opacity', '1' );
     //update_sortable_module_indexes_page_sort();
     },
     stop: function( event, ui ) {
     jQuery( '.stop-pages-navigation' ).css( 'opacity', '1' );
     }
     } );
     */

    /*
     Certificate Background Image
     */
    jQuery( '.certificate_background_button' ).on( 'click', function () {
        var target_url_field = jQuery( this ).prevAll( ".certificate_background_url:first" );
        wp.media.string.props = function ( props, attachment ) {
            jQuery( target_url_field ).val( props.url );

            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
        }

        wp.media.editor.send.attachment = function ( props, attachment ) {
            jQuery( target_url_field ).val( attachment.url );
            if ( cp_is_extension_allowed( attachment.url, target_url_field ) ) {//extension is allowed
                $( target_url_field ).removeClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).hide();
            } else {//extension is not allowed
                $( target_url_field ).addClass( 'invalid_extension_field' );
                $( target_url_field ).parent().find( '.invalid_extension_message' ).show();
            }
        };

        wp.media.editor.open( this );
        return false;
    } );

} );