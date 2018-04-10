/*
 * Steps for setup
 * 1- sudo npm install
 * 2- grunt build
 * 3- grunt watch
 */
module.exports = function(grunt) {

	var CONFIG = {
		///////////////////
		// Theme scripts //
		///////////////////
		scripts: ['./assets/js/**.js', '!./assets/js/**.min.js'],

		/////////////
		//Minimize //
		/////////////
		uglify: {
			on: 1,
			drop_console: false,
			files: {
			  './assets/js/scripts.min.js': ['./assets/js/scripts.js']  // Dest : src
			}
		},

		//////////////////////////
		//Combine Media Queries //
		//////////////////////////
		cmq: {
			on: 1,
			log: false,
			files: {
				'./assets/css/style.css': ['./assets/css/style.css']  // Dest : src
			}
		},

		//////////////////
		// Autoprefixer //
		//////////////////
		autoprefixer: {
			on: 1,
			browsers: ['last 2 versions', 'ie 9', 'ios 6', 'android 4'],
            map: false,
            files: {
                expand: true,
                flatten: true,
                src: './assets/css/style.css',
                dest: './assets/css'
            }
		},

		////////////
		//Compass //
		////////////
		compass: {
			on: 1,
			files: ['./scss/*.scss','./scss/partials/*.scss'],
			sassDir		: './scss',
			imagesDir   : './assets/img',
	    	cssDir		: './assets/css',
	    	sourcemap	: false,
	    	outputStyle	: 'expanded',
	    	relativeAssets: true
		}

	};

/*
  _           
 | |__   _  _ 
 | '_ \ | || |
 |_.__/  \_, |
         |__/ 
 .----------------. .----------------. .-----------------..----------------. .----------------. .----------------. 
| .--------------. | .--------------. | .--------------. | .--------------. | .--------------. | .--------------. |
| |   _____      | | |  _________   | | | ____  _____  | | |   _____      | | |      __      | | |  ____  ____  | |
| |  |_   _|     | | | |_   ___  |  | | ||_   \|_   _| | | |  |_   _|     | | |     /  \     | | | |_  _||_  _| | |
| |    | |       | | |   | |_  \_|  | | |  |   \ | |   | | |    | |       | | |    / /\ \    | | |   \ \  / /   | |
| |    | |   _   | | |   |  _|  _   | | |  | |\ \| |   | | |    | |   _   | | |   / ____ \   | | |    \ \/ /    | |
| |   _| |__/ |  | | |  _| |___/ |  | | | _| |_\   |_  | | |   _| |__/ |  | | | _/ /    \ \_ | | |    _|  |_    | |
| |  |________|  | | | |_________|  | | ||_____|\____| | | |  |________|  | | ||____|  |____|| | |   |______|   | |
| |              | | |              | | |              | | |              | | |              | | |              | |
| '--------------' | '--------------' | '--------------' | '--------------' | '--------------' | '--------------' |
 '----------------' '----------------' '----------------' '----------------' '----------------' '----------------' 
*/
/***********************************************/
/*Init config**********************************/
/*********************************************/
	var scriptsTasks = [];
	var buildTasks = [];

	var gruntConfig = {
		watch: {
			scss: {
				files: CONFIG.compass.files,
				tasks: []
			},
			scripts: {
				files: CONFIG.scripts,
				tasks: []
			}
		}
	};

	if(CONFIG.cmq.on){
		gruntConfig.watch.scss.tasks.push('compass');

		gruntConfig.compass = {
		    dist: {
		  		options: {
			    	sassDir: CONFIG.compass.sassDir,
			    	cssDir: CONFIG.compass.cssDir,
			    	imagesDir: CONFIG.compass.imagesDir,
			    	sourcemap: CONFIG.compass.sourcemap,
			    	outputStyle: CONFIG.compass.outputStyle,
			    	relativeAssets: CONFIG.compass.relativeAssets
			  	}
			}
		};
		grunt.loadNpmTasks('grunt-contrib-compass');
	}

	if(CONFIG.cmq.on){
		gruntConfig.watch.scss.tasks.push('cmq');

		gruntConfig.cmq = {
			options: {
				log: CONFIG.cmq.log
			},
			media_queries: {
				files: CONFIG.cmq.files
			}
		};
		grunt.loadNpmTasks('grunt-combine-media-queries');
	}

	if(CONFIG.autoprefixer.on){
		gruntConfig.watch.scss.tasks.push('autoprefixer');

		gruntConfig.autoprefixer = {
			options: {
				browsers: CONFIG.autoprefixer.browsers,
                map: CONFIG.autoprefixer.map
			},
			files: CONFIG.autoprefixer.files
		};
		grunt.loadNpmTasks('grunt-autoprefixer');
	}

	if(CONFIG.uglify.on){
		gruntConfig.watch.scripts.tasks.push('uglify');

		gruntConfig.uglify = {
			options: {
				compress: {
					drop_console: CONFIG.uglify.drop_console
				}
			},
			dist: {
				files: CONFIG.uglify.files
			}
		};
		grunt.loadNpmTasks('grunt-contrib-uglify');
	}

	// INIT
	grunt.initConfig(gruntConfig);

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-notify');


	grunt.registerTask('default', ['watch']);

};