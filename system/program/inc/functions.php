<?php
// 7モード
function thumbnail_image($main_img, $copy_img, $height, $width)
{
  copy($main_img, $copy_img);
  $src           = @imageCreateFromJPEG($copy_img);
  $src_width     = imageSX($src);
  $src_height    = imageSY($src);
  $aspect_ratio  = $src_height / $src_width;
  $target_aspect = $height / $width;
  if ($aspect_ratio > $target_aspect) {
    $dst_width  = $height / $aspect_ratio;
    $dst_height = $height;
  } else {
    $dst_width  = $width;
    $dst_height = $width * $aspect_ratio;
  }
  $dst = imageCreateTrueColor($dst_width, $dst_height);
  imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
  imageJpeg($dst, $copy_img, 90);
  return $copy_img;
}
// 5モード
function thumbnail_image_width($main_img, $copy_img, $width)
{
  copy($main_img, $copy_img);
  $src          = @imageCreateFromJPEG($copy_img);
  $src_width    = imageSX($src);
  $src_height   = imageSY($src);
  $aspect_ratio = $src_height / $src_width;
  $dst_width    = $width;
  $dst_height   = $width * $aspect_ratio;
  $dst          = imageCreateTrueColor($dst_width, $dst_height);
  imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
  imageJpeg($dst, $copy_img, 90);
  return $copy_img;
}
//  6モード
function thumbnail_image_height($main_img, $copy_img, $height)
{
  copy($main_img, $copy_img);
  $src          = @imageCreateFromJPEG($copy_img);
  $src_width    = imageSX($src);
  $src_height   = imageSY($src);
  $aspect_ratio = $src_height / $src_width;
  $dst_width    = $height / $aspect_ratio;
  $dst_height   = $height;
  $dst          = imageCreateTrueColor($dst_width, $dst_height);
  imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
  imageJpeg($dst, $copy_img, 90);
  return $copy_img;
}
//  ドラッグシステム
function thumbnail_image_remake($main_img, $copy_img, $x, $y, $w, $h, $trag_with, $trag_height = null)
{
  if (!$trag_height) {
    $targ_w = $trag_with;
    $targ_h = $trag_with;
  } else {
    $targ_w = $trag_with;
    $targ_h = $trag_height;
  }
  $src = @imagecreatefromjpeg($main_img);
  $dst = ImageCreateTrueColor($targ_w, $targ_h);
  imagecopyresampled($dst, $src, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);
  $copy_img = imageJpeg($dst, $copy_img, 100);
  return $copy_img;
}
//  画像を自動で真ん中で正方形化
function thumbnail_square($file, $upload, $thumbnail_width, $thumbnail_height)
{
  $image  = imagecreatefromjpeg($file);
  $width  = imagesx($image);
  $height = imagesy($image);
  if ($width >= $height) {
    $side  = $height;
    $x     = floor(($width - $height) / 2);
    $y     = 0;
    $width = $side;
  } else {
    $side   = $width;
    $y      = floor(($height - $width) / 2);
    $x      = 0;
    $height = $side;
  }
  $thumbnail = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
  imagecopyresized($thumbnail, $image, 0, 0, $x, $y, $thumbnail_width, $thumbnail_height, $width, $height);
  $data = imagejpeg($thumbnail, $upload, 90);
  return $data;
}
// 数字を整理
function spr_nb($number, $count = '%08d')
{
  return sprintf($count, $number);
}
// 疑似静的URL化
function detail_url($number, $count = '%08d.html')
{
  return sprintf($count, $number);
}

