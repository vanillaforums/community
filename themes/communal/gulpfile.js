'use strict';

var gulp    = require('gulp')
  , $       = require('gulp-load-plugins')()
  , wiredep = require('wiredep').stream;

gulp.task('styles', function () {
  return gulp.src('less/communal.less')
    .pipe($.plumber())
    .pipe($.less({
      paths: 'bower_components'
    }))
    .pipe($.autoprefixer())
    .pipe($.csslint('design/.csslintrc'))
    .pipe($.csslint.reporter('default'))
    .pipe($.csso())
    .pipe($.rename('custom.css'))
    .pipe(gulp.dest('design'))
    .pipe($.size());
});

gulp.task('scripts', function () {
  return gulp.src('js/src/communal.js')
    .pipe($.plumber())
    .pipe($.jshint('js/.jshintrc'))
    .pipe($.jshint.reporter('default'))
    .pipe($.include())
    .pipe($.concat('custom.js'))
    .pipe($.uglify())
    .pipe(gulp.dest('js'))
    .pipe($.size());
});

gulp.task('wiredep', function () {
  gulp.src('less/**/*.less')
    .pipe($.plumber())
    .pipe(wiredep({
      directory: 'bower_components'
    }))
    .pipe(gulp.dest('less'));

  gulp.src('js/src/**/*.js')
    .pipe($.plumber())
    .pipe(wiredep({
      directory: 'bower_components'
    , exclude: ['bower_components/jquery/dist/jquery.js']
    , fileTypes: {
        js: {
          block: /(([ \t]*)\/\/\s*bower:*(\S*))(\n|\r|.)*?(\/\/\s*endbower)/gi,
          detect: {
            js: /\/\/\s= require (.+)/gi
          },
          replace: {
            js: '//= require {{filePath}}'
          }
        }
      }
    }))
    .pipe(gulp.dest('js/src'));
});

gulp.task('build', ['styles', 'scripts']);

gulp.task('default', ['wiredep'], function () {
  return gulp.start('build');
});

gulp.task('watch',  function () {
  var server = $.livereload();

  gulp.watch([
    'design/*.css'
  , 'js/*.js'
  , 'views/**/*.tpl'
  ], function (file) {
    return server.changed(file.path);
  });

  gulp.watch('less/**/*.less', ['styles']);

  gulp.watch('js/src/**/*.js', ['scripts']);

  gulp.watch('bower.json', ['wiredep']);
});
