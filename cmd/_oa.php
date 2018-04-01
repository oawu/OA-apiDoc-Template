<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

include_once '_dirs' . PHP;
include_once 'libs' . DIRECTORY_SEPARATOR . 'OAS3Tool' . PHP;
system ('clear');

$tool = null;

try {
  if (!isset ($option)) throw new Exception ('參數錯誤！');

  $tool = new OAS3Tool ($option);
    
  $tool->logAppend ("\n", str_repeat ('═', CLI_LEN), "\n", array (' ◎ 執行開始 ◎', 'P'), str_repeat (' ', 46), '[ ', array ('OA S3 Tools v2.0', 'Y'), ' ]', "\n", str_repeat ('═', CLI_LEN), "\n");

  $tool->loads ('載入所需資源', array ('Func', 'Minify', 'S3'))
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->setDirFiles ($_dirs);

  $tool->listLocalFiles ('列出本機內檔案')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->initS3 ('初始化 S3 工具')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
       ->listS3Files ('取得 S3 上目前的檔案')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->filterLocalFiles ('過濾上傳的檔案')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->uploadLocalFiles ('上傳檔案')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->filterS3Files ('過濾 S3 上需要刪除的檔案')
       ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n");

  $tool->deletwS3Files ('刪除 S3 上面的檔案')
       ->logAppend (str_repeat ('═', CLI_LEN), "\n");

  $tool->usage ();
  $tool->logAppend (str_repeat ('═', CLI_LEN), "\n", array (' ◎ 執行結束 ◎', 'P'), str_repeat (' ', 53), sprintf ('%20s', '[ ' . color (round (microtime (true) - $tool->getStartT (), 4), 'Y') . ' ' . color ('秒', 'y') . ' ]'), "\n", str_repeat ('═', CLI_LEN), "\n");
  $tool->url ();


  if (!CLI) {
    header ('Content-Type: application/json', 'true');
    echo json_encode (array ('status' => true, 'message' => nl2br(str_replace(' ', '&nbsp;', $tool->getLog ()->get ()))));
  }
} catch (Exception $e) {
  if (CLI) {
    echo $e->getMessage ();
    exit ();
  } else {
    $code = 405;
    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    if (substr (php_sapi_name (), 0, 3) == 'cgi') header ('Status: ' . $code . ' Method Not Allowed', true);
    else if (($server_protocol == 'HTTP/1.1') || ($server_protocol == 'HTTP/1.0')) header ($server_protocol . ' ' . $code . ' Method Not Allowed', true, $code);
    else header ('HTTP/1.1 ' . $code . ' Method Not Allowed', true, $code);

    header ('Content-Type: application/json', 'true');
    echo json_encode (array ('status' => false, 'message' => $e->getMessage ()));

    exit ();
  }
}
