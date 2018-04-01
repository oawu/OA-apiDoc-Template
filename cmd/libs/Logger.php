<?php
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

if (!function_exists ('color')) {
  function color ($str, $fc = null, $bc = null) {
    if (!strlen ($str)) return "";
    if (!CLI) return $str;

    $nstr = "";
    $keys = array ('n' => '30', 'w' => '37', 'b' => '34', 'g' => '32', 'c' => '36', 'r' => '31', 'p' => '35', 'y' => '33');
    if ($fc && in_array (strtolower ($fc), array_map ('strtolower', array_keys ($keys)))) {
      $fc = !in_array (ord ($fc[0]), array_map ('ord', array_keys ($keys))) ? in_array (ord ($fc[0]) | 0x20, array_map ('ord', array_keys ($keys))) ? '1;' . $keys[strtolower ($fc[0])] : null : $keys[$fc[0]];
      $nstr .= $fc ? "\033[" . $fc . "m" : "";
    }
    $nstr .= $bc && in_array (strtolower ($bc), array_map ('strtolower', array_keys ($keys))) ? "\033[" . ($keys[strtolower ($bc[0])] + 10) . "m" : "";

    if (substr ($str, -1) == "\n") { $str = substr ($str, 0, -1); $has_new_line = true; } else { $has_new_line = false; }
    $nstr .=  $str . "\033[0m";
    $nstr = $nstr . ($has_new_line ? "\n" : "");

    return $nstr;
  }
}
if (!function_exists ('memoryUnit')) {
  function memoryUnit ($s) {
    $u = array ('B','KB','MB','GB','TB','PB');
    return array (@round ($s / pow (1024, ($i = floor (log ($s, 1024)))), 2), $u[$i]);
  }
}

class Logger {
  private $log = '';

  public function __construct () {
    $this->clean ();
  }

  public function get () { return $this->log; }
  public function set ($log) { $this->log = $log; return $this; }
  public function clean () { return $this->set (''); }

  public function append () {

    $strs = implode ('', array_map (function ($str)  {
      if ($str === null) return '';
      else if (is_string ($str) && $str) return call_user_func_array ('color', array ($str));
      else if (is_array ($str) && $str) return call_user_func_array ('color', $str);
      else return '';
    }, func_get_args ()));

    if (CLI) echo $strs; else $this->log = $this->log . $strs;

    return $this;
  }
}

class RowLogger {
  private static $maxLen = CLI_LEN;
  private $str = '';
  private $c = 0;
  private $i = 0;
  private $m = 0;
  private $n = 0;
  public $tool = null;

  public function __construct ($tool, $str, $els) {
    $this->m = $this->n = memory_get_usage ();

    $this->i = 0;
    $this->c = count ($els);
    $this->els = $els;
    $this->tool = $tool;
    $this->str = $str;
  }
  public static function start ($tool, $str, $els) {
    $row = new RowLogger ($tool, $str, $els);
    $row->progress ();
    return $row;
  }
  public static function startAndRunAndEnd ($tool, $str, $els, $fn, $tn = null, $cb = null) {
    return RowLogger::start ($tool, $str, $els)->runAndEnd ($fn, $tn, $cb);
  }
  private function setMemory () {
    $this->m = memory_get_usage () > $this->m ? memory_get_usage () : $this->m;
    return array_merge (memoryUnit ($t = $this->m - $this->n), array ($t));
  }
  public function run ($fn = null, $tn = null, $cb = null) {
    $that = $this;
    return array_filter ($cb && is_callable ($cb) ? $cb (array_map (function ($t) use ($fn, $that) { return $fn && is_callable ($fn) && $that->progress () ? $fn ($t) : $t; }, $this->els)) : array_map (function ($t) use ($fn, $that) { return $fn && is_callable ($fn) && $that->progress () ? $fn ($t) : $t; }, $this->els), function ($t) use ($fn, $tn, $that) { return $tn && is_callable ($tn) && (!($fn && is_callable ($fn)) && $that->progress ()) ? $tn ($t) : $t; });
  }
  public function setTitle ($str) {
    $this->str = $str;
    return $this;
  }
  public function runAndEnd ($fn, $tn = null, $cb = null) {
    $that = $this;
    return ($t = $this->run ($fn, $tn, $cb)) ? $this->stop ($t) : !$this->end ();
  }
  public function progress () {
    $this->i = ($this->i > $this->c ? $this->c : $this->i) + 1;
    preg_match_all ('/(?P<c>[\x{4e00}-\x{9fa5}])/u', $this->str, $m);
    $s = $this->setMemory ();
    $str = sprintf (' ' . color ('➜', 'W') . ' ' . color ($this->str . '(' . number_format ($this->i - 1) . '/' . number_format ($this->c) . ')', 'g') . " - % 3d%% ", $this->c ? ceil ((($this->i - 1) * 100) / $this->c) : 100);
    $str = sprintf ("\r% -" . (RowLogger::$maxLen + 11 + count ($m['c'])) . "s" .  color (sprintf ('% 7s', $s[0]), 'W') . ' ' . $s[1] . " ", $str);

    $this->tool->getLog ()->append ($str);
    return $this;
  }
  public function end ($f = '完成!') {
    $this->i = $this->c + 1;
    preg_match_all('/(?P<c>[\x{4e00}-\x{9fa5}])/u', $this->str, $m);
    preg_match_all('/(?P<c>[\x{4e00}-\x{9fa5}])/u', $f, $n);

    $s = $this->setMemory ();
    $str = sprintf (' ' . color ('➜', 'W') . ' ' . color ($this->str . '(' . number_format ($this->i - 1) . '/' . number_format ($this->c) . ')', 'g') . " - % 3d%% " . '- ' . color ($f, 'C'), $this->c ? ceil ((($this->i - 1) * 100) / $this->c) : 100);
    $str = sprintf ("\r% -" . (RowLogger::$maxLen + 11 + count ($m['c']) + count ($n['c']) + 11) . "s" .  color (sprintf ('% 7s', $s[0]), 'W') . ' ' . $s[1] . " " . "\n", $str);

    $this->tool->addMemory ($s[2]);
    $this->tool->getLog ()->append ($str);
    return $this;
  }
  public function stop ($ers = array ()) {
    if (!$ers) return false;

    $this->tool->getLog ()->append ("\n", str_repeat ('=', 80), "\n")
              ->append (" ", color ('➜', 'W'), ' ', color ('有發生錯誤！', 'r'), "\n")
              ->append ($ers ? str_repeat ('-', 80) . "\n" . implode ("\n" . color (str_repeat ('-', 80), 'N') . "\n", array_map (function ($er) { return ' ' . color ('※', 'N') . ' ' . $er; }, $ers)) . "\n" : null)
              ->append (str_repeat ('=', 80), "\n");
    return $this;
  }
}