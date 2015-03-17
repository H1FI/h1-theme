module.exports = {

	options: {
		sourceMap: true,
		outputStyle: 'compressed'
	},

	dist: {
		files: {
			'styles/css/app.css' : 'styles/scss/app.scss'
		}
	},
};