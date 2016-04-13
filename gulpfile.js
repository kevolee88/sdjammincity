// Load plugins
var gulp = require('gulp'),
	plugins = require('gulp-load-plugins')({ camelize: true }),
	lr = require('tiny-lr'),
	server = lr();

// Styles
gulp.task('less', function() {
  return gulp.src('wp-content/themes/noise-wp/less/*.less')
	.pipe(gulp.dest('wp-content/themes/noise-wp/style.css'))
	.pipe(plugins.livereload(server))
});

// Watch
gulp.task('watch', function() {

  // Listen on port 35729
  server.listen(35729, function (err) {
	if (err) {
	  return console.log(err)
	};

	// Watch .less files
	gulp.watch('wp-content/themes/noise-wp/less/*.less', ['less']);

  });

});

// Default task
gulp.task('default', ['less', 'plugins', 'scripts', 'images', 'watch']);
