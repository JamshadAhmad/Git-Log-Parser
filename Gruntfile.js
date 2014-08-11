'use strict';

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
           },
	 phpcpd: {
	    application: {
	      dir: '*.php'
	    },
	    options: {
	      bin: 'vendor/bin/phpcpd',
	      quiet: true
	    }
	  }
        ,
	shell: {                                
        listFolders: {                      // Target directory
            options: {                     
                stderr: false
            },
            command: 'php phpdcd.phar *.php' // to run php dcd through shell 
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
	},
	phpdocumentor: {
    dist: {
      options: {
        directory : './',
        target : 'docs'
      }
    }
  }

    });

    grunt.registerTask('default', [
        'test'
    ]);

    grunt.registerTask('test', [
	'phpdocumentor',	
	'phpmd',
	'phpcs',
	'phpcpd',
	'shell'
    ]);

};
