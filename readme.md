# Welcome To OA's apiDoc Template!
相信大家不管身為前端還是後端的各位大大們，在開發前後端串接或多或少都有使用過 [apiDoc](http://apidocjs.com/) 吧！？  
沒錯！這個是 OA 我自己寫的 apiDoc 的 Template 樣板！有興趣的話，歡迎各位看一下玩玩看囉！

使用的工具不難，版型是用 [scss](http://compass-style.org/) 刻的，JavaScript 也僅用 [jQuery](https://jquery.com/)。  
流程主要是藉由 [Ajax](https://zh.wikipedia.org/wiki/AJAX) 取得編譯後的 `api_data.json`、`api_project.json` 然後再產生 HTML 畫面。

若是各位大大們覺得有趣的話，歡迎在此 [GitHub](https://github.com/comdan66/OA-apiDoc-Template) 專案右上角幫我按一下 **星星** 給我個鼓勵吧！

---

## 聲明
本作品授權採用 姓名標示-非商業性 2.0 台灣 (CC BY-NC 2.0 TW) 授權，詳見 [http://creativecommons.org/licenses/by-nc/2.0/tw/](http://creativecommons.org/licenses/by-nc/2.0/tw/)

## 說明
* 此樣板是使用 [OA](http://www.ioa.tw/) 個人開發的前端工具 [OAF2E](https://github.com/comdan66/oaf2e) 所製作的。
* 本架構下使用 [jQuery](https://jquery.com/)、[compass](http://compass-style.org/)、[Gulp](http://gulpjs.com/) 開發。
* 本樣板沒有支援 100% 所有 apiDoc 的功能喔！所以使用前請先斟酌評估功能。

## 使用方法
* 請先確認電腦是否已完成安裝 [apiDoc](http://apidocjs.com/)。
* 請開啟 `終端機`，並且移到專案目錄下。
* 下指令 `apidoc -i api_forder -t template_forder`

## 舉例
* 假設 API 專案目錄是在 `/var/www/project/app/controller/api/`
* 下載的 Template 目錄在 `/var/www/OA-apiDoc-Template/`
* 終端機位置移到欲產生文件的位置，例如：`/var/www/`，所以下指令：`cd /var/www/`
* 產生文件就指令為：`	apidoc -i /var/www/project/app/controller/api/ -t /var/www/OA-apiDoc-Template/`
* 產生的文件檔案就會在：`/var/www/doc/`
* 假設伺服器 `127.0.0.1` 是指向 `/var/www/`，那就可以打開瀏覽器檢視 [http://127.0.0.1/doc/](http://127.0.0.1/doc/)

## 關於
* 作者 - [OA Wu](https://www.ioa.tw/)
* E-mail - <comdan66@gmail.com>
* 作品名稱 - OA's apiDoc Template!
* 最新版本 - 0.0.0
* GitHub - [OA's apiDoc Template](https://github.com/comdan66/OA-apiDoc-Template)
* 更新日期 - 2018/04/02