// Load plugins
var gulp = require('gulp'),
    plugins = require('gulp-load-plugins')({ camelize: true }),
    dependencies = require('gulp-dependencies'),
    lr = require('tiny-lr'),
    less = require('gulp-less'),
    path = require('path'),
    concat = require('gulp-concat'),
    server = lr();

// Less
gulp.task('less', function() {
  return gulp.src('wp-content/themes/noise-wp/less/style.less')
  .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
  .pipe(concat('style.css'))
	.pipe(gulp.dest('wp-content/themes/noise-wp/'))
});

// Watch
gulp.task('watch', function() {

  // Listen on port 35729
  server.listen(35729, function (err) {
	if (err) {
	  return console.log(err)
	};

	// Watch .less files
  gulp.watch('wp-content/themes/noise-wp/less/style.less', [ 'less' ]);
	// gulp.watch('wp-content/themes/noise-wp/less/sections/*.less');

  });

});

// Default task
gulp.task('default', ['less', 'watch']);
