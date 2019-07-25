<?php
/*
* qiqForm.php: rev.14031801
*
* Copyright (c) dotAster Inc. <http://www.dotAster.com>
*/

function str($string)
{
  if (get_magic_quotes_gpc()) $string = stripslashes($string);
  return $string;
}

function _string($string)
{
  return htmlspecialchars(str($string));
}

function _str($string)
{
  return _string($string);
}

function ifnull($expr, $val)
{
  return ($expr) ? $expr : $val;
}

function datenull($format, $date, $alt = null)
{
  return ($date) ? date($format, $date) : $alt;
}

function prefix($prefix, $str, $null = null)
{
  return ($str) ? "{$prefix}{$str}" : $null;
}

function suffix($str, $suffix, $null = null)
{
  return ($str) ? "{$str}{$suffix}" : $null;
}

/* 間に変数入れ込むパターン */

function betfix($pre, $str, $suf, $null = null)
{
  return ($str) ? "{$pre}{$str}{$suf}" : $null;
}

function nbsp($expr)
{
  return ifnull($expr, '&nbsp;');
}

function _http_build_query($query)
{
  foreach($query AS $k => $v)
  {
    if ($v) $q[$k] = $v;
  }

  return (isset($q)) ? http_build_query($q) : null;
}

function hidden($form, $ref = null)
{
  $ret = '';
  foreach($form AS $name => $value)
  {
    if (!$value) continue;
    if (is_array($value))
    {
      $ret.= hidden($value, $name);
    }
    else
    {
      $_name = ($ref) ? sprintf('%s[%s]', _str($ref) , _str($name)) : _str($name);
      $ret.= sprintf('<input type="hidden" name="%s" value="%s">', $_name, _str($value)) . "\n";
    }
  }

  return $ret;
}

function checked($bool, $text = 'CHECKED')
{
  return ($bool) ? $text : '';
}

/*
* OLD
*
function
radio1($name, $label, $value, $key, $param = null, $class = null)
{
$ret .= sprintf('<label><input type="radio" name="%s" value="%s"', $name, $key);
if ($value == $key) $ret .= ' CHECKED';
if ($param) $ret .= " {$param}";
$ret .= " /><span class=\"{$class}\">";
if ($label[$key]) $ret .= $label[$key];
$ret .= "</span></label>";

return $ret;
}

*/

function radio1($name, $label, $value, $key, $param = null, $class = null)
{
  $id = sprintf("%s_%s", $name, $key);
  $ret = ''; //'<span class="nowrap">';
  $ret.= sprintf('<input type="radio" name="%s" value="%s" id="%s"', $name, $key, $id);
  if ($value == $key) $ret.= ' CHECKED';
  if ($param) $ret.= " {$param}";
  $ret.= " />";
  if ($label[$key]) $ret.= "<label class=\"label_radio {$class}\" for=\"$id\">{$label[$key]}</label>";

  // $ret .= "</span>";

  return $ret;
}

/*
* OLD
*
function
radio($name, $hash, $value, $param = '', $sep = '', $class = '')
{
foreach ($hash AS $k => $v) {
$r = sprintf('<label><input type="radio" name="%s" value="%s"', $name, $k);
if ($value == $k) $r .= ' CHECKED';
if ($param) $r .= " {$param}";
$r .= " />";
if ($v) $r .= "<span class=\"{$class}\">{$v}</span>";
$r .= "</label>";
$ret[] = $r;
}

return implode($sep, $ret);
}

*/

function radio($name, $hash, $value, $param = '', $sep = '', $class = '')
{
  foreach($hash AS $k => $v)
  {
    $id = sprintf("%s_%s", $name, $k);
    $r = sprintf('<span class="nowrap"><input type="radio" name="%s" value="%s" id="%s"', $name, $k, $id);
    if ($value == $k) $r.= ' CHECKED';
    if ($param) $r.= " {$param}";
    $r.= " />";
    if ($v) $r.= "<label class=\"label_radio {$class}\" for=\"$id\">{$v}</label>";
    $r.= "</span>\n";
    $ret[] = $r;
  }

  return implode($sep, $ret);
}

function checkif($name, $checked, $attr = null)
{
  $ret.= sprintf('<input type="checkbox" name="%s"', $name);
  if ($checked) $ret.= ' CHECKED';
  if (is_array($attr))
  {
    foreach($attr AS $k => $v)
    {
      $ret.= sprintf('%s="%s" ', $k, $v);
    }
  }
  elseif ($attr)
  {
    $ret.= $attr;
  }

  $ret.= " />";
  return $ret;
}

/*
* OLD
*
function
check1($name, $label, $array, $key)
{
$ret .= sprintf('<label><input type="checkbox" name="%s[%s]"', $name, $key);
if (is_array($array)) {
if ($array[$key]) $ret .= ' CHECKED';
} else {
if ($array & $key) $ret .= ' CHECKED';
}

$ret .= " />";
if ($label[$key]) $ret .= $label[$key];
$ret .= "</label>";

return $ret;
}

*/

function check1($name, $label, $array, $key, $param = null, $class = null)
{
  $id = sprintf("%s_%s", $name, $key);
  $ret = ''; //'<span class="nowrap">';
  $ret.= sprintf('<input type="checkbox" name="%s[%s]" id="%s"', $name, $key, $id);
  if ($param)
  {
    $ret.= " $param";
  }

  if (is_array($array))
  {
    if (isset($array[$key])) $ret.= ' CHECKED';
  }
  else
  {
    if ($array & $key) $ret.= ' CHECKED';
  }

  $ret.= " />";
  if ($label[$key]) $ret.= "<label class=\"label_checkbox {$class}\" for=\"$id\">{$label[$key]}</label>";

  // $ret .= "</span>";

  return $ret;
}

