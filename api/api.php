<?php
  /**
   * @author      OA Wu <comdan66@gmail.com>
   * @copyright   Copyright (c) 2013 - 2018, OACI
   * @license     http://opensource.org/licenses/MIT  MIT License
   * @link        https://www.ioa.tw/
   */

  /**
   * @apiDefine MyError 錯誤訊息
   *
   * @apiError {String} message  訊息
   * @apiErrorExample {json} 錯誤:
   *     HTTP/1.1 400 Error
   *     {
   *       "message": "錯誤訊息..",
   *     }
   */
  
  /**
   * @apiDefine MySuccess 成功訊息
   *
   * @apiSuccess {String} message  訊息
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     {
   *       "message": "成功訊息..",
   *     }
   */

  /**
   * @apiDefine login 需先登入
   * 此 API 必需先登入後取得 <code>Access Token</code>，在藉由 Header 夾帶 Access Token 驗證身份
   *
   * @apiHeader {string} token 登入後的 Access Token
   */

  /**
   * @apiDefine loginMaybe 須不須登入皆可
   * 此 API 若有帶 <code>Access Token</code> 則代表登入
   *
   * @apiHeader {string} [token] 登入後的 Access Token
   */

  /**
   * @apiName Login
   * @api {post} /login/ 登入
   * @apiGroup Platform
   * @apiDescription 登入系統
   *
   * @apiParam {String} account  帳號
   * @apiParam {String} password 密碼
   *
   * @apiSuccess {String} token Access Token
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     {
   *       "token": "a0b1c2d3e4f5g6h7i8j9k",
   *     }
   *
   * @apiUse MyError
   */

  /**
   * @apiName Register
   * @api {post} /register/ 註冊
   * @apiGroup Platform
   * @apiDescription 註冊會員
   *
   * @apiParam {String} account    帳號
   * @apiParam {String} password   密碼
   * @apiParam {String} name       名稱
   * @apiParam {date}   [birthday] 生日
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName GetCurrentUser
   * @api {get} /users/current/ 自己的資訊
   * @apiGroup User
   * @apiDescription 取得自己的資訊
   *
   * @apiPermission login
   * @apiUse login
   *
   * @apiSuccess {String} account   我的帳號
   * @apiSuccess {String} name      我的名稱
   * @apiSuccess {Date}   Birthday  我的生日
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     {
   *       "account": "oawu",
   *       "name": "OA Wu",
   *       "Birthday": null
   *     }
   *
   * @apiUse MyError
   */

  /**
   * @apiName GetUser
   * @api {get} /users/:id/ 使用者資訊
   * @apiGroup User
   * @apiDescription 取得使用者相關資訊
   *
   * @apiParam {Number} id 使用者的 ID
   *
   * @apiSuccess {String} name      使用者名稱
   * @apiSuccess {Date}   Birthday  使用者生日
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     {
   *       "name": "OA Wu",
   *       "Birthday": null
   *     }
   *
   * @apiUse MyError
   */

  /**
   * @apiName PutUser
   * @api {put} /users/ 修改使用者資訊
   * @apiGroup User
   * @apiDescription 修改使用者相關資訊
   *
   * @apiPermission login
   * @apiUse login
   *
   * @apiParam {String} [password] 密碼
   * @apiParam {String} [name]     名稱
   * @apiParam {date}   [birthday] 生日
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName GetArticles
   * @api {get} users/:user_id/articles/ 取得文章列表
   * @apiGroup Article
   * @apiDescription 取得該位使用者下的文章列表
   *
   * @apiParam {Number} [offset=0]      位移
   * @apiParam {String} [limit=20]      長度
   *
   * @apiParam {Number} user_id         使用者 ID
   *
   * @apiSuccess {Number} id            文章 ID
   * @apiSuccess {Array}  user          作者
   * @apiSuccess {Number} user.id       作者 ID
   * @apiSuccess {String} user.name     作者名稱
   * @apiSuccess {String} title         文章標題
   * @apiSuccess {String} content       文章內容，格式為 HTML
   * @apiSuccess {Number} pv            累計瀏覽數
   * @apiSuccess {Datetime} created_at  建立時間
   * @apiSuccess {Datetime} updated_at  修改時間
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     [
   *         {
   *             "id": 1,
   *             "user": {
   *                 "id": 1,
   *                 "name": "OA Wu"
   *               },
   *             "title": "標題",
   *             "content": "<p>內文..</p>",
   *             "pv": 20,
   *             "created_at": "2017-04-01 12:13:14",
   *             "updated_at": "2017-04-01 13:14:15"
   *         }
   *     ]
   *
   * @apiUse MyError
   */

  /**
   * @apiName GetArticle
   * @api {get} users/:user_id/articles/:id/ 取得指定文章內容
   * @apiGroup Article
   * @apiDescription 取得該位使用者下的某文章內容
   *
   * @apiParam {Number} user_id         使用者 ID
   * @apiParam {Number} id              文章 ID
   *
   * @apiSuccess {Number} id            文章 ID
   * @apiSuccess {Array}  user          作者
   * @apiSuccess {Number} user.id       作者 ID
   * @apiSuccess {String} user.name     作者名稱
   * @apiSuccess {String} title         文章標題
   * @apiSuccess {String} content       文章內容，格式為 HTML
   * @apiSuccess {Number} pv            累計瀏覽數
   * @apiSuccess {Datetime} created_at  建立時間
   * @apiSuccess {Datetime} updated_at  修改時間
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     {
   *         "id": 1,
   *         "user": {
   *             "id": 1,
   *             "name": "OA Wu"
   *           },
   *         "title": "標題",
   *         "content": "<p>內文..</p>",
   *         "pv": 20,
   *         "created_at": "2017-04-01 12:13:14",
   *         "updated_at": "2017-04-01 13:14:15"
   *     }
   *
   * @apiUse MyError
   */

  /**
   * @apiName CreateArticle
   * @api {post} articles/ 新增文章
   * @apiGroup Article
   * @apiDescription 新增一篇文章
   *
   * @apiPermission login
   * @apiUse login
   *
   * @apiParam {String} title     文章標題
   * @apiParam {String} content   文章內容
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName UpdateArticle
   * @api {put} articles/:id/ 修改文章
   * @apiGroup Article
   * @apiDescription 修改指定的文章
   *
   * @apiPermission login
   * @apiUse login
   *
   * @apiParam {Number} id        文章 ID
   *
   * @apiParam {String} title     文章標題
   * @apiParam {String} content   文章內容
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName DeleteArticle
   * @api {delete} articles/:id/ 刪除文章
   * @apiGroup Article
   * @apiDescription 刪除指定的文章
   *
   * @apiPermission login
   * @apiUse login
   *
   * @apiParam {Number} id        文章 ID
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName GetArticleMessages
   * @api {get} users/:user_id/articles/:article_id/messages/ 文章內的留言列
   * @apiGroup Message
   * @apiDescription 取得該位使用者下的某文章內的留言內容列表
   *
   * @apiParam {Number} user_id             使用者 ID
   * @apiParam {Number} article_id          文章 ID
   *
   * @apiSuccess (會員) {Number} [id]       留言 ID
   * @apiSuccess (會員) {Array}  [user]     留言者
   * @apiSuccess (會員) {Number} [user.id]  留言者 ID
   * @apiSuccess (訪客) {String} [user]     留言者
   * @apiSuccess {String} user.name         留言者名稱
   * @apiSuccess {String} content           內容
   * @apiSuccess {Datetime} created_at      建立時間
   * @apiSuccessExample {json} 成功:
   *     HTTP/1.1 200 OK
   *     [
   *         {
   *             "id": 1,
   *             "user": {
   *                 "id": 1,
   *                 "name": "OA Wu"
   *               },
   *             "content": "留言內容",
   *             "created_at": "2017-04-01 12:13:14"
   *         },
   *         {
   *             "id": 2,
   *             "user": "訪客",
   *             "content": "留言內容",
   *             "created_at": "2017-04-01 12:13:14"
   *         }
   *     ]
   *
   * @apiUse MyError
   */

  /**
   * @apiName CreateArticleMessage
   * @api {post} users/:user_id/articles/:article_id/messages/ 新增留言
   * @apiGroup Message
   * @apiDescription 新增指定的該位使用者下的某文章內的留言
   *
   * @apiParam {Number} user_id             使用者 ID
   * @apiParam {Number} article_id          文章 ID
   *
   * @apiParam {String} content             留言內容
   *
   * @apiPermission loginMaybe
   * @apiUse loginMaybe
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName UpdateArticleMessage
   * @api {put} users/:user_id/articles/:article_id/messages/:id 修改留言
   * @apiGroup Message
   * @apiDescription 修改指定的該位使用者下的某文章內的特定留言
   *
   * @apiParam {Number} user_id             使用者 ID
   * @apiParam {Number} article_id          文章 ID
   * @apiParam {Number} id                  留言 ID
   *
   * @apiParam {String} content             留言內容
   *
   * @apiPermission loginMaybe
   * @apiUse loginMaybe
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */

  /**
   * @apiName DeleteArticleMessage
   * @api {delete} users/:user_id/articles/:article_id/messages/:id 刪除留言
   * @apiGroup Message
   * @apiDescription 刪除指定的該位使用者下的某文章內的特定留言
   *
   * @apiParam {Number} user_id             使用者 ID
   * @apiParam {Number} article_id          文章 ID
   * @apiParam {Number} id                  留言 ID
   *
   * @apiParam {String} content             留言內容
   *
   * @apiPermission loginMaybe
   * @apiUse loginMaybe
   *
   * @apiUse MySuccess
   * @apiUse MyError
   */
