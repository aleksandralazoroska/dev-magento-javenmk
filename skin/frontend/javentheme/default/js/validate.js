Validation.add('validate-email', 'Please enter a valid Gmail address. For example johndoe@gmail.com.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^([a-zA-Z0-9]+[a-zA-Z0-9._%-]*@gmail\.com)$/i.test(v)
});
Validation.add('validate-phone', 'Please enter a valid phone number. For example (123) 456-7890 or 123-456-7890.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}$/.test(v);
});
Validation.add('validate-url', 'Please enter a valid URL', function(v) {
    v = (v || '').replace(/^\s+/, '').replace(/\s+$/, '');
    return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?$/i.test(v)
});
