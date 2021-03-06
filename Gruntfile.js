'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');

    grunt.initConfig({
        // Generate POT files.
        makepot: {
            target: {
                options: {
                    exclude: ['build/.*'],
                    domainPath: '/languages/', // Where to save the POT file.
                    potFilename: 'talkwithtext-sms-notification-for-woo-australia.pot', // Name of the POT file.
                    type: 'wp-plugin', // Type of project (wp-plugin or wp-theme).
                    potHeaders: {
                        'report-msgid-bugs-to': 'http://web-apps.ninja',
                        'language-team': 'LANGUAGE <EMAIL@ADDRESS>'
                    }
                }
            }
        },

        // Clean up build directory
        clean: {
            main: ['build/']
        },

        // Copy the plugin into the build directory
        copy: {
            main: {
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!bin/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!debug.log',
                    '!phpunit.xml',
                    '!.gitignore',
                    '!.gitmodules',
                    '!npm-debug.log',
                    '!secret.json',
                    '!plugin-deploy.sh',
                    '!assets/less/**',
                    '!tests/**',
                    '!**/Gruntfile.js',
                    '!**/package.json',
                    '!**/composer.json',
                    '!**/composer.lock',
                    '!**/README.md',
                    '!**/readme.md',
                    '!**/*~'
                ],
                dest: 'build/'
            }
        },

        //Compress build directory into <name>.zip and <name>-<version>.zip
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: './build/talkwithtext-sms-for-woo-australia-v' + pkg.version + '.zip'
                },
                expand: true,
                cwd: 'build/',
                src: ['**/*'],
                dest: 'talkwithtext-sms-notification-for-woo'
            }
        },
    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );
    grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );

    grunt.registerTask( 'default', [
        'makepot',
    ]);

    grunt.registerTask('release', [
        'makepot'
    ]);

    grunt.registerTask( 'zip', [
        'clean',
        'copy',
        'compress'
    ])
};