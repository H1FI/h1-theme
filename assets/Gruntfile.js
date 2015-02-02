module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Uncomment foundation js files and add more foundation modules and any other JS as necessary
		concat: {
			options: {
			  separator: ';',
			},
			dist: {
			  src: [
			  	// 'bower_components/foundation/js/foundation/foundation.js',
			  	// 'bower_components/foundation/js/foundation/foundation.dropdown.js'
			  	'js/dev/wp/*',
			  	'js/dev/app.js'
			  	],
			  dest: 'js/dev/built.js',
			},
		},

		// SASS compile
		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'compressed'
			},
			dist: {
				files: {
					'styles/css/app.css' : 'styles/scss/app.scss'
				}
			}
		},

		// We use pixrem to generate a css version for browsers that do not support rem
		pixrem: {
			options: {
			  rootvalue: '16px', // set this to whatever you have as the base size in _settings.scss
			  replace: true // setting to false will simply add px values in addition to rem
			},
			dist: {
			  src: 'styles/css/app.css',
			  dest: 'styles/css/app-px.css'
			}
		},

		// We use legacssy to strip mediaqueries for old browsers.
		// NOTE: for this to work styles MUST be structured mobile-first,
		// with media query blocks always ordered from narrower to wider
		legacssy: {
			dist: {
				options: {
					matchingOnly: false
				},
				files: {
					'styles/css/app-no-mq.css': 'styles/css/app-px.css',
				},
			}
		},

		// Minify for production
		uglify: {
			dist: {
				options: {
					compress: true
				},
				files: {
					'js/min/built.min.js': 'js/dev/built.js'
				}
			},
		},

		// Browser prefixes
		autoprefixer: {
			options: {
				// Task-specific options go here.
				browsers: ['> 1%', 'last 4 versions', 'Firefox ESR', 'Opera 12.1']
			},
			dist: {
				src: 'styles/css/app.css',
				dest: 'styles/css/app.css'
			}
		},

		// Watch scss and js files for chagne
		watch: {
			files: ['styles/scss/**/*', 'js/dev/**/*'],
			tasks: 'dev'
		}
	});

	// Load plugin(s) needed for task(s)
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-legacssy');
	grunt.loadNpmTasks('grunt-pixrem');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-sass');

	// Register task(s)
	grunt.registerTask( 'default', [ 'concat:dist', 'sass:dist', 'autoprefixer', 'pixrem', 'legacssy', 'uglify:dist' ] );
	grunt.registerTask( 'dev', [ 'concat:dist', 'sass:dist', 'autoprefixer'] );

};
