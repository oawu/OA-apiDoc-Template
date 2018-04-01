<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

include_once 'libs' . DIRECTORY_SEPARATOR . 'Define.php';
include_once 'libs' . DIRECTORY_SEPARATOR . 'Logger' . PHP;
system ('clear');

$log = new Logger ();

function headerText ($log) {
  system('clear');
  $log->append (str_repeat ('═', CLI_LEN), "\n\n", str_repeat (' ', 25),
    color ('【 歡迎使用 OA\'s S3 上傳工具 】', 'y'), '', color ('v 2.0', 'N'), "\n",
    str_repeat (' ', CLI_LEN - 9), 
    color ('by', 'N') . ' ' . color ('OA Wu', 'W'), "\n", str_repeat ('═', CLI_LEN), "\n",
    ' ※ 注意事項 ※', "\n", color (str_repeat ('─', CLI_LEN), 'N'), "\n",
    ' ' . color ('◎', 'N') . ' 您可以使用本程式將指定的資料上傳至 AWS S3 上。', "\n",
    ' ' . color ('◎', 'N') . ' 若要修改上傳類型資料，請修改 ' . color ('cmd/_oa.php', 'y') . ' 內的 ' . color ('$_dirs', 'R') . ' 變數。', "\n",
    ' ' . color ('◎', 'N') . ' 以下請依據步驟填寫相關設定值。', "\n", color (str_repeat ('─', CLI_LEN), 'N') . "\n",
    ' ' . color ('◎', 'N') . ' 中途想要停止程式請按 ' . color ('Control', 'W') . color (' + ', 'N') . color ('c', 'W') . ' 離開。', "\n", str_repeat ('═', CLI_LEN) . "\n\n"
    );
}
do {
  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'W') . ' ' . '請輸入 S3 Bucket 名稱：');
  } while (!$bucket = trim (fgets (fopen ("php://stdin", "r"))));

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '請輸入 S3 access Key：');
  } while (!$access = trim (fgets (fopen ("php://stdin", "r"))));

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '請輸入 S3 secret Key：');
  } while (!$secret = trim (fgets (fopen ("php://stdin", "r"))));

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 secret Key：' . color ($secret, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '請選擇協定方式 ' . color ('[1: https, 2: http]', 'N') . '：');

    if (!$protocol = trim (fgets (fopen ("php://stdin", "r")))) $protocol = '1';
  } while (!in_array ($protocol, array ('1', '2')));
  $protocol = $protocol === '1' ? 'https' : 'http';

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 secret Key：' . color ($secret, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請選擇協定方式：' . color ($protocol, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '上傳至' . color ('子目錄', 'r') . '或' . color ('根目錄', 'r') . '，如下範例：', "\n", '   1. 上傳至子目錄：' . color ($protocol . '://' . $bucket . '/' . FNAME . '/', 'C') . color(' [預設]', 'N'), "\n", '   2. 上傳至根目錄：' . color ($protocol . '://' . $bucket . '/', 'C'), "\n", ' ' . color ('➜', 'R') . ' 請輸入您的選擇[1,2]：');

    if (!$usname = trim (fgets (fopen ("php://stdin", "r")))) $usname = '1';
  } while (!in_array ($usname, array ('1', '2')));
  $usname = $usname === '1' ? true : false;

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 secret Key：' . color ($secret, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請選擇協定方式：' . color ($protocol, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '上傳至子目錄或根目錄：' . color ($usname ? '子目錄' : '根目錄', 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '是否' . color ('壓縮', 'W') . '檔案 ' . color ('[1: 壓縮, 2: 不壓縮]', 'N') . '：');
      
    if (!$minify = trim (fgets (fopen ("php://stdin", "r")))) $minify = '1';
  } while (!in_array (strtolower ($minify), array ('1', '2')));
  $minify = $minify === '1' ? true : false;

  do {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 secret Key：' . color ($secret, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請選擇協定方式：' . color ($protocol, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '上傳至子目錄或根目錄：' . color ($usname ? '子目錄' : '根目錄', 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '是否壓縮檔案：' . color ($minify ? '壓縮' : '不壓縮', 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'W') . ' ' . '請確認以上設定後，是否開始上傳 ' . color ('[1: 開始上傳, 2: 關閉程式, 3: 重新填寫]', 'N') . '：');

    if (!$check = trim (fgets (fopen ("php://stdin", "r")))) $check = '1';
  } while (!in_array (strtolower ($check), array ('1', '2', '3')));

  if ($check === '2') {
    headerText ($log);
    $log->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 Bucket 名稱：' . color ($bucket, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 access Key：' . color ($access, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請輸入 S3 secret Key：' . color ($secret, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請選擇協定方式：' . color ($protocol, 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '上傳至子目錄或根目錄：' . color ($usname ? '子目錄' : '根目錄', 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '是否壓縮檔案：' . color ($minify ? '壓縮' : '不壓縮', 'W'), "\n")
        ->append (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
        ->append (' ' . color ('◎', 'N') . ' ' . '請確認以上設定後，是否開始上傳：' . color ('關閉程式', 'W') . "\n");

    $log->append (str_repeat ('═', CLI_LEN), "\n\n", ' ', color ('您已關閉使用 OA\'s S3 上傳工具。', ''), "\n\n", str_repeat ('═', CLI_LEN), "\n\n");
    exit ();
  }
} while ($check === '3');

$option = array (
    'bucket' => $bucket,
    'access' => $access,
    'secret' => $secret,
    'protocol' => $protocol,
    'usname' => $usname,
    'minify' => $minify,
  );

include_once '_oa' . PHP;
