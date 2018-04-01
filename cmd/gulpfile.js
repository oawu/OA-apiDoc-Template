var gulp       = require ('gulp'),
    livereload = require ('gulp-livereload'),
    uglifyJS   = require ('gulp-uglify'),
    htmlmin    = require ('gulp-html-minifier'),
    del        = require ('del'),
    chokidar   = require ('chokidar'),
    read       = require ('read-file'),
    writeFile  = require ('write'),
    gutil      = require ('gulp-util'),
    shell      = require ('gulp-shell'),
    colors     = gutil.colors;

gulp.task ('default', function () {
  console.log ('\n ' + colors.red ('•') + colors.cyan (' [啟動] ') + '正在開啟 Gulp 初始化！');

  read ('config_d4.rb', 'utf8', function (err, buffer) {
    if (!buffer) return console.log (colors.red ('\n\n !!! 錯誤 !!! compass 的 ' + colors.magenta ('config_d4') + ' 檔案不見惹！\n\n'));

    var path = __dirname.replace (/\\/g,'/').split (/\//);
    if (path.length < 3) return console.log (colors.red ('\n\n !!! 錯誤 !!! 資料夾位置有誤！\n\n'));
    path.pop ();
    var content = buffer.replace (/http_path\s*=\s*"([\/A-Za-z0-9_-]*)"/g, 'http_path = "/' + path.pop () + '"');
    
    writeFile ('config.rb', content, function(err) {
      if (err) return console.log ('\n ' + colors.red ('•') + colors.red (' [錯誤] ') + '寫入檔案失敗！');
      else console.log ('\n ' + colors.red ('•') + colors.yellow (' [設定] ') + '設定 ' + colors.magenta ('config.rb') + ' 檔案成功！');

      console.log ('\n ' + colors.red ('•') + colors.cyan (' [開啟] ') + '設定相關 ' + colors.magenta ('watch') + ' 功能！');

      livereload.listen ({
        silent: true
      });

      var watcherReload = chokidar.watch (['./root/*.html', './root/css/**/*.css', './root/js/**/*.js'], {
        ignored: /(^|[\/\\])\../,
        persistent: true
      });

      watcherReload.on ('change', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有檔案更新，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      }).on ('add', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有新增檔案，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      }).on ('unlink', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有檔案刪除，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      });

      var watcherStyle = chokidar.watch ('./root/font/icomoon/style.css', {
        ignored: /(^|[\/\\])\../,
        persistent: true
      });

      watcherStyle.on ('add', function (path) { gulp.start ('update_icomoon_font_icon'); })
                  .on ('change', function (path) { gulp.start ('update_icomoon_font_icon'); });
      // watcherStyle.on ('unlink', function (path) { gulp.start ('update_icomoon_font_icon'); });

      var watcherScss = chokidar.watch ('./root/scss/**/*.scss', {
        ignored: /(^|[\/\\])\../,
        persistent: true
      });

      watcherScss.on ('change', function (path) {
        gulp.start ('compass_compile');
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [scss] ') + '完成編譯 scss，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
      }).on ('unlink', function (path) {
        del (path.replace (/scss\//g, 'css/').replace (/\.scss/g, '.css'));
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [scss] ') + '刪除 scss，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
      }).on ('add', function (path) {
        gulp.start ('compass_compile');
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [scss] ') + '完成編譯 scss，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
      });
    });
  });
});
gulp.task ('watch', function () {
  console.log ('\n ' + colors.red ('•') + colors.cyan (' [啟動] ') + '正在開啟 Gulp 初始化！');

  read ('config_d4.rb', 'utf8', function (err, buffer) {
    if (!buffer) return console.log (colors.red ('\n\n !!! 錯誤 !!! compass 的 ' + colors.magenta ('config_d4') + ' 檔案不見惹！\n\n'));

    var path = __dirname.replace (/\\/g,'/').split (/\//);
    if (path.length < 3) return console.log (colors.red ('\n\n !!! 錯誤 !!! 資料夾位置有誤！\n\n'));
    path.pop ();
    var content = buffer.replace (/http_path\s*=\s*"([\/A-Za-z0-9_-]*)"/g, 'http_path = "/' + path.pop () + '"');
    
    writeFile ('config.rb', content, function(err) {
      if (err) return console.log ('\n ' + colors.red ('•') + colors.red (' [錯誤] ') + '寫入檔案失敗！');
      else console.log ('\n ' + colors.red ('•') + colors.yellow (' [設定] ') + '設定 ' + colors.magenta ('config.rb') + ' 檔案成功！');

      console.log ('\n ' + colors.red ('•') + colors.cyan (' [開啟] ') + '設定相關 ' + colors.magenta ('watch') + ' 功能！');

      livereload.listen ({
        silent: true
      });

      var watcherReload = chokidar.watch (['./root/*.html', './root/css/**/*.css', './root/js/**/*.js'], {
        ignored: /(^|[\/\\])\../,
        persistent: true
      });

      watcherReload.on ('change', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有檔案更新，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      }).on ('add', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有新增檔案，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      }).on ('unlink', function (path) {
        console.log ('\n ' + colors.red ('•') + colors.yellow (' [重整] ') + '有檔案刪除，檔案：' + colors.gray (path.replace (/\\/g,'/').replace (/.*\//, '')) + '');
        gulp.start ('reload');
        console.log ('    ' + colors.green ('reload') + ' 重新整理頁面成功！');
      });

      var watcherStyle = chokidar.watch ('./root/font/icomoon/style.css', {
        ignored: /(^|[\/\\])\../,
        persistent: true
      });

      watcherStyle.on ('add', function (path) { gulp.start ('update_icomoon_font_icon'); })
                  .on ('change', function (path) { gulp.start ('update_icomoon_font_icon'); });
      // watcherStyle.on ('unlink', function (path) { gulp.start ('update_icomoon_font_icon'); });
    });
  });
});

// // ===================================================

gulp.task ('update_icomoon_font_icon', function () {
  read ('./root/font/icomoon/style.css', 'utf8', function (err, buffer) {
    var t = buffer.match (/\.icon-[a-zA-Z_\-0-9]*:before\s?\{\s*content:\s*"[\\A-Za-z0-9]*";\s*}/g);
      if (!(t && t.length)) return;

      writeFile ('./root/scss/icon.scss', '@import "_oa";\n\n@include font-face("icomoon", font-files("icomoon/fonts/icomoon.eot", "icomoon/fonts/icomoon.woff", "icomoon/fonts/icomoon.ttf", "icomoon/fonts/icomoon.svg"));\n[class^="icon-"], [class*=" icon-"] {\n  font-family: "icomoon"; speak: none; font-style: normal; font-weight: normal; font-variant: normal;\n  @include font-smoothing(antialiased);\n}\n\n' + t.join ('\n'), function(err) {
        if (err) console.log ('\n ' + colors.red ('•') + colors.red (' [錯誤] ') + '寫入檔案失敗！');
        else console.log ('\n ' + colors.red ('•') + colors.yellow (' [icon] ') + '更新 icon 惹，目前有 ' + colors.magenta (t.length) + ' 個！');
      });
  });
});

// // ===================================================

gulp.task ('compass_compile', shell.task ('compass compile'));

// // ===================================================

gulp.task ('reload', function () {
  livereload.changed ();
});

// // ===================================================

gulp.task ('minify', function () {
  gulp.start ('js-uglify');
  gulp.start ('minify-html');
});
gulp.task ('js-uglify', function () {
  gulp.src ('./root/js/**/*.js')
      .pipe (uglifyJS ())
      .pipe (gulp.dest ('./root/js/'));
});
gulp.task ('minify-html', function () {
  gulp.src ('./root/*.html')
      .pipe (htmlmin ({collapseWhitespace: true}))
      .pipe (gulp.dest ('./root/'));
});

// // ===================================================

gulp.task ('gh-pages', function () {
  del (['./root']);
});