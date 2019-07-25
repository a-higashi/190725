<?php
/*
 * qiqImage.php: rev.16100401
 *
 * Copyright (c) dotAster Inc. <http://www.dotAster.com>
 *
 */

/*
 * [更新履歴]
 * 2016/10/04: resize=10 を追加
 * 2016/09/15: resize=9 を追加
 * 2015/02/09: 長辺トリミングに対応
 * 2014/05/25: 透過画像に対応
 * 2014/03/14: DOCUMENT_ROOTを参照するように
 * 2014/03/11: type、convert を追加
 * 2013/02/18: resize=7 を追加
 */

if (!defined('DOCUMENT_ROOT')) {
  define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
}

function qiqImageCreate($w, $h)
{
  $dst = imageCreateTrueColor($w, $h);

  imageAlphaBlending($dst, false);
  imageSaveAlpha($dst, true);

  return $dst;
}

class qiqImage
{
  var $name; // フォームの値の名前
  var $dir; // 保存ディレクトリ
  var $format; // 名前生成フォーマット
  var $path; // 保存ファイル名
  var $width; // 最大幅
  var $height; // 最大高さ
  var $resize; // リサイズフラグ
  // 0: なし
  // 1: 固定
  // 2: 大きい時だけ縮小(アスペクト比保持)
  // 3: 幅固定
  // 4: 高さ固定
  // 5: 幅固定＋大きいときだけ縮小(アスペクト比保持)
  // 6: 高さ固定＋大きいときだけ縮小(アスペクト比保持)
  // 7: 指定された枠内に収める
  // 8: 長辺をトリミング
  // 9: 指定されたサイズの画像を生成
  // 10: 大きいときだけ最大幅に合わせて縮小(アスペクト比保持)
  var $tmp_id; // 一時ID
  var $tmp_dir; // 一時保存ディレクトリ
  var $ext; // 拡張子
  var $mode; // function
  var $delete; // 削除

  function __construct($array = null)
  {
    $this->name    = 'image';
    $this->type    = 'png';
    $this->dir     = '.';
    $this->format  = "%d." . $this->type;
    $this->resize  = 0;
    $this->tmp_dir = '.';
    $this->convert = true; // 変換を強制する
    if (is_array($array)) {
      foreach ($array AS $key => $value) {
        $this->$key = $value;
      }
    }
    if (!$this->tmp_id)
      $this->tmp_id(true);

    /* ここで指定されたtmp_dir内での古いファイルを削除 */
    $dir = opendir($this->tmp_dir);
    while ($e = readdir($dir)) {
      if (substr($e, 0, strlen($this->name) + 4) != "{$this->name}_tmp")
        continue;
      $fname = sprintf('%s/%s', $this->tmp_dir, $e);
      $mtime = filemtime($fname);
      if (time() - $mtime > 3600)
        @unlink($fname);
    }
  }

