<?php
/*
* qiqUpload.php: rev.14031401
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/
/*
* [更新履歴]
* 2014/03/14: DOCUMENT_ROOTを参照するように
*/
if (!defined('DOCUMENT_ROOT'))
{
  define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
}
class qiqUpload

{
  var $name; // フォームの値の名前
  var $dir; // 保存ディレクトリ
  var $format; // 名前生成フォーマット
  var $path; // 保存ファイル名
  var $tmp_id; // 一時ID
  var $tmp_dir; // 一時保存ディレクトリ
  var $type; // 拡張子タイプ
  var $ext; // 拡張子
  var $mode; // function
  var $delete; // 削除
  var $basename;
  function __construct($array = null)
  {
    $this->name = 'file';
    $this->dir = '.';
    $this->format = '%d';
    $this->ext = 'dat';
    $this->tmp_dir = '.';
    if (is_array($array))
    {
      foreach($array AS $key => $value)
      {
        $this->$key = $value;
      }
    }
    if (!$this->tmp_id) $this->tmp_id(true);
    /* ここで指定されたtmp_dir内での古いファイルを削除 */
    $dir = opendir($this->tmp_dir);
    while ($e = readdir($dir))
    {
      if (substr($e, 0, 3) != 'tmp') continue;
      $fname = sprintf('%s/%s', $this->tmp_dir, $e);
      $mtime = filemtime($fname);
      if (time() - $mtime > 3600) @unlink($fname);
    }
  }
  function dir($dir = '')
  {
    if ($dir) $this->dir = $dir;
    if (!file_exists($this->dir)) @mkdir($this->dir, 0777);
    return $this->dir;
  }

  function tmp_dir($tmp_dir = '')
  {
    if ($tmp_dir)
      $this->tmp_dir = $tmp_dir;
    if (!file_exists($this->tmp_dir))
      @mkdir($this->tmp_dir, 0777);
    return $this->tmp_dir;
  }

  function type()
  {
    return $this->type;
  }

  function mode()
  {
    return $this->mode;
  }

  function ext()
  {
    return $this->ext;
  }

  function format()
  {
    $format = sprintf('%s/%s', $this->dir() , $this->format);
    $arg = func_get_args();
    $this->path = vsprintf($format, $arg);
    return $this->path;
  }
  function path()
  {
    if (!$this->path)
    {
      $this->path = $this->format(func_get_args());
    }
    return $this->path;
  }
  function tmp_id($f = false)
  {
    if ($f) $this->tmp_id = 'tmp' . md5(uniqid(mt_rand() , true));
    return $this->tmp_id;
  }
  function tmp_path()
  {
    return sprintf("%s/%s%s", $this->tmp_dir, $this->tmp_id() , $this->ext);
  }
  function save_tmp($index = 0)
  {
    $file = $_FILES[$this->name]['tmp_name'];
    if (is_array($file)) $file = $_FILES[$this->name]['tmp_name'][$index];
    if (!$file || $file == 'none' || !filesize($file))
    {
      return;
    }
    $name = $_FILES[$this->name]['name'];
    if (is_array($name)) $name = $_FILES[$this->name]['name'][$index];
    $basename = ($this->basename) ? $this->basename : $name;
    $this->ext = strtolower(strrchr($basename, '.'));
    if (!is_uploaded_file($file))
    {
      $this->error = "ファイルのアップロードに失敗しました";
      return;
    }
    $tmpfile = $this->tmp_path();
    move_uploaded_file($file, $tmpfile);
  }
  function purge($complete = false)
  {
    $tmp = $this->tmp_path();
    $this->tmp_id(true);
    if (file_exists($tmp))
    {
      if ($complete)
      {
        unlink($tmp);
      }
      else
      {
        $new = $this->tmp_path();
        rename($tmp, $new);
      }
    }
  }
  function save()
  {
    $tmp = $this->tmp_path();
    $img = $this->path();
    if ($this->delete) @unlink($img);
    if (file_exists($tmp)) rename($tmp, $img);
  }
  function delete()
  {
    @unlink($this->path());
  }
  function realpath()
  {
    return realpath($this->path());
  }
  function relpath()
  {
    return str_replace(DOCUMENT_ROOT, '', $this->realpath());
  }
}
// basenameの代わり(php5で日本語の場合でおかしいため)
function _basename($path)
{
  $path = str_replace("\\", '/', $path); // for Windows, chrome
  $elem = explode('/', $path);
  return array_pop($elem);
}
function ext($path)
{
  $basename = _basename($path);
  return strrchr($basename, '.');
}