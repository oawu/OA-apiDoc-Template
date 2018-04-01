<?php
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2018, OAF2E
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

class OAS3Tool {
  private $log = null;
  private $startT = null;
  private $startMs = array ();
  private $loads = array ();
  private $lfs = array ();
  private $s3fs = array ();
  private $ufs = array ();
  private $dfs = array ();

  private $protocol = null;
  private $bucket = null;
  private $access = null;
  private $secret = null;
  private $usname = true;
  private $minify = false;


  public function __construct ($option = array ()) {
    $this->startT = microtime (true);
    $this->startMs = array ();

    if (!$option) 
      throw new Exception ('參數錯誤(1)！');
    else
      foreach (array ('usname', 'minify', 'protocol', 'bucket', 'access', 'secret') as $key)
        if (!isset ($option[$key])) throw new Exception ('參數錯誤(2)！');
        else $this->$key = $option[$key];

    if (!$this->load ('Logger')) 
      throw new Exception ('載入「紀錄器」資源時，發生錯誤！');
    
    $this->log = new Logger ();
  }
  public function getStartT () { return $this->startT; }
  public function getLog () { return $this->log; }
  public function setLog ($log) { $this->log = $log; return $this; }
  public function logAppend () { call_user_func_array (array ($this->log, 'append'), func_get_args ()); return $this; }
  public function addMemory ($m) { array_push ($this->startMs, $m); return $this; }

  public function load ($name = '') {
    if (!(is_string ($name) && $name)) return true;

    if (!($name && file_exists ($p = PATH_CMD_LIBS . $name . PHP))) return false;
    if (in_array ($name, $this->loads)) return true;

    return (@include_once $p) != false;
  }
  public function loads ($title, $names = array ()) {
    if (!(is_array ($names) && $names)) return true;

    $that = $this;
    if (RowLogger::startAndRunAndEnd ($this, $title, $names, function ($t) use ($that) {
      return !$that->load ($t) ? '載入「' . $t . '」有錯誤！' : '';
    })) throw new Exception ('執行「' . $title . '」時，發生錯誤！');
    return $this;
  }

  public function addDirFiles ($dir, $exts = array (), $rec = true, $hidden = false) {
    $this->lfs[$dir] = array ('dir' => $dir, 'exts' => $exts, 'rec' => $rec, 'hidden' => $hidden);
    return $this;
  }

  public function setDirFiles ($dirs) {
    foreach ($dirs as $key => $dir)
      $this->addDirFiles ($key, isset ($dir[0]) ? $dir[0] : array (), isset ($dir[1]) ? $dir[1] : true, isset ($dir[2]) ? $dir[2] : false);
    return $this;
  }
  public function listLocalFiles ($title) {
    $minify = $this->minify;
    $usname = $this->usname;
    $row = RowLogger::start ($this, $title, $this->lfs);
    
    $this->lfs = $row->run (function ($t) use ($minify, $usname) {
      $fs = array ();
      mergeArrRec (mapDir ($p = PATH . trim ($t['dir'], DIRECTORY_SEPARATOR), $t['rec'] ? 0 : 1, $t['hidden'], $t['exts']), $fs, $p);

      $fs = array_map (function ($f) use ($minify, $usname) {
        if ($minify) {
          $bom = pack ('H*','EFBBBF');

          switch (pathinfo ($f, PATHINFO_EXTENSION)) {
            case 'html': myWriteFile ($f, preg_replace ("/^$bom/", '', HTMLMin::minify (myReadFile ($f)))); break;
            case 'css': myWriteFile ($f, preg_replace ("/^$bom/", '', CSSMin::minify (myReadFile ($f)))); break;
            case 'js': myWriteFile ($f, preg_replace ("/^$bom/", '', JSMin::minify (myReadFile ($f)))); break;
          }
        }
        return array ('path' => $f, 'md5' => md5_file ($f), 'uri' => ($usname ? FNAME . DIRECTORY_SEPARATOR : '') . preg_replace ('/^(' . preg_replace ('/\//', '\/', PATH) . ')/', '', $f));
      }, $fs);

      return $fs;
    }, null, 'arr2d21d');

    $row->setTitle ('本機內' . (($t = count ($this->lfs)) ? '有 ' . number_format ($t) . ' 個' : '沒有') . '檔案等待上傳')
        ->end ();

    return $this;
  }
  public function initS3 ($title) {
    if (RowLogger::startAndRunAndEnd ($this, $title, array (array ($this->access, $this->secret)), function ($t) {
      return S3::init ($t[0], $t[1]) && S3::test () ? '' : '初始化 S3 失敗，請檢查 access、secret 是否有誤。';
    })) throw new Exception ('執行「' . $title . '」時，發生錯誤！');
    return $this;
  }
  public function listS3Files ($title) {
    $usname = $this->usname;
    
    $row = RowLogger::start ($this, $title, $this->s3fs = S3::getBucket ($this->bucket, $usname ? FNAME : null));
    $this->s3fs = $row->run ();
    $row->end ();
    
    return $this;
  }

