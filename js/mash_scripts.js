function mash_filter_form(type) {
	if (type === 'All') {
		jQuery('#s_pages').hide();
		jQuery('#s_posts').hide();
		jQuery('#ex_pages').show();
		jQuery('#ex_posts').show();
	} else if (type === 's_pages') {
		jQuery('#s_pages').show();
		jQuery('#s_posts').show();
		jQuery('#ex_pages').hide();
		jQuery('#ex_posts').hide();
	}
}

jQuery(function () {
	jQuery('select').selectize({
		plugins: ['remove_button'],
	});
});
