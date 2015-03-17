// We use pixrem to generate a css version for browsers that do not support rem
module.exports = {

	options: {
		rootvalue: '16px', // set this to whatever you have as the base size in _settings.scss
		replace: true // setting to false will simply add px values in addition to rem
	},

	dist: {
		src: 'styles/css/app.css',
		dest: 'styles/css/app-px.css'
	},
};