// AdminLTE Gruntfile
module.exports = function (grunt) {

  'use strict';

  grunt.initConfig({
    watch: {
      // If any .less file changes in directory "build/less/" run the "less"-task.
      files: [
            "build/less/*.less",
            "build/less/skins/*.less",
            "web/js/app.js",
            "web/js/*.js",
            "web/css/*.css"
        ],
      tasks: ["less", "uglify", "concat", "concat_css"]
    },
    // "less"-task configuration
    // This task will compile all less files upon saving to create both AdminLTE.css and AdminLTE.min.css
    less: {
      // Development not compressed
      development: {
        options: {
          // Whether to compress or not
          compress: false
        },
        files: {
          // compilation.css  :  source.less
          "web/css/AdminLTE.css": "build/less/AdminLTE.less",
          //Non minified skin files
          "web/css/skins/skin-blue.css": "build/less/skins/skin-blue.less",
          "web/css/skins/skin-black.css": "build/less/skins/skin-black.less",
          "web/css/skins/skin-yellow.css": "build/less/skins/skin-yellow.less",
          "web/css/skins/skin-green.css": "build/less/skins/skin-green.less",
          "web/css/skins/skin-red.css": "build/less/skins/skin-red.less",
          "web/css/skins/skin-purple.css": "build/less/skins/skin-purple.less",
          "web/css/skins/skin-blue-light.css": "build/less/skins/skin-blue-light.less",
          "web/css/skins/skin-black-light.css": "build/less/skins/skin-black-light.less",
          "web/css/skins/skin-yellow-light.css": "build/less/skins/skin-yellow-light.less",
          "web/css/skins/skin-green-light.css": "build/less/skins/skin-green-light.less",
          "web/css/skins/skin-red-light.css": "build/less/skins/skin-red-light.less",
          "web/css/skins/skin-purple-light.css": "build/less/skins/skin-purple-light.less",
          "web/css/skins/_all-skins.css": "build/less/skins/_all-skins.less",
          "web/css/main.css": "build/less/main.less",
        }
      },
      // Production compresses version
      production: {
        options: {
          // Whether to compress or not
          compress: true
        },
        files: {
          // compilation.css  :  source.less
          "web/css/AdminLTE.min.css": "build/less/AdminLTE.less",
          // Skins minified
          "web/css/skins/skin-blue.min.css": "build/less/skins/skin-blue.less",
          "web/css/skins/skin-black.min.css": "build/less/skins/skin-black.less",
          "web/css/skins/skin-yellow.min.css": "build/less/skins/skin-yellow.less",
          "web/css/skins/skin-green.min.css": "build/less/skins/skin-green.less",
          "web/css/skins/skin-red.min.css": "build/less/skins/skin-red.less",
          "web/css/skins/skin-purple.min.css": "build/less/skins/skin-purple.less",
          "web/css/skins/skin-blue-light.min.css": "build/less/skins/skin-blue-light.less",
          "web/css/skins/skin-black-light.min.css": "build/less/skins/skin-black-light.less",
          "web/css/skins/skin-yellow-light.min.css": "build/less/skins/skin-yellow-light.less",
          "web/css/skins/skin-green-light.min.css": "build/less/skins/skin-green-light.less",
          "web/css/skins/skin-red-light.min.css": "build/less/skins/skin-red-light.less",
          "web/css/skins/skin-purple-light.min.css": "build/less/skins/skin-purple-light.less",
          "web/css/skins/_all-skins.min.css": "build/less/skins/_all-skins.less",
          "web/css/main.min.css": "build/less/main.less"
        }
      }
    },
    // Uglify task info. Compress the js files.
    uglify: {
      options: {
        mangle: true,
        preserveComments: 'some'
      },
      my_target: {
        files: {
          'web/js/app.min.js': ['web/js/app.js'],
          'web/js/tipo_puesto_click.min.js': ['web/js/tipo_puesto_click.js'],
          'web/js/live-counter.min.js': ['web/js/live-counter.js'],
          'web/js/tab_register.min.js': ['web/js/tab_register.js'],
          'web/js/alertify.min.js': ['web/js/alertify.js'],
          'web/js/ajaxAprobacionHoras.min.js': ['web/js/ajaxAprobacionHoras.js'],
          'web/js/ajaxCodigo.min.js': ['web/js/ajaxCodigo.js'],
          'web/js/dateModifier.min.js': ['web/js/dateModifier.js'],
          'web/js/showHideElements.min.js': ['web/js/showHideElements.js'],
          'web/js/ajaxContacto.min.js': ['web/js/ajaxContacto.js'],
          'web/js/ajaxPuesto.min.js':['web/js/ajaxPuesto.js'],
          'web/js/bc-bootstrap-collection.min.js': ['web/js/bc-bootstrap-collection.js','web/js/datatables-default.js'],
          'web/js/jquery.initialize.min.js': ['web/js/jquery.initialize.js'],
          'web/js/register.min.js': ['web/js/register.js'],
          'web/js/dashboard.min.js': ['web/js/dashboard.js']

        }
      }
    },
    // Build the documentation files
    includes: {
      build: {
        src: ['*.html'], // Source files
        dest: 'documentation/', // Destination directory
        flatten: true,
        cwd: 'documentation/build',
        options: {
          silent: true,
          includePath: 'documentation/build/include'
        }
      }
    },

    // Optimize images
    image: {
      dynamic: {
        files: [{
          expand: true,
          cwd: 'build/img/',
          src: ['**/*.{png,jpg,gif,svg,jpeg}'],
          dest: 'dist/img/'
        }]
      }
    },

    // Validate JS code
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      core: {
        src: 'web/js/app.js'
      },
      demo: {
        src: 'web/js/demo.js'
      },
      pages: {
        src: 'web/js/pages/*.js'
      },
      rest: {
        src: 'web/js/*.js'
      }
    },

    // Validate CSS files
    csslint: {
      options: {
        csslintrc: 'build/less/.csslintrc'
      },
      dist: [
        'dist/css/AdminLTE.css',
      ]
    },

    // Validate Bootstrap HTML
    bootlint: {
      options: {
        relaxerror: ['W005']
      },
      files: ['pages/**/*.html', '*.html']
    },

    // Delete images in build directory
    // After compressing the images in the build/img dir, there is no need
    // for them
    clean: {
      build: ["build/img/*"]
    },
    cssmin: {
      target: {
        files: [{
          expand: true,
          cwd: 'web/css',
          src: [
            'sweetalert.css',
            'animation.css',
            ],
          dest: 'web/css',
          ext: '.min.css'
        }]
      }
    },
    concat: {
        options: {
          separator: ';',
        },
        dist: {
          src: [
              'web/js/app.min.js', 
              'web/js/sweetalert.min.js',
              'web/js/bootstrap.min.js',
              'web/js/tab_register.min.js',
              'web/js/alertify.min.js',
              'web/js/bc-bootstrap-collection.min.js',
              'web/js/jquery.initialize.min.js',
              'web/js/select2.min.js',
              'web/js/dashboard.min.js'

          ],
          dest: 'web/js/built.js',
        },
    },
    concat_css: {
        options: {
          // Task-specific options go here. 
        },
        all: {
            src: [
                "web/css/bootstrap.min.css",
                "web/css/AdminLTE.min.css",
                "web/css/main.min.css",
                "web/css/select2-bootstrap.min.css",
                "web/css/animation.min.css",
                "web/css/sweetalert.min.css",
                "web/css/select2.min.css",
                "web/css/skins/_all-skins.min.css"
            ],
           dest: "web/css/main-styles.css"
                
        },
    },
  });

  // Load all grunt tasks

  // LESS Compiler
  grunt.loadNpmTasks('grunt-contrib-less');
  // Watch File Changes
  grunt.loadNpmTasks('grunt-contrib-watch');
  // Compress JS Files
  grunt.loadNpmTasks('grunt-contrib-uglify');
  // Include Files Within HTML
  grunt.loadNpmTasks('grunt-includes');
  // Optimize images
  grunt.loadNpmTasks('grunt-image');
  // Validate JS code
  grunt.loadNpmTasks('grunt-contrib-jshint');
  // Delete not needed files
  grunt.loadNpmTasks('grunt-contrib-clean');
  // Lint CSS
  grunt.loadNpmTasks('grunt-contrib-csslint');
  // Lint Bootstrap
  grunt.loadNpmTasks('grunt-bootlint');
  //minify CSS
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  //concat CSS files
  grunt.loadNpmTasks('grunt-concat-css');
  //concat JS files
  grunt.loadNpmTasks('grunt-contrib-concat');
  // Linting task
  grunt.registerTask('lint', ['jshint', 'csslint', 'bootlint']);

  // The default task (running "grunt" in console) is "watch"
  grunt.registerTask('default', ['watch']);
};
