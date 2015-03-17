// We use legacssy to strip mediaqueries for old browsers.
// NOTE: for this to work styles MUST be structured mobile-first,
// with media query blocks always ordered from narrower to wider
module.exports = {

	options: {
		matchingOnly: false
	},

	dist: {
		files: {
			'styles/css/app-no-mq.css': 'styles/css/app-px.css',
		}
	},
};