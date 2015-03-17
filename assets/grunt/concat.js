module.exports = {

	options: {
	  separator: ';',
	},

	dist: {
		src: [
			// 'bower_components/foundation/js/foundation/foundation.js',
			// 'bower_components/foundation/js/foundation/foundation.dropdown.js'
			'js/dev/wp/navigation.js',
			'js/dev/wp/skip-link-focus.js',
			'js/dev/app.js'
			],
		dest: 'js/dev/built.js',
	},
};