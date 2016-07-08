var config = {
    '.chosen-select': {},
    '.chosen-select-student': {width:"20%"},
    '.chosen-select-field': { width:"61.5%"},
}
for (var selector in config) {
    jQuery(selector).chosen(config[selector]);
}