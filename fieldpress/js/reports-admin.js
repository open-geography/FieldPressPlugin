jQuery(function() {
    // bind change event to select
    jQuery('#dynamic_fields').bind('change', function() {
        jQuery('#dynamic_classes').val('all');
        jQuery("#field-filter").submit();
    });

    jQuery('#dynamic_classes').bind('change', function() {
        jQuery("#field-filter").submit();
    });
    
    jQuery('.pdf').click(function(){
        jQuery(".check-column input").prop('checked', false);
        jQuery(this).closest('tr').find(".check-column input").prop('checked', true);
        jQuery("#generate-report").submit();
    });
    

});