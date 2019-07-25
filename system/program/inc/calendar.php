<?php
require_once 'calendar_data.php';

/*
* calendar用システム

$array['show'] = array{
1 => "平日",
2 => "土曜",
3 => "日曜",
4 => "祝日",
5 => "その月じゃない日"
}

$type
1 = 月曜スタート
2 = 日曜スタート

$holiday_set
デフォルトはTRUEで、祝日データ入れ込み
入れない場合はFALSEに

*/
function calendar($type = 1, $value = null, $holiday_set = TRUE)
{
  $year = '';
  $month = '';
  $day = '';
  $today = '';
  $first_time = '';
  $last_day = '';
  $first_week = '';
  $last = '';
  $last_time = '';
  $last_week = '';
  $next_today = '';
  $next_year = '';
  $next_month = '';
  $next_first_day = '';
  $next_first_time = '';
  $next_last_day = '';
  $next_last = '';
  $next_last_time = '';
  $next_fast_week = '';
  $prev_days = '';
  $prev_today = '';
  $prev_year = '';
  $prev_month = '';
  $prev_last_day = '';
  $prev_first_day = '';
  $prev_last_week = '';
  $array = array();
  $_array = array();
  // 月曜スタート
  if ($type == 1) {
    // 指定月（今月）
    if (!isset($value)) $value = date('Y-m-d');
    list($year, $month, $day) = explode('-', $value);
    if ($holiday_set) {
      $holiday = getHolidays($year);
    }
    $today = $year . '-' . $month; //今月の年月を取得
    $first_day = $today . '-01'; //YYYY-MM-01に変換
    $first_time = strtotime($first_day); //今月1日ののタイムスタンプ取得
    $last_day = date("t", $first_time); //今月の最終日の取得
    $first_week = date("N", $first_time); //今月1日のの曜日取得
    $last = $today . '-' . $last_day; //YYYY-MM-DDに変換
    $last_time = strtotime($last);
    $last_week = date("N", $last_time); //今月最終日の曜日取得
    // 来月
    $next_today = date('Y-m', strtotime($first_day . "+1 month")); //来月の年月を取得
    $next_year = date('Y', strtotime($first_day . "+1 month")); //来月の年を取得
    $next_month = date('m', strtotime($first_day . "+1 month")); //来月の月を取得
    $next_first_day = $next_today . '-01'; //YYYY-MM-01に変換
    $next_first_time = strtotime($next_first_day); //来月1日ののタイムスタンプ取得
    $next_last_day = date("t", $next_first_time); //来月の最終日の取得
    $next_last = $next_today . '-' . $next_last_day; //来月の最終日をYYYY-MM-ddに変換
    $next_last_time = strtotime($next_last); //来月の最終日の取得のタイムスタンプ取得
    $next_fast_week = date("N", mktime(0, 0, 0, intval($next_month), 01, intval($next_year)));
    // 先月
    $prev_days = strtotime($first_day . "-1 month"); //先月1日ののタイムスタンプ
    $prev_today = date('Y-m', strtotime($prev_days . "-1 month")); //先月の年月を取得
    $prev_year = date('Y', $prev_days);
    $prev_month = date('m', $prev_days);
    $prev_last_day = date("t", $prev_days);
    $prev_first_day = date('Y-m-d', $prev_days);
    $prev_last_week = date("N", mktime(0, 0, 0, intval($prev_month), intval($prev_last_day), intval($prev_year)));
    // 先月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    if ($prev_last_week != 7)
    {
      for ($i = $prev_last_week; $i >= 1; $i--)
      {
        $today = sprintf('%04d-%02d-%02d', $prev_year, $prev_month, $prev_last_day);
        $array[$today]['year'] = $prev_year;
        $array[$today]['month'] = $prev_month;
        $array[$today]['day'] = $prev_last_day;
        $array[$today]['week'] = date("N", mktime(0, 0, 0, intval($prev_month), intval($prev_last_day), intval($prev_year)));
        $array[$today]['show'] = 5;
        $prev_last_day--;
      }
    }
    // 今月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    for ($i = 0; $i < $last_day; $i++)
    {
      $n = $i + 1;
      $todays = sprintf('%04d-%02d-%02d', $year, $month, $n);
      // $year_month = $year. $month;
      $array[$todays]['year'] = $year;
      $array[$todays]['month'] = $month;
      $array[$todays]['day'] = $n;
      $array[$todays]['week'] = date("N", mktime(0, 0, 0, intval($month), intval($n), intval($year)));
      if ($array[$todays]['week'] == 6)
      {
        $array[$todays]['show'] = 2;
      }
      elseif ($array[$todays]['week'] == 7)
      {
        $array[$todays]['show'] = 3;
      }
      elseif (isset($holiday[$todays]) && $holiday_set)
      {
        $array[$todays]['show'] = 4;
      }
      else
      {
        $array[$todays]['show'] = 1;
      }
    }
    // 来月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    if ($next_fast_week != 1)
    {
      $n = 1;
      for ($i = $next_fast_week; $i < 8; $i++)
      {
        $today = sprintf('%04d-%02d-%02d', $next_year, $next_month, $n);
        $array[$today]['year'] = $next_year;
        $array[$today]['month'] = $next_month;
        $array[$today]['day'] = $n;
        $array[$today]['week'] = date("N", mktime(0, 0, 0, intval($next_month), intval($n), intval($next_year)));
        $array[$today]['show'] = 5;
        $n++;
      }
    }
    ksort($array);
    $i = 1;
    $n = 1;
    foreach($array as $day => $data)
    {
      if ($n >= 7)
      {
        $_array[$i][$day] = $data;
        $n = 1;
        $i++;
      }
      else
      {
        $_array[$i][$day] = $data;
        $n++;
      }
    }
    return $_array;
  // 日曜スタート
  } elseif ($type == 2) {
    // 指定月（今月）
    if (!isset($value)) $value = date('Y-m-d');
    list($year, $month, $day) = explode('-', $value);
    if ($holiday_set) {
      $holiday = getHolidays($year);
    }
    $today = $year . '-' . $month; //今月の年月を取得
    $first_day = $today . '-01'; //YYYY-MM-01に変換
    $first_time = strtotime($first_day); //今月1日ののタイムスタンプ取得
    $last_day = date("t", $first_time); //今月の最終日の取得
    $first_week = date("w", $first_time); //今月1日のの曜日取得
    $last = $today . '-' . $last_day; //YYYY-MM-DDに変換
    $last_time = strtotime($last);
    $last_week = date("w", $last_time); //今月最終日の曜日取得
    // 来月
    $next_today = date('Y-m', strtotime($first_day . "+1 month")); //来月の年月を取得
    $next_year = date('Y', strtotime($first_day . "+1 month")); //来月の年を取得
    $next_month = date('m', strtotime($first_day . "+1 month")); //来月の月を取得
    $next_first_day = $next_today . '-01'; //YYYY-MM-01に変換
    $next_first_time = strtotime($next_first_day); //来月1日ののタイムスタンプ取得
    $next_last_day = date("t", $next_first_time); //来月の最終日の取得
    $next_last = $next_today . '-' . $next_last_day; //来月の最終日をYYYY-MM-ddに変換
    $next_last_time = strtotime($next_last); //来月の最終日の取得のタイムスタンプ取得
    $next_fast_week = date("w", mktime(0, 0, 0, intval($next_month), 01, intval($next_year)));
    // 先月
    $prev_days = strtotime($first_day . "-1 month"); //先月1日ののタイムスタンプ
    $prev_today = date('Y-m', strtotime($prev_days . "-1 month")); //先月の年月を取得
    $prev_year = date('Y', $prev_days);
    $prev_month = date('m', $prev_days);
    $prev_last_day = date("t", $prev_days);
    $prev_first_day = date('Y-m-d', $prev_days);
    $prev_last_week = date("w", mktime(0, 0, 0, intval($prev_month), intval($prev_last_day), intval($prev_year)));
    // 先月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    if ($prev_last_week != 6)
    {
      for ($i = $prev_last_week; $i >= 0; $i--)
      {
        $today = sprintf('%04d-%02d-%02d', $prev_year, $prev_month, $prev_last_day);
        $array[$today]['year'] = $prev_year;
        $array[$today]['month'] = $prev_month;
        $array[$today]['day'] = $prev_last_day;
        $array[$today]['week'] = date("w", mktime(0, 0, 0, intval($prev_month), intval($prev_last_day), intval($prev_year)));
        $array[$today]['show'] = 5;
        $prev_last_day--;
      }
    }
    // 今月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    for ($i = 0; $i < $last_day; $i++)
    {
      $n = $i + 1;
      $todays = sprintf('%04d-%02d-%02d', $year, $month, $n);
      // $year_month = $year. $month;
      $array[$todays]['year'] = $year;
      $array[$todays]['month'] = $month;
      $array[$todays]['day'] = $n;
      $array[$todays]['week'] = date("w", mktime(0, 0, 0, intval($month), intval($n), intval($year)));
      if ($array[$todays]['week'] == 6)
      {
        $array[$todays]['show'] = 2;
      }
      elseif ($array[$todays]['week'] == 0)
      {
        $array[$todays]['show'] = 3;
      }
      elseif (isset($holiday[$todays]) && $holiday_set)
      {
        $array[$todays]['show'] = 4;
      }
      else
      {
        $array[$todays]['show'] = 1;
      }
    }
    // 来月の日付を曜日ごとに入れ込む
    $i = 0;
    $n = 0;
    if ($next_fast_week != 0)
    {
      $n = 1;
      for ($i = $next_fast_week; $i < 7; $i++)
      {
        $today = sprintf('%04d-%02d-%02d', $next_year, $next_month, $n);
        $array[$today]['year'] = $next_year;
        $array[$today]['month'] = $next_month;
        $array[$today]['day'] = $n;
        $array[$today]['week'] = date("w", mktime(0, 0, 0, intval($next_month), intval($n), intval($next_year)));
        $array[$today]['show'] = 5;
        $n++;
      }
    }
    ksort($array);
    $i = 1;
    $n = 1;
    foreach($array as $day => $data)
    {
      if ($n >= 7)
      {
        $_array[$i][$day] = $data;
        $n = 1;
        $i++;
      }
      else
      {
        $_array[$i][$day] = $data;
        $n++;
      }
    }
    return $_array;
  }
}

function get_calendar_list($value = null)
{
  $year = '';
  $month = '';
  $day = '';
  $today = '';
  $calendar_list = array();

  if (!isset($value)) $value = date('Y-m-d');
  $calendar_list = calendar($value);
  return $calendar_list;
}