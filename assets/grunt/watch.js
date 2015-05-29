module.exports = {

	options: {
		spawn: false,
		livereload: true
	},

	scripts: {
		files: [
			'js/dev/**/*.js'
		],
		tasks: [
			'concat', 'uglify'
		]
	},

	styles: {
		files: [
			'styles/scss/**/*.scss'
		],
		tasks: [
			'sass'
		]
	},
}