var grunt = require('grunt');

//userful dirs
var styleDir = "lib/css/";
var templateDir = "pages/templates/";

grunt.initConfig({
  concat: {
    dist: {
      src: [styleDir + 'partials/*.css'],
      dest: styleDir + 'style.css',
    },
  },
  less:{
    icons:{
      files :{
        'lib/css/partials/a_icons.css' : styleDir + 'less/glyphicons.less',
      }
    },
  },
  watch: {
    css: {
      files: styleDir + 'partials/*.css',
      tasks: ['concat', 'notify:concat'],
      options: {
        interrupt: true,
      },
    },
  },
  notify: {
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
grunt.loadNpmTasks('grunt-contrib-less');
grunt.loadNpmTasks('grunt-contrib-handlebars');
grunt.registerTask('default', ['concat']);