  function dir($dir = '')
  {
    if ($dir)
      $this->dir = $dir;
    if (!file_exists($this->dir))
      @mkdir($this->dir, 0777);
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

  function format()
  {
    $format     = sprintf('%s/%s', $this->dir(), $this->format);
    $arg        = func_get_args();
    $this->path = vsprintf($format, $arg);
    return $this->path;
  }

  function path()
  {
    if (!$this->path) {
      $this->path = $this->format(func_get_args());
    }
    return $this->path;
  }

  function tmp_id($f = false)
  {
    if ($f)
      $this->tmp_id = "{$this->name}_tmp" . md5(uniqid(mt_rand(), true));
    return $this->tmp_id;
  }

  function tmp_path()
  {
    $ext = ($this->ext) ? $this->ext : $this->type;
    return sprintf("%s/%s.%s", $this->tmp_dir, $this->tmp_id(), $ext);
  }

  function save_tmp($index = 0)
  {
    $file = $_FILES[$this->name]['tmp_name'];
    if (is_array($file)) $file = $_FILES[$this->name]['tmp_name'][$index];
    if (function_exists('exif_read_data')) {
      $exif_datas = @exif_read_data($file);
    } else {
      $exif_datas = '';
    }
    if (!$file || $file == 'none' || !filesize($file)) {
      return;
    }
    if (!is_uploaded_file($file)) {
      $this->error = "ファイルのアップロードに失敗しました";
      return;
    }
    $tmpimage = $this->tmp_path();
    if ($this->resize == -1) { // 画像じゃない
      move_uploaded_file($file, $tmpimage);
      return;
    }
    $src = @imageCreateFromPNG($file);
    if ($src) {
      $type = 'png';
    } else {
      $src = @imageCreateFromJPEG($file);
      if ($src) {
        if (isset($exif_datas['Orientation'])) {
          $src = $this->orientation_fixed_image($file, $exif_datas);
        }
        $type = 'jpg';
      } else {
        $src = @imageCreateFromGIF($file);
        if ($src) {
          $type = 'gif';
        } else {
          $this->error = "取り扱えない画像形式です。";
          return;
        }
      }
    }
    $src_width    = imageSX($src);
    $src_height   = imageSY($src);
    $aspect_ratio = $src_height / $src_width;
    switch ($this->resize) {
      case 1: // 固定
        if (!$this->convert && $src_width == $this->width && $src_height == $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst = qiqImageCreate($this->width, $this->height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $this->width, $this->height, $src_width, $src_height);
        }
        break;
      case 2: // 大きいときだけ縮小(アスペクト比保持)
        if (!$this->convert && $src_width <= $this->width && $src_height <= $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          if ($aspect_ratio > 1) {
            if ($src_height < $this->height) {
              $dst_width  = $src_width;
              $dst_height = $src_height;
            } else {
              $dst_width  = $src_width * $this->height / $src_height;
              $dst_height = $this->height;
            }
          } else {
            if ($src_width < $this->width) {
              $dst_width  = $src_width;
              $dst_height = $src_height;
            } else {
              $dst_width  = $this->width;
              $dst_height = $src_height * $this->width / $src_width;
            }
          }
          $dst = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 3: // 幅固定
        if (!$this->convert && $src_width == $this->width && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst_width  = $this->width;
          $dst_height = $this->width * $aspect_ratio;
          $dst        = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 4: // 高さ固定
        if (!$this->convert && $src_height == $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst_width  = $this->height / $aspect_ratio;
          $dst_height = $this->height;
          $dst        = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 5: // 幅固定＋大きいときだけ縮小(アスペクト比保持)
        if (!$this->convert && $src_width <= $this->width && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst_width  = $this->width;
          $dst_height = $this->width * $aspect_ratio;
          $dst        = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 6: // 高さ固定＋大きいときだけ縮小(アスペクト比保持)
        if (!$this->foce_convert && $src_height <= $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst_width  = $this->height / $aspect_ratio;
          $dst_height = $this->height;
          $dst        = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 7: // 指定された枠内に収める
        if (!$this->convert && $src_width <= $this->width && $src_height <= $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $target_aspect = $this->height / $this->width;
          if ($aspect_ratio > $target_aspect) {
            $dst_width  = $this->height / $aspect_ratio;
            $dst_height = $this->height;
          } else {
            $dst_width  = $this->width;
            $dst_height = $this->width * $aspect_ratio;
          }
          $dst = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 8: // 長辺をトリミング
        if (!$this->convert && $src_width <= $this->width && $src_height <= $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          if ($aspect_ratio > 1) {
            $src_size   = $src_width;
            $dst_width  = $this->width;
            $dst_height = $this->width;
            $src_x      = 0;
            $src_y      = ($src_height - $src_width) >> 1;
          } else {
            $src_size   = $src_height;
            $dst_width  = $this->height;
            $dst_height = $this->height;
            $src_x      = ($src_width - $src_height) >> 1;
            $src_y      = 0;
          }
          $dst = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, $src_x, $src_y, $dst_width, $dst_height, $src_size, $src_size);
        }
        break;
      case 9: // 指定されたサイズの画像を生成
        if (!$this->convert && $src_width == $this->width && $src_height == $this->height && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $target_aspect = $this->height / $this->width;
          if ($aspect_ratio > $target_aspect) {
            $dst_width  = $this->height / $aspect_ratio;
            $dst_height = $this->height;
            $x          = ($this->width - $dst_width) >> 1;
            if ($x < 0)
              $x = 0;
            $y = 0;
          } else {
            $dst_width  = $this->width;
            $dst_height = $this->width * $aspect_ratio;
            $x          = 0;
            $y          = ($this->height - $dst_height) >> 1;
            if ($y < 0)
              $y = 0;
          }
          $dst   = qiqImageCreate($this->width, $this->height);
          $white = imageColorAllocate($dst, 0xee, 0xee, 0xee);
          imageFilledRectangle($dst, 0, 0, $this->width, $this->height, $white);
          imageCopyResampled($dst, $src, $x, $y, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 10: // 大きいときだけ最大幅に合わせて縮小(アスペクト比保持)
        if (!$this->convert && $src_width <= $this->width && $type == $this->type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          if ($src_width <= $this->width) {
            $dst_width  = $src_width;
            $dst_height = $src_height;
          } else {
            $dst_width  = $this->width;
            $dst_height = $this->width * $aspect_ratio;
          }
          $dst = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
      case 0: // そのまま
      default:
        if (!$this->convert && $this->type == $type) {
          move_uploaded_file($file, $tmpimage);
        } else {
          $dst_width  = $src_width;
          $dst_height = $src_height;
          $dst        = qiqImageCreate($dst_width, $dst_height);
          imageCopyResampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        }
        break;
    }
    if ($dst) {
      imageInterlace($dst, 0);
      switch ($this->type) {
        case 'png':
          imagePNG($dst, $tmpimage);
          break;
        case 'jpg':
          imageJpeg($dst, $tmpimage);
          break;
        case 'gif':
          imageGif($dst, $tmpimage);
          break;
      }
    }
  }

  function purge($complete = false)
  {
    $tmp = $this->tmp_path();
    $this->tmp_id(true);
    if (file_exists($tmp)) {
      if ($complete) {
        unlink($tmp);
      } else {
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
  // 画像の左右反転
  function image_flop($image)
  {
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w, $h);
    // 逆側から色を取得
    for ($i = ($w - 1); $i >= 0; $i--)
    {
      for ($j = 0; $j < $h; $j++)
      {
        $color_index = imagecolorat($image, $i, $j);
        $colors = imagecolorsforindex($image, $color_index);
        imagesetpixel($destImage, abs($i - $w + 1) , $j, imagecolorallocate($destImage, $colors["red"], $colors["green"], $colors["blue"]));
      }
    }
    return $destImage;
  }
  // 上下反転
  function image_flip($image)
  {
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w, $h);
    // 逆側から色を取得
    for ($i = 0; $i < $w; $i++)
    {
      for ($j = ($h - 1); $j >= 0; $j--)
      {
        $color_index = imagecolorat($image, $i, $j);
        $colors = imagecolorsforindex($image, $color_index);
        imagesetpixel($destImage, $i, abs($j - $h + 1) , imagecolorallocate($destImage, $colors["red"], $colors["green"], $colors["blue"]));
      }
    }
    return $destImage;
  }
  // 画像を回転
  function image_rotate($image, $angle, $bgd_color)
  {
    return imagerotate($image, $angle, $bgd_color, 0);
  }
  // 画像の方向を正す
  function orientation_fixed_image($input, $exif_datas)
  {
    $image = @ImageCreateFromJPEG($input);
    $orientation = $exif_datas['Orientation'];
    if ($image)
    {
      // 未定義
      if ($orientation == 0)
      {
        // 通常
      }
      else if ($orientation == 1)
      {
        // 左右反転
      }
      else if ($orientation == 2)
      {
        $image = $this->image_flop($image);
        // 180°回転
      }
      else if ($orientation == 3)
      {
        $image = $this->image_rotate($image, 180, 0);
        // 上下反転
      }
      else if ($orientation == 4)
      {
        $image = $this->image_Flip($image);
        // 反時計回りに90°回転 上下反転
      }
      else if ($orientation == 5)
      {
        $image = $this->image_rotate($image, 90, 0);
        $image = $this->image_flip($image);
        // 時計回りに90°回転
      }
      else if ($orientation == 6)
      {
        $image = $this->image_rotate($image, 270, 0);
        // 時計回りに90°回転 上下反転
      }
      else if ($orientation == 7)
      {
        $image = $this->image_rotate($image, 270, 0);
        $image = $this->image_flip($image);
        // 反時計回りに90°回転
      }
      else if ($orientation == 8)
      {
        $image = $this->image_rotate($image, 90, 0);
      }
    }
    // 画像の書き出し
    return $image;
  }
}
