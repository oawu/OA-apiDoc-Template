<?php
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

include_once 'libs' . DIRECTORY_SEPARATOR . 'Define.php';
include_once 'libs' . DIRECTORY_SEPARATOR . 'Func' . PHP;
include_once 'libs' . DIRECTORY_SEPARATOR . 'Logger' . PHP;

$file = array_shift ($argv);

$argv = params ($argv, array (
  array ('-b', '-bucket'),
  array ('-a', '-access'),
  array ('-s', '-secret'),
  array ('-u', '-upload'),
  array ('-m', '-minify'),
  array ('-n', '-usname'),
  array ('-p', '-protocol')));

$log = new Logger ();
if (!(isset ($argv['-b'][0]) && ($bucket = trim ($argv['-b'][0], '/')) && isset ($argv['-a'][0]) && ($access = trim ($argv['-a'][0])) && isset ($argv['-s'][0]) && ($secret = trim ($argv['-s'][0])))) {
  $log->append ("\n", str_repeat ('═', 80), "\n\n",
      ' ' . color ('◎', 'R') . ' ' . color ('錯誤囉！', 'r') . color ('請確認參數是否正確，分別需要', 'p') . ' ' . color ('-b', 'W') . '、' . color ('-a', 'W') . '、' . color ('-s', 'W') . color (' 的參數！', 'p') . ' ' . color ('◎', 'R'),
      "\n\n" . str_repeat ('═', 80) . "\n\n"
    );
  exit ();
}

$option = array (
    'bucket' => $bucket,
    'access' => $access,
    'secret' => $secret,
    'protocol' => isset ($argv['-p'][0]) && ($argv['-p'][0] = strtolower (trim ($argv['-p'][0]))) && in_array ($argv['-p'][0], array ('https', 'http')) ? $argv['-p'][0] : 'https',
    'usname' => !isset ($argv['-n'][0]) ? true : (is_numeric ($argv['-n'][0] = trim ($argv['-n'][0])) && $argv['-n'][0]),
    'minify' => !isset ($argv['-m'][0]) ? true : (is_numeric ($argv['-m'][0] = trim ($argv['-m'][0])) && $argv['-m'][0]),
  );

include_once '_oa' . PHP;