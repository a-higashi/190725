<?php
/*
 * qiqFramework.php: rev.13120601
 *
 * Copyright (c) dotAster Inc. <http://www.dotAster.com>
 */
class qiqFramework
{
  public $var = array();
  public $html_dir;
  public $load_file;
  public $destructor;
  public $mode;
  public $query = array();
  public $name;
  /* 件数 */
  public $offset;
  public $limit;
  /* DB */
  public $db;
  /* error */
  public $error;
  function __construct()
  {
    $this->var = array(
      'mode'
    );
    $this->import($this->var);
    if (defined('HTMLDIR') && HTMLDIR)
      $this->html_dir = HTMLDIR;

    // json_encode()関数が存在しないなら
    if (!function_exists('json_encode')) {
      // JSON.phpを読み込んで
      require_once 'JSON.php';
      // json_encode()関数を定義する
      function json_encode($value)
      {
        $s = new Services_JSON();
        return $s->encodeUnsafe($value);
      }
      // json_decode()関数を定義する
      function json_decode($json, $assoc = false)
      {
        $s = new Services_JSON($assoc ? SERVICES_JSON_LOOSE_TYPE : 0);
        return $s->decode($json);
      }
    }
  }

  function set_html_dir($dir)
  {
    $this->html_dir = $dir;
  }

  function get_html_dir()
  {
    return $this->html_dir;
  }

  function set_load_file($fname)
  {
    $this->load_file = $fname;
  }

  function get_load_file()
  {
    return $this->load_file;
  }

  function set_destructor($funcname)
  {
    $this->destructor = $funcname;
  }

  function get_destructor()
  {
    return $this->destructor;
  }

  function import($array)
  {
    $this->_var($array);
    if (isset($_GET)) {
      $this->set($_GET, $array, 1);
    }
    if (isset($_POST)) {
      $this->set($_POST, $array, 1);
    }
  }

  function _var($array)
  {
    if (is_array($array)) {
      foreach ($array as $name) {
        if (!isset($this->$name)) {
          $this->$name = '';
        }
      }
    }
  }

  function set($hash, $array, $form = 0)
  {
    if ($form && get_magic_quotes_gpc()) {
      $hash = $this->unquote($hash);
    }
    foreach ($array as $name) {
      if (isset($hash[$name])) {
        $this->$name = $hash[$name];
      }
    }
    $this->convert($array, $form);
    $this->escape($array, $form);
  }

  function unquote($value)
  {
    if (is_array($value)) {
      $value = array_map(array(
        'qiqFramework',
        'unquote'
      ), $value);
    } else if (!is_object($value)) {
      $value = stripslashes($value);
    }
    return $value;
  }

  function _convert($value, $to_enc, $from_enc)
  {
    if (is_array($value)) {
      $array = array();
      foreach ($value AS $k => $v) {
        $array[$k] = $this->_convert($v, $to_enc, $from_enc);
      }
      return $array;
    } else if (is_object($value)) {
      return $value;
    } else {
      $value = mb_convert_encoding($value, $to_enc, $from_enc);
      return mb_convert_kana($value, 'KV');
    }
  }

  function convert($array)
  {
    $val      = array();
    $value    = '';
    $from_enc = '';
    $to_enc   = '';
    foreach ($array as $name) {
      if (isset($this->$name)) {
        $val = $this->$name;
      }
      if (is_array($val)) {
        foreach ($val AS $v) {
          $value .= $v;
        }
      } else if (!is_object($val)) {
        $value .= $val;
      }
    }
    $from_enc = mb_detect_encoding($value);
    $to_enc   = mb_internal_encoding();
    if (isset($this->$name)) {
      foreach ($array as $name) {
        $this->$name = $this->_convert($this->$name, $to_enc, $from_enc);
      }
    }
  }

  function _escape($value, $form = 0)
  {
    if (is_array($value)) {
      $value = array_map(array(
        'qiqFramework',
        '_escape'
      ), $value);
    } else if (!is_object($value)) {
      $value = htmlspecialchars($value, ENT_QUOTES, 'utf-8');
    }
    return $value;
  }

  function escape($array, $form = 0)
  {
    $name = '';
    foreach ($array as $name) {
      $escaped        = "_$name";
      $value          = $this->$name;
      $this->$escaped = $this->_escape($value, $form);
    }
  }

  function clear($array)
  {
    $mode = $this->mode;
    foreach ($array AS $name) {
      unset($this->$name);
      $escaped = "_$name";
      unset($this->$escaped);
    }

    $this->mode = $mode;
  }

  function mb_include($file, $charset = 1)
  {
    if (strstr($file, '://'))
      exit;
    if (is_file($file)) {
      $src  = implode('', file($file, 0));
      $code = mb_convert_encoding($src, mb_internal_encoding(), ($charset == 1) ? mb_detect_encoding($src, mb_detect_order(), 1) : $charset);
      switch (DEBUG) {
        case '2':
          highlight_string($code);
          break;
        case '3':
          $ret   = highlight_string($code, 1);
          $lines = explode("<br />", $ret);
          foreach ($lines AS $n => $line) {
            printf('<tt style="color:black">%04d:</tt> %s<br />', $n + 1, $line);
          }
          break;
        default:
          eval("?> $code<?php ");
          break;
      }
    }
  }

  function main($charset = 0)
  {
    $ret       = 0;
    $this->wakeup();
    $call_func = $this->mode;
    $exit_func = ($this->destructor) ? $this->destructor : "_" . get_class($this);
    if (method_exists($this, $call_func)) {
      $this->$call_func();
      $ret = 1;
    }
    $basename  = addSlashes($this->mode); // メソッドの中で置き換わってるかも
    $load_file = ($this->load_file) ? (($this->html_dir) ? "$this->html_dir/$this->load_file" : $this->load_file) : (($this->html_dir) ? "$this->html_dir/$basename.html" : "$basename.html");
    if (DEBUG) {
      if ($charset) {
        $this->mb_include($load_file, $charset);
      } else {
        include $load_file;
      }
    } else {
      if ($charset) {
        $this->mb_include($load_file, $charset);
      } else {
        @include $load_file;
      }
    }
    if (method_exists($this, $exit_func)) {
      $this->$exit_func();
    }
    return $ret;
  }

  function wakeup()
  {
    register_shutdown_function(array(
      $this,
      'shutdownHandle'
    ));
    ob_start();
  }

  function shutdownHandle()
  {
    $contents = ob_get_contents();
    @ob_end_clean();
    // クリックジャッキング対策 自身と生成元が同じフレーム内に限り、ページを表示することができます。
    header('X-FRAME-OPTIONS: SAMEORIGIN');
    // XSS攻撃を検知させる（検知したら実行させない）。
    header("X-XSS-Protection: 1; mode=block");
    // IEにコンテンツの内容を解析させない（ファイルの内容からファイルの種類を決定させない）。
    header("X-Content-Type-Options: nosniff");
    print trim($contents);
    while (@ob_end_flush());
    exit;
  }
}