// 指定キーワードでの配列化
function _explode($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  if (strstr($value, $ext)) {
    $array = explode($ext, $value);
    foreach ($array as $dummy => $data) {
      $_array[] = _str($data);
    }
  } else {
    $_array[] = _str($value);
  }
  return $_array;
}
// 指定キーワードでの配列化
function _unexplode($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  if (strstr($value, $ext)) {
    $array = explode($ext, $value);
    foreach ($array as $dummy => $data) {
      $_array[] = $data;
    }
  } else {
    $_array[] = $value;
  }
  return $_array;
}
// 指定キーワードでの配列化
function _explode_int($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  if (strstr($value, $ext)) {
    $array = explode($ext, $value);
    foreach ($array as $dummy => $data) {
      $_array[] = (int) $data;
    }
  } else {
    $_array[] = (int) $value;
  }
  return $_array;
}
// 指定キーワードでの文字列化
function _implode($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  return implode($ext, $value);
}
// データベースから出す場合の配列のcheck_array化
function _input_check($array)
{
  foreach ($array as $key => $value) {
    $_value          = (int) $value;
    $_array[$_value] = "on";
  }
  return $_array;
}
// データベースから出す場合の配列のテキスト化
function number_text($array, $label, $ext = null)
{
  if (!$ext)
    $ext = ',';
  foreach ($array as $key => $value) {
    $_value   = (int) $value;
    $_array[] = $_value;
  }
  return implode($ext, $_array);
}
// データベースから出す場合の配列のラベル化
function _label($array, $label, $ext = null)
{
  if (!$ext)
    $ext = ',';
  foreach ($array as $key => $value) {
    $_value   = (int) $value;
    $_array[] = $label[$_value];
  }
  return implode($ext, $_array);
}
// 確認画面での配列化
function confirm_array($value, $ext = null)
{
  if (!$ext)
    $ext = ',';
  if (strpos($value, $ext)) {
    $array = explode($ext, $value);
    if (is_array($array)) {
      foreach ($array as $dummy => $data) {
        if ($data) {
          $_array[$data] = 'on';
        }
      }
    }
  } else {
    $_array[$value] = 'on';
  }
  return $_array;
}
// 確認画面での文字列化
function confirm_name($array, $label, $ext = null)
{
  if (!$ext)
    $ext = ',';
  if (is_array($array)) {
    foreach ($array as $dummy => $data) {
      if ($data) {
        $_array[] = $label[$data];
      }
    }
  }
  return implode($ext, $_array);
}
// checkbox用DB入力用文字列化 フォーマットあり
function check_implode($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  foreach ($value as $key => $onoff) {
    $_value[] = spr_nb($key);
  }
  return implode($ext, $_value);
}
// checkbox用DB入力用文字列化 フォーマットなし
function _check_array($array, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  if (is_array($array)) {
    foreach ($array as $key => $onoff) {
      $_array[] = $key;
    }
  }
  return implode($ext, $_array);
}
// checkbox用配列化
function export_array($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  $array = explode($ext, $value);
  if (is_array($array)) {
    foreach ($array as $dummy => $data) {
      if ($data) {
        $_array[$data] = 'on';
      }
    }
  }
  return $_array;
}
// checkbox用配列化
function export_int_array($value, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  $array = explode($ext, $value);
  if (is_array($array)) {
    foreach ($array as $dummy => $data) {
      if ($data) {
        $_array[(int) $data] = 'on';
      }
    }
  }
  return $_array;
}
// checkbox用文字列化
function export_name($array, $label, $ext = null)
{
  if (!$ext)
    $ext = "/,";
  if (is_array($array)) {
    foreach ($array as $dummy => $data) {
      if ($data) {
        $_array[] = $label[$data];
      }
    }
  }
  return implode($ext, $_array);
}
// テキストのシリアライズ化
function input_serialize($array)
{
  foreach ($array as $key => $value) {
    $data[$key] = addslashes(_h($value));
  }
  return serialize($data);
}
// 数字のシリアライズ化
function input_serialize_int($array)
{
  foreach ($array as $key => $value) {
    $data[$key] = spr_nb($value);
  }
  return serialize($data);
}
// チェックボックス系のシリアライズ化
function input_serialize_int_check($array)
{
  foreach ($array as $key => $value) {
    $data[$key] = spr_nb($key);
  }
  return serialize($data);
}

// チェックボックス系のシリアライズ化 文字列var
function input_serialize_text_check($array)
{
  foreach ($array as $key => $value) {
    $data[$key] = $key;
  }
  return serialize($data);
}

