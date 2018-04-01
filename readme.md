# Welcome To OA's F2E Framework!
OA 個人常用的前端開發工具！ 

---

## 聲明
本作品授權採用 姓名標示-非商業性 2.0 台灣 (CC BY-NC 2.0 TW) 授權，詳見 [http://creativecommons.org/licenses/by-nc/2.0/tw/](http://creativecommons.org/licenses/by-nc/2.0/tw/)

## 說明
* [OA](http://www.ioa.tw/) 個人開發前端所使用的工具，有興趣的可以參考。
* 本架構下使用 [jQuery](https://jquery.com/)、[compass](http://compass-style.org/)、[Gulp](http://gulpjs.com/) 開發。
* 支援部署至 [GitHub Pages](https://pages.github.com/) 做靜態頁面 DEMO。
* 支援部署至 [Amazon Simple Storage Service (Amazon S3)](https://aws.amazon.com/tw/s3/) 做靜態頁面 DEMO。

## 使用方法
* 請先確認電腦是否已完成安裝 [Node.js](https://nodejs.org) 與 [compass](http://compass-style.org/)。
* 第一次使用，請至 `cmd/` 下執行 `sudo npm install .` 以完成 **npm** 相關套件安裝。
* 執行 compass，請至 `cmd/` 下執行 `compass watch`
* 執行 [Livereload](http://livereload.com/)，請至 `cmd/` 下執行 `gulp watch`。
* 新增 font icon，請先執行 `gulp watch` 後再下載的檔案取代至 `font/icomoon/` 將目錄內檔案。
* Font icon 參考網站：[https://icomoon.io/](https://icomoon.io/)。

## 部署
類型有兩種 1. 部署到 GitHub Pages 2. 部署到 Amazon Web Services(AWS) S3，若是不知道該選擇什麼語法建議使用 `php` 部署工具，使用方式是在 `cmd` 資料夾下執行指令 **`php upload`** 即可依據步驟上傳部署。

### 部署到 GitHub Pages
主要分兩大類狀況，**尚未執行過 sudo npm install .** 與 **已經執行過 sudo npm install .**，並且要注意本機是否已經有 gh-pages 分支，以下分別是各種狀況下所使用的語法。

* 尚**未執行**過 `sudo npm install .`，並且本機**尚未有** gh-pages 分支，請至**專案目錄**下執行以下指令：
```
git branch -v gh-pages && git checkout gh-pages && cd cmd && sudo npm install .  && gulp minify && gulp gh-pages && cd ../ && git add -A && git commit -m 'Minify js、html, fix gh-pages path bug.' && git push origin gh-pages --force && git checkout master
```

* 尚**未執行**過 `sudo npm install .`，並且本機**已經有** gh-pages 分支，請至**專案目錄**下執行以下指令：
```
git branch -D gh-pages && git branch -v gh-pages && git checkout gh-pages && cd cmd && sudo npm install . && gulp minify && gulp gh-pages && cd ../ && git add -A && git commit -m 'Minify js、html, fix gh-pages path bug.' && git push origin gh-pages --force && git checkout master
```

* **已經執行**過 `sudo npm install .`，並且本機**尚未有** gh-pages 分支，請至**專案目錄**下執行以下指令：
```
git branch -v gh-pages && git checkout gh-pages && cd cmd && gulp minify && gulp gh-pages && cd ../ && git add -A && git commit -m 'Minify js、html, fix gh-pages path bug.' && git push origin gh-pages --force && git checkout master
```

* **已經執行**過 `sudo npm install .`，本機**已經有** gh-pages 分支，請至**專案目錄**下執行以下指令：
```
git branch -D gh-pages && git branch -v gh-pages && git checkout gh-pages && cd cmd && gulp minify && gulp gh-pages && cd ../ && git add -A && git commit -m 'Minify js、html, fix gh-pages path bug.' && git push origin gh-pages --force && git checkout master
```

> 若是都不知道該怎麼選擇，則直接在 `cmd` 資料夾下執行指令 **`php upload`** 即可依據步驟上傳部署。
	
### 部署到 Amazon Web Services(AWS) S3
主要是藉由執行 `cmd/put.php` 將檔案上傳至 S3，請確保本機能執行 php 版本 5.6 或以上版本才可以使用，指令中的 {bucket}、{access}、{secret} 請置換成自己的值

* **要**壓縮檔案，請至**專案目錄**下執行以下指令
```
git add -A && git commit -m 'Fix code.' && git push origin master && cd cmd && php put.php -b {bucket} -a {access} -s {secret} -m 0 && cd .. && git checkout .
```

* **不要**壓縮檔案，請至**專案目錄**下執行以下指令：
```
git add -A && git commit -m 'Fix code.' && git push origin master && cd cmd && php put.php -b {bucket} -a {access} -s {secret} && cd .. && git checkout .
```

> 若是都不知道該怎麼選擇，則直接在 `cmd` 資料夾下執行指令 **`php upload`** 即可依據步驟上傳部署。


參數說明：  

指令  | 必填  | 預設值 | 說明
---- | ----- |------ |----
-b | y | 無 | bucket 名稱
-a | y | 無 | access key
-s | y | 無 | secret key
-u | n | 1 | 是否上傳
-m | n | 1 | 是否壓縮
-n | n | 1 | 是否將專案目錄一併上傳

> EX: 假設 Bucket 名稱是 abc.com.tw，Access key 是 AsDfGh，Secret key 是 ZxCvB123，那就是在 `cmd/` 下執行以下語法：  
> `php put.php -b abc.com.tw -a AsDfGh -s ZxCvB123`




## 關於
* 作者 - [OA Wu](https://www.ioa.tw/)
* E-mail - <comdan66@gmail.com>
* 作品名稱 - OA's F2E Framework
* 最新版本 - 4.3.3
* GitHub - [OA's F2E Framework](https://github.com/comdan66/oaf2e/)
* 更新日期 - 2018/01/02