/*
* OLD
*
function
checkbox($name, $hash, $value, $param = '', $multi = 0, $class = '', $sep = '')
{
foreach ($hash AS $k => $v) {
if ($multi) {
$r = sprintf('<label><nobr><input type="checkbox" name="%s[%s]"', $name, $k);
if ($value[$k]) $r .= ' CHECKED';
} else {
$r = sprintf('<label><nobr><input type="checkbox" name="%s" value="%s"', $name, $k);
if ($value == $k) $r .= ' CHECKED';
}

if ($param) $r .= " {$param}";
$r .= " />";
if ($v) $r .= "<span class=\"{$class}\">{$v}</span>";
$r .= "</nobr></label>";
$ret[] = $r;
}

return implode($sep, $ret);
}

*/

function checkbox($name, $hash, $value, $param = '', $multi = 0, $class = '', $sep = '')
{
  foreach($hash AS $k => $v)
  {
    if ($k[0] == ':')
    {
      $ret[] = '<span class="label_checkbox">' . $v . '</span>';
      continue;
    }
    elseif ($k[0] == ';')
    {
      $ret[] = $v;
      continue;
    }
    elseif ($multi)
    {
      $id = sprintf("%s_%s", $name, $k);
      $r = sprintf('<span class="nowrap"><input type="checkbox" name="%s[%s]" id="%s"', $name, $k, $id);
      if (isset($value[$k])) $r.= ' CHECKED';
    }
    else
    {
      $id = sprintf("%s_%s", $name, $k);
      $r = sprintf('<span class="nowrap"><input type="checkbox" name="%s" value="%s" id="%s"', $name, $k, $id);
      if ($value == $k) $r.= ' CHECKED';
    }

    if ($param) $r.= " {$param}";
    $r.= " />";
    if ($v) $r.= "<label class=\"label_checkbox {$class}\" for=\"{$id}\">{$v}</label>";
    $r.= "</span>\n";
    $ret[] = $r;
  }

  return implode($sep, $ret);
}

function check_flag($name, $hash, $value, $param = '', $multi = 0, $class = '', $sep = '', $data = null)
{
  foreach($hash AS $k => $v)
  {
    if ($k[0] == ':')
    {
      $ret[] = '<span class="label_checkbox">' . $v . '</span>';
      continue;
    }
    elseif ($k[0] == ';')
    {
      $ret[] = $v;
      continue;
    }
    elseif ($multi)
    {
      $id = sprintf("%s_%s", $name, $k);
      $r = sprintf('<input class="check_flag" type="checkbox"
        name="%s[%s]" id="%s" %s', $name, $k, $id, $data);
      if ($value[$k]) $r.= ' CHECKED';
    }
    else
    {
      $id = sprintf("%s_%s", $name, $k);
      $r = sprintf('<input class="check_flag" type="checkbox" name="%s" value="%s" id="%s" %s', $name, $k, $id, $data);
      if ($value == $k) $r.= ' CHECKED';
    }

    if ($param) $r.= " {$param}";
    $r.= " />";
    if ($v) $r.= "<label class=\"label_checkbox {$class}\" for=\"{$id}\">{$v}</label>";
    $r.= "\n";
    $ret[] = $r;
  }

  return implode($sep, $ret);
}

function select($name, $hash, $selected = '', $param = '')
{
  $ret = sprintf("<select name=\"%s\" %s>\n", $name, $param);
  foreach($hash AS $value => $label)
  {
    if ($value[0] == ':')
    {
      $ret.= sprintf('<optgroup label="%s">', $label);
    }
    else
    if ($value[0] == ';')
    {
      $ret.= "</optgroup>\n";
    }
    else
    if ($value[0] == '#')
    {
      $ret.= "<option disabled>---</option>\n";
    }
    else
    {
      $ret.= sprintf('<option value="%s"', $value);
      if (is_array($selected))
      { // multiple
        if (in_array($value, $selected)) $ret.= ' SELECTED';
      }
      else
      {
        if ($value == $selected) $ret.= ' SELECTED';
      }

      $ret.= ">{$label}</option>\n";
    }
  }

  $ret.= "</select>";
  return $ret;
}

// フォーム変数表示(デバッグ用)

function printFormVariables()
{
  global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_POST_FILES;
  echo "<hr>";
  if (count($_GET))
  {
    echo '<table border><tr><th>_GET</th></tr><tr><td><pre>';
    print_r($_GET);
    echo '</pre></td></tr></table>';
  }

  if (count($_POST))
  {
    echo '<table border><tr><th>_POST</th></tr><tr><td><pre>';
    print_r($_POST);
    echo '</pre></td></tr></table>';
  }

  if (count($_FILES))
  {
    echo '<table border><tr><th>_FILES</th></tr><tr><td><pre>';
    print_r($_FILES);
    echo '</pre></td></tr></table>';
  }

  if (count($_SESSION))
  {
    echo '<table border><tr><th>_SESSION</th></tr><tr><td><pre>';
    print_r($_SESSION);
    echo '</pre></td></tr></table>';
  }
}

function print_pre($var)
{
  echo '<pre>';
  print_r($var);
  echo '</pre>';
}
