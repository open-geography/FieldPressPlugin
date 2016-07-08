jQuery(document).ready(function($) {
    jQuery('#tabs').tabs({
        //event: "mouseover" /*remove this if you want to open tabs on click */
    });
});

jQuery(function() {
    // bind change event to select
    jQuery('#dynamic_fields').bind('change', function() {
        jQuery('#dynamic_classes').val('all');
        jQuery("#field-filter").submit();
    });

    jQuery('#dynamic_classes').bind('change', function() {
        jQuery("#field-filter").submit();
    });


    jQuery('#ungraded').bind('change', function() {
        if (jQuery('#ungraded').is(':checked')) {
            jQuery('#ungraded').val('yes');
        } else {
            jQuery('#ungraded').val('no');
        }
        jQuery("#field-filter").submit();
    });

});