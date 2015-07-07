
module.exports = function(grunt) {
  grunt.initConfig({
    concat: {
      dist: {
        src: ['lib/css/partials/*.css'],
        dest: 'lib/css/style.css',
      },
    },
    watch: {
      css: {
        files: 'lib/css/partials/*.css',
        tasks: ['concat', 'notify:concat'],
        options: {
          interrupt: true,
        },
      },
    },
    notify:{
      concat: {
       options: {
          title: 'Stylesheets processed',
          message: 'All the css files have been concat',
        },
      },
  }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-notify');

  grunt.registerTask('default', ['concat']);
};