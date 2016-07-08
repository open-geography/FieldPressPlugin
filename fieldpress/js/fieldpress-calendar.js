
function update_calendar( date, field_calendar ) {

    $ = jQuery;

    $.post(
        wpajaxurl, // declared by class.fieldcalendar
        {
            action: 'refresh_field_calendar',
            field_id: $( field_calendar ).data( 'fieldid' ),
            date: date,
        }
    ).done( function( data, status ) {

        // Set a field_id if its still empty
        var response = $.parseJSON( $( data ).find( 'response_data' ).text() );
        html = $.parseHTML( response.calendar );
        // console.log( field_calendar );
        $( field_calendar ).find( '.field-calendar-body' ).replaceWith( $( html ).find( '.field-calendar-body' ) );

        if ( $( html ).find( '.pre-month' ).data( 'date' ) == 'empty' ) {
            $( field_calendar ).find( '.pre-month' ).hide();
        } else {
            $( field_calendar ).find( '.pre-month' ).show();
        }

        if ( $( html ).find( '.next-month' ).data( 'date' ) == 'empty' ) {
            $( field_calendar ).find( '.next-month' ).hide();
        } else {
            $( field_calendar ).find( '.next-month' ).show();
        }

        $( field_calendar ).find( '.pre-month' ).data( 'date', $( html ).find( '.pre-month' ).data( 'date' ) );
        $( field_calendar ).find( '.next-month' ).data( 'date', $( html ).find( '.next-month' ).data( 'date' ) );

    } ).fail( function( data ) {
    } );



}



jQuery( document ).ready( function( $ ) {

    if ( $( '.pre-month' ).data( 'date' ) == 'empty' ) {
        $( '.pre-month' ).hide();
    }

    if ( $( '.next-month' ).data( 'date' ) == 'empty' ) {
        $( '.next-month' ).hide();
    }

    $( document.body ).on( 'click', '.field-calendar .pre-month', function( event ) {
        event.stopPropagation();
        update_calendar( $( this ).data( 'date' ), $( this ).parents( '.field-calendar' )[0] );
    } );

    $( document.body ).on( 'click', '.field-calendar .next-month', function( event ) {
        event.stopPropagation();
        update_calendar( $( this ).data( 'date' ), $( this ).parents( '.field-calendar' )[0] );
    } );

} );