// チェックボックス系のシリアライズ化　多重配列用
function input_serialize_int_check_array($arrays)
{
  foreach ($arrays as $category => $array) {
    foreach ($array as $key => $value) {
      $_category              = spr_nb($category);
      $data[$_category][$key] = spr_nb($key);
    }
  }
  return serialize($data);
}
// テキストのアンシリアライズ化
function output_unserialize($array)
{
  $_array = stripslashes(unserialize($array));
  foreach ($_array as $key => $value) {
    $data[$key] = _r($value);
  }
  return $data;
}
// 数字のアンシリアライズ化
function output_unserialize_int($array)
{
  $_array = unserialize($array);
  foreach ($_array as $key => $value) {
    $data[$key] = (int) $value;
  }
  return $data;
}
// 数字のアンシリアライズ化 keyは何も入れないvar
function output_unserialize_int_data($array)
{
  $_array = unserialize($array);
  foreach ($_array as $key => $value) {
    $data[] = (int) $value;
  }
  return $data;
}

// 数字のアンシリアライズ化　多重配列用
function output_unserialize_int_array($arrays)
{
  $_arrays = unserialize($arrays);
  foreach ($_arrays as $category => $array) {
    foreach ($array as $key => $value) {
      $_category              = (int) $category;
      $data[$_category][$key] = (int) $value;
    }
  }
  return $data;
}
// チェックボックス系のアンシリアライズ化
function _label_unserialize($array, $label, $ext = null)
{
  $_array     = '';
  $labelarray = array();
  if (!$ext) {
    $ext = ',';
  }
  $_array = unserialize($array);
  foreach ($_array as $key => $value) {
    $_value       = (int) $value;
    $labelarray[] = $label[$_value];
  }
  return implode($ext, $labelarray);
}
function _h($text)
{
  return htmlspecialchars($text, ENT_QUOTES);
}
function _r($text)
{
  return htmlspecialchars_decode($text, ENT_NOQUOTES);
}
function validate_date($date)
{
  if (stristr($date, ' ')) {
    $date = date_replace($date);
    list($date_day, $date_time) = explode(' ', $date);
    list($year, $month, $day) = explode('-', $date_day);
    return checkdate($month, $day, $year);
  } else {
    $date = date_replace($date);
    if (preg_match('/-/', $date)) {
      list($year, $month, $day) = explode('-', $date);
      return checkdate($month, $day, $year);
    } else {
      return false;
    }
  }
  // return $answer;
  // $d = DateTime::createFromFormat($format, $date);
  // return $d && $d->format($format) == $date;
}
function date_replace($date)
{
  $search  = array(
    '/',
    '年',
    '月',
    '日',
    '時',
    '分'
  );
  $replace = array(
    '-',
    '-',
    '-',
    '',
    ':',
    ''
  );
  $day     = str_replace($search, $replace, $date);
  return $day;
}
// 文章を指定部分で「...」に変換
function omit($val, $int = 20, $append = "...")
{
  return mb_strimwidth($val, 0, $int, $append, 'UTF-8');
}
function remove_script($str)
{
  $str = preg_replace('!<script.*?>.*?</script.*?>!is', "", $str);
  return $str;
}
function remove_style($str)
{
  $str = preg_replace('!<style.*?>.*?</style.*?>!is', "", $str);
  return $str;
}
// 改行やタブ、スタイルなど文字以外の部分を削除
function _trim($val)
{
  $val = remove_style($val);
  $val = remove_script($val);
  $val = trim($val);
  $val = strip_tags($val);
  $val = preg_replace('/(?:\n|\r|\r\n|\t)/', '', $val);
  return $val;
}
// 改行やタブなどを削除して、文章を指定部分で「...」に変換
function omit_trim($val, $length = 20, $append = "...")
{
  $val = _trim($val);
  $val = omit($val, $length, $append, "UTF-8");
  return $val;
}

// エラー文とかを出すとき用
function alert($value)
{
  if (isset($value)) {
    echo '<script> alert("' . $value . '"); </script>';
  }
}

function alert_redirect($value)
{
  $url     = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  $search  = array(
    'index.php'
  );
  $replace = array(
    ''
  );
  $url     = str_replace($search, $replace, $url);
  if (isset($value)) {
    echo '<script> alert("' . $value . '"); location.href = "' . $url . '";</script>';
  }
}

function index_replace($url)
{
  $search  = array(
    'index.php'
  );
  $replace = array(
    ''
  );
  $url     = str_replace($search, $replace, $url);
  return $url;
}
function get_first_key($array)
{
  reset($array);
  return key($array);
}
function get_domain()
{
  $url = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
  $url .= $_SERVER["HTTP_HOST"];
  return $url;
}