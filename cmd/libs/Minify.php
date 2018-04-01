<?php
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

include_once PATH_CMD_LIBS . 'Minify' . DIRECTORY_SEPARATOR . 'CSSMin.php';
include_once PATH_CMD_LIBS . 'Minify' . DIRECTORY_SEPARATOR . 'JSMin.php';
include_once PATH_CMD_LIBS . 'Minify' . DIRECTORY_SEPARATOR . 'HTMLMin.php';

class Minify {
  private static $list = array ();

  private static function asset () {
    $list = array_filter (func_get_args ());
    if (isset (self::$list[$key = implode ('_', $list)]))
      return self::$list[$key];

    $format = array_shift ($list);
    $asset_path = PATH_ASSET;

    if (!MINIFY) return self::$list[$key] = $list;

    if (!(in_array ($format, array ('css', 'js')) && file_exists ($asset_path) && is_writable ($asset_path) && (count ($list) == count (array_filter ($list, function ($t) { return is_readable (PATH . $t); })))))
      return self::$list[$key] = $list;

    $content = "\xEF\xBB\xBF" . implode ("\n", array_map (function ($t) {
        $bom = pack ('H*','EFBBBF');
        return preg_replace ("/^$bom/", '', Min::readFile (PATH . $t));
      }, $list));

    $class = $format == 'js' ? 'JSMin' : 'CSSMin';
    $content = $class::minify ($content);

    if (!write_file ($asset_path . ($name = md5 ($content) . '.' . $format), $content))
      return self::$list[$key] = $list;

    return self::$list[$key] = array ('/asset/' . $name);
  }
  public static function css () {
    $list = array_filter (func_get_args ());
    return call_user_func_array ('self::asset', array_merge (array ('css'), $list));
  }
  public static function js () {
    $list = array_filter (func_get_args ());
    return call_user_func_array ('self::asset', array_merge (array ('js'), $list));
  }
  public static function readFile ($file) {
    if (!file_exists ($file)) return false;
    if (function_exists ('file_get_contents')) return file_get_contents ($file);
    if (!$fp = @fopen ($file, 'rb')) return false;

    $data = '';
    flock ($fp, LOCK_SH);
    if (filesize ($file) > 0) $data =& fread ($fp, filesize ($file));
    flock ($fp, LOCK_UN);
    fclose ($fp);

    return $data;
  }
}
