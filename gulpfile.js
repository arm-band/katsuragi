/**
 * gulp task
 *
 * @author    アルム＝バンド
 * @copyright Copyright (c) アルム＝バンド
 */

var gulp = require("gulp");
//全般
var watch = require("gulp-watch");
var plumber = require("gulp-plumber"); //待機
var notify = require("gulp-notify"); //標準出力
//sass
var sass = require("gulp-sass"); //sass
var autoprefixer = require("gulp-autoprefixer");
//img
var imagemin = require("gulp-imagemin"); //画像ロスレス圧縮
//js
var uglify = require("gulp-uglify"); //js圧縮
var concat = require("gulp-concat"); //ファイル結合
var rename = require("gulp-rename"); //ファイル名変更
//file operation
var fs = require("fs");
//reload
var connect = require("gulp-connect-php"); //proxy(phpファイル更新時リロード用)
var browserSync = require("browser-sync"); //ブラウザリロード

//path difinition
var file = {
  php : 'index.php',
  css : 'index.css',
  js  : 'app.min.js'
};
var dir = {
  src: {
    php       : './src/php/' + file.php,
    scss      : './src/scss',
    js        : './src/js'
  },
  data: {
    dir       : './src/data',
    variables : '/variables.json',
    commonvar : '/commonvar.json'
  },
  dist: {
    php       : './dist',
    css       : './src/gencss/',
    js        : './src/genjs/' + file.js
  }
};

//jsonファイル取得
//ejs内で使用するパラメータ
var getVariables = () => {
    return JSON.parse(fs.readFileSync(dir.data.dir + dir.data.variables, { encoding: "UTF-8" }).replace(/\r|\n|\t/g, ""));
}
//ejs, js, scssにまたがって使用するパラメータ
var getCommonVar = () => {
    return JSON.parse(fs.readFileSync(dir.data.dir + dir.data.commonvar, { encoding: "UTF-8" }).replace(/\r|\n|\t/g, ""));
}

//scssコンパイルタスク
gulp.task("sass", () => {
    return gulp.src(`${dir.src.scss}/**/*.scss`)
        .pipe(plumber())
        .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 version', 'iOS >= 8.1', 'Android >= 4.4'],
            cascade: false
        }))
        .pipe(gulp.dest(dir.dist.css));
});

//js圧縮&結合&リネーム
gulp.task("js", () => {
    return gulp.src(`${dir.src.js}/index.js`)
        .pipe(plumber())
        .pipe(uglify({output: {comments: "some"}}))
        .pipe(rename(dir.dist.js))
        .pipe(gulp.dest("./"));
});

//php
gulp.task("php", gulp.series(gulp.parallel("sass", "js"), done => {
    var css = fs.readFileSync(`${dir.dist.css}/${file.css}`, { encoding: "UTF-8" });
    var js = fs.readFileSync(dir.dist.js, { encoding: "UTF-8" });

//    return gulp.src(
//        [dir.src.php + "/**/*"]
//    )
//    .pipe(plumber())
//    .pipe(gulp.dest(dir.dist.php));
    fs.readFile(dir.src.php, "utf8", function (err, data) {
        if(err) {
            console.log(err);
            done();
        }
        var cssProcessed = data.replace(/<\!-- KATSURAGI_CSS -->/g, css);
        var processed = cssProcessed.replace(/<\!-- KATSURAGI_JS -->/g, js);
        fs.writeFile(`${dir.dist.php}/${file.php}`, processed, "utf8", function (err) {
            if(err) {
                console.log(err);
            }
            done();
        });
    });
}));

//自動リロード
gulp.task("connect-sync", () => {
  connect.server({ //php使うときはこっち
        port: 8001,
        base: dir.dist.php,
        bin: "D:/xampp/php/php.exe",
        ini: "D:/xampp/php/php.ini"
    }, () =>{
        browserSync({
            proxy: "localhost:8001",
            open: 'external'
        });
    });
//    browserSync({
//        server: {
//            baseDir: dir.dist.html
//        },
//        open: 'external'
//    });

    watch([`${dir.src.scss}/**/*.scss`, `${dir.src.js}/*.js`, dir.src.php], gulp.series("php", browserSync.reload));
});

gulp.task("server", gulp.series("connect-sync"));
gulp.task("build", gulp.parallel("php"));

//gulpのデフォルトタスクで諸々を動かす
gulp.task("default", gulp.series("build", "server"));