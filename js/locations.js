// simple redirect

if ('undefined' == typeof mash_location) {
	var mash_location = { url: '' };
}
window.location.replace(mash_location.url);
