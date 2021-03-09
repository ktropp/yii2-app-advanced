var gulp = require('gulp');

//PLUGINS
var gulpLoadPlugins = require('gulp-load-plugins');
var plugins = gulpLoadPlugins();
var path = require('path');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;

var LessPluginGlob = require('less-plugin-glob');
var gutil = require('gulp-util');

var less_path = ['less/**/*.less'];
var js_path = ['js/**/*.js'];

var dist_css_path = [
    'dist/global.css',
];
var dist_js_path = [
    '../../vendor/bower-asset/jquery/dist/jquery.js',
    '../../vendor/bower-asset/jquery-ui/jquery-ui.js',
    '../../vendor/yiisoft/yii2/assets/yii.js',
    '../../vendor/yiisoft/yii2/assets/yii.activeForm.js',
    '../../vendor/bower-asset/yii2-pjax/jquery.pjax.js',
    'dist/main.js',
];

var runTimestamp = Math.round(Date.now()/1000);
var fontName = 'iconfont';

gulp.task('browserSync', function() {
  browserSync.init({
    proxy: 'anygamble_frontend',
    open: false,
  });

  gulp.watch(less_path, gulp.series('less')); 
  gulp.watch(js_path, gulp.series('js'));
  gulp.watch(['*.php', '../**/*.php']).on("change", reload);
})

gulp.task('less', function (done) {
    gulp.src('less/main.less')
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.plumber())
    .pipe(plugins.less({
      plugins: [LessPluginGlob],
    }))
    .pipe(plugins.concat('global.css'))
    .pipe(plugins.autoprefixer()) 
    .pipe(plugins.cleanCss())
    .pipe(plugins.sourcemaps.write())
    .pipe(gulp.dest('dist'))
    .pipe(browserSync.stream())

    done();
});

gulp.task('js', function (done) {
  gulp.src(js_path)
  .pipe(plugins.uglify())
  .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
  .pipe(plugins.concat('main.js'))
  .pipe(gulp.dest('dist'))
  .pipe(browserSync.stream())

  browserSync.reload();

  done();
});

gulp.task('iconfont', function(){
  return gulp.src(['icons/*.svg'])
    .pipe(plugins.iconfontCss({
      fontName: fontName,
      path: '_iconfont.less',
      targetPath: '../less/main/iconfont.less',
      fontPath: '../fonts/'
    }))
    .pipe(plugins.iconfont({
      fontName: fontName, // required
      prependUnicode: true, // recommended option
      formats: ['ttf', 'eot', 'woff', 'svg', 'woff2'], // default, 'woff2' and 'svg' are available
      timestamp: runTimestamp, // recommended to get consistent builds when watching files
      normalize:true,
      fontHeight: 1001,
    }))
      .on('glyphs', function(glyphs, options) {
        // CSS templating, e.g.
        //console.log(glyphs, options);
      })
    .pipe(gulp.dest('fonts'));
});

gulp.task('dist_css', function (done) {
    gulp.src(dist_css_path)
        .pipe(plugins.plumber())
        .pipe(plugins.concat('all.css'))
        .pipe(plugins.autoprefixer())
        .pipe(plugins.cleanCss())
        .pipe(gulp.dest('deploy'))

    done();
});

gulp.task('dist_js', function (done) {
    gulp.src(dist_js_path)
        .pipe(plugins.uglify())
        .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
        .pipe(plugins.concat('all.js'))
        .pipe(gulp.dest('deploy'))

    done();
});

gulp.task('deploy', gulp.parallel('dist_css', 'dist_js'));

gulp.task('build', gulp.parallel('less', 'js', 'iconfont'));
     

gulp.task('watch', gulp.series('less', 'js', 'browserSync'));


gulp.task('default', gulp.series('watch'));

//exports.default = watch