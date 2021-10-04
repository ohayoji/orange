var gulp = require("gulp"),
  tsify = require('tsify'),
  browserify = require('browserify'),
  watchify = require('watchify'),
  babelify = require('babelify'),
  vueify = require('vueify'),
  source = require('vinyl-source-stream'),
  watch = require('gulp-watch');

// folders
const PBLC = {
  js: 'js'
};
const SRC_AST_BASE = 'src/assets';
const SRC_AST = {
  js: `${SRC_AST_BASE}/js`,
}

const entries = [
  'bundle_reserve_after.js',
  'bundle_reserve_before.js'
];

//browserify and watchify
gulp.task('bundle', function(){
    entries.forEach(function(entryPoint){
        // bundle option
        var bundler = watchify(
            browserify({
                cache: {}, //watchifyの差分ビルドを有効化
                entries:[`${SRC_AST.js}/${entryPoint}`],
                debug: true,
                packageCache: {} //watchifyの差分ビルドを有効化
            })
            .plugin(tsify)
            .transform(vueify)
            .transform(babelify)
        );
        //bundle function
        function bundled(){
            return bundler
                .bundle()
                .pipe(source(entryPoint))
                .pipe(gulp.dest(PBLC.js));
        }
        bundler.on('update', bundled);
        bundler.on('log', function(message){console.log(message)});
        return bundled();
    })
});

gulp.task("default", [], function() {
  gulp.start(['bundle']);
});