  public function filterLocalFiles ($title) {
    $s3fs = $this->s3fs;
    $row = RowLogger::start ($this, $title, $this->lfs);
    $this->ufs = $row->run (null, function ($t) use ($s3fs) {
      foreach ($s3fs as $s3f)
        if (($s3f['name'] == $t['uri']) && ($s3f['hash'] == $t['md5']))
          return false;
      return true;
    });

    $row->setTitle ((($t = count ($this->ufs)) ? '有 ' . number_format ($t) . ' 個' : '沒有') . '檔案需要上傳')
        ->end ();

    return $this;
  }
  public function uploadLocalFiles ($title) {
    $bucket = $this->bucket;
    if (RowLogger::startAndRunAndEnd ($this, $title, $this->ufs, function ($t) use ($bucket) {
      return !S3::putFile ($t['path'], $bucket, $t['uri']) ? ' 檔案「' . $t['path'] . '」上傳失敗！' : '';
    })) throw new Exception ('執行「' . $title . '」時，發生錯誤！');
    return $this;
  }
  public function filterS3Files ($title) {
    $lfs = $this->lfs;

    $row = RowLogger::start ($this, $title, $this->s3fs);
    $this->dfs = $row->run (null, function ($t) use ($lfs) {
      foreach ($lfs as $lf)
        if ($t['name'] == $lf['uri'])
          return false;
      return true;
    });

    $row->setTitle ('S3 上' . (($t = count ($this->dfs)) ? '有 ' . number_format ($t) . ' 個' : '沒有') . '檔案需要刪除')
        ->end ();

    return $this;
  }
  public function deletwS3Files ($title) {
    $bucket = $this->bucket;
    if (RowLogger::startAndRunAndEnd ($this, $title, $this->dfs, function ($t) use ($bucket) {
        return !S3::deleteObject ($bucket, $t['name']) ? ' 檔案「' . $file['name'] . '」刪除失敗！' : '';
    })) throw new Exception ('執行「' . $title . '」時，發生錯誤！');
    return $this;
  }
  public function memoryUsage () {
    $size = memoryUnit (memory_get_usage ());
    return $this->logAppend (' ' . color ('➜', 'W') . ' ' . color ('使用記憶體：', 'R') . '' . color ($size[0], 'W') . ' ' . $size[1] . "\n");
  }
  public function timeUsage () {
    return $this->logAppend (' ' . color ('➜', 'W') . ' ' . color ('執行時間：', 'R') . '' . color (round (microtime (true) - $this->startT, 4), 'W') . ' 秒' . "\n");
  }
  public function usage () {
    $s1 = memoryUnit (memory_get_usage ());
    $s2 = memoryUnit (array_sum ($this->startMs));

    return $this->logAppend (' ' . color ('➜', 'W') . ' ' . color ('累積使用記憶體量', 'R') . '：' . str_repeat (' ', 49) . color ($s2[0], 'W') . ' ' . $s2[1] . "\n")
                ->logAppend (color (str_repeat ('─', CLI_LEN), 'N'), "\n")
                ->logAppend (' ' . color ('➜', 'W') . ' ' . color ('目前使用記憶體量', 'R') . '：' . str_repeat (' ', 49) . color ($s1[0], 'W') . ' ' . $s1[1] . "\n");
  }
  public function url () {
    return $this->logAppend ("\n ", color ('➜', 'R') . " " . color ('您的網址是', 'G') . "：" . color ($this->protocol . '://' . $this->bucket . '/' . ($this->usname ? FNAME . '/' : ''), 'W'), "\n\n");
  }
}
