module.exports = function (grunt) {

    // Loading the auto-loader

    require('load-grunt-tasks')(grunt);

    // Configuring tasks

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        project: {
            host: "http://soron.dev",
            dist: 'assets',
            src:  'src',
            css: {
                src: '<%= project.src %>/less',
                dist: '<%= project.dist %>/css'
            },
            js: {
                src: '<%= project.src %>/js',
                dist: '<%= project.dist %>/js'
            }
        },

        less: {
            dist: {
                files: {
                    '<%= project.css.dist %>/xs.css': '<%= project.css.src %>/xs.less',
                    '<%= project.css.dist %>/fx.css': '<%= project.css.src %>/fx.less'
                }
            }
        },

        uncss: {
            options: {
                ignore: ['.js']
            },
            xs: {
                files: {
                    '<%= project.css.dist %>/xs.css': ['<%= project.host %>/?uncss=assets.css.xs']
                }
            },
            fx: {
                media: ["print", "screen"],
                files: {
                    '<%= project.css.dist %>/fx.css': ['<%= project.host %>/?uncss=assets.css.fx']
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 5 versions', 'ie 9', '> 1%']
            },
            files: {
                '<%= project.css.dist %>/xs.css': '<%= project.css.dist %>/xs.css',
                '<%= project.css.dist %>/fx.css': '<%= project.css.dist %>/fx.css'
            }
        },

        cssmin: {
            options: {
                roundingPrecision: 3
            },
            dist: {
                files: {
                    '<%= project.css.dist %>/xs.css': '<%= project.css.dist %>/xs.css',
                    '<%= project.css.dist %>/fx.css': '<%= project.css.dist %>/fx.css'
                }
            }
        },

        closurecompiler: {
            dist: {
                options: {
                    compilation_level: 'SIMPLE_OPTIMIZATIONS',
                    max_processes: 5,
                    language_in: 'ECMASCRIPT5_STRICT',
                    warning_level: 'QUIET',
                    charset: 'UTF-8',
                    source_map_format: 'V3'
                },
                files: {
                    '<%= project.js.dist %>/app.js': '<%= project.js.src %>/app.js',
                    '<%= project.js.dist %>/app-inline.js': '<%= project.js.src %>/app-inline.js'
                }
            }
        },

        watch: {
            options: {
                livereload: true
            },
            css: {
                files: ['<%= project.css.src %>/**/*.less'],
                tasks: ['less']
            },
            js: {
                files: ['<%= project.js.src %>/**/*.js'],
                tasks: ['compile:js']
            }
        }
    });

    grunt.registerTask('compile:css', [
        'less',
        'uncss',
        'autoprefixer',
        'cssmin'
    ]);
    grunt.registerTask('compile:js', [
        'closurecompiler'
    ]);
    grunt.registerTask('compile', [
        'compile:css',
        'compile:js'
    ]);
    grunt.registerTask('default', [
        'compile',
        'watch'
    ]);
};
