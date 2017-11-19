/*
  TODO: Move src files to src.
  TODO: Make angular min-safe before uglifying. ng-annotate?
*/
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      files: ['Gruntfile.js', 'js/**/*.js'],
      options: {
        globals: {
          jQuery: true
        }
      }
    },
    concat: {
      options: {
        separator: ';\n',
      },
      dist: {
        src: ['js/**/*.js'],
        dest: 'src/app.concat.js',
      },
    },
    uglify: {
      target_build: {
        files: [{
          'build/app.min.js': ['src/app.concat.js']
        }]
      }
    },
    watch: {
      files: ['<%= jshint.files %>'],
      tasks: ['jshint']
    },
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');

  grunt.registerTask('default', ['jshint']);

  grunt.registerTask('build', ['concat', 'uglify']);

};