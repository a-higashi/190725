<?php
/*
* qiqSort.php: rev.13120601
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/
/*
* usage:
*

require_once 'qiqSort.php';

$objSort = new qiqSort($param);
if ($this->sort) $objSort->set_key($this->sort);
if ($this->dir) $objSort->set_dir($this->dir);

$SQL = "SELECT * FROM table ORDER BY ". $objSort->get_sql();

echo implode(' | ', $objSort->get_list());

*/
define('qiqSort_TAG', 'qiqSort');

class qiqSort
{
  public $key = '';
  public $tag = '';
  public $dir = '';
  public $param = '';
  public $mark = '';
  public $default_key = '';
  public $default_dir = '';
  function __construct($param)
  {
    /* デフォルト値 */
    $default = array(
      'tag' => 'view',
      'mark' => array(
        '▲',
        '▼'
      ) ,
      'default_key' => 'priority',
      'default_dir' => 1,
      'set_key' => 'set_key',
      'set_dir' => 'set_dir'
    );
    $this->labelDir = array(-1 => 'ASC',
      1 => 'DESC'
    );
    /* データはセッションに保存 */
    if (!isset($_COOKIE['PHPSESSID'])) session_start();
    /* デフォルト値をメンバ変数に設定 */
    foreach($default AS $key => $value)
    {
      $this->$key = $value;
    }
    /* パラメータをメンバ変数に設定 */
    foreach($param AS $key => $value)
    {
      $this->$key = $param[$key];
    }
    /* 保存されてるソートキーをセッションから取得 */
    if (isset($_SESSION[qiqSort_TAG][$this->tag]['key'])) {
      $this->key = $_SESSION[qiqSort_TAG][$this->tag]['key'];
    }
    if (!$this->key) $this->key = $this->default_key;
    /* 保存されてるソート方向をセッションから取得 */
    if (isset($_SESSION[qiqSort_TAG][$this->tag]['dir'][$this->key])) {
      $this->dir = $_SESSION[qiqSort_TAG][$this->tag]['dir'][$this->key];
    }
    if (!abs($this->dir)) $this->dir = $this->default_dir;
  }
  /* ソートキー設定 */
  function set_key($key)
  {
    $this->key = $_SESSION[qiqSort_TAG][$this->tag]['key'] = $key;
  }
  /* ソート方向設定 */
  function set_dir($dir)
  {
    $this->dir = $_SESSION[qiqSort_TAG][$this->tag]['dir'][$this->key] = $dir;
  }
  /* ソート用リンク取得(配列) */
  function get_list()
  {
    $label = '';
    $item  = array();
    foreach($this->item AS $key => $data)
    {
      $label = $data['label'];
      if ($this->key == $key)
      {
        $item[] = sprintf('%s <a href="%s?%s=%s&%s">%s</a>', $label, MYSELF, $this->set_dir, $this->dir * -1, $this->param, $this->mark[($this->dir == - 1) ? 0 : 1]);
      }
      else
      {
        $item[] = sprintf('<a href="%s?%s=%s&%s">%s</a>', MYSELF, $this->set_key, $key, $this->param, $label);
      }
    }
    return $item;
  }
  /* ソート用リンク取得(一件) */
  function get_item($key)
  {
    $data = $this->item[$key];
    $label = $data['label'];
    if ($this->key == $key)
    {
      $item = sprintf('%s <a href="%s?%s=%s&%s">%s</a>', $label, MYSELF, $this->set_dir, $this->dir * -1, $this->param, $this->mark[($this->dir == - 1) ? 0 : 1]);
    }
    else
    {
      $item = sprintf('<a href="%s?%s=%s&%s">%s</a>', MYSELF, $this->set_key, $key, $this->param, $label);
    }
    return $item;
  }
  /* ソート用SQL文取得(ORDER BY の後に続ける) */
  function get_sql()
  {
    foreach($this->item AS $key => $data)
    {
      if ($key == $this->key)
      {
        return sprintf('%s %s', $data['column'], $this->labelDir[$this->dir]);
      }
    }
    return sprintf('%s %s', $this->item[$this->default_key]['column'], $this->labelDir[$this->default_dir]);
  }
}