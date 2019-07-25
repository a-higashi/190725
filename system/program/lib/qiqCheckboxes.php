<?php
/*
* qiqCheckboxes.php: rev.13120601
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/
class qiqCheckboxes

{
  var $assoc; //表示用ラベル
  var $separator; //区切り文字
  function __construct($arg = null)
  {
    $this->separator = '、';
    if (is_array($arg))
    {
      foreach($arg AS $key => $value)
      {
        $this->$key = $value;
      }
    }
    if ($this->labelCheck) $this->assoc = $this->labelCheck; // 旧互換
  }
  // チェックを入れる
  function input_check($check)
  {
    $_check = array();
    if (is_array($check))
    {
      foreach($check AS $bit => $onoff)
      {
        $_check[$bit] = 'on';
      }
      return $_check;
    }
    else
    {
      foreach($this->assoc AS $bit => $onoff)
      {
        if ($check & $bit)
        {
          $_check[$bit] = 'on';
        }
      }
      return $_check;
    }
  }
  // ラベル表示
  function label($check, $separator = null, $label = array())
  {
    $labelCheck = array();
    $sep = ($separator) ? $separator : $this->separator;
    $assoc = ($label) ? $label : $this->assoc;
    if (is_array($check))
    {
      foreach($check AS $bit => $onoff)
      {
        $labelCheck[] = $assoc[$bit];
      }
    }
    else
    {
      foreach($this->assoc AS $bit => $onoff)
      {
        if ($check & $bit)
        {
          $labelCheck[] = $assoc[$bit];
        }
      }
    }
    return implode($sep, $labelCheck);
  }
  // 保存用データ生成
  function set_data($check)
  {
    $value = 0;
    foreach($check AS $bit => $onoff)
    {
      $check_bit|= $bit;
    }
    return $check_bit;
  }
}