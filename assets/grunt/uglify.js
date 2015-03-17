module.exports = {

	options: {
		compress: true
	},

	dist: {
		files: {
			'js/min/built.min.js': 'js/dev/built.js'
		}
	},
};