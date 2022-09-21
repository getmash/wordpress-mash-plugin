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

function mash_boosts_form_display_control(type) {
	if (type === 'None') {
		jQuery(`.boosts_exclude_picker`).hide();
		jQuery(`.boosts_includes_picker`).hide();
	} else if (type === 'All') {
		jQuery(`.boosts_exclude_picker`).show();
		jQuery(`.boosts_includes_picker`).hide();
	} else {
		jQuery(`.boosts_exclude_picker`).hide();
		jQuery(`.boosts_includes_picker`).show();
	}
}
