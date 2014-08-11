'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {
    // show elapsed time at the end
    require('time-grunt')(grunt);
    // load all grunt tasks
    require('load-grunt-tasks')(grunt);
    grunt.loadTasks('tasks');

    var directoriesConfig = {
        composer: 'vendor',
        composerBin: 'vendor/bin',
        reports: 'logs',
        php: 'app'
    };

    grunt.initConfig({
        directories: directoriesConfig,
        
	phpmd: {
          application: {
                dir: 'gitlogparser.php'
          },
            options: {
                bin: 'vendor/bin/phpmd',
                rulesets: 'codesize'
               }
           }    
        ,
	phpcs: {
    application: {
        dir: ['gitlogparser.php','gitlog.php']
    },
    options: {
       bin: 'vendor/bin/phpcs',
       standard: 'PSR1'
    }
	}

    });

    grunt.registerTask('default', [
        'test'
    ]);

    grunt.registerTask('test', [
	'phpmd',
	'phpcs'
    ]);

};
