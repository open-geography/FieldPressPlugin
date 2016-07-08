function delete_student_confirmed() {
    return confirm(student.delete_student_alert);
}

function removeStudent() {
    if (delete_student_confirmed()) {
        return true;
    } else {
        return false;
    }
}

jQuery(function() {
    // bind change event to select
    jQuery('#dynamic_fields').bind('change', function() {
        jQuery('#dynamic_classes').val('all');
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

    jQuery('#stops_accordion').accordion({
        heightStyle: "content"
    });
});