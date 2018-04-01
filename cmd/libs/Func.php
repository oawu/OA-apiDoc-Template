<?php
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

if (!function_exists ('arr2d21d')) {
  function arr2d21d ($arr) {
    $msgs = array ();
    foreach ($arr as $val)
      if (is_array ($val)) $msgs = array_merge ($msgs, $val);
      else array_push ($msgs, $val);
    return $msgs;
  }
}

if (!function_exists ('mergeArrRec')) {
  function mergeArrRec ($fs, &$rfs, $p = null) {
    if (!($fs && is_array ($fs))) return false;

    foreach ($fs as $k => $f)
      if (is_array ($f)) $k . mergeArrRec ($f, $rfs, ($p ? rtrim ($p, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $k);
      else array_push ($rfs, ($p ? rtrim ($p, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $f);
  }
}


if (!function_exists ('mapDir')) {
  function mapDir ($sDir, $dDir = 0, $hidden = false, $exts = array ()) {
    if ($fp = @opendir ($sDir)) {
      $fs = array (); $ndDir  = $dDir - 1; $sDir = rtrim ($sDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
      while (false !== ($f = readdir ($fp))) { if (trim ($f, '.') == '' || (($hidden === false) && ($f[0] === '.')) || is_link ($f) || ($f == 'cmd')) continue; if ((($dDir < 1) || ($ndDir > 0)) && @is_dir ($sDir . $f)) $fs[$f] = mapDir ($sDir . $f . DIRECTORY_SEPARATOR, $ndDir, $hidden, $exts); else if (!$exts || in_array (pathinfo ($f, PATHINFO_EXTENSION), $exts)) array_push ($fs, $f); else; }
      closedir ($fp);
      return $fs;
    }
    return false;
  }
}

if (!function_exists ('myReadFile')) {
  function myReadFile ($f) {
    if (!file_exists ($f)) return false;
    if (function_exists ('file_get_contents')) return file_get_contents ($f);
    if (!$fp = @fopen ($f, 'rb')) return false;
    $data = ''; flock ($fp, LOCK_SH); if (filesize ($f) > 0) $data =& fread ($fp, filesize ($f)); flock ($fp, LOCK_UN); fclose ($fp);
    return $data;
  }
}
if (!function_exists ('myWriteFile')) {
  function myWriteFile ($p, $d, $m = 'wb') {
    if (!$fp = @fopen ($p, $m)) return false;
    flock ($fp, LOCK_EX);
    fwrite ($fp, $d);
    flock ($fp, LOCK_UN);
    fclose ($fp);
    return true;
  }
}

if (!function_exists ('params')) {
  function params ($params, $keys) {
    $ks = $return = $result = array ();

    if (!$params) return $return;
    if (!$keys) return $return;

    foreach ($keys as $key)
      if (is_array ($key)) foreach ($key as $k) array_push ($ks, $k);
      else  array_push ($ks, $key);

    $key = null;

    foreach ($params as $param)
      if (in_array ($param, $ks)) if (!isset ($result[$key = $param])) $result[$key] = array (); else ;
      else if (isset ($result[$key])) array_push ($result[$key], $param); else ;

    foreach ($keys as $key)
      if (is_array ($key))  foreach ($key as $k) if (isset ($result[$k])) $return[$key[0]] = isset ($return[$key[0]]) ? array_merge ($return[$key[0]], $result[$k]) : $result[$k]; else;
      else if (isset ($result[$key])) $return[$key] = isset ($return[$key]) ? array_merge ($return[$key], $result[$key]) : $result[$key]; else;

    return $return;
  }
}